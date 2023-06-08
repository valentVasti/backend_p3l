<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal_umum;
use App\Models\Kelas;
use App\Models\Instruktur;
use Illuminate\Support\Facades\Validator;

class JadwalUmumController extends Controller
{
    public function index()
    {
        $jadwalUmum = Jadwal_umum::with('kelas', 'instruktur')->get();
        $kelas = Kelas::latest()->get();
        $instruktur = Instruktur::latest()->get();

        if (count($jadwalUmum) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwalUmum
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $jadwalUmum = Jadwal_umum::find($id);
        $kelas = Kelas::all();
        $instruktur = Instruktur::all();

        if (!is_null($jadwalUmum)) {
            return response([
                'message' => 'Retrieve Jadwal Umum Success',
                'data' => $jadwalUmum
            ], 200);
        }

        return response([
            'message' => 'Jadwal Umum Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {

        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_instruktur' => 'required',
            'id_kelas' => 'required',
            'hari_kelas_umum' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $checkJadwal = Jadwal_umum::where('hari_kelas_umum', $storeData['hari_kelas_umum'])
                            ->where('jam_mulai','=', $storeData['jam_mulai'])
                            ->where('id_instruktur','=', $storeData['id_instruktur'])
                            ->get();
        
        if(count($checkJadwal) != 0){
            return response(['message' => 'Jadwal Bertabrakan!'], 400);
        }else{
            $jadwalUmum = Jadwal_umum::create($storeData);
            $jadwalUmum = Jadwal_umum::latest()->first();
            
            return response([
                'message' => 'Add Jadwal Umum Success',
                'data' => $jadwalUmum
            ], 200);
        }

    }

    public function update(Request $request, $id)
    {
        $jadwalUmum = Jadwal_umum::find($id);

        if (is_null($jadwalUmum)) {
            return response([
                'message' => 'Jadwal Umum Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'id_instruktur' => 'required',
            'id_kelas' => 'required',
            'hari_kelas_umum' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $checkJadwal = Jadwal_umum::where('hari_kelas_umum', $updateData['hari_kelas_umum'])
            ->where('jam_mulai','=', $updateData['jam_mulai'])
            ->where('id_instruktur','=', $updateData['id_instruktur'])
            ->get();

        if(count($checkJadwal) != 0){
            return response(['message' => 'Jadwal Bertabrakan!'], 400);
        }else{
            $jadwalUmum->update([
                'id_instruktur' => $updateData['id_instruktur'],
                'id_kelas' => $updateData['id_kelas'],
                'hari_kelas_umum' => $updateData['hari_kelas_umum'],
                'jam_mulai' => $updateData['jam_mulai'],
                'jam_selesai' => $updateData['jam_selesai']
            ]);
    
            if ($jadwalUmum->save()) {
                return response([
                    'message' => 'Update Jadwal Umum Success',
                    'data' => $jadwalUmum
                ], 200);
            }
        }    

        return response([
            'message' => 'Update Jadwal Umum Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $jadwalUmum = Jadwal_umum::find($id);

        if (is_null($jadwalUmum)) {
            return response([
                'message' => 'Jadwal Umum Not Found',
                'deleted data' => null
            ], 404);
        }

        if ($jadwalUmum->delete()) {
            return response([
                'message' => 'Delete Jadwal Umum Success',
                'deleted data' => $jadwalUmum
            ], 200);
        }

        return response([
            'message' => 'Delete Jadwal_umum Failed',
            'deleted data' => null
        ], 400);
    }
}
