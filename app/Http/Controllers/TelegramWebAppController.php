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
use App\Models\CrmCustomer;
use App\Models\CrmLead;
use App\Models\Customer;
use App\Models\MarketPrice;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Services\Telegram\TelegramMessageTemplates;

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

        // Market prices for the home page strip
        $marketPrices = MarketPrice::latestMonth()->orderByDesc('avg_price_m2')->take(3)->get();

        // First batch of properties for server-side render
        $properties = Property::with(['category', 'ward', 'propery_image'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $likedIds = $customer
            ? Favourite::where('user_id', $customer->id)->pluck('property_id')->toArray()
            : [];

        $categories = Category::where('status', 1)->orderBy('order')->get(['id', 'category']);

        return view('webapp.layout', compact('customer', 'stats', 'properties', 'marketPrices', 'likedIds', 'categories'));
    //return view('frontend_dashboard', compact('customer', 'stats', 'properties'));
    }

    public function toggleFavourite(Request $request)
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $propertyId = $request->input('property_id');
        if (!$propertyId) {
            return response()->json(['success' => false, 'message' => 'Missing property_id'], 422);
        }

        $existing = Favourite::where('user_id', $customer->id)
            ->where('property_id', $propertyId)
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            Favourite::create(['user_id' => $customer->id, 'property_id' => $propertyId]);
            $liked = true;
        }

        return response()->json(['success' => true, 'liked' => $liked]);
    }

    public function likedProperties(Request $request)
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer) {
            return response()->json(['success' => false], 401);
        }

        $properties = Property::with(['category', 'ward'])
            ->whereIn('id', Favourite::where('user_id', $customer->id)->pluck('property_id'))
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($p) {
                return [
                    'id'            => $p->id,
                    'title'         => $p->title_by_address,
                    'price'         => $p->formatted_prices,
                    'location'      => $p->address_location,
                    'area'          => $p->area,
                    'category_name' => $p->category?->category,
                    'title_image'   => $p->title_image,
                ];
            });

        return response()->json(['success' => true, 'data' => $properties]);
    }

    public function tempui(Request $request)
    {
        $categories = Category::where('status', 1)->orderBy('order')->get(['id', 'category']);
        return view('frontend_dashboard_temp', compact('categories'));
    }

    public function propertyDetailJson(Request $request, $id)
    {
        $customer = Auth::guard('webapp')->user();

        $property = Property::with([
            'category', 'ward', 'street', 'parameters', 'assignfacilities.outdoorfacilities', 'host',
        ])->where('status', 1)->find($id);

        if (!$property) {
            return response()->json(['error' => 'Not found'], 404);
        }

        // Increment view count
        $property->increment('total_click');

        // All images: title + gallery
        $galleryBase = url('') . config('global.IMG_PATH') . config('global.PROPERTY_GALLERY_IMG_PATH');
        $galleryImages = $property->propery_image
            ->filter(fn($img) => $img->image)
            ->map(fn($img) => $galleryBase . $property->id . '/' . $img->image)
            ->values()->toArray();
        $allImages = $property->title_image
            ? array_merge([$property->title_image], $galleryImages)
            : $galleryImages;

        // Parameters keyed by id
        $areaParamId      = (int) config('global.area');
        $legalParamId     = (int) config('global.legal');
        $directionParamId = (int) config('global.direction');
        $area = $legal = $direction = '';
        $paramList = [];
        foreach ($property->parameters as $param) {
            $val = $param->pivot->value ?? '';
            if ((int)$param->id === $areaParamId)      $area      = $val;
            if ((int)$param->id === $legalParamId)     $legal     = $val;
            if ((int)$param->id === $directionParamId) $direction = $val;
            if ($val !== '' && $val !== null) {
                $paramList[] = ['id' => $param->id, 'name' => $param->name, 'value' => $val];
            }
        }

        // Facilities
        $facilities = [];
        foreach ($property->assignfacilities as $fac) {
            $of = $fac->outdoorfacilities;
            if ($of) {
                $facilities[] = [
                    'name'     => $of->name ?? '',
                    'icon'     => $of->image ?? '',
                    'distance' => $fac->distance ?? '',
                ];
            }
        }

        // Commission rate
        $commissionRate = 2;
        if ($property->price > 0 && $property->commission > 0) {
            $commissionRate = round(($property->commission / $property->price) * 100, 1);
        }

        // Host info — only for broker+
        $hostData = null;
        $brokerRoles = ['broker', 'sale', 'sale_admin', 'bds_admin', 'admin'];
        if ($customer && in_array($customer->role, $brokerRoles) && $property->host) {
            $phone = $property->host->contact ?? '';
            if (substr($phone, 0, 2) === '84') {
                $phone = '0' . substr($phone, 2);
            }
            $hostData = [
                'gender' => $property->host->gender ?? '1',
                'name'   => $property->host->name ?? '',
                'phone'  => $phone,
            ];
        }

        // Broker (person who listed it)
        $broker = null;
        if ($property->added_by) {
            $brokerUser = Customer::select('name', 'profile')->find($property->added_by);
            if ($brokerUser) {
                $parts = preg_split('/\s+/', trim($brokerUser->name ?? 'BK'));
                $initials = mb_strtoupper(mb_substr($parts[0], 0, 1) . (count($parts) > 1 ? mb_substr(end($parts), 0, 1) : ''));
                $broker = [
                    'name'     => $brokerUser->name ?? 'Môi giới',
                    'initials' => $initials ?: 'BK',
                    'avatar'   => $brokerUser->profile ?? null,
                    'role'     => 'eBroker · Đà Lạt BĐS',
                ];
            }
        }

        // Address
        $streetName = optional($property->street)->street_name ?? '';
        $wardName   = optional($property->ward)->name ?? '';

        // Similar properties — same category, exclude current
        $similar = Property::with(['category', 'ward'])
            ->where('status', 1)
            ->where('id', '!=', $property->id)
            ->where('category_id', $property->category_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn ($p) => [
                'id'    => $p->id,
                'title' => $p->title_by_address,
                'price' => $p->formatted_prices,
                'ward'  => optional($p->ward)->name ?? '',
                'image' => $p->title_image,
                'type'  => $p->category?->category ?? 'BĐS',
            ])->values()->toArray();

        return response()->json([
            'id'             => $property->id,
            'title'          => $property->title_by_address,
            'price'          => $property->formatted_prices,
            'priceM2'        => $property->formatted_price_m2 ?? '',
            'type'           => $property->category?->category ?? 'BĐS',
            'transactionType'=> $property->property_type == 1 ? 'rent' : 'sale',
            'area'           => $area ? $area . ' m²' : null,
            'room'           => $property->number_room ? $property->number_room . ' PN' : null,
            'legal'          => $legal,
            'direction'      => $direction,
            'addr'           => $streetName . ($wardName ? ', ' . $wardName : '') . ', Tp.Đà Lạt',
            'street'         => $streetName,
            'ward'           => $wardName,
            'houseNumber'    => $property->street_number ?? '',
            'rentduration'   => $property->rentduration,
            'description'    => $property->description,
            'latitude'       => $property->latitude,
            'longitude'      => $property->longitude,
            'views'          => $property->total_click,
            'images'         => $allImages,
            'commissionRate' => $commissionRate,
            'commission'     => $property->commission,
            'parameters'     => $paramList,
            'facilities'     => $facilities,
            'host'           => $hostData,
            'broker'         => $broker,
            'similar'        => $similar,
        ]);
    }

    public function nearbyProperties(Request $request)
    {
        $lat = clone $request->query('lat');
        $lng = clone $request->query('lng');
        $exclude_id = clone $request->query('exclude_id');

        $lat = (float) $lat;
        $lng = (float) $lng;

        if (!$lat || !$lng) {
            return response()->json(['success' => false, 'message' => 'Vui lòng cung cấp tọa độ hợp lệ']);
        }

        // Haversine formula to find nearby properties
        $haversine = "(6371 * acos(cos(radians($lat)) 
                        * cos(radians(latitude)) 
                        * cos(radians(longitude) - radians($lng)) 
                        + sin(radians($lat)) 
                        * sin(radians(latitude))))";

        $query = Property::select('id', 'title_by_address as title', 'category_id', 'latitude', 'longitude', 'title_image', 'price', 'type')
            ->selectRaw("{$haversine} AS distance")
            ->where('status', 1)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', '')
            ->where('longitude', '!=', '');

        if ($exclude_id) {
            $query->where('id', '!=', $exclude_id);
        }

        $nearby = $query->having('distance', '<', 50) // within 50km
            ->orderBy('distance', 'asc')
            ->limit(5)
            ->get();

        $data = $nearby->map(function ($p) {
            return [
                'id' => $p->id,
                'title' => $p->title,
                'price' => $p->formatted_prices, // Accessor from Property model
                'image' => $p->title_image ? url('') . config('global.IMG_PATH') . config('global.PROPERTY_TITLE_IMG_PATH') . $p->title_image : null,
                'type' => $p->category?->category ?? 'BĐS',
                'lat' => (float) $p->latitude,
                'lng' => (float) $p->longitude,
                'distance' => round($p->distance, 2) . ' km'
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function homeFeed(Request $request)
    {
        $page = (int) $request->get('page', 1);
        $categorySlug = $request->get('category_id'); // slug string from frontend chip
        $type = $request->get('type'); // null=all, '0'=buy, '1'=rent

        // Map frontend chip slugs to actual category IDs
        $categorySlugMap = [
            'dato'     => [1, 5, 8, 11], // Đất ở, Đất giấy tay, Đất ở phân quyền, Đất nông nghiệp
            'nha'      => [3, 4, 6],      // Nhà phân quyền, Nhà giấy tay, Nhà
            'bietthu'  => [9],            // Biệt thự
            'chungcu'  => [7],            // Chung cư
            'khachsan' => [2],            // Khách sạn
        ];

        $query = Property::with(['category', 'ward', 'propery_image'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc');

        if ($categorySlug && isset($categorySlugMap[$categorySlug])) {
            $query->whereIn('category_id', $categorySlugMap[$categorySlug]);
        }
        if ($type !== null && $type !== '') {
            $query->where('property_type', (int) $type);
        }

        $paginator = $query->paginate(10, ['*'], 'page', $page);

        $galleryBase = url('') . config('global.IMG_PATH') . config('global.PROPERTY_GALLERY_IMG_PATH');

        $items = $paginator->map(function ($p) use ($galleryBase) {
            $galleryImages = $p->propery_image
                ->filter(fn($img) => $img->image)
                ->map(fn($img) => $galleryBase . $p->id . '/' . $img->image)
                ->values()
                ->toArray();

            return [
                'id'             => $p->id,
                'title'          => $p->title_by_address,
                'price'          => $p->formatted_prices,
                'location'       => $p->address_location,
                'area'           => $p->area,
                'legal'          => $p->legal,
                'number_room'    => $p->number_room,
                'total_click'    => $p->total_click,
                'title_image'    => $p->title_image ?: null,
                'category_name'  => $p->category?->category,
                'type_label'     => $p->type,
                'property_type'  => $p->property_type,
                'gallery_images' => $galleryImages,
                'added_by'       => $p->added_by,
            ];
        });

        return response()->json([
            'properties' => $items,
            'has_more'   => $paginator->hasMorePages(),
            'next_page'  => $paginator->currentPage() + 1,
        ]);
    }

    public function searchResults(Request $request)
    {
        $q = trim($request->get('q', ''));
        $page = (int) $request->get('page', 1);

        $query = Property::with(['category', 'ward', 'host', 'propery_image'])
            ->where('status', 1);

        // Default sort
        $sort = $request->get('sort', 'latest');

        if ($q !== '') {
            $query->where(function ($qBuilder) use ($q) {
                // Bỏ chữ "Đường " hoặc "Đ. " hoặc "Phường " ở đầu để search linh hoạt hơn
                $streetQ = trim(preg_replace('/^(Đường|đường|Đ\.|đ\.)\s+/iu', '', $q));
                $wardQ = trim(preg_replace('/^(Phường|phường|P\.|p\.|Xã|xã|X\.|x\.)\s+/iu', '', $q));

                $qBuilder->where('title', 'LIKE', '%' . $q . '%')
                         ->orWhere('address', 'LIKE', '%' . $q . '%')
                         ->orWhereHas('street', function ($sq) use ($streetQ) {
                             $sq->where('street_name', 'LIKE', '%' . $streetQ . '%');
                         })
                         ->orWhereHas('ward', function ($wq) use ($wardQ) {
                             $wq->where('full_name', 'LIKE', '%' . $wardQ . '%')
                                ->orWhere('name', 'LIKE', '%' . $wardQ . '%');
                         })
                         ->orWhereHas('category', function ($cq) use ($q) {
                             $cq->where('category', 'LIKE', '%' . $q . '%');
                         });
            });
        }

        // Filter: property_type (0=bán, 1=thuê)
        $type = $request->get('type');
        if ($type !== null && $type !== '') {
            if ($type === 'rent') $query->where('property_type', 1);
            else if ($type === 'sale') $query->where('property_type', 0);
            else $query->where('property_type', (int)$type);
        }

        // Filter: price range
        $priceLabel = $request->get('price');
        if ($priceLabel) {
            if ($priceLabel === 'Dưới 1 tỷ') {
                $query->where('price', '<', 1000000000);
            } elseif ($priceLabel === '1–2 tỷ') {
                $query->whereBetween('price', [1000000000, 2000000000]);
            } elseif ($priceLabel === '2–3 tỷ') {
                $query->whereBetween('price', [2000000000, 3000000000]);
            } elseif ($priceLabel === '3–5 tỷ') {
                $query->whereBetween('price', [3000000000, 5000000000]);
            } elseif ($priceLabel === '5–7 tỷ') {
                $query->whereBetween('price', [5000000000, 7000000000]);
            } elseif ($priceLabel === '7–10 tỷ') {
                $query->whereBetween('price', [7000000000, 10000000000]);
            } elseif ($priceLabel === 'Trên 10 tỷ') {
                $query->where('price', '>', 10000000000);
            }
        }
        
        // Filter: category name
        $categoryName = $request->get('categoryName');
        if ($categoryName) {
            $query->whereHas('category', function ($cq) use ($categoryName) {
                $cq->where('category', $categoryName);
            });
        }

        // Filter: area range (via assign_parameters)
        $areaRange = $request->get('area_range');
        if ($areaRange) {
            $areaParamId = (int) config('global.area');
            if ($areaRange === '1000+') {
                $query->whereHas('parameters', function ($pq) use ($areaParamId) {
                    $pq->where('parameters.id', $areaParamId)
                       ->whereRaw('CAST(assign_parameters.value AS DECIMAL(10,2)) >= 1000');
                });
            } else {
                $parts = explode('-', $areaRange);
                if (count($parts) === 2) {
                    $min = (float) $parts[0];
                    $max = (float) $parts[1];
                    $query->whereHas('parameters', function ($pq) use ($areaParamId, $min, $max) {
                        $pq->where('parameters.id', $areaParamId)
                           ->whereRaw('CAST(assign_parameters.value AS DECIMAL(10,2)) >= ?', [$min])
                           ->whereRaw('CAST(assign_parameters.value AS DECIMAL(10,2)) <= ?', [$max]);
                    });
                }
            }
        }

        // Filter: direction (via assign_parameters)
        $direction = $request->get('direction');
        if ($direction) {
            $dirParamId = (int) config('global.direction');
            $query->whereHas('parameters', function ($pq) use ($dirParamId, $direction) {
                $pq->where('parameters.id', $dirParamId)
                   ->where('assign_parameters.value', $direction);
            });
        }

        // Filter: legal (via assign_parameters)
        $legal = $request->get('legal');
        if ($legal) {
            $legalParamId = (int) config('global.legal');
            $query->whereHas('parameters', function ($pq) use ($legalParamId, $legal) {
                $pq->where('parameters.id', $legalParamId)
                   ->where('assign_parameters.value', 'LIKE', '%' . $legal . '%');
            });
        }

        // Sort
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'area_asc':
            case 'area_desc':
                $areaParamId = (int) config('global.area');
                $query->select('propertys.*')
                    ->leftJoin('assign_parameters', function ($join) use ($areaParamId) {
                        $join->on('propertys.id', '=', 'assign_parameters.modal_id')
                            ->where('assign_parameters.parameter_id', $areaParamId);
                    })
                    ->addSelect(DB::raw('CAST(assign_parameters.value AS DECIMAL(10,2)) as area_value'))
                    ->orderBy('area_value', $sort === 'area_asc' ? 'asc' : 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $paginator = $query->paginate(10, ['*'], 'page', $page);

        $galleryBase = url('') . config('global.IMG_PATH') . config('global.PROPERTY_GALLERY_IMG_PATH');

        $items = $paginator->map(function ($p) use ($galleryBase) {
            $galleryImages = $p->propery_image
                ->filter(fn($img) => $img->image)
                ->map(fn($img) => $galleryBase . $p->id . '/' . $img->image)
                ->values()
                ->toArray();

            return [
                'id'             => $p->id,
                'title'          => $p->title_by_address,
                'price'          => $p->formatted_prices,
                'location'       => $p->address_location,
                'area'           => $p->area,
                'legal'          => $p->legal,
                'number_room'    => $p->number_room,
                'total_click'    => $p->total_click,
                'title_image'    => $p->title_image ?: null,
                'category_name'  => $p->category?->category,
                'type_label'     => $p->type,
                'property_type'  => $p->property_type,
                'gallery_images' => $galleryImages,
                'created_at_diff' => \Carbon\Carbon::parse($p->created_at)->diffForHumans(),
                'added_by'       => $p->added_by,
                'host_phone'     => optional($p->host)->contact,
            ];
        });

        return response()->json([
            'success' => true,
            'properties' => $items,
            'total'      => $paginator->total(),
            'has_more'   => $paginator->hasMorePages(),
            'next_page'  => $paginator->currentPage() + 1,
        ]);
    }

    public function searchSuggestions(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $results = [];

        // Bỏ tiền tố để tìm kiếm chính xác hơn trong CSDL
        $streetQ = trim(preg_replace('/^(Đường|đường|Đ\.|đ\.)\s+/iu', '', $q));
        $wardQ = trim(preg_replace('/^(Phường|phường|P\.|p\.|Xã|xã|X\.|x\.)\s+/iu', '', $q));

        // 1. Tìm đường (Street)
        $streets = LocationsStreet::where('district_code', config('location.district_code'))
            ->where('street_name', 'LIKE', '%' . $streetQ . '%')
            ->limit(3)
            ->get();
        foreach ($streets as $s) {
            $results[] = [
                'type' => 'street',
                'title' => 'Đường ' . $s->street_name,
                'sub' => 'Đà Lạt',
                'query' => 'Đường ' . $s->street_name,
                'icon' => 'street'
            ];
        }

        // 2. Tìm phường (Ward)
        $wards = LocationsWard::where('district_code', config('location.district_code'))
            ->where(function ($wBuilder) use ($wardQ) {
                $wBuilder->where('full_name', 'LIKE', '%' . $wardQ . '%')
                         ->orWhere('name', 'LIKE', '%' . $wardQ . '%');
            })
            ->limit(2)
            ->get();
        foreach ($wards as $w) {
            $results[] = [
                'type' => 'ward',
                'title' => $w->full_name,
                'sub' => 'Khu vực',
                'query' => $w->full_name,
                'icon' => 'area'
            ];
        }

        // 3. Tìm BĐS (Property title or address)
        $props = Property::with('category')->where('status', 1)
            ->where(function ($query) use ($q) {
                $query->where('title', 'LIKE', '%' . $q . '%')
                      ->orWhere('address', 'LIKE', '%' . $q . '%');
            })
            ->limit(4)
            ->get();
        
        foreach ($props as $p) {
            $results[] = [
                'type' => 'property',
                'title' => mb_substr($p->title_by_address, 0, 45) . '...',
                'sub' => $p->formatted_prices . ' · ' . ($p->category ? $p->category->category : 'BĐS'),
                'query' => $p->title_by_address,
                'id' => $p->id,
                'icon' => 'property'
            ];
        }

        return response()->json(['success' => true, 'data' => $results]);
    }

    // Task 8: Search Leads API
    public function searchLeads(Request $request)
    {
        $q = trim($request->get('q', ''));
        $status = $request->get('status', '');
        $page = (int) $request->get('page', 1);

        $query = CrmLead::with(['customer', 'sale'])
            ->orderBy('created_at', 'desc');

        if ($q !== '') {
            $query->where(function ($qb) use ($q) {
                $qb->where('note', 'LIKE', '%' . $q . '%')
                    ->orWhere('source_note', 'LIKE', '%' . $q . '%')
                    ->orWhereHas('customer', function ($cq) use ($q) {
                        $cq->where('name', 'LIKE', '%' . $q . '%')
                            ->orWhere('phone', 'LIKE', '%' . $q . '%');
                    });
            });
        }

        if ($status !== '' && $status !== null) {
            $query->whereRaw("LOWER(REPLACE(status, ' ', '-')) = ?", [strtolower($status)]);
        }

        $paginator = $query->paginate(15, ['*'], 'page', $page);

        $items = $paginator->map(function ($lead) {
            return [
                'id'           => $lead->id,
                'customer_name' => optional($lead->customer)->name ?? 'Chưa rõ',
                'customer_phone' => optional($lead->customer)->phone ?? '',
                'lead_type'    => $lead->getRawOriginal('lead_type') === 'buy' ? 'Mua' : 'Thuê',
                'status'       => $lead->getRawOriginal('status'),
                'status_label' => $lead->status,
                'categories'   => $lead->categories,
                'wards'        => $lead->wards,
                'budget_min'   => $lead->demand_rate_min,
                'budget_max'   => $lead->demand_rate_max,
                'note'         => $lead->note,
                'sale_name'    => optional($lead->sale)->name ?? '',
                'created_at_diff' => \Carbon\Carbon::parse($lead->getRawOriginal('created_at'))->diffForHumans(),
            ];
        });

        return response()->json([
            'success'    => true,
            'leads'      => $items,
            'total'      => $paginator->total(),
            'has_more'   => $paginator->hasMorePages(),
            'next_page'  => $paginator->currentPage() + 1,
        ]);
    }

    // Task 11: Search Areas API
    public function searchAreas(Request $request)
    {
        $wards = LocationsWard::where('district_code', config('location.district_code'))->get();

        $wardStats = Property::where('status', 1)
            ->selectRaw('ward_code, count(*) as count_bds, AVG(price) as avg_price')
            ->groupBy('ward_code')
            ->get()
            ->keyBy('ward_code');

        $data = $wards->map(function ($w) use ($wardStats) {
            $stats = $wardStats[$w->code] ?? null;
            $avgPrice = null;
            if ($stats && $stats->avg_price > 0) {
                $avg = $stats->avg_price;
                $ty = 1000000000;
                $trieu = 1000000;
                if ($avg >= $ty) {
                    $avgPrice = number_format($avg / $ty, 1) . ' tỷ';
                } elseif ($avg > 0) {
                    $avgPrice = number_format($avg / $trieu, 0) . ' triệu';
                }
            }
            return [
                'code'      => $w->code,
                'name'      => $w->full_name,
                'count_bds' => $stats ? $stats->count_bds : 0,
                'avg_price' => $avgPrice,
            ];
        })->sortByDesc('count_bds')->values();

        return response()->json(['success' => true, 'areas' => $data]);
    }

    public function profile(Request $request)
    {
        $customer = Auth::guard('webapp')->user();
        return view('frontend_dashboard_myprofile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('webapp')->user();

        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'mobile'  => ['nullable', 'string', 'regex:/^(0[3-9][0-9]{8})$/'],
            'address' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required'   => 'Họ và tên không được để trống.',
            'email.required'  => 'Email không được để trống.',
            'email.email'     => 'Email không đúng định dạng.',
            'mobile.regex'    => 'Số điện thoại phải là số VN 10 chữ số (bắt đầu bằng 03-09).',
        ]);

        $customer->fill($validated)->save();

        return redirect()->route('webapp.profile')->with('success', 'Cập nhật hồ sơ thành công!');
    }

    public function updateAvatar(Request $request)
    {
        $customer = Auth::guard('webapp')->user();

        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ], [
            'avatar.required' => 'Vui lòng chọn ảnh.',
            'avatar.image'    => 'File phải là ảnh (jpg, png, gif...).',
            'avatar.max'      => 'Ảnh không được vượt quá 2MB.',
        ]);

        $file = $request->file('avatar');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path('images') . config('global.USER_IMG_PATH');

        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $file->move($destinationPath, $filename);

        // Lưu raw filename (accessor sẽ build full URL)
        $customer->setRawAttributes(array_merge($customer->getAttributes(), ['profile' => $filename]));
        $customer->save();

        return response()->json([
            'success' => true,
            'url'     => url('images' . config('global.USER_IMG_PATH') . $filename),
        ]);
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

    public function feed(Request $request)
    {
        try {
            $customer = Auth::guard('webapp')->user();

            $perPage = 10;
            // Get properties with status = 1 (Active) and sort by newest
            $query = Property::with(['category', 'host'])->where('status', 1)->orderBy('created_at', 'desc');

            $properties = $query->paginate($perPage);

            if ($request->ajax()) {
                return view('frontends.components.dashboard_feed_items', compact('properties', 'customer'))->render();
            }

            return view('frontend_dashboard_feed', compact('customer', 'properties'));
        }
        catch (\Exception $e) {
            Log::error($e);
            if ($request->ajax()) {
                return response()->json(['error' => 'Server Error'], 500);
            }
            return redirect()->route('webapp')->with('error', 'Không thể tải luồng tin.');
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

            $this->notifyNewListingToTelegram($property, $customer);

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

    public function addCustomer(Request $request)
    {
        // Get customer data for authenticating (optional if required by view)
        $customer = Auth::guard('webapp')->user();

        // 1. Property Types (Categories)
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

        return view('frontend_dashboard_add_customer', compact('propertyTypes', 'wards', 'streets'));
    }

    public function storeCustomer(Request $request)
    {
        try {
            DB::beginTransaction();

            $customer = Auth::guard('webapp')->user();
            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập lại.'], 401);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'lead_type' => 'required|in:buy,rent',
                'categories' => 'nullable|array',
                'wards' => 'nullable|array',
                'price_min' => 'nullable|numeric|min:0',
                'price_max' => 'nullable|numeric|min:0',
                'purpose' => 'nullable|string',
            ], [
                'name.required' => 'Vui lòng nhập tên khách hàng.',
                'phone.required' => 'Vui lòng nhập số điện thoại.',
                'lead_type.required' => 'Vui lòng chọn nhu cầu (Mua/Thuê).',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // 1. Create or Update CrmCustomer
            // Normalize phone
            $rawPhone = $request->input('phone');
            $phone = preg_replace('/[^0-9]/', '', $rawPhone);
            if (substr($phone, 0, 1) === '0') {
                $phone = '84' . substr($phone, 1);
            }

            $crmCustomer = CrmCustomer::firstOrNew(['contact' => $phone]);
            $crmCustomer->full_name = $request->input('name');
            $crmCustomer->contact = $phone;
            $crmCustomer->save();

            // 2. Create CrmLead
            $lead = new CrmLead();
            $lead->user_id = $customer->id; // The broker who added this customer
            $lead->customer_id = $crmCustomer->id;
            $lead->lead_type = $request->input('lead_type');
            $lead->categories = $request->input('categories');
            $lead->wards = $request->input('wards');
            $lead->demand_rate_min = $request->input('price_min', 0);
            $lead->demand_rate_max = $request->input('price_max', 0);
            $lead->purpose = $request->input('purpose');
            $lead->source_note = 'telegram_webapp';

            $note = $request->input('purpose', '');
            if ($request->filled('street')) {
                $streetCode = $request->input('street');
                $streetObj = LocationsStreet::where('code', $streetCode)->first();
                if ($streetObj) {
                    if ($note)
                        $note .= ' - ';
                    $note .= 'Tên đường: ' . $streetObj->street_name;
                }
            }
            $lead->note = $note;

            $lead->status = 'new';
            $lead->save();

            DB::commit();

            $this->notifyNewLeadToTelegram($lead, $crmCustomer, $customer);

            return response()->json([
                'success' => true,
                'message' => 'Thêm khách hàng thành công.',
                'redirect_url' => route('webapp.leads') // Or back to dashboard
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

    private function notifyNewListingToTelegram(Property $property, $customer): void
    {
        try {
            $notificationService = app(NotificationService::class);
            $type = $property->property_type == 0 ? 'Bán' : 'Cho thuê';
            $price = number_format((float)$property->price, 0, ',', '.');
            $posterName = $customer->name ?? 'N/A';
            $posterPhone = $customer->mobile ?? $customer->phone ?? 'N/A';
            $propertyUrl = route('property.showid', ['id' => $property->id]);

            $message = "🏠 *BĐS MỚI TỪ WEBAPP*\n";
            $message .= "----------------\n";
            $message .= "🆔 ID: `{$property->id}`\n";
            $message .= "📌 Loại tin: {$type}\n";
            $message .= "📝 Tiêu đề: " . $this->escapeTelegramText($property->title) . "\n";
            $message .= "📍 Địa chỉ: " . $this->escapeTelegramText($property->address) . "\n";
            $message .= "💰 Giá: {$price} VNĐ\n";
            $message .= "👤 Người đăng: " . $this->escapeTelegramText($posterName) . "\n";
            $message .= "📞 Liên hệ: " . $this->escapeTelegramText($posterPhone) . "\n";
            $message .= "📊 Trạng thái: Chờ duyệt\n";
            $message .= "🔗 [Xem tin]({$propertyUrl})";

            $notificationService->sendToGroup('public_channel', $message);
        }
        catch (\Exception $e) {
            Log::warning('Failed to send listing telegram notification: ' . $e->getMessage());
        }
    }

    private function notifyNewLeadToTelegram(CrmLead $lead, CrmCustomer $crmCustomer, $creator): void
    {
        try {
            $notificationService = app(NotificationService::class);
            $leadType = $lead->lead_type === 'rent' ? 'Cần thuê' : 'Cần mua';
            $budgetMin = number_format((float)($lead->demand_rate_min ?? 0), 0, ',', '.');
            $budgetMax = number_format((float)($lead->demand_rate_max ?? 0), 0, ',', '.');
            $wards = 'Không giới hạn';
            if (is_array($lead->wards) && count($lead->wards) > 0) {
                $wardNames = LocationsWard::whereIn('code', $lead->wards)->pluck('full_name')->toArray();
                $wards = count($wardNames) > 0 ? implode(', ', $wardNames) : implode(', ', $lead->wards);
            }

            $categories = 'Không giới hạn';
            if (is_array($lead->categories) && count($lead->categories) > 0) {
                $categoryNames = Category::whereIn('id', $lead->categories)->pluck('category')->toArray();
                $categories = count($categoryNames) > 0 ? implode(', ', $categoryNames) : implode(', ', $lead->categories);
            }
            $creatorName = $creator->name ?? 'N/A';
            $creatorPhone = $creator->mobile ?? $creator->phone ?? 'N/A';
            $leadUrl = route('webapp.leads');

            $message = "🎯 [ĐÀ LẠT BĐS] - KHÁCH HÀNG MỚI\n";
            $message .= "----------------\n";
            $message .= "🆔 Lead ID: `{$lead->id}`\n";
            $message .= "👤 Khách hàng: " . $this->escapeTelegramText($crmCustomer->full_name ?? 'N/A') . "\n";
            //$message .= "📞 SĐT khách: " . $this->escapeTelegramText($crmCustomer->contact ?? 'N/A') . "\n";
            $message .= "🏷️ Nhu cầu: {$leadType}\n";
            $message .= "💰 Ngân sách: {$budgetMin} - {$budgetMax} VNĐ\n";
            $message .= "📍 Khu vực: " . $this->escapeTelegramText($wards) . "\n";
            $message .= "🏠 Loại BĐS: " . $this->escapeTelegramText($categories) . "\n";
            $message .= "🧭 Mục đích: " . $this->escapeTelegramText($lead->purpose ?? 'N/A') . "\n";
            //$message .= "👨‍💼 Người tạo: " . $this->escapeTelegramText($creatorName) . " - " . $this->escapeTelegramText($creatorPhone) . "\n";
            //$message .= "🔗 [Mở danh sách lead]({$leadUrl})";

            $notificationService->sendToGroup('public_channel', $message);

            // Gửi vào group sale_admin với 1 nút web_app để mở trang phân công
            $assignUrl = URL::temporarySignedRoute(
                'webapp.leads.assign-page',
                Carbon::now()->addHours(24),
                ['id' => $lead->id]
            );

            ['text' => $groupMessage, 'keyboard' => $keyboard] =
                TelegramMessageTemplates::newLeadForGroupWebApp($lead->load(['customer', 'user']), $assignUrl);

            $notificationService->sendWithInlineKeyboard(
                (string) config('services.telegram.groups.sale_admin'),
                $groupMessage,
                $keyboard
            );

            // Gửi xác nhận đến broker tạo lead
            if ($creator && $creator->telegram_id) {
                $brokerMsg = "✅ *Đà Lạt BĐS đã tiếp nhận thông tin khách hàng của bạn!*\n";
                $brokerMsg .= "Đội ngũ chúng tôi sẽ hỗ trợ tư vấn và kết nối sớm nhất có thể.\n";
                $brokerMsg .= "----------------\n";
                $brokerMsg .= "🆔 Lead ID: `{$lead->id}`\n";
                $brokerMsg .= "👤 Khách hàng: " . $this->escapeTelegramText($crmCustomer->full_name ?? 'N/A') . "\n";
                $brokerMsg .= "🏷️ Nhu cầu: {$leadType}\n";
                $brokerMsg .= "💰 Ngân sách: {$budgetMin} - {$budgetMax} VNĐ\n";
                $brokerMsg .= "📍 Khu vực: " . $this->escapeTelegramText($wards) . "\n";
                $brokerMsg .= "🏠 Loại BĐS: " . $this->escapeTelegramText($categories) . "\n";
                $notificationService->sendToCustomer($creator, $brokerMsg);
            }
        }
        catch (\Exception $e) {
            Log::warning('Failed to send lead telegram notification: ' . $e->getMessage());
        }
    }

    /**
     * POST /webapp/log-action
     * Fire-and-forget action logging từ frontend webapp.
     */
    public function logAction(Request $request)
    {
        $customer = Auth::guard('webapp')->user();

        $validated = $request->validate([
            'subject_type'  => 'required|in:property,lead,deal',
            'subject_id'    => 'required|integer|min:1',
            'subject_title' => 'nullable|string|max:255',
            'action'        => 'required|in:call,share,edit,view,create,delete',
            'metadata'      => 'nullable|array',
        ]);

        \App\Models\WebappActionLog::create([
            ...$validated,
            'actor_id' => $customer?->id,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * GET /webapp/action-logs
     * Trả về danh sách logs cho admin/sale_admin/bds_admin xem.
     */
    public function actionLogs(Request $request)
    {
        $customer = Auth::guard('webapp')->user();
        if (!$customer || !in_array($customer->getEffectiveRole(), ['admin', 'sale_admin', 'bds_admin'])) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $query = \App\Models\WebappActionLog::with('actor')
            ->orderBy('created_at', 'desc');

        if ($request->subject_type) {
            $query->where('subject_type', $request->subject_type);
        }
        if ($request->action) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(20);

        // Counts by action, respecting subject_type filter only (not action filter)
        $countsQuery = \App\Models\WebappActionLog::query();
        if ($request->subject_type) {
            $countsQuery->where('subject_type', $request->subject_type);
        }
        $countsByAction = $countsQuery->selectRaw('action, count(*) as cnt')
            ->groupBy('action')
            ->pluck('cnt', 'action');

        return response()->json([
            'data'      => $logs->map(fn($log) => [
                'id'            => $log->id,
                'subject_type'  => $log->subject_type,
                'subject_label' => $log->getSubjectLabel(),
                'subject_id'    => $log->subject_id,
                'subject_title' => $log->subject_title,
                'action'        => $log->action,
                'action_label'  => $log->getActionLabel(),
                'action_color'  => $log->getActionColor(),
                'actor_name'    => $log->actor?->name ?? 'Hệ thống',
                'actor_initials'=> $log->actor ? mb_strtoupper(mb_substr($log->actor->name ?? 'S', 0, 1)) : 'S',
                'time_diff'     => $log->created_at->diffForHumans(),
                'time_full'     => $log->created_at->format('H:i d/m/Y'),
            ]),
            'has_more'        => $logs->hasMorePages(),
            'next_page'       => $logs->currentPage() + 1,
            'total'           => $logs->total(),
            'counts_by_action'=> $countsByAction,
        ]);
    }

    private function escapeTelegramText(?string $text): string
    {
        if (!$text) {
            return '';
        }

        return str_replace(['*', '_', '`', '['], ['\*', '\_', '\`', '\['], $text);
    }
}