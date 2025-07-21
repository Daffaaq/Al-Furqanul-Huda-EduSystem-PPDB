<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJadwalPendaftaranRequest extends FormRequest
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
            'nama_jadwal_pendaftaran' => 'required|string|max:255',
            'tanggal_mulai_jadwal_pendaftaran' => 'required|date', // Tanggal mulai bebas
            'tanggal_selesai_jadwal_pendaftaran' => 'required|date|after_or_equal:tanggal_mulai_jadwal_pendaftaran', // Tanggal selesai harus setelah tanggal mulai
            'tanggal_mulai_verifikasi' => 'required|date|after_or_equal:deadline_biodata_calon_siswa', // Tanggal mulai verifikasi setelah deadline biodata
            'tanggal_selesai_verifikasi' => 'required|date|after_or_equal:tanggal_mulai_verifikasi', // Tanggal selesai verifikasi setelah tanggal mulai verifikasi
            'gelombang_pendaftaran' => 'required|string|max:255',
            'deadline_biodata_calon_siswa' => 'required|date|after_or_equal:tanggal_mulai_jadwal_pendaftaran', // Deadline biodata harus setelah tanggal mulai
            'deadline_upload_pendaftaran' => 'required|date|after_or_equal:deadline_biodata_calon_siswa', // Deadline upload setelah deadline biodata
            'pengumuman_hasil_seleksi' => 'required|date|after_or_equal:tanggal_selesai_jadwal_pendaftaran', // Pengumuman hasil seleksi setelah tanggal selesai pendaftaran
            'kuota_akun' => 'required|numeric',
            'kuota_pendaftaran' => 'required|numeric',
            'kuota_penerimaan' => 'required|numeric',
            'status_jadwal_pendaftaran' => 'required|in:Opened,Ongoing,Closed',
            'periode_id' => 'required|exists:periodes,id',
        ];
    }
}
