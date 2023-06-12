<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking_kelas;
use App\Models\Deposit_kelas;
use App\Models\Jadwal_harian;
use App\Models\Kelas;
use App\Models\Member;
use Illuminate\Support\Facades\Validator;

class BookingKelasController extends Controller
{
    public function index()
    {
        $booking_kelas = Booking_kelas::with('jadwal_harian.kelas', 'jadwal_harian.instruktur', 'member')->get();

        if (count($booking_kelas) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $booking_kelas
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $booking_kelas = Booking_kelas::find($id);

        if (!is_null($booking_kelas)) {
            return response([
                'message' => 'Retrieve Booking_kelas Success',
                'data' => $booking_kelas
            ], 200);
        }

        return response([
            'message' => 'Booking_kelas Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_jadwal_harian' => 'required',
            'id_member' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);
            
        $member = Member::find($storeData['id_member']);
        $jadwal_harian = Jadwal_harian::find($storeData['id_jadwal_harian']);
        $kelas = Kelas::find($jadwal_harian['id_kelas']);
        $kuota_kelas = $jadwal_harian['kuota'];

        $storeData['tgl_booking_kelas'] = $jadwal_harian['tgl_kelas'];

        $booking_kelas = Booking_kelas::where('id_member','=', $storeData['id_member'])
            ->where('id_jadwal_harian','=', $storeData['id_jadwal_harian'])
            ->where('tgl_booking_kelas', '=', $storeData['tgl_booking_kelas'])->first();

        $check_kuota = Booking_kelas::where('id_jadwal_harian','=', $storeData['id_jadwal_harian'])
            ->where('tgl_booking_kelas', '=', $storeData['tgl_booking_kelas'])->get();

        $deposit_kelas = Deposit_kelas::where('id_member','=', $storeData['id_member'])
                            ->where('id_kelas', '=', $jadwal_harian['id_kelas'])->first();

        if(is_null($booking_kelas)){
            if($member['status'] == 1){
                if(($check_kuota->count()) < (int)$kuota_kelas){
                    if(!is_null($deposit_kelas)){
                        if($deposit_kelas['deposit_kelas'] != 0){
                            //pake deposit kelas
                            $deposit_digunakan = $deposit_kelas;
                        }
                    }else{
                        if($member['deposit_uang'] >= $kelas['harga']){
                            //pake deposit uang
                            $deposit_digunakan = 'Deposit Uang: '.$member['deposit_uang'];
                        }else{
                            return response([
                                'message' => 'Deposit uang tidak cukup',
                            ], 400);
                        }
                    }

                    $storeData['status'] = 'BELUM PRESENSI';
                    
                    $booking_kelas_store = Booking_kelas::create($storeData);
                    $booking_kelas_store = Booking_kelas::latest()->first();
                    
                    return response([
                        'message' => 'Add Booking_kelas Success',
                        'data' => $booking_kelas_store,
                        'deposit digunakan' => $deposit_digunakan
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
                'message' => 'Booking Kelas sudah ada',
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $booking_kelas = Booking_kelas::find($id);

        if (is_null($booking_kelas)) {
            return response([
                'message' => 'Booking_kelas Not Found',
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

        $booking_kelas->update([
            'nama' => $updateData['nama'],
            'no_telp' => $updateData['no_telp'],
            'tgl_lahir' => $updateData['tgl_lahir'],
            'email' => $updateData['email'],
            'password' => $updateData['password']
        ]);

        if ($booking_kelas->save()) {
            return response([
                'message' => 'Update Booking_kelas Success',
                'data' => $booking_kelas
            ], 200);
        }

        return response([
            'message' => 'Update Booking_kelas Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $booking_kelas = Booking_kelas::find($id);

        if (is_null($booking_kelas)) {
            return response([
                'message' => 'Booking_kelas Not Found',
                'deleted data' => null
            ], 404);
        }

        if ($booking_kelas->delete()) {
            return response([
                'message' => 'Delete Booking_kelas Success',
                'deleted data' => $booking_kelas
            ], 200);
        }

        return response([
            'message' => 'Delete Booking_kelas Failed',
            'deleted data' => null
        ], 400);
    }

    public function cancelBookingKelas($id_jadwal_harian, $id_member, $tgl_booking_kelas)
    {
        $booking_kelas = Booking_kelas::where('id_jadwal_harian','=', $id_jadwal_harian)
                        ->where('id_member','=', $id_member)
                        ->where('tgl_booking_kelas', '=', $tgl_booking_kelas)
                        ->update(['status' => 'BATAL']);

        if ($booking_kelas == 0) {
            return response([
                'message' => 'Booking_kelas Not Found',
                'canceled data' => null
            ], 404);
        }

        return response([
            'message' => 'Cancel Booking_kelas Success',
            'id_member' => $id_member,
            'deleted data' => $booking_kelas
        ], 200);
    }

    public function getByIdMember($id_member)
    {
        $booking_kelas = Booking_kelas::where('id_member','=', $id_member)->with('jadwal_harian.kelas', 'jadwal_harian.instruktur', 'member')->get();

        if (count($booking_kelas) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $booking_kelas
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }
}
