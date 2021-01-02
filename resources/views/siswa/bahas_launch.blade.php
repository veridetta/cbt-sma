@extends('template')
@section('title', 'Pembahasan BaseCampTO ')

@section('intro-header')
@endsection
@section('main')
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
               // echo $tpp_hitung;
                foreach($tpp->get() as $tpps){
                    $hitung_so=DB::table('soal')->where('id_sesi_soal',$tpps->id);
                    $hitung_soal=$hitung_so->count();
                            ?>
                            <div class="col-md-3 col-12" style="margin-bottom:12px;">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <p class="card-title">{{$tpps->nama_sesi}}</p>
                                        </div>
                                        <div class="card-body">
                                        <p>Total Soal : {{$hitung_soal}} Soal
                                            <p>Durasi : {{$tpps->durasi}} Menit</p>
                                        </div>
                                        <div class="card-footer">
                                            <form method="post" action="{{url('/siswa/pembahasan')}}" name="mulai" id="mulai">
                                                @csrf
                                                <input type="hidden" name="durasi" value=" {{$tpps->durasi}}">
                                                <input type="hidden" name="idsoal" value=" {{$tpps->id}}">
                                                <input type="hidden" name="idsiswa" value=" {{Auth::user()->id}}">
                                                <input type="hidden" name="idpaket" value=" {{$tps->id}}">
                                                <button class="btn btn-primary btn-block">Lihat</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
})
</script>
@endsection