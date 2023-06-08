<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function index()
    {
        $member = Member::all();
        
        if(count($member) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_member)
    {
        $member = Member::find($id_member);

        if(!is_null($member)){
            return response([
                'message' => 'Retrieve Member Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Member Not Found',
            'data' => null
        ], 404);

    }

    public function store(Request $request)
    {

        $storeData = $request->all();

        $storeData['status'] = 0;
        $storeData['tgl_daftar'] = Carbon::today();
        $storeData['deposit_uang'] = 0;
        $storeData['tgl_kadaluarsa'] = null;

        $validate = Validator::make($storeData, [
            'nama' => 'required',
            'no_telp' => 'required',
            'tgl_lahir' => 'required',
            'status' => 'required',
            'email' => 'required',
            'password' => 'required',
            'tgl_daftar' => 'required',
            'deposit_uang' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $member = Member::create($storeData);
        $member = Member::latest()->first();

        return response([
            'message' => 'Add Member Success',
            'data' => $member
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $member = Member::find($id);

        if(is_null($member)){
            return response([
                'message' => 'Member Not Found',
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

        $member->update([
            'nama' => $updateData['nama'],
            'no_telp' => $updateData['no_telp'],
            'tgl_lahir' => $updateData['tgl_lahir'],
            'email' => $updateData['email'],
            'password' => $updateData['password']
        ]);

        if($member->save()){
            return response([
                'message' => 'Update Member Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Update Member Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $member = Member::find($id);

        if(is_null($member)){
            return response([
                'message' => 'Member Not Found',
                'deleted data' => null
            ], 404);
        }

        if($member->delete()){
            return response([
                'message' => 'Delete Member Success',
                'deleted data' => $member
            ], 200);
        }

        return response([
            'message' => 'Delete Member Failed',
            'deleted data' => null
        ], 400);
    }

    public function resetPassword($id)
    {
        $member = Member::find($id);

        if(is_null($member)){
            return response([
                'message' => 'Member Not Found',
                'data' => null
            ], 404);
        }

        $tgl_lahir = $member->tgl_lahir;

        $year = Str::substr($tgl_lahir, 2, 2);
        $month = Str::substr($tgl_lahir, 5, 2);
        $tgl = Str::substr($tgl_lahir, 8, 2);

        if($member->password == $tgl.$month.$year){
            return response([
                'message' => 'Member Password Already Default',
                'data' => null
            ], 400);
        }

        $member->update([
            'password' => $tgl.$month.$year
        ]);

        if($member->save()){
            return response([
                'message' => 'Member Password Reset Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Member Password Reset Failed',
            'data' => null
        ], 400);
    }

    public function showNotActive()
    {
        $member = Member::where('status', '=', '0')->get();
        // $pegawai = Pegawai::where('email','=',$loginData['email'])->first();

        if(!is_null($member)){
            return response([
                'message' => 'Retrieve Not Active Member Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Member Not Found',
            'data' => null
        ], 404);

    }

    public function getExpiredMember()
    {
        $date_now = Carbon::today();
        $date_now->toDateString();
        $member = Member::where('tgl_kadaluarsa', '<=', $date_now)->get();
        
        if(count($member) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function deactivateMember($id){
        $member = Member::find($id);

        if(is_null($member)){
            return response([
                'message' => 'Member Not Found',
                'data' => null
            ], 404);
        }

        $member->update([
            'tgl_kadaluarsa' => null,
            'status' => 0
        ]);

        if($member->save()){
            return response([
                'message' => 'Deactivate Member Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Deactivate Member Failed',
            'data' => null
        ], 400);
    }
}
