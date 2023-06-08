<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking_gym;
use App\Models\Member;
use App\Models\Sesi_gym;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class BookingGymController extends Controller
{
    public function index()
    {
        $booking_gym = Booking_gym::with('member', 'sesiGym')->get();
        $sesi = Sesi_gym::latest()->get();
        $member = Member::latest()->get();

        if (count($booking_gym) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $booking_gym
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function getBookingGymToday()
    {
        $today = Carbon::today();
        $booking_gym = Booking_gym::where('tgl_booking','=', $today)
                        ->with('member', 'sesiGym')->get();
        $sesi = Sesi_gym::latest()->get();
        $member = Member::latest()->get();

        if (count($booking_gym) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $booking_gym
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $booking_gym = Booking_gym::find($id);

        if (!is_null($booking_gym)) {
            return response([
                'message' => 'Retrieve Booking_gym Success',
                'data' => $booking_gym
            ], 200);
        }

        return response([
            'message' => 'Booking_gym Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_member' => 'required',
            'sesi' => 'required',
            'tgl_booking' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);
            
        $booking_gym = Booking_gym::where('id_member','=', $storeData['id_member'])
            ->where('sesi','=', $storeData['sesi'])
            ->where('tgl_booking', '=', $storeData['tgl_booking'])->first();

        $check_kuota = Booking_gym::where('sesi','=', $storeData['sesi'])
            ->where('tgl_booking', '=', $storeData['tgl_booking'])->get();

        $member = Member::find($storeData['id_member']);

        if(is_null($booking_gym)){
            if($member['status'] == 1){
                if(($check_kuota->count()) < 10){
                    $storeData['status'] = 'BELUM PRESENSI';

                    $booking_gym_store = Booking_gym::create($storeData);
                    $booking_gym_store = Booking_gym::latest()->first();
                    
                    return response([
                        'message' => 'Add Booking_gym Success',
                        'data' => $booking_gym_store
                    ], 200);
                }else{
                    return response([
                        'message' => 'Kuota sudah penuh',
                    ], 400);
                } 
            }else{
                return response([
                    'message' => 'Membership tidak aktif',
                ], 400);
            }
        }else{
            return response([
                'message' => 'Booking Gym sudah ada',
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $booking_gym = Booking_gym::find($id);

        if (is_null($booking_gym)) {
            return response([
                'message' => 'Booking_gym Not Found',
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

        $booking_gym->update([
            'nama' => $updateData['nama'],
            'no_telp' => $updateData['no_telp'],
            'tgl_lahir' => $updateData['tgl_lahir'],
            'email' => $updateData['email'],
            'password' => $updateData['password']
        ]);

        if ($booking_gym->save()) {
            return response([
                'message' => 'Update Booking_gym Success',
                'data' => $booking_gym
            ], 200);
        }

        return response([
            'message' => 'Update Booking_gym Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $booking_gym = Booking_gym::find($id);

        if (is_null($booking_gym)) {
            return response([
                'message' => 'Booking_gym Not Found',
                'deleted data' => null
            ], 404);
        }

        if ($booking_gym->delete()) {
            return response([
                'message' => 'Delete Booking_gym Success',
                'deleted data' => $booking_gym
            ], 200);
        }

        return response([
            'message' => 'Delete Booking_gym Failed',
            'deleted data' => null
        ], 400);
    }

    public function cancelBookingGym($id_member, $sesi, $tgl_booking)
    {
        $booking_gym = Booking_gym::where('id_member','=',$id_member)
                        ->where('sesi','=',$sesi)
                        ->where('tgl_booking','=',$tgl_booking)->first();

        if($booking_gym['status'] == 'SUDAH PRESENSI'){
            return response([
                'message' => 'Sudah Presensi',
                'canceled data' => null
            ], 404);   
        }

        $booking_gym_save = Booking_gym::where('id_member','=',$id_member)
        ->where('sesi','=',$sesi)
        ->where('tgl_booking','=',$tgl_booking)
        ->update(['status' => 'BATAL']);

        if ($booking_gym_save == 0) {
            return response([
                'message' => 'Booking_gym Not Found',
                'canceled data' => null
            ], 404);
        }

        return response([
            'message' => 'Cancel Booking_gym Success',
            'id_member' => $id_member,
            'sesi' => $sesi,
            'tgl_booking' => $tgl_booking,
            'deleted data' => $booking_gym
        ], 200);
    }
}
