<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MerchantSignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'business_name' => 'required|string',
            'email' => 'required|email|unique:merchants,email',
            'phone_number' => 'required|string',
            'password' => 'required|string',
            'referred_by' => 'nullable|string',
            't_and_c' => 'required|boolean',
        ];
    }
}
