{{--
    WebApp: rebind the header hamburger to open the dashboard sidebar.
    No separate "Menu quản lý" button — the header hamburger serves that role.
--}}
@push('scripts')
<script>
$(document).ready(function () {
    // Hide elements not relevant in webapp context
    $('.nav-holder.main-menu').hide();
    $('.header-search-button').hide();

    // Rebind header hamburger → open dashboard sidebar instead of main-site nav
    $('.nav-button-wrap').off('click').on('click', function (e) {
        e.stopPropagation();
        $('.dashbard-menu-wrap').addClass('dashbard-menu-wrap_vis');
        $('.dashbard-menu-overlay').fadeIn(100);
    });
});
</script>
@endpush
