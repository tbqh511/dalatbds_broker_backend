<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TelegramWebAppController extends Controller
{
    public function index(Request $request)
    {
        return view('frontends.frontend_webapp_home');
    }
}
