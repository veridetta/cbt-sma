<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SoalController extends Controller
{
    //
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
}
