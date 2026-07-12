@extends('frontends.ltbs.master')

@section('title', 'Đà Lạt BĐS — Mạng lưới thổ địa Đà Lạt')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ltbs-home.css') }}">
@endpush

@section('content')
    {{-- ══════════ HERO + SEARCH ══════════ --}}
    <section class="hero">
        <div class="hero-slides"><div class="hero-slide on" style="background-image:url('{{ asset('images/ltbs-hero.jpg') }}')"></div></div>
        <div class="hero-scrim"></div>
        <div class="hero-glow" style="width:520px;height:520px;background:#dbe7ff;top:-160px;right:-60px;opacity:.4;z-index:1"></div>
        <div class="wrapc hero-in">
            <span class="hero-eyebrow">Người Đà Lạt. BĐS Đà Lạt.</span>
            <h1>An cư giữa <em>thông và sương</em> Đà Lạt</h1>
            <p class="hero-sub">Nền tảng BĐS thổ địa Đà Lạt — mỗi tin đều xác minh pháp lý.</p>

            @include('frontends.ltbs.components.search_widget')

            <div class="wardchips">
                <span class="wl">Phổ biến:</span>
                @foreach ($locationsWards->take(6) as $w)
                    <a class="ward" href="{{ route('properties.index', ['ward' => $w->code]) }}">{{ $w->full_name }}</a>
                @endforeach
            </div>
            <div class="trustbar">
                <span class="tb">
                    <svg viewBox="0 0 24 24" class="ds-ic ds-ic-14" style="stroke:var(--success);stroke-width:2.6"><path d="M20 6 9 17l-5-5"></path></svg>
                    Mỗi tin đều xác minh pháp lý &amp; quy hoạch
                </span>
            </div>
        </div>
    </section>

    {{-- ══════════ FEATURED LISTINGS ══════════ --}}
    <section class="sec" id="listings">
        <div class="wrapc">
            <div class="sec-head sec-head-row">
                <div>
                    <span class="eyebrow">Đang lên sóng</span>
                    <h2 class="h2">BĐS nổi bật tại Đà Lạt</h2>
                    <p class="hc-sub">Tin mới nhất trên hệ thống — đã qua kiểm duyệt</p>
                </div>
                <a class="hc-all" href="{{ route('properties.index') }}">Tất cả
                    <svg viewBox="0 0 24 24" class="ds-ic ds-ic-16"><path d="M9 18l6-6-6-6"></path></svg>
                </a>
            </div>
            @if ($newestProducts->isEmpty())
                <p class="lead">Chưa có tin nào. Hãy quay lại sau nhé.</p>
            @else
                <div class="hc-row">
                    <button type="button" class="hc-arrow prev" id="hotPrev" aria-label="Xem tin trước">
                        <svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><path d="M15 18l-6-6 6-6"></path></svg>
                    </button>
                    <div class="hc-track" id="hotTrack">
                        @foreach ($newestProducts as $property)
                            <div style="flex:0 0 300px;width:300px">
                                @include('frontends.ltbs.components.property_card', ['property' => $property])
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="hc-arrow next" id="hotNext" aria-label="Xem tin tiếp">
                        <svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><path d="M9 18l6-6-6-6"></path></svg>
                    </button>
                </div>
                <p class="hc-hint">vuốt ngang để xem thêm</p>
            @endif
        </div>
    </section>

    {{-- ══════════ ZONING CHECK (UI final, result stub) ══════════ --}}
    @include('frontends.ltbs.components.zoning_sheet')

    {{-- ══════════ MARKET PULSE ══════════ --}}
    <section class="sec market-tech" id="market">
        <div class="mesh" style="-webkit-mask-image:none;mask-image:none;opacity:.6"></div>
        <div class="hero-glow" style="width:460px;height:460px;background:#3270fc;top:-160px;right:-120px;opacity:.3"></div>
        <div class="wrapc">
            <div class="sec-head sec-head-row">
                <div>
                    <span class="eyebrow">Nhịp đập thị trường</span>
                    <h2 class="h2">Số tin theo phường</h2>
                    <div class="mkt-provenance">
                        <span class="mkt-check"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-14" style="stroke:var(--primary);stroke-width:3"><path d="M20 6 9 17l-5-5"></path></svg></span>
                        Tổng hợp từ tin đang đăng trên hệ thống
                    </div>
                </div>
                <span class="mkt-note"><span class="livedot"></span>Cập nhật hôm nay</span>
            </div>
            <div class="mktgrid">
                @forelse ($wardStats as $ws)
                    <a class="mkt-tile" href="{{ route('properties.index', ['ward' => $ws['code']]) }}">
                        <div class="ma">{{ $ws['name'] }}</div>
                        <div class="mp">{{ $ws['count'] }} tin</div>
                        <div class="mkt-link">Xem BĐS
                            <svg viewBox="0 0 24 24" class="ds-ic ds-ic-14"><path d="M9 18l6-6-6-6"></path></svg>
                        </div>
                    </a>
                @empty
                    <p class="lead">Chưa có dữ liệu theo phường.</p>
                @endforelse
            </div>
            {{-- TODO(backend): "Giá trung bình theo phường" + máy tính ngân sách cần bảng market_prices
                 (route admin webapp.admin.market-prices.* đã có). Hiện hiển thị số tin thực. --}}
            <div class="mkt-cta">
                <div>
                    <h3 class="mkt-cta-title">Đất của bạn đang trị giá bao nhiêu?</h3>
                    <p class="mkt-cta-sub">Ký gửi để nhận khoảng giá dựa trên giao dịch thật quanh khu vực. Miễn phí.</p>
                </div>
                <div class="mkt-cta-actions">
                    <a class="ds-btn ds-btn-solid ds-btn-lg" href="{{ route('webapp') }}">Ký gửi miễn phí
                        <svg viewBox="0 0 24 24" class="ds-ic ds-ic-16"><path d="M9 18l6-6-6-6"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════ ROLE CTA ══════════ --}}
    <section class="sec role-cta" id="role-cta">
        <div class="wrapc">
            <div class="sec-head" style="text-align:center;max-width:600px;margin:0 auto 40px">
                <span class="eyebrow">Bắt đầu ngay</span>
                <h2 class="h2">Bạn đến với Đà Lạt BĐS vì điều gì?</h2>
            </div>
            <div class="role-grid">
                <div class="role-col">
                    <div class="role-ic"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-22" style="stroke:var(--primary)"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg></div>
                    <h3>Tôi muốn mua</h3>
                    <p>Đã xác minh chủ, pháp lý và quy hoạch.</p>
                    <a href="{{ route('properties.index') }}" class="ds-btn ds-btn-outline ds-btn-md ds-btn-block">Tìm BĐS đã xác minh</a>
                </div>
                <div class="role-col">
                    <div class="role-ic"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-22" style="stroke:var(--primary)"><path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"></path></svg></div>
                    <h3>Tôi muốn bán</h3>
                    <p>Miễn phí, có báo cáo mỗi tuần.</p>
                    <a href="{{ route('webapp') }}" class="ds-btn ds-btn-outline ds-btn-md ds-btn-block">Ký gửi miễn phí</a>
                </div>
                <div class="role-col role-col-feat">
                    <div class="role-ic"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-22" style="stroke:#fff"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"></path></svg></div>
                    <h3>Tôi là môi giới</h3>
                    <p>Kho hàng chung, hoa hồng công khai.</p>
                    <a href="{{ route('webapp') }}" class="ds-btn ds-btn-white ds-btn-md ds-btn-block">Đăng ký môi giới</a>
                    <span class="role-feat-note">Nhận địa bàn phường của bạn →</span>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/ltbs-home.js') }}"></script>
@endpush
