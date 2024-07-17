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
       
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h2 class="mb-sm-0">ระบบตรวจสอบและบำรุงรักษา ระบบสนับสนุนบริการสุขภาพ</h2>
                    {{-- <span class="mb-sm-0 pe-2">
                        <i class="lnr-apartment opacity-6" style="color:rgb(255, 255, 255)"></i>
                    </span> --}}
                    {{-- <span class="mb-sm-0">ตรวจสอบและบำรุงรักษา ระบบสนับสนุนบริการสุขภาพ Dashboard</span> --}}
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">ระบบสนับสนุนบริการสุขภาพ</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->
       
        
 
 
 
@endsection
@section('footer')
  

@endsection

