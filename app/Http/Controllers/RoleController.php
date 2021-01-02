<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Paket_soal;
use App\Models\Peserta_paket;
class RoleController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
      }
      public function siswa() {
        $id_users= Auth::user()->id;
        $bin = DB::select('select * from riwayat_bintang where id_users = ? order by id desc limit 1', [$id_users]);
        $pem=DB::select('select * from riwayat_bintang where id_users = ? order by id desc', [$id_users]);
        //$paket=DB::table('paket_soal')->where('status','<','3')->orderBy('id','DESC');
        $paket=Paket_soal::where('status','<',3)->orderBy('id','DESC');
        $user_p=DB::table('peserta_paket')
                ->join('paket_soal','paket_soal.id','=','peserta_paket.paket_soal_id')
                ->select('paket_soal.*','peserta_paket.status as UserStatus')
                ->where('peserta_paket.id_user','=',$id_users)
                ->where('paket_soal.status','<','3')
                ->orderBy('paket_soal.id','DESC');
        $get_paket=$paket->get();
        $adapaket=$paket->count();
        $ada_user_paket=$user_p->count();
        $user_paket=$user_p->get();
        //history
        //$history=DB::table('paket_soal')->where('status','>','2')->orderBy('id','DESC');
        $history=Paket_soal::where('status','>','2')->orderBy('id','DESC');
        $user_h=DB::table('peserta_paket')
                ->join('paket_soal','paket_soal.id','=','peserta_paket.paket_soal_id')
                ->select('peserta_paket.status as UserStatus','paket_soal.*')
                ->where('peserta_paket.id_user','=',$id_users)
                ->where('paket_soal.status','>','2')
                ->orderBy('paket_soal.id','DESC');
        $get_history=$history->get();
        $adahistory=$history->count();
        $ada_user_history=$user_h->count();
        $user_history=$user_h->get();

        return view('siswa.siswa', ['bintang' => $bin,'pembelian'=>$pem,'jumlah_paket'=>$adapaket,'paket'=>$get_paket,'jumlah_user'=>$ada_user_paket,'user_paket'=>$user_paket,'jumlah_history'=>$adahistory,'history'=>$get_history,'jumlah_user_history'=>$ada_user_history,'user_history'=>$user_history]);
      }
      public function admin() {
        return view('admin.admin');
      }
      public function index()
      {
          $role = Auth::user()->role;
          switch ($role) {
            case 'admin':
              return redirect('/admin');
              break;
            case 'user':
              return redirect('/siswa');
              break; 
        
            default:
              return redirect('/'); 
            break;
          }
    
      }
      public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
      }
}
