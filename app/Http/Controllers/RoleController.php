<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function store(Request $request)
    {

        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_role' => 'required',
            'nama_role' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $role = Role::create($storeData);
        $rple = Role::latest()->first();

        return response([
            'message' => 'Add Role Success',
            'data' => $role
        ], 200);
    }
}
