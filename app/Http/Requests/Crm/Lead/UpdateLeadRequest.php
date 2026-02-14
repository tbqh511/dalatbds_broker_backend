<?php

namespace App\Http\Requests\Crm\Lead;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateLeadRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::guard('webapp')->check();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'lead_type' => 'required|in:buy,rent',
            'status' => 'required|string|in:new,contacted,converted,lost',
            'price_min' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
        ];
    }
}
