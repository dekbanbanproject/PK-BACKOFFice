@extends('layouts.accountpk')
@section('title', 'PK-BACKOFFice || ACCOUNT')
 
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
    $ynow = date('Y')+543;
    $yb =  date('Y')+542;
    ?>
     
     <style>
        #button {
            display: block;
            margin: 20px auto;
            padding: 30px 30px;
            background-color: #eee;
            border: solid #ccc 1px;
            cursor: pointer;
        }

        #overlay {
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.6);
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
            border: 5px #ddd solid;
            border-top: 10px #12c6fd solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }

        @keyframes sp-anime {
            100% {
                transform: rotate(360deg);
            }
        }

        .is-hide {
            display: none;
        }
    </style>

    <?php
        $ynow = date('Y')+543;
        $yb =  date('Y')+542;
    ?>

   <div class="tabs-animation">
        <div id="preloader">
            <div id="status">
                <div class="spinner"> 
                </div>
            </div>
        </div>
        {{-- <form action="{{ route('acc.account_pkti4011_dash') }}" method="GET">
            @csrf --}}
            <div class="row"> 
                <div class="col-md-4">
                    <h4 class="card-title">Detail 1102050101.401</h4>
                    <p class="card-title-desc">รายละเอียดข้อมูล ผัง 1102050101.401</p>
                </div>
                <div class="col"></div>
                <div class="col-md-1 text-end mt-2">วันที่</div>
                <div class="col-md-3 text-end">
                    <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                        <input type="text" class="form-control" name="startdate" id="datepicker" placeholder="Start Date"
                            data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true"
                            data-date-language="th-th" value="{{ $startdate }}" required/>
                        <input type="text" class="form-control" name="enddate" placeholder="End Date" id="datepicker2"
                            data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true"
                            data-date-language="th-th" value="{{ $enddate }}" required/>  
                    </div> 
                </div>
                <div class="col-md-2 text-start">
                    <button type="button" class="mb-2 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info">
                        <i class="fa-solid fa-magnifying-glass text-info me-2"></i>
                        ค้นหา
                    </button>
                    <a href="{{url('account_401_pull')}}" class="mb-2 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-primary" target="_blank">  
                        <i class="fa-solid fa-file-circle-plus text-primary me-2"></i>
                        ดึงข้อมูล
                    </a>
                </div>
               
            </div>
        {{-- </form>   --}}
        <div class="row "> 
            @foreach ($datashow as $item)   
            <div class="col-xl-6 col-md-12">
                <div class="main-card mb-3 card">   
                    @if ($startdate == '')
                        <div class="grid-menu-col">
                            <div class="g-0 row">
                                <div class="col-sm-12">
                                    <div class="d-flex text-start">
                                        <div class="flex-grow-1 ">
                                            <?php 
                                                $y = $item->year; 
                                                $ynew = $y + 543;
                                               
                                                $datas = DB::select('
                                                    SELECT count(DISTINCT vn) as Can
                                                        ,SUM(debit) as sumdebit                                                     
                                                        from acc_debtor  
                                                            WHERE account_code="1102050101.401"             
                                                            AND stamp = "N" 
                                                            and month(vstdate) = "'.$item->months.'" 
                                                            and year(vstdate) = "'.$item->year.'";
                                                ');
                                                foreach ($datas as $key => $value) {
                                                    $count_N = $value->Can;
                                                    $sum_N = $value->sumdebit;
                                                }
                                                $datasum_ = DB::select('
                                                    SELECT sum(debit_total) as debit_total,count(vn) as Cvit 
                                                            from acc_1102050101_401 
                                                            WHERE month(vstdate) = "'.$item->months.'" 
                                                            and year(vstdate) = "'.$item->year.'" 
                                                                                                               
                                                ');
                                                foreach ($datasum_ as $key => $value2) {
                                                    $sum_Y = $value2->debit_total;
                                                    $count_Y = $value2->Cvit;
                                                }
                                                // AND status = "N" 
                                                // สีเขียว STM
                                                $sumapprove_ = DB::select('
                                                        SELECT count(DISTINCT a.vn) as Apvit ,sum(au.pricereq_all) as amountpay
                                                            FROM acc_1102050101_401 a 
		                                                    LEFT JOIN acc_stm_ofc au ON au.hn = a.hn AND au.vstdate = a.vstdate
                                                            WHERE year(a.vstdate) = "'.$item->year.'"
                                                            AND month(a.vstdate) = "'.$item->months.'"
                                                            AND a.status = "Y"                                                                  
                                                    ');
                                                    foreach ($sumapprove_ as $key => $value3) {
                                                        $amountpay = $value3->amountpay;
                                                        $stm_count = $value3->Apvit;
                                                    }   
                                                    // สีส้ม ยกยอดไป                                                           
                                                    $sumyokma_ = DB::select('
                                                        SELECT count(DISTINCT vn) as anyokma ,sum(debit_total) as debityokma
                                                                FROM acc_1102050101_401
                                                                WHERE year(vstdate) = "'.$item->year.'"
                                                                AND month(vstdate) = "'.$item->months.'" 
                                                                AND status ="N"
                                                    '); 
                                                    foreach ($sumyokma_ as $key => $value5) {
                                                        $total_yokma = $value5->debityokma;
                                                        $count_yokma = $value5->anyokma;
                                                    }  
                                                    $sumyokma_all_ = DB::select('
                                                        SELECT count(DISTINCT vn) as anyokma ,sum(debit_total) as debityokma
                                                                FROM acc_1102050101_401
                                                                WHERE status ="N"
                                                    '); 
                                                    foreach ($sumyokma_all_ as $key => $value6) {
                                                        $total_yokma_all = $value6->debityokma;
                                                        $count_yokma_all = $value6->anyokma;
                                                    }  
                                                                                            
                                            ?>  
                                            <div class="row">
                                                <div class="col-md-5 text-start mt-4 ms-4">
                                                    <h4 >เดือน {{$item->MONTH_NAME}} {{$ynew}}</h4> 
                                                </div>
                                                <div class="col"></div>
                                                <div class="col-md-3 text-end mt-2 me-4">
                                                    <a href="{{url('account_401_pull')}}" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="จำนวนลูกหนี้ที่ต้องตั้ง"> 
                                                            <h4 class="text-end">{{$count_N}} Visit</h4> 
                                                        </div> 
                                                    </a>                                                            
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <a href="" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="ลูกหนี้ {{number_format($sum_N, 2)}}">
                                                            <p class="text-muted mb-0"><span class="text-info fw-bold font-size-12 me-2"><i class="fa-solid fa-sack-dollar me-1 align-middle"></i><br>{{ number_format($sum_N, 2) }}</span></p>
                                                        </div> 
                                                    </a>
                                                </div>
                                                <div class="col-md-2">
                                                    <a href="{{url('account_401_detail/'.$item->months.'/'.$item->year)}}" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="ตั้งลูกหนี้ {{number_format($sum_Y, 2)}} / {{$count_Y}}Visit"> 
                                                            <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2"><i class="fa-solid fa-dollar-sign me-1 align-middle"></i><br>{{ number_format($sum_Y, 2) }}</span></p>
                                                        </div> 
                                                    </a>
                                                </div>
                                                <div class="col-md-2">
                                                    <a href="{{url('account_401_stm/'.$item->months.'/'.$item->year)}}" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="STM {{number_format($amountpay, 2) }} / {{$stm_count}}Visit">
                                                            <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="fa-solid fa-hand-holding-dollar me-1 align-middle"></i><br>{{ number_format($amountpay, 2) }}</span></p>
                                                        </div> 
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="{{url('account_401_stmnull/'.$item->months.'/'.$item->year)}}" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="ยอดยกไปเดือนนี้ {{number_format($total_yokma, 2) }} / {{$count_yokma}}Visit">
                                                            <p class="text-muted mb-0"><span class="text-warning fw-bold font-size-12 me-2"><i class="fa-solid fa-hand-holding-dollar me-1 align-middle"></i><br>{{number_format($total_yokma, 2) }}</span></p>
                                                        </div> 
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="{{url('account_401_stmnull_all/'.$item->months.'/'.$item->year)}}" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="ยอดยกไปรวมทั้งหมด {{number_format($total_yokma_all, 2) }} / {{$count_yokma_all}}Visit">
                                                            <p class="text-muted mb-0"><span class="text-primary fw-bold font-size-12 me-2"><i class="fa-solid fa-hand-holding-dollar me-1 align-middle"></i><br>{{number_format($total_yokma_all, 2) }}</span></p>
                                                        </div> 
                                                    </a>
                                                </div>
                                            </div>       
                                          
                                        </div>     
                                    </div>   
                                </div> 
                            </div>                                           
                        </div> 
                    @else
                        
                    @endif                 
                       
                   
                </div> 
            </div> 
            @endforeach
        </div>

    </div>

@endsection
@section('footer')
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            $('#example2').DataTable();
            $('#p4p_work_month').select2({
                placeholder: "--เลือก--",
                allowClear: true
            });
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

        });
    </script>

@endsection
