<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePendaftaranCalonSiswaRequest extends FormRequest
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
            'nama_calon_siswa' => 'required|min:3',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'email_calon_siswa' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ];
    }
}
