@extends('adminlte::page')

@section('title', 'Dashboard Try Out')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard Try Out</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">Soal</li>
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
    <section class="col-lg-12 connectedSortable ui-sortable">
        <div class="card card-info">
            <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                        Sedang Berlangsung
                </h3>
            </div><!-- /.card-header -->
            <div class="card-body">
                <p class="h4 text-secondary quoted">Try Out Welcome Rangers</p>
            </div>
        </div>
    </section>
    <div class="row">
        <section class="col-lg-7 connectedSortable ui-sortable">
            <div class="card">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Peserta Try Out
                    </h3>
                    <div class="card-tools">
                        <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                <a class="nav-link active charte" href="#pendaftar-chart" data-toggle="tab">Area</a>
                            </li>
                        </ul>
                    </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content p-0">
                        <!-- Morris chart - Sales -->
                        <div class="tab-pane active" id="pendaftar-chart">
                            <div id='chart-pendaftar'></div>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div>
        </section>
        <section class="col-lg-5 connectedSortable ui-sortable">
            <div class="card card-info">
                <div class="card-header ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">
                        <i class="fas fa-book mr-1"></i>
                        Paket Try Out
                    </h3>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content p-0">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="">
                                <tr>
                                    <td class="text-center font-weight-bold">No</td>
                                    <td class="text-center font-weight-bold">Nama Paket</td>
                                    <td class="text-center font-weight-bold">Pelaksanaan</td>
                                    <td class="text-center font-weight-bold">Peserta</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if($paket->count()>0)
                                    <?php $no=1;?>
                                    @foreach($paket->get() as $paketan)
                                        <tr>
                                            <td>{{$no}}</td>
                                            <td>{{$paketan->nama}}</td>
                                            <td>{{$paketan->tgl_mulai}}</td>
                                            <td class="text-center">{{$paketan->total}}</td>
                                        </tr>
                                        <?php $no++;?>
                                    @endforeach
                                @else
                                <tr><td  colspan="4" class="text-center">Tidak ada data</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div><!-- /.card-body -->
            </div>
        </section>
    </div>
</div>
@stop
@section('js')
<script>
var op_pendaftar = {
    series : [{
        name: 'Peserta Try Out',
        data: <?php echo json_encode($arr_pendaftar_to);?>
    }],
    chart: {
        height : 350,
        type : 'area'
    },
    dataLabels:{
        enabled:false
    },
    stroke : {
        curve : 'smooth'
    },
    xaxis : {
        categories : <?php echo json_encode($arr_to);?>
    },
};
var pendaftar = new ApexCharts(document.querySelector("#chart-pendaftar"), op_pendaftar);
pendaftar.render();
</script>
@stop