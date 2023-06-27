<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Instruktur;
use Illuminate\Http\Request;
use App\Models\Izin_instruktur;
use App\Models\Jadwal_harian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class IzinInstrukturController extends Controller
{
    public function index()
    {
        $izin_instruktur = Izin_instruktur::with('instruktur')->get();
        $instruktur = Instruktur::latest()->get();
        $jadwal_harian = Jadwal_harian::latest()->get();
        
        if(count($izin_instruktur) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $izin_instruktur
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_izin)
    {
        $izin_instruktur = Izin_instruktur::find($id_izin);
        $instruktur = Instruktur::all();
        $jadwal_harian = Jadwal_harian::all();

        if(!is_null($izin_instruktur)){
            return response([
                'message' => 'Retrieve Izin_instruktur Success',
                'data' => $izin_instruktur
            ], 200);
        }

        return response([
            'message' => 'Izin_instruktur Not Found',
            'data' => null
        ], 404);

    }

    public function store(Request $request)
    {

        $storeData = $request->all();
        $jadwal_harian = Jadwal_harian::find($storeData['id_jadwal_harian']);

        $storeData['status_konfirmasi'] = 0;
        $storeData['tgl_izin'] = $jadwal_harian['tgl_kelas'];

        $validate = Validator::make($storeData, [
            'id_jadwal_harian' => 'required',
            'id_instruktur' => 'required',
            'id_instruktur_pengganti' => 'required',
            'status_konfirmasi' => 'required',
            'tgl_izin' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $izin_instruktur = Izin_instruktur::create($storeData);
        $izin_instruktur = Izin_instruktur::latest()->first();

        return response([
            'message' => 'Add Izin_instruktur Success',
            'data' => $izin_instruktur
        ], 200);
    }

    public function confirmIzin(Request $request, $id)
    {
        $izin_instruktur = Izin_instruktur::find($id);
        $jadwal_harian = Jadwal_harian::find($izin_instruktur['id_jadwal_harian']);
        $instruktur = Instruktur::find($izin_instruktur['id_instruktur']);

        $jadwal_harian->update([
            'keterangan' => 'MENGGANTIKAN '. $instruktur['id_instruktur'].' - '.$instruktur['nama'],
            'id_instruktur' => $izin_instruktur['id_instruktur_pengganti']
        ]);      
        
        if(is_null($izin_instruktur)){
            return response([
                'message' => 'Izin_instruktur Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'status_konfirmasi' => 'required',
        ]);

        if($validate->fails())
            return response()->json($validate->errors(), 400);

        $izin_instruktur->update([
            'status_konfirmasi' => 1
        ]);

        $jadwal_harian->save();

        if($izin_instruktur->save()){
            return response([
                'message' => 'Izin Instruktur Terkonfirmasi',
                'data' => $izin_instruktur
            ], 200);
        }

        return response([
            'message' => 'Update Izin_instruktur Failed',
            'data' => null
        ], 400);
    }

    public function getIzinByIdInstruktur($id_instruktur)
    {
        $izin_instruktur = Izin_instruktur::where('id_instruktur','=', $id_instruktur)->with('instruktur')->get();

        //kalo izin instruktur perlu di history, kasi where status_konfirmasi
        if(count($izin_instruktur) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $izin_instruktur,
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }
}
