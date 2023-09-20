@extends('layouts.accountpk')
@section('title', 'PK-BACKOFFice || ACCOUNT-SET')

@section('content')
    <script>
        function TypeAdmin() {
            window.location.href = '{{ route('index') }}';
        }

        function acc_settingpang_destroy(acc_setpang_id) {
            Swal.fire({
                title: 'ต้องการลบใช่ไหม?',
                text: "ข้อมูลนี้จะถูกลบไปเลย !!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่, ลบเดี๋ยวนี้ !',
                cancelButtonText: 'ไม่, ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('acc_settingpang_destroy') }}" + '/' + acc_setpang_id,
                        type: 'POST',
                        data: {
                            _token: $("input[name=_token]").val()
                        },
                        success: function(response) {
                                if (response.status == 200) {
                                    Swal.fire({
                                    title: 'ลบข้อมูล!',
                                    text: "You Delet data success",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#06D177',
                                    // cancelButtonColor: '#d33',
                                    confirmButtonText: 'เรียบร้อย'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $("#sid" + acc_setpang_id).remove();
                                        window.location.reload();
                                        //   window.location = "/person/person_index"; //     
                                    }
                                })
                            } else {
                                
                            }
                           
                        }
                    })
                }
            })
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
    $ynow = date('Y') + 543;
    $yb = date('Y') + 542;
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
            border-top: 10px #fd6812 solid;
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
    <?php
    use App\Http\Controllers\StaticController;
    use Illuminate\Support\Facades\DB;
    $count_meettingroom = StaticController::count_meettingroom();
    ?>
    <div class="container-fluid">
        <div id="preloader">
            <div id="status">
                <div class="spinner">

                </div>
            </div>
        </div>
        
        <form action="{{ route('pk.book_inside_manage') }}" method="GET">
            @csrf
        <div class="row">  
            <div class="col-md-3">
                <h4 class="card-title">Detail ACCOUNT PANG</h4>
                <p class="card-title-desc">รายละเอียดั้งค่าผังบัญชี</p>
            </div>
            <div class="col"></div>
            {{-- <div class="col-md-1 text-end mt-2">วันที่</div>
            <div class="col-md-3 text-end">
                <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                    <input type="text" class="form-control" name="startdate" id="datepicker" placeholder="Start Date"
                        data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                        data-date-language="th-th" value="{{ $startdate }}" required/>
                    <input type="text" class="form-control" name="enddate" placeholder="End Date" id="datepicker2"
                        data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                        data-date-language="th-th" value="{{ $enddate }}" required/>  
                </div> 
            </div> --}}
            <div class="col-md-2 text-start">
                {{-- <button type="submit" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info">
                    <i class="fa-solid fa-magnifying-glass text-info me-2"></i>
                    ค้นหา
                </button>  --}}
                <button type="button" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i>
                    เพิ่มผังบัญชี
                </button>
            </div> 
        </div>

        <div class="row"> 
            <div class="col-xl-8 col-md-6">
                <div class="main-card card p-3">
                    <div class="grid-menu-col"> 
                        <table id="example" class="table table-striped table-bordered " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-center">ลำดับ</th> 
                                    <th class="text-center">รหัส</th>
                                    <th class="text-center">ชื่อผัง</th>
                                    <th class="text-center">pttype</th>
                                    <th class="text-center">icode</th>  
                                    <th class="text-center">hipdata_code</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $number = 0;
                                $total1 = 0; ?>
                                @foreach ($datashow as $item)
                                    <?php $number++; ?> 
                                    <tr height="20" id="#sid">
                                        <td class="text-center" width="5%">{{ $number }}</td> 
                                        <td class="text-center" width="10%" >
                                            <button type="button"class="btn-icon btn-shadow btn-dashed btn btn-outline-danger editModal" value="{{ $item->acc_setpang_id }}" data-bs-toggle="tooltip" data-bs-placement="left" title="แก้ไข">
                                               {{ $item->pang }}
                                            </button>
                                        </td> 
                                        <td class="p-2"> <a href="{{url('acc_settingpang_detail/'.$item->acc_setpang_id)}}" data-bs-toggle="tooltip" data-bs-placement="top" title="ข้อมูลที่กำหนด">{{ $item->pangname }}</a> </td> 
                                        <td class="text-center" width="11%"> 
                                            <button type="button" class="btn-icon btn-shadow btn-dashed btn btn-outline-success addpttypeModal" value="{{ $item->acc_setpang_id }}" data-bs-toggle="tooltip" data-bs-placement="left" title="เพิ่ม pttype">
                                                <i class="fa-solid fa-plus text-success"></i>
                                                pttype
                                            </button>
                                        </td>
                                        <td class="text-center" width="10%">
                                            <button type="button" class="btn-icon btn-shadow btn-dashed btn btn-outline-success addicodeModal" value="{{ $item->acc_setpang_id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="เพิ่ม icode">
                                                <i class="fa-solid fa-plus text-success"></i>
                                                icode
                                            </button>
                                        </td> 
                                        <td class="text-center" width="15%">
                                            <button type="button" class="btn-icon btn-shadow btn-dashed btn btn-outline-success addhipdata_codeModal" value="{{ $item->acc_setpang_id }}" data-bs-toggle="tooltip" data-bs-placement="right" title="เพิ่ม icode">
                                                <i class="fa-solid fa-plus text-success"></i>
                                                hipdata_code
                                            </button>
                                        </td> 
                                        {{-- <td class="p-2" width="30%" > {{ $item->pttype }}</td>  --}}
                                        {{-- <td class="text-center" width="30%"> {{ $item->icode }}</td>  --}}
                                        {{-- <td class="text-center" width="5%">  
                                            <div class="dropdown">
                                                <button class="btn btn-outline-primary dropdown-toggle menu btn-sm"
                                                    type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">ทำรายการ</button>
                                                <ul class="dropdown-menu">
                                                    <button type="button"class="dropdown-item menu editModal" value="{{ $item->acc_setpang_id }}" data-bs-toggle="tooltip" data-bs-placement="left" title="แก้ไข">
                                                        <i class="fa-solid fa-pen-to-square ms-2 me-2 text-warning"></i>
                                                        <label for="" style="font-size:12px;color: rgb(255, 185, 34)">แก้ไข</label>
                                                    </button> 
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                        <a class="dropdown-item menu text-danger" href="javascript:void(0)"
                                                            onclick="book_inside_manage_destroy({{ $item->acc_setpang_id }})"
                                                            data-bs-toggle="tooltip" data-bs-placement="left"
                                                            data-bs-custom-class="custom-tooltip" title="ลบ">
                                                            <i class="fa-solid fa-trash-can ms-2 me-2 mb-1"></i>
                                                            <label for=""
                                                                style="color: rgb(255, 2, 2);font-size:13px">ลบ</label>
                                                        </a>
                                                </ul>
                                              </div>
                                        </td> --}}
                                    </tr> 
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
            <div class="col-xl-4 col-md-6">
                <div class="main-card card p-3">
                    222
                </div>
            </div>
        </div>
         
    </div>

    <!-- Insert Modal -->
    <div class="modal fade" id="exampleModal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ตั้งค่าผังบัญชี</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <label for="pang" class="form-label">รหัสผังบัญชี</label>
                            <div class="input-group input-group-sm"> 
                                <input type="text" class="form-control" id="pang" name="pang">  
                            </div>
                        </div>  
                    </div>
 
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label for="pangname" class="form-label">ชื่อผังบัญชี</label>
                            <div class="input-group input-group-sm"> 
                                <input type="text" class="form-control" id="pangname" name="pangname">  
                            </div>
                        </div>  
                    </div> 
                    {{-- <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="pttype" class="form-label">สิทธิ์การรักษา</label>
                            <div class="input-group input-group-sm"> 
                                <input type="text" class="form-control" id="pttype" name="pttype">  
                            </div>
                        </div>  
                        <div class="col-md-6">
                            <label for="icode" class="form-label">icode</label>
                            <div class="input-group input-group-sm"> 
                                <input type="text" class="form-control" id="icode" name="icode">  
                            </div>
                        </div> 
                    </div> --}}
                    
                    <input type="hidden" name="user_id" id="user_id" value="{{$iduser}}"> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info" id="Savedata">
                        <i class="pe-7s-diskette btn-icon-wrapper"></i>Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="editModal"  tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">แก้ไขผังบัญชี</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body"> 

                        <div class="row">
                            <div class="col-md-12">
                                <label for="pang" class="form-label">รหัสผังบัญชี</label>
                                <div class="input-group input-group-sm"> 
                                    <input type="text" class="form-control" id="editpang" name="pang">  
                                </div>
                            </div>  
                        </div>
     
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="pangname" class="form-label">ชื่อผังบัญชี</label>
                                <div class="input-group input-group-sm"> 
                                    <input type="text" class="form-control" id="editpangname" name="pangname">  
                                </div>
                            </div>  
                        </div> 

                        {{-- <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="pttype" class="form-label">สิทธิ์การรักษา</label>
                                <div class="input-group input-group-sm"> 
                                    <input type="text" class="form-control" id="editpttype" name="pttype">  
                                </div>
                            </div>  
                            <div class="col-md-6">
                                <label for="icode" class="form-label">icode</label>
                                <div class="input-group input-group-sm"> 
                                    <input type="text" class="form-control" id="editicode" name="icode">  
                                </div>
                            </div> 
                        </div>  --}}
                    
                    <input type="hidden" name="user_id" id="edituser_id"> 
                    <input type="hidden" name="editacc_setpang_id" id="editacc_setpang_id"> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info" id="Updatedata">
                        <i class="pe-7s-diskette btn-icon-wrapper"></i>Update changes
                    </button>
                </div>
            </div>
        </div>
    </div>

     <!-- Update Modal -->
     <div class="modal fade" id="addpttypeModal"  tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">เพิ่มสิทธิ์การรักษา</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body"> 

                        <div class="row">
                            <div class="col-md-4">
                                <label for="pang" class="form-label">รหัสผังบัญชี</label>
                                <div class="input-group input-group-sm"> 
                                    <input type="text" class="form-control" id="addtypepang" name="pang" readonly>  
                                </div>
                            </div>  
                            <div class="col-md-8">
                                <label for="pangname" class="form-label">ชื่อผังบัญชี</label>
                                <div class="input-group input-group-sm"> 
                                    <input type="text" class="form-control" id="addtypepangname" name="pangname" readonly>  
                                </div>
                            </div> 
                        </div>
      

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label for="pttype" class="form-label">เพิ่มสิทธิ์การรักษา</label>
                                <div class="input-group input-group-sm">  
                                    <select name="addpttype" id="addpttype" class="form-control" style="width: 100%">
                                        <option value="">-Choose-</option>
                                        @foreach ($data_sit as $item1)
                                        <option value="{{$item1->pttype}}">{{$item1->pttype}} {{$item1->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            
                        </div> 
                    
                    <input type="hidden" name="user_id" id="adduser_id"> 
                    <input type="hidden" name="addtypeacc_setpang_id" id="addtypeacc_setpang_id"> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info" id="Updatetype">
                        <i class="pe-7s-diskette btn-icon-wrapper"></i>Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>
 

@endsection
@section('footer')
<script src="{{ asset('pdfupload/pdf_up.js') }}"></script> 
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="{{ asset('js/gcpdfviewer.js') }}"></script> 
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

        $('#datepicker3').datepicker({
            format: 'yyyy-mm-dd'
        }); 
        $('#addpttype').select2({
            dropdownParent: $('#addpttypeModal')
        });

        // $('#editacc_stm_repmoney_tri').select2({
        //     dropdownParent: $('#editModal')
        // });

        $('#Savedata').click(function() {
                var pang = $('#pang').val();
                var pangname = $('#pangname').val(); 
                var pttype = $('#pttype').val();
                var icode = $('#icode').val(); 

                $.ajax({
                    url: "{{ route('acc.acc_settingpang_save') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        pang,pangname,pttype,icode 
                    },
                    success: function(data) {
                        if (data.status == 200) {
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
                                    window.location.reload();
                                    // window.location="{{ url('warehouse/warehouse_index') }}";
                                }
                            })
                        } else {

                        }

                    },
                });
        }); 
        $('#Updatedata').click(function() {
                var pang = $('#editpang').val();
                var pangname = $('#editpangname').val(); 
                var pttype = $('#editpttype').val();
                var icode = $('#editicode').val(); 
                var acc_setpang_id = $('#editacc_setpang_id').val();
                $.ajax({
                    url: "{{ route('acc.acc_settingpang_update') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        pang,pangname,pttype,icode,acc_setpang_id
                    },
                    success: function(data) {
                        if (data.status == 200) {
                            Swal.fire({
                                title: 'แก้ไขข้อมูลสำเร็จ',
                                text: "You Update data success",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#06D177',
                                confirmButtonText: 'เรียบร้อย'
                            }).then((result) => {
                                if (result
                                    .isConfirmed) {
                                    console.log(
                                        data);
                                    window.location.reload(); 
                                }
                            })
                        } else {

                        }

                    },
                });
        }); 
        $('#Updatetype').click(function() { 
                var addtypepang = $('#addtypepang').val(); 
                var addpttype = $('#addpttype').val(); 
                var acc_setpang_id = $('#addtypeacc_setpang_id').val();
                $.ajax({
                    url: "{{ route('acc.acc_pang_addtypesave') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        addpttype,acc_setpang_id,addtypepang
                    },
                    success: function(data) {
                        if (data.status == 200) {
                            Swal.fire({
                                title: 'เพิ่มข้อมูลสำเร็จ',
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
                                    window.location.reload(); 
                                }
                            })
                        } else {

                        }

                    },
                });
        });
    });

    $(document).on('click', '.editModal', function() {
        var acc_setpang_id = $(this).val(); 
        $('#editModal').modal('show');
        $.ajax({
            type: "GET",
            url: "{{ url('acc_settingpang_edit') }}" + '/' + acc_setpang_id,
            success: function(data) {
                console.log(data.data_show.acc_setpang_id);
                $('#editpang').val(data.data_show.pang)
                $('#editpangname').val(data.data_show.pangname)
                $('#editpttype').val(data.data_show.pttype)
                $('#editicode').val(data.data_show.icode)  
                $('#editacc_setpang_id').val(data.data_show.acc_setpang_id)
            },
        });
    });

    $(document).on('click', '.addpttypeModal', function() {
        var acc_setpang_id = $(this).val(); 
        $('#addpttypeModal').modal('show');
        $.ajax({
            type: "GET",
            url: "{{ url('acc_pang_addtype') }}" + '/' + acc_setpang_id,
            success: function(data) {
                console.log(data.data_type.acc_setpang_id); 
                $('#addtypepang').val(data.data_type.pang)
                $('#addtypepangname').val(data.data_type.pangname)
                $('#addpttype').val(data.data_type.pttype) 
                $('#addtypeacc_setpang_id').val(data.data_type.acc_setpang_id)
            },
        });
    });

    // $('#SaveFileModal').on('submit', function(e) {
    //     e.preventDefault();
    //     var form = this;
    //     // alert('OJJJJOL');
    //     $.ajax({
    //         url: $(form).attr('action'),
    //         method: $(form).attr('method'),
    //         data: new FormData(form),
    //         processData: false,
    //         dataType: 'json',
    //         contentType: false,
    //         beforeSend: function() {
    //             $(form).find('span.error-text').text('');
    //         },
    //         success: function(data) {
    //             if (data.status == 200) {
    //                 Swal.fire({
    //                     title: 'Up File สำเร็จ',
    //                     text: "You Up File data success",
    //                     icon: 'success',
    //                     showCancelButton: false,
    //                     confirmButtonColor: '#06D177',
    //                     // cancelButtonColor: '#d33',
    //                     confirmButtonText: 'เรียบร้อย'
    //                 }).then((result) => {
    //                     if (result.isConfirmed) {
    //                         window.location.reload();
    //                     }
    //                 })

    //             } else {
                    
    //             }
    //         }
    //     });
    // });
</script> 
@endsection
