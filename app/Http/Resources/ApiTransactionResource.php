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
            'created_at'=>Carbon::parse($this->resource->created_at)->format('d.m.Y H:m:s'),
            'amount'=>$this->resource->amount,
            'destination_card'=>$this->resource->dest_card_number,
            'customer_email'=>$this->resource->email,
            'customer_name'=>$this->resource->customer_name,
            'customer_card_number'=>$this->resource->customer_card_number,
            'ip_address'=>$this->resource->ip_address,
            'status'=>$this->resource->status?->name_ru
        ];
    }
}
