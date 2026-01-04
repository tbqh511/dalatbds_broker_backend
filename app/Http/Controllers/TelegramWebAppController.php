<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TelegramWebAppController extends Controller
{
    public function index(Request $request)
    {
        return view('frontend_dashboard');
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
