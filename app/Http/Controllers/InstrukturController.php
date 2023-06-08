<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instruktur;
use Illuminate\Support\Facades\Validator;

class InstrukturController extends Controller
{
    public function index()
    {
        $instruktur = Instruktur::all();

        if (count($instruktur) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $instruktur
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $instruktur = Instruktur::find($id);

        if (!is_null($instruktur)) {
            return response([
                'message' => 'Retrieve Instruktur Success',
                'data' => $instruktur
            ], 200);
        }

        return response([
            'message' => 'Instruktur Not Found',
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

        $instruktur = Instruktur::create($storeData);
        $instruktur = Instruktur::latest()->first();
        
        return response([
            'message' => 'Add Instruktur Success',
            'data' => $instruktur
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $instruktur = Instruktur::find($id);

        if (is_null($instruktur)) {
            return response([
                'message' => 'Instruktur Not Found',
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

        $instruktur->update([
            'nama' => $updateData['nama'],
            'no_telp' => $updateData['no_telp'],
            'tgl_lahir' => $updateData['tgl_lahir'],
            'email' => $updateData['email'],
            'password' => $updateData['password']
        ]);

        if ($instruktur->save()) {
            return response([
                'message' => 'Update Instruktur Success',
                'data' => $instruktur
            ], 200);
        }

        return response([
            'message' => 'Update Instruktur Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $instruktur = Instruktur::find($id);

        if (is_null($instruktur)) {
            return response([
                'message' => 'Instruktur Not Found',
                'deleted data' => null
            ], 404);
        }

        if ($instruktur->delete()) {
            return response([
                'message' => 'Delete Instruktur Success',
                'deleted data' => $instruktur
            ], 200);
        }

        return response([
            'message' => 'Delete Instruktur Failed',
            'deleted data' => null
        ], 400);
    }

    public function resetKeterlambatan()
    {
        $instruktur = Instruktur::query()->update(['keterlambatan' => '00:00:00']);
        // $instruktur = Instruktur::all();
        // $instruktur->update([
        //     'keterlambatan' => '00:00:00'
        // ]);

        // if ($instruktur->save()) {
        //     return response([
        //         'message' => 'Reset Keterlambatan Instruktur Success',
        //         'data' => $instruktur
        //     ], 200);
        // }

        return response([
            'message' => 'Reset Keterlambatan Instruktur Succsess',
            'data' => $instruktur
        ], 200);
    }
}
