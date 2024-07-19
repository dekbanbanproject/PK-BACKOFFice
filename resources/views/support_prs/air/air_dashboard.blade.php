{{-- @extends('layouts.support_prs_new') --}}
@extends('layouts.support_prs_airback')
@section('title', 'PK-OFFICER || Support-System')

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
          
        <div id="preloader">
            <div id="status">
                <div id="container_spin">
                    <svg viewBox="0 0 100 100">
                        <defs>
                            <filter id="shadow">
                            <feDropShadow dx="0" dy="0" stdDeviation="2.5" 
                                flood-color="#fc6767"/>
                            </filter>
                        </defs>
                        <circle id="spinner" style="fill:transparent;stroke:#dd2476;stroke-width: 7px;stroke-linecap: round;filter:url(#shadow);" cx="50" cy="50" r="45"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="app-main__outer">
            <div class="app-main__inner">
                <div class="app-page-title app-page-title-simple">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                <div class="page-title-head center-elem">
                                    <span class="d-inline-block pe-2">
                                        <i class="lnr-apartment opacity-6" style="color:rgb(228, 8, 129)"></i>
                                    </span>
                                    <span class="d-inline-block"><h3>ตรวจสอบและบำรุงรักษา ระบบสนับสนุนบริการสุขภาพ Dashboard</h3></span>
                                </div>
                                <div class="page-title-subheading opacity-10">
                                    <nav class="" aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a>
                                                    <i aria-hidden="true" class="fa fa-home" style="color:rgb(252, 52, 162)"></i>
                                                </a>
                                            </li>
                                            <li class="breadcrumb-item">
                                                <a>Dashboards</a>
                                            </li>
                                            <li class="active breadcrumb-item" aria-current="page">
                                                Inspection and maintenance Dashboard
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions"> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- start page title -->
    
                    @foreach ($datashow as $item) 
                        <?php 
                            $namyod_air = DB::select('SELECT COUNT(b.repaire_sub_id) as namyod FROM air_repaire a 
                                LEFT JOIN air_repaire_sub b ON b.air_repaire_id = a.air_repaire_id 
                                WHERE a.air_supplies_id = "'.$item->air_supplies_id.'" AND b.air_repaire_ploblem_id = "1" AND b.air_repaire_type_code ="04"  
                            ');                                     
                            foreach ($namyod_air as $key => $value_air) {$namyod = $value_air->namyod;}

                            $lom_air = DB::select('SELECT COUNT(b.repaire_sub_id) as lomair FROM air_repaire a 
                                LEFT JOIN air_repaire_sub b ON b.air_repaire_id = a.air_repaire_id 
                                WHERE a.air_supplies_id = "'.$item->air_supplies_id.'" AND b.air_repaire_ploblem_id = "2" AND b.air_repaire_type_code ="04"  
                            ');                                     
                            foreach ($lom_air as $key => $lom_air) {$lomair = $lom_air->lomair;} 

                            $men_air = DB::select('SELECT COUNT(b.repaire_sub_id) as menair FROM air_repaire a 
                                LEFT JOIN air_repaire_sub b ON b.air_repaire_id = a.air_repaire_id 
                                WHERE a.air_supplies_id = "'.$item->air_supplies_id.'" AND b.air_repaire_ploblem_id = "3" AND b.air_repaire_type_code ="04"  
                            ');                                     
                            foreach ($men_air as $key => $air_men) {$menair = $air_men->menair;} 

                            $valumn_air = DB::select('SELECT COUNT(b.repaire_sub_id) as valumnair FROM air_repaire a 
                                LEFT JOIN air_repaire_sub b ON b.air_repaire_id = a.air_repaire_id 
                                WHERE a.air_supplies_id = "'.$item->air_supplies_id.'" AND b.air_repaire_ploblem_id = "4" AND b.air_repaire_type_code ="04"  
                            ');                                     
                            foreach ($valumn_air as $key => $air_valumn) {$valumnair = $air_valumn->valumnair;}

                            $dap_air = DB::select('SELECT COUNT(b.repaire_sub_id) as dapair FROM air_repaire a 
                                LEFT JOIN air_repaire_sub b ON b.air_repaire_id = a.air_repaire_id 
                                WHERE a.air_supplies_id = "'.$item->air_supplies_id.'" AND b.air_repaire_ploblem_id = "5" AND b.air_repaire_type_code ="04"  
                            ');                                     
                            foreach ($dap_air as $key => $air_dap) {$dapair = $air_dap->dapair;}
                            $aeun_air = DB::select('SELECT COUNT(b.repaire_sub_id) as aeunair FROM air_repaire a 
                                LEFT JOIN air_repaire_sub b ON b.air_repaire_id = a.air_repaire_id 
                                WHERE a.air_supplies_id = "'.$item->air_supplies_id.'" AND b.air_repaire_ploblem_id = "6" AND b.air_repaire_type_code ="04"  
                            ');                                     
                            foreach ($aeun_air as $key => $air_auen) {$aeunair = $air_auen->aeunair;}
                        ?>
                    <div class="row">
                        <div class="col-xl-4 col-md-6">
                            <div class="card widget-chart widget-chart-hover" style="height: 225px">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1"> 
                                            <p class="text-start font-size-14 mb-2">บริษัท {{$item->supplies_name}} (ครั้ง)</p>
                                            <h1 class="text-start mb-2">{{$item->c_repaire}}</h1> 
                                        </div> 
                                        <div class="avatar-sm" style="width: 100px;height:100px">
                                            <span class="avatar-title bg-light text-success rounded-3">
                                                @if ($item->air_supplies_id == '2')
                                                    <img src="{{ asset('images/building_community.png') }}" height="80px" width="80px" class="text-danger"> 
                                                @else
                                                    <img src="{{ asset('images/building_community_p.png') }}" height="80px" width="80px" class="text-danger"> 
                                                @endif 
                                            </span>
                                        </div>
                                    </div>  
                                    <div class="d-flex align-content-center flex-wrap mt-4">
                                        <p class="text-muted mb-0">
                                            <span class="text-info fw-bold font-size-20 me-2">
                                                <i class="ri-arrow-right-up-line me-1 align-middle"></i>0.00 %
                                            </span> 
                                        </p>
                                    </div> 
                                </div> 
                            </div> 
                        </div> 
                        <div class="col-xl-6 col-md-6">
                            <div class="row">
                                <div class="col-xl-4 col-md-4">
                                    <div class="card widget-chart widget-chart-hover" style="height: 100px">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-grow-1"> 
                                                    <p class="text-start font-size-14">น้ำหยด</p> 
                                                    <h3 class="text-start">{{$namyod}}</h3> 
                                                </div> 
                                                <div class="avatar-sm" style="width: 40px;height:40px">
                                                    <span class="avatar-title bg-light text-success rounded-3"> 
                                                        <i class="fa-solid fa-droplet" style="color: rgb(252, 90, 203);font-size:30px"></i> 
                                                    </span>
                                                </div>
                                            </div>  
                                            
                                        </div> 
                                    </div> 
                                </div> 
                                <div class="col-xl-4 col-md-4">
                                    <div class="card widget-chart widget-chart-hover" style="height: 100px">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-grow-1"> 
                                                    <p class="text-start font-size-14">มีกลิ่นเหม็น</p> 
                                                    <h3 class="text-start">{{$menair}}</h3> 
                                                </div> 
                                                <div class="avatar-sm" style="width: 40px;height:40px">
                                                    <span class="avatar-title bg-light text-success rounded-3"> 
                                                        <i class="fas fa-soap" style="color: rgb(253, 102, 15);font-size:30px"></i> 
                                                    </span>
                                                </div>
                                            </div>  
                                            
                                        </div> 
                                    </div> 
                                </div>
                                <div class="col-xl-4 col-md-4">
                                    <div class="card widget-chart widget-chart-hover" style="height: 100px">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-grow-1"> 
                                                    <p class="text-start font-size-14">เสียงดัง</p> 
                                                    <h3 class="text-start">{{$valumnair}}</h3> 
                                                </div> 
                                                <div class="avatar-sm" style="width: 40px;height:40px">
                                                    <span class="avatar-title bg-light text-success rounded-3"> 
                                                        <i class="fa-solid fa-volume-high" style="color: rgb(10, 132, 231);font-size:30px"></i> 
                                                    </span>
                                                </div>
                                            </div>  
                                            
                                        </div> 
                                    </div> 
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-xl-4 col-md-4">
                                    <div class="card widget-chart widget-chart-hover" style="height: 100px">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-grow-1"> 
                                                    <p class="text-start font-size-14">ไม่เย็นมีแต่ลม</p> 
                                                    <h3 class="text-start">{{$lomair}}</h3> 
                                                </div> 
                                                <div class="avatar-sm" style="width: 40px;height:40px">
                                                    <span class="avatar-title bg-light text-success rounded-3"> 
                                                        <i class="fa-solid fa-fan" style="color:rgb(5, 179, 170);font-size:30px"></i> 
                                                    </span>
                                                </div>
                                            </div>  
                                        </div> 
                                    </div> 
                                </div> 
                                <div class="col-xl-4 col-md-4">
                                    <div class="card widget-chart widget-chart-hover" style="height: 100px">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-grow-1"> 
                                                    <p class="text-start font-size-14">ไม่ติด/ติดๆดับๆ</p> 
                                                    <h3 class="text-start">{{$dapair}}</h3> 
                                                </div> 
                                                <div class="avatar-sm" style="width: 40px;height:40px">
                                                    <span class="avatar-title bg-light text-success rounded-3"> 
                                                        <i class="fa-solid fa-tenge-sign" style="color:rgb(250, 128, 138);font-size:30px"></i> 
                                                    </span>
                                                </div>
                                            </div>  
                                        </div> 
                                    </div> 
                                </div> 
                                <div class="col-xl-4 col-md-4">
                                    <div class="card widget-chart widget-chart-hover" style="height: 100px">
                                        <div class="card-body">
                                            <div class="d-flex">
                                                <div class="flex-grow-1"> 
                                                    <p class="text-start font-size-14">อื่นๆ</p> 
                                                    <h3 class="text-start">{{$aeunair}}</h3> 
                                                </div> 
                                                <div class="avatar-sm" style="width: 40px;height:40px">
                                                    <span class="avatar-title bg-light text-success rounded-3"> 
                                                        <i class="fab fa-slack" style="color:rgb(8, 184, 228);font-size:30px"></i> 
                                                    </span>
                                                </div>
                                            </div>  
                                        </div> 
                                    </div> 
                                </div>
                            </div>
                        </div> 
                        <div class="col"></div>
                      
                        <hr style="color:#ffffff">
                    </div> 
                    @endforeach
                    
               
            {{-- </div> --}}
            {{-- <div class="col"></div> --}}
        {{-- </div> --}}
        <!-- end page title -->
        
 
@endsection
@section('footer')   
@endsection

