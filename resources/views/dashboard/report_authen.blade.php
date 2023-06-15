@extends('layouts.report_font')
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
     
    <div class="container-fluid">
        <div id="preloader">
            <div id="status">
                <div class="spinner">
                    
                </div>
            </div>
        </div>  
        
        <div class="row">
            
                <div class="col-xl-6 col-md-3">
                    <div class="main-card mb-3 card p-2" >
                        <div class="grid-menu-col">
                            <div class="g-0 row">
                                <div class="col-sm-12">
                                    {{-- <div class="widget-chart widget-chart-hover" style="height: 800px;">  --}}
                                        <h5 class="card-title mt-2 ms-2">Authen Report Month OPD</h5>
                                        {{-- <div class="widget-chart-wrapper widget-chart-wrapper-lg opacity-10 m-0 p-2"> --}}
                                            <div style="height:450px;">
                                                <canvas id="Mychart"></canvas>
                                            </div>
                                        {{-- </div>  --}}
                                    {{-- </div>  --}}
                                </div>  
                            </div>                                           
                        </div> 
                    </div> 
                </div> 
                <div class="col-xl-6 col-md-3">
                    <div class="main-card mb-3 card p-2">
                        <div class="grid-menu-col">
                            <div class="g-0 row">
                                <div class="col-sm-12">
                                    <h5 class="card-title mt-2 ms-2">Authen Report Month IPD</h5>
                                    {{-- <div class="widget-chart-wrapper widget-chart-wrapper-lg opacity-10 ms-2 me-2 mb-2"> --}}
                                        <div style="height:450px;">
                                            <canvas id="Mychartipd"></canvas>
                                        </div>
                                    {{-- </div> --}}
                                </div>  
                            </div>                                           
                        </div> 
                    </div> 
                </div>
                                 
                            
        </div>

        <div class="row">
            
            <div class="col-xl-6 col-md-3">
                <div class="main-card mb-3 card p-2" >
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                     
                                    <h5 class="card-title mt-2 ms-2">Authen Report Month OPD</h5>
                                    {{-- <div class="widget-chart-wrapper widget-chart-wrapper-lg opacity-10 m-0 p-2"> --}}
                                        <div style="height:450px;">
                                            <div class="row">
                                                <div class="col-md-6 col-lg-6">
                                                    <div class="widget-chart widget-chart2 text-start mb-3 card-btm-border card-shadow-primary border-primary card shadow-lg">
                                                        <div class="widget-chat-wrapper-outer">
                                                            <div class="widget-chart-content">
                                                                <div class="widget-title opacity-5 text-uppercase">New accounts</div>
                                                                <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                                                    <div class="widget-chart-flex align-items-center">
                                                                        <div>
                                                                            <span class="opacity-10 text-success pe-2">
                                                                                <i class="fa fa-angle-up"></i>
                                                                            </span>
                                                                            234
                                                                            <small class="opacity-5 ps-1">%</small>
                                                                        </div>
                                                                        <div class="widget-title ms-auto font-size-lg fw-normal text-muted">
                                                                            <div class="circle-progress circle-progress-gradient-alt-sm d-inline-block">
                                                                                <small></small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                            </div>
                                            {{-- <canvas id="Mychart"></canvas> --}}
                                        </div>
                                   
                            </div>  
                        </div>                                           
                    </div> 
                </div> 
            </div> 
            <div class="col-xl-6 col-md-3">
                <div class="main-card mb-3 card p-2">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <h5 class="card-title mt-2 ms-2">Authen Report Month IPD</h5> 
                                    <div style="height:450px;">
                                        {{-- <canvas id="Mychartipd"></canvas> --}}
                                    </div> 
                            </div>  
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
      <script>
        var ctx = document.getElementById("Mychart").getContext("2d");

            fetch("{{ route('rep.reportauthen_getbar') }}")
                .then(response => response.json())
                .then(json => {
                    const Mychart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: json.labels,
                                datasets: json.datasets,
                            
                            },
                            options:{
                                scales:{
                                    y:{
                                        beginAtZero:true
                                    }
                                }
                            }
                        }) 
                });        
 
    </script>
     <script>
        var ctx2 = document.getElementById("Mychartipd").getContext("2d");

            fetch("{{ route('rep.reportauthen_getbaripd') }}")
                .then(response => response.json())
                .then(json => {
                    const Mychart = new Chart(ctx2, {
                            type: 'bar',
                            // type: 'line',
                            data: {
                                labels: json.labels,
                                datasets: json.datasets,
                            
                            },
                            options:{
                                scales:{
                                    y:{
                                        beginAtZero:true
                                        // stacked: true
                                    }
                                }
                            }
                        }) 
                });        
 
    </script>
    @endsection
