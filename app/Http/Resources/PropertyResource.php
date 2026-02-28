<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => $this->price,
            'formatted_price' => $this->formatted_prices,
            'category' => $this->whenLoaded('category'),
            'description' => $this->description,
            'address' => $this->address,
            'client_address' => $this->client_address,
            'property_type' => $this->property_type == 0 ? 'Sell' : ($this->property_type == 1 ? 'Rent' : ($this->property_type == 2 ? 'Sold' : 'Rented')),
            'title_image' => $this->title_image,
            'threeD_image' => $this->threeD_image,
            'post_created' => $this->created_at ? $this->created_at->diffForHumans() : null,
            'gallery' => $this->gallery,
            'total_view' => $this->total_click,
            'status' => $this->status,
            'state' => $this->state,
            'city' => $this->city,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'added_by' => $this->added_by,
            'user' => $this->whenLoaded('user'),
            'parameters' => $this->whenLoaded('parameters', function () {
                return $this->parameters->map(function ($parameter) {
                    return [
                        'id' => $parameter->id,
                        'name' => $parameter->name,
                        'value' => $parameter->pivot->value,
                        'image' => $parameter->image,
                    ];
                });
            }),
            // Handle assignParameter if parameters relationship is not used or to supplement
            'assigned_parameters' => $this->whenLoaded('assignParameter', function () {
                return $this->assignParameter->map(function ($assign) {
                    return [
                        'parameter_id' => $assign->parameter_id,
                        'parameter' => $assign->parameter,
                        'value' => $assign->value,
                    ];
                });
            }),
             'propery_image' => $this->whenLoaded('propery_image'),
             'promoted' => $this->when($this->promoted, true, false),
        ];
    }
}
