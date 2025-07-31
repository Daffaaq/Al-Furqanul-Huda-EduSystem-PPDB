<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateKontakRequest;
use App\Models\JamOperasional;
use App\Models\Kontak;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KontakController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:contact.index')->only('index');
        $this->middleware('permission:contact.create')->only('create', 'store');
        $this->middleware('permission:contact.edit')->only('edit', 'update');
        $this->middleware('permission:contact.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kontak = DB::table('kontaks')->select('id', 'alamat', 'telepon', 'email', 'latitude', 'longitude', 'facebook', 'instagram', 'youtube', 'tiktok', 'whatsapp')->first();
        $jamOperasionals = DB::table('jam_operasionals')->select('id', 'hari', 'buka', 'tutup', 'tutup_full')->get();
        return view('kontak.index', compact('kontak', 'jamOperasionals'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kontak $kontak)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKontakRequest $request, $id)
    {
        // dd($request->all());
        $kontak = Kontak::find($id);
        $kontak->update($request->validated());

        return redirect()->route('contact.index')
            ->with('success', 'Kontak berhasil diperbarui!');
    }

    public function updateJamOperasional(Request $request)
    {
        Log::info('updateJamOperasional masuk');

        // Normalisasi "" menjadi null
        $jamsInput = $request->input('jams', []);
        foreach ($jamsInput as $i => $jam) {
            if (isset($jam['buka']) && $jam['buka'] === '') {
                $jamsInput[$i]['buka'] = null;
            }
            if (isset($jam['tutup']) && $jam['tutup'] === '') {
                $jamsInput[$i]['tutup'] = null;
            }
        }
        $request->merge(['jams' => $jamsInput]);

        // Aturan dasar
        $rules = [
            'jams.*.id' => 'required|exists:jam_operasionals,id',
            'jams.*.tutup_full' => 'sometimes|nullable|in:0,1',
        ];

        // Validasi jam buka/tutup dinamis
        foreach ($jamsInput as $key => $jam) {
            $tutupFullChecked = !empty($jam['tutup_full']) && $jam['tutup_full'] == '1';

            if ($tutupFullChecked) {
                $rules["jams.$key.buka"] = 'nullable|date_format:H:i';
                $rules["jams.$key.tutup"] = 'nullable|date_format:H:i';
            } else {
                $rules["jams.$key.buka"] = 'required|date_format:H:i';
                $rules["jams.$key.tutup"] = 'required|date_format:H:i';
            }
        }

        $data = $request->validate($rules);

        foreach ($data['jams'] as $jamData) {
            $jam = JamOperasional::find($jamData['id']);
            $jam->buka = $jamData['buka'] ?? null;
            $jam->tutup = $jamData['tutup'] ?? null;
            $jam->tutup_full = !empty($jamData['tutup_full']);
            $jam->save();
        }

        return redirect()->route('contact.index')->with('success', 'Jam operasional berhasil diperbarui!');
    }
}
