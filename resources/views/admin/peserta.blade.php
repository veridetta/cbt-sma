@extends('adminlte::page')

@section('title', 'Peserta')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Peserta</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">Peserta</li>
            </ol>
        </div>
    </div>
    <style>
    .content-wrapper{
        max-width:100% !important;
    }
    #TBpeserta tr .links {
        display:none;
    }

    #TBpeserta tr:hover .links {
        display:block;   
    }
    </style>
@stop
@section('content')
<div class="container-fluid col-12">
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pendaftar Minggu Ini</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <td class="text-center font-weight-bold">No</td>
                                <td class="text-center font-weight-bold">Nama</td>
                                <td class="text-center font-weight-bold">HP</td>
                                <td class="text-center font-weight-bold">Sekolah</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if($minggu->count()>0)
                                <?php $no=1;?>
                                @foreach($minggu->get() as $mingguan)
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$mingguan->name}}</td>
                                        <td>{{$mingguan->hp}}</td>
                                        <td>{{$mingguan->sekolah}}</td>
                                    </tr>
                                    <?php $no++;?>
                                @endforeach
                            @else
                            <tr><td  colspan="4" class="text-center">Tidak ada data</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sekolah Terdaftar</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <td class="text-center font-weight-bold">No</td>
                                <td class="text-center font-weight-bold">Nama Sekolah</td>
                                <td class="text-center font-weight-bold">Pendaftar</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if($sekolah->count()>0)
                                <?php $no=1;?>
                                @foreach($sekolah->get() as $sekolahan)
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>@if($sekolahan->sekolah=='')Tidak Mengisi @else {{$sekolahan->sekolah}}@endif</td>
                                        <td>{{$sekolahan->total}}</td>
                                    </tr>
                                   <?php $no++;?>
                                @endforeach
                            @else
                            <tr><td  colspan="4" class="text-center">Tidak ada data</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
                <h3 class="card-title">Daftar Peserta</h3>
        </div>
        <div class="card-body">
            <table
                id="table"
                data-toolbar="#toolbar"
                data-search="true"
                data-show-refresh="true"
                data-show-toggle="true"
                data-show-fullscreen="true"
                data-show-columns="true"
                data-show-columns-toggle-all="true"
                data-show-export="true"
                data-click-to-select="true"
                data-minimum-count-columns="2"
                data-show-pagination-switch="true"
                data-pagination="true"
                data-id-field="id"
                data-page-list="[10, 25, 50, 100, all]"
                data-show-footer="true"
                data-side-pagination="server"
                data-url="{{url('peserta/datatables')}}"
                data-response-handler="responseHandler">
            </table>
        </div>
    </div>
</div>
@stop
@section('js')
<script>
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
        }
    });
    var $table = $('#table')
  var $remove = $('#remove')
  var selections = []

  function getIdSelections() {
    return $.map($table.bootstrapTable('getSelections'), function (row) {
      return row.id
    })
  }

  function responseHandler(res) {
    $.each(res.rows, function (i, row) {
      row.state = $.inArray(row.id, selections) !== -1
    })
    return res
  }


  function operateFormatter(value, row, index) {
    return [
      '<a class="like" href="javascript:void(0)" title="Like">',
      '<i class="fa fa-heart"></i>',
      '</a>  ',
      '<a class="remove" href="javascript:void(0)" title="Remove">',
      '<i class="fa fa-trash"></i>',
      '</a>'
    ].join('')
  }

  window.operateEvents = {
    'click .like': function (e, value, row, index) {
      alert('You click like action, row: ' + JSON.stringify(row))
    },
    'click .remove': function (e, value, row, index) {
      $table.bootstrapTable('remove', {
        field: 'id',
        values: [row.id]
      })
    }
  }

  function totalTextFormatter(data) {
    return 'Total'
  }

  function totalNameFormatter(data) {
    return data.length
  }

  function totalPriceFormatter(data) {
    var field = this.field
    return  data.map(function (row) {
      return +row[field]
    }).reduce(function (sum, i) {
      return sum + i
    }, 0)
  }

  function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
      height: 550,
      columns: [
        [{
          title: 'Id',
          field: 'id',
          visible:false,
          align: 'center',
          valign: 'middle',
          sortable: true
        }, {
          title: 'Nomor',
          field: 'no',
          align: 'center',
          valign: 'middle',
          sortable: false,
          footerFormatter: totalTextFormatter
          
        }, {
          field: 'name',
          title: 'Nama Siswa',
          sortable: true,
          footerFormatter: totalNameFormatter,
          align: 'center'
        }, {
          field: 'hp',
          title: 'No HP',
          sortable: true,
          footerFormatter: totalNameFormatter,
          align: 'center'
        }, {
          field: 'email',
          title: 'Email',
          sortable: false,
          align: 'center',
          footerFormatter: totalPriceFormatter
        }, {
          field: 'sekolah',
          title: 'Sekolah',
          sortable: true,
          align: 'center',
          footerFormatter: totalPriceFormatter
        }, {
          field: 'created_at',
          title: 'Mendaftar',
          sortable: true,
          align: 'center',
          footerFormatter: totalPriceFormatter
        }, {
          field: 'operate',
          title: 'Action',
          align: 'center',
          clickToSelect: false,
          events: window.operateEvents,
          formatter: operateFormatter
        }]
      ]
    })
    $table.on('check.bs.table uncheck.bs.table ' +
      'check-all.bs.table uncheck-all.bs.table',
    function () {
      $remove.prop('disabled', !$table.bootstrapTable('getSelections').length)

      // save your data, here just save the current page
      selections = getIdSelections()
      // push or splice the selections if you want to save all data selections
    })
    $table.on('all.bs.table', function (e, nama, args) {
      console.log(nama, args)
    })
    $remove.click(function () {
      var ids = getIdSelections()
      $table.bootstrapTable('remove', {
        field: 'id',
        values: ids
      })
      $remove.prop('disabled', true)
    })
  }

  $(function() {
    initTable()

  })
</script>
@stop