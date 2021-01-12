@extends('adminlte::page')

@section('title', 'Dashboard Paket Soal')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard Paket Soal</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">Paket Soal</li>
            </ol>
        </div>
    </div>
    <style>
    .hide{
        display:none;
    }
    .content-wrapper{
        max-width:100% !important;
    }
    </style>
@stop
@section('content')
<div class="container-fluid col-12">
    <div id="message" style="z-index:999;position:fixed;right:12px;bottom:12px;"></div>    
    <div class="row col-12">
        <div class="col-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h5>Kategori Paket</h5>
                </div>
                <div class="card-body">
                    <?php $no=1;?>
                    <table class="table">
                        <thead>
                            <tr>
                                <td class="text-center font-weight-bold">No</td>
                                <td class="text-center font-weight-bold">Nama</td>
                                <td class="text-center font-weight-bold">Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kat->get() as $kategori)
                                <form method="post" name="kategori{{$kategori->id}}" id="kategori{{$kategori->id}}">
                                @csrf
                                    <input type="hidden" value="{{$kategori->id}}" name="id">
                                    <tr>
                                        <td class="text-center" >{{$no}}</td>
                                        <td class="text-center"><input type="text" name="nama" class="form-control input-dekat" value="{{$kategori->nama}}" disabled id="nama_kategori" data-form="kategori{{$kategori->id}}"></td>
                                        <td class="text-center"><button class="btn-edit btn btn-primary btn-sm" tujuan="kategori{{$kategori->id}}" status="pasif"><i class="fas fa-edit"></i></button> &nbsp;<button class="btn-delete btn btn-danger btn-sm" tujuan="kategori{{$kategori->id}}" status="pasif"><i class="fas fa-trash"></i></button> &nbsp;<button class="btn-update btn-update-kategori{{$kategori->id}} btn btn-success btn-sm input-dekat" data-form="kategori{{$kategori->id}}" data-id="{{$kategori->id}}" disabled><i class="fas fa-check"></i></button></td>
                                    </tr>
                                </form>
                                <?php $no++;?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header">
                    <h5>Tambah Kategori Baru</h5>
                </div>
                <div class="card-body">
                    <form method="post" name="kategori" id="kategori">
                    @csrf
                        <div class="form-group">
                            <label for="nama">Nama Kategori Paket</label>
                            <div class="input-group">
                                <input type="text" name="nama" placeholder="Nama" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <button name="submit" class="btn-tambah btn btn-primary"  data-form="kategori" data-target="kategori" data-jenis="kategori"><i class="fas fa-fw fa-plus"></i> Tambah</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Tambah Paket Soal</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3 text-center my-auto">
                        <p class="display-3 text-secondary text-center"><i class="fas fa-fw fa-book"></i></p>
                    </div>
                    <div class="col-8">
                       <form methode="post" id="tambah_soal" name="tambah_soal">
                       @csrf
                        <div class="form-group">
                            <label for="nama">Nama Paket Soal</label>
                            <div class="input-group">
                                <input type="text" placeholder="Nama " name="nama" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nama">Kategori</label>
                                    <div class="input-group">
                                        <select class="form-control" name="kategori">
                                            @foreach($kat->get() as $kategori)
                                                <option value="{{$kategori->id}}">{{$kategori->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nama">Status</label>
                                    <div class="input-group">
                                        <select class="form-control" name="status">
                                        <?php
                                         $st=array('Idle','Terjadwal','Dimulai','Selesai','Pembahasan');
                                         for($s=0;$s<5;$s++){
                                            echo '<option value="'.$s.'">'.$st[$s].'</option>'; 
                                         }
                                         ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nama">Voucher</label>
                                    <div class="input-group">
                                        <input name="voucher" oninput="this.value = this.value.toUpperCase()" placeholder="Kode Voucher" class="form-control" id="voucher" maxlength="8">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nama">Bintang</label>
                                    <div class="input-group">
                                        <input type="number" name="bintang" class="form-control required" placeholder="Harga" >
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="validationTooltipUsernamePrepend"> <i class="text-warning fa fa-star"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nama">Dimulai</label>
                                    <div class="input-group">
                                        <input type="date" id="tgl_mulai" name="tgl_mulai" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nama">Selesai</label>
                                    <div class="input-group">
                                        <input type="date" id="tgl_selesai" name="tgl_selesai" class="form-control"  value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama">Keterangan</label>
                            <div class="input-group">
                                <textarea type="text" name="keterangan" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <button class="btn-tambah btn btn-success" data-form="tambah_soal" data-jenis="paket" data-target="tambah_soal"><i class="fas fa-plus"></i> Tambah Paket Soal</button>
                            </div>
                        </div>
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    @foreach($pak as $paket)
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <h5>{{$paket['nama']}}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3 text-center my-auto">
                        <p class="display-3 text-secondary text-center">{{$paket['total']}}</p>
                        <span class="badge badge-success"> Peserta Terdaftar</span>
                    </div>
                    <div class="col-8">
                       <form methode="post" id="edit_soal{{$paket['id']}}" name="edit_soal{{$paket['id']}}">
                       @csrf
                       <input type="hidden" value="{{$paket['id']}}" name="id" data-form="edit_soal{{$paket['id']}}">
                        <div class="form-group">
                            <label for="nama">Nama Paket Soal</label>
                            <div class="input-group">
                                <input type="text" name="nama" class="form-control" value="{{$paket['nama']}}" disabled  data-form="edit_soal{{$paket['id']}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nama">Kategori</label>
                                    <div class="input-group">
                                        <select class="form-control" name="kategori" disabled  data-form="edit_soal{{$paket['id']}}">
                                            @foreach($kat->get() as $kategori)
                                                @if($kategori->nama == $paket['kategori_nama'])
                                                <option value="{{$kategori->id}}" selected>{{$kategori->nama}}</option>
                                                @else
                                                <option value="{{$kategori->id}}">{{$kategori->nama}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nama">Status</label>
                                    <div class="input-group">
                                        <select class="form-control" name="status" data-form="edit_soal{{$paket['id']}}" disabled>
                                        <?php
                                         $st=array('Idle','Terjadwal','Dimulai','Selesai','Pembahasan');
                                         for($s=0;$s<5;$s++){
                                             if($s==$paket['status']){
                                                 echo '<option value="'.$s.'" selected>'.$st[$s].'</option>'; 
                                             }else{
                                                echo '<option value="'.$s.'">'.$st[$s].'</option>'; 
                                             }
                                         }
                                         ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nama">Voucher</label>
                                    <div class="input-group">
                                        <input name="voucher" oninput="this.value = this.value.toUpperCase()" placeholder="Kode Voucher" data-form="edit_soal{{$paket['id']}}" value="{{$paket['voucher']}}" class="form-control" id="voucher" maxlength="8" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nama">Bintang</label>
                                    <div class="input-group">
                                        <input type="number" name="bintang" id="bintang" class="form-control required" placeholder="Harga" data-form="edit_soal{{$paket['id']}}" value="{{$paket['bintang']}}" disabled>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="validationTooltipUsernamePrepend"> <i class="text-warning fa fa-star"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nama">Dimulai</label>
                                    <div class="input-group">
                                        <input type="date" id="tgl_mulai" data-form="edit_soal{{$paket['id']}}" name="tgl_mulai" class="form-control" disabled value="{{$paket['tgl_mulai']}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nama">Selesai</label>
                                    <div class="input-group">
                                        <input type="date" id="tgl_selesai" data-form="edit_soal{{$paket['id']}}" name="tgl_selesai" class="form-control" disabled value="{{$paket['tgl_selesai']}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama">Keterangan</label>
                            <div class="input-group">
                                <textarea type="text" name="keterangan" data-form="edit_soal{{$paket['id']}}" class="form-control" disabled>{{$paket['keterangan']}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <button class="btn-edit btn btn-primary btn-sm" tujuan="edit_soal{{$paket['id']}}" status="pasif"><i class="fas fa-edit"></i> Ubah</button> &nbsp;<button class="btn-update btn-update-edit_soal{{$paket['id']}} btn btn-success btn-sm" data-form="edit_soal{{$paket['id']}}" data-id="{{$paket['id']}}" disabled><i class="fas fa-check"></i> Selesai</button>
                            </div>
                        </div>
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body" id="modal_body">
                
            </div>
            <div class="modal-footer">
                <a href="#" data-form="'+form+'" data-action="'+action+'" data-id="'+id+'" id="konfirm_modal" class="btn btn-success">Konfirmasi</a>&nbsp;&nbsp;&nbsp;<a href="#" data-dismiss="modal">Batal</a>
            </div>
        </div>
    </div>
</div>
@stop
@section('js')
<script>
$(document).ready(function(){
$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
    }
});
function hapus(id,form,jenis){
    var tform='#'+form;
    var urle="/dashboard/action/delete_"+jenis+"_soal";
        $.ajax({
            type: "POST",
            url: urle,
            data: $(tform).serialize(),
            dataType: "json"
        }).done(function(data) {
            if(data.success) {
                $('#modal').modal('hide');
                mAlert(data.judul,data.pesan,'success');
                setTimeout(function() {
                    window.location.replace("{{url('/dashboard/paket_soal')}}");
                }, 1500);
            } else {
                mAlert(data.judul,data.pesan,'danger');
            }     
        });
}
function ubah(id,form,jenis){
    var tform='#'+form;
    var urle="/dashboard/action/update_"+jenis+"_soal";
        $.ajax({
            type: "POST",
            url: urle,
            data: $(tform).serialize(),
            dataType: "json"
        }).done(function(data) {
            if(data.success) {
                mAlert(data.judul,data.pesan,'success');
                setTimeout(function() {
                    window.location.replace("{{url('/dashboard/paket_soal')}}");
                }, 1500);
            } else {
                mAlert(data.judul,data.pesan,'danger');
            }     
        });
}
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
                window.location.replace("{{url('/dashboard/paket_soal')}}");
            }, 1500);
        } else {
            mAlert(data.judul,data.pesan,'danger');
        }
    });
}
function mAlert(judul,pesan,clas){
    $("#message").append('<div id="" class="alert alert-'+clas+' alert-dismissible fade show"><button type="button" class="close" data-dismiss="alert"> &times;</button><span id="message"><i class="fas fa-fw fa-bell"></i> '+pesan+'. &nbsp;&nbsp;</span></div>');
};
function mModal(judul,pesan,clas,action,form,id){
    $('#modal_body').html('<div id="" class="alert alert-'+clas+' alert-dismissible fade show" style="margin-top:12px;"><button type="button" class="close" data-dismiss="alert"> &times;</button><span id="message"><i class="fas fa-fw fa-bell"></i> '+pesan+'. &nbsp;&nbsp;</span></div><p class="text-center">');
    $('#konfirm_modal').attr('data-form',form);
    $('#konfirm_modal').attr('data-id',id);
    $('#konfirm_modal').attr('data-action',action);
    $('#modal').modal('show');
}
$("#konfirm_modal").click(function(e){
    var form = $(this).attr("data-form");
    var id = $(this).attr("data-id");
    var action = $(this).attr("data-action");
    if(action=="hapus"){
        hapus(id,form,"kategori");
    }
})
$(".btn-edit").click(function(e){
    e.preventDefault();
    //var form =$(this).closest('.input-dekat');
    //$(this).closest('form').find("[disabled]").length
    var attr = "*[data-form="+$(this).attr("tujuan");
    var btnup = ".btn-update-"+$(this).attr("tujuan");
    var status = $(this).attr('status');
    if(status=='aktif'){
        $(attr).prop("disabled",true);
        $(this).attr('status',"pasif");
        $(btnup).prop("disabled",true);
    }else{
        $(attr).prop("disabled",false);
        $(this).attr('status',"aktif");
        $(btnup).prop("disabled",false);
    }
    //form.attr('disabled','disabled');
})
$('.btn-delete').click(function(e){
    e.preventDefault();
    var attr = "#"+$(this).attr("tujuan");
    mModal('Delete','Yakin akan menghapus item ini?','danger','hapus',attr,4);
})
$('.btn-update').click(function(e){
    e.preventDefault();
    var attr = "#"+$(this).attr("tujuan");
    var form = $(this).attr("data-form");
    var id = $(this).attr("data-id");
    var action = $(this).attr("data-action");
    ubah(id,form,'paket');
})
$('.btn-tambah').click(function(e){
    e.preventDefault();
    var attr = "#"+$(this).attr("tujuan");
    var form = $(this).attr("data-form");
    var jenis = $(this).attr("data-jenis");
    tambah(form,jenis);
})
})
</script>
@stop