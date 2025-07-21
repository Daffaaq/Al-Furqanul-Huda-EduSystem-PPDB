<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBiodataCalonSiswaRequest extends FormRequest
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
            'name' => 'required|max:255', // Sesuaikan nama dengan input form
            'nama_calon_siswa' => 'required|max:255',
            'email' => 'required|email|unique:users,email', // Tambahkan pengecekan untuk email
            'password' => 'required|min:8',
            'jenis_kelamin_calon_siswa' => 'required|in:Laki-laki,Perempuan',
            'agama_calon_siswa' => 'required|in:Islam,Kristen,Katolik,Hindu,Budha,Konghucu',
            'tanggal_lahir_calon_siswa' => 'required|date',
            'tempat_lahir_calon_siswa' => 'required',
            'alamat_calon_siswa' => 'required',
            'nomor_telepon_calon_siswa' => 'required',
            'foto_formal_calon_siswa' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Pastikan nullable jika tidak wajib
        ];
    }
}
