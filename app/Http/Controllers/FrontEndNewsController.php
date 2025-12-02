<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\LocationsWard;
use App\Models\NewsPost;
use Illuminate\Http\Request;

class FrontEndNewsController extends Controller
{
    ///*** Display the detail of a news post by its ID.*/
    public function show(int $id)
    {
        // Get the district code from configuration
        $districtCode = config('location.district_code', null);

        // Get the list of wards based on the district code
        $locationsWards = LocationsWard::when($districtCode, function ($query) use ($districtCode) {
            return $query->where('district_code', $districtCode);
        })->get()->sortBy('full_name');

        // Get category for header section
        $categories = Category::orderBy('category')->get();

        $news = NewsPost::findOrFail($id);
        
        return view('frontend_news_detail',[
            'locationsWards'=> $locationsWards,
            'categories'=> $categories,
            'news' => $news
        ]);
    }

    /**
     * Display a listing of the news posts.
     */
    public function index(Request $request)
    {

        // Get the district code from configuration
        $districtCode = config('location.district_code', null);

        // Get the list of wards based on the district code
        $locationsWards = LocationsWard::when($districtCode, function ($query) use ($districtCode) {
            return $query->where('district_code', $districtCode);
        })->get()->sortBy('full_name');

        // Get category for header section
        $categories = Category::orderBy('category')->get();

        $news = NewsPost::where('post_status', 'publish')->orderByDesc('created_at')->paginate(10);
        
         return view('frontend_news_listing',[
            'locationsWards'=> $locationsWards,
            'categories'=> $categories,
            'news' => $news
        ]);
    }
}
