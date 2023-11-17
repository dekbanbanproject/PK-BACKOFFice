@extends('layouts.envnew')
@section('title', 'PK-BACKOFFice || ENV')
@section('content')
<script>
    function TypeAdmin() {
        window.location.href = '{{ route('index') }}';
    }
</script>
<?php
if (Auth::check()) {
    $type = Auth::user()->type;
    $iduser = Auth::user()->id;
} else {
    echo "<body onload=\"TypeAdmin()\"></body>";
    exit();
}
$url = Request::url();
$pos = strrpos($url, '/') + 1;
?>
<style>
    #button{
           display:block;
           margin:20px auto;
           padding:30px 30px;
           background-color:#eee;
           border:solid #ccc 1px;
           cursor: pointer;
           }
           #overlay{	
           position: fixed;
           top: 0;
           z-index: 100;
           width: 100%;
           height:100%;
           display: none;
           background: rgba(0,0,0,0.6);
           }
           .cv-spinner {
           height: 100%;
           display: flex;
           justify-content: center;
           align-items: center;  
           }
           .spinner {
           width: 250px;
           height: 250px;
           border: 10px #ddd solid;
           border-top: 10px #1fdab1 solid;
           border-radius: 50%;
           animation: sp-anime 0.8s infinite linear;
           }
           @keyframes sp-anime {
           100% { 
               transform: rotate(390deg); 
           }
           }
           .is-hide{
           display:none;
           }
</style>
<script>
    function TypeAdmin() {
        window.location.href = '{{ route('index') }}';
    }
</script>
  
<div class="tabs-animation">
    <div id="preloader">
        <div id="status">
            <div class="spinner">

            </div>
        </div>
    </div>

       

        <div class="main-card mb-3 card">
            <div class="card-header">
                รายงานคุณภาพน้ำทิ้ง
                
                <div class="btn-actions-pane-right">
                    <form action="{{ route('env.env_water_rep') }}" method="GET">
                        @csrf 
                            <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy"
                                    data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                                    <input type="text" class="form-control" name="startdate" id="datepicker" placeholder="Start Date"
                                        data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off" data-date-language="th-th" value="{{ $startdate }}" required/>
                                    <input type="text" class="form-control" name="enddate" placeholder="End Date" id="datepicker2"
                                        data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off" data-date-language="th-th" value="{{ $enddate }}" required/>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-magnifying-glass me-2"></i>
                                        ค้นหา
                                    </button>  
                            </div>  
                    </form>   
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-eg2-0" role="tabpanel">
                        <p>  
                                <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="example2">
                                    <thead>
                                        <tr>
                                            <th class="text-center"width="2%">ลำดับ</th> 
                                            <th class="text-center"width="2%">วันที่บันทึก</th>
                                            <th class="text-center"width="5%">รายการพารามิเตอร์</th>
                                            <th class="text-center"width="4%">ผู้บันทึก</th>
                                            <th class="text-center"width="5%">หมายเหตุ</th>
                                                                                    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $ia = 1; ?>
                                        {{-- @foreach ($datashow as $item)
                                            <tr>
                                                <td class="text-center">{{ $ia++ }}</td>
                                                <td class="text-center">{{DateThai ($item->water_date) }}</td> 
                                                <td class="text-center">{{ $item->water_location }}</td>   
                                                <td class="text-center">{{ $item->water_user }}</td> 
                                                <td class="text-center">{{ $item->water_comment }}</td>  
                                                                                                                                               
                                            </tr>
                                             
                                        @endforeach --}}
                                        
                                    </tbody>
                                </table> 
                        </p>
                    </div>
                     
                </div>
            </div>
            
        </div>
</div>  

      
@endsection
@section('footer')

<script>
    
    $(document).ready(function() {
        // $("#overlay").fadeIn(300);　

        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });
        $('#datepicker2').datepicker({
            format: 'yyyy-mm-dd'
        });

        $('#datepicker3').datepicker({
            format: 'yyyy-mm-dd'
        });
        $('#datepicker4').datepicker({
            format: 'yyyy-mm-dd'
        });

        
        $("#spinner-div").hide(); //Request is complete so hide spinner
 
    });
</script>
 
@endsection
 
 