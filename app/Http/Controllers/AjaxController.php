<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AjaxController extends Controller
{
    //
    public function daftar_TO(Request $request){
        $id=Auth::user()->id;
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "judul"=>'',
            "success" => false);
        $user=DB::table('riwayat_bintang')->where('id_users',$id)->orderBy('id','desc')->first();
        if(Str::length($request->voucher)>0){
            $paket=DB::table('paket_soal')->where('id',$request->id_paket_soal)->where('voucher',$request->voucher);
            if($paket->count()>0){
                $sisa=$user->saldo;
                $riwayat=DB::table('riwayat_bintang')->insert([
                    'id_user'   => $id,
                    'nominal'   => $paket->harga,
                    'status'    => '2',
                    'saldo'     => $sisa
                ]);
                if($riwayat){
                    $insert=DB::table('peserta_paket')->insert([
                        'id_user'   => $id,
                        'id_paket'  => $request->id_paket_soal,
                        'status'    => '1'
                    ]);
                    if($insert){
                        $pesan = "Pembayaran Berhasil, kamu akan segera di alihkan ";
                        $judul="Pembelian berhasil";
                        $return_arr['pesan']=$pesan;
                        $return_arr['judul']=$judul;
                        $return_arr['success']=true;
                        $output = json_encode($return_arr);
                        die($output);
                    }else{
                        $pesan = "Error tidak diketahui ";
                        $judul="Pembelian gagal";
                        $return_arr['pesan']=$pesan;
                        $return_arr['judul']=$judul;
                        $return_arr['success']=false;
                        $output = json_encode($return_arr);
                        die($output);
                    }
                }else{
                    $pesan = "Error tidak diketahui ";
                    $judul="Pembelian gagal";
                    $return_arr['pesan']=$pesan;
                    $return_arr['judul']=$judul;
                    $return_arr['success']=false;
                    $output = json_encode($return_arr);
                    die($output);
                }
            }else{
                $pesan = "Kode Voucher yang dimasukkan tidak berlaku, silahkan periksa kembali kode voucher anda, atau silahkan lakukan pembelian tanpa menggunakan voucher";
                $judul="Pembelian gagal";
                $return_arr['pesan']=$pesan;
                $return_arr['judul']=$judul;
                $return_arr['success']=false;
                $output = json_encode($return_arr);
                die($output);
            }
        }else{
            $paket=DB::table('paket_soal')->where('id',$request->id_paket_soal)->first();
            if($user->saldo>$paket->harga){
                $sisa=$user->saldo-$paket->harga;
                $riwayat=DB::table('riwayat_bintang')->insert([
                    'id_user'   => $id,
                    'nominal'   => $paket->harga,
                    'status'    => '2',
                    'saldo'     => $sisa
                ]);
                if($riwayat){
                    $insert=DB::table('peserta_paket')->insert([
                        'id_user'   => $id,
                        'id_paket'  => $request->id_paket_soal,
                        'status'    => '1'
                    ]);
                    if($insert){
                        $pesan = "Pembayaran Berhasil, kamu akan segera di alihkan ";
                        $judul="Pembelian berhasil";
                        $return_arr['pesan']=$pesan;
                        $return_arr['judul']=$judul;
                        $return_arr['success']=true;
                        $output = json_encode($return_arr);
                        die($output);
                    }else{
                        $pesan = "Error tidak diketahui ";
                        $judul="Pembelian gagal";
                        $return_arr['pesan']=$pesan;
                        $return_arr['judul']=$judul;
                        $return_arr['success']=false;
                        $output = json_encode($return_arr);
                        die($output);
                    }
                }else{
                    $pesan = "Error tidak diketahui ";
                    $judul="Pembelian gagal";
                    $return_arr['pesan']=$pesan;
                    $return_arr['judul']=$judul;
                    $return_arr['success']=false;
                    $output = json_encode($return_arr);
                    die($output);
                }
            }else{
                $pesan = "Bintang kamu tidak mencukupi ";
                $judul="Pembelian gagal";
                $return_arr['pesan']=$pesan;
                $return_arr['judul']=$judul;
                $return_arr['success']=false;
                $output = json_encode($return_arr);
                die($output);
            }
        }
        return response()->json(['success'=>true,'pesan'=>$request->id_user,'judu'=>'Judul']);
    }
}
