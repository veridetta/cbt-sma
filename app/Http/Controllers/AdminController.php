<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Carbon\Carbon;

class AdminController extends Controller
{
    //
/*$users = User::whereMonth('created_at', date('m'))->get();
//or you could also just use $carbon = \Carbon\Carbon::now(); $carbon->month;
//select * from `users` where month(`created_at`) = "04"
$users = User::whereDay('created_at', date('d'))->get();
//or you could also just use $carbon = \Carbon\Carbon::now(); $carbon->day;
//select * from `users` where day(`created_at`) = "03"
$users = User::whereYear('created_at', date('Y'))->get();
//or you could also just use $carbon = \Carbon\Carbon::now(); $carbon->year;
//select * from `users` where year(`created_at`) = "2017"
*/
   public function peserta(){
       $week = date('W');
       Carbon::setWeekStartsAt(Carbon::MONDAY);
       Carbon::setWeekEndsAt(Carbon::SUNDAY);
       $minggu = DB::table('users')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('role','user');
       $sekolah = DB::table('users')->select('users.*', DB::raw('COUNT(*) as total'))->where('role','user')->groupBy('sekolah');
       //$minggu=DB::select("select * from users where WEEK(created_at, 3)='$week'");
       return view('/admin/peserta',['minggu'=>$minggu,'sekolah'=>$sekolah]);
   }

    public function usersByGroupDatatables(Request $request){
        // The columns variable is used for sorting
        $columns = array (
                // datatable column index => database column name
                
                0 =>'name',
                1 =>'email',
                2 =>'hp',
                3 =>'sekolah',
                4 =>'created_at',
                5 =>'id',
        );
        //Getting the data
        $users = DB::table ( 'users' )
        ->select ('users.id',
            'users.name',
            'users.email',
            'users.hp',
            'users.sekolah',
            'users.created_at',
        )->where('role','user');
        $totalData = $users->count ();            //Total record
        $totalFiltered = $totalData;      // No filter at first so we can assign like this
        // Here are the parameters sent from client for paging 
        
        if ($request->has ( 'offset' )) {
            $start = $request->input ( 'offset' );           // Skip first start records
        }else{
            $start = 0;

        }
        $length = 10;   //  Get length record from start
        /*
         * Where Clause
         */
        if ($request->has ( 'search' )) {
            if ($request->input ( 'search' ) != '') {
                $searchTerm = $request->input ( 'search' );
                /*
                * Seach clause : we only allow to search on user_name field
                */
                $users->where ( 'users.name', 'Like', '%' . $searchTerm . '%' );
            }
        }
        /*
         * Order By
         */
        // Data to client
        $jobs = $users->skip ( $start )->take ( $length );
        if ($request->has ( 'sort' )) {
            if ($request->input ( 'sort' ) != '') {
                $orderColumn = $request->input ( 'sort' );
                $orderDirection = $request->input ( 'order' );
                $jobs->orderBy ( $columns [intval ( $orderColumn )], $orderDirection );
            }
        }
        // Get the real count after being filtered by Where Clause
        $totalFiltered = $users->count ();
        

        /*
         * Execute the query
         */
        $users = $users->get ();
        /*
        * We built the structure required by BootStrap datatables
        */
        $data = array ();
        $no=1;
        foreach ( $users as $user ) {
            $nestedData = array ();
            $nestedData ['no'] = $no;
            $nestedData ['name'] = $user->name;
            $nestedData ['email'] = $user->email;
            $nestedData ['hp'] = $user->hp;
            $nestedData ['sekolah'] = $user->sekolah;
            $nestedData ['created_at'] = $user->created_at;
            $nestedData ['id'] = $user->id;
            $data [] = $nestedData;
            $no++;
        }
        /*
        * This below structure is required by Datatables
        */ 
        $tableContent = array (
                "draw" => intval ( $request->input ( 'draw' ) ), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "total" => intval ( $totalData ), // total number of records
                "totalNotFiltered" => intval ( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "rows" => $data
        );
        return $tableContent;
    }

    public function dashboard_soal(){
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
        $pak=DB::table('peserta_paket as u')->join('paket_soal as p','p.id','u.paket_soal_id')->select('u.paket_soal_id','p.nama',DB::raw('count(*) as total'),'p.tgl_mulai')->groupBy('u.paket_soal_id');
        return view('admin/dashboard_soal',['arr_to'=>$arr_to,'arr_pendaftar_to'=>$arr_pend,'paket'=>$pak]);
    }

    public function paket_soal(){
        $p=DB::table('paket_soal as p')->join('kategori_soal as k','k.id','p.kategori')->select('k.nama as kategori_nama','p.*')->orderBy('p.id','desc');
        $arr_to=array();
        foreach($p->get() as $pa){
            $total=0;
            $po=DB::table('peserta_paket')->select('peserta_paket.*',DB::raw('count(*) as total'))->groupBy('paket_soal_id')->where('paket_soal_id',$pa->id)->get();
            foreach($po as $poo){
                $total=$poo->total;
            }
            $x['id']=$pa->id;
            $x['nama']=$pa->nama;
            $x['kategori_nama']=$pa->kategori_nama;
            $x['total']=$total;
            $x['kategori']=$pa->kategori;
            $x['bintang']=$pa->bintang;
            $x['keterangan']=$pa->keterangan;
            $x['status']=$pa->status;
            $x['voucher']=$pa->voucher;
            $x['tgl_mulai']=$pa->tgl_mulai;
            $x['tgl_selesai']=$pa->tgl_selesai;
            array_push($arr_to,$x);
        }
        //$pak=DB::table('paket_soal as p')->join('peserta_paket as u','u.paket_soal_id','p.id')->join('kategori_soal as k','k.id','p.kategori')->select('u.paket_soal_id','k.nama as kategori_nama','p.*',DB::raw('count(*) as total'))->orderBy('p.id','desc')->groupBy('u.paket_soal_id');
        $kat=DB::table('kategori_soal')->orderBy('nama');
        return view('admin/paket_soal',['pak'=>$arr_to,'kat'=>$kat]);
    }
    public function sesi_soal(){
        return view('admin/sesi_soal');
    }
    public function list_soal(Request $request){
        $sesi = $request->segment(3);
        $paket = $request->segment(4);
        $qu=DB::table('soal')->where('id_paket_soal',$paket)->where('id_sesi_soal',$sesi)->orderBy('id');
        $da=DB::table('sesi_soal')->where('id',$sesi);
        $dat=$da->first();
        return view('admin/list_soal',['sesi'=>$sesi,'paket'=>$paket,'dat'=>$dat,'qu'=>$qu]);
    }
    public function input_soal(Request $request){
        $sesi = $request->segment(3);
        $paket = $request->segment(4);
        $nomor = $request->segment(5);
        $a_so=DB::table('soal')->where('id',$nomor);
        $da=DB::table('sesi_soal')->where('id',$sesi);
        $x=array(
            'isi'=>""
        );
        if($a_so->count()>0){
            $dat=$a_so->first();
            $x['isi']=$dat->isi;
            $x['pembahasan']=$dat->pembahasan;
            $x['kunci']=$dat->kunci;
            $x['a']=$dat->a;
            $x['b']=$dat->b;
            $x['c']=$dat->c;
            $x['d']=$dat->d;
            $x['e']=$dat->e;
        }else{
            $x['isi']="Masukan Soal";
            $x['pembahasan']="Masukan Pembahasan";
            $x['kunci']="";
            $x['a']="Opsi A";
            $x['b']="Opsi B";
            $x['c']="Opsi C";
            $x['d']="Opsi D";
            $x['e']="Opsi D";
            
        }
        $dat=$da->first();
        return view('admin/buat_soal',['sesi'=>$sesi,'paket'=>$paket,'idnomor'=>$nomor,'a_so'=>$a_so,'arr_soal'=>$x]);
    }
    public function import_soal(Request $request){
        $sesi = $request->segment(3);
        $paket = $request->segment(4);
        $nomor = $request->segment(5);
        $da=DB::table('sesi_soal as ss')->join('paket_soal as ps','ps.id','ss.id_paket_soal')->select('ss.nama_sesi as nama_sesi','ps.nama as nama_paket')->where('ss.id',$sesi);
        return view('admin/import_soal',['sesi'=>$sesi,'paket'=>$paket,'idnomor'=>$nomor,'data'=>$da->first()]);
    }
    public function nilai_siswa(){
        return view('admin.nilai_siswa');
    }
    public function nilai_siswa_detail(Request $request){
        $sis=DB::table('users')->where('id',$request->id_siswa);
        $to=DB::table('peserta_paket as pp')->join('paket_soal as ps','ps.id','pp.paket_soal_id')->select('ps.nama','ps.id')->where('pp.id_user',$request->id_siswa);
        return view('admin.nilai_siswa_detail',['id_siswa'=>$request->id_siswa,'siswa'=>$sis->first(),'paket'=>$to]);
    }
}

