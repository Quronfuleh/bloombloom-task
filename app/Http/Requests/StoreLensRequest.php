<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreLensRequest extends FormRequest
{
    protected $requiredCurrencies = ['USD', 'GBP', 'EUR', 'JOD', 'JPY'];
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        
        
        return [
            'colour' => 'required|string|max:255',
            'description' => 'required|string',
            'prescription_type' => 'required|in:fashion,single_vision,varifocal',
            'lens_type' => 'required|in:classic,blue_light,transition',
            'stock' => 'required|integer|min:0',
            'prices' => 'required|array',
            'prices.*.currency_code' => ['required', Rule::in($this->requiredCurrencies)],
            'prices.*.price' => 'required|numeric|min:0'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $pricesInput = $this->input('prices', []);
    
            if (!$pricesInput|| !is_array($pricesInput)) {
                return; 
            }
    
            $providedCurrencies = array_column($pricesInput, 'currency_code');
            
            // Check for missing required currencies
            $missingCurrencies = array_diff($this->requiredCurrencies, $providedCurrencies);
            if (!empty($missingCurrencies)) {
                $validator->errors()->add('prices', 'Prices must be provided for all required currencies: ' . implode(', ', $missingCurrencies));
            }
    
            // Check for duplicate currencies
            if (count($providedCurrencies) !== count(array_unique($providedCurrencies))) {
                $validator->errors()->add('prices', 'Each currency can only have one price.');
            }
        });
    }

    public function messages()
    {
        return [
            'prescription_type.in' => 'prescription_type must be one of the following types: fashion,single_vision,varifocal',
            'lens_type.in' => 'lens_type must be one of the following types: classic,blue_light,transition',
        ];
    }
}