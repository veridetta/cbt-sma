@extends('adminlte::page')

@section('title', 'Dashboard Nilai Siswa')
@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Dashboard Nilai Siswa</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">Nilai Siswa</li>
            </ol>
        </div>
    </div>
    <style>
    .content-wrapper{
        max-width:100% !important;
    }
    .hidden{
        display:none;
    }
    </style>
@stop
@section('content')
<div class="container-fluid col-12">
    <div id="message" style="z-index:999;position:fixed;right:12px;bottom:12px;"></div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <p class="h4">Nilai Siswa</p>
                </div>
                <div class="col-6 text-right">
                    <a href="{{url()->previous()}}" class="btn btn-primary">Back</a>
                </div>
            </div>
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
      '<a class="btn-detail btn btn-info" href="javascript:viod(0)" title="Detail">',
      '<i class="fas fa-info"></i> Detail',
      '</a>  '
    ].join('')
  }

  window.operateEvents = {
    'click .btn-detail': function (e, value, row, index) {
        //"'+row.id+'
      //alert('You click like action, row: ' + JSON.stringify(row.id))
      window.location.replace("/dashboard/nilai_siswa/detail/"+row.id);
    },
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