<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BalancesCardResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'balance' => number_format(round($this->balance),2,'.',' '),
            'credit_balance' => number_format(round($this->credit),2,'.',' '),
            'transactions_last_day' => $this->transactions_last_day ?? 0,
            'successful_transactions_last_day' => $this->successful_transactions_last_day ?? 0,
            'withdrawals_last_day' => $this->withdrawals_last_day ?? 0,
            'successful_withdrawals_last_day' => $this->successful_withdrawals_last_day ?? 0,
        ];
    }
}
