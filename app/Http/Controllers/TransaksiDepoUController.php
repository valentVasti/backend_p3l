<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi_depou;
use App\Models\Pegawai;
use App\Models\Member;
use App\Models\Promo_uang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class TransaksiDepoUController extends Controller
{
    public function index()
    {
        $transaksi_depou = Transaksi_depou::with('pegawai', 'member', 'promo_uang')->get();
        $pegawai = Pegawai::latest()->get();
        $member = Member::latest()->get();
        $promo_uang = Promo_uang::latest()->get();
        
        if(count($transaksi_depou) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksi_depou
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_transaksi_aktivasi)
    {
        $transaksi_depou = Transaksi_depou::find($id_transaksi_aktivasi);
        $pegawai = Pegawai::all();
        $member = Member::all();
        $promo_uang = Promo_uang::all();

        if(!is_null($transaksi_depou)){
            return response([
                'message' => 'Retrieve Transaksi_depou Success',
                'data' => $transaksi_depou
            ], 200);
        }

        return response([
            'message' => 'Transaksi_depou Not Found',
            'data' => null
        ], 404);

    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $storeData['id_transaksi'] = 0;
        $storeData['tgl_transaksi'] = Carbon::today();

        // $tanggal = Carbon::today();
        // $tanggal->toFormattedDateString()

        $validate = Validator::make($storeData, [
            'id_member' => 'required',
            'jumlah_depou' => 'required|gte:500000',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }
        
        $transaksi_depou = Transaksi_depou::create($storeData);
        $transaksi_depou = Transaksi_depou::latest()->first();

        return response([
            'message' => 'Add Transaksi_depou Success',
            'data' => $transaksi_depou
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $transaksi_depou = Transaksi_depou::find($id);

        if(is_null($transaksi_depou)){
            return response([
                'message' => 'Transaksi_depou Not Found',
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

        $transaksi_depou->update([
            'nama' => $updateData['nama'],
            'no_telp' => $updateData['no_telp'],
            'tgl_lahir' => $updateData['tgl_lahir'],
            'email' => $updateData['email'],
            'password' => $updateData['password']
        ]);

        if($transaksi_depou->save()){
            return response([
                'message' => 'Update Transaksi_depou Success',
                'data' => $transaksi_depou
            ], 200);
        }

        return response([
            'message' => 'Update Transaksi_depou Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $transaksi_depou = Transaksi_depou::find($id);

        if(is_null($transaksi_depou)){
            return response([
                'message' => 'Transaksi_depou Not Found',
                'deleted data' => null
            ], 404);
        }

        if($transaksi_depou->delete()){
            return response([
                'message' => 'Delete Transaksi_depou Success',
                'deleted data' => $transaksi_depou
            ], 200);
        }

        return response([
            'message' => 'Delete Transaksi_depou Failed',
            'deleted data' => null
        ], 400);
    }
}
