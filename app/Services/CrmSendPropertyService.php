<?php

namespace App\Services;

use App\Enums\DealsProductStatus;
use App\Models\CrmDeal;
use App\Models\CrmDealProduct;
use App\Models\CrmLead;
use App\Models\CrmLeadActivity;
use App\Models\Customer;
use App\Models\Property;
use Illuminate\Support\Collection;

/**
 * Xử lý nghiệp vụ "Gửi BĐS cho khách":
 * - Đảm bảo lead có Deal (mỗi Deal mang một share_code public).
 * - Ghi nhận từng BĐS đã gửi vào crm_deals_products + crm_lead_activities.
 * - Dựng payload Zalo (text + ảnh) cho sale copy/tải.
 * - Dựng read-model public (ẩn PII) cho trang /s/{code}.
 *
 * Các content type hợp lệ: full | location | legal | gallery (khớp checkbox UI).
 */
class CrmSendPropertyService
{
    public const CONTENT_TYPES = ['full', 'location', 'legal', 'gallery'];

    /**
     * Trả về deal hiện có của lead hoặc tạo mới (status 'new').
     * Ghi activity status_change khi tạo, đồng bộ pattern CrmLeadController::createDeal.
     */
    public function getOrCreateDealForLead(CrmLead $lead, Customer $actor): CrmDeal
    {
        if ($lead->deal) {
            return $lead->deal;
        }

        $deal = CrmDeal::create([
            'lead_id'     => $lead->id,
            'customer_id' => $lead->customer_id,
            'status'      => 'new',
            'amount'      => 0,
        ]);

        // Chuyển lead sang converted nếu chưa (status accessor đã humanize khi đọc).
        if ($lead->getRawOriginal('status') !== 'converted') {
            $lead->status = 'converted';
            $lead->save();
        }

        CrmLeadActivity::create([
            'lead_id'  => $lead->id,
            'actor_id' => $actor->id,
            'type'     => 'status_change',
            'content'  => 'Tạo Deal từ Lead khi gửi BĐS cho khách',
        ]);

        // Nạp lại quan hệ để các lần đọc tiếp theo thấy deal.
        $lead->setRelation('deal', $deal);

        return $deal;
    }

    /**
     * Sinh (lazy) share_code duy nhất cho deal, persist và trả về.
     */
    public function ensureShareCode(CrmDeal $deal): string
    {
        if (! empty($deal->share_code)) {
            return $deal->share_code;
        }

        do {
            $code = bin2hex(random_bytes(6)); // 12 ký tự hex, khó đoán
        } while (CrmDeal::where('share_code', $code)->exists());

        $deal->share_code = $code;
        $deal->save();

        return $code;
    }

    /**
     * Upsert một BĐS vào deal (cho phép gửi lại) + ghi activity.
     * Không hạ cấp status đã tiến xa hơn các trạng thái "đã gửi".
     */
    public function recordSend(
        CrmDeal $deal,
        Property $property,
        array $contentTypes,
        ?string $note,
        Customer $actor,
        ?string $shareCode = null
    ): CrmDealProduct {
        $contentTypes = $this->normalizeContentTypes($contentTypes);
        $targetStatus = $this->statusForContentTypes($contentTypes);

        $product = CrmDealProduct::firstOrNew([
            'deal_id'     => $deal->id,
            'property_id' => $property->id,
        ]);

        // Chỉ set status khi tạo mới hoặc khi đang ở giai đoạn "đã gửi"
        // (tránh kéo lùi BĐS đã ở viewed_success/negotiating...).
        $currentRaw = $product->exists ? $product->getRawOriginal('status') : null;
        if (! $product->exists || $this->isSentStage($currentRaw)) {
            $product->status = $targetStatus;
        }

        if ($note !== null && $note !== '') {
            $product->note = $note;
        }

        $product->save();

        CrmLeadActivity::create([
            'lead_id'  => $deal->lead_id,
            'actor_id' => $actor->id,
            'type'     => 'property_sent',
            'content'  => 'Gửi BĐS "'.($property->title ?: $property->title_by_address).'" cho khách',
            'metadata' => [
                'deal_id'       => $deal->id,
                'product_id'    => $product->id,
                'property_id'   => $property->id,
                'content_types' => $contentTypes,
                'share_code'    => $shareCode ?? $deal->share_code,
            ],
        ]);

        return $product;
    }

    /**
     * Payload để sale gửi thủ công qua Zalo: text + danh sách ảnh + link share.
     * zalo_text là plain text (KHÔNG escape Markdown).
     */
    public function buildZaloPayload(
        Collection $properties,
        array $contentTypes,
        ?string $note,
        string $shareCode
    ): array {
        $contentTypes = $this->normalizeContentTypes($contentTypes);
        $shareUrl = url('/s/'.$shareCode);

        $blocks = [];
        $images = [];

        foreach ($properties as $prop) {
            $title = $prop->title ?: $prop->title_by_address;
            $price = $prop->formatted_prices ?? '';
            $area = $prop->area ? $prop->area.' m²' : '';
            $location = $prop->address_location ?? '';

            $lines = ['🏠 '.$title];
            if ($price) {
                $lines[] = '💰 Giá: '.$price;
            }
            if ($area) {
                $lines[] = '📐 Diện tích: '.$area;
            }
            if ($location) {
                $lines[] = '📍 '.$location;
            }

            if (in_array('location', $contentTypes, true) && $prop->latitude && $prop->longitude) {
                $lines[] = '🗺 Bản đồ: https://www.google.com/maps?q='.$prop->latitude.','.$prop->longitude;
            }

            $lines[] = '👉 Xem chi tiết: '.$shareUrl;
            $blocks[] = implode("\n", $lines);

            // Ảnh BĐS
            if (in_array('full', $contentTypes, true) || in_array('gallery', $contentTypes, true)) {
                $images = array_merge($images, $this->galleryUrls($prop));
            }
            // Ảnh pháp lý — chỉ khi sale tick legal
            if (in_array('legal', $contentTypes, true)) {
                $images = array_merge($images, $this->legalUrls($prop));
            }
        }

        $text = implode("\n\n— — —\n\n", $blocks);
        if ($note !== null && $note !== '') {
            $text .= "\n\n💬 ".$note;
        }

        return [
            'zalo_text' => $text,
            'images'    => array_values(array_unique(array_filter($images))),
            'share_url' => $shareUrl,
        ];
    }

    /**
     * Read-model whitelist cho trang public /s/{code} — KHÔNG lộ PII chủ nhà/broker.
     * $contentTypes lấy từ lần gửi (lưu trong activity/note) để quyết hiện pháp lý.
     */
    public function buildPublicProperty(CrmDealProduct $dp, array $contentTypes): array
    {
        $contentTypes = $this->normalizeContentTypes($contentTypes);
        $prop = $dp->property;

        if (! $prop) {
            return [];
        }

        $showLegal = in_array('legal', $contentTypes, true);

        return [
            'product_id'  => $dp->id,
            'property_id' => $prop->id,
            'title'       => $prop->title ?: $prop->title_by_address,
            'price'       => $prop->formatted_prices ?? '',
            'area'        => $prop->area ? $prop->area.' m²' : '',
            'location'    => $prop->address_location ?? '',
            'description' => $prop->description ?? '',
            'category'    => optional($prop->category)->category ?? '',
            'type_label'  => (int) $prop->property_type === 1 ? 'Cho thuê' : 'Bán',
            'bedrooms'    => $prop->number_room ?: null,
            'bathrooms'   => $prop->bathroom ?: null,
            'floors'      => $prop->number_floor ?: null,
            'title_image' => $prop->title_image ?: null,
            'gallery'     => $this->galleryUrls($prop),
            'legal'       => $showLegal ? $this->legalUrls($prop) : [],
            'show_legal'  => $showLegal,
            'latitude'    => $prop->latitude ?: null,
            'longitude'   => $prop->longitude ?: null,
            'status'      => $dp->getRawOriginal('status'),
        ];
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    /** Lọc content type về tập hợp lệ; mặc định ['full']. */
    public function normalizeContentTypes(array $contentTypes): array
    {
        $clean = array_values(array_intersect(self::CONTENT_TYPES, $contentTypes));

        return $clean ?: ['full'];
    }

    /** Map mảng content type về 1 status (stage xa nhất). */
    private function statusForContentTypes(array $contentTypes): DealsProductStatus
    {
        if (in_array('legal', $contentTypes, true)) {
            return DealsProductStatus::SENT_LEGAL;
        }
        if (in_array('location', $contentTypes, true)) {
            return DealsProductStatus::SENT_LOCATION;
        }

        return DealsProductStatus::SENT_INFO;
    }

    /** Status hiện tại có còn ở giai đoạn "đã gửi" (cho phép cập nhật) không? */
    private function isSentStage(?string $raw): bool
    {
        return $raw === null || in_array($raw, [
            DealsProductStatus::SENT_INFO->value,
            DealsProductStatus::SENT_LOCATION->value,
            DealsProductStatus::SENT_LEGAL->value,
        ], true);
    }

    /** Danh sách URL ảnh gallery của BĐS. */
    private function galleryUrls(Property $prop): array
    {
        $base = url('').config('global.IMG_PATH').config('global.PROPERTY_GALLERY_IMG_PATH').$prop->id.'/';

        return collect($prop->getGalleryAttribute())
            ->filter(fn ($img) => ! empty($img['image']))
            ->map(fn ($img) => $base.$img['image'])
            ->values()->all();
    }

    /** Danh sách URL ảnh pháp lý của BĐS. */
    private function legalUrls(Property $prop): array
    {
        $base = url('').config('global.IMG_PATH').config('global.PROPERTY_GALLERY_IMG_PATH').$prop->id.'/';

        return collect($prop->getLegalImagesAttribute())
            ->filter(fn ($img) => ! empty($img['image']))
            ->map(fn ($img) => $base.$img['image'])
            ->values()->all();
    }
}
