<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules():array
    {
        return [
            'name' => 'required|string|min:3|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required|string',
            'roles'=>'required|array'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Необходимо заполнить',
            'email.required' => 'Необходимо заполнить',
            'email.email' => 'Должно быть валидным email',
            'password.required' => 'Необходимо заполнить',
            'password.min' => 'Минимум 8 символов',
            'password_confirmation.required' => 'Необходимо заполнить',
            'roles.required' => 'Необходимо заполнить',
        ];
    }
}
