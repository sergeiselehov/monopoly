<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiMerchantResource extends JsonResource
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
            'name'=>$this->resource->name,
            'success_url'=>$this->resource->success_url_callback,
            'fail_url'=>$this->resource->error_url_callback,
            'secret_key'=>$this->resource->secret_key,
            'created_date'=>Carbon::parse($this->resource->created_at)->format('d.m.Y H:i:s'),
        ];
    }
}
