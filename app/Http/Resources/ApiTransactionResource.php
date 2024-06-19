<?php

namespace App\Http\Resources;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiTransactionResource extends JsonResource
{

    /**
     * @var Payment
     */
    public $resource;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid'=>$this->resource->uuid,
            'created_at'=>Carbon::parse($this->resource->created_at)->format('d.m.Y H:i:s'),
            'amount'=>$this->resource->amount,
            'external_id'=>$this->resource->external_id,
            'destination_card'=>$this->resource->dest_card_number,
            'customer_email'=>$this->resource->email,
            'customer_card_number'=>$this->resource->customer_card_number,
            'status'=>$this->resource->status?->name_ru,
            'created_at_for_dispute'=>Carbon::parse($this->resource->created_at)->format('Y-m-d H:m:s'),
        ];
    }
}
