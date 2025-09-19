<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyIntegrityRequest extends FormRequest
{
    public function rules()
    {
        return [
            'integrity_token' => 'required|string',
            'package_name' => 'required|string',
        ];
    }
}
