    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' /> 
    <link href='https://fonts.googleapis.com/css?family=Kanit&subset=thai,latin' rel='stylesheet' type='text/css'>
    <!-- Bootstrap Css -->
    <link href="{{ asset('pkclaim/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" /> 
    <!-- App Css-->
    <link href="{{ asset('pkclaim/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            font-size: 14px;   
            }
            .card_fire{
                border-radius: 2em 2em 2em 2em;
                box-shadow: 0 0 10px pink;
            }
            .checkboxs{
            width: 25px;
            height: 25px;
           }
    </style>
    <?php
        use SimpleSoftwareIO\QrCode\Facades\QrCode;
    ?> 
    <body> 
        <div class="container">
            <div class="row mt-3">
                <div class="card card_fire">
                    <div class="card-header card_fire text-center mt-2">
                        <h3>แบบประเมินความพึงพอใจการใช้งานระบบสารสนเทศตรวจดูผลการเช็คถังดับเพลิง(สำหรับผู้ใช้งานทั่วไป)<br>
                            โรงพยาบาลภูเขียวเฉลิมพระเกียรติ จังหวัดชัยภูมิ
                            </h3>
                    </div>
                    <div class="card-body"> 
                        <div class="row">
                            <div class="col">
                                <h5>
                                    คำชี้แจง แบบสอบถามชุดนี้จัดทำขึ้นเพื่อประเมินความพึงพอใจ/ไม่พึงพอใจ ความต้องการและความคาดหวังของผู้รับบริการต่อการใช้งานระบบสารสนเทศตรวจดูผลเช็คถังดับเพลิง(สำหรับผู้ใช้งานทั่วไป)  
                                    ซึ่งข้อมูลที่ได้จะเป็นประโยชน์อย่างยิ่งต่อการพัฒนาระบบให้มีประสิทธิภาพต่อไป
                                </h5>
                                <h4>
                                    กรุณาทำเครื่องหมาย  <img src="{{ asset('images/true.png') }}" alt="" height="30"> ในช่องที่ท่านเลือก 
                                </h4>
                                <h3 style="color: rgb(6, 151, 103)"> 
                                    ตอนที่ 1  ข้อมูลทั่วไปของผู้ตอบแบบสอบถาม 
                                </h3> 
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <h5>1. เพศ </h5>
                            </div>
                            <div class="col-3">
                                <div class="form-check">
                                    <input class="form-check-input checkboxs mt-2" type="radio" name="flexRadioDefault" id="sex_man" checked>
                                    <label class="form-check-label mt-2 ms-2" for="sex_man">
                                        ชาย
                                    </label>
                                  </div>  
                            </div>
                            <div class="col-3">
                                <div class="form-check">
                                    <input class="form-check-input checkboxs mt-2" type="radio" name="flexRadioDefault" id="sex_men">
                                    <label class="form-check-label mt-2 ms-2" for="sex_men">
                                      หญิง
                                    </label>
                                  </div>
                            </div>
                            <div class="col"></div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <h5>2. ประเภทผู้ใช้บริการ </h5>
                            </div>
                            <div class="col-3">
                                <div class="form-check">
                                    <input class="form-check-input checkboxs mt-2" type="radio" name="flexRadioType" id="user_type_a">
                                    <label class="form-check-label mt-2 ms-2" for="user_type_a">
                                        ผู้รับบริการ
                                    </label>
                                  </div>  
                            </div>
                            <div class="col-3">
                                <div class="form-check">
                                    <input class="form-check-input checkboxs mt-2" type="radio" name="flexRadioType" id="user_type_b" checked>
                                    <label class="form-check-label mt-2 ms-2" for="user_type_b">
                                        บุคลากร รพ.
                                    </label>
                                  </div>
                            </div>
                            <div class="col"></div>
                        </div>

                        <div class="row">
                            <div class="col-2">
                                <h5>3. อายุ </h5>
                            </div>
                            <div class="col-3">
                                <div class="form-check">
                                    <input class="form-check-input checkboxs mt-2" type="radio" name="flexRadioAge" id="age_1">
                                    <label class="form-check-label mt-2 ms-2" for="age_1">
                                        ต่ำกว่า 19 ปี
                                    </label>
                                  </div>  
                            </div>
                            <div class="col-2">
                                <div class="form-check">
                                    <input class="form-check-input checkboxs mt-2" type="radio" name="flexRadioAge" id="age_2">
                                    <label class="form-check-label mt-2 ms-2" for="age_2">
                                        19-24 ปี
                                    </label>
                                  </div>  
                            </div>
                            <div class="col-2">
                                <div class="form-check">
                                    <input class="form-check-input checkboxs mt-2" type="radio" name="flexRadioAge" id="age_3">
                                    <label class="form-check-label mt-2 ms-2" for="age_3">
                                        25-40 ปี	
                                    </label>
                                  </div>
                            </div>
                            <div class="col-3">
                                <div class="form-check">
                                    <input class="form-check-input checkboxs mt-2" type="radio" name="flexRadioAge" id="age_4">
                                    <label class="form-check-label mt-2 ms-2" for="age_4">
                                        40 ปีขึ้นไป	
                                    </label>
                                  </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col"> 
                                <h3 style="color: rgb(6, 151, 103)"> 
                                    ตอนที่ 2  ระดับความพึงพอใจในการใช้งานระบบสารสนเทศ  
                                </h3> 
                                <h4 style="color: rgb(6, 151, 103)"> 
                                    โดยมีเกณฑ์วัดระดับดังนี้    5 = มากที่สุด 4 = มาก 3 = ปานกลาง  2 = น้อย 1 = น้อยที่สุด 0 = ไม่พึงพอใจ
                                </h4> 
                            </div> 
                        </div>
                        {{-- <div class="row">
                            <div class="col">  
                                <h4 style="color: rgb(6, 151, 103)"> 
                                      1 = น้อยที่สุด 0 = ไม่พึงพอใจ
                                </h4> 
                            </div> 
                        </div> --}}

                        <div class="row">
                            <div class="col">  
                               <table class="table table-bordered table-striped table-hover" style="width: 100%">
                                    <thead> 
                                        <tr>
                                            <th colspan="1" class="text-center" style="width: 50%">ประเด็นวัดความพึงพอใจ</th>
                                            <th colspan="5" class="text-center" style="width: 25%">ระดับความพึงพอใจ</th>
                                            <th class="text-center" style="width: 15%">ไม่พึงพอใจ</th>
                                        </tr>
                                        <tr>
                                            <th colspan="1" class="text-center" style="width: 50%"></th>
                                            <th class="text-center">5</th>
                                            <th class="text-center">4</th>
                                            <th class="text-center">3</th>
                                            <th class="text-center">2</th>
                                            <th class="text-center">1</th>
                                            <th class="text-center">0</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datashow as $item)
                                            <tr>
                                                <td colspan="1" class="text-start">{{$item->fire_pramuan_name}}</td> 
                                                <td class="text-center"><input class="form-check-input checkboxs" type="radio" name="{{$item->fire_pramuan_id}}" id="fire_pramuan_5"></td>
                                                <td class="text-center"><input class="form-check-input checkboxs" type="radio" name="{{$item->fire_pramuan_id}}" id="fire_pramuan_4"></td>
                                                <td class="text-center"><input class="form-check-input checkboxs" type="radio" name="{{$item->fire_pramuan_id}}" id="fire_pramuan_3"></td>
                                                <td class="text-center"><input class="form-check-input checkboxs" type="radio" name="{{$item->fire_pramuan_id}}" id="fire_pramuan_2"></td>
                                                <td class="text-center"><input class="form-check-input checkboxs" type="radio" name="{{$item->fire_pramuan_id}}" id="fire_pramuan_1"></td>
                                                <td class="text-center"><input class="form-check-input checkboxs" type="radio" name="{{$item->fire_pramuan_id}}" id="fire_pramuan_0"></td>
                                            </tr>
                                        @endforeach
                                        
                                    
                                    </tbody>
                               </table>
                            </div> 
                        </div>

                    </div>
                </div>
            </div>  
        </div>
      
     <!-- JAVASCRIPT -->
     <script src="{{ asset('pkclaim/libs/jquery/jquery.min.js') }}"></script>
     <script src="{{ asset('pkclaim/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script> 
    </body>
        
                     
