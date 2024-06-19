<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class SupportRequestStore extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules():array
    {
        return [
            'theme' => 'required|string',
            'category' => 'required|integer|exists:support_request_categories,id',
            'description' => 'required|string',
            'email'=>'required|email',
            'telegram'=>'required|string'
        ];
    }
}
