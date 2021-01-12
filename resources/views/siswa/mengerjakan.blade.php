@extends('template')
@section('title', 'Mengerjakan BaseCampTO ')

@section('intro-header')
<style>
#message {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
}
#inner-message {
    margin: 0 auto;
}
.hilang{
    display: none;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
@endsection
@section('main')
<div class="sticky-top" style="margin:20px 12px;">
    <div id=""><button class="btn btn-info"><span class="spinner-grow spinner-grow-sm"></span>&nbsp;&nbsp;&nbsp;<span  id="timer"></span></button><button class="btn btn-info" id="nomor_soal" data-toggle="modal" data-target="#myModal" style="margin-left:12px;"></button></div>
</div>
<div class="col-12 row row-imbang primary" style="margin-top:60px;margin-bottom:60px;">
    <div id="soal" name="soal" class="col-12">
        
    </div>
    <div id="footer" class="col-12 row justify-content-end">
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
    <!-- The Modal -->
    <div class="modal" id="finishModal">
        <div class="modal-dialog">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Yakin Selesai?</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="col-12 row" id="panel-control">
                    <p>Kamu yakin akan mengirimkan jawabanmu sekarang?</p>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
                <button type="button" id="yakinFinish" class="btn btn-success">Yakin</button>
            </div>

            </div>
        </div>
    </div>
    <div id="message">
        <div style="padding: 5px;">
            <div class="alert hilang alert-danger" id="pessan" role="alert">
                Waktu sudah habis, kamu akan dialhikan dalam 3 detik.
            </div>
        </div>
    </div>
    <?php
    //total soal
    $t_s=DB::table('soal')->where('id_sesi_soal',$sesi->id);
    $a_soal=1;
    $t_soal=$t_s->count();
    ?>
    <input type="hidden" id="nosoalupdate" value="{{$a_soal}}">
</div>
<script>
        $(document).ready(function(){
            var nosoal=$("#nosoalupdate");
            var soalke=nosoal.val();
            var totalSoal="<?php echo $t_soal;?>";
            var countDownDate = moment("<?php echo $akhir_ujian;?>").toDate();
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
                $("#message").toggleClass('hilang');
                setTimeout(function(){window.location.replace("/siswa/soal/launch/<?php echo $id_paket;?>"); }, 3000);
                
            }
            }, 1000);
            //getjawaban
            $.get("/siswa/soal/nav-soal/<?php echo $id;?>/<?php echo $sesi->id;?>/<?php echo $sesi->nama_sesi;?>", function( data ) {
                $( "#panel-control" ).html( data );
            });
            //getsoal
            $.get("/siswa/soal/soal/<?php echo $sesi->id;?>/"+nosoal.val()+"/<?php echo $sesi->nama_sesi;?>", function( data ) {
                $( "#soal" ).html( data );
                $("#nomor_soal").html(soalke);
            });
            $("#berikutnya").click(function(){
                if(nosoal.val()==totalSoal){
                    $("#finishModal").modal('toggle');
                    $("#yakinFinish").click(function(){
                        $.get( "/siswa/soal/finish/<?php echo $id;?>/"+nosoal.val()+"/<?php echo $user->id;?>", function( data ) {
                            if(data){
                                $("#pessan").html('Kamu akan dialihkan.');
                                $("#message").toggleClass('hilang');
                                setTimeout(function(){window.location.replace("/siswa/soal/launch/<?php echo $id_paket;?>"); }, 1000);
                            }
                        });
                    })
                }else if(nosoal.val()<=totalSoal){
                    if(nosoal.val()==totalSoal-1){
                        $(this).html('Selesai');
                    }
                    $("#sebelumnya").prop('disabled', false);
                    //soalke=$('#nosoalupdate').val();
                    var ss=parseInt(nosoal.val(), 10) + 1;
                    nosoal.val(ss);
                    //getsoal
                    $.get("/siswa/soal/soal/<?php echo $sesi->id;?>/"+nosoal.val()+"/<?php echo $sesi->nama_sesi;?>", function( data ) {
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
                    $.get("/siswa/soal/soal/<?php echo $sesi->id;?>/"+nosoal.val()+"/<?php echo $sesi->nama_sesi;?>", function( data ) {
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
                    $.get("/siswa/soal/soal/<?php echo $sesi->id;?>/"+nosoal.val()+"/<?php echo $sesi->nama_sesi;?>", function( data ) {
                        $( "#soal" ).html( data );
                        $("#nomor_soal").html(nosoal.val());
                    });
                }
            })
            //panel control
        })
    </script>
@stop
@section('js')
@stop