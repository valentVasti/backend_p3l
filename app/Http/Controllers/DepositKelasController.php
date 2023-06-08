<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Deposit_kelas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class DepositKelasController extends Controller
{

    public function index()
    {
        $deposit_kelas = Deposit_kelas::all();

        if (count($deposit_kelas) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $deposit_kelas
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $deposit_kelas = Deposit_kelas::find($id);

        if (!is_null($deposit_kelas)) {
            return response([
                'message' => 'Retrieve Deposit_kelas Success',
                'data' => $deposit_kelas
            ], 200);
        }

        return response([
            'message' => 'Deposit_kelas Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {

        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'nama' => 'required',
            'no_telp' => 'required',
            'tgl_lahir' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $deposit_kelas = Deposit_kelas::create($storeData);
        $deposit_kelas = Deposit_kelas::latest()->first();
        
        return response([
            'message' => 'Add Deposit_kelas Success',
            'data' => $deposit_kelas
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $deposit_kelas = Deposit_kelas::find($id);

        if (is_null($deposit_kelas)) {
            return response([
                'message' => 'Deposit_kelas Not Found',
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

        $deposit_kelas->update([
            'nama' => $updateData['nama'],
            'no_telp' => $updateData['no_telp'],
            'tgl_lahir' => $updateData['tgl_lahir'],
            'email' => $updateData['email'],
            'password' => $updateData['password']
        ]);

        if ($deposit_kelas->save()) {
            return response([
                'message' => 'Update Deposit_kelas Success',
                'data' => $deposit_kelas
            ], 200);
        }

        return response([
            'message' => 'Update Deposit_kelas Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $deposit_kelas = Deposit_kelas::find($id);

        if (is_null($deposit_kelas)) {
            return response([
                'message' => 'Deposit_kelas Not Found',
                'deleted data' => null
            ], 404);
        }

        if ($deposit_kelas->delete()) {
            return response([
                'message' => 'Delete Deposit_kelas Success',
                'deleted data' => $deposit_kelas
            ], 200);
        }

        return response([
            'message' => 'Delete Deposit_kelas Failed',
            'deleted data' => null
        ], 400);
    }
    public function getExpiredDepositKelas()
    {
        $deposit_kelas = Deposit_kelas::where('tgl_kadaluarsa', '<=', Carbon::today())->get();

        if (count($deposit_kelas) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $deposit_kelas
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function resetDepositKelas($id_member, $id_kelas)
    {
        $deposit_kelas = Deposit_kelas::where('id_member', '=', $id_member)->where('id_kelas', '=', $id_kelas)->first();

        if (is_null($deposit_kelas)) {
            return response([
                'message' => 'Deposit_kelas Not Found',
                'data' => null
            ], 404);
        }

        $deposit_kelas->update([
            'deposit_kelas' => 0
        ]);

        if ($deposit_kelas->save()) {
            return response([
                'message' => 'Reset Deposit kelas Success',
                'data' => $deposit_kelas
            ], 200);
        }

        return response([
            'message' => 'Reset Deposit kelas Failed',
            'data' => null
        ], 400);
    }

}
