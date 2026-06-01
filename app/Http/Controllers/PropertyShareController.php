<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\DealsProductStatus;
use App\Models\CrmDeal;
use App\Models\CrmDealProduct;
use App\Models\CrmDealProductBooking;
use App\Models\CrmLeadActivity;
use App\Models\Customer;
use App\Services\CrmSendPropertyService;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Trang public cho khách xem "gói BĐS" của một Deal qua mã share (/s/{code}).
 * KHÔNG yêu cầu đăng nhập. Tuyệt đối không lộ PII chủ nhà/broker.
 */
class PropertyShareController extends Controller
{
    public function __construct(protected CrmSendPropertyService $svc)
    {
    }

    /**
     * Hiển thị trang gói BĐS theo share_code.
     */
    public function show(string $code)
    {
        $deal = CrmDeal::with(['products.property.category', 'products.property.ward', 'products.property.street', 'products.property.parameters', 'lead', 'customer'])
            ->where('share_code', $code)
            ->firstOrFail();

        // Content types đã gửi của từng product (đọc từ activity property_sent gần nhất).
        $contentTypesByProduct = $this->contentTypesByProduct($deal);

        $properties = $deal->products
            ->filter(fn ($dp) => $dp->property !== null)
            ->map(fn ($dp) => $this->svc->buildPublicProperty($dp, $contentTypesByProduct[$dp->id] ?? ['full']))
            ->filter()
            ->values();

        $customerName = optional($deal->customer)->full_name ?? 'Quý khách';

        // Payload gọn cho bản đồ (share-map.js). idx (1-based) khớp id="prop-N" của card.
        $fallbackImg = asset('images/all/1.jpg');
        $mapMarkers = $properties->values()->map(function ($p, $i) use ($fallbackImg) {
            return [
                'idx'      => $i + 1,
                'title'    => $p['title'],
                'location' => $p['location'],
                'price'    => $p['price'] ?: 'Giá thỏa thuận',
                'category' => $p['category'],
                'type'     => $p['type_label'],
                'img'      => ! empty($p['gallery']) ? $p['gallery'][0] : ($p['title_image'] ?: $fallbackImg),
                'lat'      => $p['latitude'] ? (float) $p['latitude'] : null,
                'lng'      => $p['longitude'] ? (float) $p['longitude'] : null,
            ];
        })->all();

        return view('share.property', [
            'code'         => $code,
            'dealId'       => $deal->id,
            'customerName' => $customerName,
            'properties'   => $properties,
            'mapMarkers'   => $mapMarkers,
        ]);
    }

    /**
     * JS beacon: ghi nhận khách đã mở link (idempotent theo cookie + dedupe thời gian).
     */
    public function seen(Request $request, string $code): JsonResponse
    {
        $deal = CrmDeal::where('share_code', $code)->first();
        if (! $deal) {
            return response()->json(['ok' => false], 404);
        }

        $cookieKey = 'share_seen_'.$deal->id;
        if ($request->cookie($cookieKey)) {
            return response()->json(['ok' => true])->header('X-Seen', 'cached');
        }

        // Dedupe: chỉ ghi nếu chưa có activity share_viewed trong 30 phút gần đây.
        $recent = CrmLeadActivity::where('lead_id', $deal->lead_id)
            ->where('type', 'share_viewed')
            ->where('created_at', '>=', now()->subMinutes(30))
            ->exists();

        if (! $recent) {
            CrmLeadActivity::create([
                'lead_id'  => $deal->lead_id,
                'actor_id' => null,
                'type'     => 'share_viewed',
                'content'  => 'Khách đã mở link xem BĐS',
                'metadata' => ['deal_id' => $deal->id, 'share_code' => $code],
            ]);
        }

        return response()->json(['ok' => true])->cookie($cookieKey, '1', 60 * 24); // 1 ngày
    }

    /**
     * Khách phản hồi về một BĐS trong gói.
     */
    public function feedback(Request $request, string $code): JsonResponse
    {
        $deal = CrmDeal::with('lead')->where('share_code', $code)->first();
        if (! $deal) {
            return response()->json(['success' => false, 'message' => 'Link không hợp lệ'], 404);
        }

        $validator = Validator::make($request->all(), [
            'product_id'   => 'required|integer',
            'type'         => 'required|in:interested,not_suitable,book_viewing',
            'reason'       => 'nullable|string|max:500',
            'booking_date' => 'nullable|date',
            'booking_time' => 'nullable|string|max:10',
            'contact'      => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Product phải thuộc deal này.
        $product = CrmDealProduct::with('property')
            ->where('id', (int) $request->product_id)
            ->where('deal_id', $deal->id)
            ->first();

        if (! $product) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy BĐS'], 404);
        }

        $type = $request->input('type');
        $reason = trim((string) $request->input('reason', ''));

        try {
            $bookingInfo = null;

            switch ($type) {
                case 'interested':
                    $product->status = DealsProductStatus::CUSTOMER_FEEDBACK;
                    $product->save();
                    $contentLabel = 'Khách quan tâm BĐS';
                    break;

                case 'not_suitable':
                    $product->status = DealsProductStatus::VIEWED_FAILED;
                    if ($reason !== '') {
                        $product->reason_dont_like = $reason;
                    }
                    $product->save();
                    $contentLabel = 'Khách thấy không phù hợp'.($reason !== '' ? ': '.$reason : '');
                    break;

                case 'book_viewing':
                    $product->status = DealsProductStatus::BOOKING_CREATED;
                    $product->save();

                    $booking = CrmDealProductBooking::create([
                        'crm_deals_products_id' => $product->id,
                        'booking_date'          => $request->input('booking_date') ?: now()->addDay()->toDateString(),
                        'booking_time'          => $request->input('booking_time') ?: null,
                        'status'                => BookingStatus::SCHEDULED,
                        'customer_feedback'     => $reason !== '' ? $reason : null,
                    ]);
                    $bookingInfo = [
                        'date' => $booking->booking_date->format('d/m/Y'),
                        'time' => $booking->booking_time,
                    ];
                    $contentLabel = 'Khách đặt lịch xem BĐS'
                        .($bookingInfo['date'] ? ' ngày '.$bookingInfo['date'] : '')
                        .($bookingInfo['time'] ? ' lúc '.$bookingInfo['time'] : '');
                    break;

                default:
                    return response()->json(['success' => false, 'message' => 'Loại phản hồi không hợp lệ'], 422);
            }

            // Ghi activity vào CRM.
            CrmLeadActivity::create([
                'lead_id'  => $deal->lead_id,
                'actor_id' => null,
                'type'     => 'customer_feedback',
                'content'  => $contentLabel,
                'metadata' => [
                    'deal_id'     => $deal->id,
                    'product_id'  => $product->id,
                    'property_id' => $product->property_id,
                    'feedback'    => $type,
                    'reason'      => $reason,
                    'booking'     => $bookingInfo,
                    'contact'     => $request->input('contact'),
                ],
            ]);

            // Báo sale qua Telegram.
            $this->notifySaleFeedback($deal, $product, $type, $reason, $bookingInfo);

            return response()->json(['success' => true, 'message' => 'Cảm ơn phản hồi của bạn!']);
        } catch (\Throwable $e) {
            Log::error('share feedback failed: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống, thử lại sau'], 500);
        }
    }

    /**
     * Map product_id → content_types đã gửi (lấy từ activity property_sent gần nhất).
     */
    protected function contentTypesByProduct(CrmDeal $deal): array
    {
        $activities = CrmLeadActivity::where('lead_id', $deal->lead_id)
            ->where('type', 'property_sent')
            ->orderBy('created_at', 'desc')
            ->get(['metadata']);

        $map = [];
        foreach ($activities as $act) {
            $meta = $act->metadata ?? [];
            $pid = $meta['product_id'] ?? null;
            if ($pid !== null && ! isset($map[$pid])) {
                $map[$pid] = $meta['content_types'] ?? ['full'];
            }
        }

        return $map;
    }

    /**
     * Gửi Telegram cho sale phụ trách khi khách phản hồi.
     */
    protected function notifySaleFeedback(CrmDeal $deal, CrmDealProduct $product, string $type, string $reason, ?array $bookingInfo): void
    {
        try {
            $lead = $deal->lead;
            if (! $lead) {
                return;
            }

            $saleId = $lead->sale_id ?: $lead->user_id;
            if (! $saleId) {
                return;
            }

            $sale = Customer::find($saleId);
            if (! $sale || empty($sale->telegram_id)) {
                return;
            }

            $title = optional($product->property)->title
                ?: (optional($product->property)->title_by_address ?? ('BĐS #'.$product->property_id));

            $verb = match ($type) {
                'interested'   => '❤️ QUAN TÂM',
                'not_suitable' => '👎 thấy KHÔNG phù hợp',
                'book_viewing' => '📅 ĐẶT LỊCH XEM',
                default        => 'phản hồi',
            };

            $msg = "🔔 Khách của Deal #{$deal->id} vừa {$verb}\n🏠 {$title}";
            if ($type === 'not_suitable' && $reason !== '') {
                $msg .= "\nLý do: {$reason}";
            }
            if ($type === 'book_viewing' && $bookingInfo) {
                $msg .= "\nLịch: ".($bookingInfo['date'] ?? '').' '.($bookingInfo['time'] ?? '');
            }

            app(NotificationService::class)->sendDirectTo((string) $sale->telegram_id, $msg, ['parse_mode' => '']);
        } catch (\Throwable $e) {
            Log::warning('notifySaleFeedback failed: '.$e->getMessage());
        }
    }
}
