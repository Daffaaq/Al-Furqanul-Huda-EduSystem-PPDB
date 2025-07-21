<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeleksiRequest extends FormRequest
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
            'nilai_akademik' => 'required|array',
            'nilai_akademik.*' => 'nullable|numeric|min:0|max:100', // contoh validasi tiap nilai mapel

            'dokumen_nilai_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

            'jumlah_prestasi' => 'nullable|array',
            'jumlah_prestasi.*' => 'nullable|integer|min:0',

            'dokumen_prestasi_pendukung.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}
