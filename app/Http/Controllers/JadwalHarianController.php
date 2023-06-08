<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal_harian;
use App\Models\Kelas;
use App\Models\Instruktur;
use Illuminate\Support\Facades\Validator;

class JadwalHarianController extends Controller
{
    public function index()
    {
        $jadwalHarian = Jadwal_harian::with('kelas', 'instruktur')->get();
        $kelas = Kelas::latest()->get();
        $instruktur = Instruktur::latest()->get();

        if (count($jadwalHarian) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalHarian
            ], 200);
        } else {
            return response([
                'message' => 'Empty',
                'data' => null
            ], 400);
        }
    }

    public function show($id)
    {
        $jadwalHarian = Jadwal_harian::find($id);
        $kelas = Kelas::all();
        $instruktur = Instruktur::all();

        if (!is_null($jadwalHarian)) {
            return response([
                'message' => 'Retrieve Jadwal Harian Success',
                'data' => $jadwalHarian
            ], 200);
        }

        return response([
            'message' => 'Jadwal Harian Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {

        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_instruktur' => 'required',
            'id_kelas' => 'required',
            'hari_kelas_harian' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kuota' => 'required',
            'keterangan' => 'required',
            'tgl_kelas' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $jadwalHarian = Jadwal_harian::create($storeData);
        $jadwalHarian = Jadwal_harian::latest()->first();

        return response([
            'message' => 'Add Jadwal Harian Success',
            'data' => $jadwalHarian
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $jadwalHarian = Jadwal_harian::find($id);

        if (is_null($jadwalHarian)) {
            return response([
                'message' => 'Jadwal Harian Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'keterangan' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);


        $jadwalHarian->update([
            'keterangan' => $updateData['keterangan']
        ]);

        if ($jadwalHarian->save()) {
            return response([
                'message' => 'Update Jadwal Harian Success',
                'data' => $jadwalHarian
            ], 200);
        }


        return response([
            'message' => 'Update Jadwal Harian Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $jadwalHarian = Jadwal_harian::find($id);

        if (is_null($jadwalHarian)) {
            return response([
                'message' => 'Jadwal Harian Not Found',
                'deleted data' => null
            ], 404);
        }

        if ($jadwalHarian->delete()) {
            return response([
                'message' => 'Delete Jadwal Harian Success',
                'deleted data' => $jadwalHarian
            ], 200);
        }

        return response([
            'message' => 'Delete Jadwal_harian Failed',
            'deleted data' => null
        ], 400);
    }
}
