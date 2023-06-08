<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi_depositk;
use App\Models\Pegawai;
use App\Models\Member;
use App\Models\Kelas;
use App\Models\Promo_kelas;
use App\Models\Deposit_kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class TransaksiDepositKController extends Controller
{
    public function index()
    {
        $transaksi_depok = Transaksi_depositk::with('pegawai', 'member', 'kelas', 'promo_kelas')->get();
        $pegawai = Pegawai::latest()->get();
        $member = Member::latest()->get();
        $kelas = Kelas::latest()->get();
        $promo_kelas = Promo_kelas::latest()->get();
        
        if(count($transaksi_depok) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksi_depok
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_transaksi_aktivasi)
    {
        $transaksi_depok = Transaksi_depositk::find($id_transaksi_aktivasi);
        $pegawai = Pegawai::all();
        $member = Member::all();
        $kelas = Kelas::all();
        $promo_kelas = Promo_kelas::all();

        if(!is_null($transaksi_depok)){
            return response([
                'message' => 'Retrieve Transaksi_depositk Success',
                'data' => $transaksi_depok
            ], 200);
        }

        return response([
            'message' => 'Transaksi_depositk Not Found',
            'data' => null
        ], 404);

    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $kelas = Kelas::find($storeData['id_kelas']);
        // $tanggal = Carbon::today();
        // $tanggal->toFormattedDateString()

        $validate = Validator::make($storeData, [
            'id_member' => 'required',
            'id_kelas' => 'required',
            'jumlah_deposit' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $storeData['id_transaksi_depok'] = 0;
        $storeData['tgl_transaksi'] = Carbon::today();
        $storeData['tgl_kadaluarsa'] = Carbon::today();
        $storeData['jumlah_bayar'] = $storeData['jumlah_deposit'] * $kelas['harga'];

        $depositKelas = Deposit_kelas::where('id_member','=', $storeData['id_member'])
                        ->where('id_kelas', "=", $storeData['id_kelas'])->first();

        if((is_null($depositKelas)) || ($depositKelas['deposit_kelas'] == 0)){
            $transaksi_depok = Transaksi_depositk::create($storeData);
            $transaksi_depok = Transaksi_depositk::latest()->first();
    
            return response([
                'message' => 'Add Transaksi_depositk Success',
                'data' => $transaksi_depok,
                'kelas' => $kelas['harga_kelas']
            ], 200);
        }else{
            $depositNotZero = [
                'depositNotZero' => 'Masih ada deposit kelas untuk kelas yang dipilih',
            ];
            // return $depositNotZero;
            return response($depositNotZero, 400);
        }


    }

    public function update(Request $request, $id)
    {
        $transaksi_depok = Transaksi_depositk::find($id);

        if(is_null($transaksi_depok)){
            return response([
                'message' => 'Transaksi_depositk Not Found',
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

        $transaksi_depok->update([
            'nama' => $updateData['nama'],
            'no_telp' => $updateData['no_telp'],
            'tgl_lahir' => $updateData['tgl_lahir'],
            'email' => $updateData['email'],
            'password' => $updateData['password']
        ]);

        if($transaksi_depok->save()){
            return response([
                'message' => 'Update Transaksi_depositk Success',
                'data' => $transaksi_depok
            ], 200);
        }

        return response([
            'message' => 'Update Transaksi_depositk Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $transaksi_depok = Transaksi_depositk::find($id);

        if(is_null($transaksi_depok)){
            return response([
                'message' => 'Transaksi_depositk Not Found',
                'deleted data' => null
            ], 404);
        }

        if($transaksi_depok->delete()){
            return response([
                'message' => 'Delete Transaksi_depositk Success',
                'deleted data' => $transaksi_depok
            ], 200);
        }

        return response([
            'message' => 'Delete Transaksi_depositk Failed',
            'deleted data' => null
        ], 400);
    }
}
