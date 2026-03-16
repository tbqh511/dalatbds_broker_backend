<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Đà Lạt BĐS — WebApp</title>
<link rel="stylesheet" href="{{ asset('css/webapp-v2.css') }}?v={{ filemtime(public_path('css/webapp-v2.css')) }}">
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

  @include('webapp.partials.toast')

</div><!-- end #app -->

<script>
  window.WEBAPP_CONFIG = {
    customerRole: @json($customer->role ?? 'guest'),
    customerName: @json($customer->name ?? 'Khách'),
    customerId: @json($customer->id ?? null),
    csrfToken: @json(csrf_token()),
    routes: {
      addListing: @json(route('webapp.add_listing')),
      addCustomer: @json(route('webapp.add_customer')),
      leadsCreate: @json(route('webapp.leads.create')),
    }
  };
</script>
<script src="{{ asset('js/webapp-v2.js') }}?v={{ filemtime(public_path('js/webapp-v2.js')) }}"></script>
</body>
</html>
