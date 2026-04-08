<?php

use App\Http\Controllers\AdvertisementController;

use App\Http\Controllers\AreaMeasurementController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BedroomController;
use App\Http\Controllers\FrontEndNewsController;
use App\Http\Controllers\FrontEndProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PropertController;
use App\Http\Controllers\PropertysInquiryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\HouseTypeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\OutdoorFacilityController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportReasonController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\FrontEndHomeController;
use App\Http\Controllers\FrontEndPropertiesController;
use App\Http\Controllers\FrontEndAgentsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\PostTagController;
use App\Http\Controllers\TelegramWebAppController;
use App\Http\Controllers\CrmLeadController;
use App\Http\Controllers\CrmLeadActivityController;
use App\Http\Controllers\SaleLeadController;
use App\Http\Controllers\SaleAdminController;
use App\Models\Payments;
use App\Models\PropertysInquiry;
use Illuminate\Support\Facades\Artisan;


use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\NewsCategoryApiController;
use App\Http\Controllers\Api\NewsTagApiController;
use App\Http\Controllers\Api\PropertyApiController;
use App\Http\Controllers\Api\LeadApiController;
use App\Http\Controllers\Api\DealApiController;
use App\Http\Controllers\Api\CommissionApiController;
use App\Http\Controllers\WebAppNotificationController;
use Illuminate\Http\Request;

/*
 |--------------------------------------------------------------------------
 | Web Routes
 | Here is where you can register web routes for your application. These
 | routes are loaded by the RouteServiceProvider within a group which
 | contains the "web" middleware group. Now create something great!
 |
 */

// Telegram WebApp Login (Moved from API to support Session)
Route::post('/api/webapp/login', [ApiController::class , 'loginViaMiniApp']);

// Telegram WebApp Auth Redirect (form POST → sets session cookie in navigation response → reliable on iOS WKWebView)
Route::post('/webapp/auth', [TelegramWebAppController::class, 'authRedirect'])->name('webapp.auth');


//HuyTBQ: Route for Frontend Page
// Route::get('/', function () {
//     return view('coming_soon');
// });

Route::get('/', [FrontEndHomeController::class , 'index'])->name('index');
Route::get('/webapp/temp', [TelegramWebAppController::class , 'tempui'])->name('webapp');

// Lead assignment via signed URL (no session required — opened from Telegram group button)
Route::get('/webapp/leads/{id}/assign', [CrmLeadController::class, 'assignPage'])
    ->name('webapp.leads.assign-page')
    ->middleware('signed');
Route::post('/webapp/leads/{id}/assign', [CrmLeadController::class, 'doAssign'])
    ->name('webapp.leads.do-assign')
    ->middleware('signed');
Route::group(['middleware' => 'telegram.webapp'], function () {
    Route::get('/webapp/logout', function () {
        \Illuminate\Support\Facades\Auth::guard('webapp')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/webapp');
    })->name('webapp.logout');
    Route::get('/webapp', [TelegramWebAppController::class , 'index'])->name('webapp');
    Route::get('/webapp/home-feed', [TelegramWebAppController::class , 'homeFeed'])->name('webapp.home_feed');
    Route::get('/webapp/search/suggestions', [TelegramWebAppController::class, 'searchSuggestions'])->name('webapp.search.suggestions');
    Route::get('/webapp/search/results', [TelegramWebAppController::class, 'searchResults'])->name('webapp.search.results');
    Route::get('/webapp/search/results/map', [TelegramWebAppController::class, 'searchResultsMap'])->name('webapp.search.results.map');
    Route::get('/webapp/search/leads', [TelegramWebAppController::class, 'searchLeads'])->name('webapp.search.leads');
    Route::get('/webapp/search/areas', [TelegramWebAppController::class, 'searchAreas'])->name('webapp.search.areas');
    Route::post('/webapp/log-action', [TelegramWebAppController::class, 'logAction'])->name('webapp.log_action');
    Route::get('/webapp/action-logs', [TelegramWebAppController::class, 'actionLogs'])->name('webapp.action_logs');
    Route::get('/webapp/property/{id}/json', [TelegramWebAppController::class, 'propertyDetailJson'])->name('webapp.property.json');
    Route::get('/webapp/properties/nearby', [TelegramWebAppController::class, 'nearbyProperties'])->name('webapp.properties.nearby');
    Route::post('/webapp/favourite/toggle', [TelegramWebAppController::class, 'toggleFavourite'])->name('webapp.favourite.toggle');
    Route::get('/webapp/favourites/json', [TelegramWebAppController::class, 'likedProperties'])->name('webapp.favourites.json');
    Route::get('/webapp/api/my-properties', [TelegramWebAppController::class, 'myPropertiesApi'])->name('webapp.api.my_properties');
    Route::get('/webapp/api/my-customers', [TelegramWebAppController::class, 'myCustomersApi'])->name('webapp.api.my_customers');
    Route::get('/webapp/api/leads', [TelegramWebAppController::class, 'myLeadsApi'])->name('webapp.api.leads');
    Route::get('/webapp/api/deals', [TelegramWebAppController::class, 'myDealsApi'])->name('webapp.api.deals');
    Route::get('/webapp/api/commissions', [TelegramWebAppController::class, 'myCommissionsApi'])->name('webapp.api.commissions');
    Route::get('/webapp/referral/data', [TelegramWebAppController::class, 'referralApi'])->name('webapp.referral.data');
    Route::post('/webapp/referral/claim', [TelegramWebAppController::class, 'claimReferral'])->name('webapp.referral.claim');
    Route::get('/webapp/profile', [TelegramWebAppController::class , 'profile'])->name('webapp.profile');
    Route::post('/webapp/profile', [TelegramWebAppController::class , 'updateProfile'])->name('webapp.profile.update');
    Route::post('/webapp/profile/avatar', [TelegramWebAppController::class , 'updateAvatar'])->name('webapp.profile.avatar');
    Route::get('/webapp/messages', [TelegramWebAppController::class , 'messages'])->name('webapp.messages');
    Route::get('/webapp/listings', [TelegramWebAppController::class , 'listings'])->name('webapp.listings');
    Route::get('/webapp/agents', [TelegramWebAppController::class , 'agents'])->name('webapp.agents');
    Route::get('/webapp/bookings', [TelegramWebAppController::class , 'bookings'])->name('webapp.bookings');
    Route::get('/webapp/api/bookings', [TelegramWebAppController::class, 'apiGetBookings'])->name('webapp.api.bookings');
    Route::patch('/webapp/api/bookings/{id}/result', [TelegramWebAppController::class, 'apiUpdateBookingResult'])->name('webapp.api.bookings.result');
    Route::patch('/webapp/api/bookings/{id}/reschedule', [TelegramWebAppController::class, 'apiRescheduleBooking'])->name('webapp.api.bookings.reschedule');
    Route::patch('/webapp/api/bookings/{id}/cancel', [TelegramWebAppController::class, 'apiCancelBooking'])->name('webapp.api.bookings.cancel');
    Route::get('/webapp/reviews', [TelegramWebAppController::class , 'reviews'])->name('webapp.reviews');
    Route::post('/webapp/support/ticket', [TelegramWebAppController::class, 'submitSupportTicket'])->name('webapp.support.ticket');
    Route::get('/webapp/notifications/settings', [TelegramWebAppController::class, 'getNotifSettings'])->name('webapp.notif.settings.get');
    Route::post('/webapp/notifications/settings', [TelegramWebAppController::class, 'saveNotifSettings'])->name('webapp.notif.settings.save');

    // In-app notifications API
    Route::get('/webapp/api/notifications', [WebAppNotificationController::class, 'index'])->name('webapp.api.notifications');
    Route::get('/webapp/api/notifications/unread-count', [WebAppNotificationController::class, 'unreadCount'])->name('webapp.api.notifications.unread');
    Route::post('/webapp/api/notifications/{id}/read', [WebAppNotificationController::class, 'markRead'])->name('webapp.api.notifications.read');
    Route::post('/webapp/api/notifications/read-all', [WebAppNotificationController::class, 'markAllRead'])->name('webapp.api.notifications.read-all');

    // Admin user management routes
    Route::middleware(['webapp.role:admin'])->group(function () {
        Route::get('/webapp/api/admin/reports', [SaleAdminController::class, 'getAdminReportsData'])->name('webapp.admin.reports');
        Route::get('/webapp/api/admin/users', [TelegramWebAppController::class, 'adminUsersApi'])->name('webapp.admin.users');
        Route::post('/webapp/api/admin/users/{id}/approve', [TelegramWebAppController::class, 'adminApproveUser'])->name('webapp.admin.approve');
        Route::post('/webapp/api/admin/users/{id}/reject', [TelegramWebAppController::class, 'adminRejectUser'])->name('webapp.admin.reject');
        Route::post('/webapp/api/admin/users/{id}/approve-temp', [TelegramWebAppController::class, 'adminApproveTempUser'])->name('webapp.admin.approve-temp');
        Route::patch('/webapp/api/admin/users/{id}/role', [TelegramWebAppController::class, 'adminChangeUserRole'])->name('webapp.admin.change-role');
        Route::patch('/webapp/api/admin/users/{id}/toggle-active', [TelegramWebAppController::class, 'adminToggleUserActive'])->name('webapp.admin.toggle');
        Route::delete('/webapp/api/admin/users/{id}', [TelegramWebAppController::class, 'adminDeleteUser'])->name('webapp.admin.delete');
        // Market Prices CRUD (admin only)
        Route::get('/webapp/api/admin/market-prices', [TelegramWebAppController::class, 'adminMarketPricesIndex'])->name('webapp.admin.market-prices.index');
        Route::post('/webapp/api/admin/market-prices', [TelegramWebAppController::class, 'adminMarketPricesStore'])->name('webapp.admin.market-prices.store');
        Route::put('/webapp/api/admin/market-prices/{id}', [TelegramWebAppController::class, 'adminMarketPricesUpdate'])->name('webapp.admin.market-prices.update');
        Route::delete('/webapp/api/admin/market-prices/{id}', [TelegramWebAppController::class, 'adminMarketPricesDestroy'])->name('webapp.admin.market-prices.destroy');
    });
    // Property approval routes (bds_admin + admin)
    Route::middleware(['webapp.role:bds_admin,admin'])->group(function () {
        Route::get('/webapp/api/admin/properties', [TelegramWebAppController::class, 'adminPropertiesApi'])->name('webapp.admin.properties');
        Route::get('/webapp/api/admin/properties/{id}', [TelegramWebAppController::class, 'adminPropertyDetail'])->name('webapp.admin.properties.detail');
        Route::post('/webapp/api/admin/properties/{id}/approve', [TelegramWebAppController::class, 'adminApproveProperty'])->name('webapp.admin.properties.approve');
        Route::post('/webapp/api/admin/properties/{id}/reject', [TelegramWebAppController::class, 'adminRejectProperty'])->name('webapp.admin.properties.reject');
        Route::post('/webapp/api/admin/properties/{id}/hide', [TelegramWebAppController::class, 'adminHideProperty'])->name('webapp.admin.properties.hide');
        Route::post('/webapp/api/admin/properties/{id}/restore', [TelegramWebAppController::class, 'adminRestoreProperty'])->name('webapp.admin.properties.restore');
    });
    // Commission approval routes (admin only)
    Route::middleware(['webapp.role:admin'])->group(function () {
        Route::get('/webapp/api/admin/commissions', [TelegramWebAppController::class, 'adminCommissionsApi'])->name('webapp.admin.commissions');
        Route::post('/webapp/api/admin/commissions/{id}/approve', [TelegramWebAppController::class, 'adminApproveCommission'])->name('webapp.admin.commissions.approve');
        Route::post('/webapp/api/admin/commissions/{id}/advance', [TelegramWebAppController::class, 'adminAdvanceCommission'])->name('webapp.admin.commissions.advance');
        Route::post('/webapp/api/admin/commissions/{id}/hold', [TelegramWebAppController::class, 'adminHoldCommission'])->name('webapp.admin.commissions.hold');
    });
    // Routes yêu cầu phải có số điện thoại
    Route::middleware(['webapp.require_phone'])->group(function () {
        // Đăng tin BĐS
        Route::get('/webapp/add-listing', [TelegramWebAppController::class , 'addListing'])->name('webapp.add_listing');
        Route::post('/webapp/submit-listing', [TelegramWebAppController::class , 'submitForm'])->name('webapp.submit_listing');
        Route::get('/webapp/listing-success', [TelegramWebAppController::class , 'addListingSuccess'])->name('webapp.add_listing_success');
        Route::get('/webapp/check-host-phone', [TelegramWebAppController::class , 'checkHostPhone'])->name('webapp.check_host_phone');
        Route::delete('/webapp/listings/{id}', [TelegramWebAppController::class , 'destroy'])->name('webapp.listings.destroy');
        Route::patch('/webapp/listings/{id}/toggle', [TelegramWebAppController::class , 'toggleStatus'])->name('webapp.listings.toggle');
        Route::patch('/webapp/listings/{id}/resubmit', [TelegramWebAppController::class , 'resubmitProperty'])->name('webapp.listings.resubmit');
        Route::get('/webapp/edit-listing/{id}', [TelegramWebAppController::class , 'editListing'])->name('webapp.edit_listing');
        Route::post('/webapp/update-listing/{id}', [TelegramWebAppController::class , 'updateForm'])->name('webapp.update_listing');

        // CRM Leads Routes
        Route::get('/webapp/leads', [CrmLeadController::class , 'index'])->name('webapp.leads');
        Route::get('/webapp/leads/create', [CrmLeadController::class , 'create'])->name('webapp.leads.create');
        Route::post('/webapp/leads', [CrmLeadController::class , 'store'])->name('webapp.leads.store');
        Route::get('/webapp/leads/{id}', [CrmLeadController::class , 'show'])->name('webapp.leads.show');
        Route::get('/webapp/leads/{id}/edit', [CrmLeadController::class , 'edit'])->name('webapp.leads.edit');
        Route::put('/webapp/leads/{id}', [CrmLeadController::class , 'update'])->name('webapp.leads.update');
        Route::delete('/webapp/leads/{id}', [CrmLeadController::class , 'destroy'])->name('webapp.leads.destroy');
        Route::patch('/webapp/leads/{id}/status', [CrmLeadController::class , 'updateStatus'])->name('webapp.leads.update-status');

        // Sale routes (sale + sale_admin only)
        Route::middleware(['webapp.role:sale,sale_admin'])->group(function () {
            Route::get('/webapp/sale/leads', [SaleLeadController::class, 'index'])->name('webapp.sale.leads');
            Route::post('/webapp/leads/{id}/activities', [CrmLeadActivityController::class, 'store'])->name('webapp.leads.activities.store');
            Route::post('/webapp/leads/{id}/deal', [CrmLeadController::class, 'createDeal'])->name('webapp.leads.create-deal');
        });

        // Sale Admin routes (sale_admin only)
        Route::middleware(['webapp.role:sale_admin'])->group(function () {
            Route::post('/webapp/leads/bulk-assign', [CrmLeadController::class, 'bulkAssign'])->name('webapp.leads.bulk-assign');
            Route::post('/webapp/leads/{id}/assign-sale', [CrmLeadController::class, 'assignSale'])->name('webapp.leads.assign-sale');
            Route::get('/webapp/sale-admin', [SaleAdminController::class, 'index'])->name('webapp.sale-admin');
            Route::get('/webapp/sale-admin/assign-data', [SaleAdminController::class, 'getAssignData'])->name('webapp.sale-admin.assign-data');
            Route::get('/webapp/api/kpi-team', [SaleAdminController::class, 'getKpiTeamData'])->name('webapp.api.kpi-team');
            Route::post('/webapp/api/kpi-team/send-support', [SaleAdminController::class, 'sendSupportReminder'])->name('webapp.api.kpi-team.support');
        });

        // Add Customer (Custom UI)
        Route::get('/webapp/add-customer', [TelegramWebAppController::class , 'addCustomer'])->name('webapp.add_customer');
        Route::post('/webapp/add-customer', [TelegramWebAppController::class , 'storeCustomer'])->name('webapp.store_customer');
    });

    // Feed
    Route::get('/webapp/feed', [TelegramWebAppController::class , 'feed'])->name('webapp.feed');
});

// Public referral landing page
Route::get('/ref/{code}', [TelegramWebAppController::class, 'referralLanding'])->name('referral.landing');

// Smart property share redirect
Route::get('/share/p/{id}', [TelegramWebAppController::class, 'propertyShareRedirect'])->name('property.share.redirect');

//property controller
Route::get('/property/{id}', [FrontEndPropertiesController::class , 'getPropertyById'])->name('property.showid')->where('id', '[0-9]+');
Route::get('/bds/{slug}', [FrontEndPropertiesController::class , 'show'])->name('bds.show');
Route::get('/autocomplete/street', [FrontEndPropertiesController::class , 'autocompleteStreet'])->name('autocomplete.street');

// Route for displaying the detail of a property


// Route for displaying a listing of the properties with search variables
Route::get('/properties', [FrontEndPropertiesController::class , 'index'])->name('properties.index');

//Category menu
Route::get('/nha-ban', [FrontEndPropertiesController::class , 'index']);

Route::get('/dat-ban', [FrontEndPropertiesController::class , 'index']);


//News Layout
// Route::get('/new/{id}', [FrontEndNewsController::class,'show'])->name('new.showid');
// Route::get('/news', [FrontEndNewsController::class,'index'])->name('news.index');

Route::get('/tin-tuc', [FrontEndNewsController::class , 'index'])->name('news.index');
Route::get('/tin-tuc/danh-muc/{slug}', [FrontEndNewsController::class , 'category'])->name('news.category');
Route::get('/tin-tuc/tag/{slug}', [FrontEndNewsController::class , 'tag'])->name('news.tag');
Route::get('/tin-tuc/{slug}', [FrontEndNewsController::class , 'show'])->name('news.show');
Route::get('/tin-tuc/nam/{year}/thang/{month}', [FrontEndNewsController::class , 'month'])->name('news.month');


//agent layout
Route::get('/agent/{id}', [FrontEndAgentsController::class , 'getAgentById'])->name('agent.showid');


Route::get('/agents', [FrontEndAgentsController::class , 'index'])->name('agents.index');


Route::get('/gioi-thieu', [FrontEndHomeController::class , 'about'])->name('about');

Route::get('/lien-he', function () {
    return view('contact');
});
Route::fallback(function () {
    return view('404');
});

// //Create properties layout
// Route::get('/dang-tin', function () {
//     return view('product_create');
// });
//HuyTBQ: End - Route for Frontend Page


Route::get('/admin', function () {
    return view('auth.login');
});

Route::get('/new-migrate', function () {
    Artisan::call('migrate');
    return redirect()->back();
});


Route::get('/fresh-migrate', function () {
    Artisan::call('migrate:fresh');
    return redirect()->back();
});
Route::get('customer-privacy-policy', [SettingController::class , 'show_privacy_policy'])->name('customer-privacy-policy');


Route::get('customer-terms-conditions', [SettingController::class , 'show_terms_conditions'])->name('customer-terms-conditions');


Route::get('/telegram/leads/{id}', [LeadApiController::class, 'showWebApp'])->name('telegram.leads.show');
Route::post('/telegram/leads/{id}/update', [LeadApiController::class, 'updateFromWebApp'])->name('telegram.leads.update');

Route::get('/telegram/deals/{id}', [DealApiController::class, 'showWebApp'])->name('telegram.deals.show');
Route::get('/telegram/commissions', [CommissionApiController::class, 'showWebApp'])->name('telegram.commissions.index');

Auth::routes();

Route::get('privacypolicy', [HomeController::class , 'privacy_policy']);
Route::post('/webhook/razorpay', [WebhookController::class , 'razorpay']);
Route::post('/webhook/paystack', [WebhookController::class , 'paystack']);
Route::post('/webhook/paypal', [WebhookController::class , 'paypal']);
Route::post('/webhook/stripe', [WebhookController::class , 'stripe']);



Route::middleware(['auth', 'checklogin'])->group(function () {
    Route::group(['middleware' => 'language'], function () {

            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('view:cache');


            Route::get('dashboard', [App\Http\Controllers\HomeController::class , 'blank_dashboard'])->name('dashboard');
            Route::get('/home', [App\Http\Controllers\HomeController::class , 'index'])->name('home');
            Route::get('about-us', [SettingController::class , 'index']);
            Route::get('privacy-policy', [SettingController::class , 'index']);
            Route::get('terms-conditions', [SettingController::class , 'index']);
            Route::get('system-settings', [SettingController::class , 'index']);
            Route::get('firebase_settings', [SettingController::class , 'index']);
            Route::get('system_version', [SettingController::class , 'index']);
            Route::post('firebase-settings', [SettingController::class , 'firebase_settings']);
            Route::get('system_version', [SettingController::class , 'system_version']);

            Route::post('system_version_setting', [SettingController::class , 'system_version_setting']);

            /// START :: HOME ROUTE
            Route::get('change-password', [App\Http\Controllers\HomeController::class , 'change_password'])->name('changepassword');
            Route::post('check-password', [App\Http\Controllers\HomeController::class , 'check_password'])->name('checkpassword');
            Route::post('store-password', [App\Http\Controllers\HomeController::class , 'store_password'])->name('changepassword.store');
            Route::get('changeprofile', [HomeController::class , 'changeprofile'])->name('changeprofile');
            Route::post('updateprofile', [HomeController::class , 'update_profile'])->name('updateprofile');
            Route::post('firebase_messaging_settings', [HomeController::class , 'firebase_messaging_settings'])->name('firebase_messaging_settings');

            /// END :: HOME ROUTE
    
            /// START :: SETTINGS ROUTE
    
            Route::post('settings', [SettingController::class , 'settings']);
            Route::post('set_settings', [SettingController::class , 'system_settings']);
            /// END :: SETTINGS ROUTE
    
            /// START :: LANGUAGES ROUTE
    

            Route::resource('language', LanguageController::class);
            Route::get('language_list', [LanguageController::class , 'show']);
            Route::post('language_update', [LanguageController::class , 'update'])->name('language_update');
            Route::get('language-destory/{id}', [LanguageController::class , 'destroy'])->name('language.destroy');
            Route::get('set-language/{lang}', [LanguageController::class , 'set_language']);
            Route::get('downloadPanelFile', [LanguageController::class , 'downloadPanelFile'])->name('downloadPanelFile');
            Route::get('downloadAppFile', [LanguageController::class , 'downloadAppFile'])->name('downloadAppFile');
            /// END :: LANGUAGES ROUTE
    
            /// START :: PAYMENT ROUTE
    
            Route::get('getPaymentList', [PaymentController::class , 'get_payment_list']);
            Route::get('payment', [PaymentController::class , 'index']);
            /// END :: PAYMENT ROUTE
    
            /// START :: USER ROUTE
    
            Route::resource('users', UserController::class);
            Route::post('users-update', [UserController::class , 'update']);
            Route::post('users-reset-password', [UserController::class , 'resetpassword']);
            Route::get('userList', [UserController::class , 'userList']);

            /// END :: PAYMENT ROUTE
    
            /// START :: PAYMENT ROUTE
    
            Route::resource('customer', CustomersController::class);
            Route::get('customerList', [CustomersController::class , 'customerList']);
            Route::post('customerstatus', [CustomersController::class , 'update'])->name('customer.customerstatus');
            Route::patch('customer/{id}/role', [CustomersController::class , 'updateRole'])->name('customer.updaterole');
            Route::patch('customer/{id}/referrer', [CustomersController::class , 'updateReferrer'])->name('customer.updatereferrer');
            /// END :: CUSTOMER ROUTE
    
            /// START :: SLIDER ROUTE
    
            Route::resource('slider', SliderController::class);
            Route::post('slider-order', [SliderController::class , 'update'])->name('slider.slider-order');
            Route::get('slider-destory/{id}', [SliderController::class , 'destroy'])->name('slider.destroy');
            Route::get('get-property-by-category', [SliderController::class , 'getPropertyByCategory'])->name('slider.getpropertybycategory');
            Route::get('sliderList', [SliderController::class , 'sliderList']);
            /// END :: SLIDER ROUTE
    
            /// START :: ARTICLE ROUTE
    
            Route::resource('article', ArticleController::class);
            Route::get('article_list', [ArticleController::class , 'show']);
            Route::get('article-destory/{id}', [ArticleController::class , 'destroy'])->name('article.destroy');
            /// END :: ARTICLE ROUTE
    
            /// START :: POSTS ROUTE
            // Post Categories - Must be defined BEFORE posts resource to avoid conflict with posts/{id}
            Route::resource('posts/categories', PostCategoryController::class)->names('admin.posts.categories');

            // Post Tags - Must be defined BEFORE posts resource
            Route::resource('posts/tags', PostTagController::class)->names('admin.posts.tags');

            Route::resource('posts', PostController::class)->names('admin.posts');
            Route::get('posts-list', [PostController::class , 'getPostsList'])->name('admin.posts.list');
            /// END :: POSTS ROUTE
    
            /// START :: ADVERTISEMENT ROUTE
    
            Route::resource('featured_properties', AdvertisementController::class);
            Route::get('featured_properties_list', [AdvertisementController::class , 'show']);
            Route::post('featured_properties-status', [AdvertisementController::class , 'updateStatus'])->name('featured_properties.updateadvertisementstatus');
            Route::post('adv-status-update', [AdvertisementController::class , 'update'])->name('adv-status-update');
            /// END :: ADVERTISEMENT ROUTE
    
            /// START :: PACKAGE ROUTE
    
            Route::resource('package', PackageController::class);
            Route::get('package_list', [PackageController::class , 'show']);
            Route::post('package-update', [PackageController::class , 'update']);
            Route::post('package-status', [PackageController::class , 'updatestatus'])->name('package.updatestatus');
            Route::get('get_user_purchased_packages', [PackageController::class , function () {
                    return view('packages.users_packages');
                }
                ]);

                Route::get('get_user_package_list', [PackageController::class , 'get_user_package_list']);

                /// END :: PACKAGE ROUTE
        

                /// START :: CATEGORYW ROUTE
        
                Route::resource('categories', CategoryController::class);
                Route::get('categoriesList', [CategoryController::class , 'categoryList']);
                Route::post('categories-update', [CategoryController::class , 'update']);
                Route::post('categories-status', [CategoryController::class , 'updateCategory'])->name('customer.categoriesstatus');
                /// END :: CATEGORYW ROUTE
        

                /// START :: PARAMETER FACILITY ROUTE
        
                Route::resource('parameters', ParameterController::class);
                Route::get('parameter-list', [ParameterController::class , 'show']);
                Route::post('parameter-update', [ParameterController::class , 'update']);

                /// END :: PARAMETER FACILITY ROUTE
        
                /// START :: OUTDOOR FACILITY ROUTE
        
                Route::resource('outdoor_facilities', OutdoorFacilityController::class);
                Route::get('facility-list', [OutdoorFacilityController::class , 'show']);
                Route::post('facility-update', [OutdoorFacilityController::class , 'update']);
                Route::get('facility-delete/{id}', [OutdoorFacilityController::class , 'destroy'])->name('outdoor_facilities.destroy');
                /// END :: OUTDOOR FACILITY ROUTE
        

                /// START :: PROPERTY ROUTE
                Route::resource('property', PropertController::class);
                Route::get('getPropertyList', [PropertController::class , 'getPropertyList']);
                Route::post('property-status', [PropertController::class , 'updateStatus'])->name('property.updatepropertystatus');
                Route::post('property-gallery', [PropertController::class , 'removeGalleryImage'])->name('property.removeGalleryImage');
                Route::get('get-state-by-country', [PropertController::class , 'getStatesByCountry'])->name('property.getStatesByCountry');
                Route::get('property-destory/{id}', [PropertController::class , 'destroy'])->name('property.destroy');

                Route::get('updateFCMID', [UserController::class , 'updateFCMID']);
                /// END :: PROPERTY ROUTE
        

                /// START :: PROPERTY INQUIRY
                Route::resource('property-inquiry', PropertysInquiryController::class);
                Route::get('getPropertyInquiryList', [PropertysInquiryController::class , 'getPropertyInquiryList']);
                Route::post('property-inquiry-status', [PropertysInquiryController::class , 'updateStatus'])->name('property-inquiry.updateStatus');

                /// ENND :: PROPERTY INQUIRY
                /// START :: REPORTREASON
                Route::resource('report-reasons', ReportReasonController::class);
                Route::get('report-reasons-list', [ReportReasonController::class , 'show']);
                Route::post('report-reasons-update', [ReportReasonController::class , 'update']);
                Route::get('report-reasons-destroy/{id}', [ReportReasonController::class , 'destroy'])->name('reasons.destroy');

                Route::get('users_reports', [ReportReasonController::class , 'users_reports']);

                Route::get('user_reports_list', [ReportReasonController::class , 'user_reports_list']);








                /// END :: REPORTREASON
        




                Route::resource('property-inquiry', PropertysInquiryController::class);


                /// START :: CHAT ROUTE
        
                Route::get('getChatList', [ChatController::class , 'getChats']);
                Route::post('store_chat', [ChatController::class , 'store']);
                Route::get('getAllMessage', [ChatController::class , 'getAllMessage']);
                /// END :: CHAT ROUTE
        

                /// START :: NOTIFICATION
                Route::resource('notification', NotificationController::class);
                Route::get('notificationList', [NotificationController::class , 'notificationList']);
                Route::get('notification-delete', [NotificationController::class , 'destroy']);
                Route::post('notification-multiple-delete', [NotificationController::class , 'multiple_delete']);
                /// END :: NOTIFICATION
        
                Route::get('chat', function () {
                    return view('chat');
                }
                );

                Route::get('calculator', function () {
                    return view('Calculator.calculator');
                }
                );
            }
            );
        });

Auth::routes();