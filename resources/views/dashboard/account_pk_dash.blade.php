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
    $ynow = date('Y') + 543;
    $yb = date('Y') + 542;
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

    <div class="tabs-animation">
        <div id="preloader">
            <div id="status">
                <div class="spinner">
                </div>
            </div>
        </div>
       
        <form action="{{ route('acc.account_pk_dash') }}" method="POST">
            @csrf
            <div class="row"> 
                <div class="col-md-4">
                    <h4 class="card-title">Dashboard Account </h4>
                    <p class="card-title-desc">รายละเอียดข้อมูล </p>
                </div>
                <div class="col"></div>
                <div class="col-md-1 text-end mt-2">วันที่</div>
                <div class="col-md-4 text-end">
                    <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                        <input type="text" class="form-control" name="startdate" id="datepicker" placeholder="Start Date"
                            data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                            data-date-language="th-th" value="{{ $startdate }}" required/>
                        <input type="text" class="form-control" name="enddate" placeholder="End Date" id="datepicker2"
                            data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                            data-date-language="th-th" value="{{ $enddate }}" required/>  
                    </div> 
                </div>
                <div class="col-md-2 text-start">
                    <button type="submit" class="mb-2 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info">
                        <i class="fa-solid fa-magnifying-glass text-info me-2"></i>
                        ค้นหา
                    </button>
                     
                </div>
            </div>
        </form> 
        <div class="row">
            <div class="col-md-12">
                <div class="main-card card">
                    <h6 class="card-title mt-2 ms-2">Report </h6> 
                        <div style="height:auto;" class="p-2"> 
                            <canvas id="myChartNew"></canvas>
                        <br>
                        <h6 class="text-center" style="color:rgb(241, 137, 155)">OPD แยกตามสิทธิ์ </h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="row p-3">

            {{-- <div class="col-xl-3 col-md-3">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                    <div id="chart"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="col-xl-3 col-md-3">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                    <div id="chart2"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="col-xl-3 col-md-3">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                    <div id="chart3"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="col-xl-3 col-md-3">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                    <div id="chart4"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>

        {{-- <div class="row ms-2 me-3">
            <div class="col-xl-3 col-md-3">
                <div class="main-card card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                    <div class="no-shadow rm-border bg-transparent widget-chart text-start card">
                                        <div class="progress-circle-wrapper">
                                            <div class="circle-progress circle-progress-gradient-lg">
                                                <small></small>
                                            </div>
                                        </div>
                                        <div class="widget-chart-content">
                                            <div class="widget-subheading">Capital Gains</div>
                                            <div class="widget-numbers text-success">
                                                <span>$563</span>
                                            </div>
                                            <div class="widget-description text-focus">
                                                Increased by
                                                <span class="text-warning ps-1">
                                                    <i class="fa fa-angle-up"></i>
                                                    <span class="ps-1">7.35%</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}



    </div>
    {{-- @apexchartsScripts --}}
@endsection
@section('footer')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script>
        var Linechart;
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
 

            var xmlhttp = new XMLHttpRequest();
            var url = "{{ route('acc.account_dashline') }}";
            xmlhttp.open("GET", url, true);
            xmlhttp.send();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var datas = JSON.parse(this.responseText);
                    console.log(datas);
                    label = datas.Dataset1.map(function(e) {
                        return e.label;
                    });
                    
                    count_vn = datas.Dataset1.map(function(e) {
                        return e.count_vn;
                    });
                    income = datas.Dataset1.map(function(e) {
                        return e.income;
                    });
                    rcpt_money = datas.Dataset1.map(function(e) {
                        return e.rcpt_money;
                    });
                    debit = datas.Dataset1.map(function(e) {
                        return e.debit;
                    });
                     // setup 
                    const data = {
                        // labels: ["ม.ค", "ก.พ", "มี.ค", "เม.ย", "พ.ย", "มิ.ย", "ก.ค","ส.ค","ก.ย","ต.ค","พ.ย","ธ.ค"] ,
                        labels: label ,
                        datasets: [                        
                            // {
                            //     label: ['Visit OPD'],
                            //     // data: [0],
                            //     data: count_vn,
                            //     fill: false,
                            //     borderColor: 'rgba(255, 205, 86)',
                            //     lineTension: 0.4 
                            // },
                            {
                                label: ['income'], 
                                data: income,
                                fill: false,
                                borderColor: 'rgba(75, 192, 192)',
                                lineTension: 0.4 
                            },
                            {
                                label: ['rcpt_money'], 
                                data: rcpt_money,
                                fill: false,
                                borderColor: 'rgba(255, 99, 132)',
                                lineTension: 0.4 
                            },  
                            // {
                            //     label: ['looknee_sum_opd'], 
                            //     data: looknee_sum_opd,
                            //     fill: false,
                            //     borderColor: 'rgba(255, 99, 32)',
                            //     lineTension: 0.4 
                            // },  
                        ]
                    };
             
                    const config = {
                        type: 'line',
                        data:data,
                        options: { 
                            scales: { 
                                y: {
                                    beginAtZero: true 
                                }
                            } 
                        },                        
                        plugins:[ChartDataLabels],                        
                    };                    
                    // render init block
                    const myChart = new Chart(
                        document.getElementById('myChartNew'),
                        config
                    );
                    
                }
             }

        });
    </script>
    {{-- <script>
        var options1 = {
            chart: {
                height: 350,
                type: "radialBar",
            },
            series: [32, 98, 70, 61],
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        total: {
                            show: true,
                            label: 'TOTAL'
                        }
                    }
                }
            },
            labels: ['401', '402', '403', '404']
        };

        new ApexCharts(document.querySelector("#chart4"), options1).render();
    </script>
    <script>
        var options7 = {
            series: [{
                // data: data.slice()
                data: [1523, 2562, 2555, 2240]
            }],
            chart: {
                id: 'realtime',
                height: 350,
                type: 'line',
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 1000
                    }
                },
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: 'Dynamic Updating Chart',
                align: 'left'
            },
            markers: {
                size: 0
            },
            xaxis: {
                type: 'datetime',
                range: XAXISRANGE,
            },
            yaxis: {
                max: 100
            },
            legend: {
                show: false
            },
        };

        var chart = new ApexCharts(document.querySelector("#linechart"), options7);
        chart.render();


        window.setInterval(function() {
            getNewSeries(lastDate, {
                min: 10,
                max: 90
            })

            chart.updateSeries([{
                data: data
            }])
        }, 1000)
    </script> --}}

@endsection
