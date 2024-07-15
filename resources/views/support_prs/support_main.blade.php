@extends('layouts.support_prs_new')
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
        {{-- <div class="row text-center">
            <div id="overlay">
                <div class="cv-spinner">
                    <span class="spinner"></span>
                </div>
            </div>
        </div> --}}
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

        {{-- <div class="row text-center" id="container_spins">
            <div id="container_spin">
                <svg viewBox="0 0 100 100">
                    <defs>
                        <filter id="shadow">
                        <feDropShadow dx="0" dy="0" stdDeviation="1.5" 
                            flood-color="#fc6767"/>
                        </filter>
                    </defs>
                    <circle id="spinner" style="fill:transparent;stroke:#dd2476;stroke-width: 7px;stroke-linecap: round;filter:url(#shadow);" cx="50" cy="50" r="45"/>
                </svg>
            </div>
        </div> --}}
        {{-- <div class="container"> 
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
                                        <span class="d-inline-block">ตรวจสอบและบำรุงรักษา ระบบสนับสนุนบริการสุขภาพ Dashboard</span>
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
        </div> --}}
    </div>
 
@endsection
@section('footer')
 
    <script>
        
     $(document).ready(function() {
             
            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
            $('#datepicker2').datepicker({
                format: 'yyyy-mm-dd'
            });
            var table = $('#example').DataTable({
                scrollY: '60vh',
                scrollCollapse: true,
                scrollX: true,
                "autoWidth": false,
                "pageLength": 100,
                "lengthMenu": [10, 100, 150, 200, 300, 400, 500],
            });
            var table = $('#example2').DataTable({
                scrollY: '60vh',
                scrollCollapse: true,
                scrollX: true,
                "autoWidth": false,
                "pageLength": 10,
                "lengthMenu": [10, 100, 150, 200, 300, 400, 500],
            });
 

             $("#spinner-div").hide(); //Request is complete so hide spinner
         
            

        });

        

    </script>

@endsection

