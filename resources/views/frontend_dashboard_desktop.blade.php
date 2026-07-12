<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bảng làm việc — Đà Lạt BĐS</title>
    <link rel="stylesheet" href="{{ asset('css/ltbs-core.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ltbs-dashboard.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <style>
        .dash-cardhead{display:flex;align-items:center;gap:10px;padding:16px 18px;border-bottom:1px solid var(--border);font-size:15px;font-weight:700;color:var(--ink-900)}
        .dash-cardhead svg{width:18px;height:18px;stroke:var(--primary);stroke-width:1.7;fill:none;stroke-linecap:round;stroke-linejoin:round}
    </style>
</head>

<body class="ltbs">
    <div class="dash" id="dash">
        <button type="button" class="dash-scrim" id="dashScrim" style="display:none" onclick="document.getElementById('dash').classList.remove('open')"></button>
        {{-- Sidebar --}}
        <aside class="dash-side">
            <div class="side-logo"><img src="{{ asset('images/ltbs-logo.svg') }}" alt="Đà Lạt BĐS" style="height:30px"></div>
            <nav class="side-nav" style="padding:8px 12px;display:flex;flex-direction:column;gap:4px">
                <a href="{{ route('webapp.desktop') }}" class="side-link active active-bar"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><path d="M9 22V12h6v10"></path></svg><span>Tổng quan</span></a>
                <a href="{{ route('webapp.listings') }}" class="side-link"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><path d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z"></path></svg><span>Tin của tôi</span></a>
                <a href="{{ route('webapp.listings') }}" class="side-link"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg><span>Chờ xác minh</span></a>
                <a href="{{ route('webapp.leads') }}" class="side-link"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg><span>Khách hàng</span></a>
                <a href="{{ route('webapp.bookings') }}" class="side-link"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg><span>Lịch xem</span></a>
                <a href="{{ route('webapp.profile') }}" class="side-link"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><circle cx="12" cy="8" r="4"></circle><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"></path></svg><span>Hồ sơ</span></a>
            </nav>
            <div style="margin-top:auto;padding:12px">
                <a href="{{ route('index') }}" class="side-link"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-18"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"></path></svg><span>Về trang chủ</span></a>
            </div>
        </aside>

        {{-- Main --}}
        <div class="dash-main">
            <header class="dash-top">
                <button type="button" class="menu-btn" onclick="document.getElementById('dash').classList.toggle('open')" aria-label="Menu">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
                <div class="top-greet">
                    <h1>{{ $greeting }}, {{ $customer->name ?? 'bạn' }}</h1>
                    <p>Không gian làm việc eBroker · Đà Lạt</p>
                </div>
                <div class="top-actions">
                    <button type="button" class="bell-btn" aria-label="Thông báo"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-20"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 0 1-3.4 0"></path></svg></button>
                    <img class="ds-avatar" style="width:38px;height:38px" src="{{ $customer->profile ?? asset('images/ltbs-logo.svg') }}" alt="">
                </div>
            </header>

            <main class="dash-content" style="padding:24px 32px 64px">
                {{-- KPIs --}}
                <section class="kpi-grid">
                    @foreach ($kpis as $k)
                        <div class="kpi-card">
                            <div class="kpi-icon">
                                <svg viewBox="0 0 24 24" class="ds-ic ds-ic-18" style="stroke:var(--primary)">
                                    @switch($k['icon'])
                                        @case('home')<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><path d="M9 22V12h6v10"></path>@break
                                        @case('eye')<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>@break
                                        @case('users')<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle>@break
                                        @default<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    @endswitch
                                </svg>
                            </div>
                            <div class="kpi-label">{{ $k['label'] }}</div>
                            <div class="kpi-value-row"><span class="kpi-value">{{ $k['value'] }}</span></div>
                            <div class="kpi-delta">{{ $k['delta'] }}</div>
                        </div>
                    @endforeach
                </section>

                <div class="dash-cols">
                    <div class="col-main">
                        {{-- Nguồn theo địa bàn --}}
                        <div class="card">
                            <div class="dash-cardhead"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>Nguồn theo địa bàn</div>
                            @forelse ($wardsData as $w)
                                <div class="ward-row">
                                    <div class="ward-name">{{ $w['name'] }}</div>
                                    <div class="ward-right"><span class="ward-count">{{ $w['count'] }} tin đã xác minh</span></div>
                                </div>
                            @empty
                                <div class="empty-block"><div class="empty-ic"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-22" style="stroke:var(--primary)"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></div><p class="empty-text">Địa bàn của bạn đang trống. Thêm tin đầu tiên để bắt đầu phủ nguồn.</p><a class="ds-btn ds-btn-solid ds-btn-sm" href="{{ route('webapp.add_listing') }}">Thêm tin đầu tiên</a></div>
                            @endforelse
                        </div>
                        {{-- Đang chờ xác minh --}}
                        <div class="card">
                            <div class="dash-cardhead"><svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>Đang chờ xác minh</div>
                            @forelse ($pendingData as $p)
                                <div class="pend-row">
                                    <img class="pend-thumb" src="{{ $p->title_image }}" alt="">
                                    <div class="pend-info">
                                        <div class="pend-addr">{{ $p->title }}</div>
                                        <div class="pend-bar"><span class="pend-seg done"></span><span class="pend-seg active"></span><span class="pend-seg"></span></div>
                                        <div class="pend-steps"><span class="pstep done">Sổ</span><span class="pstep active">Quy hoạch</span><span class="pstep">Hiện trạng</span></div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-block"><div class="empty-ic empty-ic-success"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-22" style="stroke:var(--success)"><path d="M20 6 9 17l-5-5"></path></svg></div><p class="empty-text">Không có tin nào đang chờ. Mọi tin của bạn đã được xác minh.</p></div>
                            @endforelse
                        </div>
                    </div>

                    <div class="col-side">
                        {{-- Việc hôm nay --}}
                        <div class="card">
                            <div class="dash-cardhead"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>Việc hôm nay</div>
                            @if ($todayBookings->isEmpty() && $nurtureLeads->isEmpty())
                                <div class="empty-block"><div class="empty-ic"><svg viewBox="0 0 24 24" class="ds-ic ds-ic-22" style="stroke:var(--primary)"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg></div><p class="empty-text">Hôm nay chưa có lịch xem. Đặt lịch để tiến gần một giao dịch.</p></div>
                            @else
                                @if ($todayBookings->isNotEmpty())
                                    <div class="subhead" style="padding:12px 18px 4px">Lịch xem hôm nay</div>
                                    @foreach ($todayBookings as $b)
                                        <div class="sched-row"><div class="sched-time">{{ optional($b->scheduled_at)->format('H:i') }}</div><div class="sched-body"><div class="sched-name">{{ $b->deal->lead->customer->name ?? 'Khách' }}</div><div class="sched-addr">Lịch xem BĐS</div></div></div>
                                    @endforeach
                                @endif
                                @if ($nurtureLeads->isNotEmpty())
                                    <div class="subhead" style="padding:12px 18px 4px">Khách cần chăm</div>
                                    @foreach ($nurtureLeads as $l)
                                        <div class="lead-row"><div class="lead-top"><span class="lead-name">{{ $l->name ?? $l->customer->name ?? 'Khách #' . $l->id }}</span><span class="ds-badge ds-badge-primary">{{ $l->status ?? 'Mới' }}</span></div><div class="lead-task">Liên hệ và cập nhật nhu cầu</div></div>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                        {{-- Thông báo --}}
                        <div class="card">
                            <div class="dash-cardhead"><svg viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path></svg>Thông báo gần đây</div>
                            <div class="empty-block" style="padding:20px 24px 28px"><p class="empty-text">Chưa có thông báo mới.</p></div>
                            {{-- TODO(backend): wire InAppNotificationService feed vào đây. --}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="ltbs-toast-wrap" id="ltbsToastWrap"></div>
    <script src="{{ asset('js/ltbs-core.js') }}"></script>
</body>

</html>
