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
        <form action="{{ route('acc.account_pkucs202_dash') }}" method="GET">
            @csrf
            <div class="row"> 
                <div class="col-md-4">
                    <h4 class="card-title">Detail 1102050101.202</h4>
                    <p class="card-title-desc">รายละเอียดข้อมูล ผัง 1102050101.202</p>
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
                <div class="col-md-1 text-start">
                    <button type="submit" class="mb-2 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info">
                        <i class="pe-7s-search btn-icon-wrapper"></i>ค้นหา
                    </button>
                </div>
            </div>
        </form>  
        <div class="row "> 
            @foreach ($datashow as $item)   
            <div class="col-xl-6 col-md-6">
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
                                                    SELECT count(an) as Can,SUM(debit)-sum(debit_drug)-sum(debit_instument)-sum(debit_toa)-sum(debit_refer) as sumdebit from acc_debtor  
                                                        WHERE account_code="1102050101.202"             
                                                        AND stamp = "N" 
                                                        and month(dchdate) = "'.$item->months.'" 
                                                        and year(dchdate) = "'.$item->year.'";
                                                ');
                                                foreach ($datas as $key => $value) {
                                                    $count_N = $value->Can;
                                                    $sum_N = $value->sumdebit;
                                                }
                                                $datasum_ = DB::select('
                                                    SELECT sum(debit_total) as debit_total from acc_1102050101_202  
                                                        WHERE month(dchdate) = "'.$item->months.'" 
                                                        and year(dchdate) = "'.$item->year.'"                                                                  
                                                ');
                                                foreach ($datasum_ as $key => $value2) {
                                                    $sum_Y = $value2->debit_total;
                                                }
                                                // สีเขียว STM
                                            //     $sumapprove_ = DB::select('
                                            //         SELECT sum(a.sum_price_approve) as priceapprove 
                                            //         from acc_stm_ti_total a 
                                            //         LEFT JOIN acc_debtor ad ON ad.cid = a.cid AND ad.dchdate = a.dchdate
                                            //                 WHERE ad.account_code="1102050101.202"             
                                            //                 AND ad.stamp = "Y" and ad.income <>0 
                                            //                 and month(ad.dchdate) = "'.$item->months.'" 
                                            //                 and year(ad.dchdate) = "'.$item->year.'"                                                                  
                                            //         ');
                                            //         foreach ($sumapprove_ as $key => $value3) {
                                            //             $sum_approveY = $value3->priceapprove;
                                            //         }                                                       
                                            // ?>        
                                            <div class="row">
                                                <div class="col-md-5 text-start mt-4 ms-4">
                                                    <h4 >เดือน {{$item->MONTH_NAME}} {{$ynew}}</h4> 
                                                </div>
                                                <div class="col"></div>
                                                <div class="col-md-3 text-end mt-2 me-4">
                                                    <a href="{{url('account_pkucs202/'.$item->months.'/'.$item->year)}}" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="จำนวนลูกหนี้ที่ต้องตั้ง"> 
                                                            <h4 class="text-end">{{$count_N}} Visit</h4> 
                                                        </div> 
                                                    </a>                                                            
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <a href="" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="ลูกหนี้ {{number_format($sum_N, 2)}}">
                                                            <p class="text-muted mb-0"><span class="text-info fw-bold font-size-12 me-2"><i class="fa-solid fa-sack-dollar me-1 align-middle"></i>{{ number_format($sum_N, 2) }}</span></p>
                                                        </div> 
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="ตั้งลูกหนี้ {{number_format($sum_Y, 2)}}"> 
                                                            <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2"><i class="fa-solid fa-dollar-sign me-1 align-middle"></i>{{ number_format($sum_Y, 2) }}</span></p>
                                                        </div> 
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="{{url('account_pkucs202_stm/'.$item->months.'/'.$item->year)}}" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="STM 0.00">
                                                            <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="fa-solid fa-hand-holding-dollar me-1 align-middle"></i>0.00</span></p>
                                                        </div> 
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="{{url('account_pkucs202_stm/'.$item->months.'/'.$item->year)}}" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="ยอดยกไป 0.00">
                                                            <p class="text-muted mb-0"><span class="text-warning fw-bold font-size-12 me-2"><i class="fa-solid fa-hand-holding-dollar me-1 align-middle"></i>0.00</span></p>
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
                        <div class="grid-menu-col">
                            <div class="g-0 row">
                                <div class="col-sm-12"> 
                                    <div class="d-flex text-start">
                                        <div class="flex-grow-1 ">
                                            <?php 
                                                $y = $item->year; 
                                                $ynew = $y +543;
                                                $datas = DB::select('
                                                    SELECT count(an) as Can,SUM(debit) as sumdebit from acc_debtor  
                                                        WHERE account_code="1102050101.202"             
                                                        AND stamp = "N" 
                                                        AND dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'" 
                                                ');
                                                foreach ($datas as $key => $value) {
                                                    $count_NN = $value->Can;
                                                    $sum_NN = $value->sumdebit;
                                                }
                                                $datasum_ = DB::select('
                                                    SELECT sum(debit) as income from acc_debtor  
                                                        WHERE account_code="1102050101.202"             
                                                        AND stamp = "Y" AND dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'"                                                                  
                                                ');
                                                foreach ($datasum_ as $key => $value2) {
                                                    $sum_Y = $value2->income;
                                                }
                                            ?>   
                                            <div class="row">
                                                <div class="col-md-5 text-start mt-4 ms-4">
                                                    <h4 >เดือน {{$item->MONTH_NAME}} {{$ynew}}</h4> 
                                                </div>
                                                <div class="col"></div>
                                                <div class="col-md-3 text-end mt-2 me-4">
                                                    <a href="{{url('account_pkucs202/'.$item->months.'/'.$item->year)}}" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="จำนวนลูกหนี้ที่ต้องตั้ง"> 
                                                            <h4 class="text-end">{{$count_NN}} Visit</h4> 
                                                        </div> 
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <a href="" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="ลูกหนี้ {{number_format($sum_NN, 2)}}"> 
                                                            <p class="text-muted mb-0"><span class="text-info fw-bold font-size-12 me-2"><i class="fa-solid fa-sack-dollar me-1 align-middle"></i>{{ number_format($sum_NN, 2) }}</span></p>
                                                        </div> 
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="ตั้งลูกหนี้ 0.00">
                                                            <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2"><i class="fa-solid fa-dollar-sign me-1 align-middle"></i>0.00</span></p>
                                                        </div> 
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="{{url('account_pkucs202_stm/'.$item->months.'/'.$item->year)}}" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="STM 0.00">
                                                            <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="fa-solid fa-hand-holding-dollar me-1 align-middle"></i>0.00</span></p>
                                                        </div> 
                                                    </a>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="{{url('account_pkucs202_stm/'.$item->months.'/'.$item->year)}}" target="_blank"> 
                                                        <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="ยอดยกไป 0.00">
                                                            <p class="text-muted mb-0"><span class="text-warning fw-bold font-size-12 me-2"><i class="fa-solid fa-hand-holding-dollar me-1 align-middle"></i>0.00</span></p>
                                                        </div> 
                                                    </a>
                                                </div>
                                            </div> 
                                        </div>  
                                    </div>   
                                </div> 
                            </div>                                           
                        </div> 
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
