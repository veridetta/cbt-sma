@extends('adminlte::page')

@section('title', 'Dashboard Sesi Soal')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard Sesi Soal</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">Sesi Soal</li>
            </ol>
        </div>
    </div>
    <style>
    .content-wrapper{
        max-width:100% !important;
    }
    </style>
@stop
@section('content')
<div class="container-fluid col-12">
<div id="message" style="z-index:999;position:fixed;right:12px;bottom:12px;"></div>    
<div class="card">
    <div class="card-header">
        <p class="h4"><i class="fas fa-book mr-1"></i> Paket List</p>
    </div>
    <div class="card-body">
        <?php
            $q=DB::table('paket_soal')->join('kategori_soal as k','k.id','paket_soal.kategori')->select('paket_soal.*','k.nama as katnam')->orderBy('id','desc')->get();
            foreach($q as $qu){
                $go=DB::table('soal')->where('id_paket_soal',$qu->id)->get();
        ?>
        <div class="col-12 row row-imbang" data-toggle="collapse" href="#collapse<?php echo $qu->id;?>">
            <div class="col-6">
                <p class="h5"><?php echo $qu->nama;?></p>
                <label class="text-muted"><?php echo $qu->keterangan;?></label>
            </div>
            <div class="col-6 text-right">
                <p class="h6"><span class="badge badge-warning">{{$qu->katnam}}</span> &nbsp;&nbsp;&nbsp;<span class="badge badge-success"><?php echo $go->count();?> Soal</span></p>
            </div>
        </div>
        <div class="col-12 panel-collapse collapse" id="collapse<?php echo $qu->id;?>">
            <table class="table table-striped">
                <tbody>
                    <?php
                    $s=DB::table('sesi_soal')->where('id_paket_soal',$qu->id)->orderBY('id','asc')->get();
                    foreach($s as $su){
                        $ju=DB::table('soal')->where('id_paket_soal',$qu->id)->where('id_sesi_soal',$su->id);
                        $jumlahe=$ju->count();
                ?>
                    <tr>
                        <td><?php echo $su->nama_sesi;?></td>
                        <td><?php echo $su->durasi;?> Menit</td>
                        <td><?php echo $jumlahe;?> Soal</td>
                        <td><button class="btn button btn-primary pilih-sesi" sesi="<?php echo $su->id;?>" href="sesi" paket="<?php echo $su->id_paket_soal;?>"><i class="fa fa-edit"></i> Ubah</button></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <hr>
            <div class="col-12 row justify-content-center h-60">
                <div class="my-auto">
                    <button class="btn button btn-primary btn-sesi" id-paket-soal="<?php echo $qu->id;?>" data-toggle="modal" data-target="#sesiSoal"><i class="fa fa-plus"></i> Tambah Sesi Soal </button>
                </div>
            </div>
        </div>
        <hr>
        <?php
            }
        ?>
        <hr>        
    </div>
    <div class="card-footer">
    </div>
    <div class="modal" id="sesiSoal">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Sesi Soal</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form method="post" action="" name="sesisoal" id="sesisoal">
                    <div class="form-group">
                        <label for="induk_sesi">Induk Sesi</label>
                        <div class="input-group">
                            <select name="induk_sesi" id="induk_sesi" class="form-control required">
                                <option>TPS</option>
                                <option>TKA</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nama_sesi">Nama Sesi</label>
                        <div class="input-group">
                            <select name="nama_sesi" id="nama_sesi" class="form-control required">
                                <option value="Penalaran Umum">Penalaran Umum</option>
                                <option value="Pemahaman Bacaan & Menulis">Pemahaman Bacaan & Menulis</option>
                                <option value="Pengatahuan & Pemahaman Umum">Pengatahuan & Pemahaman Umum</option>
                                <option value="Penalaran Kuantitatif">Penalaran Kuantitatif</option>
                                <option value="Matematika">Matematika</option>
                                <option value="Fisika">Fisika</option>
                                <option value="Kimia">Kimia</option>
                                <option value="Biologi">Biologi</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="urutan">Urutan Sesi</label>
                        <div class="input-group">
                            <select name="urutan" id="urutan" class="form-control required">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="durasi">Durasi</label>
                        <div class="input-group">
                            <input type="text" name="durasi" id="durasi" class="form-control required" placeholder="Durasi dalam menit, tanpa koma">
                        </div>
                    </div>
                    <input type="hidden" id="id-paket" name="id_paket_soal" value="">
                    <div class="form-group">
                        <div class="input-group">
                            <button name="submit" class="btn-tambah btn btn-primary" data-form="sesisoal" data-target="sesisoal" data-jenis="sesi"><i class="fas fa-fw fa-plus" ></i> Tambah</button>
                        </div>
                    </div> 
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <div id="hasilnya"></div>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

            </div>
        </div>
    </div>
<div>
</div>
@stop
@section('js')
<script>
$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
    }
});
function mAlert(judul,pesan,clas){
    $("#message").append('<div id="" class="alert alert-'+clas+' alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert"> &times;</button><span id="message"><i class="fas fa-fw fa-bell"></i> '+pesan+'. &nbsp;&nbsp;</span></div>');
};
function tambah(form,jenis){
    var tform='#'+form;
    var urle="/dashboard/action/buat_"+jenis+"_soal";
    $.ajax({
        type: "POST",
        url: urle,
        data: $(tform).serialize(),
        dataType: "json"
    }).done(function(data) {
        if(data.success) {
            mAlert(data.judul,data.pesan,'success');
            setTimeout(function() {
                window.location.replace("{{url('/dashboard/sesi_soal')}}");
            }, 1500);
        } else {
            mAlert(data.judul,data.pesan,'danger');
        }
    });
}
$('.btn-tambah').click(function(e){
    e.preventDefault();
    var attr = "#"+$(this).attr("tujuan");
    var form = $(this).attr("data-form");
    var jenis = $(this).attr("data-jenis");
    tambah(form,jenis);
})
$(".btn-sesi").click(function(e){
    var vale = $(this).attr("id-paket-soal");
    $("#id-paket").val(vale);
})
    //var induk_sesi = $("#induk_sesi option:selected").val();
    $("#nama_sesi option[value='Matematika']").hide();
    $("#nama_sesi option[value='Fisika']").hide();
    $("#nama_sesi option[value='Kimia']").hide();
    $("#nama_sesi option[value='Biologi']").hide();
    var induk_sesi = $("#induk_sesi");
    induk_sesi.change(function(){
        if(induk_sesi.val() == "TPS"){
            $("#nama_sesi option[value='Matematika']").hide();
            $("#nama_sesi option[value='Fisika']").hide();
            $("#nama_sesi option[value='Kimia']").hide();
            $("#nama_sesi option[value='Biologi']").hide();
            $("#nama_sesi option[value='Penalaran Umum']").show();
            $("#nama_sesi option[value='Pemahaman Bacaan & Menulis']").show();
            $("#nama_sesi option[value='Pengatahuan & Pemahaman Umum']").show();
            $("#nama_sesi option[value='Penalaran Kuantitatif']").show();
            //$("#nama_sesi").append('<option value="IPS">IPS</option>');
        }else{
            $("#nama_sesi option[value='Penalaran Umum']").hide();
            $("#nama_sesi option[value='Pemahaman Bacaan & Menulis']").hide();
            $("#nama_sesi option[value='Pengatahuan & Pemahaman Umum']").hide();
            $("#nama_sesi option[value='Penalaran Kuantitatif']").hide();
            $("#nama_sesi option[value='Matematika']").show();
            $("#nama_sesi option[value='Fisika']").show();
            $("#nama_sesi option[value='Kimia']").show();
            $("#nama_sesi option[value='Biologi']").show();
        }
    })
    $('.pilih-sesi').click(function(e){
    e.preventDefault();
    var sesi = $(this).attr('sesi');
    var paket = $(this).attr('paket');
    window.location.replace("/dashboard/list_soal/"+sesi+"/"+paket);
});
</script>
@stop