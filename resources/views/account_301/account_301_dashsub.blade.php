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
        <form action="{{ route('acc.account_301_dash') }}" method="GET">
            @csrf
            <div class="row ms-3 me-3"> 
                <div class="col-md-4">
                    <h4 class="card-title">Detail 1102050101.301</h4>
                    <p class="card-title-desc">รายละเอียดข้อมูล ผัง 1102050101.301</p>
                </div>
                <div class="col"></div>
               
            </div>
        </form>  
        <div class="row ms-3 me-3"> 
            @foreach ($datashow as $item)   
            <div class="col-xl-4 col-md-6">
                <div class="main-card mb-3 card shadow" style="background-color: rgb(246, 235, 247)"> 
 
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="d-flex text-start">
                                    <div class="flex-grow-1 ">
                                        <?php   
                                            $datashow = DB::select('
                                                    SELECT month(a.vstdate) as months,year(a.vstdate) as year,l.MONTH_NAME,l.MONTH_ID
                                                    ,count(distinct a.hn) as hn
                                                    ,count(distinct a.vn) as vn
                                                    ,count(distinct a.an) as an
                                                    ,sum(a.income) as income
                                                    ,sum(a.paid_money) as paid_money
                                                    ,sum(a.income)-sum(a.discount_money)-sum(a.rcpt_money) as total
                                                    ,sum(a.debit) as debit
                                                    FROM acc_debtor a
                                                    left outer join leave_month l on l.MONTH_ID = month(a.vstdate)
                                                    WHERE a.vstdate between "'.$startdate.'" and "'.$enddate.'"
                                                    and account_code="1102050101.301"
                                                    group by month(a.vstdate) order by month(a.vstdate) desc;
                                            ');
                                        ?>
                                        <div class="row">
                                            <div class="col-md-5 text-start mt-4 ms-4">
                                                <h5 > {{$item->MONTH_NAME}}</h5>
                                            </div>
                                            <div class="col"></div>
                                            <div class="col-md-3 text-end mt-2 me-4">
                                                <a href="{{url('account_301_pull')}}" target="_blank">
                                                    <div class="widget-chart widget-chart-hover" data-bs-toggle="tooltip" data-bs-placement="top" title="จำนวนลูกหนี้ที่ต้องตั้ง">
                                                        <h6 class="text-end">10 Visit</h6>
                                                    </div>
                                                </a>
                                            </div>
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
