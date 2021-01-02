<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/siswa', [RoleController::class,'siswa'])->middleware('role:user');
Route::get('/admin',  [RoleController::class,'admin'])->middleware('role:admin');
Route::get('/redirect',  [RoleController::class,'index']);
Route::get('logout', [RoleController::class,'logout']);

//ADMIN
Route::get('/dashboard/peserta', [AdminController::class,'peserta'])->middleware('role:admin');
Route::post('/peserta/datatables',  [AdminController::class,'usersByGroupDatatables'])->middleware('role:admin');
//SISWA
Route::get('/siswa/payment/topup',[PaymentController::class,'topup'])->middleware('role:user');;
Route::get('/siswa/payment/generate',[PaymentController::class,'generate'])->middleware('role:user');;
Route::get('/siswa/payment/update',[PaymentController::class,'update'])->middleware('role:user');;
Route::get('/siswa/payment/cara',[PaymentController::class,'cara'])->middleware('role:user');;
Route::post('/siswa/action/daftar_to', [AjaxController::class, 'daftar_TO'])->middleware('role:user');;
Route::post('/siswa/analisis', [ProfileController::class, 'analisis'])->middleware('role:user');;

//SOAL
Route::post('/siswa/pembahasan',[ProfileController::class,'pembahasan'])->middleware('role:user');;
Route::get('/siswa/soal/nav-bahas',[SoalController::class,'navbahas'])->middleware('role:user');;
Route::get('/siswa/soal/soal-bahas',[SoalController::class,'soalbahas'])->middleware('role:user');;
Route::post('/siswa/soal/bahas-launch',[SoalController::class,'bahaslaunch'])->middleware('role:user');;

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
