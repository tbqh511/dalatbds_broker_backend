<?php

use App\Http\Controllers\ApiController;
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
Route::post('user_signup', [ApiController::class, 'user_signup']);
Route::post('get_languages', [ApiController::class, 'get_languages']);
Route::get('app_payment_status', [ApiController::class, 'app_payment_status']);

// Route::get('paypal', [ApiController::class, 'paypal']);
// Route::get('paypal1', [ApiController::class, 'paypal']);


//HuyTBQH: Add Guest Mode
Route::get('get_facilities', [ApiController::class, 'get_facilities']);
//HuyTBQ: Integrations
Route::get('get_locations_wards', [ApiController::class, 'get_locations_wards']);
Route::get('get_locations_streets', [ApiController::class, 'get_locations_streets']);
Route::get('get_categories', [ApiController::class, 'get_categories']);
Route::get('get_payment_settings', [ApiController::class, 'get_payment_settings']);
Route::get('get_count_by_cities_categoris', [ApiController::class, 'get_count_by_cities_categoris']);
Route::get('get_report_reasons', [ApiController::class, 'get_report_reasons']);
Route::get('get_slider', [ApiController::class, 'get_slider']);


Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('get_property', [ApiController::class, 'get_property']);

    Route::post('update_profile', [ApiController::class, 'update_profile']);
    
    Route::post('post_property', [ApiController::class, 'post_property']);
    Route::post('update_post_property', [ApiController::class, 'update_post_property']);
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
    Route::get('leads/{id}', [ApiController::class, 'get_lead']);
    Route::post('leads', [ApiController::class, 'create_lead']);
    Route::put('leads/{id}', [ApiController::class, 'update_lead']);
    Route::delete('leads/{id}', [ApiController::class, 'delete_lead']);
    Route::post('leads/{id}/convert', [ApiController::class, 'convert_lead_to_deal']);

    // CRM Deals
    Route::get('deals', [ApiController::class, 'get_deals']);
    Route::get('deals/{id}', [ApiController::class, 'get_deal']);
    Route::post('deals', [ApiController::class, 'create_deal']);
    Route::put('deals/{id}', [ApiController::class, 'update_deal']);
    Route::delete('deals/{id}', [ApiController::class, 'delete_deal']);
    Route::post('deals/{id}/status', [ApiController::class, 'update_deal_status']);

    // CRM Deals Assigned
    Route::post('deals/{id}/assign', [ApiController::class, 'assign_deal']);
    Route::get('deals/{id}/assigned', [ApiController::class, 'get_assigned_deals']);
    Route::delete('deals/{id}/assigned/{assigned_id}', [ApiController::class, 'remove_assigned_deal']);

    // CRM Deal Products
    Route::post('deals/{id}/products', [ApiController::class, 'add_deal_product']);
    Route::get('deals/{id}/products', [ApiController::class, 'get_deal_products']);
    Route::put('deals/{id}/products/{product_id}', [ApiController::class, 'update_deal_product']);
    Route::delete('deals/{id}/products/{product_id}', [ApiController::class, 'delete_deal_product']);

    // CRM Deal Commissions
    Route::post('deals/{id}/commissions', [ApiController::class, 'add_deal_commission']);
    Route::get('deals/{id}/commissions', [ApiController::class, 'get_deal_commission']);
    Route::put('deals/{id}/commissions/{commission_id}', [ApiController::class, 'update_deal_commission']);
    Route::delete('deals/{id}/commissions/{commission_id}', [ApiController::class, 'delete_deal_commission']);

    // Reports and Analytics
    Route::get('reports/leads', [ApiController::class, 'get_leads_report']);
    Route::get('reports/deals', [ApiController::class, 'get_deals_report']);
    Route::get('reports/customers', [ApiController::class, 'get_customers_statistics']);
});
