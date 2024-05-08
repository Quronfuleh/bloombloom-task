<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLensIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Return true if the user is authorized to make this request
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'X-Currency' => 'required|in:USD,GBP,EUR,JOD,JPY'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'X-Currency.in' => 'User currency is not supported.',
            'X-Currency.required' => 'Currency header is required.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'X-Currency' => $this->header('X-Currency')
        ]);
    }
}
