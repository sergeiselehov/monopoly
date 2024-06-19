<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Dispute */
class DisputeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'user_id' => $this->user_id,
            'source_card_number' => $this->source_card_number,
            'destination_card_number' => $this->destination_card_number,
            'bill_time' => $this->bill_time,
            'bill_amount' => $this->bill_amount,
            'customer_email' => $this->customer_email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'dispute_image_count' => $this->dispute_image_count,
            'payment_uuid' => $this->payment?->uuid,
            'status' => $this->status?->name,
        ];
    }
}
