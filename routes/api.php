<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::post('loginMobile', 'App\Http\Controllers\AuthController@loginMobile');

Route::get('instruktur', 'App\Http\Controllers\InstrukturController@index');
Route::get('instruktur/{id}', 'App\Http\Controllers\InstrukturController@show');
Route::post('instruktur', 'App\Http\Controllers\InstrukturController@store');
Route::put('instruktur/{id}', 'App\Http\Controllers\InstrukturController@update');
Route::delete('instruktur/{id}', 'App\Http\Controllers\InstrukturController@destroy');
Route::post('resetKeterlambatan', 'App\Http\Controllers\InstrukturController@resetKeterlambatan');

Route::get('member', 'App\Http\Controllers\MemberController@index');
Route::get('member/{id}', 'App\Http\Controllers\MemberController@show');
Route::post('member', 'App\Http\Controllers\MemberController@store');
Route::put('member/{id}', 'App\Http\Controllers\MemberController@update');
Route::delete('member/{id}', 'App\Http\Controllers\MemberController@destroy');
Route::get('member/resetPass/{id}', 'App\Http\Controllers\MemberController@resetPassword');
Route::get('nonActiveMember', 'App\Http\Controllers\MemberController@showNotActive');
Route::get('expiredMember', 'App\Http\Controllers\MemberController@getExpiredMember');
Route::put('member/deactivate/{id}', 'App\Http\Controllers\MemberController@deactivateMember');

Route::get('kelas', 'App\Http\Controllers\KelasController@index');
Route::get('kelas/{id}', 'App\Http\Controllers\KelasController@show');
Route::post('kelas', 'App\Http\Controllers\KelasController@store');
Route::put('kelas/{id}', 'App\Http\Controllers\KelasController@update');
Route::delete('kelas/{id}', 'App\Http\Controllers\KelasController@destroy');

Route::get('depositKelas', 'App\Http\Controllers\DepositKelasController@index');
Route::get('depositKelas/{id_member}', 'App\Http\Controllers\DepositKelasController@getDepositKelasByMember');

Route::post('role', 'App\Http\Controllers\RoleController@store');

Route::get('pegawai', 'App\Http\Controllers\PegawaiController@index');
Route::get('pegawai/{id}', 'App\Http\Controllers\PegawaiController@show');
Route::post('pegawai', 'App\Http\Controllers\PegawaiController@store');
Route::put('pegawai/{id}', 'App\Http\Controllers\PegawaiController@update');
Route::delete('pegawai/{id}', 'App\Http\Controllers\PegawaiController@destroy');

Route::get('jadwalUmum', 'App\Http\Controllers\JadwalUmumController@index');
Route::get('jadwalUmum/{id}', 'App\Http\Controllers\JadwalUmumController@show');
Route::post('jadwalUmum', 'App\Http\Controllers\JadwalUmumController@store');
Route::put('jadwalUmum/{id}', 'App\Http\Controllers\JadwalUmumController@update');
Route::delete('jadwalUmum/{id}', 'App\Http\Controllers\JadwalUmumController@destroy');

Route::get('jadwalHarian', 'App\Http\Controllers\JadwalHarianController@index');
Route::get('jadwalHarian/{id}', 'App\Http\Controllers\JadwalHarianController@show');
Route::get('jadwalHarianByDate/{date}', 'App\Http\Controllers\JadwalHarianController@getJadwalHarianByDate');
Route::get('jadwalKelasInstrukturToday/{id_instruktur}/{date}', 'App\Http\Controllers\JadwalHarianController@getJadwalHarianTodayByInstruktur');
Route::post('jadwalHarian', 'App\Http\Controllers\JadwalHarianController@store');
Route::put('jadwalHarian/{id}', 'App\Http\Controllers\JadwalHarianController@update');
Route::delete('jadwalHarian/{id}', 'App\Http\Controllers\JadwalHarianController@destroy');

Route::get('transaksiAktivasi', 'App\Http\Controllers\TransaksiAktivasi@index');
Route::get('transaksiAktivasi/{id}', 'App\Http\Controllers\TransaksiAktivasi@show');
Route::post('transaksiAktivasi', 'App\Http\Controllers\TransaksiAktivasi@store');
Route::put('transaksiAktivasi/{id}', 'App\Http\Controllers\TransaksiAktivasi@update');
Route::delete('transaksiAktivasi/{id}', 'App\Http\Controllers\TransaksiAktivasi@destroy');

Route::get('transaksiDepoU', 'App\Http\Controllers\TransaksiDepoUController@index');
Route::get('transaksiDepoU/{id}', 'App\Http\Controllers\TransaksiDepoUController@show');
Route::post('transaksiDepoU', 'App\Http\Controllers\TransaksiDepoUController@store');
Route::put('transaksiDepoU/{id}', 'App\Http\Controllers\TransaksiDepoUController@update');
Route::delete('transaksiDepoU/{id}', 'App\Http\Controllers\TransaksiDepoUController@destroy');

Route::get('transaksiDepoK', 'App\Http\Controllers\TransaksiDepositKController@index');
Route::get('transaksiDepoK/{id}', 'App\Http\Controllers\TransaksiDepositKController@show');
Route::post('transaksiDepoK', 'App\Http\Controllers\TransaksiDepositKController@store');
Route::put('transaksiDepoK/{id}', 'App\Http\Controllers\TransaksiDepositKController@update');
Route::delete('transaksiDepoK/{id}', 'App\Http\Controllers\TransaksiDepositKController@destroy');

Route::get('izinInstruktur', 'App\Http\Controllers\IzinInstrukturController@index');
Route::get('izinInstruktur/{id}', 'App\Http\Controllers\IzinInstrukturController@show');
Route::post('izinInstruktur', 'App\Http\Controllers\IzinInstrukturController@store');
Route::put('izinInstruktur/confirm/{id}', 'App\Http\Controllers\IzinInstrukturController@confirmIzin');
Route::get('izinInstruktur/byInstruktur/{id}', 'App\Http\Controllers\IzinInstrukturController@getIzinByIdInstruktur');

Route::get('getExpiredDepoK', 'App\Http\Controllers\DepositKelasController@getExpiredDepositKelas');
Route::get('resetDepositKelas/{id_member}/{id_kelas}', 'App\Http\Controllers\DepositKelasController@resetDepositKelas');

Route::post('presensiInstruktur', 'App\Http\Controllers\PresensiInstrukturController@store');
Route::put('presensiInstruktur/updateJamSelesai/{id_jadwal_harian}', 'App\Http\Controllers\PresensiInstrukturController@updateJamSelesai');
Route::get('presensiInstruktur/checkUpdateJamMulai/{id_jadwal_harian}', 'App\Http\Controllers\PresensiInstrukturController@checkUpdateJamMulai');

Route::get('bookingGym', 'App\Http\Controllers\BookingGymController@index');
Route::get('bookingGymToday', 'App\Http\Controllers\BookingGymController@getBookingGymToday');
Route::post('bookingGym', 'App\Http\Controllers\BookingGymController@store');
Route::get('cancelBookingGym/{id_member}/{sesi}/{tgl_booking}', 'App\Http\Controllers\BookingGymController@cancelBookingGym');

Route::get('bookingKelas', 'App\Http\Controllers\BookingKelasController@index');
Route::post('bookingKelas', 'App\Http\Controllers\BookingKelasController@store');
Route::get('bookingKelas/{id_member}', 'App\Http\Controllers\BookingKelasController@getByIdMember');
Route::get('bookingKelas/getByIdJadwalHarian/{id_jadwal_harian}', 'App\Http\Controllers\BookingKelasController@getByIdJadwalHarian');
Route::get('cancelBookingKelas/{id_jadwal_harian}/{id_member}/{tgl_booking_kelas}', 'App\Http\Controllers\BookingKelasController@cancelBookingKelas');

Route::get('presensiGym', 'App\Http\Controllers\PresensiGymController@index');
Route::post('presensiGym/{presensi}', 'App\Http\Controllers\PresensiGymController@store');

Route::get('presensiKelas', 'App\Http\Controllers\PresensiKelasController@index');
Route::get('presensiKelas/{id}', 'App\Http\Controllers\PresensiKelasController@show');
Route::post('presensiKelas', 'App\Http\Controllers\PresensiKelasController@store');
Route::get('updateDepositMember/{id_jadwal_harian}', 'App\Http\Controllers\PresensiKelasController@updateDepositMember');

Route::get('laporanPendapatan', 'App\Http\Controllers\LaporanController@laporanPendapatan');
Route::get('laporanGymBulanan/{month}', 'App\Http\Controllers\LaporanController@laporanGymBulanan');
Route::get('laporanKelasBulanan/{month}', 'App\Http\Controllers\LaporanController@laporanKelasBulanan');
Route::get('laporanKinerjaInstruktur', 'App\Http\Controllers\LaporanController@laporanKinerjaInstruktur');
Route::get('cetakPdf', 'App\Http\Controllers\LaporanController@cetakLaporan');

Route::get('historyTransaksi/{id_member}', 'App\Http\Controllers\HistoryController@historyTransaksiMember');
Route::get('historyKelas/{id_member}', 'App\Http\Controllers\HistoryController@historyKelasMember');
Route::get('historyInstruktur/{id_instruktur}', 'App\Http\Controllers\HistoryController@historyInstruktur');
