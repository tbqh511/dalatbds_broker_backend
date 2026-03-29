<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('customer.index');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!has_permissions('delete', 'customer')) {
            $response['error'] = true;
            $response['message'] = PERMISSION_ERROR_MSG;
            return response()->json($response);
        } else {

            Customer::where('id', $request->id)->update(['isActive' => $request->status]);
            $response['error'] = false;
            return response()->json($response);
        }
    }




    public function updateRole(Request $request, $id)
    {
        if (!has_permissions('delete', 'customer')) {
            return response()->json(['error' => true, 'message' => PERMISSION_ERROR_MSG]);
        }

        $role = $request->input('role');
        if (!in_array($role, Customer::VALID_ROLES)) {
            return response()->json(['error' => true, 'message' => 'Role không hợp lệ']);
        }

        Customer::where('id', $id)->update(['role' => $role]);
        return response()->json(['error' => false]);
    }

    /**
     * PATCH customer/{id}/referrer
     * Chỉ superadmin (type=0) mới được thay đổi người giới thiệu của broker.
     * Body: { referral_code: "ABC123" } để gán, hoặc { referral_code: "" } để xóa.
     */
    public function updateReferrer(Request $request, $id)
    {
        if (intval(Auth::user()->type) !== 0) {
            return response()->json(['error' => true, 'message' => 'Chỉ Admin mới được thay đổi người giới thiệu.'], 403);
        }

        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['error' => true, 'message' => 'Không tìm thấy broker.'], 404);
        }

        $code = strtoupper(trim($request->input('referral_code', '')));

        if ($code === '') {
            // Xóa người giới thiệu
            $customer->referred_by = null;
            $customer->save();
            \Log::info("Admin #{" . Auth::id() . "} cleared referrer for Customer #{$customer->id}");
            return response()->json(['error' => false, 'message' => 'Đã xóa người giới thiệu.', 'referrer_name' => null]);
        }

        $referrer = Customer::where('referral_code', $code)->first();
        if (!$referrer) {
            return response()->json(['error' => true, 'message' => 'Mã giới thiệu không hợp lệ.'], 404);
        }
        if ($referrer->id === $customer->id) {
            return response()->json(['error' => true, 'message' => 'Không thể tự giới thiệu chính mình.'], 422);
        }

        $customer->referred_by = $referrer->id;
        $customer->save();
        \Log::info("Admin #{" . Auth::id() . "} set referrer for Customer #{$customer->id} to #{$referrer->id} (code: {$code})");

        return response()->json(['error' => false, 'message' => 'Đã cập nhật người giới thiệu.', 'referrer_name' => $referrer->name]);
    }

    public function customerList()
    {
        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }

        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }

        if (isset($_GET['order'])) {
            $order = $_GET['order'];
        }



        $sql = Customer::orderBy($sort, $order);


        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql->where('id', 'LIKE', "%$search%")->orwhere('email', 'LIKE', "%$search%")->orwhere('name', 'LIKE', "%$search%")->orwhere('mobile', 'LIKE', "%$search%");
        }


        $total = $sql->count();

        if (isset($_GET['limit'])) {
            $sql->skip($offset)->take($limit);
        }


        $res = $sql->with('referrer:id,name')->get();

        $isAdmin = intval(Auth::user()->type) === 0;

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;


        $operate = '';
        foreach ($res as $row) {
            $tempRow['id'] = $row->id;
            $tempRow['name'] = $row->name;
            $tempRow['email'] = $row->email;
            $tempRow['mobile'] = $row->mobile;
            $tempRow['address'] = $row->address;
            $tempRow['firebase_id'] = $row->firebase_id;
            $tempRow['isActive'] = ($row->isActive == '0') ? '<span class="badge rounded-pill bg-danger">Inactive</span>' : '<span class="badge rounded-pill bg-success">Active</span>';
            $roleLabels = [
                'guest'      => '👤 Guest',
                'broker'     => '🏠 Broker',
                'bds_admin'  => '🏘️ BĐS Admin',
                'sale'       => '💼 Sale',
                'sale_admin' => '📋 Sale Admin',
                'admin'      => '👑 Admin',
            ];
            $effectiveRole = $row->getEffectiveRole();
            $tempRow['role'] = '<select class="form-select form-select-sm role-select" data-id="' . $row->id . '" style="min-width:130px">'
                . collect(Customer::VALID_ROLES)->map(fn ($r) =>
                    '<option value="' . $r . '"' . ($effectiveRole === $r ? ' selected' : '') . '>' . ($roleLabels[$r] ?? $r) . '</option>'
                )->implode('')
                . '</select>';
            $tempRow['profile'] = ($row->profile != '') ? '<a class="image-popup-no-margins" href="' . $row->profile . '" width="55" height="55"><img class="rounded avatar-md shadow img-fluid" alt="dalat-bds" src="' . $row->profile . '" width="55" height="55"></a>' : '';

            $tempRow['fcm_id'] = $row->fcm_id;

            $isActive = $row->isActive == '1' ? 'checked' : '';

            $operate =   '<div class="form-check form-switch" style="justify-content: center;display: flex;">
         <input class="form-check-input switch1" id="' . $row->id . '"  onclick="chk(this);" type="checkbox" role="switch"' . $isActive . '>

            </div>';

            // $tempRow['enble_disable'] = $enable_disable;
            // if ($row->isActive == '0') {
            //     $operate =   '&nbsp;<a id="' . $row->id . '" class="btn icon btn-primary btn-sm rounded-pill" onclick="return active(this.id);" title="Enable"><i class="bi bi-eye-fill"></i></a>';
            // } else {
            //     $operate =   '&nbsp;<a id="' . $row->id . '" class="btn icon btn-danger btn-sm rounded-pill" onclick="return disable(this.id);" title="Disable"><i class="bi bi-eye-slash-fill"></i></a>';
            // }

            $tempRow['customertotalpost'] =  '<a href="' . url('property') . '?customer=' . $row->id . '">' . $row->customertotalpost . '</a>';

            $referrerName = $row->referrer ? htmlspecialchars($row->referrer->name, ENT_QUOTES) : '';
            if ($isAdmin) {
                $tempRow['referred_by'] = '<span class="referrer-name" id="ref-name-' . $row->id . '">'
                    . ($referrerName ?: '<span class="text-muted">—</span>')
                    . '</span>'
                    . ' <button class="btn btn-xs btn-outline-secondary ms-1" onclick="changeReferrer(' . $row->id . ')" title="Thay đổi người giới thiệu" style="padding:1px 6px;font-size:11px">✏️</button>';
            } else {
                $tempRow['referred_by'] = $referrerName ?: '<span class="text-muted">—</span>';
            }

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }
}
