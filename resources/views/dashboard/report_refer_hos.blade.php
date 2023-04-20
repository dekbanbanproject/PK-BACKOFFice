@extends('layouts.report_font')
@section('title', 'PK-BACKOFFice || REFER')
@section('content')
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
  
<div class="tabs-animation">
    
        <div class="row text-center">  
            <div id="overlay">
                <div class="cv-spinner">
                  <span class="spinner"></span>
                </div>
              </div>
              
        </div> 
        <div class="row"> 
            <div class="col-md-12"> 
                 <div class="main-card mb-3 card">
                    <div class="card-header">
                        Report REFER Hos
                        <div class="btn-actions-pane-right">
                            <div role="group" class="btn-group-sm btn-group">  
                             
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('rep.report_refer_hos') }}" method="GET" >
                            @csrf
                            <div class="row mt-3"> 
                                <div class="col"></div>
                                <div class="col-md-1 text-end">วันที่</div>
                                <div class="col-md-2 text-center">
                                    <div class="input-group" id="datepicker1">
                                        <input type="text" class="form-control" name="startdate" id="datepicker"  data-date-container='#datepicker1'
                                            data-provide="datepicker" data-date-autoclose="true" data-date-language="th-th"
                                            value="{{ $start }}">
                    
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-1 text-center">ถึงวันที่</div>
                                <div class="col-md-2 text-center">
                                    <div class="input-group" id="datepicker1">
                                        <input type="text" class="form-control" name="enddate" id="datepicker2" data-date-container='#datepicker1'
                                            data-provide="datepicker" data-date-autoclose="true" data-date-language="th-th"
                                            value="{{ $end }}">
                    
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>    
                                <div class="col-md-4">
                                
                                    <button type="submit" class="mb-2 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info">
                                        <i class="pe-7s-search btn-icon-wrapper"></i>ค้นหา
                                    </button>
                                   
                                </div>  
                                 <div class="col"></div>   
                            </div> 
                        </form>
                        
                        <div class="table-responsive mt-3">
                            <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>department</th> 
                                        <th>hn</th>
                                        <th>ชื่อ-นามสกุล</th> 
                                        <th>refer_date</th>
                                        <th>vstdate</th>
                                        <th>vsttime</th> 
                                        <th>doctor_name</th> 
                                        <th>hospmain</th>
                                        <th>hospname</th> 
                                        <th>AMBULANCE</th> 
                                        <th>พยาบาล</th>                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $number = 0; ?>
                                    @foreach ($datashow_ as $inforefer)
                                    <?php $number++; ?>
    
                                        <tr height="20">
                                            <td class="text-font" style="text-align: center;">{{$number}}</td>  
                                            <td class="text-font text-pedding" style="text-align: left;">{{$inforefer->department}}</td>
                                            <td class="text-font text-pedding" style="text-align: left;">{{$inforefer->hn}}</td>
                                            <td class="text-font text-pedding" style="text-align: left;">{{$inforefer->ptname}}</td>
                                            <td class="text-font text-pedding" style="text-align: left;">{{DateThai($inforefer->refer_date)}}</td>  
                                            <td class="text-font text-pedding" style="text-align: left;">{{DateThai($inforefer->vstdate)}}</td>
                                            <td class="text-font text-pedding" style="text-align: left;">{{$inforefer->vsttime}}</td>
                                            <td class="text-font text-pedding" style="text-align: left;">{{$inforefer->doctor_name}}</td>
                                            <td class="text-font text-pedding" style="text-align: left;">{{$inforefer->hospmain}}</td>
                                            <td class="text-font text-pedding" style="text-align: left;">{{$inforefer->hospname}}</td>
                                            <td class="text-font text-pedding" style="text-align: left;">{{$inforefer->with_ambulance}}</td>
                                            <td class="text-font text-pedding" style="text-align: left;">{{$inforefer->with_nurse}}</td>  
                                        </tr>
    
    
                                        @endforeach 
                                    
                                </tbody>
                            </table>
                        </div>
                
                    </div>
                </div>
            </div>            
        </div>
</div> 


      
@endsection
@section('footer')

<script>
     window.setTimeout(function() {             
            window.location.reload();
        },500000);
    $(document).ready(function() {
        // $("#overlay").fadeIn(300);　

        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });
        $('#datepicker2').datepicker({
            format: 'yyyy-mm-dd'
        });

        $('#example').DataTable();

        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
        });

        $("#spinner-div").hide(); //Request is complete so hide spinner
       
    });
</script>
@endsection
 
 