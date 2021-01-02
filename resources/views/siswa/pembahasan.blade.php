@extends('template')
@section('title', 'Pembahasan BaseCampTO ')

@section('intro-header')
@endsection
@section('main')
<div class="sticky-top" style="margin:20px 12px;">
    <div id=""><button class="btn btn-info"><span class="   ">Pembahasan </span>&nbsp;&nbsp;&nbsp;<span  id="timer"></span></button><button class="btn btn-info" id="nomor_soal" data-toggle="modal" data-target="#myModal" style="margin-left:12px;"></button></div>
</div>
<div class="col-12 row row-imbang primary" style="margin-top:60px;margin-bottom:60px;">
    <div id="soal" name="soal" class="col-12">
        
    </div>
    <div id="footer" class="col-12 row justify-content-end" style="margin-top:12px;">
        <button style="margin-right:12px;" id="sebelumnya" class="btn btn-secondary">Sebelumnya</button><button class="btn btn-primary" id="berikutnya">Berikutnya</button>
    </div>
    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Nomor Soal</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="col-12 row" id="panel-control">
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

            </div>
        </div>
    </div>    
    <?php
    //total soal
    $a_soal=1;
    ?>
    <input type="hidden" id="nosoalupdate" value="{{$a_soal}}">
    <form method="post" action="{{url('/siswa/soal/bahas-launch')}}" style="margin-top:12px;">
        @csrf
        <input type="hidden" name="id" value="{{Auth::user()->id}}">
        <input type="hidden" name="id_paket" value="{{$paket->id}}">
        <input type="submit" id="lanjut" style="display:none;">
    </form>
    <script>
        $(document).ready(function(){
            var nosoal=$("#nosoalupdate");
            var soalke=nosoal.val();
            var totalSoal="<?php echo $t_soal;?>";
            //getjawaban
            $.get( "/siswa/soal/nav-bahas?id_siswa=<?php echo $id;?>&&id_sesi=<?php echo $sesi->id;?>&&nama=<?php echo $sesi->nama_sesi;?>", function( data ) {
                $( "#panel-control" ).html( data );
            });
            //getsoal
            $.get( "/siswa/soal/soal-bahas?idSesi=<?php echo $sesi->id;?>&&nomor="+nosoal.val()+"&&nama=<?php echo $sesi->nama_sesi;?>", function( data ) {
                $( "#soal" ).html( data );
                $("#nomor_soal").html(soalke);
            });
            $("#berikutnya").click(function(){
                if(nosoal.val()==totalSoal){
                    $("#finishModal").modal('toggle');
                        $("#pessan").html('Kamu akan dialihkan.');
                        $("#message").toggleClass('hilang');
                        setTimeout(function(){
                            $('#lanjut').trigger('click');
                         }, 1000);
                }else if(nosoal.val()<=totalSoal){
                    if(nosoal.val()==totalSoal-1){
                        $(this).html('Selesai');
                    }
                    $("#sebelumnya").prop('disabled', false);
                    //soalke=$('#nosoalupdate').val();
                    //soalke++;
                    var ss=parseInt(nosoal.val(), 10) + 1;
                    nosoal.val(ss);
                    //getsoal
                    $.get( "/siswa/soal/soal-bahas?idSesi=<?php echo $sesi->id;?>&&nomor="+nosoal.val()+"&&nama=<?php echo $sesi->nama_sesi;?>", function( data ) {
                        $( "#soal" ).html( data );
                        $("#nomor_soal").html(nosoal.val());
                    });
                }else if(nosoal.val()>20){
                    alert('Error');
                }else{
                    $("#sebelumnya").prop('disabled', false);
                    var ss=parseInt(nosoal.val(), 10) + 1;
                    nosoal.val(ss);
                    //getsoal
                    $.get( "/siswa/soal/soal-bahas?idSesi=<?php echo $sesi->id;?>&&nomor="+nosoal.val()+"&&nama=<?php echo $sesi->nama_sesi;?>", function( data ) {
                        $( "#soal" ).html( data );
                        $("#nomor_soal").html(nosoal.val());
                    });
                }
            });
            $("#sebelumnya").prop('disabled', true);
            $("#sebelumnya").click(function(){
                if(nosoal.val()==1){
                    $("#sebelumnya").prop('disabled', true);
                }else{
                    $("#berikutnya").html('Berikutnya');
                    $("#sebelumnya").prop('disabled', false);
                    //soalke=$('#nosoalupdate').val();
                    var ss=parseInt(nosoal.val(), 10) - 1;
                    nosoal.val(ss);
                    //getsoal
                    $.get( "/siswa/soal/soal-bahas?idSesi=<?php echo $sesi->id;?>&&nomor="+nosoal.val()+"&&nama=<?php echo $sesi->nama_sesi;?>", function( data ) {
                        $( "#soal" ).html( data );
                        $("#nomor_soal").html(nosoal.val());
                    });
                }
            })
            //panel control
        })
    </script>
</div>
@endsection