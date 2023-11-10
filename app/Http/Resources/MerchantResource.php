<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'business_name' => $this->business_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            // Add other fields as needed
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
