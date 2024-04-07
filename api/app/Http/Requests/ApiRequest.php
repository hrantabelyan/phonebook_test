<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;



class ApiRequest extends FormRequest
{
    use ApiResponseTrait;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException($this->respondFailedValidation($validator->errors()));
    }

    protected function fail(array $errors = []) {
        throw new HttpResponseException($this->respondFailedValidation($errors));
    }

    protected function throwNotFoundError() {
        throw new HttpResponseException($this->respondNotFound());
    }

    protected function throwError($errors = []) {
        throw new HttpResponseException($this->respondError($errors));
    }
}
