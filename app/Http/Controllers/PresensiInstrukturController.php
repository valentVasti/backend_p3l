<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Instruktur;
use App\Models\Jadwal_harian;
use App\Models\Jadwal_umum;
use Illuminate\Http\Request;
use App\Models\Presensi_instruktur;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PresensiInstrukturController extends Controller
{
    public function index()
    {
        $presensi_instruktur = Presensi_instruktur::all();

        if (count($presensi_instruktur) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $presensi_instruktur
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $presensi_instruktur = Presensi_instruktur::find($id);

        if (!is_null($presensi_instruktur)) {
            return response([
                'message' => 'Retrieve Presensi_instruktur Success',
                'data' => $presensi_instruktur
            ], 200);
        }

        return response([
            'message' => 'Presensi_instruktur Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {

        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_jadwal_harian' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $jadwal_harian = Jadwal_harian::find($storeData['id_jadwal_harian']);
        $instruktur = Instruktur::find($jadwal_harian['id_instruktur']);

        // $updateJamMulai = Carbon::now()->format('H:i:s');
        // $updateJamMulai = '08:03:10';

        $jamMulaiKelas = Carbon::parse($jadwal_harian['jam_mulai']);
        $updateJamMulai = Carbon::parse('08:03:10');
        $keterlambatan = $updateJamMulai->diff($jamMulaiKelas);

        $keterlambatanInstruktur = Carbon::parse($instruktur['keterlambatan']);

        $hours = $keterlambatan->h;
        $minutes = $keterlambatan->i;
        $second = $keterlambatan->s;

        $totalKeterlambatan = $keterlambatanInstruktur->addHours($hours)->addMinutes($minutes)->addSeconds($second);
        $hasilKeterlambatan = $totalKeterlambatan->toTimeString();

        $storeData['update_jam_mulai'] = $updateJamMulai;
        $storeData['keterlambatan'] = $hours.':'. $minutes.':'.$second;
        $storeData['update_jam_selesai'] = null;
        $storeData['status_kelas'] = "KELAS DIMULAI";

        // $instruktur->update([
        //     'keterlambatan' => $hasilKeterlambatan
        // ]);

        $instruktur->keterlambatan = $hasilKeterlambatan;
        $instruktur->save();

        $presensi_instruktur = Presensi_instruktur::create($storeData);
        $presensi_instruktur = Presensi_instruktur::latest()->first();
        
        return response([
            'message' => 'Add Presensi_instruktur Success',
            'data' => $presensi_instruktur
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $presensi_instruktur = Presensi_instruktur::find($id);

        if (is_null($presensi_instruktur)) {
            return response([
                'message' => 'Presensi_instruktur Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'nama' => 'required',
            'no_telp' => 'required',
            'tgl_lahir' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $presensi_instruktur->update([
            'nama' => $updateData['nama'],
            'no_telp' => $updateData['no_telp'],
            'tgl_lahir' => $updateData['tgl_lahir'],
            'email' => $updateData['email'],
            'password' => $updateData['password']
        ]);

        if ($presensi_instruktur->save()) {
            return response([
                'message' => 'Update Presensi_instruktur Success',
                'data' => $presensi_instruktur
            ], 200);
        }

        return response([
            'message' => 'Update Presensi_instruktur Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $presensi_instruktur = Presensi_instruktur::find($id);

        if (is_null($presensi_instruktur)) {
            return response([
                'message' => 'Presensi_instruktur Not Found',
                'deleted data' => null
            ], 404);
        }

        if ($presensi_instruktur->delete()) {
            return response([
                'message' => 'Delete Presensi_instruktur Success',
                'deleted data' => $presensi_instruktur
            ], 200);
        }

        return response([
            'message' => 'Delete Presensi_instruktur Failed',
            'deleted data' => null
        ], 400);
    }

    public function updateJamSelesai($id_jadwal_harian){
        
        $presensi_instruktur = Presensi_instruktur::where('id_jadwal_harian',"=", $id_jadwal_harian)->
                                where('status_kelas','!=','KELAS DIMULAI')->first();

        $updateJamSelesai = Carbon::now()->format('H:i:s');

        $presensi_instruktur->update([
            'update_jam_selesai' => $updateJamSelesai,
            'status_kelas' => 'KELAS SELESAI'
        ]);

        if ($presensi_instruktur->save()) {
            return response([
                'message' => 'Update Jam Selesai Kelas Success',
                'data' => $presensi_instruktur
            ], 200);
        }

        return response([
            'message' => 'Update Jam Selesai Kelas Failed',
            'data' => null
        ], 400);
    }

    public function checkUpdateJamMulai($id_jadwal_harian){
        $presensi_instruktur = Presensi_instruktur::where('id_jadwal_harian','=', $id_jadwal_harian)
                                ->where('status_kelas','=','KELAS DIMULAI')->get();

        if(count($presensi_instruktur) > 0){
            return response([
                'message' => 'Sudah update jam mulai',
                'status' => '1',
                'data' => $presensi_instruktur,
                'id_jadwal_harian' => $id_jadwal_harian
            ], 200);
        }else{
            return response([
                'message' => 'Kelas sudah selesai',
                'status' => '0',
                'data' => $presensi_instruktur,
                'id_jadwal_harian' => $id_jadwal_harian
            ], 200);
        }
    }
}
