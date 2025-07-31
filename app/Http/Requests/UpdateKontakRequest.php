<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKontakRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'alamat'    => 'required|string|max:2000',
            'telepon'   => 'nullable|string|max:20',
            'whatsapp'  => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:100',
            'latitude'  => 'required|string',
            'longitude' => 'required|string',
            'facebook'  => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube'   => 'nullable|url|max:255',
            'tiktok'    => 'nullable|url|max:255',
        ];
    }
}
