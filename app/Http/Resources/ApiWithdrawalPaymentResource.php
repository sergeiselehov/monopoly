<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\WithdrawalPayment */
class ApiWithdrawalPaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'uuid' => $this->uuid,
            'external_id' => $this->external_id,
            'status' => $this->status?->name_ru,
            'destination_card_number' => $this->destination_card_number,
            'source_card_number' => $this->resource->card?->card_number,
            'amount' => $this->amount,
            'commission' => $this->commission,
            'created_at' => Carbon::parse($this->created_at)->format('d.m.Y H:i:s'),
        ];
    }
}
