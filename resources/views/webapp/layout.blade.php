<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Đà Lạt BĐS — WebApp</title>
<link rel="stylesheet" href="{{ asset('css/webapp-v2.css') }}?v={{ filemtime(public_path('css/webapp-v2.css')) }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>[x-cloak]{display:none!important;}</style>
<script src="https://telegram.org/js/telegram-web-app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/eruda"></script>
<script>eruda.init();</script>
</head>
<body>

@if(!isset($customer) || $customer === null)
<div id="auth-loading-overlay" style="position:fixed;inset:0;background:#fff;z-index:9999;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;">
  <div style="width:28px;height:28px;border:3px solid #f3f3f3;border-top-color:#3270FC;border-radius:50%;animation:authOverlaySpin 1s linear infinite;margin-bottom:14px;"></div>
  <p style="color:#666;font-size:14px;margin:0;">Đang đồng bộ dữ liệu...</p>
  <style>@keyframes authOverlaySpin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}</style>
</div>
@endif

<div id="app">

  @include('webapp.partials.header')

  @if(isset($customer) && $customer->getEffectiveRole() === 'admin')
    @include('webapp.partials.role-switcher')
  @endif

  <!-- SCROLL AREA -->
  <div class="scroll-area" id="scrollArea">
    @include('webapp.pages.home')
    @include('webapp.pages.search')
    @include('webapp.pages.post')
    @include('webapp.pages.activity')

    {{-- Notification detail pages --}}
    @include('webapp.notifications.notif-lead')
    @include('webapp.notifications.notif-booking')
    @include('webapp.notifications.notif-bds')
    @include('webapp.notifications.notif-comm')
    @include('webapp.notifications.notif-approve')
    @include('webapp.notifications.notif-deal')

    @include('webapp.pages.profile')
  </div><!-- end scroll-area -->

  @include('webapp.partials.bottom-nav')

  {{-- Detail page (slide-in overlay) --}}
  @include('webapp.detail.property-detail')

  {{-- Subpages (slide-in overlays) — chỉ render khi đã xác thực để tránh Alpine.js
       gọi API trong lúc auth retry loop (user chưa đăng nhập, $customer = null) --}}
  @if($customer)
  @include('webapp.subpages.mybds')
  @include('webapp.subpages.mycustomers')
  @include('webapp.subpages.customerdetail')
  @include('webapp.subpages.clients')
  @include('webapp.subpages.leads')
  @include('webapp.subpages.deals')
  @include('webapp.subpages.bookings')
  @include('webapp.subpages.commissions')
  @include('webapp.subpages.kpiteam')
  @include('webapp.subpages.assignlead')
  @include('webapp.subpages.approvebds')
  @include('webapp.subpages.users')
  @include('webapp.subpages.reports')
  @include('webapp.subpages.approvecomm')
  @include('webapp.subpages.marketprices')
  @include('webapp.subpages.editprofile')
  @include('webapp.subpages.notifset')
  @include('webapp.subpages.support')
  @include('webapp.subpages.referral')
  @include('webapp.subpages.activitylog')
  @include('webapp.subpages.likedbds')
  @include('webapp.subpages.reviews')
  @include('webapp.subpages.userbds')
  @endif

  {{-- GUEST DIALOG — no-account prompt --}}
  <div class="guest-dialog-overlay" id="guestDialogOverlay">
    <div class="guest-dialog">
      <button class="guest-dialog-close" onclick="closeGuestDialog()" aria-label="Đóng">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
      <div class="guest-dialog-logo">
        <img src="{{ asset('images/logo.svg') }}" alt="Đà Lạt BĐS" style="height:40px;width:auto;">
      </div>
      <div class="guest-dialog-title">Chào mừng đến DalatBDS!</div>
      <div class="guest-dialog-body">
        Chỉ một bước nữa thôi! Nhấn <strong>"Xác nhận"</strong> và chia sẻ số điện thoại để tụi mình tạo tài khoản miễn phí cho bạn ngay nhé. 🏡
      </div>
      <div class="guest-dialog-actions">
        <button class="guest-dialog-btn guest-dialog-btn-outline" onclick="closeGuestDialog()">Để sau</button>
        <button class="guest-dialog-btn guest-dialog-btn-primary" onclick="guestShareContact()">Xác nhận</button>
      </div>
    </div>
  </div>

  @include('webapp.partials.toast')

</div><!-- end #app -->

<script>
  window.WEBAPP_CONFIG = {
    customerRole: @json(isset($customer) ? $customer->getEffectiveRole() : 'guest'),
    customerName: @json($customer->name ?? 'Khách'),
    customerId: @json($customer->id ?? null),
    telegram_id: @json($customer->telegram_id ?? null),
    csrfToken: @json(csrf_token()),
    mapsKey: @json(config('services.google_maps.place_api_key')),
    likedPropertyIds: @json($likedIds ?? []),
    telegramBotUsername: @json(config('services.telegram.bot_username')),
    telegramWebappShortName: @json(config('services.telegram.webapp_short_name')),
    routes: {
      addListing: @json(route('webapp.add_listing')),
      addCustomer: @json(route('webapp.add_customer')),
      leadsCreate: @json(route('webapp.leads.create')),
      favouriteToggle: @json(route('webapp.favourite.toggle')),
      favouritesJson: @json(route('webapp.favourites.json')),
      myPropertiesJson: @json(route('webapp.api.my_properties')),
      myCustomersJson: @json(route('webapp.api.my_customers')),
      myLeadsJson: @json(route('webapp.api.leads')),
      myClientsJson: @json(route('webapp.api.my_clients')),
      myDealsJson: @json(route('webapp.api.deals')),
      myCommissionsJson: @json(route('webapp.api.commissions')),
      leadsUpdateStatusBase: '/webapp/leads/',
      leadsCreateDealBase: '/webapp/leads/',
      leadsActivitiesBase: '/webapp/leads/',
      myListingToggleBase: '/webapp/listings/',
      myListingDeleteBase: '/webapp/listings/',
      editListingBase: '/webapp/edit-listing/',
      adminUsersJson: @json(route('webapp.admin.users')),
      adminUsersBase: '/webapp/api/admin/users/',
      adminPropertiesJson: @json(app('router')->has('webapp.admin.properties') ? route('webapp.admin.properties') : null),
      adminPropertiesBase: '/webapp/api/admin/properties/',
      adminCommissionsJson: @json(app('router')->has('webapp.admin.commissions') ? route('webapp.admin.commissions') : null),
      adminCommissionsBase: '/webapp/api/admin/commissions/',
      profileUpdate: @json(route('webapp.profile.update')),
      profileAvatar: @json(route('webapp.profile.avatar')),
      supportTicket: @json(route('webapp.support.ticket')),
      notifSettingsSave: @json(route('webapp.notif.settings.save')),
      notificationsJson: '/webapp/api/notifications',
      notificationsUnread: '/webapp/api/notifications/unread-count',
      notificationsReadAll: '/webapp/api/notifications/read-all',
      assignData: @json(route('webapp.sale-admin.assign-data')),
      bulkAssign: @json(route('webapp.leads.bulk-assign')),
      kpiTeamJson:      @json(app('router')->has('webapp.api.kpi-team') ? route('webapp.api.kpi-team') : null),
      kpiTeamSupport:   @json(app('router')->has('webapp.api.kpi-team.support') ? route('webapp.api.kpi-team.support') : null),
      adminReportsJson: @json(app('router')->has('webapp.admin.reports') ? route('webapp.admin.reports') : null),
      adminMarketPricesJson: @json(app('router')->has('webapp.admin.market-prices.index') ? route('webapp.admin.market-prices.index') : null),
      adminMarketPricesBase: '/webapp/api/admin/market-prices/',
    },
    notifSettings: @json($notifSettings ?? \App\Models\Customer::DEFAULT_NOTIFICATION_SETTINGS),
    customerProfile: {
      name: @json($customer->name ?? ''),
      email: @json($customer->email ?? ''),
      mobile: @json($customer->mobile ?? ''),
      zalo: @json($customer->zalo ?? ''),
      bio: @json($customer->bio ?? ''),
      facebook_link: @json($customer->facebook_link ?? ''),
      years_experience: @json($customer->years_experience ?? ''),
      work_area: @json($customer->work_area ?? ''),
      specialization: @json($customer->specialization ?? ''),
      telegram_id: @json($customer->telegram_id ?? ''),
      avatar_url: @json(($customer && $customer->getRawOriginal('profile')) ? url('images' . config('global.USER_IMG_PATH') . $customer->getRawOriginal('profile')) : 'https://dalatbds.com/images/users/1693209486.1303.png'),
      role: @json($customer ? $customer->getEffectiveRole() : 'guest'),
    }
  };
  window.likedIds = new Set((window.WEBAPP_CONFIG.likedPropertyIds || []).map(String));
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.place_api_key') }}&libraries=places,marker&loading=async"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@8.3.0/dist/web/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
@if($customer->id ?? null)
<script>
  (function() {
    var echoConfig = {
      broadcaster: 'pusher',
      key: @json(config('broadcasting.connections.pusher.key')),
      cluster: @json(env('PUSHER_APP_CLUSTER', 'mt1')),
      forceTLS: @json(config('broadcasting.connections.pusher.options.useTLS', false)),
      disableStats: true,
      authEndpoint: '/broadcasting/auth',
      auth: { headers: { 'X-CSRF-TOKEN': window.WEBAPP_CONFIG.csrfToken } },
    };

    // Self-hosted websockets (laravel-websockets): cần wsHost + enabledTransports
    var pusherHost = @json(env('PUSHER_HOST'));
    if (pusherHost) {
      echoConfig.wsHost = pusherHost;
      echoConfig.wsPort = @json((int) env('PUSHER_PORT', 6001));
      echoConfig.wssPort = @json((int) env('PUSHER_PORT', 6001));
      echoConfig.enabledTransports = ['ws', 'wss'];
    }

    window.Echo = new Echo(echoConfig);
  })();
</script>
@endif
<script src="https://cdn.jsdelivr.net/npm/axios@1.7.9/dist/axios.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>
<script src="{{ asset('js/webapp-v2.js') }}?v={{ filemtime(public_path('js/webapp-v2.js')) }}"></script>
{{-- Telegram auto-login: runs after webapp-v2.js so deep link handler has already set referral_code in sessionStorage --}}
<script>
(function() {
  function removeAuthOverlay() {
    var ov = document.getElementById('auth-loading-overlay');
    if (ov) ov.parentNode.removeChild(ov);
  }

  // Nếu browser đang ở /webapp/auth (do authRedirect() render page trực tiếp thay vì redirect)
  // → sửa URL về /webapp để tránh prompt "resubmit form?" khi refresh
  if (window.location.pathname === '/webapp/auth') {
    history.replaceState(null, '', '/webapp');
  }

  var cfg = window.WEBAPP_CONFIG || {};
  var tg = window.Telegram && window.Telegram.WebApp;

  // ─── Đọc login_status TRƯỚC KHI xóa khỏi URL ────────────────────────
  var _params     = new URLSearchParams(window.location.search);
  var _loginStatus = _params.get('login_status');
  var _loginRetry  = parseInt(_params.get('retry') || '0', 10);

  // Dọn sạch query params login_status/retry khỏi URL để không ảnh hưởng deep links
  if (_loginStatus || _params.has('retry')) {
    var _cleanUrl = new URL(window.location.href);
    _cleanUrl.searchParams.delete('login_status');
    _cleanUrl.searchParams.delete('retry');
    _cleanUrl.searchParams.delete('t');
    history.replaceState(null, '', _cleanUrl.pathname + (_cleanUrl.search || '') + (_cleanUrl.hash || ''));
  }

  // ─── ĐÃ CÓ SESSION → kiểm tra có phải account mới không ────────────────
  var hasSession = !!cfg.customerId;
  console.log('[WebApp Auth]', {
    hasSession: hasSession,
    customerId: cfg.customerId,
    sessionTelegramId: cfg.telegram_id,
    currentTgUser: tg && tg.initDataUnsafe && tg.initDataUnsafe.user ? tg.initDataUnsafe.user.id : null,
    loginStatus: _loginStatus,
    loginRetry: _loginRetry,
  });
  if (hasSession) {
    // Phát hiện account switch: so sánh Telegram ID hiện tại với session
    // Nếu user switch account trong Telegram mà không logout webapp → force re-auth
    if (tg && tg.initData && tg.initDataUnsafe && tg.initDataUnsafe.user) {
      var currentTgId = String(tg.initDataUnsafe.user.id);
      var sessionTgId = String(cfg.telegram_id || '');
      console.log('[WebApp Auth] account switch check', { currentTgId: currentTgId, sessionTgId: sessionTgId, mismatch: currentTgId !== sessionTgId });
      if (currentTgId && sessionTgId && currentTgId !== sessionTgId) {
        // initDataUnsafe chỉ dùng để phát hiện switch (không dùng để auth)
        // Xác thực thực sự vẫn là HMAC-SHA256 server-side tại POST /webapp/auth
        console.log('[WebApp Auth] account switch detected → re-auth');
        submitAuthForm(0);
        return;
      }
    }
    sessionStorage.removeItem('_auth_submit');
    sessionStorage.removeItem('_auth_loop');
    // Replay deep link if user just completed auth (e.g. new user after phone-sharing flow)
    var _pending = sessionStorage.getItem('pending_deeplink');
    if (_pending && _pending.indexOf('property_') === 0) {
      sessionStorage.removeItem('pending_deeplink');
      var _propId = parseInt(_pending.substring(9));
      if (_propId) {
        document.addEventListener('DOMContentLoaded', function() {
          setTimeout(function() {
            if (typeof openDetail === 'function') openDetail({ id: _propId });
          }, 800);
        });
      }
    }
    removeAuthOverlay();
    return;
  }

  // ─── CHƯA CÓ SESSION (hoặc session không khớp) → Cần xác thực qua Telegram ──────────────
  if (!tg || !tg.initData) {
    var appEl = document.getElementById('app');
    if (appEl) {
      appEl.innerHTML = '<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;text-align:center;padding:20px;">'
        + '<img src="/images/logo.svg" alt="Đà Lạt BĐS" style="height:48px;margin-bottom:16px;">'
        + '<h3 style="margin:0 0 8px;">Vui lòng mở qua Telegram</h3>'
        + '<p style="color:#666;margin:0;">Ứng dụng này chỉ hoạt động bên trong Telegram Mini App.</p>'
        + '</div>';
    }
    return;
  }

  tg.expand();

  // ─── Xử lý kết quả redirect từ POST /webapp/auth ────────────────────
  if (_loginStatus === 'guest') {
    var _phoneShared = false;
    try { _phoneShared = sessionStorage.getItem('_phone_shared') === '1'; } catch(e) {}

    if (_phoneShared && _loginRetry < 5) {
      // Vừa share SĐT → bot có thể chưa kịp xử lý webhook → retry sau 4 giây
      // Tổng window: 8s (initial) + 5×4s = 28s — đủ cho Telegram webhook delivery
      var _ov = document.getElementById('auth-loading-overlay');
      if (_ov) {
        var _ovP = _ov.querySelector('p');
        if (_ovP) _ovP.textContent = 'Đang tạo tài khoản... (' + (_loginRetry + 1) + '/5)';
      }
      setTimeout(function() { submitAuthForm(_loginRetry + 1); }, 6000);
    } else {
      // Lần đầu mở (chưa share SĐT) HOẶC hết retry → hiện guest dialog ngay
      sessionStorage.removeItem('_phone_shared');
      removeAuthOverlay();
      document.addEventListener('DOMContentLoaded', function() {
        if (typeof showGuestDialog === 'function') showGuestDialog();
      });
    }
    return;
  }

  if (_loginStatus === 'error') {
    var _reason = _params.get('reason');
    sessionStorage.removeItem('_phone_shared');
    removeAuthOverlay();

    // Nếu lỗi do initData không hợp lệ (invalid_initdata) — đây là lỗi cấu hình
    // TELEGRAM_BOT_TOKEN, không thể tự giải quyết bằng cách share SĐT.
    // Hiện thông báo lỗi rõ ràng thay vì dialog share SĐT gây nhầm lẫn.
    if (_reason === 'invalid_initdata') {
      document.addEventListener('DOMContentLoaded', function() {
        var appEl = document.getElementById('app');
        if (appEl) {
          appEl.innerHTML = '<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;text-align:center;padding:24px;">'
            + '<img src="/images/logo.svg" alt="Đà Lạt BĐS" style="height:48px;margin-bottom:16px;">'
            + '<h3 style="margin:0 0 8px;color:#333;">Lỗi xác thực</h3>'
            + '<p style="color:#666;font-size:14px;margin:0 0 20px;">Không thể xác thực Telegram. Vui lòng đóng và mở lại ứng dụng.</p>'
            + '<p style="color:#999;font-size:12px;margin:0 0 20px;">(Mã lỗi: E_INITDATA)</p>'
            + '<button onclick="sessionStorage.clear();window.location.reload()" style="background:#2563eb;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-size:14px;cursor:pointer;">Thử lại</button>'
            + '</div>';
        }
      });
    } else {
      document.addEventListener('DOMContentLoaded', function() {
        if (typeof showGuestDialog === 'function') showGuestDialog();
      });
    }
    return;
  }

  // ─── Auth thành công server-side (login_status=ok) nhưng session chưa load ──
  if (_loginStatus === 'ok') {
    // Trường hợp bình thường đã được xử lý ở hasSession=true phía trên.
    // Đây là trường hợp hiếm: server set session thành công nhưng browser
    // chưa đọc được (race condition hoặc WebView quirk).
    // → Thử reload 1 lần sạch; nếu vẫn không được thì hiện lỗi.
    var _loopCount = parseInt(sessionStorage.getItem('_auth_loop') || '0', 10);
    if (_loopCount >= 1) {
      sessionStorage.removeItem('_auth_loop');
      var _errEl = document.getElementById('app');
      if (_errEl) {
        _errEl.innerHTML = '<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;text-align:center;padding:24px;">'
          + '<img src="/images/logo.svg" alt="Đà Lạt BĐS" style="height:48px;margin-bottom:16px;">'
          + '<h3 style="margin:0 0 8px;color:#333;">Không thể kết nối phiên</h3>'
          + '<p style="color:#666;font-size:14px;margin:0 0 20px;">Vui lòng đóng và mở lại ứng dụng.</p>'
          + '<button onclick="window.location.reload()" style="background:#2563eb;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-size:14px;cursor:pointer;">Thử lại</button>'
          + '</div>';
      }
      return;
    }
    sessionStorage.setItem('_auth_loop', '1');
    window.location.replace(window.location.pathname); // reload không có query param
    return;
  }

  // ─── Lần đầu mở app (không có login_status) → submit form auth ──────
  function submitAuthForm(retryCount) {
    var refCode = sessionStorage.getItem('referral_code') || '';
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '/webapp/auth';
    form.style.display = 'none';

    [
      ['_token',        cfg.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content')],
      ['initData',      tg.initData],
      ['referral_code', refCode],
      ['retry',         String(retryCount)],
    ].forEach(function(pair) {
      var inp = document.createElement('input');
      inp.type  = 'hidden';
      inp.name  = pair[0];
      inp.value = pair[1] || '';
      form.appendChild(inp);
    });

    document.body.appendChild(form);

    // Hiển thị spinner trong lúc chờ server redirect
    var appEl = document.getElementById('app');
    if (appEl) {
      appEl.innerHTML = '<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;text-align:center;">'
        + '<div style="width:24px;height:24px;border:3px solid #f3f3f3;border-top:3px solid var(--primary-color,#2563eb);border-radius:50%;animation:spin 1s linear infinite;margin-bottom:12px;"></div>'
        + '<p style="color:#666;font-size:14px;margin:0;">Đang đồng bộ dữ liệu...</p>'
        + '</div><style>@keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}</style>';
    }

    sessionStorage.removeItem('referral_code');
    form.submit();
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Bảo vệ chống loop vô hạn: nếu đã submit quá 3 lần mà vẫn không có session
    // (trường hợp không có login_status=ok trong URL) → hiện lỗi thay vì loop tiếp
    var _submitCount = parseInt(sessionStorage.getItem('_auth_submit') || '0', 10);
    if (_submitCount >= 3) {
      sessionStorage.removeItem('_auth_submit');
      removeAuthOverlay();
      var _errEl2 = document.getElementById('app');
      if (_errEl2) {
        _errEl2.innerHTML = '<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100vh;text-align:center;padding:24px;">'
          + '<img src="/images/logo.svg" alt="Đà Lạt BĐS" style="height:48px;margin-bottom:16px;">'
          + '<h3 style="margin:0 0 8px;color:#333;">Không thể xác thực</h3>'
          + '<p style="color:#666;font-size:14px;margin:0 0 20px;">Vui lòng đóng và mở lại ứng dụng trong Telegram.</p>'
          + '<button onclick="sessionStorage.clear();window.location.reload()" style="background:#2563eb;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-size:14px;cursor:pointer;">Thử lại</button>'
          + '</div>';
      }
      return;
    }
    sessionStorage.setItem('_auth_submit', _submitCount + 1);
    submitAuthForm(0);
  });
})();
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
</body>
</html>
