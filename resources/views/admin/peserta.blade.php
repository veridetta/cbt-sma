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
    <div class="table-responsive">
        <table class="display table table-bordered table-hover" cellspacing="0" width="100%" id = "TBpeserta" name ="TBpeserta">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>HP</th>
                    <th>Sekolah</th>
                    <th>Daftar</th>
                    <th>UserID</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
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
    editor = new $.fn.dataTable.Editor( {
        ajax: "../php/staff.php",
        table: "#TBpeserta",
        fields: [ {
                label: "Nama:",
                name: "name"
            }, {
                label: "Email:",
                name: "email"
            }, {
                label: "Sekolah:",
                name: "sekolah"
            }, {
                label: "HP:",
                name: "hp"
            }
        ]
    } );
    var dataTable = $('#TBpeserta').DataTable(
    {
        dom: "Bfrtip",
        "paging": true, // Allow data to be paged
         "lengthChange": false,
        "searching": true, // Search box and search function will be actived
        "ordering": true,
        "info": true,
        "autoWidth": true,
         "processing": true,  // Show processing 
         "serverSide": true,  // Server side processing
          "deferLoading": 0, // In this case we want the table load on request so initial automatical load is not desired
          "pageLength": 5,    // 5 rows per page
                "ajax":{
              url :  '',
                    type : "POST",
                    dataType: 'json',
                    error: function(data){
                        console.log(data);
                    }
                },
                // aoColumnDefs allow us to specify which column we want to make
                // sortable or column's alignment
                "aoColumnDefs": [
                { 'bSortable': true, 'aTargets': [0] }    ,
                { className: "dt-center", "aTargets": [1,2,3,4] },
            ],
            "columns": [
                    {data : "no"},
                    {data : "name"},
                    {data : "email"},          
                    {data : "hp"},
                    {data : "sekolah"},
                    {data : "created_at"},
                    { data:"id", visible: false },
                     //The last column will be invisible
                ],
                select: true,
            buttons: [
                { extend: "create", editor: editor },
                { extend: "edit",   editor: editor },
                {
                    extend: "selectedSingle",
                    text: "Salary +250",
                    action: function ( e, dt, node, config ) {
                        // Immediately add `250` to the value of the salary and submit
                        editor
                            .edit( table.row( { selected: true } ).index(), false )
                            .set( 'sekolah', (editor.get( 'sekolah' )*1) + 250 )
                            .submit();
                    }
                },
                { extend: "remove", editor: editor }
            ]

    });
    var resourceURL = "{{url('peserta/datatables')}}";
    /*
    * Change the URL of dataTable and call ajax to load new data
    */
    dataTable.ajax.url(resourceURL).load();
    dataTable.draw();
</script>
@stop