<?php

namespace App\Http\Requests;

use App\Rules\CountryCode;
use App\Rules\Timezone;
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
                'bail',
                'nullable',
                'string',
                'size:2',
                new CountryCode()
            ],
            'timezone' => [
                'nullable',
                'string',
                new Timezone()
            ],
            'phone_numbers' => [
                'bail',
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

    protected function prepareForValidation()
    {
        if ($this->has('country_code')) {
            $this->merge([
                'country_code' => mb_strtolower($this->input('country_code')),
            ]);
        }

        if ($this->has('phone_numbers')) {
            $phoneNumbers = $this->input('phone_numbers');
            foreach ($phoneNumbers as $key => $phoneNumber) {
                $phoneNumbers[$key] = preg_replace('/\s+/', '', $phoneNumber);
            }

            $this->merge([
                'phone_numbers' => $phoneNumbers,
            ]);
        }
    }
}
