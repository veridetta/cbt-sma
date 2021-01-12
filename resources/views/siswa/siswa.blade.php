@extends('template')
@section('title', 'Member BaseCampTO ')

@section('intro-header')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<style>
.bg-dark p{
    color:white !important;
}
</style>
@endsection
@section('main')
<?php
function format_hari_tanggal($waktu){
    $hari_array = array(
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    );
    $hr = date('w', strtotime($waktu));
    $hari = $hari_array[$hr];
    $tanggal = date('j', strtotime($waktu));
    $bulan_array = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    );
    $bl = date('n', strtotime($waktu));
    $bulan = $bulan_array[$bl];
    $tahun = date('Y', strtotime($waktu));
    $jam = date( 'H:i:s', strtotime($waktu));
    //untuk menampilkan hari, tanggal bulan tahun jam
    //return "$hari, $tanggal $bulan $tahun $jam";

    //untuk menampilkan hari, tanggal bulan tahun
    return "$hari, $tanggal $bulan $tahun";
}
 function statistik($tipe,$id){
    $go=0;
    $sesi=DB::table('sesi_soal')->where('induk_sesi',$tipe)->groupBy('nama_sesi');
    $dat= array(
        "deta"=>array()
    );
    if($sesi->count()>0){
      foreach($sesi->get() as $sessi){
        $nila=DB::table('nilai_siswa')->where('id_siswa',$id)->where('nama_sesi',$sessi->nama_sesi);
        $ar['name']=$sessi->nama_sesi;
            $ar['data']=array();
            $dak=array(
                "x"=>"",
                "y"=>""
            );
            $n=1;
            if($nila->count()<0){
              //$go=0;
            }else{
              foreach($nila->get() as $nilai){
                $arx['x']="TO".$n;
                $arx['y']=$nilai->nilai;
                array_push($ar["data"], $arx);
                $n++;
              }
              $go=1;
            }
            array_push($dat["deta"], $ar);
      }
    }else{
      $go=0;
    }
    if($go>0){
      $dat['data']=$ar;
      echo json_encode($dat['deta']);  
    }else{
      echo "[]";
    }
}
$cla=array("success","danger","warning","primary","info","dark");
?>
<div class="col-12 row row-imbang primary" style="padding-top:10px;padding-bottom:12px;background:#f0f0f0">
    <div id="message" style="z-index:999;position:fixed;right:12px;bottom:12px;"></div>    
    <div class="col-12 col-lg-4 col-sm-12 col-xl-4 col-xs-12">
        <div class="card">
            <div class="card-body">
                <p class="text-center"><img class=" text-center rounded-circle" alt="100x100" src="https://place-hold.it/100x100/e74c3c/ecf0f1?text={{Str::substr(Auth::user()->name,0,1)}}&fontsize=55" data-holder-rendered="true"></p>
                <p class="text-center h4 text-info text-capitalize">{{ Auth::user()->name }}</p>
                <hr>
                <div class="text-center">
                    <span class="h4 text-right"><i class="text-warning fa fa-star"></i> @foreach($bintang as $bin){{$bin->saldo}}@endforeach</span>
                    <a class="btn btn-outline-warning h4" href="{{url('siswa/payment/topup')}}">Tambah</a>
                </div>
            </div>
        </div>
        <div class="card" style="margin-top:10px;">
            <div class="card-header">
                <p class="card-title">Riwayat Bintang</p>
            </div>
            <div class="card-body">
                <table class="col-12">
                    @foreach($pembelian as $pem)
                    <tr>
                        @if($pem->status ==1)
                        <td class="text-success">{{$pem->tgl}}</td>
                        <td class="text-success text-right">+{{$pem->nominal}}</td>
                        @elseif($pem->status ==2)
                        <td class="text-danger">{{$pem->tgl}}</td>
                        <td class="text-danger text-right">-{{$pem->nominal}}</td>
                        @endif
                    </tr>
                    @endforeach
                    <tr style="border-top:1px solid #f0f0f0;padding-top:6px;margin-top:6px;">
                        <td class="text-info">Sisa Bintang</td>
                        <td class="text-info text-right">@foreach($bintang as $bin){{$bin->saldo}}@endforeach</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8 col-sm-12 col-xl-8 col-xs-12">
        <div class="card">
            <div class="card-header">
                <p class="card-title h4 text-info"><i class="fa fa-bar-chart"></i> Statistik</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <p class="text-center display-3 font-weight-bold text-success">TPS</p>
                        <div id="chart"></div>
                    </div>
                    <div class="col-lg-6 col-12">

                        <p class="text-center display-3 font-weight-bold text-warning">TKA</p>
                        <div id="chart-tka"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12" style="margin-top:12px;">
        <div class="card">
            <div class="card-header">
                <p class="card-title h4 text-info"><i class="fa fa-calendar"></i> Event Terbaru</p>
            </div>
            <div class="card-body">
                <div class="col-12 row">
                    @if($jumlah_paket < 1)
                    <div class="col-12 row" style="min-height:30px;">
                        <p class="h4 text-muted">Belum ada event terbaru</p>
                    </div>
                    @else
                        @if($jumlah_user < 1)
                            @foreach($paket as $paket_list)
                            <?php $rand=array_rand($cla,4);?>
                            <div class="col-md-3 col-11">
                                <div class="card" style="margin-bottom:12px;">
                                    <div style="background:rgba(20,27,150,0.7);position:absolute;height:200px;width:100%"></div>
                                    <div class="card-header bg-<?php echo $cla[$rand[1]];?>" style="background-image:url('/images/pattern.png');height:200px;">
                                        <div class="row col-12 justify-content-center" style="min-height:200px">
                                            <div style="position:absolute" class="text-center col-12">
                                                <span class="badge badge-<?php echo $cla[$rand[0]];?>" style="padding:5px;">{{$paket_list->nama_kategori}}</span> <span class="badge badge-<?php echo $cla[$rand[2]];?>" style="padding:5px;">{{format_hari_tanggal(date("Y-m-d",strtotime($paket_list->tgl_mulai)))}}</span>
                                            </div>
                                            <p class="card-title h4 text-center my-auto font-weight-bolder text-white">{{$paket_list->nama}}</p>
                                            <div style="position:absolute; bottom:2px;">
                                                <span class="badge badge-<?php echo $cla[$rand[3]];?>" style="padding:5px;">Berakhir {{format_hari_tanggal(date("Y-m-d",strtotime($paket_list->tgl_selesai)))}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12" style="position:absolute">
                                    </div>
                                    <div class="card-body text-center" >
                                        <div class="row col-12 text-center" style="min-height:70px;">
                                            <p class="text-center col-12 my-auto text-<?php echo $cla[$rand[1]];?>" style="margin-left:8px;margin-bottom:0px;">"{{$paket_list->keterangan}}"</p>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                    @if($paket_list->status==0)
                                        <button class="btn btn-block" id="" disabled paket="{{$paket_list->id}}"><i class="text-warning fa fa-calendar"></i>    Terjadwal</button>
                                    @else
                                        <button class="kluk btn button btn-<?php echo $cla[$rand[1]];?> btn-block" id="btn-buy{{$paket_list->id}}" paket="{{$paket_list->id}}">Daftar ({{$paket_list->bintang}} <i class="text-warning fa fa-star"></i>)</button>                                    
                                    @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            @foreach($paket as $paket_list)
                            <?php $rand=array_rand($cla,4);
                            $use=DB::table('peserta_paket')->where('paket_soal_id',$paket_list->id);
                            $userr=$use->first();
                            ?>
                            <div class="col-md-3 col-11">
                                <div class="card" style="margin-bottom:12px;">
                                    <div style="background:rgba(20,27,150,0.7);position:absolute;height:200px;width:100%"></div>
                                    <div class="card-header bg-<?php echo $cla[$rand[1]];?>" style="background-image:url('/images/pattern.png');height:200px;">
                                        <div class="row col-12 justify-content-center" style="min-height:200px">
                                            <div style="position:absolute" class="text-center col-12">
                                                <span class="badge badge-<?php echo $cla[$rand[0]];?>" style="padding:5px;">{{$paket_list->nama_kategori}}</span> <span class="badge badge-<?php echo $cla[$rand[2]];?>" style="padding:5px;">{{format_hari_tanggal(date("Y-m-d",strtotime($paket_list->tgl_mulai)))}}</span>
                                            </div>
                                            <p class="card-title h4 text-center my-auto font-weight-bolder text-white">{{$paket_list->nama}}</p>
                                            <div style="position:absolute; bottom:2px;">
                                                <span class="badge badge-<?php echo $cla[$rand[3]];?>" style="padding:5px;">Berakhir {{format_hari_tanggal(date("Y-m-d",strtotime($paket_list->tgl_selesai)))}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12" style="position:absolute">
                                    </div>
                                    <div class="card-body text-center" >
                                        <div class="row col-12 text-center" style="min-height:70px;">
                                            <p class="text-center col-12 my-auto text-<?php echo $cla[$rand[1]];?>" style="margin-left:8px;margin-bottom:0px;">"{{$paket_list->keterangan}}"</p>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                    @if($use->count()>0)
                                        @if($paket_list->status==2 && $userr->status==1)
                                        <?php $udl = "/siswa/soal/launch/".$paket_list->id;?>
                                        <form method="post" action="{{url($udl)}}">
                                            @csrf
                                            <input type="hidden" value="{{$paket_list->id}}" name="id_paket">
                                            <button class="btn button btn-<?php echo $cla[$rand[1]];?> btn-block">Mulai Mengerjakan</button>
                                        </form>
                                        @else
                                        <button class="btn button btn-disabled btn-block">Sudah Terdaftar</button>
                                        @endif
                                    @else
                                        @if($paket_list->status==0)
                                            <button class="btn btn-block" id="" disabled paket="{{$paket_list->id}}"><i class="text-warning fa fa-calendar"></i>Terjadwal</button>
                                        @else
                                            <button class="kluk btn button btn-<?php echo $cla[$rand[1]];?> btn-block" id="btn-buy{{$paket_list->id}}" paket="{{$paket_list->id}}">Daftar ({{$paket_list->bintang}} <i class="text-warning fa fa-star"></i>)</button>                                    
                                        @endif
                                    @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    @endif
                    <p></p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12" style="margin-top:12px;">
        <div class="card">
            <div class="card-header">
                <p class="card-title h4 text-info"><i class="fa fa-history"></i> Event Terakhir</p>
            </div>
            <div class="card-body">
                <div class="col-12 row">
                    @if($jumlah_history < 1)
                    <div class="col-12 row" style="min-height:30px;">
                        <p class="h4 text-muted">Belum ada event terakhir</p>
                    </div>
                    @else
                        @foreach($history as $history_list)
                        <?php $rand=array_rand($cla,4);?>
                           <?php
                            $id= Auth::user()->id ;
                            $user=\App\Models\Peserta_paket::where('id_user','=',$id)->where('paket_soal_id','=',$history_list->id)->get();
                           ?>
                            @if($user->count()>0)
                                @foreach($user as $pengguna)
                                    <div class="col-md-3 col-11">
                                        <div class="card" style="margin-bottom:12px;">
                                            <div style="background:rgba(20,27,29,0.7);position:absolute;height:200px;width:100%"></div>
                                            <div class="card-header bg-<?php echo $cla[$rand[1]];?>" style="background-image:url('/images/pattern.png');height:200px;">
                                                <div class="row col-12 justify-content-center" style="min-height:200px">
                                                    <div style="position:absolute" class="text-center col-12">
                                                        <span class="badge badge-<?php echo $cla[$rand[0]];?>" style="padding:5px;">{{$history_list->nama_kategori}}</span> <span class="badge badge-<?php echo $cla[$rand[2]];?>" style="padding:5px;">{{format_hari_tanggal(date("Y-m-d",strtotime($history_list->tgl_mulai)))}}</span>
                                                    </div>
                                                    <p class="card-title h4 text-center my-auto font-weight-bolder text-white">{{$history_list->nama}}</p>
                                                    <div style="position:absolute; bottom:2px;">
                                                        <span class="badge badge-<?php echo $cla[$rand[3]];?>" style="padding:5px;">Berakhir {{format_hari_tanggal(date("Y-m-d",strtotime($history_list->tgl_selesai)))}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12" style="position:absolute">
                                            </div>
                                            <div class="card-body text-center" >
                                                <div class="row col-12 text-center" style="min-height:70px;">
                                                    <p class="text-center col-12 my-auto text-<?php echo $cla[$rand[1]];?>" style="margin-left:8px;margin-bottom:0px;">"{{$history_list->keterangan}}"</p>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                            @if($history_list->status==4 && $pengguna->status > 0)
                                            <form method="post" action="{{url('siswa/analisis')}}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{Auth::user()->id}}">
                                                <input type="hidden" name="id_paket" value="{{$history_list->id}}">
                                                <button class="btn button btn-primary btn-block">Buat Analisis Saya</button>
                                            </form>
                                            <form method="post" action="{{url('/siswa/soal/bahas-launch')}}" style="margin-top:12px;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{Auth::user()->id}}">
                                                <input type="hidden" name="id_paket" value="{{$history_list->id}}">
                                                <button class="btn button btn-success btn-block">Lihat Pembahasan</button>
                                            </form>
                                            @elseif($history_list->status==3 && $pengguna->status>0))
                                            <button class="btn button btn-disabled btn-block">Menunggu Pembahasan</button>
                                            @else
                                            <button class="btn button btn-disabled btn-block">Tidak Terdaftar</button>
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                            <div class="col-md-3 col-11">
                                        <div class="card" style="margin-bottom:12px;">
                                            <div style="background:rgba(20,27,29,0.7);position:absolute;height:200px;width:100%"></div>
                                            <div class="card-header bg-<?php echo $cla[$rand[1]];?>" style="background-image:url('/images/pattern.png');height:200px;">
                                                <div class="row col-12 justify-content-center" style="min-height:200px">
                                                    <div style="position:absolute" class="text-center col-12">
                                                        <span class="badge badge-<?php echo $cla[$rand[0]];?>" style="padding:5px;">{{$history_list->nama_kategori}}</span> <span class="badge badge-<?php echo $cla[$rand[2]];?>" style="padding:5px;">{{format_hari_tanggal(date("Y-m-d",strtotime($history_list->tgl_mulai)))}}</span>
                                                    </div>
                                                    <p class="card-title h4 text-center my-auto font-weight-bolder text-white">{{$history_list->nama}}</p>
                                                    <div style="position:absolute; bottom:2px;">
                                                        <span class="badge badge-<?php echo $cla[$rand[3]];?>" style="padding:5px;">Berakhir {{format_hari_tanggal(date("Y-m-d",strtotime($history_list->tgl_selesai)))}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12" style="position:absolute">
                                            </div>
                                            <div class="card-body text-center" >
                                                <div class="row col-12 text-center" style="min-height:70px;">
                                                    <p class="text-center col-12 my-auto text-<?php echo $cla[$rand[1]];?>" style="margin-left:8px;margin-bottom:0px;">"{{$history_list->keterangan}}"</p>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                            <button class="btn button btn-disabled btn-block">Tidak Terdaftar</button>
                                            </div>
                                        </div>
                                    </div>
                            @endif
                        @endforeach
                    @endif
                    <p></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!--FORM-->
<form method="post"  id="form_buy" name="form_buy">
    @csrf
    <input type="hidden" name="id_paket_soal" id="id_paket_soal" value="">
    <input type="hidden" name="id_user" id="id_user" value="{{Auth::user()->id}}">
    <input type="hidden" name="voucher" id="voucher" value="">
    <input type="submit" name="sb_buy" id="sb_buy" style="display:none;" value="ada">
</form>
<!-- FORM-->
<!--MODALS-->
<div class="modal" id="buy-modal">
    <div class="modal-dialog">
        <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title" id="">Konfirmasi Pembelian</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body justify-content-center">
            <div class='form-group'>
                <div class="input-group">
                    <input id="kode" maxlength="8" name="kode" type="text" class="form-control-lg" placeholder="Voucher">
                </div>
            </div>            
            <span class="text-muted">* Masukkan voucher jika mempunyai kode voucher, kosongkan jika langsung membeli dengan bintang.</span>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" id="beli-jadi" class="btn btn-success" data-dismiss="modal">Konfirmasi</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
        </div>

        </div>
    </div>
</div>
<!--END-->
<!-- MODAL EMPTY-->
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title" id="judul">Modal Heading</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <span id="pesan"></span>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<!--END-->
<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
        }
    });
    function mAlert(judul,pesan,clas){
        $("#message").append('<div id="" class="alert alert-'+clas+' alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert"> &times;</button><span id="message"><i class="fas fa-fw fa-bell"></i> '+pesan+'. &nbsp;&nbsp;</span></div>');
    };
    
    $(".kluk").click(function(){
        var vale = $(this).attr("paket");
        $("#id_paket_soal").val(vale);
        $("#buy-modal").modal('show');
    });
    $("#beli-jadi").click(function(e){
        e.preventDefault();
        var vou = $("#kode").val();
        $("#voucher").val(vou);
        //$("#sb_buy").trigger("click");
        $.ajax({
            data: $('#form_buy').serialize(),
            url: "{{url('/siswa/action/daftar_to')}}",
            type: "POST",
            dataType : 'json',
        }).done(function(data){
            if(data.success){
                mAlert(data.judul,data.pesan,'success');
            setTimeout(function() {
                window.location.replace("{{url('/siswa')}}");
            }, 1500);
            }else{
                mAlert(data.judul,data.pesan,'danger');
            }
        })
    });
    
   
var options = {
        chart: {
            type: 'area',
            stacked: true,
            height: '320',
            foreColor: "#2ecc71"
        },
        events: {
            selection: function (chart, e) {
              console.log(new Date(e.xaxis.min))
            }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth',
          width: 3
        },
        fill: {
          type: 'gradient',
          gradient: {
            enabled: true,
            opacityFrom: 0.55,
            opacityTo: 0
          }
        },
        markers: {
            size: 5,
            colors: ["#000524"],
            strokeColor: "#00BAEC",
            strokeWidth: 3
        },
        tooltip: {
            theme: "dark"
        },
        series: <?php statistik('TPS',Auth::user()->id);?>
        ,
        xaxis: {
            categories: []
        }
    }
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
        
    var options2 = {
        chart: {
            type: 'area',
            stacked: true,
            height: '320',
            foreColor: "#f39c12"
        },
        events: {
            selection: function (chart, e) {
              console.log(new Date(e.xaxis.min))
            }
        },
        dataLabels: {
          enabled: true
        },
        noData: {
            text: 'Belum ada data'
        },
        stroke: {
          curve: 'smooth',
          width: 3
        },
        fill: {
          type: 'gradient',
          gradient: {
            enabled: true,
            opacityFrom: 0.55,
            opacityTo: 0
          }
        },
        markers: {
            size: 5,
            colors: ["#000524"],
            strokeColor: "#00BAEC",
            strokeWidth: 3
        },
        tooltip: {
            theme: "dark"
        },
        series: <?php statistik('TKA',Auth::user()->id);?>,
        xaxis: {
            categories: []
        }
    }
        var chart2 = new ApexCharts(document.querySelector("#chart-tka"), options2);
        chart2.render();
</script>
@endsection