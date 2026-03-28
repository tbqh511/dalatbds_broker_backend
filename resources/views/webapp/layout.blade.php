<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Đà Lạt BĐS — WebApp</title>
<link rel="stylesheet" href="{{ asset('css/webapp-v2.css') }}?v={{ filemtime(public_path('css/webapp-v2.css')) }}">
<style>[x-cloak]{display:none!important;}</style>
<script src="https://telegram.org/js/telegram-web-app.js"></script>
</head>
<body>

<div id="app">

  @include('webapp.partials.header')

  @if(config('app.debug') || env('WEBAPP_DEV_MODE'))
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

  {{-- Subpages (slide-in overlays) --}}
  @include('webapp.subpages.mybds')
  @include('webapp.subpages.mycustomers')
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
  @include('webapp.subpages.editprofile')
  @include('webapp.subpages.notifset')
  @include('webapp.subpages.support')
  @include('webapp.subpages.referral')
  @include('webapp.subpages.activitylog')
  @include('webapp.subpages.likedbds')
  @include('webapp.subpages.reviews')

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
      <div class="guest-dialog-title">Bắt đầu hành trình ngay!</div>
      <div class="guest-dialog-body">
        Bạn hãy quay lại Bot chat và chia sẻ số điện thoại để tụi mình mở tài khoản cho bạn nhé. Hàng ngàn cơ hội an cư và đầu tư đang chờ đón!
      </div>
      <div class="guest-dialog-actions">
        <button class="guest-dialog-btn guest-dialog-btn-outline" onclick="closeGuestDialog()">Đóng</button>
        <button class="guest-dialog-btn guest-dialog-btn-primary" onclick="guestShareContact()">Chia sẻ</button>
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
      myDealsJson: @json(route('webapp.api.deals')),
      myCommissionsJson: @json(route('webapp.api.commissions')),
      leadsUpdateStatusBase: '/webapp/leads/',
      leadsCreateDealBase: '/webapp/leads/',
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
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.place_api_key') }}&libraries=places,marker"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
<script src="{{ asset('js/webapp-v2.js') }}?v={{ filemtime(public_path('js/webapp-v2.js')) }}"></script>
{{-- Telegram auto-login: runs after webapp-v2.js so deep link handler has already set referral_code in sessionStorage --}}
<script>
(function() {
  var cfg = window.WEBAPP_CONFIG || {};
  var tg = window.Telegram && window.Telegram.WebApp;
  var hasSession = cfg.customerId !== null && cfg.customerId !== undefined;

  // If already authenticated, verify identity matches current Telegram user
  if (hasSession) {
    if (tg && tg.initDataUnsafe && tg.initDataUnsafe.user) {
      var sessionTgId = String(cfg.customerProfile.telegram_id || '');
      var currentTgId = String(tg.initDataUnsafe.user.id || '');

      if (sessionTgId && currentTgId && sessionTgId === currentTgId) {
        return; // Identity matches, keep session
      }

      if (sessionTgId && currentTgId && sessionTgId !== currentTgId) {
        // True identity mismatch — logout old session first, then re-authenticate
        fetch('/webapp/logout', { method: 'GET', credentials: 'same-origin' })
          .then(function() { window.location.reload(); })
          .catch(function() { window.location.reload(); });
        return;
      }

      // sessionTgId is empty — session customer has no telegram_id.
      // Don't logout. Fall through to call loginViaMiniApp which will link telegram_id.
    } else {
      return; // Not inside Telegram, keep existing session
    }
  }

  if (!tg || !tg.initData) {
    // Not inside Telegram — nothing to do
    return;
  }

  tg.expand();

  // Wait for DOMContentLoaded so deep link handler in webapp-v2.js has set sessionStorage
  document.addEventListener('DOMContentLoaded', function() {
    var refCode = sessionStorage.getItem('referral_code') || '';

    fetch('/api/webapp/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': cfg.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
      },
      credentials: 'same-origin',
      body: JSON.stringify({ initData: tg.initData, referral_code: refCode })
    })
    .then(function(res) {
      if (!res.ok) {
        console.error('[WebApp Login] HTTP error:', res.status, res.statusText);
      }
      return res.json();
    })
    .then(function(data) {
      console.log('[WebApp Login] Response:', data.status || 'error', data.message || '');
      if (data.status === 'authenticated') {
        sessionStorage.removeItem('referral_code');
        window.location.reload();
      } else if (data.status === 'guest') {
        // Only show guest dialog if user doesn't already have a valid session.
        // If hasSession is true, loginViaMiniApp couldn't link telegram_id but
        // the session is still valid — keep it, don't show guest dialog.
        if (!hasSession) {
          if (typeof showGuestDialog === 'function') {
            showGuestDialog();
          } else {
            alert('Bạn chưa có tài khoản. Vui lòng quay lại Bot chat và chia sẻ số điện thoại để tạo tài khoản.');
          }
        }
      } else if (data.error) {
        console.error('[WebApp Login] Server error:', data.message);
      }
    })
    .catch(function(err) {
      console.error('[WebApp Login] Request failed:', err);
    });
  });
})();
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
</body>
</html>
