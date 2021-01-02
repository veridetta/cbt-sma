<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
class ProfileController extends Controller
{
    //
    public function bintang()
    {
        $id_users= Auth::user()->id;
        $bin = DB::select('select * from riwayat bintang where id_users = ? order by id desc limit 1', [$id_users]);
        return view('siswa.siswa', ['bintang' => $bin]);
    }

    public function analisis(Request $request){
        $id=Auth::user()->id;
        $gagal=0;
        $tp=DB::table('paket_soal')->where('status','4')->where('id',$request->id_paket);
        $tps=$tp->first();
        if($tp->count()<1){
            $gagal=1;
        }
        $ce=DB::table('nilai_siswa')->where('id_siswa',$id)->where('id_paket',$request->id_paket);
        $hi=$ce->count();
        if($hi<1){
            $so=DB::table('sesi_soal')->where('id_paket_soal',$request->id_paket);
            $rank=0;
            foreach($so->get() as $soal){
                $jawa=DB::table('user_jawaban')->join('soal','soal.id','=','user_jawaban.id_soal')->select('user_jawaban.id_paket', 'user_jawaban.kunci', 'user_jawaban.id_siswa','user_jawaban.jawabanSiswa','user_jawaban.id_soal','soal.id','soal.score')->where('user_jawaban.id_sesi',$soal->id)->where('user_jawaban.id_siswa',$id);
                $jawaq=$jawa->count();
                $nilai=0;
                $benar=0;
                $salah=0;
                foreach($jawa->get() as $jawaban){
                    if($jawaban->jawabanSiswa==$jawaban->kunci){
                        $nilai+=$jawaban->score;
                        $benar++;
                    }else{
                        $salah++;
                    }
                }
                $in=DB::table('nilai_siswa')->insert(['id_siswa'=>$id,'id_paket'=>$request->id_paket,'id_soal'=>$soal->id,'benar'=>$benar,'salah'=>$salah,'nilai'=>$nilai,'nama_sesi'=>$soal->nama_sesi]);
                $rank+=$nilai;
                if($in){
                     $nilai=0;
                     $benar=0;
                     $salah=0;
                     
                }
            }
        }
        $nila=DB::table('nilai_siswa')->join('sesi_soal','sesi_soal.id','=','nilai_siswa.id_soal')->select('nilai_siswa.*','sesi_soal.id','sesi_soal.induk_sesi','sesi_soal.nama_sesi')->where('nilai_siswa.id_siswa',$id)->where('nilai_siswa.id_paket',$request->id_paket)->where('sesi_soal.induk_sesi','TPS');
        $niq=$nila->count();
        $mapel=array();
        $nilaie=array();
        $detail=$nila->get();
        foreach($nila->get() as $nilai){
            $mapel[]=$nilai->nama_sesi;
            $nilaie[]=$nilai->nilai;
        }
        $nila2=DB::table('nilai_siswa')->join('sesi_soal','sesi_soal.id','=','nilai_siswa.id_soal')->select('nilai_siswa.*','sesi_soal.id','sesi_soal.induk_sesi','sesi_soal.nama_sesi')->where('nilai_siswa.id_siswa',$id)->where('nilai_siswa.id_paket',$request->id_paket)->where('sesi_soal.induk_sesi','TKA');
        $detail2=$nila2->get();
        $niq2=$nila2->count();
        $mapel2=array();
        $nilaie2=array();
        foreach($nila2->get() as $nilai2){
            $mapel2[]=$nilai2->nama_sesi;
            $nilaie2[]=$nilai2->nilai;
        }
        $sco=DB::table('peringkat')->where('id_siswa',$id)->where('id_paket',$request->id_paket);
        $myscore=$sco->first();
        $an=DB::table('sesi_soal')->where('id_paket_soal',$request->id_paket)->get();
        if($gagal>0){
            redirect('siswa');
        }else{
            return view('siswa.analisis', ['tps' => $tps,'detai'=>$detail,'detai2'=>$detail2,'id_pakett'=>$request->id_paket,'myscore'=>$myscore,'an'=>$an,'id'=>$id,'nilaie'=>$nilaie,'nilaie2'=>$nilaie2]);
        }
        
    }

    public function pembahasan(Request $request){
        $id=Auth::user()->id;
        $id_sesi=$request->idsoal;
        $id_paket=$request->idpaket;
        $so=DB::table('user_jawaban')->where('id_siswa',$id)->where('id_sesi',$id_sesi);
        $so_hitung=$so->count();
        $nomor_soal=1;
        if($so_hitung<1){
            $se=DB::table('soal')->where('id_sesi_soal',$id_sesi)->orderBy('id','DESC');
            foreach($se->get() as $sel){
                $kunci=$sel->kunci;
                $jawabanSiswa="";
                $id_soal=$sel->id;
                $nomor_soal++;
            }
        }

        //cek paket soal yang aktif
        $ak=DB::table('paket_soal')->where('id',$id_paket);
        $aktif=$ak->first();
        //cek user aktif
        $us=DB::table('user_ujian')->where('id_paket',$aktif->id)->where('id_siswa',$id);
        $user=$us->first();
        //waktu ujian
        $skrg = new DateTime(date('Y/m/d H:i:s'));
        $akhir_ujian=$user->akhir;
        $sisa_waktu = $skrg->diff(new DateTime($akhir_ujian));
        $menit=$sisa_waktu->i;
        $detik=$sisa_waktu->s;
        $total_sisa=$menit.":".$detik;
        //cek sesi aktif
        $nomor_sesi=$us->count();
        //select sesi aktif
        $ses=DB::table('sesi_soal')->where('id',$id_sesi);
        $sesi=$ses->first();
        $t_s=DB::table('soal')->where('id_sesi_soal',$sesi->id);
        $t_soal=$t_s->count();
        return view('/siswa/pembahasan',['user'=>$user,'t_soal'=>$t_soal,'id'=>$id,'sesi'=>$sesi,'paket'=>$aktif]);
    } 
}
