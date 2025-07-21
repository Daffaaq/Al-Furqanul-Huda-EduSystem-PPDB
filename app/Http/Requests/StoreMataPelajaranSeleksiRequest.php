<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMataPelajaranSeleksiRequest extends FormRequest
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
            'nama_mata_pelajaran_seleksi' => 'required|array|min:1', // Allow an array of values
            'nama_mata_pelajaran_seleksi.*' => 'string|max:255', // Validate each item in the array
            'periode_id' => 'required|exists:periodes,id|array|min:1', // Ensure `periode_id` is also an array
            'periode_id.*' => 'exists:periodes,id', // Validate each period ID
        ];
    }
}
