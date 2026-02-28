<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'profile' => $this->profile, // Image URL via accessor
            'fcm_id' => $this->fcm_id,
            'logintype' => $this->logintype,
            'address' => $this->address,
            'firebase_id' => $this->firebase_id,
            'telegram_id' => $this->telegram_id,
            'isActive' => $this->isActive,
            'notification' => $this->notification,
            'subscription' => $this->subscription,
            // 'api_token' => $this->api_token, // Hidden in model, returned separately
            'customertotalpost' => $this->customertotalpost, // Appended attribute
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
