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
 
 

    <div class="container-fluid mt-3">
        <div class="row text-center">
            <div class="col"></div>
            <div class="col-md-3">
                <h2>ทะเบียนครุภัณฑ์แอร์</h2>
            </div>
            <div class="col"></div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-12">
                <div class="card cardfire">
                    <div class="card-body">
                        <div class="row">
                            <div class="col text-start">
                                {{-- <p style="color:red">ส่วนที่ 1 : รายละเอียดทะเบียนครุภัณฑ์แอร์ </p> --}}
                                <p style="color:red">ส่วนที่ 1 : รายละเอียด </p>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col text-start">
                                @if ($data_detail_->air_imgname == null)
                                    <img src="{{ asset('assets/images/defailt_img.jpg') }}" height="120px"
                                        width="90px" alt="Image" class="img-thumbnail">
                                @else
                                    <img src="{{ asset('storage/fire/' . $data_detail_->air_imgname) }}" height="120px"
                                        width="90px" alt="Image" class="img-thumbnail">
                                @endif
                            </div>
                            <div class="col-9">
                                <p>รหัส : {{ $data_detail_->air_list_num }}</p>
                                <p>ชื่อ : {{ $data_detail_->air_list_name }}</p>
                                <p>Btu : {{ $data_detail_->btu }}</p>
                                <p>serial_no : {{ $data_detail_->serial_no }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-start">
                                <p>ที่ตั้ง : {{ $data_detail_->air_location_name }}</p>
                            </div>
                        </div>

                        <hr style="color:red">
                        <div class="row">
                            <div class="col text-start">
                                <p style="color:red">ส่วนที่ 2 : ช่างซ่อม(นอก รพ.) </p> 
                            </div> 
                        </div>

                        <div class="row">
                            <div class="col text-start"> 
                                <p style="color:rgb(9, 119, 209)">- รายการซ่อม(ตามปัญหา) </p> 
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-3 text-start"> 
                                    <div class="input-group">
                                        <input type="checkbox" class="discheckbox" id="air_problems_1" name="air_problems_1"> 
                                        &nbsp;<p>น้ำหยด</p> 
                                    </div>
                            </div>
                            <div class="col-5">
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_2" name="air_problems_2"> 
                                    <p class="ms-1">ไม่เย็นมีแต่ลม</p> 
                                </div>
                            </div>
                            <div class="col-4">  
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_3" name="air_problems_3"> 
                                    &nbsp;<p> มีกลิ่นเหม็น</p> 
                                </div>
                            </div>
                        </div> 
                        <div class="row"> 
                            <div class="col-3 text-start">  
                                    <div class="input-group">
                                        <input type="checkbox" class="discheckbox" id="air_problems_4" name="air_problems_4"> 
                                      <p>เสียงดัง</p> 
                                    </div>
                            </div>
                            <div class="col-5">
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_5" name="air_problems_5"> 
                                    <p class="ms-1">ไม่ติด/ติดๆ ดับๆ</p> 
                                </div>
                            </div>
                            <div class="col text-start"> 
                            </div>
                        </div> 
                        
                        <hr style="color:rgb(7, 114, 141)">
                        <div class="row">
                            <div class="col text-start"> 
                                <p style="color:rgb(9, 119, 209)">- การบำรุงรักษา ประจำปี ครั้ง 1 </p> 
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-7 text-start"> 
                                    <div class="input-group">
                                        <input type="checkbox" class="discheckbox" id="air_problems_6" name="air_problems_6"> 
                                        &nbsp;&nbsp;<p>ถอดล้างพัดลมกรงกระรอก</p> 
                                    </div>
                            </div> 
                            <div class="col-5"> 
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_7" name="air_problems_7"> 
                                    &nbsp;&nbsp;<p>ล้างถาดหลังแอร์</p> 
                                </div>
                            </div>
                        </div>
                    
                        <div class="row"> 
                            <div class="col-6 text-start"> 
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_8" name="air_problems_8"> 
                                    &nbsp;&nbsp;<p>ล้างแผงคอยล์เย็น</p> 
                                </div>
                            </div> 
                            <div class="col-6"> 
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_9" name="air_problems_9" > 
                                    &nbsp;&nbsp;<p>ล้างแผงคอยล์ร้อน</p> 
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col text-start">
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_10" name="air_problems_10"> 
                                    &nbsp;&nbsp;<p>ตรวจเช็คน้ำยา</p> 
                                </div>
                            </div> 
                        </div> 

                        <hr style="color:rgb(7, 114, 141)">
                        <div class="row">
                            <div class="col text-start"> 
                                <p style="color:rgb(9, 119, 209)">- การบำรุงรักษา ประจำปี ครั้ง 2 </p> 
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-7 text-start"> 
                                    <div class="input-group">
                                        <input type="checkbox" class="discheckbox" id="air_problems_11" name="air_problems_11"> 
                                        &nbsp;&nbsp;<p>ถอดล้างพัดลมกรงกระรอก</p> 
                                    </div>
                            </div> 
                            <div class="col-5"> 
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_12" name="air_problems_12"> 
                                    &nbsp;&nbsp;<p>ล้างถาดหลังแอร์</p> 
                                </div>
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-6 text-start"> 
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_13" name="air_problems_13"> 
                                    &nbsp;&nbsp;<p>ล้างแผงคอยล์เย็น</p> 
                                </div>
                            </div> 
                            <div class="col-6"> 
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_14" name="air_problems_14"> 
                                    &nbsp;&nbsp;<p>ล้างแผงคอยล์ร้อน</p> 
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col text-start">
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_15" name="air_problems_15"> 
                                    &nbsp;&nbsp;<p>ตรวจเช็คน้ำยา</p> 
                                </div>
                            </div> 
                        </div> 

                        <hr style="color:rgb(7, 114, 141)">
                        <div class="row">
                            <div class="col text-start"> 
                                <p style="color:rgb(9, 119, 209)">- การบำรุงรักษา ประจำปี ครั้ง 3 </p> 
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="col-7 text-start"> 
                                    <div class="input-group">
                                        <input type="checkbox" class="discheckbox" id="air_problems_16" name="air_problems_16"> 
                                        &nbsp;&nbsp;<p>ถอดล้างพัดลมกรงกระรอก</p> 
                                    </div>
                            </div> 
                            <div class="col-5"> 
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_17" name="air_problems_17"> 
                                    &nbsp;&nbsp;<p>ล้างถาดหลังแอร์</p> 
                                </div>
                            </div>
                        </div>
                    
                        <div class="row"> 
                            <div class="col-6 text-start"> 
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_18" name="air_problems_18"> 
                                    &nbsp;&nbsp;<p>ล้างแผงคอยล์เย็น</p> 
                                </div>
                            </div> 
                            <div class="col-6"> 
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_19" name="air_problems_19"> 
                                    &nbsp;&nbsp;<p>ล้างแผงคอยล์ร้อน</p> 
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col text-start">
                                <div class="input-group">
                                    <input type="checkbox" class="discheckbox" id="air_problems_20" name="air_problems_20"> 
                                    &nbsp;&nbsp;<p>ตรวจเช็คน้ำยา</p> 
                                </div>
                            </div> 
                        </div> 

                        <hr style="color:rgb(7, 114, 141)">                        
                        <div class="row">
                            <div class="col text-start">
                                <p >สถานะซ่อม :</p>
                            </div>
                            <div class="col-8">
                                <select class="custom-select custom-select-sm" id="air_status_techout" name="air_status_techout" style="width: 100%">
                                    <option value="" class="text-center">- เลือก -</option>
                                    <option value="Y" class="text-center">- พร้อมใช้งาน -</option>
                                    <option value="N" class="text-center">- ไม่พร้อมใช้งาน -</option> 
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-start">
                                <p>ชื่อ-นามสกุล :</p>
                            </div>
                            <div class="col-8">
                                 <input type="text" class="form-control form-control-sm" id="air_techout_name" name="air_techout_name">
                            </div>
                        </div>
                        <div class="row">
                     
                                <div id="signature-pad" class="mt-2 text-center">
                                    <div style="border:solid 1px teal;height:120px;">
                                        <div id="note" onmouseover="my_function();" class="text-center">The
                                            signature should be inside box</div>
                                        <canvas id="the_canvas" width="320px" height="120px"> </canvas>
                                    </div>

                                    <input type="hidden" id="signature" name="signature">

                                    <button type="button" id="clear_btn"
                                        class="btn btn-secondary btn-sm mt-3 ms-2 me-2" data-action="clear"><span
                                            class="glyphicon glyphicon-remove"></span>
                                        Clear</button>

                                    <button type="button" id="save_btn"
                                        class="btn btn-info btn-sm mt-3 me-2 text-white" data-action="save-png"
                                        onclick="create()"><span class="glyphicon glyphicon-ok"></span>
                                        Create
                                    </button>
                                </div>
                        </div>
 


                        <hr style="color:red">
                        <div class="row">
                            <div class="col text-start">
                                <p style="color:red">ส่วนที่ 3 : เจ้าหน้าที่ </p>
                            </div>
                            <div class="col-6"> </div>
                        </div>
                        <div class="row">
                            <div class="col text-start">
                                <p >สถานะซ่อม :</p>
                            </div>
                            <div class="col-8">
                                <select class="custom-select custom-select-sm" id="air_status_staff" name="air_status_staff" style="width: 100%">
                                    <option value="" class="text-center">- เลือก -</option>
                                    <option value="Y" class="text-center">- พร้อมใช้งาน -</option>
                                    <option value="N" class="text-center">- ไม่พร้อมใช้งาน -</option> 
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-start">
                                <p>ชื่อ-นามสกุล :</p>
                            </div>
                            <div class="col-8">
                                 {{-- <input type="text" class="form-control form-control-sm" id="air_staff_id" name="air_staff_id"> --}}
                                 <select class="custom-select custom-select-sm" id="air_staff_id" name="air_staff_id" style="width: 100%">
                                    <option value="" class="text-center">- เลือก -</option>
                                    @foreach ($users as $item_u)
                                        <option value="{{$item_u->id}}" class="text-center">{{$item_u->fname}} {{$item_u->lname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                       
                            <div id="signature-pad2" class="mt-2 text-center">
                                <div style="border:solid 1px teal;height:120px;">
                                    <div id="note2" onmouseover="my_function2();" class="text-center">The
                                        signature should be inside box</div>
                                    <canvas id="the_canvas2" width="320px" height="120px"> </canvas>
                                </div>

                                <input type="hidden" id="signature2" name="signature2">
                                <button type="button" id="clear_btn2"
                                    class="btn btn-secondary btn-sm mt-3 ms-2 me-2" data-action="clear2"><span
                                        class="glyphicon glyphicon-remove"></span>
                                    Clear</button>

                                <button type="button" id="save_btn2"
                                    class="btn btn-info btn-sm mt-3 me-2 text-white" data-action="save-png2"
                                    onclick="create2()"><span class="glyphicon glyphicon-ok"></span>
                                    Create</button>

                            </div>
                        </div>

                        <hr style="color:red">
                        <div class="row">
                            <div class="col text-start">
                                <p style="color:red">ส่วนที่ 4 : ช่างซ่อม(รพ.) </p>
                            </div>
                            <div class="col-6"> </div>
                        </div>
                        <div class="row">
                            <div class="col text-start">
                                <p >สถานะซ่อม :</p>
                            </div>
                            <div class="col-8">
                                <select class="custom-select custom-select-sm" id="air_status_tech" name="air_status_tech" style="width: 100%">
                                    <option value="" class="text-center">- เลือก -</option>
                                    <option value="Y" class="text-center">- พร้อมใช้งาน -</option>
                                    <option value="N" class="text-center">- ไม่พร้อมใช้งาน -</option> 
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-start">
                                <p>ชื่อ-นามสกุล :</p>
                            </div>
                            <div class="col-8">
                                 {{-- <input type="text" class="form-control form-control-sm" id="air_tech_id" name="air_tech_id"> --}}
                                 <select class="custom-select custom-select-sm" id="air_tech_id" name="air_tech_id" style="width: 100%">
                                    <option value="" class="text-center">- เลือก -</option>
                                    @foreach ($users as $item_u)
                                        <option value="{{$item_u->id}}" class="text-center">{{$item_u->fname}} {{$item_u->lname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                       
                            <div id="signature-pad3" class="mt-2 text-center">
                                <div style="border:solid 1px teal;height:120px;">
                                    <div id="note3" onmouseover="my_function3();" class="text-center">The
                                        signature should be inside box</div>
                                    <canvas id="the_canvas3" width="320px" height="120px"> </canvas>
                                </div>

                                <input type="hidden" id="signature3" name="signature3">
                                <button type="button" id="clear_btn3"
                                    class="btn btn-secondary btn-sm mt-3 ms-2 me-2" data-action="clear3"><span
                                        class="glyphicon glyphicon-remove"></span>
                                    Clear</button>

                                <button type="button" id="save_btn3"
                                    class="btn btn-info btn-sm mt-3 me-2 text-white" data-action="save-png3"
                                    onclick="create3()"><span class="glyphicon glyphicon-ok"></span>
                                    Create</button>

                            </div>
                        </div>

                        <hr style="color:red">
                        <div class="row mt-3">
                            <div class="col text-center">
                                <button type="button" id="saveBtn" class="ladda-button btn-pill btn btn-success">
                                    <i class="fa-solid fa-circle-check text-white me-2"></i>
                                    บันทึกข้อมูล
                                </button>
                            </div>
                        </div>

                       
                    </div>
                </div>

            </div>
        </div>
    </div>

    @endsection
@section('footer')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="{{ asset('js/gcpdfviewer.js') }}"></script>
     <script>
         $(document).ready(function() {
            $('select').select2();
         });
     </script>
<script>
     
    //ช่างซ่อมนอก
    var wrapper = document.getElementById("signature-pad");
    var clearButton = wrapper.querySelector("[data-action=clear]");
    var savePNGButton = wrapper.querySelector("[data-action=save-png]");
    var canvas = wrapper.querySelector("canvas");
    var el_note = document.getElementById("note");
    var signaturePad;
    signaturePad = new SignaturePad(canvas);
    clearButton.addEventListener("click", function(event) {
        document.getElementById("note").innerHTML = "The signature should be inside box";
        signaturePad.clear();
    });
    savePNGButton.addEventListener("click", function(event) {
        if (signaturePad.isEmpty()) {
            // alert("Please provide signature first.");
            Swal.fire(
                'กรุณาลงลายเซนต์ก่อน !',
                'You clicked the button !',
                'warning'
            )
            event.preventDefault();
        } else {
            var canvas = document.getElementById("the_canvas");
            var dataUrl = canvas.toDataURL();
            document.getElementById("signature").value = dataUrl;

            // ข้อความแจ้ง
            Swal.fire({
                title: 'สร้างสำเร็จ',
                text: "You create success",
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#06D177',
                confirmButtonText: 'เรียบร้อย'
            }).then((result) => {
                if (result.isConfirmed) {}
            })
        }
    });

    function my_function() {
        document.getElementById("note").innerHTML = "";
    }

    // เจ้าหน้าที่
    var wrapper = document.getElementById("signature-pad2");
    var clearButton2 = wrapper.querySelector("[data-action=clear2]");
    var savePNGButton2 = wrapper.querySelector("[data-action=save-png2]");
    var canvas2 = wrapper.querySelector("canvas");
    var el_note = document.getElementById("note2");
    var signaturePad2;
    signaturePad2 = new SignaturePad(canvas2);
    clearButton2.addEventListener("click", function(event) {
        document.getElementById("note2").innerHTML = "The signature should be inside box";
        signaturePad2.clear();
    });
    savePNGButton2.addEventListener("click", function(event) {
        if (signaturePad2.isEmpty()) {
            // alert("Please provide signature first.");
            Swal.fire(
                'กรุณาลงลายเซนต์ก่อน !',
                'You clicked the button !',
                'warning'
            )
            event.preventDefault();
        } else {
            var canvas = document.getElementById("the_canvas2");
            var dataUrl = canvas.toDataURL();
            document.getElementById("signature2").value = dataUrl;

            // ข้อความแจ้ง
            Swal.fire({
                title: 'สร้างสำเร็จ',
                text: "You create success",
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#06D177',
                confirmButtonText: 'เรียบร้อย'
            }).then((result) => {
                if (result.isConfirmed) {}
            })
        }
    });

    function my_function2() {
        document.getElementById("note2").innerHTML = "";
    }

    // ช่างซ่อมใน รพ 
    var wrapper = document.getElementById("signature-pad3");
    var clearButton3 = wrapper.querySelector("[data-action=clear3]");
    var savePNGButton3 = wrapper.querySelector("[data-action=save-png3]");
    var canvas3 = wrapper.querySelector("canvas");
    var el_note = document.getElementById("note3");
    var signaturePad3;
    signaturePad3 = new SignaturePad(canvas3);
    clearButton3.addEventListener("click", function(event) {
        document.getElementById("note3").innerHTML = "The signature should be inside box";
        signaturePad3.clear();
    });
    savePNGButton3.addEventListener("click", function(event) {
        if (signaturePad3.isEmpty()) {
            // alert("Please provide signature first.");
            Swal.fire(
                'กรุณาลงลายเซนต์ก่อน !',
                'You clicked the button !',
                'warning'
            )
            event.preventDefault();
        } else {
            var canvas = document.getElementById("the_canvas3");
            var dataUrl = canvas.toDataURL();
            document.getElementById("signature2").value = dataUrl;

            // ข้อความแจ้ง
            Swal.fire({
                title: 'สร้างสำเร็จ',
                text: "You create success",
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#06D177',
                confirmButtonText: 'เรียบร้อย'
            }).then((result) => {
                if (result.isConfirmed) {}
            })
        }
    });

    function my_function3() {
        document.getElementById("note3").innerHTML = "";
    }
</script>
<script>
    $(document).ready(function() {
        $('#saveBtn').click(function() {
            // alert('okkkkk'); 
            var com_repaire_id = $('#com_repaire_id').val();
            var com_repaire_no = $('#com_repaire_no').val();

            var com_repaire_work_date = $('#com_repaire_work_date').val();
            var com_repaire_work_time = $('#com_repaire_work_time').val();
            var com_repaire_article_id = $('#com_repaire_article_id').val();
            var com_repaire_tec_id = $('#com_repaire_tec_id').val();
            var com_repaire_detail_tech = $('#com_repaire_detail_tech').val();
            var com_repaire_rep_id = $('#com_repaire_rep_id').val();
            var signature = $('#signature').val(); //ผู้รับงาน
            var signature2 = $('#signature2').val(); //ผู้ส่งงาน
            // var user_id = $('#user_id').val();
            // alert(signature);
            $.ajax({
                url: "{{ route('com.com_staff_index_update') }}",
                type: "POST",
                dataType: 'json',
                data: {
                    com_repaire_id,
                    com_repaire_no,
                    com_repaire_work_date,
                    com_repaire_work_time,
                    com_repaire_article_id,
                    com_repaire_tec_id,
                    com_repaire_detail_tech,
                    com_repaire_rep_id,
                    signature,
                    signature2
                },
                success: function(data) {
                    if (data.status == 0) {

                    } else if (data.status == 50) {
                        Swal.fire(
                            'กรุณาลงลายชื่อผู้รับงาน !',
                            'You clicked the button !',
                            'warning'
                        )
                    } else if (data.status == 60) {
                        Swal.fire(
                            'กรุณาลงลายชื่อผู้ส่งงาน !',
                            'You clicked the button !',
                            'warning'
                        )
                    } else {
                        Swal.fire({
                            title: 'บันทึกข้อมูลสำเร็จ',
                            text: "You Edit data success",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#06D177',
                            confirmButtonText: 'เรียบร้อย'
                        }).then((result) => {
                            if (result
                                .isConfirmed) {
                                console.log(
                                    data);
                                window.location =
                                    "{{ url('computer/com_staff_index') }}"; // กรณี add page new  
                            }
                        })
                    }
                },
            });
        });

    });
</script>
@endsection
