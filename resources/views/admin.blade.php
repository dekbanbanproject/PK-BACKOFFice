@extends('layouts.admindashboard')
@section('title', 'PK-BACKOFFice  || ผู้ดูแลระบบ')

@section('content')
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

{{-- headerZ --}}
 <br>
    {{-- <div class="container mt-3"> --}}
        <div class="container-fluid mt-3 mb-4">
            {{-- <div class="circle1"> </div> --}}

        <div id="preloader">
            <div id="status">
                <div class="spinner">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">บุคคลากร</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('person/person_index') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-user-tie font-size-25 mt-3" style="color: rgb(234, 157, 172)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">ระบบลงเวลา</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('time_dashboard') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-danger rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">

                                                                <i class="fa-regular fa-3x fa-clock font-size-25 mt-3" style="color: rgb(119, 218, 215)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">โอที</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('otone') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-people-line font-size-25 mt-3" style="color: rgb(131, 105, 192)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">ระบบการลา</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('gleave') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                 <i class="fa-solid fa-3x fa-hospital-user font-size-25 mt-3" style="color: rgb(237, 102, 176)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">สารบรรณ</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('book/bookmake_index') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-book-open-reader font-size-25 mt-3" style="color: rgb(220, 136, 227)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">ยานพาหนะ</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('car/car_narmal_calenda') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-truck-medical font-size-25 mt-3" style="color: rgb(145, 234, 243)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">ห้องประชุม</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('meetting/meettingroom_dashboard') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                 <i class="fa-solid fa-3x fa-house-laptop font-size-25 mt-3" style="color: rgb(118, 223, 176)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">ซ่อมบำรุง</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('repaire_narmal') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-screwdriver-wrench font-size-25 mt-3" style="color: rgb(90, 160, 212)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

        <div class="row">
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">แผนงาน</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('plan') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-clipboard font-size-25 mt-3" style="color: rgb(240, 136, 136)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">ทรัพย์สิน</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('article/article_index') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-building-shield font-size-25 mt-3" style="color: rgb(160, 173, 166)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">พัสดุ</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('supplies/supplies_index') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                 <i class="fa-solid fa-3x fa-paste font-size-25 mt-3" style="color: rgb(103, 205, 161)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">คอมพิวเตอร์</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('computer/com_staff_calenda') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-computer font-size-25 mt-3" style="color: rgb(143, 145, 144)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">เครื่องมือแพทย์</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('medical/med_calenda') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                 <i class="fa-solid fa-3x fa-notes-medical font-size-25 mt-3" style="color: rgb(137, 134, 236)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">ซ่อมบำรุง</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('repaire_narmal') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-screwdriver-wrench font-size-25 mt-3" style="color: rgb(90, 160, 212)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
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

        <div class="row">
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">คลังวัสดุ</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('warehouse/warehouse_index') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-warehouse font-size-25 mt-3" style="color: rgb(107, 189, 202)"></i>
                                                            </button>  
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">ห้องประชุม</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('meetting/meettingroom_dashboard') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                 <i class="fa-solid fa-3x fa-house-laptop font-size-25 mt-3" style="color: rgb(118, 223, 176)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">บ้านพัก</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('housing/housing_dashboard') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-house-chimney-user font-size-25 mt-3" style="color: rgb(103, 153, 192)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">คลังยา</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-prescription font-size-25 mt-3" style="color: rgb(63, 128, 128)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">จ่ายกลาง</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-person-booth font-size-25 mt-3" style="color: rgb(187, 115, 115)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">งานประกัน</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('pkclaim/pkclaim_info') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                 <i class="fa-solid fa-3x fa-sack-dollar font-size-25 mt-3" style="color: rgb(235, 111, 158)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">การเงิน</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('account_info') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-money-check-dollar font-size-25 mt-3" style="color: rgb(225, 131, 171)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">การบัญชี</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('account_pk_dash') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-file-invoice-dollar font-size-25 mt-3" style="color: rgb(156, 149, 152)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
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

        <div class="row">
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">P4P</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('p4p') }}" target="_blank">
                                                    {{-- <span class="avatar-title bg-white text-primary rounded-3"> --}}
                                                        <span class="avatar-title bg-white rounded-3 mt-2" style="height: 10px">
                                                        <p style="font-size: 15px;">
                                                            <button class="mt-5 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3" style="height: 70px;width: 90px">
                                                                <i class="fa-solid fa-p text-danger font-size-24 mt-3"></i>
                                                                <i class="fa-solid fa-4 text-warning font-size-24 mt-3"></i>
                                                                <i class="fa-solid fa-p text-info font-size-24 mt-3"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">แพทย์แผนไทย</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('medicine_salt') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                 <i class="fa-solid fa-3x fa-square-person-confined font-size-25 mt-3" style="color: rgb(182, 120, 198)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">ระบบลงเวลา</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('time_dashboard') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-danger rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">

                                                                <i class="fa-regular fa-3x fa-clock font-size-25 mt-3" style="color: rgb(119, 218, 215)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">โอที</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('otone') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-people-line font-size-25 mt-3" style="color: rgb(131, 105, 192)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="col-xl-2 col-md-2">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">ENV</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('env_dashboard') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-hand-holding-droplet font-size-25 mt-3" style="color: rgb(90, 197, 215)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-xl-3 col-md-3">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">P4P</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('p4p') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                <i class="fa-solid fa-3x fa-person-booth font-size-25 mt-3" style="color: rgb(155, 50, 50)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  --}}
            {{-- <div class="col-xl-3 col-md-3">
                <div class="main-card mb-3 card">
                    <div class="grid-menu-col">
                        <div class="g-0 row">
                            <div class="col-sm-12">
                                <div class="widget-chart widget-chart-hover">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="text-start font-size-13 mb-2">PK-BACKOFFice</p>
                                                <h5 class="text-start mb-2">แพทย์แผนไทย</h5>
                                            </div>
                                            <div class="avatar-sm me-2">
                                                <a href="{{ url('medicine_salt') }}" target="_blank">
                                                    <span class="avatar-title bg-white text-primary rounded-3">
                                                        <p style="font-size: 10px;">
                                                            <button class="mt-5 mb-3 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info avatar-title bg-white text-primary rounded-3">
                                                                 <i class="fa-solid fa-3x fa-square-person-confined font-size-25 mt-3" style="color: rgb(159, 9, 197)"></i>
                                                            </button>
                                                        </p>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>               --}}

        </div>


    </div>


    <?php
    $datadetail = DB::connection('mysql')->select('
                                select * from orginfo
                                where orginfo_id = 1
                                 ');
    ?>

  @endsection
