<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::all();
        
        if(count($pegawai) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $pegawai = Pegawai::find($id);

        if(!is_null($pegawai)){
            return response([
                'message' => 'Retrieve Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Pegawai Not Found',
            'data' => null
        ], 404);

    }

    public function store(Request $request)
    {

        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_role' => 'required',
            'nama_pegawai' => 'required',
            'no_telp_pegawai' => 'required',
            'tgl_lahir_pegawai' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $pegawai = Pegawai::create($storeData);
        $pegawai = Pegawai::latest()->first();

        return response([
            'message' => 'Add Pegawai Success',
            'data' => $pegawai
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::find($id);

        if(is_null($pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'id_role' => 'required',
            'nama_pegawai' => 'required',
            'no_telp_pegawai' => 'required',
            'tgl_lahir_pegawai' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $pegawai->update([
            'id_role' => $updateData['id_role'],
            'nama_pegawai' => $updateData['nama_pegawai'],
            'no_telp_pegawai' => $updateData['no_telp_pegawai'],
            'tgl_lahir_pegawai' => $updateData['tgl_lahir_pegawai'],
            'email' => $updateData['email'],
            'password' => $updateData['password']
        ]);

        if($pegawai->save()){
            return response([
                'message' => 'Update Pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Update Pegawai Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::find($id);

        if(is_null($pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
                'deleted data' => null
            ], 404);
        }

        if($pegawai->delete()){
            return response([
                'message' => 'Delete Pegawai Success',
                'deleted data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Delete Pegawai Failed',
            'deleted data' => null
        ], 400);
    }
}
