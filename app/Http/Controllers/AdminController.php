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
}
