<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Instruktur;
use App\Models\Member;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validate->fails())
            return response([
                'status' => 400,
                'message' => $validate->errors()
            ], 400);

        // if(is_null($request->email) || is_null($request->password)){
        //     return response(['message' => 'Inputan tidak boleh kosong'], 400);
        // }

        $pegawai = null;
        //get token with random string//
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 10; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        if (Pegawai::where('email', '=', $loginData['email'])->first()) {
            $pegawai = Pegawai::where('email', '=', $loginData['email'])->first();

            if ($loginData['password'] == $pegawai['password']) {

                $token = bcrypt($randomString);
                $role = Role::find($pegawai['id_role']);

                return response([
                    'message' => 'Berhasil Login',
                    'data' => $pegawai,
                    'role' => $role,
                    'token' => $token
                ]);
            } else {
                return response([
                    'status' => 401,
                    'message' => 'Email atau Password Salah',
                ], 401);
            }
        } else {
            return response([
                'status' => 404,
                'message' => 'User tidak ditemukan',
                'data' => null
            ], 404);
        }
    }

    public function loginMobile(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validate->fails())
            return response([
                'status' => 400,
                'message' => $validate->errors()
            ], 400);

        // if(is_null($request->email) || is_null($request->password)){
        //     return response(['message' => 'Inputan tidak boleh kosong'], 400);
        // }

        $user = null;

        if (Pegawai::where('email', '=', $loginData['email'])->first()) {
            $user = Pegawai::where('email', '=', $loginData['email'])->first();

            if ($user['id_role'] == 'R02') {
                if ($loginData['password'] == $user['password']) {

                    $role = Role::find($user['id_role']);

                    return response()->json([
                        'message' => 'Berhasil Login sebagai MO',
                        'nama' => $user['nama_pegawai'],
                        'id' => $user['id_pegawai'],
                    ], 200);
                } else {
                    return response([
                        'status' => 401,
                        'message' => 'Email atau Password Salah',
                    ], 401);
                }
            } else {
                return response([
                    'status' => 404,
                    'message' => 'User tidak ditemukan',
                    'data' => null
                ], 404);
            }
        } else if (Instruktur::where('email', '=', $loginData['email'])->first()) {
            $user = Instruktur::where('email', '=', $loginData['email'])->first();

            if ($loginData['password'] == $user['password']) {

                return response([
                    'message' => 'Berhasil Login sebagai Instruktur',
                    'nama' => $user['nama'],
                    'id' => $user['id_instruktur'],
                ]);
            } else {
                return response([
                    'status' => 401,
                    'message' => 'Email atau Password Salah',
                ], 401);
            }
        } else if (Member::where('email', '=', $loginData['email'])->first()) {
            $user = Member::where('email', '=', $loginData['email'])->first();

            if ($loginData['password'] == $user['password']) {

                return response([
                    'message' => 'Berhasil Login sebagai Member',
                    'nama' => $user['nama'],
                    'id' => $user['id_member'], 
                ]);
            } else {
                return response([
                    'status' => 401,
                    'message' => 'Email atau Password Salah',
                ], 401);
            }
        } else {
            return response([
                'status' => 404,
                'message' => 'User tidak ditemukan',
                'data' => null
            ], 404);
        }
    }
}
