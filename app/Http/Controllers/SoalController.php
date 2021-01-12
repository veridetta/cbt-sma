<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
Use DateTime;

class SoalController extends Controller
{
    //
    public function launch(Request $request){
        $id=Auth::user()->id;
        $id_paket=$request->id_paket;
        $sa=DB::table('riwayat_bintang')->where('id_users',$id)->orderBy('id','DESC');
        $hitung=$sa->count();
        if($hitung>0){
            $sal=$sa->first();
            $saldo=$sal->saldo;    
        }else{
            $saldo=0;
        }
        
        $tp=DB::table('paket_soal')->where('id',$id_paket);
        $tps=$tp->first();
        $tpp=DB::table('sesi_soal')->where('id_paket_soal',$id_paket);
        $tpp_hitung=$tpp->count();
        return view('siswa.launch', ['id'=>$id,'saldo' => $saldo,'tps'=>$tps,'tpp'=>$tpp,'id_ppaket'=>$id_paket]);
    }
    public function bahaslaunch(Request $request){
        $id=Auth::user()->id;
        $id_paket=$request->id_paket;
        $sa=DB::table('riwayat_bintang')->where('id_users',$id)->orderBy('id','DESC');
        $hitung=$sa->count();
        if($hitung>0){
            $sal=$sa->first();
            $saldo=$sal->saldo;    
        }else{
            $saldo=0;
        }
        
        $tp=DB::table('paket_soal')->where('id',$id_paket);
        $tps=$tp->first();
        $tpp=DB::table('sesi_soal')->where('id_paket_soal',$id_paket);
        $tpp_hitung=$tpp->count();
        return view('siswa.bahas_launch', ['saldo' => $saldo,'tps'=>$tps,'tpp'=>$tpp]);
    }
    public function navbahas(Request $request){
        $sesi_id=$request->id_sesi;
        $id=Auth::user()->id;
        $nama_sesi=$request->nama;
        $sq=DB::table('user_jawaban')->where('id_siswa',$id)->where('id_sesi',$sesi_id);
        $btn='';
        ob_start();
        foreach($sq->get() as $q){
            $nosoal=$q->nomor_soal;
            $id_soal=$q->id_soal;
            $jawaban=$q->jawabanSiswa;
            ?>
                <button style="margin-left:10px; margin-top:10px;min-width:44px;" class="btn btn-nav <?php if(empty($jawaban)){echo 'btn-outline-warning';}else{echo 'btn-success';};?>" idSoal="<?php echo $id_soal;?>"><?php echo $nosoal;?></button>
            <?php
        }
        $btn=ob_get_clean();
        ob_start();
        ?>
        <script>
            $(".btn-nav").click(function(){
                var sesi=<?php echo $sesi_id;?>;
                var nosoal=$(this).html();
                $("#nosoalupdate").val($(this).html());
                var nosoall=$("#nosoalupdate");
                if(nosoall.val()==1){
                    $("#sebelumnya").prop('disabled', true);
                }else{
                    $("#sebelumnya").prop('disabled', false);
                }
                if(nosoall.val()==20){
                    $("#berikutnya").html('Selesai');
                }else{
                    $("#berikutnya").html('Berikutnya');
                }
                $.get( "/siswa/soal/soal-bahas?idSesi="+sesi+"&&nomor="+nosoal+"&&nama=<?php echo $nama_sesi;?>", function( data ) {
                        $( "#soal" ).html( data );
                        $("#nomor_soal").html(nosoal);
                    });
            });
            
        </script>
        <?php
        $script=ob_get_clean();
        $output=$btn.$script;
        ob_flush();
        return($output);
    }
    public function navsoal(Request $request){
        $sesi_id=$request->id_sesi;
        $id=Auth::user()->id;
        $nama_sesi=$request->nama;
        $sq=DB::table('user_jawaban')->where('id_siswa',$id)->where('id_sesi',$sesi_id);
        $btn='';
        ob_start();
        foreach($sq->get() as $q){
            $nosoal=$q->nomor_soal;
            $id_soal=$q->id_soal;
            $jawaban=$q->jawabanSiswa;
            ?>
                <button style="margin-left:10px; margin-top:10px;min-width:44px;" class="btn btn-nav <?php if(empty($jawaban)){echo 'btn-outline-warning';}else{echo 'btn-success';};?>" idSoal="<?php echo $id_soal;?>"><?php echo $nosoal;?></button>
                <input type="hidden" id="nosoalupdate" value="1">
            <?php
        }
        $btn=ob_get_clean();
        ob_start();
        ?>
        <script>
            $(".btn-nav").click(function(){
                var sesi=<?php echo $sesi_id;?>;
                var nosoal=$(this).html();
                $("#nosoalupdate").val($(this).html());
                var nosoall=$("#nosoalupdate");
                if(nosoall.val()==1){
                    $("#sebelumnya").prop('disabled', true);
                }else{
                    $("#sebelumnya").prop('disabled', false);
                }
                if(nosoall.val()==20){
                    $("#berikutnya").html('Selesai');
                }else{
                    $("#berikutnya").html('Berikutnya');
                }
                $.get( "/siswa/soal/soal/"+sesi+"/"+nosoal+"/<?php echo $nama_sesi;?>", function( data ) {
                        $( "#soal" ).html( data );
                        $("#nomor_soal").html(nosoal);
                    });
            });
            
        </script>
        <?php
        $script=ob_get_clean();
        $output=$btn.$script;
        ob_flush();
        return($output);
    }
    public function mengerjakan(Request $request){
        date_default_timezone_set('Asia/Jakarta');
        $id=Auth::user()->id;
        $id_sesi=$request->idsoal;
        $id_paket=$request->idpaket;
        $durasi=$request->durasi;
        //cek sesi siswa
        $se=DB::table('user_ujian')->where('id_siswa',$id)->where('id_soal',$id_sesi);
        $se_hitung=$se->count();
        if($se_hitung<1){
            $mulai = date('Y/m/d H:i:s');
            $akhir=date("Y/m/d H:i:s", strtotime("+".$durasi." minutes"));
            $insert_sesi=DB::table('user_ujian')->insert(['id_siswa'=>$id,'id_paket'=>$id_paket,'id_soal'=>$id_sesi,'mulai'=>$mulai,'akhir'=>$akhir,'status'=>'1','percobaan'=>'1']);
        }
        // cek jawaban siswa
        $so=DB::table('user_jawaban')->where('id_siswa',$id)->where('id_sesi',$id_sesi);
        $so_hitung=$so->count();
        $nomor_soal=1;
        if($so_hitung<1){
            $sell=DB::table('soal')->where('id_sesi_soal',$id_sesi)->orderByRaw('rand(UNIX_TIMESTAMP(NOW()))');
            foreach($sell->get() as $sel){
                $kunci=$sel->kunci;
                $jawabanSiswa="";
                $id_soal=$sel->id;
                $in=DB::table('user_jawaban')->insert(['id_siswa'=>$id,'id_paket'=>$id_paket,'nomor_soal'=>$nomor_soal,'kunci'=>$kunci,'jawabanSiswa'=>$jawabanSiswa,'id_soal'=>$id_soal,'id_sesi'=>$id_sesi]);
                $nomor_soal++;
            }
        }
        //cek paket soal yang aktif
        $tp=DB::table('paket_soal')->where('status','2');
        $tps=$tp->first();
        $hitung=$tp->count();
        if($hitung<1){
            return redirect('siswa');
        }
        //cek user aktif
        $us=DB::table('user_ujian')->where('id_paket',$tps->id)->where('id_siswa',$id)->where('status','1');
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
        $ses=DB::table('sesi_soal')->where('id',$user->id_soal);
        $sesi=$ses->first();
        return view('siswa.mengerjakan',['id_paket'=>$id_paket,'id_soal'=>$id_sesi,'sesi'=>$sesi,'akhir_ujian'=>$akhir_ujian,'id'=>$id,'user'=>$user]);
    }
    public function soal(Request $request){
        $nama=Auth::user()->name;
        $id=Auth::user()->id;
        $ref=Auth::user()->ref;
        $idnya=$request->id_sesi;
        $nama_sesi=$request->nama;
        $nomor=$request->nomor;
        $so=DB::table('user_jawaban')->join('soal','soal.id','=','user_jawaban.id_soal')->select('user_jawaban.id as id_jawaban','user_jawaban.id_paket','user_jawaban.kunci','user_jawaban.jawabanSiswa','user_jawaban.id_sesi','user_jawaban.nomor_soal','soal.isi','soal.id','soal.pembahasan','soal.a','soal.b','soal.c','soal.d','soal.e')->where('user_jawaban.id_sesi',$idnya)->where('user_jawaban.nomor_soal',$nomor)->where('user_jawaban.id_siswa',$id);
        $soale=$so->first();
        //dd($request);
        if($so->count()>0){
            ob_start();
            ?>
            <p class="h4"><?php echo $nama_sesi;?></p>
            <p>Soal Nomor <?php echo $soale->nomor_soal;?></p>
            <div class="col-12" id="isi-soal" name="isi-soal">
                <?php echo $soale->isi;?>
            </div>
            <div class="col-12" id="opsi-soal" name="opsi-soal">
                <fieldset class="form-group">
                    <div class="row">
                        <div class="col-sm-10">
                            <table class="table">
                            <tbody>
                        <?php 
                        $aj=array("A","B","C","D","E");
                        $opsi=array($soale->a,$soale->b,$soale->c,$soale->d,$soale->e);
                        for($i=0;$i<5;$i++){
                            ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="opsi" id="opsi_<?php echo $aj[$i];?>" value="<?php echo $aj[$i];?>" soal="<?php echo $soale->id_jawaban;?>" <?php if($aj[$i]==$soale->jawabanSiswa){echo "checked";}?>>
                                <label class="form-check-label" for="opsi_<?php echo $aj[$i];?>">
                                    <?php echo $opsi[$i];?>
                                </label>
                            </div>
                            <?php
                        } 
                        ?>
                        </div>
                    </div>
                </fieldset>
            </div>
            <script>
                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                    }
                });
                $("input[name='opsi']").change(function(){
                    // Do something interesting here
                    var isi=$(this).val();
                    var ids=$(this).attr('soal');
                    $.ajax({
                        url: '<?php echo "/siswa/soal/jawab";?>',
                        type: 'POST',
                        data: {jawab: isi, 
                            idnya:ids}
                    })
                    .done(function(datak){
                        if(datak.success) {
                            $.get( "/siswa/soal/nav-soal/<?php echo $id;?>/<?php echo $idnya;?>/<?php echo $nama_sesi;?>", function( data ) {
                                $( "#panel-control" ).html( datak );
                            });
                        } else {
                            alert(datak);
                        }
                    })
                    .fail(function(){
                        alert('Maaf, submit gagal..');
                    });
                });
            </script>
            <?php
            $output=ob_get_clean();
            ob_flush();
        }else{
            $output=json_encode($request);
        }
        return ($output);
    }
    public function jawab(Request $request){
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "success" => false);
        $jawab=$request->jawab;
        $idnya=$request->idnya;
        $qq=DB::table('user_jawaban')->where('id',$idnya)->update(['jawabanSiswa'=>$jawab]);
        if($qq){
            $pesan = "Berhasil, Soal berhasil dibuat";
            $return_arr['pesan']=$pesan;
            $return_arr['success']=true;
            $output = json_encode($return_arr);
        }else{
            $pesan = "Gagal, silahkan coba lagi.";
            $return_arr['pesan']=$pesan;
            $return_arr['success']=false;
            $output = json_encode($return_arr);
        }
        return response()->json($return_arr);
    }
    public function soalbahas(Request $request){
        $nama=Auth::user()->name;
        $id=Auth::user()->id;
        $ref=Auth::user()->ref;
        $idnya=$request->idSesi;
        $nama_sesi=$request->nama;
        $nomor=$request->nomor;
        $so=DB::table('user_jawaban')->join('soal','soal.id','=','user_jawaban.id_soal')->select('user_jawaban.id as id_jawaban','user_jawaban.id_paket','user_jawaban.kunci','user_jawaban.jawabanSiswa','user_jawaban.id_sesi','user_jawaban.nomor_soal','soal.isi','soal.id','soal.pembahasan','soal.a','soal.b','soal.c','soal.d','soal.e')->where('user_jawaban.id_sesi',$idnya)->where('user_jawaban.nomor_soal',$nomor)->where('user_jawaban.id_siswa',$id);
        $soale=$so->first();
        ob_start();
        ?>
        <p class="h4"><?php echo $nama_sesi;?></p>
        <p>Soal Nomor <?php echo $soale->nomor_soal;?></p>
        <div class="col-12" id="isi-soal" name="isi-soal">
            <?php echo $soale->isi;?>
        </div>
        <div class="col-12" id="opsi-soal" name="opsi-soal">
            <fieldset class="form-group">
                <div class="row">
                    <div class="col-sm-10">
                        <table class="table">
                        <tbody>
                    <?php 
                    $aj=array("A","B","C","D","E");
                    $opsi=array($soale->a,$soale->b,$soale->c,$soale->d,$soale->e);
                    for($i=0;$i<5;$i++){
                        if($soale->kunci==$soale->jawabanSiswa && $aj[$i]==$soale->jawabanSiswa){
                            ?>
                            <tr class="bg-success">
                                <td><?php echo $aj[$i];?></td>
                                <td><?php echo $opsi[$i];?></td>
                            </tr>
                            <?php
                        }else{
                            if($aj[$i]==$soale->jawabanSiswa){
                                ?>
                                <tr class="bg-warning">
                                    <td><?php echo $aj[$i];?></td>
                                    <td><?php echo $opsi[$i];?></td>
                                </tr>
                                <?php
                            }else if($aj[$i]==$soale->kunci){
                                ?>
                                <tr class="bg-info">
                                    <td><?php echo $aj[$i];?></td>
                                    <td><?php echo $opsi[$i];?></td>
                                </tr>
                                <?php
                            }else{
                                ?>
                                <tr class="">
                                    <td><?php echo $aj[$i];?></td>
                                    <td><?php echo $opsi[$i];?></td>
                                </tr>
                                <?php
                            }
                        }
                        
                        ?>
                        <?php
                    } 
                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="col-12">
            <hr>
            <p class="font-weight-bold">Keterangan</p>
            <p><span class="bg-warning ">&nbsp;&nbsp;&nbsp;</span> <span class="">Jawaban Siswa</span></p>
            <p><span class="bg-info ">&nbsp;&nbsp;&nbsp;</span> <span class="">Kunci Jawaban</span></p>
            <p><span class="bg-success ">&nbsp;&nbsp;&nbsp;</span> <span class="">Menjawab  Benar</span></p>
        </div>
        <div class="col-12">
            <hr>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"><i class="fa" aria-hidden="true"></i>
                            Pembahasan
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse" >
                    <div class="card-body">
                        <p class=""><?php echo $soale->pembahasan;?></p>
                        <span class="badge badge-info h4">Jawaban <?php echo $soale->kunci;?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $output=ob_get_clean();
        ob_flush();
        return ($output);
    }
    public function finish(Request $request){
        $return_arr= array(
            "id" => '',
            "pesan" => '',
            "success" => false);
        //header('Content-Type: application/json');
        $id=$request->id;
        $ujian=$request->ujian;
        //waktu ujian
        date_default_timezone_set('Asia/Jakarta');
        $skrg = date("Y-m-d H:i:s");
        $ubah=DB::table('user_ujian')->where('id',$ujian)->update(['akhir'=>$skrg]);
            if($ubah){
                $pesan = "<strong>Berhasil</strong>, Selesai";
                $return_arr['pesan']=$pesan;
                $return_arr['success']=true;
                $output = json_encode($return_arr);
                die($output);
            }else{
                $pesan = "Gagal, silahkan coba lagi.";
                $return_arr['pesan']=$pesan;
                $return_arr['success']=false;
                $output = json_encode($return_arr);
                die($output);                  
            }
    }
}
