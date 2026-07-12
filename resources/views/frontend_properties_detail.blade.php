@extends('frontends.ltbs.master')

@section('title', $property->title . ' — Đà Lạt BĐS')
@section('meta_description', Str::limit(strip_tags($property->description), 160))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ltbs-detail.css') }}">
@endpush

@php
    /** @var \App\Models\Property $property */
    // ── Role gating (server-side; sensitive data NOT emitted to non-privileged roles) ──
    $u = auth('webapp')->user();
    $role = $u->role ?? 'guest';
    $isAdmin = in_array($role, ['admin', 'bds_admin']);
    $isSale = in_array($role, ['sale', 'sale_admin']);
    $canSeeExact = $isAdmin; // exact coords + owner contact only for admins
    $verified = !empty($property->approved_at);

    // EAV spec reader
    $specVal = function ($pid) use ($property) {
        if (!$pid) return null;
        return optional($property->assignParameter->firstWhere('parameter_id', $pid))->value;
    };
    $area = $property->area ?? $specVal(config('global.area'));
    $beds = $property->number_room ?? $specVal(config('global.number_room'));
    $baths = $property->bathroom ?? $specVal(config('global.bathroom'));
    $floors = $specVal(config('global.number_floor'));
    $legal = $specVal(config('global.legal'));
    $direction = $specVal(config('global.direction'));

    // Gallery
    $gallery = collect($property->gallery ?? []);
    $images = $gallery->pluck('image_url')->filter()->values();
    if ($images->isEmpty()) {
        $images = collect([$property->title_image]);
    }
    $mainImage = $property->title_image ?: $images->first();

    $statusMap = [1 => ['Đang mở bán', 'open'], 4 => ['Đã bán', 'sold'], 5 => ['Đã cho thuê', 'sold']];
    [$statusLabel, $statusCls] = $statusMap[$property->status] ?? ['Đang mở bán', 'open'];
@endphp

@section('content')
    <main class="wrapc">
        {{-- Breadcrumb --}}
        <div class="crumbs">
            <a href="{{ route('index') }}">Trang chủ</a><span class="sep">/</span>
            <a href="{{ route('properties.index') }}">{{ $property->type }}</a><span class="sep">/</span>
            @if ($property->ward)<a href="{{ route('properties.index', ['ward' => $property->ward_code]) }}">{{ $property->ward->full_name }}</a><span class="sep">/</span>@endif
            <span class="cur">{{ $property->code }}</span>
        </div>

        {{-- Title row --}}
        <div class="titlerow">
            <div class="tl-left">
                <div class="tl-badges">
                    <span class="sbadge {{ $statusCls }}"><svg viewBox="0 0 24 24" class="ic ic14"><circle cx="12" cy="12" r="4" fill="currentColor" stroke="none"></circle></svg>{{ $statusLabel }}</span>
                    <span class="tl-code">Mã tin {{ $property->code }}</span>
                    @if ($verified)
                        <span class="tl-verify"><svg viewBox="0 0 24 24" class="ic ic14"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>Đã xác minh</span>
                    @endif
                </div>
                <h1 class="tl-title">{{ $property->title }}</h1>
                <div class="tl-addr"><svg viewBox="0 0 24 24" class="ic ic16"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>{{ $property->address_location ?: 'TP Đà Lạt' }}</div>
            </div>
            <div class="tl-actions">
                <button class="actbtn" onclick="LTBS.toggleFav({{ $property->id }}, this)"><svg viewBox="0 0 24 24" class="ic ic17"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>Lưu tin</button>
                <button class="actbtn" onclick="LTBSDetail.share()"><svg viewBox="0 0 24 24" class="ic ic17"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>Chia sẻ</button>
            </div>
        </div>

        {{-- Gallery --}}
        <div class="gallery">
            <div class="gcell main" onclick="LTBSDetail.lightbox(0)"><img src="{{ $mainImage }}" alt="{{ $property->title }}">
                <div class="gchips"><span class="gchip"><svg viewBox="0 0 24 24" class="ic ic14"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>{{ max($images->count(), 1) }} ảnh</span></div>
            </div>
            <div class="gcell" onclick="LTBSDetail.lightbox(1)"><img src="{{ $images->get(1, $mainImage) }}" alt=""></div>
            <div class="gcell" onclick="LTBSDetail.lightbox(2)"><img src="{{ $images->get(2, $mainImage) }}" alt="">
                @if ($images->count() > 3)<div class="gmore"><svg viewBox="0 0 24 24" class="ic ic24" style="stroke:#fff"><rect x="3" y="3" width="18" height="18" rx="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>Xem tất cả</div>@endif
            </div>
        </div>

        <div class="layout">
            <div class="mcol">
                {{-- Price + 8 facts --}}
                <div class="card">
                    <div class="pricewrap">
                        <div class="price">{{ $property->formatted_prices }}</div>
                        @if ($property->avg_price_per_m2 ?? false)<div class="ppm">Đơn giá <b>{{ $property->avg_price_per_m2 }}</b></div>@endif
                    </div>
                    <div class="facts">
                        <div class="fact"><svg viewBox="0 0 24 24" class="ic ic22"><polyline points="15 3 21 3 21 9"></polyline><polyline points="9 21 3 21 3 15"></polyline><line x1="21" y1="3" x2="14" y2="10"></line><line x1="3" y1="21" x2="10" y2="14"></line></svg><div><div class="fact-l">Diện tích</div><div class="fact-v">{{ $area ?? '—' }} m²</div></div></div>
                        <div class="fact"><svg viewBox="0 0 24 24" class="ic ic22"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"></path><path d="M2 15h20"></path></svg><div><div class="fact-l">Phòng ngủ</div><div class="fact-v">{{ $beds ?? '—' }} PN</div></div></div>
                        <div class="fact"><svg viewBox="0 0 24 24" class="ic ic22"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg><div><div class="fact-l">Phòng tắm</div><div class="fact-v">{{ $baths ?? '—' }} PT</div></div></div>
                        <div class="fact"><svg viewBox="0 0 24 24" class="ic ic22"><circle cx="12" cy="12" r="10"></circle><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon></svg><div><div class="fact-l">Hướng</div><div class="fact-v">{{ $direction ?? '—' }}</div></div></div>
                        <div class="fact"><svg viewBox="0 0 24 24" class="ic ic22"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg><div><div class="fact-l">Số tầng</div><div class="fact-v">{{ $floors ?? '—' }}</div></div></div>
                        <div class="fact"><svg viewBox="0 0 24 24" class="ic ic22"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg><div><div class="fact-l">Pháp lý</div><div class="fact-v">{{ $legal ?? '—' }}</div></div></div>
                        <div class="fact"><svg viewBox="0 0 24 24" class="ic ic22"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"></path></svg><div><div class="fact-l">Loại hình</div><div class="fact-v">{{ $property->category->category ?? '—' }}</div></div></div>
                        <div class="fact"><svg viewBox="0 0 24 24" class="ic ic22"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg><div><div class="fact-l">Đăng</div><div class="fact-v">{{ $property->created_at?->diffForHumans() }}</div></div></div>
                    </div>
                </div>

                {{-- Location (role-aware) --}}
                <div class="card">
                    <div class="card-h"><svg viewBox="0 0 24 24" class="ic"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg><h2>Vị trí</h2><span class="hnote">{{ $canSeeExact ? 'Toạ độ chính xác' : 'Khu vực ước tính' }}</span></div>
                    @if ($canSeeExact && $property->latitude && $property->longitude)
                        <div id="detailMap" style="height:300px;border-radius:var(--radius-md);overflow:hidden;border:1px solid var(--border)"></div>
                        <div class="map-addr" style="margin-top:10px">
                            <svg viewBox="0 0 24 24" class="ic ic22"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <div><b>{{ $property->address ?: $property->address_location }}</b><br><span>Toạ độ: {{ $property->latitude }}, {{ $property->longitude }} · chỉ hiển thị cho vai trò có quyền</span></div>
                        </div>
                    @else
                        <div class="map"><div class="map-grid"></div>
                            <div class="zone-lbl"><svg viewBox="0 0 24 24" class="ic ic14"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>Khu vực ước tính · {{ $property->ward->full_name ?? 'TP Đà Lạt' }}</div>
                            <div class="zone"></div>
                            <div class="map-lockbar"><svg viewBox="0 0 24 24" class="ic ic22" style="stroke:#fff"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg><div><b>Địa chỉ chính xác được bảo vệ</b><span>{{ $isSale ? 'Mở khoá khi bạn được admin phân bổ vai trò trong deal.' : 'Đăng ký / đăng nhập môi giới để làm việc với tin này.' }}</span></div></div>
                        </div>
                    @endif
                </div>

                {{-- Zoning CTA --}}
                <div class="qh-cta">
                    <span class="qh-cta-ic"><svg viewBox="0 0 24 24" class="ic ic22" style="stroke:#fff"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg></span>
                    <div class="qh-cta-body"><div class="qh-cta-t">Đất này có dính quy hoạch không?</div><div class="qh-cta-s">Tra cứu miễn phí dữ liệu quy hoạch công bố cho thửa đất này.</div></div>
                    <a class="ds-btn ds-btn-white ds-btn-md" href="{{ route('index') }}#quyhoach">Kiểm tra quy hoạch</a>
                </div>

                {{-- Description --}}
                <div class="card">
                    <div class="card-h"><svg viewBox="0 0 24 24" class="ic"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg><h2>Mô tả chi tiết</h2></div>
                    <div class="desc">{!! nl2br(e($property->description)) !!}</div>
                </div>

                {{-- Sale/Admin panel (UI shell; interactive pipeline/commission = TODO backend) --}}
                @if ($isSale || $isAdmin)
                    <div class="rolecard">
                        <div class="rc-head {{ $isAdmin ? 'admin' : 'sale' }}">
                            <span class="rc-ic"><svg viewBox="0 0 24 24" class="ic ic22" style="stroke:#fff"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></span>
                            <div><h2>{{ $isAdmin ? 'Bảng quản trị' : 'Bảng làm việc của tôi' }}</h2><p>{{ $u->name ?? '' }} · {{ ucfirst($role) }}</p></div>
                        </div>
                        <div class="rc-body">
                            @if ($isAdmin)
                                <div class="owner-contact">
                                    <div class="cl-title" style="color:var(--success)"><svg viewBox="0 0 24 24" class="ic ic17"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>Liên hệ chủ nhà</div>
                                    <div class="oc-row"><svg viewBox="0 0 24 24" class="ic ic17"><circle cx="12" cy="8" r="4"></circle><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"></path></svg>{{ $property->host->name ?? '—' }}</div>
                                    <div class="oc-row"><svg viewBox="0 0 24 24" class="ic ic17"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.9a16 16 0 0 0 6 6l.92-.92a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7a2 2 0 0 1 1.72 2.02z"></path></svg><b>{{ $property->host->contact ?? '—' }}</b></div>
                                </div>
                            @endif
                            <p class="pipe-hint" style="margin-top:14px">Pipeline chăm khách, claim vai trò và phân bổ hoa hồng cho tin này đang được phát triển. {{-- TODO(backend): API pipeline/claim/commission theo tin. --}}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="aside">
                <div class="broker">
                    <div class="broker-top">
                        <img class="ds-avatar" style="width:48px;height:48px" src="{{ $property->agent->profile ?? asset('images/ltbs-logo.svg') }}" alt="">
                        <div><div class="broker-role">Môi giới phụ trách</div><div style="font-weight:700">{{ $property->agent->name ?? 'Đà Lạt BĐS' }}</div></div>
                    </div>
                    @if ($canSeeExact)
                        <div class="owner-contact" style="margin-top:14px">
                            <div class="oc-row"><b>SĐT chủ: {{ $property->host->contact ?? '—' }}</b></div>
                        </div>
                    @elseif ($isSale)
                        <div class="contact-locked">
                            <div class="cl-title"><svg viewBox="0 0 24 24" class="ic ic17"><rect x="3" y="11" width="18" height="11" rx="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>Liên hệ chủ nhà</div>
                            <p class="cl-desc">Thông tin chủ được bảo vệ để chống cắt cầu. Cần được admin phân bổ vai trò trước khi mở khoá.</p>
                        </div>
                    @else
                        <a class="ds-btn ds-btn-solid ds-btn-md ds-btn-block" style="margin-top:14px" href="{{ route('webapp') }}">Đăng ký để liên hệ</a>
                    @endif
                    <div class="viewstat" style="margin-top:14px">
                        <span>{{ $property->total_click ?? 0 }} lượt xem</span> · <span>Đăng {{ $property->created_at?->diffForHumans() }}</span>
                    </div>
                </div>
            </aside>
        </div>

        {{-- Related --}}
        @if (($relatedProducts ?? collect())->isNotEmpty())
            <div class="rel">
                <div class="rel-head"><h2>BĐS liên quan</h2></div>
                <div class="rgrid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px">
                    @foreach ($relatedProducts->take(3) as $rp)
                        @include('frontends.ltbs.components.property_card', ['property' => $rp])
                    @endforeach
                </div>
            </div>
        @endif
    </main>

    {{-- Sticky CTA bar --}}
    <div class="ctabar">
        <div class="wrapc ctabar-in">
            <div class="ctabar-price"><b>{{ $property->formatted_prices }}</b> · {{ $property->address_location }}</div>
            <div class="ctabar-actions">
                <button class="ds-btn ds-btn-outline ds-btn-md" onclick="LTBS.toggleFav({{ $property->id }}, this)">Lưu tin</button>
                <a class="ds-btn ds-btn-solid ds-btn-md" href="{{ route('webapp') }}">Liên hệ ngay</a>
            </div>
        </div>
    </div>

    @php
        $galleryJson = $images->values()->all();
    @endphp
    <script>
        window.LTBS_GALLERY = {!! json_encode($galleryJson, JSON_UNESCAPED_UNICODE) !!};
        @if ($canSeeExact && $property->latitude && $property->longitude)
        window.LTBS_DETAIL_LATLNG = { lat: {{ (float) $property->latitude }}, lng: {{ (float) $property->longitude }}, title: @json($property->title) };
        @endif
    </script>
@endsection

@push('scripts')
    <script src="{{ asset('js/ltbs-detail.js') }}"></script>
    @if ($canSeeExact && $property->latitude && $property->longitude)
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.place_api_key') }}&libraries=marker&loading=async&callback=LTBSDetailMapInit" async defer></script>
    @endif
@endpush
