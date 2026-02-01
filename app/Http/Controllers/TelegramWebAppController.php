<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\PropertysInquiry;
use App\Models\Favourite;
use Carbon\Carbon;

use App\Models\Category;
use App\Models\LocationsWard;
use App\Models\LocationsStreet;
use App\Models\parameter;
use App\Models\AssignParameters;
use App\Models\OutdoorFacilities;
use App\Models\CrmHost;
use App\Models\PropertyImages;
use App\Models\PropertyLegalImage;
use App\Models\AssignedOutdoorFacilities;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

        return view('frontend_dashboard', compact('customer', 'stats'));
        //return view('frontend_dashboard_temp', compact('customer', 'stats'));
    }

    

    public function tempui(Request $request)
    {
        return view('frontend_dashboard_temp');
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
        // 1. Property Types (Categories)
        $dbCategories = Category::where('status', '1')->orderBy('order', 'asc')->get();
        $propertyTypes = $dbCategories->map(function ($cat) {
            $isHouse = !Str::contains(Str::lower($cat->category), ['đất', 'land']);

            // Parse parameter_types from the category
            $parameterIds = [];
            if ($cat->parameter_types) {
                $parameterIds = array_map('intval', explode(',', $cat->parameter_types));
            }

            // Icon mapping (basic heuristic)
            $icon = 'fa-house';
            $lowerName = Str::lower($cat->category);
            if (Str::contains($lowerName, 'biệt thự')) $icon = 'fa-hotel';
            elseif (Str::contains($lowerName, 'khách sạn')) $icon = 'fa-bell-concierge';
            elseif (Str::contains($lowerName, 'chung cư')) $icon = 'fa-building';
            elseif (Str::contains($lowerName, 'đất')) $icon = 'fa-map-location-dot';

            return [
                'id' => $cat->id, // Use DB ID
                'name' => $cat->category,
                'icon' => $icon,
                'isHouse' => $isHouse,
                'parameter_ids' => $parameterIds // Add parameter IDs for this category
            ];
        });

        // 2. Wards
        $districtCode = config('location.district_code');
        $wards = LocationsWard::select('code', 'full_name')
            ->where('district_code', $districtCode)
            ->orderByRaw("CASE
                            WHEN full_name LIKE 'phường%' THEN 1
                            WHEN full_name LIKE 'Xã%' THEN 2
                            ELSE 3 END,
                          CAST(SUBSTRING_INDEX(full_name, ' ', -1) AS UNSIGNED),
                          full_name")
            ->get()
            ->map(function($w) {
                return [
                    'id' => $w->code,
                    'name' => $w->full_name,
                    'icon' => 'fa-map-pin'
                ];
            });

        // 3. Streets
        $streets = LocationsStreet::select('code', 'street_name')
            ->where('district_code', $districtCode)
            ->get()
            ->map(function($s) {
                return [
                    'id' => $s->code,
                    'name' => $s->street_name
                ];
            });

        // 4. Parameters and Assign Parameters
        $parameters = parameter::with('assigned_parameter')->get()->map(function($param) {
            return [
                'id' => $param->id,
                'name' => $param->name,
                'type_of_parameter' => $param->type_of_parameter,
                'type_values' => $param->type_values
            ];
        });

        $assignParameters = AssignParameters::all()->map(function($ap) {
            return [
                'id' => $ap->id,
                'property_id' => $ap->property_id,
                'parameter_id' => $ap->parameter_id,
                'value' => $ap->value
            ];
        });

        // 5. Outdoor Facilities
        $facilities = OutdoorFacilities::all();

        // 6. Hardcoded Data (Moved from View)
        $legalTypes = [
            ['value' => 'Sổ riêng xây dựng', 'name' => 'Sổ riêng xây dựng', 'icon' => 'fa-file-contract'],
            ['value' => 'Sổ riêng nông nghiệp', 'name' => 'Sổ riêng nông nghiệp', 'icon' => 'fa-file-contract'],
            ['value' => 'Sổ phân quyền xây dựng', 'name' => 'Sổ phân quyền xây dựng', 'icon' => 'fa-file-signature'],
            ['value' => 'Sổ phân quyền nông nghiệp', 'name' => 'Sổ phân quyền nông nghiệp', 'icon' => 'fa-file-signature'],
            ['value' => 'Giấy tay / Vi bằng', 'name' => 'Giấy tay / Vi bằng', 'icon' => 'fa-file-alt']
        ];

        $directions = ['Đông', 'Tây', 'Nam', 'Bắc', 'Đông Nam', 'Đông Bắc', 'Tây Nam', 'Tây Bắc'];

        $commissionRates = [1, 1.5, 2, 2.5, 3];

        return view('frontend_dashboard_add_listing', compact('propertyTypes', 'wards', 'streets', 'parameters', 'assignParameters', 'facilities', 'legalTypes', 'directions', 'commissionRates'));
    }

    public function submitForm(Request $request)
    {
        try {
            DB::beginTransaction();

            // Check Auth
            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập lại.'], 401);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'transactionType' => 'required',
                'price' => 'required|numeric',
                'area' => 'required|numeric',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // --- DATA PREPARATION ---
            $categoryId = $request->input('type');
            $propertyType = ($request->input('transactionType') === 'sale') ? 0 : 1; // 0: Sale, 1: Rent

            // Construct Title & Address
            $streetId = $request->input('street');
            $wardId = $request->input('ward');
            $houseNumber = $request->input('houseNumber') ?? '';

            $streetName = '';
            if ($streetId) {
                $streetObj = LocationsStreet::where('code', $streetId)->first();
                if ($streetObj) $streetName = $streetObj->street_name;
            }

            $wardName = '';
            if ($wardId) {
                $wardObj = LocationsWard::where('code', $wardId)->first();
                if ($wardObj) $wardName = $wardObj->full_name;
            }

            // Format: "123 Đường A, Phường B - Đà Lạt, Tỉnh Lâm Đồng"
            $addressParts = [];
            if ($houseNumber) $addressParts[] = $houseNumber;
            if ($streetName) $addressParts[] = $streetName;
            if ($wardName) $addressParts[] = $wardName;
            $address = implode(', ', $addressParts) . ' - Đà Lạt, Tỉnh Lâm Đồng';

            // Generate Title: "Bán nhà/Cho thuê nhà [Category] [Street], [Ward] - Đà Lạt"
            $category = Category::find($categoryId);
            $catName = $category ? $category->category : 'Bất động sản';
            $actionName = ($propertyType == 0) ? 'Bán' : 'Cho thuê';

            $titleParts = [$actionName . ' ' . strtolower($catName)];
            if ($streetName) $titleParts[] = $streetName;
            if ($wardName) $titleParts[] = $wardName;
            $title = implode(', ', $titleParts) . ' - Đà Lạt';


            // --- 1. HOST (Contact) ---
            $contact = $request->input('contact');
            if (is_string($contact)) {
                $contact = json_decode($contact, true);
            }
            
            // Format phone number to international format (84...)
            $rawPhone = $contact['phone'] ?? $customer->phone ?? '';
            $phone = preg_replace('/[^0-9]/', '', $rawPhone); // Remove non-numeric chars
            if (substr($phone, 0, 1) === '0') {
                $phone = '84' . substr($phone, 1);
            }

            $host = new CrmHost();
            $host->name = $contact['name'] ?? $customer->name ?? 'Unknown';
            $host->contact = $phone;
            $host->gender = $contact['gender'] ?? '';
            $host->save();


            // --- 2. PROPERTY ---
            $property = new Property();
            $property->category_id = $categoryId;
            $property->package_id = 1; // Default
            $property->title = $title;
            $property->description = $request->input('description');
            $property->address = $address;
            $property->client_address = $address;
            $property->propery_type = $propertyType;
            $property->price = $request->input('price');
            $property->added_by = $customer->id;
            $property->status = 0; // Pending approval
            $property->host_id = $host->id;
            $property->post_type = 1; // User submitted
            $property->street_code = $streetId;
            $property->ward_code = $wardId;

            // Commission
            $commissionRate = $request->input('commissionRate', 0);
            $property->commission = ($property->price * ($commissionRate / 100));

            // Slug
            $slug = Str::slug($title) . '-' . time();
            $property->slug = $slug;

            // Map Location
            if ($request->has('latitude')) $property->latitude = $request->input('latitude');
            if ($request->has('longitude')) $property->longitude = $request->input('longitude');

            $property->save();


            // --- 3. IMAGES ---
            // Title Image
            $imagePath = public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH');
            if (!is_dir($imagePath)) {
                mkdir($imagePath, 0777, true);
            }

            // Avatar (title_image)
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move($imagePath, $filename);

                $property->title_image = $filename;
                $property->save();
            }

            // 3D Image
            if ($request->hasFile('threeD_image')) {
                $threeDPath = public_path('images') . config('global.3D_IMG_PATH');
                if (!is_dir($threeDPath)) {
                    mkdir($threeDPath, 0777, true);
                }
                $file = $request->file('threeD_image');
                $filename = microtime(true) . "." . $file->getClientOriginalExtension();
                $file->move($threeDPath, $filename);
                $property->threeD_image = $filename;
                $property->save();
            }

            // Gallery & Legal Path
            $galleryPathBase = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH');
            if (!is_dir($galleryPathBase)) {
                mkdir($galleryPathBase, 0777, true);
            }
            $galleryPath = $galleryPathBase . "/" . $property->id;
            if (!is_dir($galleryPath)) {
                mkdir($galleryPath, 0777, true);
            }

            // Gallery (others)
            if ($request->hasFile('others')) {
                foreach ($request->file('others') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($galleryPath, $filename);

                    $propImg = new PropertyImages();
                    $propImg->propertys_id = $property->id;
                    $propImg->image = $filename;
                    $propImg->save();
                }
            }

            // Legal (legal_images)
            if ($request->hasFile('legal')) {
                foreach ($request->file('legal') as $file) {
                    $filename = time() . '_legal_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($galleryPath, $filename);

                    $legalImg = new PropertyLegalImage();
                    $legalImg->propertys_id = $property->id;
                    $legalImg->image = $filename;
                    $legalImg->save();
                }
            }


            // --- 4. PARAMETERS ---
            $parameters = $request->input('parameters');
            if (is_string($parameters)) $parameters = json_decode($parameters, true);

            $destinationPathforparam = public_path('images') . config('global.PARAMETER_IMAGE_PATH');
            if (!is_dir($destinationPathforparam)) {
                mkdir($destinationPathforparam, 0777, true);
            }

            $excludedNames = ['Diện tích', 'Pháp lý', 'Giá m2'];

            if (is_array($parameters)) {
                foreach ($parameters as $paramId => $val) {
                    $paramDef = parameter::find($paramId);
                    if (!$paramDef || in_array($paramDef->name, $excludedNames)) continue;

                    $assignParam = new AssignParameters();
                    $assignParam->modal()->associate($property);
                    $assignParam->parameter_id = $paramId;

                    // Check for file upload for this parameter
                    if ($request->hasFile("parameters.$paramId")) {
                        $profile = $request->file("parameters.$paramId");
                        $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                        $profile->move($destinationPathforparam, $imageName);
                        $assignParam->value = $imageName;
                    } else {
                        if (empty($val)) continue;
                        $assignParam->value = $val;
                    }
                    $assignParam->save();
                }
            }


            // --- 5. FACILITIES ---
            $amenities = $request->input('amenities');
            if (is_string($amenities)) $amenities = json_decode($amenities, true);

            if (is_array($amenities)) {
                foreach ($amenities as $facId => $val) {
                    if (!empty($val)) {
                        $assignFac = new AssignedOutdoorFacilities();
                        $assignFac->property_id = $property->id;
                        $assignFac->facility_id = $facId;
                        $assignFac->distance = $val;
                        $assignFac->save();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect_url' => route('webapp.add_listing_success')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addListingSuccess()
    {
        return view('frontend_dashboard_add_listing_success');
    }
}
