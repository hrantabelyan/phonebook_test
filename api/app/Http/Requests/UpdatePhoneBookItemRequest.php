<?php

namespace App\Http\Requests;

use App\Rules\CountryCode;
use App\Rules\Timezone;
use Illuminate\Validation\Rule;

class UpdatePhoneBookItemRequest extends ApiRequest
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
                'sometimes',
                'string',
                'min:3',
                'max:255',
            ],
            'last_name' => [
                'nullable',
                'sometimes',
                'string',
                'min:3',
                'max:255',
            ],
            'country_code' => [
                'bail',
                'sometimes',
                'nullable',
                'string',
                'size:2',
                new CountryCode()
            ],
            'timezone' => [
                'sometimes',
                'nullable',
                'string',
                new Timezone()
            ],
            'phone_numbers' => [
                'bail',
                'sometimes',
                'array',
                'min:1',
            ],
            'phone_numbers.*' => [
                'phone:INTERNATIONAL',
                Rule::unique('phone_book_item_numbers', 'number')->ignore($this->phone_book_item->id, 'phone_book_item_id')->whereNull('deleted_at'),
            ],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('country_code')) {
            $this->merge([
                'country_code' => mb_strtoupper($this->input('country_code')),
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
