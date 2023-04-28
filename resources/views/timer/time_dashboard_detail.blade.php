@extends('layouts.timesystem')
@section('title', 'PK-BACKOFFice || DASHBOARD')
  

@section('content')
   
    <?php  
        $ynow = date('Y')+543;
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

            @foreach ($dep_show_all as $item)  
             
            <?php 
                $depsub_count_ = DB::connection('mysql6')->select(' 
                    SELECT COUNT(p.ID) as CountID
                        FROM checkin_index c
                        LEFT JOIN checkin_type ct on ct.CHECKIN_TYPE_ID=c.CHECKIN_TYPE_ID
                        LEFT JOIN hrd_person p on p.ID=c.CHECKIN_PERSON_ID
                        LEFT JOIN hrd_department h on h.HR_DEPARTMENT_ID = p.HR_DEPARTMENT_ID
                        LEFT JOIN hrd_department_sub hs on hs.HR_DEPARTMENT_SUB_ID=p.HR_DEPARTMENT_SUB_ID
                        LEFT JOIN hrd_department_sub_sub d on d.HR_DEPARTMENT_SUB_SUB_ID=p.HR_DEPARTMENT_SUB_SUB_ID
                        
                        LEFT JOIN operate_job j on j.OPERATE_JOB_ID=c.OPERATE_JOB_ID
                        LEFT JOIN operate_type ot on ot.OPERATE_TYPE_ID=j.OPERATE_JOB_TYPE_ID
                        LEFT JOIN hrd_prefix f on f.HR_PREFIX_ID=p.HR_PREFIX_ID
                        LEFT JOIN hrd_position hp on hp.HR_POSITION_ID=p.HR_POSITION_ID
                        WHERE c.CHEACKIN_DATE = CURDATE()
                        AND hs.HR_DEPARTMENT_SUB_ID = "'.$item->HR_DEPARTMENT_SUB_ID.'"
                    
                ');
                foreach ($depsub_count_ as $key => $value) {
                    $depsub_count = $value->CountID;
                }
            ?>  
                      
                <div class="col-xl-4 col-md-3">
                    <div class="main-card mb-3 card" style="height: 120px">
                        <div class="grid-menu-col">
                            <div class="g-0 row">
                                <div class="col-sm-12">
                                    <div class="widget-chart widget-chart-hover"> 
                                            <div class="d-flex">
                                                <div class="flex-grow-1">                                                    
                                                    <p class="text-start font-size-14 mb-2">{{$item->HR_DEPARTMENT_SUB_NAME}}</p>   
                                                    <h4 class="text-start mb-2">{{$depsub_count}} คน</h4>                                                         
                                                </div>    
                                                <div class="avatar-sm me-2">
                                                    <a href="{{url('time_dashboard_detail_sub/'.$item->HR_DEPARTMENT_SUB_ID)}}" target="_blank">
                                                        <span class="avatar-title bg-light text-primary rounded-3" style="height: 100px">
                                                            <p style="font-size: 10px;"> 
                                                                <button type="button" class="btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-light text-primary rounded-3">
                                                                    <i class="pe-7s-search btn-icon-wrapper font-size-17 mt-2"></i>
                                                                    Detail
                                                                </button> 
                                                            </p>
                                                        </span> 
                                                    </a>
                                                </div>
                                            </div> 
                                    </div>                                           
                                </div>  
                            </div>                                           
                        </div> 
                    </div> 
                </div> 

            @endforeach
                  
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
