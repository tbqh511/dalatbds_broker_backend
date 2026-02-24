<div class="dashboard-footer">
    {{-- <div class="dashboard-footer-links fl-wrap">
        <a href="{{ route('webapp.add_listing') }}" class="btn color-bg">Thêm BĐS</a>
        <a href="#" class="btn color-bg">Thêm Khách</a>
    </div> --}}
    <a href="#main" class="dashbord-totop custom-scroll-link"><i class="fas fa-caret-up"></i></a>
</div>

<!-- FAB Backdrop -->
<div id="menu-backdrop" class="fab-backdrop"></div>

<!-- FAB Container -->
<div id="fab-container">
    
    <!-- Main Button -->
    <button id="main-fab" class="btn-main btn-pulse" aria-label="Thêm mới">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Sub Buttons Container -->
    <div class="fab-sub-buttons">
        
        <!-- Sub Button 1: Khách -->
        <a href="{{ route('webapp.add_customer') }}" class="menu-item item-1">
            <div class="menu-item-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <span class="menu-item-text">Thêm Khách</span>
        </a>

        <!-- Sub Button 2: Thêm BĐS -->
        <a href="{{ route('webapp.add_listing') }}" class="menu-item item-2">
            <div class="menu-item-icon">
                <i class="fas fa-building"></i>
            </div>
            <span class="menu-item-text">Thêm BĐS</span>
        </a>
        
    </div>
    
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fabContainer = document.getElementById('fab-container');
        const mainFab = document.getElementById('main-fab');
        const backdrop = document.getElementById('menu-backdrop');

        // Toggle Menu Function
        const toggleMenu = () => {
            fabContainer.classList.toggle('menu-open');
            backdrop.classList.toggle('menu-open-backdrop');
        };

        // Click on main FAB
        if(mainFab) {
             mainFab.addEventListener('click', toggleMenu);
        }

        // Click outside or on backdrop to close
        document.addEventListener('click', (e) => {
            if (fabContainer && fabContainer.classList.contains('menu-open') &&
                !fabContainer.contains(e.target) && e.target !== mainFab && !mainFab.contains(e.target)) {
                fabContainer.classList.remove('menu-open');
                backdrop.classList.remove('menu-open-backdrop');
            }
        });
        
        // Also close when clicking backdrop specifically
        if(backdrop) {
            backdrop.addEventListener('click', () => {
                fabContainer.classList.remove('menu-open');
                backdrop.classList.remove('menu-open-backdrop');
            });
        }

        // Close menu when clicking items
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                fabContainer.classList.remove('menu-open');
                backdrop.classList.remove('menu-open-backdrop');
            });
        });
    });
</script>
