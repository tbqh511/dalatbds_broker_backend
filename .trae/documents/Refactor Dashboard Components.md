I will refactor the dashboard Blade templates to reduce code duplication and improve maintainability by extracting common UI elements into reusable components.

### 1. Identify Common Components
I have analyzed the 8 dashboard files and identified the following reusable sections:
- **Sidebar Menu** (`.dashbard-menu-wrap`): Contains the main navigation, user profile links, and listing management links. Identical structure across all pages with dynamic "active" state.
- **Top Header** (`.dashboard-title`): Contains the page title, user avatar/greeting, logout button, and Tariff Plan info. The page title is the only variable part.
- **Mobile Menu Button** (`.dashboard-menu-btn`): The button to toggle the menu on mobile devices.
- **Footer** (`.dashboard-footer`): Contains useful links and the scroll-to-top button.

### 2. Create Reusable Components
I will create a new directory `resources/views/components/dashboard/` and extract the code into these files:

1.  **`resources/views/components/dashboard/sidebar.blade.php`**
    -   Will contain the sidebar navigation logic.
    -   **Logic**: Use `request()->routeIs('route.name')` to automatically set the `user-profile-act` class for the active menu item.
2.  **`resources/views/components/dashboard/header.blade.php`**
    -   Will contain the header section.
    -   **Inputs**: Accepts a `$title` variable for the page title.
    -   **Logic**: Uses `auth()->user()` for user data.
3.  **`resources/views/components/dashboard/mobile_btn.blade.php`**
    -   Contains the mobile menu toggle button.
4.  **`resources/views/components/dashboard/footer.blade.php`**
    -   Contains the footer links and copyright info.

### 3. Refactor Existing Views
I will update all 8 files (`dashboard_home`, `dashboard_add_listing`, `dashboard_agents`, `dashboard_bookings`, `dashboard_listings`, `dashboard_messages`, `dashboard_myprofile`, `dashboard_reviews`) to use these components.

**New Structure Example:**
```blade
<div class="content">
    <div class="dashbard-menu-overlay"></div>
    
    @include('components.dashboard.sidebar')

    <div class="dashboard-content">
        @include('components.dashboard.mobile_btn')
        
        <div class="container dasboard-container">
            @include('components.dashboard.header', ['title' => 'Tiêu đề trang'])
            
            <!-- Unique Page Content -->
            <div class="dasboard-wrapper fl-wrap">
                ...
            </div>
            
            @include('components.dashboard.footer')
        </div>
    </div>
</div>
```

### 4. Implementation Steps
1.  Create the `resources/views/components/dashboard` directory.
2.  Extract code from `dashboard_home.blade.php` to create the 4 component files.
3.  Implement dynamic "active" class logic in `sidebar.blade.php`.
4.  Refactor `dashboard_home.blade.php` first to verify the structure.
5.  Apply the refactoring to the remaining 7 files.
6.  Verify syntax and structure compatibility.