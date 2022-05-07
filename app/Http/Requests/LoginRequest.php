<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'expires_at' => 'required|integer',
            'expires_in' => 'required|integer',
            'refresh_token' => 'required|string',
            'access_token' => 'required|string',
            'athlete' => 'required|array',
            'athlete.id' => 'required|integer',
            'athlete.username' => 'nullable|string',
            'athlete.resource_state' => 'nullable|integer',
            'athlete.firstname' => 'nullable|string',
            'athlete.lastname' => 'nullable|string',
            'athlete.city' => 'nullable|string',
            'athlete.state' => 'nullable|string',
            'athlete.country' => 'nullable|string',
            'athlete.sex' => 'nullable|string',
            'athlete.badge_type_id' => 'nullable|integer',
            'athlete.weight' => 'nullable|numeric',
        ];
    }
}
