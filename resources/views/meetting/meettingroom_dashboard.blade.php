@extends('layouts.meetting')
@section('title', 'PK-BACKOFFice || ห้องประชุม')
@section('content')
     <?php
     use App\Http\Controllers\StaticController;
     use Illuminate\Support\Facades\DB;   
     $count_meettingroom = StaticController::count_meettingroom();
     $count_meettinservice = StaticController::count_meettinservice();
 ?>
  
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
        body {
            font-size: 13px;
        }
       
        .btn {
            font-size: 13px;
        }
        .form-control{
            font-size: 13px;
        }
        .bgc {
            background-color: #264886;
        }
    
        .bga {
            background-color: #fbff7d;
        }
    
        .boxpdf {
            /* height: 1150px; */
            height: auto;
        }
    
        .page {
            width: 90%;
            margin: 10px;
            box-shadow: 0px 0px 5px #000;
            animation: pageIn 1s ease;
            transition: all 1s ease, width 0.2s ease;
        }
    
        @keyframes pageIn {
            0% {
                transform: translateX(-300px);
                opacity: 0;
            }
    
            100% {
                transform: translateX(0px);
                opacity: 1;
            }
        }
    
        @media (min-width: 500px) {
            .modal {
                --bs-modal-width: 500px;
            }
        }
    
        @media (min-width: 950px) {
            .modal-lg {
                --bs-modal-width: 950px;
            }
        }
    
        @media (min-width: 1500px) {
            .modal-xls {
                --bs-modal-width: 1500px;
            }
        }
    
        @media (min-width: auto; ) {
            .container-fluids {
                width: auto;
                margin-left: 20px;
                margin-right: 20px;
                margin-top: auto;
            }
    
            .dataTables_wrapper .dataTables_filter {
                float: right
            }
    
            .dataTables_wrapper .dataTables_length {
                float: left
            }
    
            .dataTables_info {
                float: left;
            }
    
            .dataTables_paginate {
                float: right
            }
    
            .custom-tooltip {
                --bs-tooltip-bg: var(--bs-primary);
            }
    
            .table thead tr th {
                font-size: 14px;
            }
    
            .table tbody tr td {
                font-size: 13px;
            }
    
            .menu {
                font-size: 13px;
            }
        }
    
        .hrow {
            height: 2px;
            margin-bottom: 9px;
        }
    
        .custom-tooltip {
            --bs-tooltip-bg: var(--bs-primary);
        }
    
        .colortool {
            background-color: red;
        }
    </style>
    <div class="container-fluid ">
        <div class="row"> 
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body shadow-lg">
                        


                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="{{ asset('js/book.js') }}"></script>

@endsection
