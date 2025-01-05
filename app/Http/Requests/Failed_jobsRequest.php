<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Failed_jobsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'uuid' => 'string|max:255',
            'connection' => 'string',
            'queue' => 'string',
            'payload' => 'string',
            'exception' => 'string',
            'failed_at' => 'date'
        ];
    }
}