<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\AssignParameters;
use App\Models\Category;
use App\Models\CrmHost;
use App\Models\Customer;
use App\Models\Favourite;

use App\Models\InterestedUser;
use App\Models\Language;
use App\Models\LocationsStreet;
use App\Models\LocationsWard;
use App\Models\Notifications;
use App\Models\Package;
use App\Models\parameter;
use App\Models\Property;
use App\Models\PropertyImages;
use App\Models\PropertyLegalImage;
use App\Models\PropertysInquiry;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Type;
use App\Models\Usertokens;

use App\Models\User;
use App\Models\Chats;
use Carbon\CarbonInterface;
use App\Models\UserPurchasedPackage;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Schema;
// use GuzzleHttp\Client;
use App\Models\report_reasons;
use App\Models\user_reports;


use Intervention\Image\ImageManagerStatic as Image;

use Illuminate\Support\Str;
use kornrunner\Blurhash\Blurhash;
use App\Libraries\Paypal;
use App\Models\Payments;
use App\Libraries\Paypal_pro;
use App\Models\AssignedOutdoorFacilities;
use App\Models\OutdoorFacilities;
// HuyTBQ:
use App\Models\CrmCustomer;
use App\Models\CrmDeal;
use App\Models\CrmLead;
use App\Models\CrmDealAssigned;
use App\Models\CrmDealProduct;
use App\Models\CrmDealCommission;

// use PayPal_Pro as GlobalPayPal_Pro;
use Tymon\JWTAuth\Claims\Issuer;


//use Google\Client;
use Google\Service\PlayIntegrity;
use App\Http\Requests\VerifyIntegrityRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Services\PropertyService;
use App\Http\Resources\PropertyResource;

class ApiController extends Controller
{
    public function __construct(protected UserService $userService, protected PropertyService $propertyService) {}

    function update_subscription()
    {
        $data = UserPurchasedPackage::where('user_id', Auth::id())->where('end_date', Carbon::now());
        if ($data) {
            $Customer = Customer::find(Auth::id());
            $Customer->subscription = 0;
            $Customer->update();
        }
    }
    //* START :: get_system_settings   *//
    public function get_system_settings(Request $request)
    {


        $result = '';

        $result = Setting::select('type', 'data')->get();
        $data_arr = [];
        foreach ($result as $row) {
            $tempRow[$row->type] = $row->data;
        }





        if (isset($request->user_id)) {

            $data = UserPurchasedPackage::where('modal_id', $request->user_id)->where('end_date', date('d'))->where('end_date', '!=', NULL)->get();


            $customer = Customer::select('id')->where('subscription', 1)->with('user_purchased_package.package')->find($request->user_id);


            if ($customer) {
                if (count($data)) {

                    $customer->subscription = 0;
                    $customer->update();
                }

                $tempRow['subscription'] = true;
                $tempRow['package'] = $customer;
            } else {
                $tempRow['subscription'] = false;
            }
        }
        $language = Language::select('code', 'name')->get();
        $tempRow['demo_mode'] = env('DEMO_MODE');
        $tempRow['languages'] = $language;

        if (!empty($result)) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $tempRow;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: Get System Setting   *//
    //* START :: user_signup   *//
    public function user_signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'type' => 'required',
            'firebase_id' => 'required',

        ]);

        if (!$validator->fails()) {
            $data = $request->all();
            if ($request->hasFile('profile')) {
                $data['profile_file'] = $request->file('profile');
            }

            try {
                $result = $this->userService->signup($data);
                
                $response['error'] = false;
                $response['message'] = $result['message'];
                $response['token'] = $result['token'];
                $response['data'] = new UserResource($result['user']);
            } catch (Exception $e) {
                $response['error'] = true;
                $response['message'] = $e->getMessage();
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Please fill all data and Submit';
        }
        return response()->json($response);
    }

    /**
     * Server-to-server login to issue JWT for a Customer.
     * Requires a server secret to be provided in the request (env: API_LOGIN_SECRET).
     * This avoids exposing token issuance publicly since customers don't have passwords in this app.
     */
    public function login(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'secret' => 'required',
            'telegram_id' => 'nullable',
            'first_name' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Thiếu số điện thoại hoặc secret',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Kiểm tra Secret
        $secret = env('API_LOGIN_SECRET');
        if (empty($secret) || $request->input('secret') !== $secret) {
            return response()->json([
                'error' => true,
                'message' => 'Secret key không hợp lệ'
            ], 401);
        }

        try {
            $result = $this->userService->login($request->all());

            return response()->json([
                'error' => false,
                'message' => $result['message'],
                'access_token' => $result['token'],
                'token_type' => 'bearer',
                'data' => new UserResource($result['user'])
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    public function check_telegram_user(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'telegram_id' => 'required|numeric',
            'secret' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Thiếu telegram_id hoặc secret',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Kiểm tra Secret
        $secret = env('API_LOGIN_SECRET');
        if (empty($secret) || $request->input('secret') !== $secret) {
            return response()->json([
                'error' => true,
                'message' => 'Secret key không hợp lệ'
            ], 401);
        }

        $telegramId = $request->telegram_id;

        // 3. Tìm user trong Database
        $customer = Customer::where('telegram_id', $telegramId)->first();

        // --- TRƯỜNG HỢP A: ĐÃ CÓ USER (NGƯỜI QUEN) ---
        if ($customer) {
            // Kiểm tra trạng thái tài khoản
            if (isset($customer->isActive) && $customer->isActive == 0) {
                return response()->json([
                    'status' => 'blocked',
                    'message' => 'Tài khoản đã bị khóa.'
                ], 401);
            }

            // Xử lý JWT Token
            $token = $customer->api_token;
            $needsNewToken = true;

            // 1. Kiểm tra token hiện tại có tồn tại và hợp lệ không
            if (!empty($token)) {
                try {
                    // Set token để kiểm tra
                    JWTAuth::setToken($token);
                    // Nếu token hợp lệ (không hết hạn, signature đúng)
                    if (JWTAuth::check()) {
                        $needsNewToken = false;
                    }
                } catch (\Exception $e) {
                    // Token lỗi hoặc hết hạn -> sẽ tạo mới
                    $needsNewToken = true;
                }
            }

            // 2. Nếu cần tạo token mới (do chưa có hoặc đã hết hạn/lỗi)
            if ($needsNewToken) {
                try {
                    $token = JWTAuth::fromUser($customer);
                    if (!$token) {
                        return response()->json(['error' => true, 'message' => 'Tạo token thất bại'], 500);
                    }

                    // Cập nhật token vào DB
                    $customer->api_token = $token;
                    $customer->save();
                } catch (JWTException $e) {
                    return response()->json(['error' => true, 'message' => 'Tạo token thất bại'], 500);
                }
            }

            return response()->json([
                'status' => 'authenticated',
                'message' => 'Người dùng đã tồn tại.',
                'user' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->mobile, 
                ],
                'access_token' => $token,
            ], 200);
        }

        // --- TRƯỜNG HỢP B: CHƯA CÓ USER (KHÁCH VÃNG LAI) ---
        return response()->json([
            'status' => 'guest',
            'message' => 'Người dùng chưa tồn tại. Vui lòng gửi Contact.',
        ], 200);
    }
    //* START :: get_slider   *//


    public function get_slider(Request $request)
    {
        $data = $this->propertyService->getSlider();
        if (count($data) > 0) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $data;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: get_slider   *//
    //* START :: get_categories   *//
    public function get_categories(Request $request)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;
        $categories = Category::select('id', 'category', 'image', 'parameter_types', 'order')
            ->where('status', '1')
            ->orderBy('order', 'asc');

        if (isset($request->search) && !empty($request->search)) {
            $search = $request->search;
            $categories->where('category', 'LIKE', "%$search%");
        }

        if (isset($request->id) && !empty($request->id)) {
            $id = $request->id;
            $categories->where('id', '=', $id);
        }

        $total = $categories->get()->count();
        $result = $categories->orderBy('sequence', 'ASC')->skip($offset)->take($limit)->get();

        if (!$result->isEmpty()) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            foreach ($result as $row) {
                $row->parameter_types = parameterTypesByCategory($row->id);
            }
            $response['total'] = $total;
            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: get_categories   *//
    //* START :: update_profile   *//
    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',
        ]);

        if (!$validator->fails()) {
            $id = $request->userid;
            $customer = Customer::find($id);

            if (!empty($customer)) {
                $data = $request->all();
                if ($request->hasFile('profile')) {
                    $data['profile_file'] = $request->file('profile');
                }
                
                $customer = $this->userService->updateProfile($customer, $data);
                
                $response['error'] = false;
                $response['data'] = new UserResource($customer);
            } else {
                $response['error'] = false;
                $response['message'] = "No data found!";
                $response['data'] = [];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: update_profile   *//
    //* START :: get_user_by_id   *//
    public function get_user_by_id(Request $request)
    {
        return $this->get_profile($request);
    }

    public function get_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',
        ]);

        if (!$validator->fails()) {
            $id = $request->userid;
            $customer = Customer::find($id);
            if (!empty($customer)) {
                $response['error'] = false;
                $response['data'] = new UserResource($customer);
            } else {
                $response['error'] = false;
                $response['message'] = "No data found!";
                $response['data'] = [];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: get_user_by_id   *//
    //* START :: get_property   *//
    public function get_property(Request $request)
    {
        $token = $this->bearerToken($request);
        if ($token) {
            $payload = JWTAuth::getPayload($token);
            $current_user = ($payload['customer_id']);
        } else {
            $current_user = null;
        }
        
        $properties = $this->propertyService->getPropertyList($request, $current_user);
        $data = PropertyResource::collection($properties);
        
        if ($data->count() > 0) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['total'] = $properties->count();
            $response['data'] = $data;
        } else {
             $response['error'] = false;
             $response['message'] = "No data found!";
             $response['data'] = [];
        }
        return response()->json($response);
    }

    //* START :: get_property_public   *//
    public function get_property_public(Request $request)
    {
        $properties = $this->propertyService->getPropertyList($request, null);
        $data = PropertyResource::collection($properties);
        
        if ($data->count() > 0) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['total'] = $properties->count();
            $response['data'] = $data;
        } else {
             $response['error'] = false;
             $response['message'] = "No data found!";
             $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: get_property_public   *//


    //* START :: getProperties (private method for shared logic)   *//
    // Removed as part of refactoring to use PropertyService
    //* END :: getProperties (private method for shared logic)   *//


    //* START :: post_property   *//
    public function post_property(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // HuyTBQ: Disable packeage modules
            //'package_id' => 'required',
            //'title_image' => 'required|file|max:3000|mimes:jpeg,png,jpg',
        ]);

        if (!$validator->fails()) {
            $token = $this->bearerToken($request);
            if ($token) {
                $payload = JWTAuth::getPayload($token);
                $current_user = ($payload['customer_id']);
            } else {
                $current_user = null;
            }

            try {
                $Saveproperty = $this->propertyService->storeProperty($request, $current_user);
                
                $result = Property::with('customer')->with('category:id,category,image')->with('assignfacilities.outdoorfacilities')->with('favourite')->with('parameters')->with('interested_users')->where('id', $Saveproperty->id)->get();
                $property_details = get_property_details($result);

                $response['error'] = false;
                $response['message'] = 'Property Post Succssfully';
                $response['data'] = $property_details;
            } catch (Exception $e) {
                $response['error'] = true;
                $response['message'] = $e->getMessage();
            }

        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }
        return response()->json($response);
    }
    //* END :: post_property   *//
    //* START :: update_post_property   *//
    /// This api use for update and delete  property
    public function update_post_property(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'action_type' => 'required'
        ]);
        $token = $this->bearerToken($request);
        if ($token) {
            $payload = JWTAuth::getPayload($token);
            $current_user = ($payload['customer_id']);
        } else {
            $current_user = null;
        }
        if (!$validator->fails()) {
            $id = $request->id;
            $action_type = $request->action_type;

            try {
                if ($action_type == 0) {
                    $this->propertyService->updateProperty($request, $current_user);
                    
                    $update_property = Property::with('customer')->with('category:id,category,image')->with('assignfacilities.outdoorfacilities')->with('favourite')->with('parameters')->with('interested_users')->where('id', $id)->get();
                    $property_details = get_property_details($update_property, $current_user);

                    $response['error'] = false;
                    $response['message'] = 'Property Update Successfully';
                    $response['data'] = $property_details;

                } elseif ($action_type == 1) {
                    $this->propertyService->deleteProperty($id, $current_user);
                    $response['error'] = false;
                    $response['message'] = 'Delete Successfully';
                }
            } catch (Exception $e) {
                $response['error'] = true;
                $response['message'] = $e->getMessage();
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: update_post_property   *//
    //* START :: delete_property   *//
    public function delete_property(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:propertys,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first(),
            ]);
        }

        $token = $this->bearerToken($request);
        if ($token) {
            $payload = JWTAuth::getPayload($token);
            $current_user = ($payload['customer_id']);
        } else {
            $current_user = null;
        }

        if (!$current_user) {
            return response()->json([
                'error' => true,
                'message' => 'Authentication required',
            ]);
        }

        try {
            $this->propertyService->deleteProperty($request->id, $current_user);
            
            $response['error'] = false;
            $response['message'] = 'Property deleted successfully';
            return response()->json($response);
        } catch (Exception $e) {
            $response = array(
                'error' => true,
                'message' => $e->getMessage()
            );
            return response()->json($response, 500);
        }
    }
    //* END :: delete_property   *//
    //* START :: remove_post_images   *//
    public function remove_post_images(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if (!$validator->fails()) {
            $id = $request->id;
            $getImage = PropertyImages::where('id', $id)->first();
            $image = $getImage->image;
            $propertys_id = $getImage->propertys_id;

            if (PropertyImages::where('id', $id)->delete()) {
                if (file_exists(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $propertys_id . "/" . $image)) {
                    unlink(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $propertys_id . "/" . $image);
                }
                $response['error'] = false;
            } else {
                $response['error'] = true;
            }

            $countImage = PropertyImages::where('propertys_id', $propertys_id)->get();
            if ($countImage->count() == 0) {
                rmdir(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $propertys_id);
            }

            $response['error'] = false;
            $response['message'] = 'Property Post Succssfully';
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: remove_post_images   *//
    //* START :: set_property_inquiry   *//
    public function set_property_inquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action_type' => 'required',
        ]);

        $token = $this->bearerToken($request);
        if ($token) {
            $payload = JWTAuth::getPayload($token);
            $current_user = ($payload['customer_id']);
        } else {
            $current_user = null;
        }

        if (!$validator->fails()) {
            $action_type = $request->action_type; ////0: add   1:update
            if ($action_type == 0) {
                //add inquiry
                $validator = Validator::make($request->all(), [
                    'property_id' => 'required',
                ]);
                if (!$validator->fails()) {
                    $PropertysInquiry = PropertysInquiry::where('propertys_id', $request->property_id)->where('customers_id', $current_user)->first();
                    if (empty($PropertysInquiry)) {
                        PropertysInquiry::create([
                            'propertys_id' => $request->property_id,
                            'customers_id' => $current_user,
                            'status' => '0'
                        ]);
                        $response['error'] = false;
                        $response['message'] = 'Inquiry Send Succssfully';
                    } else {
                        $response['error'] = true;
                        $response['message'] = 'Request Already Submitted';
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = "Please fill all data and Submit";
                }
            } elseif ($action_type == 1) {
                //update inquiry
                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'status' => 'required',
                ]);

                if (!$validator->fails()) {
                    $id = $request->id;
                    $propertyInquiry = PropertysInquiry::find($id);
                    $propertyInquiry->status = $request->status;
                    $propertyInquiry->update();

                    $response['error'] = false;
                    $response['message'] = 'Inquiry Update Succssfully';
                } else {
                    $response['error'] = true;
                    $response['message'] = "Please fill all data and Submit";
                }
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return response()->json($response);
    }
    //* END :: set_property_inquiry   *//
    //* START :: get_notification_list   *//
    public function get_notification_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',
        ]);

        if (!$validator->fails()) {
            $id = $request->userid;

            $Notifications = Notifications::whereRaw("FIND_IN_SET($id,customers_id)")->orwhere('send_type', '1')->orderBy('id', 'DESC')->get();


            if (!$Notifications->isEmpty()) {
                for ($i = 0; $i < count($Notifications); $i++) {
                    $Notifications[$i]->created = $Notifications[$i]->created_at->diffForHumans();
                    $Notifications[$i]->image = ($Notifications[$i]->image != '') ? url('') . config('global.IMG_PATH') . config('global.NOTIFICATION_IMG_PATH') . $Notifications[$i]->image : '';
                }
                $response['error'] = false;
                $response['data'] = $Notifications;
            } else {
                $response['error'] = false;
                $response['message'] = "No data found!";
                $response['data'] = [];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: get_notification_list   *//
    //* START :: get_property_inquiry   *//
    public function get_property_inquiry(Request $request)
    {

        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;
        $token = $this->bearerToken($request);
        if ($token) {
            $payload = JWTAuth::getPayload($token);
            $current_user = ($payload['customer_id']);
        } else {
            $current_user = null;
        }
        $propertyInquiry = PropertysInquiry::with('property')->where('customers_id', $current_user);
        $total = $propertyInquiry->get()->count();
        $result = $propertyInquiry->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();
        $rows = array();
        $tempRow = array();
        $count = 1;

        if (!$result->isEmpty()) {

            foreach ($result as $key => $row) {
                // print_r($row->toArray());
                $tempRow['id'] = $row->id;
                $tempRow['propertys_id'] = $row->propertys_id;
                $tempRow['customers_id'] = $row->customers_id;
                $tempRow['status'] = $row->status;
                $tempRow['created_at'] = $row->created_at;
                $tempRow['property']['id'] = $row['property']->id;
                $tempRow['property']['title'] = $row['property']->title;
                $tempRow['property']['price'] = $row['property']->price;
                $tempRow['property']['category'] = $row['property']->category;
                $tempRow['property']['description'] = $row['property']->description;
                $tempRow['property']['address'] = $row['property']->address;
                $tempRow['property']['client_address'] = $row['property']->client_address;
                $tempRow['property']['property_type'] = ($row['property']->property_type == '0') ? 'Sell' : 'Rent';
                $tempRow['property']['title_image'] = $row['property']->title_image;
                $tempRow['property']['threeD_image'] = $row['property']->threeD_image;
                $tempRow['property']['post_created'] = $row['property']->created_at->diffForHumans();
                $tempRow['property']['gallery'] = $row['property']->gallery;
                $tempRow['property']['total_view'] = $row['property']->total_click;
                $tempRow['property']['status'] = $row['property']->status;
                $tempRow['property']['state'] = $row['property']->state;
                $tempRow['property']['city'] = $row['property']->city;
                $tempRow['property']['country'] = $row['property']->country;
                $tempRow['property']['latitude'] = $row['property']->latitude;
                $tempRow['property']['longitude'] = $row['property']->longitude;
                $tempRow['property']['added_by'] = $row['property']->added_by;
                foreach ($row->property->assignParameter as $key => $res) {


                    $tempRow['property']["parameters"][$key] = $res->parameter;

                    $tempRow['property']["parameters"][$key]["value"] = $res->value;
                }


                $rows[] = $tempRow;
                // $parameters[] = $arr;
                $count++;
            }

            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['total'] = $total;
            $response['data'] = $rows;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }


        return response()->json($response);
    }
    //* END :: get_property_inquiry   *//
    //* START :: set_property_total_click   *//
    public function set_property_total_click(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required',
        ]);

        if (!$validator->fails()) {
            $property_id = $request->property_id;


            $Property = Property::find($property_id);
            $Property->increment('total_click');

            $response['error'] = false;
            $response['message'] = 'Update Succssfully';
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: set_property_total_click   *//
    //* START :: delete_user   *//
    public function delete_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',

        ]);

        if (!$validator->fails()) {
            $userid = $request->userid;

            Customer::find($userid)->delete();
            Property::where('added_by', $userid)->delete();
            PropertysInquiry::where('customers_id', $userid)->delete();

            $response['error'] = false;
            $response['message'] = 'Delete Succssfully';
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: delete_user   *//
    public function bearerToken($request)
    {
        $header = $request->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        }
    }
    //*START :: add favoutite *//
    public function add_favourite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'property_id' => 'required',


        ]);

        if (!$validator->fails()) {
            //add favourite
            $token = $this->bearerToken($request);
            if ($token) {
                $payload = JWTAuth::getPayload($token);
                $current_user = ($payload['customer_id']);
            } else {
                $current_user = null;
            }
            if ($request->type == 1) {


                $fav_prop = Favourite::where('user_id', $current_user)->where('property_id', $request->property_id)->get();

                if (count($fav_prop) > 0) {
                    $response['error'] = false;
                    $response['message'] = "Property already add to favourite";
                    return response()->json($response);
                }
                $favourite = new Favourite();
                $favourite->user_id = $current_user;
                $favourite->property_id = $request->property_id;
                $favourite->save();
                $response['error'] = false;
                $response['message'] = "Property add to Favourite add successfully";
            }
            //delete favourite
            if ($request->type == 0) {
                Favourite::where('property_id', $request->property_id)->where('user_id', $current_user)->delete();

                $response['error'] = false;
                $response['message'] = "Property remove from Favourite  successfully";
            }
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }


        return response()->json($response);
    }

    public function get_articles(Request $request)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;

        $article = Article::select('id', 'image', 'title', 'description', 'created_at');

        $total = $article->get()->count();
        $result = $article->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();
        if (!$result->isEmpty()) {
            foreach ($article as $row) {

                if (filter_var($row->image, FILTER_VALIDATE_URL) === false) {

                    $row->image = ($row->image != '') ? url('') . config('global.IMG_PATH') . config('global.ARTICLE_IMG_PATH') . $row->image : '';
                } else {
                    $row->image = $row->image;
                }
            }
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['total'] = $total;
            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    public function store_advertisement(Request $request)
    {
        // dd($request->toArray());
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'property_id' => 'required',
            'package_id' => 'required',
        ]);
        if (!$validator->fails()) {
            $token = $this->bearerToken($request);
            if ($token) {
                $payload = JWTAuth::getPayload($token);
                $current_user = ($payload['customer_id']);
            } else {
                $current_user = null;
            }

            $userpackage = UserPurchasedPackage::where('modal_id', $current_user)->with([
                'package' => function ($q) {
                    $q->select('id', 'property_limit', 'advertisement_limit')->where('advertisement_limit', '!=', NULL);
                }
            ])->first();
            $arr = 0;

            $prop_count = 0;
            if (!($userpackage)) {
                $response['error'] = false;
                $response['message'] = 'Package not found';
                return response()->json($response);
            } else {

                if (!$userpackage->package) {

                    $response['error'] = false;
                    $response['message'] = 'Package not found for add property';
                    return response()->json($response);
                }
                $advertisement_count = $userpackage->package->advertisement_limit;

                $arr = $userpackage->id;

                $advertisement_limit = Advertisement::where('customer_id', $current_user)->where('package_id', $request->package_id)->get();


                if ($userpackage->used_limit_for_advertisement < ($advertisement_count) || $advertisement_count == 0) {

                    $token = $this->bearerToken($request);
                    if ($token) {
                        $payload = JWTAuth::getPayload($token);
                        $current_user = ($payload['customer_id']);
                    } else {
                        $current_user = null;
                    }

                    $package = Package::where('advertisement_limit', '!=', NULL)->find($request->package_id);

                    $adv = new Advertisement();

                    $adv->start_date = Carbon::now();
                    if ($package) {
                        if (isset($request->end_date)) {
                            $adv->end_date = $request->end_date;
                        } else {
                            $adv->end_date = Carbon::now()->addDays($package->duration);
                        }
                        $adv->package_id = $package->id;
                        $adv->type = $request->type;
                        $adv->property_id = $request->property_id;
                        $adv->customer_id = $current_user;
                        $adv->is_enable = false;
                        $adv->status = 0;

                        $destinationPath = public_path('images') . config('global.ADVERTISEMENT_IMAGE_PATH');
                        if (!is_dir($destinationPath)) {
                            mkdir($destinationPath, 0777, true);
                        }

                        if ($request->type == 'Slider') {
                            $destinationPath_slider = public_path('images') . config('global.SLIDER_IMG_PATH');

                            if (!is_dir($destinationPath_slider)) {
                                mkdir($destinationPath_slider, 0777, true);
                            }
                            $slider = new Slider();

                            if ($request->hasFile('image')) {


                                $file = $request->file('image');


                                $name = time() . rand(1, 100) . '.' . $file->extension();

                                $file->move($destinationPath_slider, $name);
                                $sliderimageName = microtime(true) . "." . $file->getClientOriginalExtension();
                                $slider->image = $sliderimageName;
                            } else {
                                $slider->image = '';
                            }
                            $slider->category_id = isset($request->category_id) ? $request->category_id : 0;
                            $slider->propertys_id = $request->property_id;
                            $slider->save();
                        }
                        $result = Property::with('customer')->with('category:id,category,image')->with('favourite')->with('parameters')->with('interested_users')->where('id', $request->property_id)->get();
                        $property_details = get_property_details($result);

                        $adv->image = "";
                        $adv->save();
                        $userpackage->used_limit_for_advertisement = $userpackage->used_limit_for_advertisement + 1;

                        $userpackage->save();
                        $response['error'] = false;
                        $response['message'] = "Advertisement add successfully";
                        $response['data'] = $property_details;
                    } else {
                        $response['error'] = false;
                        $response['message'] = "Package not found";
                        return response()->json($response);
                    }
                } else {
                    $response['error'] = false;
                    $response['message'] = "Package Limit is over";
                    return response()->json($response);
                }
            }
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }
        return response()->json($response);
    }
    public function get_advertisement(Request $request)
    {

        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;

        $article = Article::select('id', 'image', 'title', 'description');
        $date = date('Y-m-d');
        DB::enableQueryLog();
        $adv = Advertisement::select('id', 'image', 'category_id', 'property_id', 'type', 'customer_id', 'is_enable', 'status')->with('customer:id,name')->where('end_date', '>', $date);
        if (isset($request->customer_id)) {
            $adv->where('customer_id', $request->customer_id);
        }
        $total = $adv->get()->count();
        $result = $adv->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();
        if (!$result->isEmpty()) {
            foreach ($adv as $row) {
                if (filter_var($row->image, FILTER_VALIDATE_URL) === false) {
                    $row->image = ($row->image != '') ? url('') . config('global.IMG_PATH') . config('global.ADVERTISEMENT_IMAGE_PATH') . $row->image : '';
                } else {
                    $row->image = $row->image;
                }
            }
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }


        return response()->json($response);
    }
    public function get_package(Request $request)
    {

        $date = date('Y-m-d');
        DB::enableQueryLog();
        $package = Package::where('status', 1)->orderBy('id', 'ASC')->where('name', '!=', 'Trial Package')->get();
        // dd(DB::getQueryLog());
        if (!$package->isEmpty()) {

            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $package;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }

        return response()->json($response);
    }
    public function user_purchase_package(Request $request)
    {

        $start_date = Carbon::now();
        $validator = Validator::make($request->all(), [

            'package_id' => 'required',

        ]);

        if (!$validator->fails()) {
            $payload = JWTAuth::getPayload($this->bearerToken($request));
            $current_user = ($payload['customer_id']);
            if (isset($request->flag)) {
                $user_exists = UserPurchasedPackage::where('modal_id', $current_user)->get();
                if ($user_exists) {
                    UserPurchasedPackage::where('modal_id', $current_user)->delete();
                }
            }

            $package = Package::find($request->package_id);
            $user = Customer::find($current_user);
            $data_exists = UserPurchasedPackage::where('modal_id', $current_user)->get();
            if (count($data_exists) == 0 && $package) {
                $user_package = new UserPurchasedPackage();
                $user_package->modal()->associate($user);
                $user_package->package_id = $request->package_id;
                $user_package->start_date = $start_date;
                $user_package->end_date = $package->duratio != 0 ? Carbon::now()->addDays($package->duration) : NULL;
                $user_package->save();

                $user->subscription = 1;
                $user->update();

                $response['error'] = false;
                $response['message'] = "purchased package  add successfully";
            } else {
                $response['error'] = false;
                $response['message'] = "data already exists or package not found or add flag for add new package";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return response()->json($response);
    }
    public function get_favourite_property(Request $request)
    {

        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 25;

        $token = $this->bearerToken($request);
        if ($token) {
            $payload = JWTAuth::getPayload($token);
            $current_user = ($payload['customer_id']);
        } else {
            $current_user = null;
        }
        DB::enableQueryLog(); // Enable query log


        $favourite = Favourite::where('user_id', $current_user)->select('property_id')->get();
        // dd($favourite);
        $arr = array();
        foreach ($favourite as $p) {
            $arr[] = $p->property_id;
        }

        $property_details = Property::whereIn('id', $arr)->with('category:id,category,image')->with('assignfacilities.outdoorfacilities')->with('parameters');
        $result = $property_details->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();

        $total = $result->count();

        if (!$result->isEmpty()) {
            $result->transform(function ($property) {
                if ($property->property_type == 0) {
                    $property->property_type = "Sell";
                } elseif ($property->property_type == 1) {
                    $property->property_type = "Rent";
                } elseif ($property->property_type == 2) {
                    $property->property_type = "Sold";
                } elseif ($property->property_type == 3) {
                    $property->property_type = "Rented";
                }
                return $property;
            });
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = get_property_details($result);
            $response['total'] = $total;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    public function delete_advertisement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',

        ]);

        if (!$validator->fails()) {
            $adv = Advertisement::find($request->id);
            if (!$adv) {
                $response['error'] = false;
                $response['message'] = "Data not found";
            } else {

                $adv->delete();
                $response['error'] = false;
                $response['message'] = "Advertisement Deleted successfully";
            }
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }
        return response()->json($response);
    }
    public function interested_users(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required',
            'type' => 'required'


        ]);
        if (!$validator->fails()) {
            $token = $this->bearerToken($request);
            if ($token) {
                $payload = JWTAuth::getPayload($token);
                $current_user = ($payload['customer_id']);
            } else {
                $current_user = null;
            }

            $interested_user = InterestedUser::where('customer_id', $current_user)->where('property_id', $request->property_id);

            if ($request->type == 1) {

                if (count($interested_user->get()) > 0) {
                    $response['error'] = false;
                    $response['message'] = "already added to interested users ";
                } else {
                    $interested_user = new InterestedUser();
                    $interested_user->property_id = $request->property_id;
                    $interested_user->customer_id = $current_user;
                    $interested_user->save();
                    $response['error'] = false;
                    $response['message'] = "Interested Users added successfully";
                }
            }
            if ($request->type == 0) {

                if (count($interested_user->get()) == 0) {
                    $response['error'] = false;
                    $response['message'] = "No data found to delete";
                } else {
                    $interested_user->delete();

                    $response['error'] = false;
                    $response['message'] = "Interested Users removed  successfully";
                }
            }
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }
        return response()->json($response);
    }
    public function delete_inquiry(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',

        ]);

        if (!$validator->fails()) {
            $adv = PropertysInquiry::where('status', 0)->find($request->id);
            if (!$adv) {
                $response['error'] = false;
                $response['message'] = "Data not found";
            } else {

                $adv->delete();
                $response['error'] = false;
                $response['message'] = "Property inquiry Deleted successfully";
            }
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }
        return response()->json($response);
    }
    public function user_interested_property(Request $request)
    {

        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 25;


        $payload = JWTAuth::getPayload($this->bearerToken($request));
        $current_user = ($payload['customer_id']);
        DB::enableQueryLog(); // Enable query log


        $favourite = InterestedUser::where('customer_id', $current_user)->select('property_id')->get();
        // dd($favourite);
        $arr = array();
        foreach ($favourite as $p) {
            $arr[] = $p->property_id;
        }
        $property_details = Property::whereIn('id', $arr)->with('category:id,category')->with('parameters');
        $result = $property_details->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();
        // dd(\DB::getQueryLog());

        $total = $result->count();

        if (!$result->isEmpty()) {
            foreach ($property_details as $row) {
                if (filter_var($row->image, FILTER_VALIDATE_URL) === false) {
                    $row->image = ($row->image != '') ? url('') . config('global.IMG_PATH') . config('global.PROPERTY_TITLE_IMG_PATH') . $row->image : '';
                } else {
                    $row->image = $row->image;
                }
            }
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $result;
            $response['total'] = $total;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    public function get_limits(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'id' => 'required',
        ]);
        if (!$validator->fails()) {
            $token = $this->bearerToken($request);
            if ($token) {
                $payload = JWTAuth::getPayload($token);
                $current_user = ($payload['customer_id']);
            } else {
                $current_user = null;
            }
            $package = UserPurchasedPackage::where('modal_id', $current_user)->where('package_id', $request->id)->with([
                'package' => function ($q) {
                    $q->select('id', 'property_limit', 'advertisement_limit');
                }
            ])->first();
            if (!$package) {
                $response['error'] = true;
                $response['message'] = "package not found";
                return response()->json($response);
            }
            $arr = 0;
            $adv_count = 0;
            $prop_count = 0;
            // foreach ($package as $p) {

            ($adv_count = $package->package->advertisement_limit == 0 ? "Unlimited" : $package->package->advertisement_limit);
            ($prop_count = $package->package->property_limit == 0 ? "Unlimited" : $package->package->property_limit);

            ($arr = $package->id);
            // }

            $advertisement_limit = Advertisement::where('customer_id', $current_user)->where('package_id', $request->id)->get();
            // DB::enableQueryLog();

            $propeerty_limit = Property::where('added_by', $current_user)->where('package_id', $request->id)->get();


            $response['total_limit_of_advertisement'] = ($adv_count);
            $response['total_limit_of_property'] = ($prop_count);


            $response['used_limit_of_advertisement'] = $package->used_limit_for_advertisement;
            $response['used_limit_of_property'] = $package->used_limit_for_property;
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }
        return response()->json($response);
    }
    public function get_languages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language_code' => 'required',

        ]);
        if (!$validator->fails()) {

            DB::enableQueryLog();

            $language = Language::where('code', $request->language_code);

            $result = $language->get();

            //  dd(DB::getQueryLog());

            if ($result) {
                $response['error'] = false;
                $response['message'] = "Data Fetch Successfully";



                $response['data'] = $result;
            } else {
                $response['error'] = false;
                $response['message'] = "No data found!";
                $response['data'] = [];
            }
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }
        return response()->json($response);
    }
    public function get_payment_details(Request $request)
    {
        $payload = JWTAuth::getPayload($this->bearerToken($request));
        $current_user = ($payload['customer_id']);

        $payment = Payments::where('customer_id', $current_user);

        $result = $payment->get();

        //  dd(DB::getQueryLog());

        if (count($result)) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";



            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    public function paypal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required',
            'amount' => 'required'

        ]);
        if (!$validator->fails()) {
            $token = $this->bearerToken($request);
            if ($token) {
                $payload = JWTAuth::getPayload($token);
                $current_user = ($payload['customer_id']);
            } else {
                $current_user = null;
            }
            $paypal = new Paypal();
            // url('') . config('global.IMG_PATH')
            $returnURL = url('api/app_payment_status');
            $cancelURL = url('api/app_payment_status');
            $notifyURL = url('webhook/paypal');
            // $package_id = $request->package_id;
            $package_id = $request->package_id;
            // Get product data from the database

            // Get current user ID from the session
            $paypal->add_field('return', $returnURL);
            $paypal->add_field('cancel_return', $cancelURL);
            $paypal->add_field('notify_url', $notifyURL);
            $custom_data = $package_id . ',' . $current_user;

            // // Add fields to paypal form


            $paypal->add_field('item_name', "package");
            $paypal->add_field('custom_id', json_encode($custom_data));

            $paypal->add_field('custom', ($custom_data));

            $paypal->add_field('amount', $request->amount);

            // Render paypal form
            $paypal->paypal_auto_form();
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
    }
    public function app_payment_status(Request $request)
    {

        $paypalInfo = $request->all();

        if (!empty($paypalInfo) && isset($_GET['st']) && strtolower($_GET['st']) == "completed") {

            $response['error'] = false;
            $response['message'] = "Your Purchase Package Activate Within 10 Minutes ";
            $response['data'] = $paypalInfo['txn_id'];
        } elseif (!empty($paypalInfo) && isset($_GET['st']) && strtolower($_GET['st']) == "authorized") {

            $response['error'] = false;
            $response['message'] = "Your payment has been Authorized successfully. We will capture your transaction within 30 minutes, once we process your order. After successful capture Ads wil be credited automatically.";
            $response['data'] = $paypalInfo;
        } else {
            $response['error'] = true;
            $response['message'] = "Payment Cancelled / Declined ";
            $response['data'] = (isset($_GET)) ? $paypalInfo : "";
        }
        // print_r(json_encode($response));
        return (response()->json($response));
    }
    public function get_payment_settings(Request $request)
    {

        $payment_settings =
            Setting::select('type', 'data')->whereIn('type', ['paypal_business_id', 'sandbox_mode', 'paypal_gateway', 'razor_key', 'razor_secret', 'razorpay_gateway', 'paystack_public_key', 'paystack_secret_key', 'paystack_currency', 'paystack_gateway', 'stripe_publishable_key', 'stripe_currency', 'stripe_gateway', 'stripe_secret_key']);

        $result = $payment_settings->get();

        //  dd(DB::getQueryLog());

        if (count($result)) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return (response()->json($response));
    }
    public function send_message(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'sender_id' => 'required',
            'receiver_id' => 'required',
            'message' => 'required',
            'property_id' => 'required',
        ]);
        $fcm_id = array();
        if (!$validator->fails()) {

            $chat = new Chats();
            $chat->sender_id = $request->sender_id;
            $chat->receiver_id = $request->receiver_id;
            $chat->property_id = $request->property_id;
            $chat->message = $request->message;
            $destinationPath = public_path('images') . config('global.CHAT_FILE');
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            // image upload

            if ($request->hasFile('file')) {
                // dd('in');
                $file = $request->file('file');
                $fileName = microtime(true) . "." . $file->getClientOriginalExtension();
                $file->move($destinationPath, $fileName);
                $chat->file = $fileName;
            } else {
                $chat->file = '';
            }

            $audiodestinationPath = public_path('images') . config('global.CHAT_AUDIO');
            if (!is_dir($audiodestinationPath)) {
                mkdir($audiodestinationPath, 0777, true);
            }
            if ($request->hasFile('audio')) {
                // dd('in');
                $file = $request->file('audio');
                $fileName = microtime(true) . "." . $file->getClientOriginalExtension();
                $file->move($audiodestinationPath, $fileName);
                $chat->audio = $fileName;
            } else {
                $chat->audio = '';
            }
            $chat->save();
            $customer = Customer::select('id', 'fcm_id', 'name', 'profile')->with([
                'usertokens' => function ($q) {
                    $q->select('fcm_id', 'id', 'customer_id');
                }
            ])->find($request->receiver_id);
            $property = Property::find($request->property_id);
            // dd($customer->toArray());
            if ($customer) {

                foreach ($customer->usertokens as $usertokens) {

                    array_push($fcm_id, $usertokens->fcm_id);
                }
                // $fcm_id = [$customer->usertokens->fcm_id];

                $username = $customer->name;
                $profile = $customer->profile;
            }
            $user_data = User::select('fcm_id', 'name')->get();

            if (!$customer && $property->added_by == 0) {
                $username = "Admin";
                $profile = "";

                foreach ($user_data as $user) {
                    array_push($fcm_id, $user->fcm_id);
                }
            };
            $customer = Customer::select('fcm_id', 'name')->find($request->sender_id);

            // print_r($fcm_id);
            $Property = Property::find($request->property_id);
            $fcmMsg = array(
                'title' => 'Message',
                'message' => $request->message,
                'type' => 'chat',
                'body' => $request->message,
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'file' => $chat->file,
                'username' => $username,
                'user_profile' => $profile,
                'audio' => $chat->audio,
                'date' => $chat->created_at,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'sound' => 'default',
                'time_ago' => $chat->created_at->diffForHumans(now(), CarbonInterface::DIFF_RELATIVE_AUTO, true),
                'property_id' => $Property->id,
                'property_title_image' => $Property->title_image,
                'title' => $Property->title,
            );

            $send = send_push_notification($fcm_id, $fcmMsg);
            $response['error'] = false;
            $response['message'] = "Data Store Successfully";
            $response['id'] = $chat->id;
            $response['data'] = $send;
            // $chat->sender_id = $request->sender_id;
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return (response()->json($response));
    }
    public function get_messages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'property_id' => 'required'

        ]);
        if (!$validator->fails()) {
            $payload = JWTAuth::getPayload($this->bearerToken($request));
            $current_user = ($payload['customer_id']);
            // dd($current_user);

            $tempRow = array();
            $perPage = $request->per_page ? $request->per_page : 15; // Number of results to display per page
            $page = $request->page ?? 1; // Get the current page from the query string, or default to 1
            $chat = Chats::where('property_id', $request->property_id)
                ->where(function ($query) use ($request) {
                    $query->where('sender_id', $request->user_id)
                        ->orWhere('receiver_id', $request->user_id);
                })
                ->Where(function ($query) use ($current_user) {
                    $query->where('sender_id', $current_user)
                        ->orWhere('receiver_id', $current_user);
                })
                ->orderBy('created_at', 'DESC')
                //  ->get();
                ->paginate($perPage, ['*'], 'page', $page);

            // You can then pass the $chat object to your view to display the paginated results.
            // dd($chat->toArray());
            if ($chat) {

                $response['error'] = false;
                $response['message'] = "Data Fetch Successfully";
                $response['total_page'] = $chat->lastPage();
                $response['data'] = $chat;
            } else {
                $response['error'] = false;
                $response['message'] = "No data found!";
                $response['data'] = [];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return response()->json($response);
    }
    public function get_chats(Request $request)
    {
        $payload = JWTAuth::getPayload($this->bearerToken($request));
        $current_user = ($payload['customer_id']);
        $perPage = $request->per_page ? $request->per_page : 15; // Number of results to display per page
        $page = $request->page ?? 1;

        $chat = Chats::with(['sender', 'receiver'])->with('property')
            ->select('id', 'sender_id', 'receiver_id', 'property_id', 'created_at')
            ->where('sender_id', $current_user)
            ->orWhere('receiver_id', $current_user)
            ->orderBy('id', 'desc')
            ->groupBy('property_id')
            ->paginate($perPage, ['*'], 'page', $page);



        if (!$chat->isEmpty()) {

            $rows = array();

            $count = 1;

            $response['total_page'] = $chat->lastPage();

            foreach ($chat as $key => $row) {
                $tempRow = array();
                // $tempRow['property_id'] = $row->property_id;
                // $tempRow['title'] = $row->property->title;
                // $tempRow['title_image'] = $row->property->title_image;
                // Kiểm tra xem mối quan hệ property có tồn tại không
                if ($row->property) {
                    $tempRow['title'] = $row->property->title;
                    $tempRow['title_image'] = $row->property->title_image;
                } else {
                    $tempRow['title'] = "Property not found"; // Giá trị mặc định
                    $tempRow['title_image'] = ""; // Giá trị mặc định
                }

                $tempRow['date'] = $row->created_at;
                $tempRow['property_id'] = $row->property_id;
                if (!$row->receiver || !$row->sender) {
                    $user =
                        user::where('id', $row->sender_id)->orWhere('id', $row->receiver_id)->select('id')->first();

                    $tempRow['user_id'] = 0;
                    $tempRow['name'] = "Admin";
                    $tempRow['profile'] = '';

                    // $tempRow['fcm_id'] = $row->receiver->fcm_id;
                } else {
                    if ($row->sender->id == $current_user) {

                        $tempRow['user_id'] = $row->receiver->id;
                        $tempRow['name'] = $row->receiver->name;
                        $tempRow['profile'] = $row->receiver->profile;
                        $tempRow['firebase_id'] = $row->receiver->firebase_id;
                        $tempRow['fcm_id'] = $row->receiver->fcm_id;
                    }
                    if ($row->receiver->id == $current_user) {
                        $tempRow['user_id'] = $row->sender->id;
                        $tempRow['name'] = $row->sender->name;

                        $tempRow['profile'] = $row->sender->profile;
                        $tempRow['firebase_id'] = $row->sender->firebase_id;
                        $tempRow['fcm_id'] = $row->sender->fcm_id;
                    }
                }
                $rows[] = $tempRow;
                // $parameters[] = $arr;
                $count++;
            }

            //dd($rows);
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $rows;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    public function get_nearby_properties(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'city' => 'required',

        ]);
        if (!$validator->fails()) {
            $result = Property::select('id', 'price', 'latitude', 'longitude', 'property_type')->where('city', 'LIKE', "%$request->city%")->where('status', 1)->get();
            $rows = array();
            $tempRow = array();
            $count = 1;

            if (!$result->isEmpty()) {

                foreach ($result as $key => $row) {
                    $tempRow['id'] = $row->id;
                    $tempRow['price'] = $row->price;
                    $tempRow['latitude'] = $row->latitude;
                    $tempRow['longitude'] = $row->longitude;
                    if ($row->property_type == 0) {
                        $tempRow['property_type'] = "Sell";
                    } elseif ($row->property_type == 1) {
                        $tempRow['property_type'] = "Rent";
                    } elseif ($row->property_type == 2) {
                        $tempRow['property_type'] = "Sold";
                    } elseif ($row->property_type == 3) {
                        $tempRow['property_type'] = "Rented";
                    }
                    $rows[] = $tempRow;

                    $count++;
                }
            }

            $response['error'] = false;
            $response['data'] = $rows;
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return response()->json($response);
    }
    public function update_property_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'property_id' => 'required'

        ]);
        if (!$validator->fails()) {
            $property = Property::find($request->property_id);
            $property->status = $request->status;
            $property->save();
            $response['error'] = false;
            $response['message'] = "Data updated Successfully";
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return response()->json($response);
    }
    public function get_count_by_cities_categoris(Request $request)
    {
        // get count by category

        $categoriesWithCount = Category::withCount('properties')->get();
        $cat_arr = array();
        $city_arr = array();
        $agent_arr = array();

        foreach ($categoriesWithCount as $category) {

            array_push($cat_arr, ['category' => $category->category, 'Count' => $category->properties_count]);
        }
        $response['category_data'] = $cat_arr;
        $propertiesByCity = Property::groupBy('city')
            ->select('city', DB::raw('count(*) as count'))
            ->orderBy('count', 'DESC')->get();

        foreach ($propertiesByCity as $city) {
            $keyword = $city->city; // Get the keyword from the request

            $client = new Client();

            $_imgresponse = $client->request('GET', 'https://www.google.com/search?tbm=isch&q=HighResolutionImageFor' . urlencode($keyword));
            $html = $_imgresponse->getBody()->getContents();

            preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/', $html, $matches);

            $imageUrls = $matches[1] ?? [];

            if (count($imageUrls) > 1) {
                array_push($city_arr, ['City' => $city->city, 'Count' => $city->count, 'image' => $imageUrls[1]]);
            }
        }
        $response['city_data'] = $city_arr;

        return response()->json($response);
    }
    public function get_agents_details(Request $request)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;
        $agent_arr = array();
        $propertiesByAgent = Property::with([
            'customer' => function ($q) {
                $q->where('role', 1);
            }
        ])
            ->groupBy('added_by')
            ->select('added_by', DB::raw('count(*) as count'))->skip($offset)->take($limit)
            ->get();
        foreach ($propertiesByAgent as $agent) {
            if (count($agent->customer)) {
                array_push($agent_arr, ['agent' => $agent->added_by, 'Count' => $agent->count, 'customer' => $agent->customer]);
            }
        }
        if (count($agent_arr)) {
            $response['error'] = false;
            $response['message'] = "Data Fetch  Successfully";
            $response['agent_data'] = $agent_arr;
        } else {
            $response['error'] = false;
            $response['message'] = "No Data Found";
        }
        return response()->json($response);
    }
    public function get_facilities(Request $request)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;

        $facilities = OutdoorFacilities::all();

        // if (isset($request->search) && !empty($request->search)) {
        //     $search = $request->search;
        //     $facilities->where('category', 'LIKE', "%$search%");
        // }

        if (isset($request->id) && !empty($request->id)) {
            $id = $request->id;
            $facilities->where('id', '=', $id);
        }
        $result = $facilities->skip($offset);

        $total = $facilities->count();

        if (!$result->isEmpty()) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";

            $response['total'] = $total;
            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    public function get_report_reasons(Request $request)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;

        $report_reason = report_reasons::all();

        if (isset($request->id) && !empty($request->id)) {
            $id = $request->id;
            $report_reason->where('id', '=', $id);
        }
        $result = $report_reason->skip($offset)->take($limit);

        $total = $report_reason->count();

        if (!$result->isEmpty()) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";

            $response['total'] = $total;
            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    public function add_reports(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reason_id' => 'required',
            'property_id' => 'required',



        ]);
        $payload = JWTAuth::getPayload($this->bearerToken($request));
        $current_user = ($payload['customer_id']);
        if (!$validator->fails()) {
            $report_count = user_reports::where('property_id', $request->property_id)->where('customer_id', $current_user)->get();
            if (!count($report_count)) {
                $report_reason = new user_reports();
                $report_reason->reason_id = $request->reason_id ? $request->reason_id : 0;
                $report_reason->property_id = $request->property_id;
                $report_reason->customer_id = $current_user;
                $report_reason->other_message = $request->other_message ? $request->other_message : '';



                $report_reason->save();


                $response['error'] = false;
                $response['message'] = "Report Submited Successfully";
            } else {
                $response['error'] = false;
                $response['message'] = "Already Reported";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return response()->json($response);
    }
    public function delete_chat_message(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message_id' => 'required',


        ]);
        if (!$validator->fails()) {
            $chat = Chats::find($request->message_id);
            if ($chat) {
                $chat->delete();

                $response['error'] = false;
                $response['message'] = "Message Deleted Successfully";
            } else {
                $response['error'] = false;
                $response['message'] = "No Data Found";
            }
        }
        return response()->json($response);
    }
    //HuyTBQ
    //* START :: get_locations_wards   *//
    public function get_locations_wards(Request $request)
    {
        $districtCode = config('location.district_code');


        $result = LocationsWard::select('code', 'full_name', 'full_name_en', 'district_code', 'administrative_unit_id')
            ->whereNotNull('district_code')
            ->where('district_code', $districtCode)
            ->orderByRaw("CASE
                            WHEN full_name LIKE 'phường%' THEN 1
                            WHEN full_name LIKE 'Xã%' THEN 2
                            ELSE 3 END,
                          CAST(SUBSTRING_INDEX(full_name, ' ', -1) AS UNSIGNED),
                          full_name")
            ->get();

        $total = $result->count();

        if (!$result->isEmpty()) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['total'] = $total;
            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: get_locations_wards   *//
    //* START :: get_streets   *//
    public function get_locations_streets(Request $request)
    {
        $districtCode = config('location.district_code');

        $result = LocationsStreet::select('code', 'street_name', 'district_code', 'ward_code')
            ->whereNotNull('district_code')
            ->where('district_code', $districtCode)
            ->get();

        $total = $result->count();

        if (!$result->isEmpty()) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['total'] = $total;
            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: get_streets   *//

    //* START :: get_crm_host   *//
    public function get_crm_hosts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'host_id' => 'required',
        ]);

        if (!$validator->fails()) {
            $id = $request->host_id;

            $host = CrmHost::find($id);
            if (!empty($host)) {

                $response['error'] = false;
                $response['data'] = $host;
            } else {
                $response['error'] = false;
                $response['message'] = "No data found!";
                $response['data'] = [];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: get_crm_host   *//

    //HuyTBQ : Crm customer
    //* START :: get_customers *//
    public function get_customers(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;

        $customers = CrmCustomer::query();

        if (!empty($request->search)) {
            $customers->where('full_name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('contact', 'LIKE', '%' . $request->search . '%');
        }

        $total = $customers->count();
        $result = $customers->skip($offset)->take($limit)->get();

        if ($result->isNotEmpty()) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['total'] = $total;
            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: get_customers *//

    //* START :: get_customer *//
    public function get_customer($id)
    {
        $customer = CrmCustomer::find($id);

        if ($customer) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $customer;
        } else {
            $response['error'] = true;
            $response['message'] = "Customer not found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: get_customer *//

    //* START :: create_customer *//
    public function create_customer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        } else {
            $customer = CrmCustomer::create([
                'full_name' => $request->full_name,
                'contact' => $request->contact,
                'gender' => $request->gender ?? null,
                'age' => $request->age ?? null,
                'about_customer' => $request->about_customer ?? null,
            ]);

            $response['error'] = false;
            $response['message'] = "Customer created successfully";
            $response['data'] = $customer;
        }
        return response()->json($response);
    }
    //* END :: create_customer *//

    //* START :: update_customer *//
    public function update_customer(Request $request, $id)
    {
        $customer = CrmCustomer::find($id);

        if ($customer) {
            $customer->update([
                'full_name' => $request->full_name ?? $customer->full_name,
                'contact' => $request->contact ?? $customer->contact,
                'gender' => $request->gender ?? $customer->gender,
                'age' => $request->age ?? $customer->age,
                'about_customer' => $request->about_customer ?? $customer->about_customer,
            ]);

            $response['error'] = false;
            $response['message'] = "Customer updated successfully";
            $response['data'] = $customer;
        } else {
            $response['error'] = true;
            $response['message'] = "Customer not found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: update_customer *//

    //* START :: delete_customer *//
    public function delete_customer($id)
    {
        $customer = CrmCustomer::find($id);

        if ($customer) {
            $customer->delete();
            $response['error'] = false;
            $response['message'] = "Customer deleted successfully";
        } else {
            $response['error'] = true;
            $response['message'] = "Customer not found!";
        }
        return response()->json($response);
    }
    //* END :: delete_customer *//
    //HuyTBQ : crm leads
    //* START :: get_leads *//
    public function get_leads(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $userId = $request->userid;

        // Bắt đầu xây dựng truy vấn
        $leads = CrmLead::with(['customer', 'user']);

        // Áp dụng điều kiện lọc theo userid nếu được cung cấp
        if (!empty($userId)) {
            $leads->where('user_id', $userId);
        }

        // Tìm kiếm theo từ khóa nếu được cung cấp
        if (!empty($request->search)) {
            $leads->where(function ($query) use ($request) {
                $query->where('source_note', 'LIKE', '%' . $request->search . '%')
                    ->orWhereHas('customer', function ($subQuery) use ($request) {
                        $subQuery->where('full_name', 'LIKE', '%' . $request->search . '%');
                    });
            });
        }

        // Lấy tổng số lượng leads phù hợp với điều kiện
        $total = $leads->count();

        // Lấy dữ liệu dựa trên offset và limit
        $result = $leads->skip($offset)->take($limit)->get();

        // Chuẩn bị phản hồi
        if ($result->isNotEmpty()) {
            $response = [
                'error' => false,
                'message' => "Data Fetch Successfully",
                'total' => $total,
                'data' => $result,
            ];
        } else {
            $response = [
                'error' => false,
                'message' => "No data found!",
                'data' => [],
            ];
        }

        return response()->json($response);
    }
    //* END :: get_leads *//


    //* START :: get_lead *//
    public function get_lead($id)
    {
        $lead = CrmLead::with(['customer', 'user'])->find($id);

        if ($lead) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $lead;
        } else {
            $response['error'] = true;
            $response['message'] = "Lead not found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: get_lead *//

    //* START :: create_lead *//
    public function create_lead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:crm_customers,id',
            'source_note' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        } else {
            $lead = CrmLead::create([
                'user_id' => $request->user_id,
                'customer_id' => $request->customer_id,
                'source_note' => $request->source_note,
                'lead_type' => $request->lead_type ?? "0",
                'categories' => $request->categories ?? null,
                'wards' => $request->wards ?? null,
                'demand_rate_min' => $request->demand_rate_min ?? 0,
                'demand_rate_max' => $request->demand_rate_max ?? 0,
                'note' => $request->note ?? null,
                'status' => $request->status ?? 'new',
            ]);

            $response['error'] = false;
            $response['message'] = "Lead created successfully";
            $response['data'] = $lead;
        }
        return response()->json($response);
    }
    //* END :: create_lead *//

    //* START :: update_lead *//
    public function update_lead(Request $request, $id)
    {
        $lead = CrmLead::find($id);

        if ($lead) {
            $lead->update([
                'source_note' => $request->source_note ?? $lead->source_note,
                'lead_type' => $request->lead_type ?? $lead->lead_type,
                'categories' => $request->categories ?? $lead->categories,
                'wards' => $request->wards ?? $lead->wards,
                'demand_rate_min' => $request->demand_rate_min ?? $lead->demand_rate_min,
                'demand_rate_max' => $request->demand_rate_max ?? $lead->demand_rate_max,
                'note' => $request->note ?? $lead->note,
                'status' => $request->status ?? $lead->status,
            ]);

            $response['error'] = false;
            $response['message'] = "Lead updated successfully";
            $response['data'] = $lead;
        } else {
            $response['error'] = true;
            $response['message'] = "Lead not found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: update_lead *//

    //* START :: delete_lead *//
    public function delete_lead($id)
    {
        $lead = CrmLead::find($id);

        if ($lead) {
            $lead->delete();
            $response['error'] = false;
            $response['message'] = "Lead deleted successfully";
        } else {
            $response['error'] = true;
            $response['message'] = "Lead not found!";
        }
        return response()->json($response);
    }
    //* END :: delete_lead *//

    //* START :: convert_lead_to_deal *//
    public function convert_lead_to_deal($id)
    {
        $lead = CrmLead::find($id);

        if ($lead) {
            $deal = CrmDeal::create([
                'customer_id' => $lead->customer_id,
                'notes' => $lead->note,
                'status' => 'new',
                'amount' => $lead->demand_rate_min ?? 0,
            ]);

            $lead->update(['status' => 'converted']);

            $response['error'] = false;
            $response['message'] = "Lead converted to Deal successfully";
            $response['data'] = $deal;
        } else {
            $response['error'] = true;
            $response['message'] = "Lead not found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: convert_lead_to_deal *//
    //HuyTBQ: crm_deal
    //* START :: get_deals *//
    public function get_deals(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;

        $deals = CrmDeal::with('customer');

        if (!empty($request->search)) {
            $deals->where('notes', 'LIKE', '%' . $request->search . '%')
                ->orWhereHas('customer', function ($query) use ($request) {
                    $query->where('full_name', 'LIKE', '%' . $request->search . '%');
                });
        }

        $total = $deals->count();
        $result = $deals->skip($offset)->take($limit)->get();

        if ($result->isNotEmpty()) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['total'] = $total;
            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: get_deals *//

    //* START :: get_deal *//
    public function get_deal($id)
    {
        $deal = CrmDeal::with('customer')->find($id);

        if ($deal) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $deal;
        } else {
            $response['error'] = true;
            $response['message'] = "Deal not found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: get_deal *//

    //* START :: create_deal *//
    public function create_deal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:crm_customers,id',
            'amount' => 'required|numeric',
            'notes' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        } else {
            $deal = CrmDeal::create([
                'customer_id' => $request->customer_id,
                'amount' => $request->amount,
                'notes' => $request->notes,
                'status' => 'new',
            ]);

            $response['error'] = false;
            $response['message'] = "Deal created successfully";
            $response['data'] = $deal;
        }
        return response()->json($response);
    }
    //* END :: create_deal *//

    //* START :: update_deal *//
    public function update_deal(Request $request, $id)
    {
        $deal = CrmDeal::find($id);

        if ($deal) {
            $deal->update([
                'amount' => $request->amount ?? $deal->amount,
                'notes' => $request->notes ?? $deal->notes,
                'status' => $request->status ?? $deal->status,
            ]);

            $response['error'] = false;
            $response['message'] = "Deal updated successfully";
            $response['data'] = $deal;
        } else {
            $response['error'] = true;
            $response['message'] = "Deal not found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: update_deal *//

    //* START :: delete_deal *//
    public function delete_deal($id)
    {
        $deal = CrmDeal::find($id);

        if ($deal) {
            $deal->delete();
            $response['error'] = false;
            $response['message'] = "Deal deleted successfully";
        } else {
            $response['error'] = true;
            $response['message'] = "Deal not found!";
        }
        return response()->json($response);
    }
    //* END :: delete_deal *//

    //* START :: update_deal_status *//
    public function update_deal_status(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:new,prospecting,negotiating,deposit_paid,pending_notary,win,lost',
        ]);

        if ($validator->fails()) {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        } else {
            $deal = CrmDeal::find($id);

            if ($deal) {
                $deal->update(['status' => $request->status]);

                $response['error'] = false;
                $response['message'] = "Deal status updated successfully";
                $response['data'] = $deal;
            } else {
                $response['error'] = true;
                $response['message'] = "Deal not found!";
                $response['data'] = [];
            }
        }
        return response()->json($response);
    }
    //* END :: update_deal_status *//
    //HuyTBQ: crm_deal_assign
    public function assign_deal(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        } else {
            $assignment = CrmDealAssigned::create([
                'deal_id' => $id,
                'user_id' => $request->user_id,
                'note' => $request->note ?? '',
            ]);

            $response['error'] = false;
            $response['message'] = "Deal assigned successfully";
            $response['data'] = $assignment;
        }
        return response()->json($response);
    }
    //* END :: assign_deal *//

    //* START :: get_assigned_deals *//
    public function get_assigned_deals($id)
    {
        $assignedDeals = CrmDealAssigned::where('deal_id', $id)->with('user')->get();

        if ($assignedDeals->isNotEmpty()) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $assignedDeals;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: get_assigned_deals *//

    //* START :: remove_assigned_deal *//
    public function remove_assigned_deal($id, $assigned_id)
    {
        $assignment = CrmDealAssigned::where('deal_id', $id)->where('id', $assigned_id)->first();

        if ($assignment) {
            $assignment->delete();
            $response['error'] = false;
            $response['message'] = "Assigned deal removed successfully";
        } else {
            $response['error'] = true;
            $response['message'] = "Assigned deal not found!";
        }
        return response()->json($response);
    }
    //* END :: remove_assigned_deal *//
    //HuyTBQ: crm_deal_products
    //* START :: add_deal_product *//
    public function add_deal_product(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required|exists:propertys,id',
            'status' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        } else {
            $product = CrmDealProduct::create([
                'deal_id' => $id,
                'property_id' => $request->property_id,
                'status' => $request->status ?? 'Sent',
                'note' => $request->note ?? '',
            ]);

            $response['error'] = false;
            $response['message'] = "Product added to deal successfully";
            $response['data'] = $product;
        }
        return response()->json($response);
    }
    //* END :: add_deal_product *//

    //* START :: get_deal_products *//
    public function get_deal_products($id)
    {
        $products = CrmDealProduct::where('deal_id', $id)->with('property')->get();

        if ($products->isNotEmpty()) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $products;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: get_deal_products *//

    //* START :: update_deal_product *//
    public function update_deal_product(Request $request, $id, $property_id)
    {
        $product = CrmDealProduct::where('deal_id', $id)->where('id', $property_id)->first();

        if ($product) {
            $product->update([
                'status' => $request->status ?? $product->status,
                'note' => $request->note ?? $product->note,
            ]);

            $response['error'] = false;
            $response['message'] = "Product updated successfully";
            $response['data'] = $product;
        } else {
            $response['error'] = true;
            $response['message'] = "Product not found!";
        }
        return response()->json($response);
    }
    //* END :: update_deal_product *//

    //* START :: delete_deal_product *//
    public function delete_deal_product($id, $property_id)
    {
        $product = CrmDealProduct::where('deal_id', $id)->where('id', $property_id)->first();

        if ($product) {
            $product->delete();
            $response['error'] = false;
            $response['message'] = "Product removed from deal successfully";
        } else {
            $response['error'] = true;
            $response['message'] = "Product not found!";
        }
        return response()->json($response);
    }
    //* END :: delete_deal_product *//
    //HuyTBQ: crm_deal_commissions
    //* START :: add_deal_commission *//
    public function add_deal_commission(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sale_commission' => 'required|numeric',
            'sale_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        } else {
            $commission = CrmDealCommission::create([
                'deal_id' => $id,
                'sale_id' => $request->sale_id,
                'sale_commission' => $request->sale_commission,
                'notes' => $request->notes ?? '',
            ]);

            $response['error'] = false;
            $response['message'] = "Commission added successfully";
            $response['data'] = $commission;
        }
        return response()->json($response);
    }
    //* END :: add_deal_commission *//

    //* START :: get_deal_commission *//
    public function get_deal_commission($id)
    {
        $commissions = CrmDealCommission::where('deal_id', $id)->get();

        if ($commissions->isNotEmpty()) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $commissions;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: get_deal_commission *//

    //* START :: update_deal_commission *//
    public function update_deal_commission(Request $request, $id, $commission_id)
    {
        $commission = CrmDealCommission::where('deal_id', $id)->where('id', $commission_id)->first();

        if ($commission) {
            $commission->update([
                'sale_commission' => $request->sale_commission ?? $commission->sale_commission,
                'notes' => $request->notes ?? $commission->notes,
            ]);

            $response['error'] = false;
            $response['message'] = "Commission updated successfully";
            $response['data'] = $commission;
        } else {
            $response['error'] = true;
            $response['message'] = "Commission not found!";
        }
        return response()->json($response);
    }
    //* END :: update_deal_commission *//

    //* START :: delete_deal_commission *//
    public function delete_deal_commission($id, $commission_id)
    {
        $commission = CrmDealCommission::where('deal_id', $id)->where('id', $commission_id)->first();

        if ($commission) {
            $commission->delete();
            $response['error'] = false;
            $response['message'] = "Commission removed successfully";
        } else {
            $response['error'] = true;
            $response['message'] = "Commission not found!";
        }
        return response()->json($response);
    }
    //* END :: delete_deal_commission *//
    // HuyTBQ: crm_report
    //* START :: get_leads_report *//
    public function get_leads_report()
    {
        $report = CrmLead::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $response['error'] = false;
        $response['message'] = "Data Fetch Successfully";
        $response['data'] = $report;

        return response()->json($response);
    }
    //* END :: get_leads_report *//

    //* START :: get_deals_report *//
    public function get_deals_report()
    {
        $report = CrmDeal::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $response['error'] = false;
        $response['message'] = "Data Fetch Successfully";
        $response['data'] = $report;

        return response()->json($response);
    }
    //* END :: get_deals_report *//

    //* START :: get_customers_statistics *//
    public function get_customers_statistics()
    {
        $totalCustomers = CrmCustomer::count();

        $response['error'] = false;
        $response['message'] = "Data Fetch Successfully";
        $response['data'] = ['total_customers' => $totalCustomers];

        return response()->json($response);
    }
    //* END :: get_customers_statistics *//

    //HuyTBQ: Telegram WebApp Login
    public function loginViaMiniApp(Request $request)
    {
        // 1. Nhận initData từ Frontend gửi lên
        $initData = $request->input('initData');
        
        if (!$initData) {
            return response()->json(['error' => true, 'message' => 'Không tìm thấy initData'], 400);
        }

        // 2. Phân tách chuỗi dữ liệu thành mảng
        parse_str($initData, $data);

        // Kiểm tra chữ ký hash
        if (!isset($data['hash'])) {
            return response()->json(['error' => true, 'message' => 'Dữ liệu không hợp lệ (thiếu hash)'], 401);
        }

        $receivedHash = $data['hash'];
        unset($data['hash']); // QUAN TRỌNG: Phải bỏ hash ra trước khi sắp xếp

        // 3. Sắp xếp dữ liệu theo key (a-z) theo quy chuẩn Telegram
        ksort($data);

        // 4. Tạo chuỗi đối chiếu (Data Check String)
        $dataCheckArr = [];
        foreach ($data as $key => $value) {
            $dataCheckArr[] = $key . '=' . $value;
        }
        $dataCheckString = implode("\n", $dataCheckArr);

        // 5. Tạo Secret Key từ Bot Token
        // Theo tài liệu: HMAC-SHA256 của Bot Token với key là "WebAppData"
        $botToken = env('TELEGRAM_BOT_TOKEN');
        if (!$botToken) {
            return response()->json(['error' => true, 'message' => 'Server chưa cấu hình Bot Token'], 500);
        }
        
        $secretKey = hash_hmac('sha256', $botToken, "WebAppData", true);

        // 6. Tính toán Hash
        $calculatedHash = bin2hex(hash_hmac('sha256', $dataCheckString, $secretKey, true));

        // 7. SO SÁNH CHỮ KÝ
        if (strcmp($calculatedHash, $receivedHash) !== 0) {
            return response()->json(['error' => true, 'message' => 'Xác thực thất bại! Chữ ký không khớp.'], 403);
        }

        // --- ĐẾN ĐÂY LÀ HÀNG THẬT 100% ---

        // 8. Kiểm tra thời hạn (Optional: ví dụ 24h)
        if (isset($data['auth_date']) && (time() - $data['auth_date'] > 86400)) {
             return response()->json(['error' => true, 'message' => 'Phiên đăng nhập đã hết hạn, vui lòng tải lại trang'], 401);
        }

        // 9. Lấy thông tin User
        // initData chứa field 'user' dạng JSON String -> Cần decode
        $telegramUserData = json_decode($data['user'], true);
        $telegramId = $telegramUserData['id'];

        // 10. Tìm Customer trong Database
        $customer = Customer::where('telegram_id', $telegramId)->first();

        if ($customer) {
            // Log in the user to the session for Blade views
            Auth::guard('webapp')->login($customer, true);

            // Logic tái sử dụng hoặc tạo mới JWT
            $token = $this->handleJwtToken($customer);
            
            if (!$token) {
                return response()->json(['error' => true, 'message' => 'Lỗi tạo Token'], 500);
            }

            return response()->json([
                'status' => 'authenticated',
                'message' => 'Đăng nhập thành công qua WebApp',
                'user' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->mobile,
                    'profile' => $customer->profile,
                ],
                'access_token' => $token,
            ]);
        } else {
            // Chưa có user -> Trả về Guest để Frontend điều hướng
            return response()->json([
                'status' => 'guest',
                'message' => 'User chưa đăng ký hệ thống',
                'telegram_user' => $telegramUserData // Trả về để frontend có thể hiển thị tên
            ]);
        }
    }

    // Hàm phụ trợ xử lý JWT (Tách ra cho gọn)
    private function handleJwtToken($customer)
    {
        if (isset($customer->isActive) && $customer->isActive == 0) {
            return null; // Tài khoản bị khóa
        }

        $token = $customer->api_token;
        $needsNewToken = true;

        if (!empty($token)) {
            try {
                JWTAuth::setToken($token);
                if (JWTAuth::check()) {
                    $needsNewToken = false;
                }
            } catch (\Exception $e) {
                $needsNewToken = true;
            }
        }

        if ($needsNewToken) {
            try {
                $token = JWTAuth::fromUser($customer);
                $customer->api_token = $token;
                $customer->save();
            } catch (JWTException $e) {
                return null;
            }
        }
        return $token;
    }
}
