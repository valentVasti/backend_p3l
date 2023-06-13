<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking_kelas;
use Illuminate\Http\Request;
use App\Models\Presensi_kelas;
use App\Models\Deposit_kelas;
use App\Models\Jadwal_harian;
use App\Models\Jadwal_umum;
use App\Models\Kelas;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PresensiKelasController extends Controller
{
    public function index()
    {
        // $presensi_kelas = Presensi_kelas::all();
        $presensi_kelas = Presensi_kelas::with('member', 'jadwal_harian.kelas', 'jadwal_harian.instruktur')->get();

        if (count($presensi_kelas) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $presensi_kelas
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $presensi_kelas = Presensi_kelas::with('member', 'jadwal_harian.kelas', 'jadwal_harian.instruktur')->find($id);

        $deposit_kelas = Deposit_kelas::where('id_member','=', $presensi_kelas['id_member'])
                            ->where('id_kelas', '=', $presensi_kelas['jadwal_harian']['id_kelas'])->first();

        // $member = Member::find($presensi_kelas['id_member']);

        if(!is_null($deposit_kelas)){
            if($deposit_kelas['deposit_kelas'] != 0){
                //pake deposit kelas
                $sisa_deposit_kelas = $deposit_kelas['deposit_kelas'];
                $masa_berlaku = $deposit_kelas['tgl_kadaluarsa'];


                $jenis_pembayaran = 'Deposit Kelas';

                return response([
                    'message' => 'Retrieve Presensi_kelas Success',
                    'data' => $presensi_kelas,
                    'jenis_pembayaran' => $jenis_pembayaran,
                    'sisa_deposit' => $sisa_deposit_kelas,
                    'data_pendukung' => $masa_berlaku
                ], 200);
            }
        }else{
            if($presensi_kelas->member['deposit_uang'] >= $presensi_kelas['jadwal_harian']['kelas']['harga']){
                //pake deposit uang
                $jenis_pembayaran = 'Deposit Uang';
                $sisa_deposit_uang =  $presensi_kelas->member['deposit_uang'];
                $tarif_kelas = $presensi_kelas['jadwal_harian']['kelas']['harga'];

                return response([
                    'message' => 'Retrieve Presensi_kelas Success',
                    'data' => $presensi_kelas,
                    'jenis_pembayaran' => $jenis_pembayaran,
                    'sisa_deposit' => $sisa_deposit_uang,
                    'data_pendukung' => $tarif_kelas
                ], 200);
            }else{
                return response([
                'message' => 'Deposit uang tidak cukup',
                ], 400);
            }
        }

        if (!is_null($presensi_kelas)) {
            return response([
                'message' => 'Retrieve Presensi_kelas Failed',
                'data' => $presensi_kelas
            ], 404);
        }

        return response([
            'message' => 'Presensi_kelas Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_jadwal_harian' => 'required',
            'id_member' => 'required',
            'status_kehadiran' => 'required'
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $storeData['tgl_presensi_kelas'] = Carbon::today();
        
        $booking_kelas = Booking_kelas::where('id_member','=', $storeData['id_member'])
                                    ->where('id_jadwal_harian','=', $storeData['id_jadwal_harian'])
                                    ->where('tgl_booking_kelas','=', $storeData['tgl_presensi_kelas'])
                                    ->get();

        if(!count($booking_kelas) > 0){
            return response([
                'message' => 'Booking Kelas Not Found',
                'data' => $booking_kelas,
            ], 400);           
        }

        $booking_kelas_update = Booking_kelas::where('id_member','=', $storeData['id_member'])
                                ->where('id_jadwal_harian','=', $storeData['id_jadwal_harian'])
                                ->where('tgl_booking_kelas','=', $storeData['tgl_presensi_kelas'])
                                ->update(['status' => 'SUDAH PRESENSI']);
                                

        $booking_kelas_store = Presensi_kelas::create($storeData);
        $booking_kelas_store = Presensi_kelas::latest()->first();
        
        return response([
            'message' => 'Add Presensi_kelas Success',
            'data' => $booking_kelas_store,
            'booking kelas update' => $booking_kelas_update
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $presensi_kelas = Presensi_kelas::find($id);

        if (is_null($presensi_kelas)) {
            return response([
                'message' => 'Presensi_kelas Not Found',
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

        $presensi_kelas->update([
            'nama' => $updateData['nama'],
            'no_telp' => $updateData['no_telp'],
            'tgl_lahir' => $updateData['tgl_lahir'],
            'email' => $updateData['email'],
            'password' => $updateData['password']
        ]);

        if ($presensi_kelas->save()) {
            return response([
                'message' => 'Update Presensi_kelas Success',
                'data' => $presensi_kelas
            ], 200);
        }

        return response([
            'message' => 'Update Presensi_kelas Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $presensi_kelas = Presensi_kelas::find($id);

        if (is_null($presensi_kelas)) {
            return response([
                'message' => 'Presensi_kelas Not Found',
                'deleted data' => null
            ], 404);
        }

        if ($presensi_kelas->delete()) {
            return response([
                'message' => 'Delete Presensi_kelas Success',
                'deleted data' => $presensi_kelas
            ], 200);
        }

        return response([
            'message' => 'Delete Presensi_kelas Failed',
            'deleted data' => null
        ], 400);
    }

    public function updateDepositMember($id_jadwal_harian)
    {
        
        $booking_kelas = Booking_kelas::where('id_jadwal_harian','=',$id_jadwal_harian)->get();
        $jadwal_harian = Jadwal_harian::find($id_jadwal_harian);

        foreach ($booking_kelas as $data) {
            
            $id_member = $data['id_member'];
            $member = Member::find($id_member);

            // $jadwal_harian = Jadwal_harian::find($data['id_jadwal_harian']);
            $kelas = Kelas::find($jadwal_harian['id_kelas']);
    
            $storeData['tgl_presensi_kelas'] = Carbon::today();
    
            $deposit_kelas = Deposit_kelas::where('id_member','=', $id_member)
                                ->where('id_kelas', '=', $jadwal_harian['id_kelas'])->first();

            if(!is_null($deposit_kelas)){
                if($deposit_kelas['deposit_kelas'] != 0){
                    //pake deposit kelas
                    //kalo deposit kelas gaada, dari awal gabakal bisa booking   
                    $sisa_deposit = $deposit_kelas['deposit_kelas'];
                    $tarif_kelas = 1;
    
                    $update_deposit_kelas = $sisa_deposit - $tarif_kelas;
    
                    $deposit_kelas = Deposit_kelas::where('id_member','=', $id_member)
                                        ->where('id_kelas', '=', $jadwal_harian['id_kelas'])
                                        ->update([
                                            'deposit_kelas' => $update_deposit_kelas
                                        ]);   
                    // $deposit_digunakan = 'Deposit Kelas';
                }
            }else{
                if($member['deposit_uang'] >= $kelas['harga']){
                    //pake deposit uang
                    $sisa_deposit = $member['deposit_uang'];
                    $tarif_kelas = $kelas['harga'];
    
                    $update_deposit_uang = $sisa_deposit - $tarif_kelas;
    
                    $member->update([
                        'deposit_uang' => $update_deposit_uang
                    ]);
    
                    // $deposit_digunakan = 'Deposit Uang';
                }else{
                    return response([
                        'message' => 'Deposit uang tidak cukup',
                    ], 400);
                }
            }

        }
                  
        return response([
            'message' => 'Deposit semua member terpotong',
            'jumlah_member' => count($booking_kelas),
        ], 200);
    }
}
