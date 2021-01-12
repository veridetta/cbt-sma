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
        $paket=DB::table('paket_soal as ps')->join('kategori_soal as ks','ks.id','ps.kategori')->select('ps.*','ks.nama as nama_kategori')->where('ps.status','<',3)->orderBy('ps.id');
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
        $history=DB::table('paket_soal as ps')->join('kategori_soal as ks','ks.id','ps.kategori')->where('ps.status','>','2')->select('ps.*','ks.nama as nama_kategori')->orderBy('ps.id','DESC');
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
        $week = date('W');
        $df=DB::select("select * from users where WEEK(created_at, 3)='$week'");
        $dfbaru=count($df);
        $paket=DB::table('paket_soal')->orderBy('id','desc');
        $total_paket=$paket->count();
        $paket_aktif=$paket->first();
        $terdaftar=DB::table('peserta_paket')->where('paket_soal_id',$paket_aktif->id);
        $total_terdaftar=$terdaftar->count();
        $pemasukan=DB::table('riwayat_bintang as r')->join('users as u','u.id','r.id_users')->join('harga_paket as h','h.jumlah','r.nominal')->select('r.id','r.status','r.nominal','r.saldo','r.tgl','u.name','u.id','h.nominal as uang','h.jumlah');
        $pemasukan_ini=$pemasukan->get();
        $total_pemasukan_ini=0;
        foreach($pemasukan_ini as $pemasukan_hari){
          $total_pemasukan_ini+=$pemasukan_hari->uang;
        }
        $pend=DB::table('paket_soal as p')->join('peserta_paket as u','u.paket_soal_id','p.id')->select('p.nama',DB::raw('count(*) as total'),'p.id')->groupBy('p.id');
        $pendaftar=$pend->get();
        $arr_pend=array();
        $arr_to=array();
        foreach($pendaftar as $pendaftar_to){
          $x=$pendaftar_to->total;
          $y=$pendaftar_to->nama;
          array_push($arr_pend,$x);
          array_push($arr_to,$y);
        }
        return view('admin.admin',['dfbaru'=>$dfbaru,'total_terdaftar'=>$total_terdaftar,'total_paket'=>$total_paket,'pemasukan_ini'=>$total_pemasukan_ini,'arr_to'=>$arr_to,'arr_pendaftar_to'=>$arr_pend]);
        
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
