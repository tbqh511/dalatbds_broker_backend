{{-- WebApp Bottom Navigation Bar --}}

{{-- FAB Backdrop --}}
<div id="wab-backdrop"></div>

{{-- FAB Sub Menu --}}
<div id="wab-fab-menu">
    <a href="{{ route('webapp.add_customer') }}" class="wab-fab-item">
        <div class="wab-fab-item-icon" style="background: #3270FC;">
            <i class="fas fa-user-plus"></i>
        </div>
        <span>Thêm Khách hàng</span>
    </a>
    <a href="{{ route('webapp.add_listing') }}" class="wab-fab-item">
        <div class="wab-fab-item-icon" style="background: #3270FC;">
            <i class="fas fa-building"></i>
        </div>
        <span>Đăng tin BĐS</span>
    </a>
</div>

{{-- Bottom Navigation --}}
<div id="wab-bottom-nav">

    {{-- SVG bar background with curved notch --}}
    <svg id="wab-nav-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 375 70" preserveAspectRatio="none"
        overflow="visible">
        <path d="M0,0 L375,0 L375,70 L0,70 Z" fill="#ffffff" />
    </svg>

    {{-- Center FAB Button --}}
    <button id="wab-fab-btn" aria-label="Thêm mới">
        <i class="fas fa-plus"></i>
    </button>

    {{-- Nav Items --}}
    <div id="wab-nav-items">
        <a href="{{ route('webapp') }}" class="wab-nav-item {{ request()->routeIs('webapp') ? 'wab-active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('webapp.listings') }}"
            class="wab-nav-item {{ request()->routeIs('webapp.listings*') ? 'wab-active' : '' }}">
            <i class="fas fa-building"></i>
            <span>BĐS</span>
        </a>
        {{-- Center spacer for FAB --}}
        <div class="wab-nav-spacer"></div>
        <a href="{{ route('webapp.leads') }}"
            class="wab-nav-item {{ request()->routeIs('webapp.leads*') ? 'wab-active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Khách</span>
        </a>
        <button class="wab-nav-item" id="wab-menu-btn">
            <i class="fas fa-cog"></i>
            <span>Menu</span>
        </button>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var fabBtn = document.getElementById('wab-fab-btn');
        var fabMenu = document.getElementById('wab-fab-menu');
        var backdrop = document.getElementById('wab-backdrop');
        var menuBtn = document.getElementById('wab-menu-btn');

        function openFab() {
            fabBtn.classList.add('wab-open');
            fabMenu.classList.add('wab-open');
            backdrop.classList.add('wab-open');
        }
        function closeFab() {
            fabBtn.classList.remove('wab-open');
            fabMenu.classList.remove('wab-open');
            backdrop.classList.remove('wab-open');
        }

        if (fabBtn) {
            fabBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                fabBtn.classList.contains('wab-open') ? closeFab() : openFab();
            });
        }
        if (backdrop) {
            backdrop.addEventListener('click', closeFab);
        }
        document.querySelectorAll('.wab-fab-item').forEach(function (item) {
            item.addEventListener('click', closeFab);
        });

        // Toggle sidebar on Menu tap
        if (menuBtn) {
            menuBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (typeof $ !== 'undefined') {
                    var isOpen = $('.dashbard-menu-wrap').hasClass('dashbard-menu-wrap_vis');
                    if (isOpen) {
                        $('.dashbard-menu-wrap').removeClass('dashbard-menu-wrap_vis');
                        $('.dashbard-menu-overlay').fadeOut(100);
                    } else {
                        $('.dashbard-menu-wrap').addClass('dashbard-menu-wrap_vis');
                        $('.dashbard-menu-overlay').fadeIn(100);
                    }
                }
            });
        }
    });
</script>