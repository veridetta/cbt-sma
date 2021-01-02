<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminController extends Controller
{
    //

   public function peserta(){
       $week = date('W');
       $minggu = DB::table('users')->where(DB::raw('WEEK(created_at,3)=$week'));
       //$minggu=DB::select("select * from users where WEEK(created_at, 3)='$week'");
       return view('/admin/peserta');
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
        );
        $totalData = $users->count ();            //Total record
        $totalFiltered = $totalData;      // No filter at first so we can assign like this
        // Here are the parameters sent from client for paging 
        $start = $request->input ( 'start' );           // Skip first start records
        $length = $request->input ( 'length' );   //  Get length record from start
        /*
         * Where Clause
         */
        if ($request->has ( 'search' )) {
            if ($request->input ( 'search.value' ) != '') {
                $searchTerm = $request->input ( 'search.value' );
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
        if ($request->has ( 'order' )) {
            if ($request->input ( 'order.0.column' ) != '') {
                $orderColumn = $request->input ( 'order.0.column' );
                $orderDirection = $request->input ( 'order.0.dir' );
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
                "recordsTotal" => intval ( $totalData ), // total number of records
                "recordsFiltered" => intval ( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data
        );
        return $tableContent;
    }
}
