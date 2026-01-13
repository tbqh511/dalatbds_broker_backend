<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\PropertysInquiry;
use App\Models\Favourite;
use Carbon\Carbon;

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
            'views_count_week' => 0,
            'reviews_count_week' => 0,
            'favourites_count_week' => 0,
        ];

        if ($customer) {
            // Count active properties
            $stats['properties_count'] = Property::where('added_by', $customer->id)->count();
            
            // Count total views of user's properties
            $stats['views_count'] = Property::where('added_by', $customer->id)->sum('total_click');
            
            // Count reviews/inquiries
            $stats['reviews_count'] = PropertysInquiry::whereIn('propertys_id', function($query) use ($customer) {
                $query->select('id')->from('propertys')->where('added_by', $customer->id);
            })->count();

            // Count reviews/inquiries this week
            $stats['reviews_count_week'] = PropertysInquiry::whereIn('propertys_id', function($query) use ($customer) {
                $query->select('id')->from('propertys')->where('added_by', $customer->id);
            })->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            // Count favourites (properties interested by others or favourited by user)
            // Assuming we want to show how many people favourited this user's properties
            $stats['favourites_count'] = Favourite::whereIn('property_id', function($query) use ($customer) {
                $query->select('id')->from('propertys')->where('added_by', $customer->id);
            })->count();

            // Count favourites this week
            $stats['favourites_count_week'] = Favourite::whereIn('property_id', function($query) use ($customer) {
                $query->select('id')->from('propertys')->where('added_by', $customer->id);
            })->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        }

        //return view('frontend_dashboard', compact('customer', 'stats'));
        return view('frontend_dashboard_temp', compact('customer', 'stats'));
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
        try {
            $customer = Auth::guard('webapp')->user();

            // Validate input params
            $data = $request->validate([
                'search' => ['nullable', 'string', 'max:255'],
                'sort' => ['nullable', 'in:latest,oldest,views'],
                'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            ]);

            $perPage = $data['per_page'] ?? 10;

            $query = Property::query()->with(['category'])
                ->select('id', 'title', 'address', 'title_image', 'total_click', 'added_by', 'propery_type', 'price', 'slug', 'rentduration');

            if ($customer) {
                $query->where('added_by', $customer->id);
            }

            if (!empty($data['search'])) {
                $query->where(function ($q) use ($data) {
                    $q->where('title', 'like', '%' . $data['search'] . '%')
                      ->orWhere('address', 'like', '%' . $data['search'] . '%');
                });
            }

            if (!empty($data['sort'])) {
                switch ($data['sort']) {
                    case 'oldest':
                        $query->orderBy('created_at', 'asc');
                        break;
                    case 'views':
                        $query->orderBy('total_click', 'desc');
                        break;
                    default:
                        $query->orderBy('created_at', 'desc');
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $properties = $query->paginate($perPage)->appends($request->query());

            return view('frontend_dashboard_listings', compact('customer', 'properties'));
        } catch (\Illuminate\Validation\ValidationException $ve) {
            return redirect()->back()->withErrors($ve->errors());
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Không thể tải danh sách tin đăng, vui lòng thử lại sau.');
        }
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
