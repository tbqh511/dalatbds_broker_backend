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
            $stats['reviews_count'] = PropertysInquiry::whereIn('propertys_id', function ($query) use ($customer) {
                $query->select('id')->from('propertys')->where('added_by', $customer->id);
            })->count();

            // Count reviews/inquiries this week
            $stats['reviews_count_week'] = PropertysInquiry::whereIn('propertys_id', function ($query) use ($customer) {
                $query->select('id')->from('propertys')->where('added_by', $customer->id);
            })->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

            // Count favourites (properties interested by others or favourited by user)
            // Assuming we want to show how many people favourited this user's properties
            $stats['favourites_count'] = Favourite::whereIn('property_id', function ($query) use ($customer) {
                $query->select('id')->from('propertys')->where('added_by', $customer->id);
            })->count();

            // Count favourites this week
            $stats['favourites_count_week'] = Favourite::whereIn('property_id', function ($query) use ($customer) {
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
                'sort' => ['nullable', 'string'],
                'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            ]);

            $perPage = $data['per_page'] ?? 10;

            $query = Property::query()->with(['category']);

            // Select columns. Ensure we select 'propertys.*' to avoid ambiguity when joining
            $query->select('propertys.*');

            if ($customer) {
                $query->where('propertys.added_by', $customer->id);
            }

            if (!empty($data['search'])) {
                $search = $data['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('propertys.title', 'like', '%' . $search . '%')
                        ->orWhere('propertys.address', 'like', '%' . $search . '%');
                });
            }

            if (!empty($data['sort'])) {
                switch ($data['sort']) {
                    case 'oldest':
                        $query->orderBy('propertys.created_at', 'asc');
                        break;
                    case 'views':
                        $query->orderBy('propertys.total_click', 'desc');
                        break;
                    case 'price_asc':
                        $query->orderBy('propertys.price', 'asc');
                        break;
                    case 'price_desc':
                        $query->orderBy('propertys.price', 'desc');
                        break;
                    case 'area_asc':
                    case 'area_desc':
                        $areaParamId = config('global.area');
                        $query->leftJoin('assign_parameters', function ($join) use ($areaParamId) {
                            $join->on('propertys.id', '=', 'assign_parameters.modal_id')
                                ->where('assign_parameters.parameter_id', $areaParamId);
                        })
                            ->addSelect(DB::raw('CAST(assign_parameters.value AS DECIMAL(10,2)) as area_value'))
                            ->orderBy('area_value', $data['sort'] === 'area_asc' ? 'asc' : 'desc');
                        break;
                    case 'latest':
                    default:
                        $query->orderBy('propertys.created_at', 'desc');
                }
            }
            else {
                $query->orderBy('propertys.created_at', 'desc');
            }

            $properties = $query->paginate($perPage)->appends($request->query());

            if ($request->ajax()) {
                return view('frontends.components.dashboard_listings_items', compact('properties'))->render();
            }

            return view('frontend_dashboard_listings', compact('customer', 'properties'));
        }
        catch (\Illuminate\Validation\ValidationException $ve) {
            if ($request->ajax()) {
                return response()->json(['error' => $ve->errors()], 422);
            }
            return redirect()->back()->withErrors($ve->errors());
        }
        catch (\Exception $e) {
            report($e);
            if ($request->ajax()) {
                return response()->json(['error' => 'Server Error'], 500);
            }
            return redirect()->back()->with('error', 'Không thể tải danh sách tin đăng, vui lòng thử lại sau.');
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $property = Property::where('id', $id)->where('added_by', $customer->id)->first();
            if (!$property) {
                return response()->json(['success' => false, 'message' => 'Tin đăng không tồn tại hoặc bạn không có quyền xóa.'], 404);
            }

            // Optional: Soft delete or hard delete. Using delete() which is likely standard delete unless SoftDeletes trait is used.
            $property->delete();

            return response()->json(['success' => true, 'message' => 'Đã xóa tin đăng thành công.']);
        }
        catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống.'], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $property = Property::where('id', $id)->where('added_by', $customer->id)->first();
            if (!$property) {
                return response()->json(['success' => false, 'message' => 'Tin đăng không tồn tại.'], 404);
            }

            // Toggle status. Assuming 1 = Show, 0 = Hide/Pending.
            // Adjust logic if you have different status codes (e.g., 2 for hidden).
            // Based on addListing, status 0 is 'Pending approval'.
            // If we want a simple Hide/Show, we might need a separate column 'is_hidden' or reuse status if allowed.
            // Let's assume we toggle between 1 (Active) and 2 (Hidden).
            // Or if 0 is Pending, 1 is Active. If user hides it, maybe 2?
            // Let's check current status usage.
            // If currently 1, set to 2 (Hidden). If 2, set to 1.
            // If 0 (Pending), maybe don't allow toggle or toggle to 2.

            $newStatus = ($property->status == 1) ? 2 : 1;
            if ($property->status == 0) {
                // If pending, maybe just toggle to hidden (2) ?
                // For now let's assume 1 <-> 2 toggle.
                $newStatus = 2;
            }

            // If the requirement is just "Hide/Show", maybe we use a dedicated flag or status.
            // Let's stick to 1 (Active) and 2 (Hidden/Disabled) for user-controlled toggle.
            // 0 is usually "Pending Admin Approval".

            $property->status = $newStatus;
            $property->save();

            return response()->json(['success' => true, 'status' => $newStatus, 'message' => 'Cập nhật trạng thái thành công.']);
        }
        catch (\Exception $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống.'], 500);
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
            if (Str::contains($lowerName, 'biệt thự'))
                $icon = 'fa-hotel';
            elseif (Str::contains($lowerName, 'khách sạn'))
                $icon = 'fa-bell-concierge';
            elseif (Str::contains($lowerName, 'chung cư'))
                $icon = 'fa-building';
            elseif (Str::contains($lowerName, 'đất'))
                $icon = 'fa-map-location-dot';

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
            ->map(function ($w) {
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
            ->map(function ($s) {
            return [
            'id' => $s->code,
            'name' => $s->street_name
            ];
        });

        // 4. Parameters and Assign Parameters
        $parameters = parameter::with('assigned_parameter')->get()->map(function ($param) {
            return [
            'id' => $param->id,
            'name' => $param->name,
            'type_of_parameter' => $param->type_of_parameter,
            'type_values' => $param->type_values
            ];
        });

        $assignParameters = AssignParameters::all()->map(function ($ap) {
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
            ['value' => 'Sổ xây dựng', 'name' => 'Sổ xây dựng', 'icon' => 'fa-file-contract'],
            ['value' => 'Sổ nông nghiệp', 'name' => 'Sổ nông nghiệp', 'icon' => 'fa-file-contract'],
            ['value' => 'Sổ phân quyền xây dựng', 'name' => 'Sổ phân quyền xây dựng', 'icon' => 'fa-file-signature'],
            ['value' => 'Sổ phân quyền nông nghiệp', 'name' => 'Sổ phân quyền nông nghiệp', 'icon' => 'fa-file-signature'],
            ['value' => 'Giấy tay', 'name' => 'Giấy tay / Vi bằng', 'icon' => 'fa-file-alt']
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
                'rentduration' => 'nullable|string',
                'ward' => 'required',
                'price' => 'required|numeric|min:0',
                'area' => 'required|numeric|min:0',
                'commissionRate' => 'required|numeric',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'avatar' => 'required|image|max:5120', // Max 5MB to match frontend
            ], [
                'type.required' => 'Vui lòng chọn loại bất động sản.',
                'transactionType.required' => 'Vui lòng chọn hình thức giao dịch.',
                'ward.required' => 'Vui lòng chọn khu vực (Phường/Xã).',
                'price.required' => 'Vui lòng nhập mức giá.',
                'area.required' => 'Vui lòng nhập diện tích.',
                'commissionRate.required' => 'Vui lòng chọn mức hoa hồng.',
                'avatar.required' => 'Vui lòng tải lên ảnh đại diện.',
                'avatar.max' => 'Ảnh đại diện không được vượt quá 5MB.',
                'avatar.image' => 'File tải lên phải là hình ảnh.',
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
                if ($streetObj)
                    $streetName = $streetObj->street_name;
            }

            $wardName = '';
            if ($wardId) {
                $wardObj = LocationsWard::where('code', $wardId)->first();
                if ($wardObj)
                    $wardName = $wardObj->full_name;
            }

            // Format: "123 Đường A, Phường B - Đà Lạt, Tỉnh Lâm Đồng"
            $addressParts = [];
            if ($houseNumber)
                $addressParts[] = $houseNumber;
            if ($streetName)
                $addressParts[] = $streetName;
            if ($wardName)
                $addressParts[] = $wardName;
            $address = implode(', ', $addressParts) . ' - Đà Lạt, Tỉnh Lâm Đồng';

            // Generate Title: "Bán nhà/Cho thuê nhà [Category] [Street], [Ward] - Đà Lạt"
            $category = Category::find($categoryId);
            $catName = $category ? $category->category : 'Bất động sản';
            $actionName = ($propertyType == 0) ? 'Bán' : 'Cho thuê';

            $titleParts = [$actionName . ' ' . strtolower($catName)];
            if ($streetName)
                $titleParts[] = $streetName;
            if ($wardName)
                $titleParts[] = $wardName;
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

            // Fix duplicate host: Check if host exists
            $host = CrmHost::firstOrNew(['contact' => $phone]);

            // Update name/gender if provided (or if new)
            $host->name = $contact['name'] ?? $customer->name ?? 'Unknown';
            // Only update gender if it's new or user explicitly provides it (and it's not empty)
            if (!empty($contact['gender'])) {
                $host->gender = $contact['gender'];
            }
            // Ensure contact is set for new records
            $host->contact = $phone;

            // Handle Note (stored in 'about' column)
            if (!empty($contact['note'])) {
                $newNote = trim($contact['note']);
                if (empty($host->about)) {
                    $host->about = $newNote;
                }
                else {
                    // Check if note already exists to avoid duplication
                    if (!Str::contains($host->about, $newNote)) {
                        $timestamp = Carbon::now()->format('d/m/Y H:i');
                        $host->about .= "\n[{$timestamp}]: {$newNote}";
                    }
                }
            }

            $host->save();


            // --- 2. PROPERTY ---
            $property = new Property();
            $property->category_id = $categoryId;
            $property->package_id = 1; // Default
            $property->title = $title;
            $property->description = $request->input('description');
            $property->address = $address;
            $property->client_address = $address;
            $property->property_type = $propertyType;
            $property->rentduration = ($propertyType == 1) ? $request->input('rentduration') : null;
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
            if ($request->has('latitude'))
                $property->latitude = $request->input('latitude');
            if ($request->has('longitude'))
                $property->longitude = $request->input('longitude');

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
            if (is_string($parameters))
                $parameters = json_decode($parameters, true);

            $destinationPathforparam = public_path('images') . config('global.PARAMETER_IMAGE_PATH');
            if (!is_dir($destinationPathforparam)) {
                mkdir($destinationPathforparam, 0777, true);
            }

            $excludedNames = ['Diện tích', 'Pháp lý', 'Giá m2'];

            if (is_array($parameters)) {
                foreach ($parameters as $paramId => $val) {
                    $paramDef = parameter::find($paramId);
                    if (!$paramDef || in_array($paramDef->name, $excludedNames))
                        continue;

                    $assignParam = new AssignParameters();
                    $assignParam->modal()->associate($property);
                    $assignParam->parameter_id = $paramId;

                    // Check for file upload for this parameter
                    if ($request->hasFile("parameters.$paramId")) {
                        $profile = $request->file("parameters.$paramId");
                        $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                        $profile->move($destinationPathforparam, $imageName);
                        $assignParam->value = $imageName;
                    }
                    else {
                        if (empty($val))
                            continue;
                        $assignParam->value = $val;
                    }
                    $assignParam->save();
                }
            }


            // --- 5. FACILITIES ---
            $amenities = $request->input('amenities');
            if (is_string($amenities))
                $amenities = json_decode($amenities, true);

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
                'redirect_url' => route('webapp.add_listing_success', ['slug' => $slug])
            ]);

        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addListingSuccess(Request $request)
    {
        $slug = $request->input('slug');
        return view('frontend_dashboard_add_listing_success', compact('slug'));
    }

    public function editListing(Request $request, $id)
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer) {
            return redirect()->route('webapp');
        }

        $property = Property::with(['host', 'parameters', 'assignfacilities', 'category'])
            ->where('id', $id)
            ->where('added_by', $customer->id)
            ->first();

        if (!$property) {
            return redirect()->route('webapp.listings')->with('error', 'Tin đăng không tồn tại hoặc bạn không có quyền chỉnh sửa.');
        }

        // Build editProperty data for the frontend
        $editData = [
            'id' => $property->id,
            'transactionType' => ($property->property_type == 0) ? 'sale' : 'rent',
            'type' => $property->category_id,
            'ward' => $property->ward_code,
            'street' => $property->street_code,
            'houseNumber' => $property->street_number ?? '',
            'price' => $property->price,
            'rentduration' => $property->rentduration ?? 'Monthly',
            'description' => $property->description ?? '',
            'latitude' => $property->latitude,
            'longitude' => $property->longitude,
            'commissionRate' => 2, // default
        ];

        // Calculate commission rate from stored commission
        if ($property->price > 0 && $property->commission > 0) {
            $editData['commissionRate'] = round(($property->commission / $property->price) * 100, 1);
        }

        // Contact info from host
        if ($property->host) {
            $phone = $property->host->contact ?? '';
            // Convert 84xxx to 0xxx for display
            if (substr($phone, 0, 2) === '84') {
                $phone = '0' . substr($phone, 2);
            }
            $editData['contact'] = [
                'gender' => $property->host->gender ?? '1',
                'name' => $property->host->name ?? '',
                'phone' => $phone,
                'note' => '',
            ];
        }

        // Parameters (key-value map)
        $editParams = [];
        foreach ($property->parameters as $param) {
            $editParams[$param->id] = $param->pivot->value;
        }
        $editData['parameters'] = $editParams;

        // Extract area from parameters
        $areaParamId = config('global.area');
        $editData['area'] = $editParams[$areaParamId] ?? '';

        // Extract legal from parameters
        $legalParamId = config('global.legal');
        $editData['legal'] = $editParams[$legalParamId] ?? '';

        // Facilities (key-value map: facility_id => distance)
        $editAmenities = [];
        foreach ($property->assignfacilities as $fac) {
            $editAmenities[$fac->facility_id] = $fac->distance ?? '';
        }
        $editData['amenities'] = $editAmenities;

        // Images
        $editData['titleImage'] = $property->title_image ?: null;
        $editData['gallery'] = $property->gallery->map(function ($img) {
            return [
            'id' => $img->id,
            'url' => $img->image_url,
            ];
        })->toArray();
        $editData['legalImages'] = $property->legalimages->map(function ($img) {
            return [
            'id' => $img->id,
            'url' => $img->image_url,
            ];
        })->toArray();

        $editProperty = json_encode($editData);

        // Load the same reference data as addListing
        $dbCategories = Category::where('status', '1')->orderBy('order', 'asc')->get();
        $propertyTypes = $dbCategories->map(function ($cat) {
            $isHouse = !Str::contains(Str::lower($cat->category), ['đất', 'land']);
            $parameterIds = [];
            if ($cat->parameter_types) {
                $parameterIds = array_map('intval', explode(',', $cat->parameter_types));
            }
            $icon = 'fa-house';
            $lowerName = Str::lower($cat->category);
            if (Str::contains($lowerName, 'biệt thự'))
                $icon = 'fa-hotel';
            elseif (Str::contains($lowerName, 'khách sạn'))
                $icon = 'fa-bell-concierge';
            elseif (Str::contains($lowerName, 'chung cư'))
                $icon = 'fa-building';
            elseif (Str::contains($lowerName, 'đất'))
                $icon = 'fa-map-location-dot';
            return [
            'id' => $cat->id,
            'name' => $cat->category,
            'icon' => $icon,
            'isHouse' => $isHouse,
            'parameter_ids' => $parameterIds
            ];
        });

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
            ->map(function ($w) {
            return ['id' => $w->code, 'name' => $w->full_name, 'icon' => 'fa-map-pin'];
        });

        $streets = LocationsStreet::select('code', 'street_name')
            ->where('district_code', $districtCode)
            ->get()
            ->map(function ($s) {
            return ['id' => $s->code, 'name' => $s->street_name];
        });

        $parameters = parameter::with('assigned_parameter')->get()->map(function ($param) {
            return [
            'id' => $param->id,
            'name' => $param->name,
            'type_of_parameter' => $param->type_of_parameter,
            'type_values' => $param->type_values
            ];
        });

        $assignParameters = AssignParameters::all()->map(function ($ap) {
            return [
            'id' => $ap->id,
            'property_id' => $ap->property_id,
            'parameter_id' => $ap->parameter_id,
            'value' => $ap->value
            ];
        });

        $facilities = OutdoorFacilities::all();

        $legalTypes = [
            ['value' => 'Sổ xây dựng', 'name' => 'Sổ xây dựng', 'icon' => 'fa-file-contract'],
            ['value' => 'Sổ nông nghiệp', 'name' => 'Sổ nông nghiệp', 'icon' => 'fa-file-contract'],
            ['value' => 'Sổ phân quyền xây dựng', 'name' => 'Sổ phân quyền xây dựng', 'icon' => 'fa-file-signature'],
            ['value' => 'Sổ phân quyền nông nghiệp', 'name' => 'Sổ phân quyền nông nghiệp', 'icon' => 'fa-file-signature'],
            ['value' => 'Giấy tay', 'name' => 'Giấy tay / Vi bằng', 'icon' => 'fa-file-alt']
        ];

        $directions = ['Đông', 'Tây', 'Nam', 'Bắc', 'Đông Nam', 'Đông Bắc', 'Tây Nam', 'Tây Bắc'];
        $commissionRates = [1, 1.5, 2, 2.5, 3];

        return view('frontend_dashboard_add_listing', compact(
            'propertyTypes', 'wards', 'streets', 'parameters', 'assignParameters',
            'facilities', 'legalTypes', 'directions', 'commissionRates', 'editProperty'
        ));
    }

    public function updateForm(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập lại.'], 401);
            }

            $property = Property::where('id', $id)->where('added_by', $customer->id)->first();
            if (!$property) {
                return response()->json(['success' => false, 'message' => 'Tin đăng không tồn tại hoặc bạn không có quyền chỉnh sửa.'], 404);
            }

            // Validation — avatar is optional when editing
            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'transactionType' => 'required',
                'rentduration' => 'nullable|string',
                'ward' => 'required',
                'price' => 'required|numeric|min:0',
                'area' => 'required|numeric|min:0',
                'commissionRate' => 'required|numeric',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'avatar' => 'nullable|image|max:5120',
            ], [
                'type.required' => 'Vui lòng chọn loại bất động sản.',
                'transactionType.required' => 'Vui lòng chọn hình thức giao dịch.',
                'ward.required' => 'Vui lòng chọn khu vực (Phường/Xã).',
                'price.required' => 'Vui lòng nhập mức giá.',
                'area.required' => 'Vui lòng nhập diện tích.',
                'commissionRate.required' => 'Vui lòng chọn mức hoa hồng.',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // --- DATA PREPARATION ---
            $categoryId = $request->input('type');
            $propertyType = ($request->input('transactionType') === 'sale') ? 0 : 1;

            $streetId = $request->input('street');
            $wardId = $request->input('ward');
            $houseNumber = $request->input('houseNumber') ?? '';

            $streetName = '';
            if ($streetId) {
                $streetObj = LocationsStreet::where('code', $streetId)->first();
                if ($streetObj)
                    $streetName = $streetObj->street_name;
            }

            $wardName = '';
            if ($wardId) {
                $wardObj = LocationsWard::where('code', $wardId)->first();
                if ($wardObj)
                    $wardName = $wardObj->full_name;
            }

            $addressParts = [];
            if ($houseNumber)
                $addressParts[] = $houseNumber;
            if ($streetName)
                $addressParts[] = $streetName;
            if ($wardName)
                $addressParts[] = $wardName;
            $address = implode(', ', $addressParts) . ' - Đà Lạt, Tỉnh Lâm Đồng';

            $category = Category::find($categoryId);
            $catName = $category ? $category->category : 'Bất động sản';
            $actionName = ($propertyType == 0) ? 'Bán' : 'Cho thuê';

            $titleParts = [$actionName . ' ' . strtolower($catName)];
            if ($streetName)
                $titleParts[] = $streetName;
            if ($wardName)
                $titleParts[] = $wardName;
            $title = implode(', ', $titleParts) . ' - Đà Lạt';

            // --- 1. HOST (Contact) ---
            $contact = $request->input('contact');
            if (is_string($contact)) {
                $contact = json_decode($contact, true);
            }

            $rawPhone = $contact['phone'] ?? $customer->phone ?? '';
            $phone = preg_replace('/[^0-9]/', '', $rawPhone);
            if (substr($phone, 0, 1) === '0') {
                $phone = '84' . substr($phone, 1);
            }

            $host = CrmHost::firstOrNew(['contact' => $phone]);
            $host->name = $contact['name'] ?? $customer->name ?? 'Unknown';
            if (!empty($contact['gender'])) {
                $host->gender = $contact['gender'];
            }
            $host->contact = $phone;

            if (!empty($contact['note'])) {
                $newNote = trim($contact['note']);
                if (empty($host->about)) {
                    $host->about = $newNote;
                }
                else {
                    if (!Str::contains($host->about, $newNote)) {
                        $timestamp = Carbon::now()->format('d/m/Y H:i');
                        $host->about .= "\n[{$timestamp}]: {$newNote}";
                    }
                }
            }
            $host->save();

            // --- 2. UPDATE PROPERTY ---
            $property->category_id = $categoryId;
            $property->title = $title;
            $property->description = $request->input('description');
            $property->address = $address;
            $property->client_address = $address;
            $property->property_type = $propertyType;
            $property->rentduration = ($propertyType == 1) ? $request->input('rentduration') : null;
            $property->price = $request->input('price');
            $property->host_id = $host->id;
            $property->street_code = $streetId;
            $property->ward_code = $wardId;

            $commissionRate = $request->input('commissionRate', 0);
            $property->commission = ($property->price * ($commissionRate / 100));

            if ($request->has('latitude'))
                $property->latitude = $request->input('latitude');
            if ($request->has('longitude'))
                $property->longitude = $request->input('longitude');

            $property->save();

            // --- 3. IMAGES ---
            $imagePath = public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH');
            if (!is_dir($imagePath)) {
                mkdir($imagePath, 0777, true);
            }

            // Avatar (title_image) — only update if new file uploaded
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move($imagePath, $filename);
                $property->title_image = $filename;
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

            // Delete removed gallery images
            $keepGalleryIds = $request->input('keep_gallery_ids');
            if (is_string($keepGalleryIds)) {
                $keepGalleryIds = json_decode($keepGalleryIds, true);
            }
            if (is_array($keepGalleryIds)) {
                PropertyImages::where('propertys_id', $property->id)
                    ->whereNotIn('id', $keepGalleryIds)
                    ->delete();
            }

            // Delete removed legal images
            $keepLegalIds = $request->input('keep_legal_ids');
            if (is_string($keepLegalIds)) {
                $keepLegalIds = json_decode($keepLegalIds, true);
            }
            if (is_array($keepLegalIds)) {
                PropertyLegalImage::where('propertys_id', $property->id)
                    ->whereNotIn('id', $keepLegalIds)
                    ->delete();
            }

            // Add new gallery images
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

            // Add new legal images
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
            // Delete existing parameters and re-create
            AssignParameters::where('modal_id', $property->id)
                ->where('modal_type', 'App\\Models\\Property')
                ->delete();

            $parameters = $request->input('parameters');
            if (is_string($parameters))
                $parameters = json_decode($parameters, true);

            $destinationPathforparam = public_path('images') . config('global.PARAMETER_IMAGE_PATH');
            if (!is_dir($destinationPathforparam)) {
                mkdir($destinationPathforparam, 0777, true);
            }

            $excludedNames = ['Diện tích', 'Pháp lý', 'Giá m2'];

            if (is_array($parameters)) {
                foreach ($parameters as $paramId => $val) {
                    $paramDef = parameter::find($paramId);
                    if (!$paramDef || in_array($paramDef->name, $excludedNames))
                        continue;

                    $assignParam = new AssignParameters();
                    $assignParam->modal()->associate($property);
                    $assignParam->parameter_id = $paramId;

                    if ($request->hasFile("parameters.$paramId")) {
                        $profile = $request->file("parameters.$paramId");
                        $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                        $profile->move($destinationPathforparam, $imageName);
                        $assignParam->value = $imageName;
                    }
                    else {
                        if (empty($val))
                            continue;
                        $assignParam->value = $val;
                    }
                    $assignParam->save();
                }
            }

            // --- 5. FACILITIES ---
            // Delete existing facilities and re-create
            AssignedOutdoorFacilities::where('property_id', $property->id)->delete();

            $amenities = $request->input('amenities');
            if (is_string($amenities))
                $amenities = json_decode($amenities, true);

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
                'message' => 'Cập nhật tin đăng thành công.',
                'redirect_url' => route('webapp.listings')
            ]);

        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkHostPhone(Request $request)
    {
        $phone = $request->input('phone');
        if (!$phone)
            return response()->json([]);

        // Normalize phone for searching
        $searchPhone = preg_replace('/[^0-9]/', '', $phone);

        // Try both formats: with 0 and with 84
        $phones = [];
        if (substr($searchPhone, 0, 1) === '0') {
            $phones[] = '84' . substr($searchPhone, 1);
            $phones[] = $searchPhone;
        }
        elseif (substr($searchPhone, 0, 2) === '84') {
            $phones[] = $searchPhone;
            $phones[] = '0' . substr($searchPhone, 2);
        }
        else {
            $phones[] = $searchPhone;
        }

        $hosts = CrmHost::whereIn('contact', $phones)
            ->select('id', 'name', 'contact', 'gender')
            ->get();

        return response()->json($hosts);
    }
}
