@extends('template')
@section('title', 'Launch BaseCampTO ')

@section('intro-header')
@endsection
@section('main')
<?php $akhir_ujian="";?>
<div class="col-12 row row-imbang primary" style="background:white;margin-top:60px;">
    <div class="col-12 row row-imbang" style="background-color:white;padding:20px;margin-bottom:12px;">
        <div class="col-12">
            <p class="h4 text-danger"><i class="fa fa-user"></i> Profile</p>
            <hr>   
        </div>            
        <div class="col-6">
            <p class="h4">{{Auth::user()->name}}</p>
            <p class="h6">Code Referal : {{Auth::user()->ref}}</p>
        </div>
        <div class="col-6 text-right">
            <span class="h4 text-right"><i class="text-warning fa fa-star"></i> {{$saldo}}</span>
            <a class="btn btn-outline-warning h4" href="{{url('siswa/payment/topup')}}">Tambah</a>
        </div>
        <hr>
    </div>
    <div class="col-12 row row-imbang" style="background-color:white;padding:20px;margin-bottom:12px;">
        <div class="col-12">
            <p class="h4 text-danger"><i class="fa fa-book"></i> {{$tps->nama}}</p>
            <hr>   
        </div>            
        <div class="col-6">
            <p class="h4">Tes Pengetahuan Skolastik & Akademik</p>
            <hr>
        </div>
        <div class="col-12 row justify-content-center">
            <?php
               $notps=0;
               $insert=0;
               $selesaie=0;
               // echo $tpp_hitung;
                foreach($tpp->get() as $tpps){
                    $status=DB::table('user_ujian')->where('id_soal',$tpps->id)->where('id_siswa',$id);
                    $hitung_sesi=$status->count();
                    $hitung_so=DB::table('soal')->where('id_sesi_soal',$tpps->id);
                    $hitung_soal=$hitung_so->count();
                    $status_siswa=$status->first();
                            ?>
                            @if($hitung_sesi < 1)
                                @if($notps < 1)
                                <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-info text-white">
                                                <p class="card-title">{{$tpps->nama_sesi}}</p>
                                            </div>
                                            <div class="card-body">
                                            <p>Total Soal : {{$hitung_soal}} Soal
                                                <p>Durasi : {{$tpps->durasi}} Menit</p>
                                            </div>
                                            <div class="card-footer">
                                                <form method="post" action="{{url('/siswa/soal/mengerjakan')}}" name="mulai" id="mulai">
                                                    @csrf
                                                    <input type="hidden" name="durasi" value="{{$tpps->durasi}}">
                                                    <input type="hidden" name="idsoal" value="{{$tpps->id}}">
                                                    <input type="hidden" name="idsiswa" value="{{$id}}">
                                                    <input type="hidden" name="idpaket" value="{{$tps->id}}">
                                                    <button class="btn btn-info btn-block">Mulai</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    @if($insert > 0)
                                    <?php
                                        date_default_timezone_set('Asia/Jakarta');
                                        $mulaie = date('Y-m-d H:i:s');
                                        $akhire=date("Y-m-d H:i:s", strtotime("+".$tpps->durasi." minutes"));
                                        $sql=DB::table('user_ujian')->insert(['id_siswa'=>$id,'id_paket'=>$tpps->id_paket_soal,'id_soal'=>$tpps->id,'mulai'=>$mulaie,'akhir'=>$akhire,'status'=>'1','percobaan'=>'1']);
                                            if($sql){
                                                $insert=0;
                                                ?>
                                            <script>
                                                location.reload();
                                            </script>
                                            <?php
                                        }?>
                                    @endif
                                        <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                            <div class="col-12" style="opacity:0.7">
                                                <div class="tutup card row" style="width:85%; min-height: 100px;position: absolute;background: black;opacity:0.4;">
                                                    <p class="text-center my-auto text-white">Belum</p>
                                                </div>
                                                <div class="card buka2" style="position:initial;">
                                                    <div class="card-header">
                                                        <p class="card-title">{{$tpps->nama_sesi}}</p>
                                                    </div>
                                                    <div class="card-body">
                                                        <p>Total Soal : {{$hitung_soal}} Soal
                                                        <p>Durasi : {{$tpps->durasi}} Menit</p>
                                                    </div>
                                                    <div class="card-footer">
                                                        <button class="btn btn-disabled btn-block">Mulai</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                @endif
                            @else
                                @if($status_siswa->status==1)
                                    <?php
                                    //waktu ujian
                                    date_default_timezone_set('Asia/Jakarta');
                                    $skrg = new DateTime(date('Y-m-d H:i:s'));
                                    $akhir_ujian=$status_siswa->akhir;
                                    if($skrg>new DateTime($akhir_ujian)){
                                        $ubah=DB::table('user_ujian')->where('id',$status_siswa->id)->update(['status'=>'2']);
                                        if($ubah){
                                            $insert=1;
                                        }else{
                                            $insert=0;
                                        
                                        }
                                    ?>
                                    <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                        <div class="col-12">
                                            <div class="tutup card row" style="width:85%; min-height: 100%;position: absolute;background: black;opacity:0.4;">
                                                <p class="text-center my-auto text-white">Selesai</p>
                                            </div>
                                            <div class="card buka" style="position:initial;">
                                                <div class="card-header">
                                                    <p class="card-title">{{$tpps->nama_sesi}}</p>
                                                </div>
                                                <div class="card-body">
                                                    <p>Total Soal : {{$hitung_soal}} Soal
                                                    <p>Durasi : {{$tpps->durasi}} Menit</p>
                                                </div>
                                                <div class="card-footer">
                                                    <button class="btn btn-disabled btn-block">Mulai</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }else{
                                        //waktu ujian
                                        $skrg = new DateTime(date('Y/m/d H:i:s'));
                                        $akhir_ujian=$status_siswa->akhir;
                                        $sisa_waktu = $skrg->diff(new DateTime($akhir_ujian));
                                        $menit=$sisa_waktu->i;
                                        $detik=$sisa_waktu->s;
                                        $total_sisa=$menit.":".$detik;
                                        $insert=0;
                                        ?>
                                        <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-header bg-info text-white">
                                                        <p class="card-title">{{$tpps->nama_sesi}}</p>
                                                    </div>
                                                    <div class="card-body">
                                                    <p>Total Soal : {{$hitung_soal}} Soal
                                                        <p>Durasi : {{$tpps->durasi}} Menit</p>
                                                        <hr>
                                                        <p class="text-center"><span id="timer"></span> </p>
                                                    </div>
                                                    <div class="card-footer">
                                                        <form method="post" action="{{url('/siswa/soal/mengerjakan')}}" name="mulai" id="mulai">
                                                            @csrf
                                                            <input type="hidden" name="durasi" value="{{$tpps->durasi}}">
                                                            <input type="hidden" name="idsoal" value="{{$tpps->id}}">
                                                            <input type="hidden" name="idsiswa" value="{{$id}}">
                                                            <input type="hidden" name="idpaket" value="{{$tps->id}}">
                                                            <button class="btn btn-info btn-block">Mulai</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                @elseif($status_siswa->status==2)
                                    <?php $selesaie++;?>
                                    <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                        <div class="col-12">
                                            <div class="tutup card row" style="width:85%; min-height: 100%;position: absolute;background: black;opacity:0.4;">
                                                <p class="text-center my-auto text-white">Selesai</p>
                                            </div>
                                            <div class="card buka" style="position:initial;">
                                                <div class="card-header">
                                                    <p class="card-title">{{$tpps->nama_sesi}}</p>
                                                </div>
                                                <div class="card-body">
                                                    <p>Total Soal : {{$hitung_soal}} Soal
                                                    <p>Durasi : {{$tpps->durasi}} Menit</p>
                                                </div>
                                                <div class="card-footer">
                                                    <button class="btn btn-disabled btn-block">Mulai</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($status_siswa->status==3)
                                    <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                        <div class="col-12">
                                            <div class="tutup card row" style="width:85%; min-height: 100px;position: absolute;background: black;opacity:0.4;">
                                                <p class="text-center my-auto text-white">Selesai</p>
                                            </div>
                                            <div class="card buka" style="position:initial;">
                                                <div class="card-header">
                                                    <p class="card-title">{{$tpps->nama_sesi}}</p>
                                                </div>
                                                <div class="card-body">
                                                    <p>Total Soal : {{$hitung_soal}} Soal
                                                    <p>Durasi : {{$tpps->durasi}} Menit</p>
                                                </div>
                                                <div class="card-footer">
                                                    <button class="btn btn-disabled btn-block">Mulai</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                
                                @else
                                    <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                        <div class="col-12" style="opacity:0.7">
                                            <div class="tutup card row" style="width:85%; min-height: 100px;position: absolute;background: black;opacity:0.4;">
                                                <p class="text-center my-auto text-white">Belum</p>
                                            </div>
                                            <div class="card buka2" style="position:initial;">
                                                <div class="card-header">
                                                    <p class="card-title">{{$tpps->nama_sesi}}</p>
                                                </div>
                                                <div class="card-body">
                                                    <p>Total Soal : {{$hitung_soal}} Soal
                                                    <p>Durasi : {{$tpps->durasi}} Menit</p>
                                                </div>
                                                <div class="card-footer">
                                                    <button class="btn btn-disabled btn-block">Mulai</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            <?php $notps++;?>
           <?php
                }
           ?>
        </div>
        <hr>
    </div>
</div>
<script>
$(document).ready(function(){
    var tinggi = $('.buka2').height();
    $('.tutup').height(tinggi);
            var countDownDate = Date.parse("<?php echo $akhir_ujian;?>");
            // Update the count down every 1 second
            var x = setInterval(function() {
            // Get today's date and time
            var now = new Date().getTime();
            // Find the distance between now and the count down date
            var distance = countDownDate - now;
            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            // Display the result in the element with id="demo"
            document.getElementById("timer").innerHTML = minutes + " : " + seconds;
            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                $("#message").toggleClass('sembunyi');
                setTimeout(function(){window.location.replace("/siswa/soal/launch/<?php echo $id_ppaket;?>"); }, 2000);
                
            }
            }, 1000);
})
</script>
@endsection