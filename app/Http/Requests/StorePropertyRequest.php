<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'category' => 'required|integer',
            'property_type' => 'required|integer',
            'price' => 'required|numeric',
            'gallery_images.*' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'title_image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ];
    }
}
