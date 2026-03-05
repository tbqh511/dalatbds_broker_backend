<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\NewsPostApiController;
use App\Http\Controllers\Api\NewsCategoryApiController;
use App\Http\Controllers\Api\NewsTagApiController;
use App\Http\Controllers\Api\PropertyApiController;
use App\Http\Controllers\Api\LeadApiController;
use App\Http\Controllers\Api\DealApiController;
use App\Http\Controllers\Api\DealProductApiController;
use App\Http\Controllers\Api\BookingApiController;
use App\Http\Controllers\Api\CommissionApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



// Artisan::call('migrate');
Route::post('get_system_settings', [ApiController::class, 'get_system_settings']);
// Server-to-server login for automation (requires API_LOGIN_SECRET in .env)
Route::post('login', [ApiController::class, 'login']);
// Check Telegram User for n8n bot
Route::post('check_telegram_user', [ApiController::class, 'check_telegram_user']);
// Route Telegram Web App Login
// Moved to web.php to support Session
// Route::post('/webapp/login', [ApiController::class, 'loginViaMiniApp']);

Route::post('user_signup', [ApiController::class, 'user_signup']);
Route::post('get_languages', [ApiController::class, 'get_languages']);
Route::get('app_payment_status', [ApiController::class, 'app_payment_status']);

// Route::get('paypal', [ApiController::class, 'paypal']);
// Route::get('paypal1', [ApiController::class, 'paypal']);

//HuyTBQH: Add Guest Mode
Route::get('get_facilities', [ApiController::class, 'get_facilities']);

// Public route for get_property_public without JWT middleware
Route::get('get_property_public', [ApiController::class, 'get_property_public']);
//HuyTBQ: Integrations
Route::get('get_locations_wards', [ApiController::class, 'get_locations_wards']);
Route::get('get_locations_streets', [ApiController::class, 'get_locations_streets']);
Route::get('get_categories', [ApiController::class, 'get_categories']);
Route::get('get_payment_settings', [ApiController::class, 'get_payment_settings']);
Route::get('get_count_by_cities_categoris', [ApiController::class, 'get_count_by_cities_categoris']);
Route::get('get_report_reasons', [ApiController::class, 'get_report_reasons']);
Route::get('get_slider', [ApiController::class, 'get_slider']);

// Public News Posts Routes (Read-only)
Route::get('news_posts', [NewsPostApiController::class, 'index']);
Route::get('news_posts/{id}', [NewsPostApiController::class, 'show']);

// Public News Categories Routes (Read-only)
Route::get('news_categories', [NewsCategoryApiController::class, 'index']);
Route::get('news_categories/{id}', [NewsCategoryApiController::class, 'show']);

// Public News Tags Routes (Read-only)
Route::get('news_tags', [NewsTagApiController::class, 'index']);
Route::get('news_tags/{id}', [NewsTagApiController::class, 'show']);

//Play Integrity
//Route::post('verify_integrity', [ApiController::class, 'verifyIntegrity']);
Route::group(['middleware' => ['jwt.verify']], function () {
    // News Posts Routes (Write access) - with Role Permission
    // Admin, Editor can publish. Sales, Customer forced to draft.
    Route::post('news_posts', [NewsPostApiController::class, 'store'])->middleware('role:admin,editor,sales,customer');
    Route::put('news_posts/{id}', [NewsPostApiController::class, 'update'])->middleware('role:admin,editor,sales,customer');
    Route::delete('news_posts/{id}', [NewsPostApiController::class, 'destroy'])->middleware('role:admin,editor');

    // News Categories Routes (Write access) - Admin/Editor Only
    Route::post('news_categories', [NewsCategoryApiController::class, 'store'])->middleware('role:admin,editor');
    Route::put('news_categories/{id}', [NewsCategoryApiController::class, 'update'])->middleware('role:admin,editor');
    Route::delete('news_categories/{id}', [NewsCategoryApiController::class, 'destroy'])->middleware('role:admin,editor');

    // News Tags Routes (Write access) - Admin/Editor Only
    Route::post('news_tags', [NewsTagApiController::class, 'store'])->middleware('role:admin,editor');
    Route::put('news_tags/{id}', [NewsTagApiController::class, 'update'])->middleware('role:admin,editor');
    Route::delete('news_tags/{id}', [NewsTagApiController::class, 'destroy'])->middleware('role:admin,editor');

    // Properties API (New)
    // Task 3.1: Create Property
    Route::post('properties', [PropertyApiController::class, 'store']);
    // Task 3.2: Verify Property (Admin/Operator only)
    Route::patch('properties/{id}/verify', [PropertyApiController::class, 'verify']);


    Route::post('get_property', [ApiController::class, 'get_property']);
    Route::post('update_profile', [ApiController::class, 'update_profile']);
    Route::post('post_property', [ApiController::class, 'post_property']);
    Route::post('update_post_property', [ApiController::class, 'update_post_property']);
    Route::post('delete_property', [ApiController::class, 'delete_property']);
    Route::post('remove_post_images', [ApiController::class, 'remove_post_images']);
    Route::post('set_property_inquiry', [ApiController::class, 'set_property_inquiry']);
    Route::post('set_property_total_click', [ApiController::class, 'set_property_total_click']);
    Route::post('add_favourite', [ApiController::class, 'add_favourite']);
    Route::post('delete_favourite', [ApiController::class, 'delete_favourite']);
    Route::post('delete_user', [ApiController::class, 'delete_user']);
    Route::post('user_purchase_package', [ApiController::class, 'user_purchase_package']);
    Route::post('interested_users', [ApiController::class, 'interested_users']);
    Route::post('delete_advertisement', [ApiController::class, 'delete_advertisement']);
    Route::post('delete_inquiry', [ApiController::class, 'delete_inquiry']);
    Route::post('user_interested_property', [ApiController::class, 'user_interested_property']);
    Route::post('send_message', [ApiController::class, 'send_message']);
    Route::post('update_property_status', [ApiController::class, 'update_property_status']);
    Route::post('delete_chat_message', [ApiController::class, 'delete_chat_message']);
    Route::post('store_advertisement', [ApiController::class, 'store_advertisement']);
    Route::post('add_reports', [ApiController::class, 'add_reports']);
  
    Route::get('get_user_by_id', [ApiController::class, 'get_user_by_id']);
    Route::get('get_property_inquiry', [ApiController::class, 'get_property_inquiry']);
    Route::get('get_notification_list', [ApiController::class, 'get_notification_list']);
    Route::get('get_articles', [ApiController::class, 'get_articles']);
    Route::get('get_advertisement', [ApiController::class, 'get_advertisement']);
    Route::get('get_package', [ApiController::class, 'get_package']);
    Route::get('get_favourite_property', [ApiController::class, 'get_favourite_property']);
    Route::get('get_payment_details', [ApiController::class, 'get_payment_details']);
    
    Route::get('get_limits', [ApiController::class, 'get_limits']);
    Route::get('get_messages', [ApiController::class, 'get_messages']);
    Route::get('get_chats', [ApiController::class, 'get_chats']);
    Route::get('get_nearby_properties', [ApiController::class, 'get_nearby_properties']);
    
    Route::get('paypal', [ApiController::class, 'paypal']);
    Route::get('get_agents_details', [ApiController::class, 'get_agents_details']);
       
    Route::get('get_crm_hosts', [ApiController::class, 'get_crm_hosts']);
    // CRM Customers
    Route::get('customers', [ApiController::class, 'get_customers']);
    Route::get('customers/{id}', [ApiController::class, 'get_customer']);
    Route::post('customers', [ApiController::class, 'create_customer']);
    Route::put('customers/{id}', [ApiController::class, 'update_customer']);
    Route::delete('customers/{id}', [ApiController::class, 'delete_customer']);

    // CRM Leads
    Route::get('leads', [ApiController::class, 'get_leads']);
    Route::get('leads/my-leads', [LeadApiController::class, 'myLeads']); // Task 4.4
    Route::get('leads/{id}', [ApiController::class, 'get_lead']);
    // Route::post('leads', [ApiController::class, 'create_lead']); // Replaced by LeadApiController
    Route::post('leads', [LeadApiController::class, 'store']); // Task 4.1
    Route::put('leads/{id}', [ApiController::class, 'update_lead']);
    Route::delete('leads/{id}', [ApiController::class, 'delete_lead']);
    Route::post('leads/{id}/convert', [ApiController::class, 'convert_lead_to_deal']);
    Route::post('leads/{id}/assign', [LeadApiController::class, 'assign']); // Task 4.2

    // CRM Deals
    // Route::get('deals', [ApiController::class, 'get_deals']); // Replaced by DealApiController
    Route::get('deals', [DealApiController::class, 'index']); // Task 5.3
    // Route::get('deals/{id}', [ApiController::class, 'get_deal']); // Replaced by DealApiController
    Route::get('deals/{id}', [DealApiController::class, 'show']); // Task 5.4
    // Route::post('deals', [ApiController::class, 'create_deal']); // Replaced by DealApiController
    Route::post('deals', [DealApiController::class, 'store']); // Task 5.1 & 5.2
    Route::put('deals/{id}', [ApiController::class, 'update_deal']);
    Route::delete('deals/{id}', [ApiController::class, 'delete_deal']);
    Route::post('deals/{id}/status', [ApiController::class, 'update_deal_status']);

    // CRM Deals Assigned
    Route::post('deals/{id}/assign', [ApiController::class, 'assign_deal']);
    Route::get('deals/{id}/assigned', [ApiController::class, 'get_assigned_deals']);
    Route::delete('deals/{id}/assigned/{assigned_id}', [ApiController::class, 'remove_assigned_deal']);

    // CRM Deal Products
    // Route::post('deals/{id}/products', [ApiController::class, 'add_deal_product']); // Replaced by DealProductApiController
    Route::post('deals/{id}/products', [DealProductApiController::class, 'store']); // Task 6.1
    // Route::get('deals/{id}/products', [ApiController::class, 'get_deal_products']); // Replaced by DealProductApiController
    Route::get('deals/{id}/products', [DealProductApiController::class, 'index']); // Task 6.3
    // Route::put('deals/{id}/products/{product_id}', [ApiController::class, 'update_deal_product']); // Replaced by DealProductApiController
    Route::patch('deals/products/{id}', [DealProductApiController::class, 'update']); // Task 6.2 (Note: using PATCH and ID of product record)
    Route::delete('deals/{id}/products/{product_id}', [ApiController::class, 'delete_deal_product']);

    // CRM Deal Commissions
    // Route::post('deals/{id}/commissions', [ApiController::class, 'add_deal_commission']); // Replaced
    Route::post('deals/{id}/commissions', [CommissionApiController::class, 'store']); // Task 8.1
    // Route::get('deals/{id}/commissions', [ApiController::class, 'get_deal_commission']); // Replaced
    // Route::put('deals/{id}/commissions/{commission_id}', [ApiController::class, 'update_deal_commission']); // Replaced
    Route::patch('commissions/{id}', [CommissionApiController::class, 'update']); // Task 8.2
    Route::delete('deals/{id}/commissions/{commission_id}', [ApiController::class, 'delete_deal_commission']);
    Route::get('commissions/report', [CommissionApiController::class, 'report']); // Task 8.4

    // CRM Bookings
    Route::post('deals/products/{id}/bookings', [BookingApiController::class, 'store']); // Task 7.1
    Route::patch('bookings/{id}', [BookingApiController::class, 'update']); // Task 7.2
    Route::patch('bookings/{id}/reschedule', [BookingApiController::class, 'reschedule']); // Task 7.3

    // Reports and Analytics
    Route::get('reports/leads', [ApiController::class, 'get_leads_report']);
    Route::get('reports/deals', [ApiController::class, 'get_deals_report']);
    Route::get('reports/customers', [ApiController::class, 'get_customers_statistics']);
});
