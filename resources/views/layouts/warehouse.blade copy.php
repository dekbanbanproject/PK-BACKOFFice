<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Font Awesome -->
    <link href="{{ asset('assets/fontawesome/css/all.css') }}" rel="stylesheet">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('apkclaim/images/logo150.ico') }}">

    
    <!-- Plugin css -->
    <link rel="stylesheet" href="{{ asset('apkclaim/libs/@fullcalendar/core/main.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('apkclaim/libs/@fullcalendar/daygrid/main.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('apkclaim/libs/@fullcalendar/bootstrap/main.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('apkclaim/libs/@fullcalendar/timegrid/main.min.css') }}" type="text/css">

    <!-- DataTables -->
    <link href="{{ asset('apkclaim/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('apkclaim/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('apkclaim/libs/datatables.net-select-bs4/css//select.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('apkclaim/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <!-- Bootstrap Css -->
    {{-- <link href="{{ asset('apkclaim/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" /> --}}
    <!-- Icons Css -->
    <link href="{{ asset('apkclaim/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('apkclaim/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/fullcalendar.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/tablecar.css') }}" rel="stylesheet">
</head>
<style>
    body {
        font-family: 'Kanit', sans-serif;
        font-size: 13px;

    }

    label {
        font-family: 'Kanit', sans-serif;
        font-size: 14px;

    }

    @media only screen and (min-width: 1200px) {
        label {
            /* float:right; */
        }

    }

    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }

    .b-example-divider {
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
    }

    .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
    }

    .bi {
        vertical-align: -.125em;
        fill: currentColor;
    }

    .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
    }

    .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
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
</style>

<body data-topbar="light" data-layout="horizontal" >
    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner">
                <i class="ri-loader-line spin-icon"></i>
            </div>
        </div>
    </div>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">                       
                    </div>
                    <button type="button" class="btn btn-sm px-3 font-size-24 d-lg-none header-item"
                        data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                        <i class="ri-menu-2-line align-middle"></i>
                    </button>

                    <div class="dropdown dropdown-mega d-none d-lg-block ">
                        <img src="{{ asset('apkclaim/images/logo150.png') }}" alt="logo-sm-light" height="40">
                        <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown"
                            aria-haspopup="false" aria-expanded="false">
                            <h4 style="color:blueviolet" class="mt-3">โรงพยาบาลภูเขียวเฉลิมพระเกียรติ</h4>

                        </button>
                    </div>

                </div>

                <div class="d-flex">

                    <div class="dropdown d-inline-block d-lg-none ms-2">
                    </div>

                    <div class="dropdown d-none d-lg-inline-block ms-1">
                        <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                            <i class="ri-fullscreen-line"></i>
                        </button>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon waves-effect"
                            id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-notification-3-line"></i>
                            <span class="noti-dot"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                            aria-labelledby="page-header-notifications-dropdown">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0"> Notifications </h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="#!" class="small"> View All</a>
                                    </div>
                                </div>
                            </div>
                            <div data-simplebar style="max-height: 230px;">
                                <a href="" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="avatar-xs me-3">
                                            <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                <i class="ri-shopping-cart-line"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <h6 class="mb-1">Your order is placed</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1">If several languages coalesce the grammar</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <img src="assets/images/users/avatar-3.jpg"
                                            class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                        <div class="flex-1">
                                            <h6 class="mb-1">James Lemire</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1">It will seem like simplified English.</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 1 hours ago
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="avatar-xs me-3">
                                            <span class="avatar-title bg-success rounded-circle font-size-16">
                                                <i class="ri-checkbox-circle-line"></i>
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <h6 class="mb-1">Your item is shipped</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1">If several languages coalesce the grammar</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <a href="" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <img src="assets/images/users/avatar-4.jpg"
                                            class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                        <div class="flex-1">
                                            <h6 class="mb-1">Salena Layfield</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1">As a skeptical Cambridge friend of mine occidental.
                                                </p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 1 hours ago
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2 border-top">
                                <div class="d-grid">
                                    <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                        <i class="mdi mdi-arrow-right-circle me-1"></i> View More..
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="dropdown d-inline-block user-dropdown">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if (Auth::user()->img == null)
                                <img src="{{ asset('assets/images/default-image.jpg') }}" height="32px"
                                    width="32px" alt="Header Avatar" class="rounded-circle header-profile-user">
                            @else
                                <img src="{{ asset('storage/person/' . Auth::user()->img) }}" height="32px"
                                    width="32px" alt="Header Avatar" class="rounded-circle header-profile-user">
                            @endif
                            <span class="d-none d-xl-inline-block ms-1">
                                {{ Auth::user()->fname }} {{ Auth::user()->lname }}
                            </span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a class="dropdown-item" href="#"><i class="ri-user-line align-middle me-1"></i>
                                Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                class="text-reset notification-item"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                    class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                            <i class="ri-settings-2-line"></i>
                        </button>
                    </div>

                </div>
            </div>
        </header>

        <div class="topnav bg-info">
            <div class="container-fluid">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
                    <div class="collapse navbar-collapse" id="topnav-menu-content">
                        <ul class="navbar-nav">
                            {{-- <li class="nav-item">
                                <a class="nav-link" href="{{ url('admin/home') }}">
                                    <i class="ri-dashboard-line me-2"></i> Dashboard
                                </a>
                            </li> --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="{{url('warehouse/warehouse_index')}}" id="topnav-apps"
                                    role="button">
                                    <i class="ri-apps-2-line me-2"></i>ตรวจรับวัสดุ </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="{{url('warehouse/warehouse_pay')}}" id="topnav-apps"
                                    role="button">
                                    <i class="ri-apps-2-line me-2" ></i>เบิกจ่ายวัสดุ
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps"
                                    role="button">
                                    <i class="ri-apps-2-line me-2"></i>Stock-Card คลังหลัก
                                </a>
                            </li>
                            {{-- <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps"
                                    role="button">
                                    <i class="ri-apps-2-line me-2"></i>Stock-Card คลังย่อย
                                </a>
                            </li> --}}
                            {{-- <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button">
                                    <i class="ri-apps-2-line me-2"></i>รายงาน<div class="arrow-down"></div>
                                </a>
                            </li> --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps" role="button"
                                >
                                    <i class="ri-apps-2-line me-2"></i>ตั่งค่า <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-apps"> 
                                    <a href="{{url("warehouse/warehouse_inven")}}" class="dropdown-item">คลังวัสดุ</a> 
                                    <a href="{{url("warehouse/warehouse_vendor")}}" class="dropdown-item">ตัวแทนจำหน่าย</a> 
                                </div>
                               
                                   
                                    
                               
                            </li> 
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">

                @yield('content')

            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © โรงพยาบาลภูเขียวเฉลิมพระเกียรติ
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Created with <i class="mdi mdi-heart text-danger"></i> By หน่วยงานประกัน
                            </div>
                        </div>
                    </div>
                </div>
            </footer>


        </div>
        <!-- end main content-->
    </div>

    <!-- END layout-wrapper -->


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- Right Sidebar -->
    <div class="right-bar">
        <div data-simplebar class="h-100">
            <div class="rightbar-title d-flex align-items-center px-3 py-4">

                <h5 class="m-0 me-2">Settings</h5>

                <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                    <i class="mdi mdi-close noti-icon"></i>
                </a>
            </div>



        </div> <!-- end slimscroll-menu-->
    </div>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    {{-- <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>  --}}
    {{-- <script src="{{ asset('sky16/js/jquery.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('apkclaim/libs/jquery/jquery.min.js') }}"></script> --}}
    <script src="{{ asset('apkclaim/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('apkclaim/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('apkclaim/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('apkclaim/libs/node-waves/waves.min.js') }}"></script>

    {{-- <script src="{{ asset('js/select2.min.js') }}"></script> --}}
    {{-- <script type="text/javascript" src="{{ asset('fullcalendar/lib/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('fullcalendar/fullcalendar.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('fullcalendar/lang/th.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

       <!-- plugin js -->
       <script src="{{ asset('apkclaim/libs/moment/min/moment.min.js') }}"></script>
       <script src="{{ asset('apkclaim/libs/jquery-ui-dist/jquery-ui.min.js') }}"></script>
       <script src="{{ asset('apkclaim/libs/@fullcalendar/core/main.min.js') }}"></script>
       <script src="{{ asset('apkclaim/libs/@fullcalendar/bootstrap/main.min.js') }}"></script>
       <script src="{{ asset('apkclaim/libs/@fullcalendar/daygrid/main.min.js') }}"></script>
       <script src="{{ asset('apkclaim/libs/@fullcalendar/timegrid/main.min.js') }}"></script>
       <script src="{{ asset('apkclaim/libs/@fullcalendar/interaction/main.min.js') }}"></script>

       <!-- Calendar init -->
       {{-- <script src="{{ asset('apkclaim/js/pages/calendar.init.js') }}"></script> --}}
    <!-- apexcharts -->
    {{-- <script src="{{ asset('apkclaim/libs/apexcharts/apexcharts.min.js') }}"></script> --}}

    <!-- jquery.vectormap map -->
    <script src="{{ asset('apkclaim/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('apkclaim/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}">
    </script>

    <!-- Required datatable js -->
    <script src="{{ asset('apkclaim/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('apkclaim/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Buttons examples -->
    <script src="{{ asset('apkclaim/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('apkclaim/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('apkclaim/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('apkclaim/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('apkclaim/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('apkclaim/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('apkclaim/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('apkclaim/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('apkclaim/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('apkclaim/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('apkclaim/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('apkclaim/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('apkclaim/js/pages/datatables.init.js') }}"></script>

    <script src="{{ asset('apkclaim/js/app.js') }}"></script>

    @yield('footer')

    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable();
            $('#example2').DataTable();
            $('#example3').DataTable();
            $('#example4').DataTable();
            $('#example5').DataTable();
            $('#table_id').DataTable();
        });
        $(document).ready(function() {


            $('#building_decline_id').select2({
                placeholder: "อัตราเสื่อม",
                allowClear: true
            });
            $('#building_buy_id').select2({
                placeholder: "วิธีการซื้อ",
                allowClear: true
            });
            $('#building_method_id').select2({
                placeholder: "วิธีได้มา",
                allowClear: true
            });
            $('#building_budget_id').select2({
                placeholder: "งบประมาณ",
                allowClear: true
            });
            $('#building_tonnage_number').select2({
                placeholder: "หมายเลขระวาง",
                allowClear: true
            });
            $('#land_province_location').select2({
                placeholder: "จังหวัด",
                allowClear: true
            });
            $('#land_district_location').select2({
                placeholder: "อำเภอ",
                allowClear: true
            });
            $('#land_tumbon_location').select2({
                placeholder: "ตำบล",
                allowClear: true
            });

            $('#article_deb_subsub_id').select2({
                placeholder: "ประจำหน่วยงาน",
                allowClear: true
            });
            $('#article_categoryid').select2({
                placeholder: "หมวดวัสดุ",
                allowClear: true
            });
            $('#leave_year_id').select2({
                placeholder: "ปีงบประมาณ",
                allowClear: true
            });
            $('#article_decline_id').select2({
                placeholder: "ประเภทค่าเสื่อม",
                allowClear: true
            });
            $('#product_typeid').select2({
                placeholder: "ประเภทวัสดุ",
                allowClear: true
            });
            $('#product_categoryid').select2({
                placeholder: "หมวดวัสดุ",
                allowClear: true
            });
            $('#product_spypriceid').select2({
                placeholder: "ราคาสืบ",
                allowClear: true
            });
            $('#product_groupid').select2({
                placeholder: "ชนิดวัสดุ",
                allowClear: true
            });
            $('#product_unit_bigid').select2({
                placeholder: "หน่วยบรรจุ",
                allowClear: true
            });
            $('#product_unit_subid').select2({
                placeholder: "หน่วยย่อย",
                allowClear: true
            });
            $('#status').select2({
                placeholder: "สถานะ",
                allowClear: true
            });
            $('#warehouse_rep_user_id').select2({
                placeholder: "-เลือก-",
                allowClear: true
            });
            $('#warehouse_rep_inven_id').select2({
                placeholder: "-เลือก-",
                allowClear: true
            });
            $('#warehouse_rep_vendor_id').select2({
                placeholder: "-เลือก-",
                allowClear: true
            });
            $('#warehouse_rep_year').select2({
                placeholder: "-เลือก-",
                allowClear: true
            });
            $('#warehouse_rep_sub_status').select2({
                placeholder: "-เลือก-",
                allowClear: true
            });
        });

        

        $(document).ready(function() {
            $('#insert_productForm').on('submit', function(e) {
                e.preventDefault();

                var form = this;

                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                    },
                    success: function(data) {
                        if (data.status == 0) {

                        } else {
                            Swal.fire({
                                title: 'บันทึกข้อมูลสำเร็จ',
                                text: "You Insert data success",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#06D177',
                                // cancelButtonColor: '#d33',
                                confirmButtonText: 'เรียบร้อย'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location =
                                        "{{ url('supplies/supplies_index') }}"; // กรณี add page new 
                                }
                            })
                        }
                    }
                });
            });

            $('#update_productForm').on('submit', function(e) {
                e.preventDefault();

                var form = this;
                // alert('OJJJJOL');
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                    },
                    success: function(data) {
                        if (data.status == 0) {

                        } else {
                            Swal.fire({
                                title: 'แก้ไขข้อมูลสำเร็จ',
                                text: "You edit data success",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#06D177',
                                // cancelButtonColor: '#d33',
                                confirmButtonText: 'เรียบร้อย'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location =
                                        "{{ url('supplies/supplies_index') }}"; // กรณี add page new   
                                }
                            })
                        }
                    }
                });
            });


        });

        $(document).ready(function() {
            $('#insert_serveForm').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                    },
                    success: function(data) {
                        if (data.status == 0) {

                        } else {
                            Swal.fire({
                                title: 'บันทึกข้อมูลสำเร็จ',
                                text: "You Insert data success",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#06D177',
                                // cancelButtonColor: '#d33',
                                confirmButtonText: 'เรียบร้อย'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = "{{ url('serve/serve_index') }}";
                                }
                            })
                        }
                    }
                });
            });

            $('#update_serveForm').on('submit', function(e) {
                e.preventDefault();

                var form = this;
                // alert('OJJJJOL');
                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                    },
                    success: function(data) {
                        if (data.status == 0) {

                        } else {
                            Swal.fire({
                                title: 'แก้ไขข้อมูลสำเร็จ',
                                text: "You edit data success",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#06D177',
                                // cancelButtonColor: '#d33',
                                confirmButtonText: 'เรียบร้อย'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = "{{ url('serve/serve_index') }}";
                                }
                            })
                        }
                    }
                });
            });


        });

        function addproducts(input) {
            var fileInput = document.getElementById('img');
            var url = input.value;
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#add_upload_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                alert('กรุณาอัพโหลดไฟล์ประเภทรูปภาพ .jpeg/.jpg/.png/.gif .');
                fileInput.value = '';
                return false;
            }
        }

        function editproducts(input) {
            var fileInput = document.getElementById('img');
            var url = input.value;
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#edit_upload_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                alert('กรุณาอัพโหลดไฟล์ประเภทรูปภาพ .jpeg/.jpg/.png/.gif .');
                fileInput.value = '';
                return false;
            }
        }

        function addunit() {
            var unitnew = document.getElementById("UNIT_INSERT").value;
            // alert(unitnew);
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ url('article/addunit') }}",
                method: "GET",
                data: {
                    unitnew: unitnew,
                    _token: _token
                },
                success: function(result) {
                    $('.show_unit').html(result);
                }
            })
        }

        function addunitsub() {
            var unitnew = document.getElementById("UNIT_SUBINSERT").value;
            // alert(unitnew);
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ url('article/addunit') }}",
                method: "GET",
                data: {
                    unitnew: unitnew,
                    _token: _token
                },
                success: function(result) {
                    $('.show_unitsub').html(result);
                }
            })
        }

        function addserve(input) {
            var fileInput = document.getElementById('img');
            var url = input.value;
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#add_upload_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                alert('กรุณาอัพโหลดไฟล์ประเภทรูปภาพ .jpeg/.jpg/.png/.gif .');
                fileInput.value = '';
                return false;
            }
        }

        function editserve(input) {
            var fileInput = document.getElementById('img');
            var url = input.value;
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#edit_upload_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                alert('กรุณาอัพโหลดไฟล์ประเภทรูปภาพ .jpeg/.jpg/.png/.gif .');
                fileInput.value = '';
                return false;
            }
        }
    </script>
</body>

</html>
