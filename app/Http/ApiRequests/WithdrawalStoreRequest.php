<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawalStoreRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules():array
    {
        return [
            'amount'=>'required|integer',
            'wallet'=>'required|string'
        ];
    }
}
