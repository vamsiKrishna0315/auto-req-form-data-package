<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Job_batchesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'total_jobs' => 'integer',
            'pending_jobs' => 'integer',
            'failed_jobs' => 'integer',
            'failed_job_ids' => 'string',
            'options' => 'nullable',
            'cancelled_at' => 'integer',
            'created_at' => 'integer',
            'finished_at' => 'integer'
        ];
    }
}