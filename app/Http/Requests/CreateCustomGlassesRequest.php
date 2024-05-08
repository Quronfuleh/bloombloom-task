<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCustomGlassesRequest extends FormRequest
{
    /**
     * Prepare the data for validation by including the header.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'currency_code' => $this->header('X-Currency')
        ]);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'frame_id' => [
                'required',
                'exists:frames,id',
                Rule::exists('frames', 'id')->where(function ($query) {
                    $query->where('stock', '>', 0);
                }),
            ],
            'lens_id' => [
                'required',
                'exists:lenses,id',
                Rule::exists('lenses', 'id')->where(function ($query) {
                    $query->where('stock', '>', 0);
                }),
            ],
            'currency_code' => [
                'required',
                'in:USD,GBP,EUR,JOD,JPY'
            ]

            
        ];
    }

    public function messages()
    {
        return [
            'frame_id.exists' => 'The selected frame is invalid.',
            'lens_id.exists' => 'The selected lens is invalid.',
            'frame_id.required' => 'A frame must be selected.',
            'lens_id.required' => 'A lens must be selected.',
            'frame_id.*.exists' => 'The selected frame is out of stock.',
            'lens_id.*.exists' => 'The selected lens is out of stock.',
            'currency_code.required' => 'Currency header is required.',
            'currency_code.in' => 'User currency is not supported.'
        ];
    }
}
