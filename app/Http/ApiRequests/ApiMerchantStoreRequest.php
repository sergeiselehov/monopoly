<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class ApiMerchantStoreRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules():array
    {
        return [
            'name'=>['required','string'],
            'base_url'=>['required','string'],
            'success_url'=>['required','string'],
            'error_url'=>['required','string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Необходимо заполнить',
            'base_url.required' => 'Необходимо заполнить',
            'success_url.required' => 'Необходимо заполнить',
            'error_url.required' => 'Необходимо заполнить',
        ];
    }
}
