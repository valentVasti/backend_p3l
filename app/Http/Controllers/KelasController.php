<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();

        if (count($kelas) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $kelas
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $kelas = Kelas::find($id);

        if (!is_null($kelas)) {
            return response([
                'message' => 'Retrieve Kelas Success',
                'data' => $kelas
            ], 200);
        }

        return response([
            'message' => 'Kelas Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'nama_kelas' => 'required',
            'harga' => 'required',
            'kuota' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $kelas = Kelas::create($storeData);
        $kelas = Kelas::latest()->first();

        return response([
            'message' => 'Add Kelas Success',
            'data' => $kelas
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::find($id);

        if (is_null($kelas)) {
            return response([
                'message' => 'Kelas Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'nama_kelas' => 'required',
            'harga' => 'required',
            'kuota' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $kelas->update([
            'nama_kelas' => $updateData['nama_kelas'],
            'harga' => $updateData['harga'],
            'kuota' => $updateData['kuota']
        ]);

        if ($kelas->save()) {
            return response([
                'message' => 'Update Kelas Success',
                'data' => $kelas
            ], 200);
        }

        return response([
            'message' => 'Update Kelas Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $kelas = Kelas::find($id);

        if (is_null($kelas)) {
            return response([
                'message' => 'Kelas Not Found',
                'deleted data' => null
            ], 404);
        }

        if ($kelas->delete()) {
            return response([
                'message' => 'Delete Kelas Success',
                'deleted data' => $kelas
            ], 200);
        }

        return response([
            'message' => 'Delete Kelas Failed',
            'deleted data' => null
        ], 400);
    }
}
