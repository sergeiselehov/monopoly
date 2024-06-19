<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiSupportRequestList extends JsonResource
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
            'title'=>$this->resource->theme,
            'description'=>$this->resource->description,
            'created_date'=>Carbon::parse($this->resource->created_at)->format('d.m.Y H:i:s'),
        ];
    }
}
