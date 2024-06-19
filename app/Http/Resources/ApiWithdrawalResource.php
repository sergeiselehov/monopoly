<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ApiWithdrawalResource extends JsonResource
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
            'uuid'=>$this->resource->uuid,
            'created_at'=>Carbon::parse($this->resource->created_at)->format('d.m.Y H:m:s'),
            'amount'=>$this->resource->amount,
            'wallet'=>$this->resource->wallet,
            'status'=>$this->resource->statusLink?->name_ru
        ];
    }
}
