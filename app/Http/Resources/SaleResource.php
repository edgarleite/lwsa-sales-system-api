<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class SaleResource extends JsonResource
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
            'seller_id' => $this->seller_id,
            'seller' => new SellerResource($this->whenLoaded('seller')),
            'amount' => $this->amount,
            'commission' => $this->commission,
            'sale_date' => $this->sale_date instanceof Carbon 
                ? $this->sale_date->format('Y-m-d') 
                : $this->sale_date,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
