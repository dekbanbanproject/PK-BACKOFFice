@extends('layouts.support_prs_airback')
@section('title', 'PK-OFFICER || Air-Service')

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

    
    <?php
    $ynow = date('Y') + 543;
    $yb = date('Y') + 542;
    ?>

<div class="tabs-animation">
 
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
    <form action="{{ url('air_report_month') }}" method="GET">
        @csrf
        <div class="row"> 
            <div class="col-md-6">
                <h4 style="color:rgb(10, 151, 85)">รายงานปัญหาที่มีการแจ้งซ่อมเครื่องปรับอากาศ รายเดือน </h4> 
            </div>
             
            <div class="col"></div>
            <div class="col-md-4 text-end"> 
                <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker1'>
                    <input type="text" class="form-control bt_prs" name="startdate" value="{{$startdate}}" id="datepicker" placeholder="Start Date" data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                        data-date-language="th-th" required/>
                    <input type="text" class="form-control bt_prs" name="enddate" value="{{$enddate}}" placeholder="End Date" id="datepicker2" data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                        data-date-language="th-th" />  
                        <button type="submit" class="ladda-button btn-pill btn btn-info bt_prs" data-style="expand-left">
                            <span class="ladda-label"> <i class="fa-solid fa-magnifying-glass text-white me-2"></i>ค้นหา</span> 
                        </button>  
                        {{-- <a href="" target="_blank" class="ladda-button me-2 btn-pill btn btn-warning bt_prs">  
                            <i class="fa-solid fa-print me-2 text-white" style="font-size:13px"></i>
                            <span>Print</span> 
                        </a>  --}}
                        <button type="button" class="ladda-button me-2 btn-pill btn btn-warning bt_prs">  
                            <i class="fa-solid fa-print me-2 text-white" style="font-size:13px"></i>
                            <span>Print</span> 
                        </button> 
                </div> 
            </div> 
        </div>  
    </form>
 
        <div class="row mt-3">
            <div class="col-xl-12">
                <div class="card card_prs_4">
                    <div class="card-body">  
                       @if ($startdate != '')
                            <p class="mb-0">
                                <div class="table-responsive">
                                    <table id="example" class="table table-hover table-sm dt-responsive nowrap myTable" style=" border-spacing: 0; width: 100%;">                     
                                        <thead>                             
                                                <tr style="font-size:13px"> 
                                                    <th width="3%" class="text-center">ลำดับ</th>  
                                                    <th class="text-center">เดือน</th>  
                                                    <th class="text-center">AIR ทั้งหมด(เครื่อง)</th> 
                                                    <th class="text-center">AIR ที่ซ่อม(เครื่อง)</th>   
                                                    <th class="text-center">ปัญหาซ่อม AIR(รายการ)</th> 
                                                    <th class="text-center">แผนการบำรุงรักษา(ครั้ง)</th> 
                                                    <th class="text-center">ผลการบำรุงรักษา(ครั้ง)</th> 
                                                    <th class="text-center">ร้อยละ AIR ที่ซ่อม</th> 
                                                    <th class="text-center">ร้อยละ AIR ที่บำรุงรักษา</th>  
                                                </tr>  
                                        </thead>
                                        <tbody>
                                            <?php $i = 0; ?>
                                            @foreach ($datashow as $item) 
                                            <?php $i++  ?>
                                            <?php  
                                                    $repaire_air = DB::select('SELECT COUNT(DISTINCT air_list_num) as air_problems FROM air_repaire WHERE repaire_date BETWEEN "'.$startdate.'" AND "'.$enddate.'"');                                     
                                                    foreach ($repaire_air as $key => $rep_air) {$airproblems = $rep_air->air_problems;}

                                                    $repaire_air_pro = DB::select('SELECT COUNT(b.repaire_sub_id) as air_problems04 FROM air_repaire a 
                                                    LEFT JOIN air_repaire_sub b ON b.air_repaire_id = a.air_repaire_id
                                                    WHERE a.repaire_date BETWEEN "'.$startdate.'" AND "'.$enddate.'" AND b.air_repaire_type_code ="04"');                                     
                                                    foreach ($repaire_air_pro as $key => $rep_air_pro) {$airproblems04 = $rep_air_pro->air_problems04;}
                                                    

                                                    $repaire_air_plan = DB::select('SELECT COUNT(b.repaire_sub_id) as air_problems_plan FROM air_repaire a 
                                                    LEFT JOIN air_repaire_sub b ON b.air_repaire_id = a.air_repaire_id
                                                    WHERE a.repaire_date BETWEEN "'.$startdate.'" AND "'.$enddate.'" AND b.air_repaire_type_code IN("01","02","03")');                                     
                                                    foreach ($repaire_air_plan as $key => $rep_air_plan) {$airproblems_plan = $rep_air_plan->air_problems_plan;}

                                                    $percent_ploblames =  (100 / $count_air) * $airproblems;
                                                    $percent_plan      =  (100 / $count_air) * $airproblems_plan;
                                                    
                                            ?>                    
                                                <tr>                                                  
                                                    <td class="text-center" style="font-size:13px;width: 5%;color: rgb(13, 134, 185)">{{$i}}</td>
                                                    <td class="text-start" style="font-size:14px;color: rgb(2, 95, 182)">{{$item->MONTH_NAME}} พ.ศ. {{$item->years_ps}}</td> 
                                                    <td class="text-center" style="font-size:13px;width: 10%;color: rgb(112, 5, 98)">
                                                        {{-- <a href="{{url('air_report_problem_group/'.$item->repaire_date_start.'/'.$item->repaire_date_end)}}" class="ladda-button btn-pill btn btn-sm card_prs_4" style="background-color: rgb(209, 181, 236);width: 70%;" target="_blank"> --}}
                                                            <span class="ladda-label"> {{$count_air}}</span>  
                                                        {{-- </a>   --}}
                                                    </td>
                                                    <td class="text-center" style="font-size:13px;width: 8%;color: rgb(253, 65, 81)">
                                                        {{-- <a href="{{url('air_report_problem_morone/'.$item->repaire_date_start.'/'.$item->repaire_date_end)}}" class="ladda-button btn-pill btn btn-sm card_prs_4" style="background-color: rgb(250, 195, 200);width: 50%;" target="_blank"> --}}
                                                            <span class="ladda-label"> {{$airproblems}}</span>  
                                                        {{-- </a>  --}}
                                                    </td>
                                                    <td class="text-center" style="font-size:13px;width: 8%;color: rgb(252, 90, 203)">
                                                        {{-- <a href="{{url('air_report_problem_morone/'.$item->repaire_date_start.'/'.$item->repaire_date_end)}}" class="ladda-button btn-pill btn btn-sm card_prs_4" style="background-color: rgb(250, 195, 200);width: 50%;" target="_blank"> --}}
                                                            <span class="ladda-label">{{$airproblems04}}</span>  
                                                        {{-- </a>  --}}
                                                    </td>
                                                    <td class="text-center" style="font-size:13px;width: 8%;color: rgb(5, 179, 170)">
                                                        {{-- <a href="{{url('air_report_problem_morone/'.$item->repaire_date_start.'/'.$item->repaire_date_end)}}" class="ladda-button btn-pill btn btn-sm card_prs_4" style="background-color: rgb(250, 195, 200);width: 50%;" target="_blank"> --}}
                                                            <span class="ladda-label">{{$airproblems_plan}}</span>  
                                                        {{-- </a>  --}}
                                                    </td>
                                                    <td class="text-center" style="font-size:13px;width: 8%;color: rgb(253, 102, 15)">
                                                        {{-- <a href="{{url('air_report_problem_morone/'.$item->repaire_date_start.'/'.$item->repaire_date_end)}}" class="ladda-button btn-pill btn btn-sm card_prs_4" style="background-color: rgb(250, 195, 200);width: 50%;" target="_blank"> --}}
                                                            <span class="ladda-label"> 0</span>  
                                                        {{-- </a>  --}}
                                                    </td>
                                                    <td class="text-center" style="font-size:13px;width: 8%;color: rgb(10, 132, 231)">
                                                        {{-- <a href="{{url('air_report_problem_morone/'.$item->repaire_date_start.'/'.$item->repaire_date_end)}}" class="ladda-button btn-pill btn btn-sm card_prs_4" style="background-color: rgb(250, 195, 200);width: 50%;" target="_blank"> --}}
                                                            <span class="ladda-label">{{number_format($percent_ploblames, 2)}} %</span>  
                                                        {{-- </a>  --}}
                                                    </td>
                                                    <td class="text-center" style="font-size:13px;width: 8%;color: rgb(250, 128, 138)">
                                                        {{-- <a href="{{url('air_report_problem_morone/'.$item->repaire_date_start.'/'.$item->repaire_date_end)}}" class="ladda-button btn-pill btn btn-sm card_prs_4" style="background-color: rgb(250, 195, 200);width: 50%;" target="_blank"> --}}
                                                            <span class="ladda-label"> {{number_format($percent_plan, 2)}} %</span>  
                                                        {{-- </a>  --}}
                                                    </td>
                                                  
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                       @else 
                            <p class="mb-0">
                                <div class="table-responsive">
                                    <table id="example" class="table table-hover table-sm dt-responsive nowrap myTable" style=" border-spacing: 0; width: 100%;">                       
                                        <thead>                             
                                                <tr style="font-size:13px"> 
                                                    <th width="3%" class="text-center">ลำดับ</th>  
                                                    <th class="text-center">เดือน</th>  
                                                    <th class="text-center">AIR ทั้งหมด(เครื่อง)</th> 
                                                    <th class="text-center">AIR ที่ซ่อม(เครื่อง)</th>   
                                                    <th class="text-center">ปัญหาซ่อม AIR(รายการ)</th> 
                                                    <th class="text-center">แผนการบำรุงรักษา(ครั้ง)</th> 
                                                    <th class="text-center">ผลการบำรุงรักษา(ครั้ง)</th> 
                                                    <th class="text-center">ร้อยละ AIR ที่ซ่อม</th> 
                                                    <th class="text-center">ร้อยละ AIR ที่บำรุงรักษา</th>  
                                                </tr>  
                                        </thead>
                                        <tbody>
                                            <?php $i = 0; ?>
                                            @foreach ($datashow as $item) 
                                            <?php $i++  ?>
                                            <?php 
                                                    $repaire_air = DB::select('SELECT COUNT(DISTINCT air_list_num) as air_problems FROM air_repaire WHERE YEAR(repaire_date) = "'.$item->years.'" AND MONTH(repaire_date) = "'.$item->months.'"');                                     
                                                    foreach ($repaire_air as $key => $rep_air) {$airproblems = $rep_air->air_problems;}

                                                    $repaire_air_pro = DB::select('SELECT COUNT(b.repaire_sub_id) as air_problems04 FROM air_repaire a 
                                                    LEFT JOIN air_repaire_sub b ON b.air_repaire_id = a.air_repaire_id
                                                    WHERE YEAR(a.repaire_date) = "'.$item->years.'" AND MONTH(a.repaire_date) = "'.$item->months.'" AND b.air_repaire_type_code ="04"');                                     
                                                    foreach ($repaire_air_pro as $key => $rep_air_pro) {$airproblems04 = $rep_air_pro->air_problems04;}
                                                    

                                                    $repaire_air_plan = DB::select('SELECT COUNT(b.repaire_sub_id) as air_problems_plan FROM air_repaire a 
                                                    LEFT JOIN air_repaire_sub b ON b.air_repaire_id = a.air_repaire_id
                                                    WHERE YEAR(a.repaire_date) = "'.$item->years.'" AND MONTH(a.repaire_date) = "'.$item->months.'" AND b.air_repaire_type_code IN("01","02","03")');                                     
                                                    foreach ($repaire_air_plan as $key => $rep_air_plan) {$airproblems_plan = $rep_air_plan->air_problems_plan;}

                                                    $percent_ploblames =  (100 / $count_air) * $airproblems;
                                                    $percent_plan      =  (100 / $count_air) * $airproblems_plan;
                                                     
                                            ?>                    
                                                <tr>                                                  
                                                    <td class="text-center" style="font-size:13px;width: 5%;color: rgb(13, 134, 185)">{{$i}}</td>
                                                    <td class="text-start" style="font-size:14px;color: rgb(2, 95, 182)">{{$item->MONTH_NAME}} พ.ศ. {{$item->years_ps}}</td> 
                                                    <td class="text-center" style="font-size:13px;width: 10%;color: rgb(112, 5, 98)">
                                                        {{-- <a href="{{url('air_report_problem_group/'.$item->repaire_date_start.'/'.$item->repaire_date_end)}}" class="ladda-button btn-pill btn btn-sm card_prs_4" style="background-color: rgb(209, 181, 236);width: 70%;" target="_blank"> --}}
                                                            <span class="ladda-label"> {{$count_air}}</span>  
                                                        {{-- </a>   --}}
                                                    </td>
                                                    <td class="text-center" style="font-size:13px;width: 8%;color: rgb(253, 65, 81)">
                                                        {{-- <a href="{{url('air_report_problem_morone/'.$item->repaire_date_start.'/'.$item->repaire_date_end)}}" class="ladda-button btn-pill btn btn-sm card_prs_4" style="background-color: rgb(250, 195, 200);width: 50%;" target="_blank"> --}}
                                                            <span class="ladda-label"> {{$airproblems}}</span>  
                                                        {{-- </a>  --}}
                                                    </td>
                                                    <td class="text-center" style="font-size:13px;width: 8%;color: rgb(252, 90, 203)">
                                                        {{-- <a href="{{url('air_report_problem_morone/'.$item->repaire_date_start.'/'.$item->repaire_date_end)}}" class="ladda-button btn-pill btn btn-sm card_prs_4" style="background-color: rgb(250, 195, 200);width: 50%;" target="_blank"> --}}
                                                            <span class="ladda-label">{{$airproblems04}}</span>  
                                                        {{-- </a>  --}}
                                                    </td>
                                                    <td class="text-center" style="font-size:13px;width: 8%;color: rgb(5, 179, 170)">
                                                        {{-- <a href="{{url('air_report_problem_morone/'.$item->repaire_date_start.'/'.$item->repaire_date_end)}}" class="ladda-button btn-pill btn btn-sm card_prs_4" style="background-color: rgb(250, 195, 200);width: 50%;" target="_blank"> --}}
                                                            <span class="ladda-label">{{$airproblems_plan}}</span>  
                                                        {{-- </a>  --}}
                                                    </td>
                                                    <td class="text-center" style="font-size:13px;width: 8%;color: rgb(253, 102, 15)">
                                                        {{-- <a href="{{url('air_report_problem_morone/'.$item->repaire_date_start.'/'.$item->repaire_date_end)}}" class="ladda-button btn-pill btn btn-sm card_prs_4" style="background-color: rgb(250, 195, 200);width: 50%;" target="_blank"> --}}
                                                            <span class="ladda-label"> 0</span>  
                                                        {{-- </a>  --}}
                                                    </td>
                                                    <td class="text-center" style="font-size:13px;width: 8%;color: rgb(10, 132, 231)">
                                                        {{-- <a href="{{url('air_report_problem_morone/'.$item->repaire_date_start.'/'.$item->repaire_date_end)}}" class="ladda-button btn-pill btn btn-sm card_prs_4" style="background-color: rgb(250, 195, 200);width: 50%;" target="_blank"> --}}
                                                            <span class="ladda-label">{{number_format($percent_ploblames, 2)}} %</span>  
                                                        {{-- </a>  --}}
                                                    </td>
                                                    <td class="text-center" style="font-size:13px;width: 8%;color: rgb(250, 128, 138)">
                                                        {{-- <a href="{{url('air_report_problem_morone/'.$item->repaire_date_start.'/'.$item->repaire_date_end)}}" class="ladda-button btn-pill btn btn-sm card_prs_4" style="background-color: rgb(250, 195, 200);width: 50%;" target="_blank"> --}}
                                                            <span class="ladda-label"> {{number_format($percent_plan, 2)}} %</span>  
                                                        {{-- </a>  --}}
                                                    </td>
                                                     
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                        @endif 
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
           
            // $('select').select2();
     
        
            // $('#example2').DataTable();
            // var table = $('#example').DataTable({
            //     scrollY: '60vh',
            //     scrollCollapse: true,
            //     scrollX: true,
            //     "autoWidth": false,
            //     "pageLength": 10,
            //     "lengthMenu": [10,25,30,31,50,100,150,200,300],
            // });
        
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#Processdata').click(function() {
                var startdate    = $('#datepicker').val(); 
                var enddate      = $('#datepicker2').val(); 
                Swal.fire({
                        position: "top-end",
                        title: 'ต้องการประมวลผลข้อมูลใช่ไหม ?',
                        text: "You Warn Process Data!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, pull it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $("#overlay").fadeIn(300);　
                                $("#spinner").show(); //Load button clicked show spinner 
                                
                                $.ajax({
                                    url: "{{ route('prs.air_report_problem_process') }}",
                                    type: "POST",
                                    dataType: 'json',
                                    data: {startdate,enddate},
                                    success: function(data) {
                                        if (data.status == 200) { 
                                            Swal.fire({
                                                position: "top-end",
                                                title: 'ประมวลผลข้อมูลสำเร็จ',
                                                text: "You Process data success",
                                                icon: 'success',
                                                showCancelButton: false,
                                                confirmButtonColor: '#06D177',
                                                confirmButtonText: 'เรียบร้อย'
                                            }).then((result) => {
                                                if (result
                                                    .isConfirmed) {
                                                    console.log(
                                                        data);
                                                    window.location.reload();
                                                    $('#spinner').hide();//Request is complete so hide spinner
                                                        setTimeout(function(){
                                                            $("#overlay").fadeOut(300);
                                                        },500);
                                                }
                                            })
                                        } else {
                                            
                                        }
                                    },
                                });
                                
                            }
                })
        });

        });
    </script>

@endsection
