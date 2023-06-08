<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi_gym;
use App\Models\Booking_gym;
use App\Models\Sesi_gym;
use App\Models\Member;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PresensiGymController extends Controller
{
    public function index()
    {
        $presensi_gym = Presensi_gym::with('member', 'sesiGym')->get();
        $member = Member::latest()->get();
        $sesi = Sesi_gym::latest()->get();

        if (count($presensi_gym) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $presensi_gym
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $presensi_gym = Presensi_gym::find($id);

        if (!is_null($presensi_gym)) {
            return response([
                'message' => 'Retrieve Presensi_gym Success',
                'data' => $presensi_gym
            ], 200);
        }

        return response([
            'message' => 'Presensi_gym Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request, $presensi)
    {

        $storeData = $request->all();
     
        $validate = Validator::make($storeData, [
            'id_member' => 'required',
            'sesi' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $storeData['jam_presensi'] = Carbon::now()->format('H:i:s');
        $storeData['tgl_presensi_gym'] = Carbon::now()->format('Y-m-d');
        // $storeData['tgl_presensi_gym'] = Carbon::parse('2023-05-12');

        // $jam_presensi= Carbon::now()->format('H:i:s');
        // $tgl_presensi_gym = Carbon::now()->format('Y-m-d');

        // $storeData['jam_presensi'] = "'".$jam_presensi."'";
        // $storeData['tgl_presensi_gym'] = "'".$tgl_presensi_gym."'";
        
        $booking_gym = Booking_gym::where('id_member','=', $storeData['id_member'])
                        ->where('sesi','=', $storeData['sesi'])
                        ->where('tgl_booking','=', $storeData['tgl_presensi_gym'])->first();
                        

        if(is_null($booking_gym)){
            return response([
                'message' => 'Booking Gym tidak ada',
                'data' => null,
                'id_member' => $storeData['id_member'],
                'sesi' => $storeData['sesi'],
                'tgl_booking' => $storeData['tgl_presensi_gym']
            ], 404);
        }else{
            $booking_gym_update = Booking_gym::where('id_member','=', $storeData['id_member'])
                ->where('sesi','=', $storeData['sesi'])
                ->where('tgl_booking','=', $storeData['tgl_presensi_gym'])
                ->update(['status' => $presensi]);
        }

        $presensi_gym = Presensi_gym::create($storeData);
        $presensi_gym = Presensi_gym::latest()->first();
        
        return response([
            'message' => 'Add Presensi_gym Success',
            'data' => $presensi_gym,
            'data booking' => $booking_gym,
            'booking update' => $booking_gym_update
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $presensi_gym = Presensi_gym::find($id);

        if (is_null($presensi_gym)) {
            return response([
                'message' => 'Presensi_gym Not Found',
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

        $presensi_gym->update([
            'nama' => $updateData['nama'],
            'no_telp' => $updateData['no_telp'],
            'tgl_lahir' => $updateData['tgl_lahir'],
            'email' => $updateData['email'],
            'password' => $updateData['password']
        ]);

        if ($presensi_gym->save()) {
            return response([
                'message' => 'Update Presensi_gym Success',
                'data' => $presensi_gym
            ], 200);
        }

        return response([
            'message' => 'Update Presensi_gym Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $presensi_gym = Presensi_gym::find($id);

        if (is_null($presensi_gym)) {
            return response([
                'message' => 'Presensi_gym Not Found',
                'deleted data' => null
            ], 404);
        }

        if ($presensi_gym->delete()) {
            return response([
                'message' => 'Delete Presensi_gym Success',
                'deleted data' => $presensi_gym
            ], 200);
        }

        return response([
            'message' => 'Delete Presensi_gym Failed',
            'deleted data' => null
        ], 400);
    }

    public function resetKeterlambatan()
    {
        $presensi_gym = Presensi_gym::query()->update(['keterlambatan' => '00:00:00']);
        // $presensi_gym = Presensi_gym::all();
        // $presensi_gym->update([
        //     'keterlambatan' => '00:00:00'
        // ]);

        // if ($presensi_gym->save()) {
        //     return response([
        //         'message' => 'Reset Keterlambatan Presensi_gym Success',
        //         'data' => $presensi_gym
        //     ], 200);
        // }

        return response([
            'message' => 'Reset Keterlambatan Presensi_gym Succsess',
            'data' => $presensi_gym
        ], 200);
    }
}
