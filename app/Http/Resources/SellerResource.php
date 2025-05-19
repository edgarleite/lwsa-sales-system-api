<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'sales' => SaleResource::collection($this->whenLoaded('sales')),
            'links' => [
                'self' => route('sellers.show', $this->id),
                'sales' => route('sellers.sales', $this->id),
            ],
        ];
    }
}