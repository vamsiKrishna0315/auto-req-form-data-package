<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'queue' => 'string|max:255',
            'payload' => 'string',
            'attempts' => 'integer|min:0',
            'reserved_at' => 'integer|min:0',
            'available_at' => 'integer|min:0',
            'created_at' => 'integer|min:0'
        ];
    }
}