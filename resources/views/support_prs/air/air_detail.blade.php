@extends('layouts.mobile')
@section('title', 'PK-OFFICER || Air-Service')

@section('content')

 
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            font-size: 14px;
        }

        .cardfire {
            border-radius: 1em 1em 1em 1em;
            box-shadow: 0 0 15px pink;
            border: solid 1px #80acfd;
            /* box-shadow: 0 0 10px rgb(232, 187, 243); */
        }
    </style>
    <?php
    
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
        
    ?>



    <div class="container-fluid mt-4 mb-5">
        <div class="row text-center">
            <div id="overlay">
                <div class="cv-spinner">
                    <span class="spinner"></span>
                </div>
            </div> 
        </div> 
        <div id="preloader">
            <div id="status">
                <div class="spinner"> 
                </div>
            </div>
        </div>
        <div class="row text-center">
            <div class="col"></div>
            <div class="col-md-8 text-center">
                <h2>ประวัติการแจ้งซ่อมเครื่องปรับอากาศ</h2>
            </div>
            <div class="col"></div>
            
        </div>

        <div class="row mt-2">
            <div class="col-sm-12">
                <div class="card cardfire">
                    <div class="card-body">

                     
                                        <div class="row">
                                            <div class="col text-start"> 
                                                <p style="color:rgb(8, 129, 228)">ส่วนที่ 1 : รายละเอียด </p>
                                            </div>
                                            <div class="col-6 text-end"> 
                                                <?php 
                                                    $countqti_ = DB::select('SELECT COUNT(air_list_num) as air_list_num FROM air_repaire WHERE air_list_num = "'.$data_detail_->air_list_num.'"');
                                                    foreach ($countqti_ as $key => $value) {
                                                        $countqti = $value->air_list_num;
                                                    }
                                                ?>
                                                <p style="color:red">ซ่อมไปแล้ว {{$countqti}} ครั้ง</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col text-start">
                                                @if ($data_detail_->air_imgname == null)
                                                    <img src="{{ asset('assets/images/defailt_img.jpg') }}" height="180px" width="200px"
                                                        alt="Image" class="img-thumbnail">
                                                @else
                                                    <img src="{{ asset('storage/air/' . $data_detail_->air_imgname) }}" height="180px"
                                                        width="200px" alt="Image" class="img-thumbnail">
                                                @endif
                                            </div>
                                            <div class="col-8">
                                                <p style="color:rgb(8, 103, 228)">รหัส : {{ $data_detail_->air_list_num }}</p>
                                                <p style="color:rgb(8, 103, 228)">ชื่อ : {{ $data_detail_->air_list_name }}</p>
                                                <p style="color:rgb(8, 103, 228)">Btu : {{ $data_detail_->btu }}</p>
                                                <p style="color:rgb(8, 103, 228)">serial_no : {{ $data_detail_->serial_no }}</p>
                                                <p style="color:rgb(8, 103, 228)">ที่ตั้ง : {{ $data_detail_->air_location_name }}</p>
                                                <p style="color:rgb(8, 103, 228)">หน่วยงาน : {{ $data_detail_->detail }}</p>
                                            </div>
                                        </div>
                                        
                                        <hr style="color:red">
                                        <div class="row">
                                            <div class="col text-start">
                                                <p style="color:rgb(8, 129, 228)">ส่วนที่ 2 : ประวัติการซ่อมเครื่องปรับอากาศ(การบำรุงรักษา) </p>
                                            </div>
                                        </div>
                                        <div class="row"> 
                                            @foreach ($data_detail_sub_mai as $item_mai)
                                                <div class="col-11 text-start">
                                                    <div class="input-group">   
                                                        <img src="{{ asset('images/true.png') }}" width="30px" height="30px"> 
                                                        <p class="mt-2 ms-2" style="color:rgb(9, 119, 209)"> {{ $item_mai->repaire_sub_name }}ครั้งที่ {{ $item_mai->repaire_no }}</p>
                                                        <p class="mt-2 ms-2" style="color:rgb(247, 135, 61)"> {{ Datethai($item_mai->repaire_date) }}</p>
                                                    </div> 
                                                </div>
                                          
                                                {{-- <div class="col-3 text-end">
                                                    <div class="input-group">  
                                                        <p class="mt-2" style="color:rgb(247, 135, 61)"> {{ Datethai($item_mai->repaire_date) }} </p>
                                                    </div> 
                                                </div> --}}
                                            @endforeach 
                                        </div>


                                        <hr style="color:red">
                                        <div class="row">
                                            <div class="col text-start">
                                                <p style="color:rgb(8, 129, 228)">ส่วนที่ 3 : ประวัติการซ่อมเครื่องปรับอากาศ(รายการซ่อม(ตามปัญหา)) </p>
                                            </div>
                                        </div>
                                        <div class="row"> 
                                            @foreach ($data_detail_sub_plo as $item_plo)
                                                <div class="col-7 text-start">
                                                    <div class="input-group">   
                                                        <img src="{{ asset('images/true.png') }}" width="30px" height="30px"> 
                                                        &nbsp;&nbsp;<p class="mt-2" style="color:rgb(9, 119, 209)"> {{ $item_plo->repaire_sub_name }}</p>
                                                    </div> 
                                                </div>
                                                {{-- <div class="col"></div> --}}
                                                <div class="col-4 text-end">
                                                    <div class="input-group">  
                                                        <p class="mt-2" style="color:rgb(247, 135, 61)"> {{ Datethai($item_plo->repaire_date) }} </p>
                                                    </div> 
                                                </div>
                                            @endforeach 
                                        </div>
 
 
 
                    </div>
                </div>
            </div>
        </div>


    </div>

@endsection
@section('footer')
  
@endsection
