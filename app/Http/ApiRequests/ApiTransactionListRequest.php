<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiTransactionListRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules():array
    {
        return [
            'date_from'=>'nullable|date',
            'date_to'=>'nullable|date',
            'uuid'=>'nullable|string',
        ];
    }
}
