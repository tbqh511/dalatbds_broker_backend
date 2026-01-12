<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\PropertysInquiry;
use App\Models\Favourite;

class TelegramWebAppController extends Controller
{
    public function index(Request $request)
    {
        // Get authenticated customer
        $customer = Auth::guard('webapp')->user();
        
        $stats = [
            'properties_count' => 0,
            'views_count' => 0,
            'reviews_count' => 0,
            'favourites_count' => 0,
        ];

        if ($customer) {
            // Count active properties
            $stats['properties_count'] = Property::where('added_by', $customer->id)->count();
            
            // Count total views of user's properties
            $stats['views_count'] = Property::where('added_by', $customer->id)->sum('total_click');
            
            // Count reviews/inquiries
            $stats['reviews_count'] = PropertysInquiry::whereIn('property_id', function($query) use ($customer) {
                $query->select('id')->from('properties')->where('added_by', $customer->id);
            })->count();

            // Count favourites (properties interested by others or favourited by user)
            // Assuming we want to show how many people favourited this user's properties
            $stats['favourites_count'] = Favourite::whereIn('property_id', function($query) use ($customer) {
                $query->select('id')->from('properties')->where('added_by', $customer->id);
            })->count();
        }

        return view('frontend_dashboard', compact('customer', 'stats'));
    }

    public function profile(Request $request)
    {
        return view('frontend_dashboard_myprofile');
    }

    public function messages(Request $request)
    {
        return view('frontend_dashboard_messages');
    }

    public function listings(Request $request)
    {
        return view('frontend_dashboard_listings');
    }

    public function agents(Request $request)
    {
        return view('frontend_dashboard_agents');
    }

    public function bookings(Request $request)
    {
        return view('frontend_dashboard_bookings');
    }

    public function reviews(Request $request)
    {
        return view('frontend_dashboard_reviews');
    }

    public function addListing(Request $request)
    {
        return view('frontend_dashboard_add_listing');
    }
}
