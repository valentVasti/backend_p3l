<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi_aktivasi;
use App\Models\Pegawai;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class TransaksiAktivasi extends Controller
{
    public function index()
    {
        $transaksi_aktivasi = Transaksi_aktivasi::with('pegawai', 'member')->get();
        $pegawai = Pegawai::latest()->get();
        $member = Member::latest()->get();
        
        if(count($transaksi_aktivasi) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksi_aktivasi
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_transaksi_aktivasi)
    {
        $transaksi_aktivasi = Transaksi_aktivasi::find($id_transaksi_aktivasi);
        $pegawai = Pegawai::all();
        $member = Member::all();

        if(!is_null($transaksi_aktivasi)){
            return response([
                'message' => 'Retrieve Transaksi_aktivasi Success',
                'data' => $transaksi_aktivasi
            ], 200);
        }

        return response([
            'message' => 'Transaksi_aktivasi Not Found',
            'data' => null
        ], 404);

    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $storeData['id_transaksi'] = 0;
        $storeData['tgl_aktivasi'] = Carbon::today();
        $storeData['tgl_kadaluarsa'] = Carbon::today();

        // $tanggal = Carbon::today();
        // $tanggal->toFormattedDateString()

        $validate = Validator::make($storeData, [
            'id_pegawai' => 'required',
            'id_member' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $transaksi_aktivasi = Transaksi_aktivasi::create($storeData);
        $transaksi_aktivasi = Transaksi_aktivasi::latest()->first();

        return response([
            'message' => 'Add Transaksi_aktivasi Success',
            'data' => $transaksi_aktivasi
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $transaksi_aktivasi = Transaksi_aktivasi::find($id);

        if(is_null($transaksi_aktivasi)){
            return response([
                'message' => 'Transaksi_aktivasi Not Found',
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

        if($validate->fails())
            return response()->json($validate->errors(), 400);

        $transaksi_aktivasi->update([
            'nama' => $updateData['nama'],
            'no_telp' => $updateData['no_telp'],
            'tgl_lahir' => $updateData['tgl_lahir'],
            'email' => $updateData['email'],
            'password' => $updateData['password']
        ]);

        if($transaksi_aktivasi->save()){
            return response([
                'message' => 'Update Transaksi_aktivasi Success',
                'data' => $transaksi_aktivasi
            ], 200);
        }

        return response([
            'message' => 'Update Transaksi_aktivasi Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $transaksi_aktivasi = Transaksi_aktivasi::find($id);

        if(is_null($transaksi_aktivasi)){
            return response([
                'message' => 'Transaksi_aktivasi Not Found',
                'deleted data' => null
            ], 404);
        }

        if($transaksi_aktivasi->delete()){
            return response([
                'message' => 'Delete Transaksi_aktivasi Success',
                'deleted data' => $transaksi_aktivasi
            ], 200);
        }

        return response([
            'message' => 'Delete Transaksi_aktivasi Failed',
            'deleted data' => null
        ], 400);
    }

}
