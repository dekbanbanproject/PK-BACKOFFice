@extends('layouts.pkclaim')
@section('title', 'PK-BACKOFFice || Ward')
   

@section('content')
   
    <?php  
        $ynow = date('Y')+543;
        $yb =  date('Y')+542;
        $mo =  date('m');
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
               border-top: 10px rgb(212, 106, 124) solid;
               border-radius: 50%;
               animation: sp-anime 0.8s infinite linear;
               }
               @keyframes sp-anime {
               100% { 
                   transform: rotate(360deg); 
               }
               }
               .is-hide{
               display:none;
               }
    </style>
      <?php
      use App\Http\Controllers\StaticController;
      use Illuminate\Support\Facades\DB;   
      $count_meettingroom = StaticController::count_meettingroom();
  ?>
    <div class="container-fluid">
        <div id="preloader">
            <div id="status">
                <div class="spinner">
                    
                </div>
            </div>
        </div>  
        
            <div class="row"> 
                <div class="col-xl-12 col-md-3">
                    <div class="main-card mb-3 card"> 
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="card-title ms-3 mt-3">Detail Ward</h4>
                            <p class="card-title-desc ms-3">รายละเอียดผู้ป่วยใน Admit แยกตึก</p>
                        </div>
                        <div class="col"></div>
                        <div class="col-md-2 text-end"> 
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive me-2 ms-2 mb-2">
                                {{-- <div class="table-responsive ms-3 me-3"> --}}
                                <table id="example" class="table table-hover table-sm dt-responsive nowrap"
                                style=" border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr style="font-size: 13px">
                                            
                                            <th width="5%" class="text-center">ลำดับ</th>  
                                            <th class="text-center" width="10%">รหัสตึก</th> 
                                            <th class="text-center">ตึก</th>
                                            <th class="text-center" >จำนวนผู้ป่วย</th>
                                            <th class="text-center" >ยังไม่ Note + Note เก่า</th>
                                            <th class="text-center">ข้าราชการยังไม่ Claimcode</th>
                                            <th class="text-center">คนไข้ประกันสังคม</th>   
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($data_ward as $item) 
                                            <tr style="font-size: 12px">                                                  
                                                <td class="text-center" width="5%">{{ $i++ }}</td>  
                                                <td class="text-center" width="0%">{{ $item->ward }}</td> 
                                                <td class="p-2">{{ $item->wardname }}</td> 
                                                <td class="text-center" width="10%">
                                                    <a href="{{url('check_warddetail/'.$item->ward)}}" target="_blank">
                                                        {{ $item->AA }}
                                                    </a>
                                                </td>  
                                                <td class="text-center" width="10%">{{ $item->BB }}</td>  
                                                <td class="text-center" width="10%">{{ $item->CC }}</td> 
                                                <td class="text-center" width="10%">{{ $item->DD }}</td>  
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> 
                        </div> 
                    </div> 
                </div>  
            </div>

            <div class="row"> 
                <div class="col-xl-12 col-md-3">
                    <div class="main-card mb-3 card"> 
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="card-title ms-3 mt-3">Detail Ward </h4>
                            <p class="card-title-desc ms-3">รายละเอียดผู้ป่วยใน ข้าราชการ OFC LGO ปี 65 ยังไม่ขอเลขอนุมัติ (ขอด้วย)</p>
                        </div>
                        <div class="col"></div>
                        <div class="col-md-2 text-end"> 
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive me-2 ms-2 mb-2"> 
                                <table id="example" class="table table-hover table-sm dt-responsive nowrap"
                                style=" border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr style="font-size: 13px">
                                            
                                            <th width="5%" class="text-center">ลำดับ</th>  
                                            <th class="text-center" width="10%">เดือน</th> 
                                            <th class="text-center">จำนวนผู้ป่วย</th>
                                            <th class="text-center" >มี CLAIMCODE</th>
                                            <th class="text-center" >ยังไม่ขอ CLAIMCODE</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- <?php $i = 1; ?>
                                        @foreach ($data_ward as $item) 
                                            <tr style="font-size: 12px">                                                  
                                                <td class="text-center" width="5%">{{ $i++ }}</td>  
                                                <td class="text-center" width="0%">{{ $item->ward }}</td> 
                                                <td class="p-2">{{ $item->wardname }}</td> 
                                                <td class="text-center" width="10%">
                                                    <a href="{{url('check_warddetail/'.$item->ward)}}" target="_blank">
                                                        {{ $item->AA }}
                                                    </a>
                                                </td>  
                                                <td class="text-center" width="10%">{{ $item->BB }}</td> 
                                            </tr>
                                        @endforeach --}}
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
        $(document).ready(function() {
            $('#example').DataTable();
            $('#example2').DataTable();
            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
            $('#datepicker2').datepicker({
                format: 'yyyy-mm-dd'
            });
              
        });
    </script>
    @endsection
