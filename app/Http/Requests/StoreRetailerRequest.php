<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StoreRetailerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'url' => 'required|url',
            'affiliate_network' => 'required|string',
            'short_text' => 'required|string',
            'long_text' => 'required|string',
            'link_status' => 'boolean',
            'last_verified' => 'date_format:Y-m-d H:i:s',
            'featured' => 'boolean',
            'benificiary_id' => 'benificiaries,id',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        Log::error('Validation failed: ' . $validator->errors());
        parent::failedValidation($validator);
    }
}
