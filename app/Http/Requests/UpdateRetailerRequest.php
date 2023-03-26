<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRetailerRequest extends FormRequest
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
            'name' => 'string',
            'url' => 'url',
            'affiliate_network' => 'string',
            'short_text' => 'string',
            'long_text' => 'string',
            'link_status' => 'boolean',
            'last_verified' => 'date_format:Y-m-d H:i:s',
            'featured' => 'boolean',
            'benificiary_id' => 'benificiaries,id',
        ];
    }
}
