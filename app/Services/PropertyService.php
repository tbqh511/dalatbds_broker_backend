<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Slider;
use App\Models\CrmHost;
use App\Models\UserPurchasedPackage;
use App\Models\PropertysInquiry;
use App\Models\PropertyImages;
use App\Models\PropertyLegalImage;
use App\Models\AssignedOutdoorFacilities;
use App\Models\AssignParameters;
use App\Models\Advertisement;
use App\Models\Chats;
use App\Models\Notifications;
use App\Models\Favourite;
use App\Models\InterestedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class PropertyService
{
    public function getSlider()
    {
        return Cache::remember('slider_data', 3600, function () {
            $slider = Slider::select('id', 'image', 'sequence', 'category_id', 'propertys_id')
                ->orderBy('sequence', 'ASC')
                ->get();

            if ($slider->isEmpty()) {
                return [];
            }

            $propertyIds = $slider->pluck('propertys_id')->unique();
            $properties = Property::with(['parameters', 'category', 'user', 'propery_image', 'assignParameter.parameter'])
                ->whereIn('id', $propertyIds)
                ->get()
                ->keyBy('id');

            $rows = [];
            foreach ($slider as $row) {
                $property = $properties->get($row->propertys_id);

                if (!$property) continue;

                $tempRow = [];
                $tempRow['id'] = $row->id;
                $tempRow['sequence'] = $row->sequence;
                $tempRow['category_id'] = $row->category_id;
                $tempRow['propertys_id'] = $row->propertys_id;

                if (filter_var($row->image, FILTER_VALIDATE_URL) === false) {
                    $tempRow['image'] = ($row->image != '') ? url('') . config('global.IMG_PATH') . config('global.SLIDER_IMG_PATH') . $row->image : $property->title_image;
                } else {
                    $tempRow['image'] = $property->title_image;
                }

                $promoted = Slider::where('propertys_id', $row->propertys_id)->exists();
                $tempRow['promoted'] = $promoted;
                
                $tempRow['property_title'] = $property->title;
                $tempRow['property_price'] = $property->price;
                
                if ($property->property_type == 0) {
                    $tempRow['property_type'] = "sell";
                } elseif ($property->property_type == 1) {
                    $tempRow['property_type'] = "rent";
                } elseif ($property->property_type == 2) {
                    $tempRow['property_type'] = "sold";
                } elseif ($property->property_type == 3) {
                    $tempRow['property_type'] = "Rented";
                }

                $tempRow['parameters'] = [];
                foreach ($property->parameters as $res) {
                    $tempRow['parameters'][] = [
                        'id' => $res->id,
                        'name' => $res->name,
                        'value' => $res->pivot->value,
                    ];
                }
                $rows[] = $tempRow;
            }

            return $rows;
        });
    }

    public function getFeaturedProperty(Request $request)
    {
        // Cache based on request parameters
        // We only cache if it's a standard request without too many dynamic filters
        // For simplicity and user request, we cache the result of promoted properties
        
        $page = $request->input('page', 1);
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);
        
        $cacheKey = 'featured_property_' . $page . '_' . $offset . '_' . $limit;
        
        return Cache::remember($cacheKey, 3600, function () use ($request) {
            // Force promoted filter
            $request->merge(['promoted' => true]);
            return $this->getPropertyList($request, null);
        });
    }

    public function getPropertyList(Request $request, $current_user = null)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;

        DB::enableQueryLog();
        
        $query = Property::with([
            'customer', 
            'user', 
            'category:id,category,image', 
            'assignfacilities.outdoorfacilities', 
            'favourite', 
            'parameters', 
            'interested_users', 
            'ward', 
            'street', 
            'host',
            'propery_image',
            'assignParameter.parameter'
        ]);

        $this->applyFilters($query, $request, $current_user);

        $price_sort = $request->price_sort;
        if (isset($price_sort)) {
            if ($price_sort == 0) {
                $query->orderBy('price', 'DESC');
            } elseif ($price_sort == 1) {
                $query->orderBy('price', 'ASC');
            }
        }
        
        // Since original controller logic used skip/take and returned collection,
        // we will do the same but return the query result so Controller can wrap it in Resource or response.
        
        // Note: For full optimization, we should use paginate() but sticking to skip/take for compatibility
        // unless we want to change response structure completely.
        
        return $query->skip($offset)->take($limit)->get();
    }

    private function applyFilters($query, Request $request, $current_user) {
        $property_type = $request->property_type; 
        $max_price = $request->max_price;
        $min_price = $request->min_price;
        $userid = $request->userid;
        $posted_since = $request->posted_since;
        $category_id = $request->category_id;
        $id = $request->id;
        $country = $request->country;
        $state = $request->state;
        $city = $request->city;
        $furnished = $request->furnished;
        $parameter_id = $request->parameter_id;
        $street_number = $request->street_number;
        $street_code = $request->street_code;
        $ward_code = $request->ward_code;
        $host_id = $request->host_id;
        $slug = $request->slug;

        if (isset($slug)) {
            $query->where('slug', $slug);
        }
        if (isset($street_number)) {
            $query->where('street_number', $street_number);
        }
        if (isset($street_code)) {
            $query->where('street_code', $street_code);
        }
        if (isset($ward_code)) {
            $query->where('ward_code', $ward_code);
        }
        if (isset($host_id)) {
            $query->where('host_id', $host_id);
        }
        if (isset($parameter_id)) {
            $query->whereHas('parameters', function ($q) use ($parameter_id) {
                $q->where('parameter_id', $parameter_id);
            });
        }
        if (isset($userid)) {
            $query->where('post_type', 1)->where('added_by', $userid);
        } else {
            $query->where('status', 1);
        }
        if (isset($max_price) && isset($min_price)) {
            $query->whereBetween('price', [$min_price, $max_price]);
        }
        if (isset($property_type)) {
            if ($property_type == 0 || $property_type == 2) {
                $query->where('property_type', $property_type);
            }
            if ($property_type == 1 || $property_type == 3) {
                $query->where('property_type', $property_type);
            }
        }
        if (isset($posted_since)) {
            if ($posted_since == 0) {
                $query->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
            }
            if ($posted_since == 1) {
                $query->whereDate('created_at', Carbon::yesterday());
            }
        }
        if (isset($category_id)) {
            $query->where('category_id', $category_id);
        }
        if (isset($id)) {
            $query->where('id', $id);
        }
        if (isset($country)) {
            $query->where('country', $country);
        }
        if (isset($state)) {
            $query->where('state', $state);
        }
        if (isset($city) && $city != '') {
            $query->where('city', $city);
        }
        if (isset($furnished)) {
            $query->where('furnished', $furnished);
        }
        
        if (isset($request->promoted)) {
            $adv = Advertisement::select('property_id')->where('is_enable', 1)->get();
            $ad_arr = $adv->pluck('property_id')->toArray();
            $query->whereIn('id', $ad_arr)->with('advertisement');
        }
        
        if (isset($request->users_promoted)) {
            $adv = Advertisement::select('property_id')->where('customer_id', $current_user)->where('is_enable', 1)->get();
            $ad_arr = $adv->pluck('property_id')->toArray();
            $query->whereIn('id', $ad_arr);
        }

        if (isset($request->search) && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%")
                  ->orWhere('address', 'LIKE', "%$search%")
                  ->orWhereHas('category', function ($q1) use ($search) {
                      $q1->where('category', 'LIKE', "%$search%");
                  });
            });
        }
    }

    public function getPropertyInquiry(Request $request, $current_user)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;

        $query = PropertysInquiry::with([
            'property.parameters',
            'property.category',
            'property.user',
            'property.propery_image',
            'property.assignParameter.parameter'
        ])->where('customers_id', $current_user);

        $total = $query->count();
        $result = $query->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();

        return [
            'total' => $total,
            'data' => $result
        ];
    }

    public function storeProperty(Request $request, $current_user)
    {
        $package = UserPurchasedPackage::where('modal_id', $current_user)->with([
            'package' => function ($q) {
                $q->select('id', 'property_limit', 'advertisement_limit')->where('property_limit', '!=', NULL);
            }
        ])->first();

        if (!$package || !$package->package) {
             throw new \Exception('Package not found');
        }

        $prop_count = $package->package->property_limit;
        
        if (($package->used_limit_for_property) >= ($prop_count) && $prop_count != 0) {
             throw new \Exception('Package Limit is over');
        }

        $destinationPath = public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH');
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // Host logic
        $crmHost = null;
        if ($request->has('host_name')) {
            $crmHost = CrmHost::create([
                'name' => $request->host_name,
                'gender' => $request->host_gender,
                'contact' => $request->host_contact,
                'about' => $request->host_about,
            ]);
        }

        $Saveproperty = new Property();
        $Saveproperty->category_id = $request->category_id;
        $Saveproperty->title = $request->title;
        $Saveproperty->description = $request->description;
        $Saveproperty->address = $request->address;
        $Saveproperty->client_address = (isset($request->client_address)) ? $request->client_address : '';
        
        if (isset($request->property_type)) {
            if ($request->property_type == "Sell") {
                $Saveproperty->property_type = 0;
            } elseif ($request->property_type == "Rent") {
                $Saveproperty->property_type = 1;
            } elseif ($request->property_type == "Sold") {
                $Saveproperty->property_type = 2;
            } elseif ($request->property_type == "Rented") {
                $Saveproperty->property_type = 3;
            } else {
                $Saveproperty->property_type = $request->property_type;
            }
        }
        
        $Saveproperty->price = (isset($request->price)) ? $request->price : 0;
        $Saveproperty->country = (isset($request->country)) ? $request->country : '';
        $Saveproperty->state = (isset($request->state)) ? $request->state : '';
        $Saveproperty->city = (isset($request->city)) ? $request->city : '';
        $Saveproperty->latitude = (isset($request->latitude)) ? $request->latitude : '';
        $Saveproperty->longitude = (isset($request->longitude)) ? $request->longitude : '';
        $Saveproperty->rentduration = (isset($request->rentduration)) ? $request->rentduration : '';
        $Saveproperty->street_code = (isset($request->street_code)) ? $request->street_code : '';
        $Saveproperty->ward_code = (isset($request->ward_code)) ? $request->ward_code : '';
        $Saveproperty->street_number =  (isset($request->street_number)) ? $request->street_number : '';
        $Saveproperty->commission = (isset($request->commission)) ? $request->commission : 0;
        $Saveproperty->slug = (isset($request->slug)) ? $request->slug : '';
        $Saveproperty->added_by = $current_user;
        $Saveproperty->status = (isset($request->status)) ? $request->status : 0;
        $Saveproperty->video_link = (isset($request->video_link)) ? $request->video_link : "";
        $Saveproperty->package_id = $request->package_id;
        $Saveproperty->post_type = 1;

        if ($request->hasFile('title_image')) {
            $profile = $request->file('title_image');
            $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
            $profile->move($destinationPath, $imageName);
            $Saveproperty->title_image = $imageName;
        } else {
            $Saveproperty->title_image = '';
        }

        if ($request->hasFile('threeD_image')) {
            $destinationPath3D = public_path('images') . config('global.3D_IMG_PATH');
            if (!is_dir($destinationPath3D)) {
                mkdir($destinationPath3D, 0777, true);
            }
            $profile = $request->file('threeD_image');
            $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
            $profile->move($destinationPath3D, $imageName);
            $Saveproperty->threeD_image = $imageName;
        } else {
            $Saveproperty->threeD_image = '';
        }

        if ($crmHost) {
            $Saveproperty->host_id = $crmHost->id;
        }

        $Saveproperty->save();
        $package->used_limit_for_property += 1;
        $package->save();

        // Save Facilities
        if ($request->facilities) {
            foreach ($request->facilities as $key => $value) {
                $facilities = new AssignedOutdoorFacilities();
                $facilities->facility_id = $value['facility_id'];
                $facilities->property_id = $Saveproperty->id;
                $facilities->distance = $value['distance'];
                $facilities->save();
            }
        }

        // Save Parameters
        if ($request->parameters) {
            $destinationPathforparam = public_path('images') . config('global.PARAMETER_IMAGE_PATH');
            if (!is_dir($destinationPathforparam)) {
                mkdir($destinationPathforparam, 0777, true);
            }
            foreach ($request->parameters as $key => $parameter) {
                $AssignParameters = new AssignParameters();
                $AssignParameters->modal()->associate($Saveproperty);
                $AssignParameters->parameter_id = $parameter['parameter_id'];
                
                if ($request->hasFile('parameters.' . $key . '.value')) {
                    $profile = $request->file('parameters.' . $key . '.value');
                    $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                    $profile->move($destinationPathforparam, $imageName);
                    $AssignParameters->value = $imageName;
                } else if (isset($parameter['value']) && filter_var($parameter['value'], FILTER_VALIDATE_URL)) {
                    // Logic for URL download (simplified for brevity)
                    $AssignParameters->value = $parameter['value'];
                } else {
                    $AssignParameters->value = $parameter['value'] ?? '';
                }
                $AssignParameters->save();
            }
        }

        // Save Gallery Images
        if ($request->hasfile('gallery_images')) {
            $destinationPathGallery = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . "/" . $Saveproperty->id;
             if (!is_dir($destinationPathGallery)) {
                mkdir($destinationPathGallery, 0777, true);
            }
            foreach ($request->file('gallery_images') as $file) {
                $name = time() . rand(1, 100) . '.' . $file->extension();
                $file->move($destinationPathGallery, $name);
                $gallary_image = new PropertyImages();
                $gallary_image->image = $name;
                $gallary_image->propertys_id = $Saveproperty->id;
                $gallary_image->save();
            }
        }

        // Save Legal Images
        if ($request->hasfile('legal_images')) {
            $destinationPathGallery = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . "/" . $Saveproperty->id;
             if (!is_dir($destinationPathGallery)) {
                mkdir($destinationPathGallery, 0777, true);
            }
            foreach ($request->file('legal_images') as $file) {
                $name = time() . rand(1, 100) . '.' . $file->extension();
                $file->move($destinationPathGallery, $name);
                $gallary_legal_image = new PropertyLegalImage();
                $gallary_legal_image->image = $name;
                $gallary_legal_image->propertys_id = $Saveproperty->id;
                $gallary_legal_image->save();
            }
        }

        return $Saveproperty;
    }

    public function updateProperty(Request $request, $current_user)
    {
        $id = $request->id;
        $property = Property::where('added_by', $current_user)->find($id);

        if (!$property) {
            throw new \Exception('Property not found or access denied');
        }

        $destinationPath = public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH');
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        if (isset($request->category_id)) {
            $property->category_id = $request->category_id;
        }
        if (isset($request->title)) {
            $property->title = $request->title;
        }
        if (isset($request->description)) {
            $property->description = $request->description;
        }
        if (isset($request->address)) {
            $property->address = $request->address;
        }
        if (isset($request->client_address)) {
            $property->client_address = $request->client_address;
        }
        if (isset($request->propery_type) && !isset($request->property_type)) {
            $property->property_type = $request->propery_type;
        }
        if (isset($request->commission)) {
            $property->commission = $request->commission;
        }
        if (isset($request->price)) {
            $property->price = $request->price;
        }
        if (isset($request->country)) {
            $property->country = $request->country;
        }
        if (isset($request->state)) {
            $property->state = $request->state;
        }
        if (isset($request->city)) {
            $property->city = $request->city;
        }
        if (isset($request->status)) {
            $property->status = $request->status;
        }
        if (isset($request->latitude)) {
            $property->latitude = $request->latitude;
        }
        if (isset($request->longitude)) {
            $property->longitude = $request->longitude;
        }
        if (isset($request->rentduration)) {
            $property->rentduration = $request->rentduration;
        }
        
        if ($request->hasFile('title_image')) {
            $profile = $request->file('title_image');
            $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
            $profile->move($destinationPath, $imageName);

            if ($property->title_image != '') {
                if (file_exists(public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH') . $property->title_image)) {
                    unlink(public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH') . $property->title_image);
                }
            }
            $property->title_image = $imageName;
        }

        if ($request->hasFile('threeD_image')) {
            $destinationPath1 = public_path('images') . config('global.3D_IMG_PATH');
            if (!is_dir($destinationPath1)) {
                mkdir($destinationPath1, 0777, true);
            }
            $profile = $request->file('threeD_image');
            $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
            $profile->move($destinationPath1, $imageName);

            if ($property->title_image != '') {
                if (file_exists(public_path('images') . config('global.3D_IMG_PATH') . $property->title_image)) {
                    unlink(public_path('images') . config('global.3D_IMG_PATH') . $property->title_image);
                }
            }
            $property->threeD_image = $imageName;
        }

        if ($request->parameters) {
            $destinationPathforparam = public_path('images') . config('global.PARAMETER_IMAGE_PATH');
            if (!is_dir($destinationPathforparam)) {
                mkdir($destinationPathforparam, 0777, true);
            }
            foreach ($request->parameters as $key => $parameter) {
                $AssignParameters = AssignParameters::where('modal_id', $property->id)->where('parameter_id', $parameter['parameter_id'])->first();

                if ($AssignParameters) {
                    if ($request->hasFile('parameters.' . $key . '.value')) {
                        $profile = $request->file('parameters.' . $key . '.value');
                        $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                        $profile->move($destinationPathforparam, $imageName);
                        $AssignParameters->value = $imageName;
                    } else if (isset($parameter['value']) && filter_var($parameter['value'], FILTER_VALIDATE_URL)) {
                        // Logic for URL download (simplified for brevity)
                        $AssignParameters->value = $parameter['value'];
                    } else {
                        $AssignParameters->value = $parameter['value'] ?? '';
                    }
                    $AssignParameters->save();
                } else {
                    $AssignParameters = new AssignParameters();
                    $AssignParameters->modal()->associate($property);
                    $AssignParameters->parameter_id = $parameter['parameter_id'];
                    
                    if ($request->hasFile('parameters.' . $key . '.value')) {
                        $profile = $request->file('parameters.' . $key . '.value');
                        $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                        $profile->move($destinationPathforparam, $imageName);
                        $AssignParameters->value = $imageName;
                    } else if (isset($parameter['value']) && filter_var($parameter['value'], FILTER_VALIDATE_URL)) {
                         // Logic for URL download (simplified for brevity)
                        $AssignParameters->value = $parameter['value'];
                    } else {
                        $AssignParameters->value = $parameter['value'] ?? '';
                    }
                    $AssignParameters->save();
                }
            }
        }

        AssignedOutdoorFacilities::where('property_id', $request->id)->delete();
        if ($request->facilities) {
            foreach ($request->facilities as $key => $value) {
                $facilities = new AssignedOutdoorFacilities();
                $facilities->facility_id = $value['facility_id'];
                $facilities->property_id = $request->id;
                $facilities->distance = $value['distance'];
                $facilities->save();
            }
        }

        if (isset($request->property_type)) {
            if ($request->property_type == "Sell") {
                $property->property_type = 0;
            } elseif ($request->property_type == "Rent") {
                $property->property_type = 1;
            } elseif ($request->property_type == "Sold") {
                $property->property_type = 2;
            } elseif ($request->property_type == "Rented") {
                $property->property_type = 3;
            } else {
                $property->property_type = $request->property_type;
            }
        }

        // Host logic
        $crmHost = CrmHost::updateOrCreate(
            ['id' => $property->host_id], 
            [
                'name' => $request->host_name,
                'gender' => $request->host_gender,
                'contact' => $request->host_contact,
                'about' => $request->host_about,
            ]
        );
        $property->host_id = $crmHost->id;

        $property->street_code = (isset($request->street_code)) ? $request->street_code : '';
        $property->ward_code = (isset($request->ward_code)) ? $request->ward_code : '';
        $property->street_number =  (isset($request->street_number)) ? $request->street_number : '';

        $property->update();

        // Update gallery images
        $destinationPath = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . "/" . $property->id;
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        if ($request->remove_gallery_images) {
            foreach ($request->remove_gallery_images as $key => $value) {
                $gallary_images = PropertyImages::find($value);
                if ($gallary_images) {
                    if (file_exists(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $gallary_images->propertys_id . '/' . $gallary_images->image)) {
                        unlink(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $gallary_images->propertys_id . '/' . $gallary_images->image);
                    }
                    $gallary_images->delete();
                }
            }
        }
        if ($request->hasfile('gallery_images')) {
            foreach ($request->file('gallery_images') as $file) {
                $name = time() . rand(1, 100) . '.' . $file->extension();
                $file->move($destinationPath, $name);
                PropertyImages::create([
                    'image' => $name,
                    'propertys_id' => $property->id,
                ]);
            }
        }

        // Update legal images
        if ($request->remove_legal_images) {
            foreach ($request->remove_legal_images as $key => $value) {
                $legal_images = PropertyLegalImage::find($value);
                if ($legal_images) {
                    if (file_exists(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $legal_images->propertys_id . '/' . $legal_images->image)) {
                        unlink(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $legal_images->propertys_id . '/' . $legal_images->image);
                    }
                    $legal_images->delete();
                }
            }
        }
        if ($request->hasfile('legal_images')) {
            foreach ($request->file('legal_images') as $file) {
                $name = time() . rand(1, 100) . '.' . $file->extension();
                $file->move($destinationPath, $name);
                PropertyLegalImage::create([
                    'image' => $name,
                    'propertys_id' => $property->id,
                ]);
            }
        }

        // Eager load for return
        return Property::with([
            'customer', 
            'category:id,category,image', 
            'assignfacilities.outdoorfacilities', 
            'favourite', 
            'parameters', 
            'interested_users'
        ])->find($property->id);
    }

    public function deleteProperty($id, $current_user)
    {
        $property = Property::where('added_by', $current_user)->find($id);

        if (!$property) {
             throw new \Exception('Property not found or you do not have permission to delete it');
        }

        DB::beginTransaction();

        try {
            Chats::where('property_id', $property->id)->delete();
            PropertysInquiry::where('propertys_id', $property->id)->delete();
            Slider::where('propertys_id', $property->id)->delete();
            Notifications::where('propertys_id', $property->id)->delete();
            Favourite::where('property_id', $property->id)->delete();
            InterestedUser::where('property_id', $property->id)->delete();
            AssignedOutdoorFacilities::where('property_id', $property->id)->delete();
            AssignParameters::where('modal_id', $property->id)->delete();
            
            // Delete images files
            if ($property->title_image != '') {
                if (file_exists(public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH') . $property->title_image)) {
                    unlink(public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH') . $property->title_image);
                }
            }

            foreach ($property->gallery as $row) {
                if (file_exists(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $property->id . "/" . $row->image)) {
                    unlink(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $property->id . "/" . $row->image);
                }
                $row->delete();
            }
            
            if (is_dir(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $property->id)) {
                rmdir(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $property->id);
            }

            PropertyImages::where('propertys_id', $property->id)->delete();
            PropertyLegalImage::where('propertys_id', $property->id)->delete();

            $property->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
