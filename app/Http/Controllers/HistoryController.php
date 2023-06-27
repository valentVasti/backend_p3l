<?php

namespace App\Http\Controllers;

use App\Models\Instruktur;
use App\Models\Jadwal_harian;
use App\Models\Jadwal_umum;
use App\Models\Kelas;
use App\Models\Member;
use App\Models\Presensi_instruktur;
use Illuminate\Http\Request;
use App\Models\Transaksi_aktivasi;
use App\Models\Transaksi_depositk;
use App\Models\Transaksi_depou;
use App\Models\Presensi_kelas;
use Carbon\Carbon;


class HistoryController extends Controller
{
    public function historyTransaksiMember($id_member)
    {

        $member = Member::find($id_member);

        if(is_null($member)){
            return response([
                'message' => 'Member Not Found!',
            ], 404);
        }

        $history = collect([]);
        $transaksi_aktivasi = Transaksi_aktivasi::where('id_member', '=', $id_member)->get();
        $transaksi_depositu = Transaksi_depou::where('id_member', '=', $id_member)->get();
        $transaksi_depositk = Transaksi_depositk::where('id_member', '=', $id_member)->get();
        $presensi_kelas = Presensi_kelas::where('id_member', '=', $id_member)->get();

        foreach($presensi_kelas as $data){
            $jadwal_harian = Jadwal_harian::find($data['id_jadwal_harian']);
            $kelas = Kelas::find($jadwal_harian['id_kelas']);

            $data_history['jenis_transaksi'] = 'Presensi Kelas';
            $data_history['id_presensi_kelas'] = $data['id_presensi_kelas'];
            $data_history['nama_kelas'] = $kelas['nama_kelas'];
            $data_history['status_kehadiran'] = $data['status_kehadiran'];
            // $data_history['tgl_history'] = $data['tgl_presensi_kelas'];
            // $data_history['jam_history'] = substr($data['created_at'], 11, 8);

            $data_history['id_transaksi'] = "";
            $data_history['jumlah_transaksi'] = "";
            $data_history['jumlah_deposit_kelas'] = "";

            $data_history['tgl_history'] = $data['tgl_presensi_kelas'];
            $data_history['jam_history'] = substr($data['created_at'], 11, 8);
            $history->add($data_history);
        }

        foreach($transaksi_aktivasi as $data){
            $data_history['jenis_transaksi'] = 'Transaksi Aktivasi';
            $data_history['id_transaksi'] = $data['id_transaksi'];
            $data_history['jumlah_transaksi'] = "3000000";
            $data_history['jumlah_deposit_kelas'] = "";


            $data_history['id_presensi_kelas'] = "";
            $data_history['nama_kelas'] = "";
            $data_history['status_kehadiran'] = "";

            $data_history['tgl_history'] = $data['tgl_aktivasi'];
            $data_history['jam_history'] = substr($data['created_at'], 11, 8);
            $history->add($data_history);
        }

        foreach($transaksi_depositu as $data){
            $data_history['jenis_transaksi'] = 'Transaksi Deposit Uang';
            $data_history['id_transaksi'] = $data['id_transaksi_depou'];
            $data_history['jumlah_transaksi'] = $data['jumlah_depou'];
            $data_history['jumlah_deposit_kelas'] = "";

            $data_history['id_presensi_kelas'] = "";
            $data_history['nama_kelas'] = "";
            $data_history['status_kehadiran'] = "";

            $data_history['tgl_history'] = $data['tgl_transaksi'];
            $data_history['jam_history'] = substr($data['created_at'], 11, 8);
            $history->add($data_history);
        }

        foreach($transaksi_depositk as $data){
            $kelas = Kelas::find($data['id_kelas']);

            $data_history['jenis_transaksi'] = 'Transaksi Deposit Kelas';
            $data_history['id_transaksi'] = $data['id_transaksi_depok'];
            $data_history['jumlah_transaksi'] = $data['jumlah_bayar'];
            $data_history['jumlah_deposit_kelas'] = $data['jumlah_deposit']." x ".$kelas['nama_kelas'];

            $data_history['id_presensi_kelas'] = "";
            $data_history['nama_kelas'] = "";
            $data_history['status_kehadiran'] = "";

            $data_history['tgl_history'] = $data['tgl_transaksi'];
            $data_history['jam_history'] = substr($data['created_at'], 11, 8);
            $history->add($data_history);
        }

        $history = $history->sortBy('tgl_history');
        $history = $history->values();

        return response([
            'message' => 'History succsesfully retrieved!',
            'data' => $history
        ], 200);
    }

    public function historyKelasMember($id_member)
    {

        $member = Member::find($id_member);

        if(is_null($member)){
            return response([
                'message' => 'Member Not Found!',
            ], 404);
        }

        $history = collect([]);
        $presensi_kelas = Presensi_kelas::where('id_member', '=', $id_member)->get();

        foreach($presensi_kelas as $data){
            $jadwal_harian = Jadwal_harian::find($data['id_jadwal_harian']);
            $kelas = Kelas::find($jadwal_harian['id_kelas']);

            $data_history['id_presensi_kelas'] = $data['id_presensi_kelas'];
            $data_history['nama_kelas'] = $kelas['nama_kelas'];
            $data_history['status_kehadiran'] = $data['status_kehadiran'];
            $data_history['tgl_kelas'] = $data['tgl_presensi_kelas'];
            $data_history['jam_presensi'] = substr($data['created_at'], 11, 8);
            $history->add($data_history);
        }

        $history = $history->sortBy('tgl_kelas');
        $history = $history->values();

        return response([
            'message' => 'History succsesfully retrieved!',
            'data' => $history
        ], 200);
    }

    public function historyInstruktur($id_instruktur)
    {

        $instruktur = Instruktur::find($id_instruktur);

        if(is_null($instruktur)){
            return response([
                'message' => 'Member Not Found!',
            ], 404);
        }

        $history = collect([]);
        $jadwal_harian = Jadwal_harian::where('id_instruktur','=',$id_instruktur)->get();

        foreach($jadwal_harian as $data_jadwal_harian){
            $presensi_instruktur = Presensi_instruktur::where('id_jadwal_harian', '=', $data_jadwal_harian['id_jadwal_harian'])
                                                            ->where('status_kelas','=','KELAS SELESAI')
                                                            ->get();
            $kelas = Kelas::find($data_jadwal_harian['id_kelas']);

            foreach($presensi_instruktur as $data_presensi_instruktur){
                $data_history['jenis_aktivitas'] = 'Mengajar Kelas';
                $data_history['id_presensi_instruktur'] = $data_presensi_instruktur['id_presensi_instruktur'];
                $data_history['kelas'] = $kelas['nama_kelas'];
                $data_history['hari_tgl_kelas'] = $data_jadwal_harian['hari_kelas_harian']." ,".$data_jadwal_harian['tgl_kelas'];
                $data_history['jam_kelas'] = $data_jadwal_harian['jam_mulai']." - ".$data_jadwal_harian['jam_selesai'];
                $data_history['keterlambatan'] = $data_presensi_instruktur['keterlambatan'];
                $history->add($data_history);
            }

        }

        $history = $history->sortBy('tgl_kelas');
        $history = $history->values();

        if(count($history) > 0){
            return response([
                'message' => 'History Instruktur succsesfully retrieved!',
                'data' => $history
            ], 200);
        }

        return response([
            'message' => 'Instruktur belum pernah selesai mengajar!',
            'data' => $history
        ], 400);        
    }
}
