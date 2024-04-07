<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StorePhoneBookItemRequest extends ApiRequest
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
            'first_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'last_name' => [
                'nullable',
                'string',
                'min:3',
                'max:255',
            ],
            'country_code' => [
                'nullable',
                'size:2',
                // country validation
            ],
            'phone_numbers' => [
                'required',
                'array',
                'min:1',
            ],
            'phone_numbers.*' => [
                'phone:INTERNATIONAL',
                Rule::unique('phone_book_item_numbers', 'number'),
            ],
        ];
    }
}
