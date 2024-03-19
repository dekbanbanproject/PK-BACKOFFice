@extends('layouts.person')

@section('title', 'PK-HOS || บุคลากร')


@section('content')
    <script>
        function TypeAdmin() {
            window.location.href = '{{ route('index') }}';
        }

        function addpre() {
            var prenew = document.getElementById("PRE_INSERT").value;
            // alert(prenew);
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ url('person/addpre') }}",
                method: "GET",
                data: {
                    prenew: prenew,
                    _token: _token
                },
                success: function(result) {
                    $('.show_pre').html(result);
                }
            })
        }

        function addpic(input) {
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
        border: 10px #ddd solid;
        border-top: 10px #d22cf3 solid;
        border-radius: 50%;
        animation: sp-anime 0.8s infinite linear;
    }

    @keyframes sp-anime {
        100% {
            transform: rotate(390deg);
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
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header ">
                        {{-- <div class="d-flex">
                            <div class=""> --}}
                        <label for="">เพิ่มข้อมูลบุคลากร</label>
                        {{-- </div> --}}
                        {{-- <div class="ms-auto">

                            </div> --}}
                        {{-- </div> --}}
                    </div>
                    <div class="card-body">
                        {{-- <h4 class="card-title mb-4">เพิ่มข้อมูลบุคลากร</h4> --}}

                        <div id="progrss-wizard" class="twitter-bs-wizard">
                            <ul class="twitter-bs-wizard-nav nav-justified">
                                <li class="nav-item">
                                    <a href="#progress-seller-details" class="nav-link" data-toggle="tab">
                                        <span class="step-number">01</span>
                                        <span class="step-title">ข้อมูลส่วนตัว</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#progress-company-document" class="nav-link" data-toggle="tab">
                                        <span class="step-number">02</span>
                                        <span class="step-title">ข้อมูลอาชีพ</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#progress-bank-detail" class="nav-link" data-toggle="tab">
                                        <span class="step-number">03</span>
                                        <span class="step-title">รูปภาพ</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#progress-confirm-detail" class="nav-link" data-toggle="tab">
                                        <span class="step-number">04</span>
                                        <span class="step-title">Confirm Detail</span>
                                    </a>
                                </li>

                            </ul>

                            <div id="bar" class="progress mt-4">
                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"></div>
                            </div>
                            <div class="tab-content twitter-bs-wizard-tab-content">
                                <div class="tab-pane" id="progress-seller-details">

                                    <form action="{{ route('person.person_save') }}" method="POST" id="insert_personForm"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <div class="mb-3">
                                                    <label class="form-label" for="pname">คำนำหน้า :</label>
                                                    <select id="pname" name="pname"
                                                        class="form-control select2 show_pre" style="width: 100%">
                                                        <option value=""></option>
                                                        @foreach ($users_prefix as $pre)
                                                            <option value="{{ $pre->prefix_id }}">{{ $pre->prefix_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fname" style="color: red">ชื่อ
                                                        :</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="fname" name="fname">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="lname" style="color: red">นามสกุล
                                                        :</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="lname" name="lname">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="username"
                                                        style="color: red">ชื่อผู้ใช้งาน</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="username" name="username">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-5">
                                                <div class="mb-3">
                                                    <label class="form-label" for="cid">บัตรประชาชน</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="cid" name="cid">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="cid">เงินเดือน</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="money" name="money">
                                                  
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="store_id"
                                                        style="color: red">โรงพยาบาล</label>
                                                        <select id="store_id" name="store_id"
                                                            class="form-control " style="width: 100%">
                                                            <option value=""></option>
                                                            @foreach ($users_hos as $hos)
                                                                <option value="{{ $hos->users_hos_id }}"> {{ $hos->users_hos_name }}</option>
                                                            @endforeach
                                                        </select>
                                                  
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="line_token">Line Token</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                    id="line_token" name="line_token">
                                                    {{-- <textarea id="line_token" name="line_token" class="form-control" rows="2"></textarea> --}}
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="line_token">กลุ่ม P4P</label>
                                                    <select id="group_p4p" name="group_p4p"
                                                    class="form-select form-select-sm" style="width: 100%" >
                                                    <option value=""> </option>
                                                    @foreach ($p4p_work_position as $its) 
                                                   
                                                    <option value="{{ $its->p4p_work_position_id }}"> {{ $its->p4p_work_position_code }}::{{ $its->p4p_work_position_name }} </option> 
                                                   
                                                   
                                                    @endforeach
                                                </select>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="staff">Staff hos</label>
                                                    <select id="staff" name="staff"
                                                    class="form-select form-select-sm" style="width: 100%" >
                                                    <option value=""> </option>
                                                    @foreach ($opduser as $its_u) 
                                                    <option value="{{ $its_u->loginname }}"> {{ $its_u->name }} </option>  
                                                   
                                                    @endforeach
                                                </select>
                                                    
                                                </div>
                                            </div>
                                        </div>


                                </div>
                                <div class="tab-pane" id="progress-company-document">
                                    <div>

                                        <div>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="basicpill-pancard-input"
                                                            style="color: red">กลุ่มงาน
                                                            :</label>
                                                        <select id="dep" name="dep_id"
                                                            class="form-control department" style="width: 100%">
                                                            <option value=""></option>
                                                            @foreach ($department as $depart)
                                                                <option value="{{ $depart->DEPARTMENT_ID }}">
                                                                    {{ $depart->DEPARTMENT_NAME }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="basicpill-vatno-input"
                                                            style="color: red">ฝ่าย/แผนก
                                                            :</label><select id="depsub" name="dep_subid"
                                                            class="form-control department_sub" style="width: 100%">
                                                            <option value=""></option>
                                                            @foreach ($department_sub as $departsub)
                                                                <option value="{{ $departsub->DEPARTMENT_SUB_ID }}">
                                                                    {{ $departsub->DEPARTMENT_SUB_NAME }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="basicpill-cstno-input"
                                                            style="color: red">หน่วยงาน
                                                            :</label> <select id="depsubsub" name="dep_subsubid"
                                                            class="form-control department_sub_sub" style="width: 100%">
                                                            <option value=""></option>
                                                            @foreach ($department_sub_sub as $departsubsub)
                                                                <option value="{{ $departsubsub->DEPARTMENT_SUB_SUB_ID }}">
                                                                    {{ $departsubsub->DEPARTMENT_SUB_SUB_NAME }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="basicpill-servicetax-input"
                                                            style="color: red">หน่วยงานจริง :</label>
                                                        <select id="depsubsubtrue" name="dep_subsubtrueid"
                                                            class="form-control" style="width: 100%">
                                                            <option value=""></option>
                                                            @foreach ($department_sub_sub as $departsubsubtrue)
                                                                <option
                                                                    value="{{ $departsubsubtrue->DEPARTMENT_SUB_SUB_ID }}">
                                                                    {{ $departsubsubtrue->DEPARTMENT_SUB_SUB_NAME }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="basicpill-companyuin-input"
                                                            style="color: red">ตำแหน่ง
                                                            :</label>
                                                        <select id="position" name="position_id" style="width: 100%">
                                                            <option value=""></option>
                                                            @foreach ($position as $item)
                                                                <option value="{{ $item->POSITION_ID }}">
                                                                    {{ $item->POSITION_NAME }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="start_date"
                                                            style="color: red">วันที่บรรจุ
                                                            :</label>
                                                        <input id="start_date" type="date"
                                                            class="form-control form-control-sm datepicker"
                                                            name="start_date">
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="basicpill-servicetax-input">วันที่ลาออก :</label>
                                                        <input id="end_date" type="date"
                                                            class="form-control form-control-sm datepicker"
                                                            name="end_date">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="basicpill-companyuin-input"
                                                            style="color: red">สถานะทำงาน :</label>
                                                        <select id="statusA" name="status" class="form-control"
                                                            style="width: 100%">
                                                            <option value=""></option>
                                                            @foreach ($status as $st)
                                                                <option value="{{ $st->STATUS_ID }}">
                                                                    {{ $st->STATUS_NAME }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="basicpill-companyuin-input">เงินเดือน :</label>
                                                        <input id="money" type="text"
                                                            class="form-control form-control-sm" name="money">
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="start_date"
                                                            style="color: red">กลุ่มบุคลากร
                                                            :</label>
                                                        <select id="users_group_id" name="users_group_id"
                                                            class="form-control" style="width: 100%">
                                                            <option value=""></option>
                                                            @foreach ($users_group as $stu)
                                                                <option value="{{ $stu->users_group_id }}">
                                                                    {{ $stu->users_group_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="users_type_id"
                                                            style="color: red">ประเภทข้าราชการ
                                                            :</label>
                                                        <select id="users_type_id" name="users_type_id"
                                                            class="form-control" style="width: 100%">
                                                            <option value=""></option>
                                                            @foreach ($users_kind_type as $st)
                                                                <option value="{{ $st->users_kind_type_id }}">
                                                                    {{ $st->users_kind_type_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane" id="progress-bank-detail">
                                    <div>

                                        <img src="{{ asset('assets/images/default-image.jpg') }}" id="add_upload_preview"
                                            alt="Image" class="img-thumbnail" width="300px" height="300px">
                                        <br>
                                        <div class="input-group mb-3 mt-3">

                                            <input type="file" class="form-control" id="img" name="img"
                                                onchange="addpic(this)">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </div>


                                    </div>
                                </div>
                                <div class="tab-pane" id="progress-confirm-detail">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-6">
                                            <div class="text-center">
                                                <div class="mb-4">
                                                    <i class="mdi mdi-check-circle-outline text-success display-4"></i>
                                                </div>
                                                <div>
                                                    <h5>Confirm Detail</h5>
                                                    {{-- <button type="button" id="saveBtn" class="btn btn-primary btn-sm"> --}}
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fa-solid fa-floppy-disk me-2"></i>
                                                        บันทึกข้อมูล
                                                    </button>
                                                    <a href="{{ url('person/person_index') }}"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="fa-solid fa-xmark me-2"></i>
                                                        ยกเลิก
                                                    </a>
                                                    <p class="text-muted">ยืนยันการบันทึกข้อมูลบุคลากร</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <ul class="pager wizard twitter-bs-wizard-pager-link">
                                <li class="previous"><a href="javascript: void(0);">Previous</a></li>
                                <li class="next"><a href="javascript: void(0);">Next</a></li>
                            </ul>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>



@endsection
@section('footer')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#group_p4p').select2({
                placeholder:"--เลือก--",
                allowClear:true
            });
            $('#staff').select2({
                placeholder:"--เลือก--",
                allowClear:true
            });
            $('#saveBtn').click(function() {
                var pname = $('#pname').val();
                var fname = $('#fname').val();
                var lname = $('#lname').val();
                var username = $('#username').val();
                var password = $('#password').val();
                var cid = $('#cid').val();
                var dep_id = $('#dep_id').val();
                var dep_subid = $('#dep_subid').val();
                var dep_subsubid = $('#dep_subsubid').val();
                var dep_subsubtrueid = $('#dep_subsubtrueid').val();
                var start_date = $('#start_date').val();
                var status = $('#status').val();
                var end_date = $('#end_date').val();
                var users_group_id = $('#users_group_id').val();
                var users_type_id = $('#users_type_id').val();
                var img = $('#img').val();

                alert(pname);
                $.ajax({
                    url: "{{ route('person.person_save') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        pname,
                        fname,
                        lname,
                        username,
                        password,
                        cid,
                        dep_id,
                        dep_subid,
                        dep_subsubid,
                        dep_subsubtrueid,
                        start_date,
                        end_date,
                        users_group_id,
                        users_type_id,
                        img,
                        status
                    },
                    success: function(data) {
                        if (data.status == 200) {
                            // alert('gggggg');
                            Swal.fire({
                                title: 'บันทึกข้อมูลสำเร็จ',
                                text: "You Insert data success",
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
                                        "{{ route('person.person_index') }}"; //
                                    // window.location
                                    //     .reload();
                                }
                            })
                        } else {

                            Swal.fire({
                                title: 'username นี้มีผู้ใช้แล้ว',
                                text: "You data success",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#06D177',
                                confirmButtonText: 'เรียบร้อย'
                            }).then((result) => {
                                if (result
                                    .isConfirmed) {
                                    console.log(
                                        data);

                                }
                            })

                        }

                    },
                });
            });

        });

        $(document).ready(function() {
            $('#insert_personForm').on('submit', function(e) {
                e.preventDefault();
                //   alert('Person');
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
                            Swal.fire({
                                icon: 'error',
                                title: 'Username...!!',
                                text: 'Username นี้ได้ถูกใช้ไปแล้ว!',
                            }).then((result) => {
                                if (result.isConfirmed) {

                                }
                            })
                        } else {
                            Swal.fire({
                                title: 'บันทึกข้อมูลสำเร็จ',
                                text: "You Insert data success",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#06D177',
                                confirmButtonText: 'เรียบร้อย'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location =
                                        "{{ route('person.person_index') }}"; //
                                }
                            })
                        }
                    }
                });
            });
        });
    </script>

@endsection
