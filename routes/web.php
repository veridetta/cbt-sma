<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SoalController;

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

//SISWA
Route::get('/siswa/payment/topup',[PaymentController::class,'topup']);
Route::get('/siswa/payment/generate',[PaymentController::class,'generate']);
Route::get('/siswa/payment/update',[PaymentController::class,'update']);
Route::get('/siswa/payment/cara',[PaymentController::class,'cara']);
Route::post('/siswa/action/daftar_to', [AjaxController::class, 'daftar_TO']);
Route::post('/siswa/analisis', [ProfileController::class, 'analisis']);

//SOAL
Route::post('/siswa/pembahasan',[ProfileController::class,'pembahasan']);
Route::get('/siswa/soal/nav-bahas',[SoalController::class,'navbahas']);
Route::get('/siswa/soal/soal-bahas',[SoalController::class,'soalbahas']);
Route::post('/siswa/soal/bahas-launch',[SoalController::class,'bahaslaunch']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
