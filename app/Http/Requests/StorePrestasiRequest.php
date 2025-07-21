<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrestasiRequest extends FormRequest
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
            'nama_kategori_prestasi' => 'required|string|max:255',
            'periode_id' => 'required|exists:periodes,id',
            'nama_prestasi' => 'required|array|min:1',
            'nama_prestasi.*' => 'required|string|max:255',
            'point_prestasi' => 'required|array|min:1',
            'point_prestasi.*' => 'required|numeric|min:0',
        ];
    }
}
