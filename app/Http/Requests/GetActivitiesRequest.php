<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetActivitiesRequest extends FormRequest
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
            'startDay' => 'nullable|date_format:Y-m-d|before:endDay',
            'endDay' => 'nullable|date_format:Y-m-d|after:startDay',
            'page' => 'required|integer|min:1',
            'rows' => 'nullable|integer|min:1',
        ];
    }
}
