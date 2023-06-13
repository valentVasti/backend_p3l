<?php

namespace App\Http\Controllers;

use App\Models\Instruktur;
use App\Models\Jadwal_harian;
use App\Models\Jadwal_umum;
use App\Models\Kelas;
use App\Models\Presensi_gym;
use App\Models\Presensi_instruktur;
use App\Models\Presensi_kelas;
use Illuminate\Http\Request;
use App\Models\Transaksi_aktivasi;
use App\Models\Transaksi_depositk;
use App\Models\Transaksi_depou;
use App\Http\Controllers\LaporanController\cetakLaporan;
use Carbon\Carbon;
use ErrorException;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class LaporanController extends Controller
{
    var $laporanGlobal;

    public function laporanPendapatan()
    {
        $laporan = collect([]);
        $periode = Carbon::now()->format('Y');
        $tanggal_cetak = Carbon::now()->format('d-F-Y');  
        $total_all = 0;

        for ($i = 1; $i < 13; $i++) {
            $bulan = Carbon::create(null, $i, 1)->format('F');
            $dataLaporan['bulan'] = $bulan;
            $dataLaporan['deposit_uang'] = 0; 
            $dataLaporan['deposit_kelas'] = 0; 
                      

            $deposit_uang = Transaksi_depou::whereMonth('tgl_transaksi',$i)->get();
            $deposit_kelas = Transaksi_depositk::whereMonth('tgl_transaksi',$i)->get();
            $aktivasi = Transaksi_aktivasi::whereMonth('tgl_aktivasi',$i)->get();

            foreach($deposit_uang as $data){
                $dataLaporan['deposit_uang'] = (float)$dataLaporan['deposit_uang'] + $data['jumlah_depou'];
            }

            foreach($deposit_kelas as $data){
                $dataLaporan['deposit_kelas'] = (float)$dataLaporan['deposit_kelas'] + $data['jumlah_bayar'];
            }

            // $dataLaporan['total'] = null;
            // $dataLaporan['total_deposit'] = null;
            $dataLaporan['aktivasi'] = count($aktivasi) * 3000000;
            $dataLaporan['total'] = $dataLaporan['deposit_uang'] + $dataLaporan['deposit_kelas'] + $dataLaporan['aktivasi'];
            $dataLaporan['total_deposit'] = $dataLaporan['deposit_uang'] + $dataLaporan['deposit_kelas']; 
            $total_all = $total_all + $dataLaporan['total'];

            if($dataLaporan['aktivasi'] != 0 ||  $dataLaporan['total_deposit'] != 0 || $dataLaporan['total'] != 0){
                $dataLaporan['total_string_format'] = number_format($dataLaporan['deposit_uang'] + $dataLaporan['deposit_kelas'] + $dataLaporan['aktivasi'],0,',','.'); 
                $dataLaporan['aktivasi_string_format'] = number_format($dataLaporan['aktivasi'],0,',','.');
                $dataLaporan['total_deposit_format'] = number_format($dataLaporan['deposit_uang'] + $dataLaporan['deposit_kelas'],0,',','.');
            }else{
                $dataLaporan['aktivasi_format'] = $dataLaporan['aktivasi'];
                $dataLaporan['total_deposit_format'] = $dataLaporan['deposit_uang'] + $dataLaporan['deposit_kelas'];
                $dataLaporan['total_format'] = $dataLaporan['deposit_uang'] + $dataLaporan['deposit_kelas'] + $dataLaporan['aktivasi'];
            }

            $laporan->add($dataLaporan);
        }

        if (!is_null($laporan)) {
            return response([
                'message' => 'Retrieve All Success',
                'periode' => $periode,
                'total_all' => number_format($total_all,0,',','.'),
                'tanggal_cetak' => $tanggal_cetak,
                'data' => $laporan
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function laporanGymBulanan($month)
    {
        $year = Carbon::now()->format('Y');
        $firstDayOfMonth = Carbon::createFromDate(null, $month, 1);
        $endDayOfMonth = $firstDayOfMonth->endOfMonth()->format('d');

        $bulan = $firstDayOfMonth->format('F');
        
        $tanggal_cetak = Carbon::now()->format('d-M-Y');
        $total = 0; 

        for ($i = 1; $i <= $endDayOfMonth; $i++) {
            $fullDate = Carbon::create($year, $month, $i)->format('d-F-Y');
            $dataLaporan['jumlah_member'] = 0; 
            $dataLaporan['tanggal'] = $fullDate;       
            
            $tgl_loop = $year.'-'.$month.'-'.$i; 
            $presensi_gym = Presensi_gym::where('tgl_presensi_gym',$tgl_loop)->get();

            $dataLaporan['jumlah_member'] = count($presensi_gym);
            $total = $total + count($presensi_gym);
            $laporan[$i] = $dataLaporan;
            
        }      
       
        if (!is_null($laporan)) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $laporan,
                'tahun' => $year,
                'bulan' => $bulan,
                'tanggal_cetak' => $tanggal_cetak,
                'total_all' => $total
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function laporanKelasBulanan($month)
    {
        $year = Carbon::now()->format('Y');
        $firstDayOfMonth = Carbon::createFromDate(null, $month, 1);

        $kelas = Kelas::orderBy('nama_kelas','asc')->get();
        $bulan = $firstDayOfMonth->format('F');
        $tanggal_cetak = Carbon::now()->format('d-M-Y');

        $jadwal_harian = [];
        $laporan = collect([]);

        try{
        foreach($kelas as $data_kelas){
            $jadwal_harian = Jadwal_harian::where('id_kelas','=',$data_kelas['id_kelas'])->whereMonth('tgl_kelas', $month)->get();

            if(count($jadwal_harian) != 0){
                if(count($jadwal_harian) > 1){
                    // berarti ada instruktur lain
                    foreach($jadwal_harian as $data_jadwal_harian){

                        $presensi_kelas = Presensi_kelas::where('id_jadwal_harian', '=', $data_jadwal_harian['id_jadwal_harian'])
                                                            ->where('status_kehadiran', '=', 'HADIR')->get();
                        $jadwal_libur = Jadwal_harian::where('id_jadwal_harian','=',$data_jadwal_harian['id_jadwal_harian'])
                                            ->where('keterangan','=','LIBUR')->get();
                        $data_laporan['nama_kelas'] = $data_kelas['nama_kelas'];
                        $data_laporan['instruktur'] = Instruktur::where('id_instruktur', '=', $data_jadwal_harian['id_instruktur'])->first();
                        $data_laporan['jumlah_peserta'] = count($presensi_kelas);
                        $data_laporan['jumlah_libur'] = count($jadwal_libur);
                        // $laporan[$data_kelas['nama_kelas']] = $data_laporan;
                        $laporan->add($data_laporan);
                    }                 
                }else{
                    
                    $presensi_kelas = Presensi_kelas::where('id_jadwal_harian', '=', $jadwal_harian[0]['id_jadwal_harian'])
                                                        ->where('status_kehadiran', '=', 'HADIR')->get();
                    $jadwal_libur = Jadwal_harian::where('id_jadwal_harian','=',$jadwal_harian[0]['id_jadwal_harian'])
                                        ->where('keterangan','=','LIBUR')->get();
                    $data_laporan['nama_kelas'] = $data_kelas['nama_kelas'];
                    $data_laporan['instruktur'] = Instruktur::where('id_instruktur', '=', $jadwal_harian[0]['id_instruktur'])->first();
                    $data_laporan['jumlah_peserta'] = count($presensi_kelas);
                    $data_laporan['jumlah_libur'] = count($jadwal_libur);
                    // $laporan[$data_kelas['nama_kelas']] = $data_laporan;
                    $laporan->add($data_laporan);
                }
            }else{
                return response([
                    'message' => 'Jadwal Harian bulan '.$bulan.' belum ada',
                    'data' => $jadwal_harian
                ], 400);
            }
        }
    }catch(ErrorException $e){
        return response([
            'message' => 'Retrieve All Success',
            'data' => $e->getMessage(),
            'jadwal_harian' => $jadwal_harian
        ], 400);
    }
       
        if (!is_null($laporan)) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $laporan,
                'bulan' => $bulan,
                'tahun' => $year,
                'tanggal_cetak' => $tanggal_cetak
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function laporanKinerjaInstruktur()
    {
        $year = Carbon::now()->format('Y');
        // $firstDayOfMonth = Carbon::createFromDate(null, $month, 1);
        $month = Carbon::now()->format('m');

        $instukrur = Instruktur::orderBy('keterlambatan','asc')->get();

        $bulan = Carbon::now()->format('F');
        $tanggal_cetak = Carbon::now()->format('d-M-Y');
        $data_laporan['jumlah_hadir'] = 0;
        $jadwal_harian = [];
        $laporan = collect([]);

        foreach($instukrur as $data_instruktur){
                $data_laporan['nama_instruktur'] = $data_instruktur['nama'];
                $jadwal_harian = Jadwal_harian::where('id_instruktur','=',$data_instruktur['id_instruktur'])
                                                    ->whereMonth('tgl_kelas',$month)->get();
                
                if(count($jadwal_harian) > 1){
                  foreach($jadwal_harian as $data_jadwal_harian){
                    $jumlah_hadir = Presensi_instruktur::where('id_jadwal_harian', '=', $data_jadwal_harian['id_jadwal_harian'])
                                                            ->whereMonth('created_at', $month)->get();
                    $data_laporan['jumlah_hadir'] = $data_laporan['jumlah_hadir'] + count($jumlah_hadir);
                  }  
                }else if(count($jadwal_harian) == 1 ){
                    $jumlah_hadir = Presensi_instruktur::where('id_jadwal_harian', '=', $jadwal_harian[0]['id_jadwal_harian'])
                                                            ->whereMonth('created_at', $month)->get();
                    $data_laporan['jumlah_hadir'] = count($jumlah_hadir);
                }else{
                    return response([
                        'message' => 'Jadwal Harian bulan '.$month.' belum ada',
                        'data' => $jadwal_harian
                    ], 400);
                }

                $jumlah_libur = Jadwal_harian::where('id_instruktur', '=', $data_instruktur['id_instruktur'])
                                                ->where('keterangan','=','LIBUR')
                                                ->whereMonth('tgl_kelas', $month)->get();

                $data_laporan['jumlah_libur'] = count($jumlah_libur);

                $keterlambatan = $data_instruktur['keterlambatan'];
                $detik_terlambat = strtotime($keterlambatan) - strtotime('00:00:00');
                $data_laporan['waktu_terlambat'] = $detik_terlambat;

                $laporan->add($data_laporan);
        }
       
        // $laporan = $laporan::orderBy('waktu_terlambat','asc')->get();

        if (!is_null($laporan)) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $laporan,
                'bulan' => $bulan,
                'tahun' => $year,
                'tanggal_cetak' => $tanggal_cetak
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    // function cetakLaporan($laporan){ 
    //     $pdf = PDF::loadview('laporanPendapatan',['laporan'=>$laporan]);
    //     return $pdf->download('laporan-pendapatan.pdf');
    // }
}
