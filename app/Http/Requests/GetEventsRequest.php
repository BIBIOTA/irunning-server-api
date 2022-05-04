<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enum\EventDistanceEnum;

class GetEventsRequest extends FormRequest
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
            'startDay' => 'nullable|required_with:endDay|before:endDay|date_format:Y-m-d',
            'endDay' => 'nullable|required_with:startDay|after:startDay|date_format:Y-m-d',
            'distances' => 'nullable|array',
            'distances.*' => 'integer|in:' . implode(',', EventDistanceEnum::getTypes()),
            'keywords' => 'nullable|string',
        ];
    }
}
