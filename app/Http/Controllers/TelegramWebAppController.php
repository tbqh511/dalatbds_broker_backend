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
}
