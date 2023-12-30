@extends('layouts.userdashboard')
@section('title', 'PK-BACKOFFice || แจ้งซ่อมคอมพิวเตอร์')
@section('content')
    <script>
        function TypeAdmin() {
            window.location.href = '{{ route('index') }}';
        }

        function repair_com_cancel(com_repaire_id) {
            // alert(bookrep_id);
            Swal.fire({
                title: 'ต้องการยกเลิกรายการนี้ใช่ไหม?',
                text: "ข้อมูลนี้จะถูกส่งไปยังผู้ดูแลงานคอมพิวเตอร์",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่ ',
                cancelButtonText: 'ไม่'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{ url('user_com/repair_com_cancel') }}" + '/' + com_repaire_id,
                        success: function(response) {
                            Swal.fire({
                                title: 'แจ้งยกเลิกรายการนี้',
                                text: "Cancel Finish",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#06D177',
                                // cancelButtonColor: '#d33',
                                confirmButtonText: 'เรียบร้อย'
                            }).then((result) => {
                                if (result.isConfirmed) {

                                    window.location.reload();

                                }
                            })
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
    use App\Http\Controllers\UsercarController;
    use App\Http\Controllers\StaticController;
    use App\Models\Products_request_sub;
    
    $refnumber = UsercarController::refnumber();
    $checkhn = StaticController::checkhn($iduser);
    $checkhnshow = StaticController::checkhnshow($iduser);
    $count_suprephn = StaticController::count_suprephn($iduser);
    $count_bookrep_po = StaticController::count_bookrep_po();
    ?>
    <style>
        @media (min-width: 950px) {
            .modal {
                --bs-modal-width: 950px;
            }
        }

        @media (min-width: 1500px) {
            .modal-xls {
                --bs-modal-width: 1500px;
            }
        }

        @media (min-width: 1500px) {
            .container-fluids {
                width: 1500px;
                margin-left: auto;
                margin-right: auto;
                margin-top: auto;
            }
        }

        .hrow {
            height: 2px;
            margin-bottom: 9px;
        }
    </style>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="row invoice-card-row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-header ">
                            <div class="d-flex">
                                <div class="p-2">
                                    <label for="">ข้อมูลการแจ้งซ่อมคอมพิวเตอร์</label>
                                </div>
                                <div class="ms-auto p-2">
                                    {{-- <a href="{{ url('warehouse/warehouse_add') }}" class="btn btn-info btn-sm">
                                            <i class="fa-solid fa-folder-plus text-white me-2"></i>
                                            ตรวจรับทั่วไป
                                        </a> --}}
                                </div>
                            </div>
                        </div>

                        <div class="card-body ">
                            <div class="table-responsive">
                                {{-- <table class="table table-hover table-bordered table-sm myTable " style="width: 100%;" id="example">  --}}
                                <table style="width: 100%;" id="example"
                                    class="table table-hover table-striped table-bordered">
                                    <thead>
                                        <tr height="10px">
                                            <th width="7%">ลำดับ</th>
                                            <th width="10%">ความเร่งด่วน</th>
                                            <th width="10%">สถานะ</th>
                                            <th width="10%">วันที่แจ้ง</th>
                                            <th>รายละเอียด</th>
                                            <th width="15%">ผู้แจ้งซ่อม</th>
                                            <th width="10%">วันที่ซ่อม</th>
                                            <th width="15%">ช่างซ่อม</th>
                                            <th width="10%">จัดการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($com_repaire as $item)
                                            <tr id="sid{{ $item->com_repaire_id }}" height="30">
                                                <td class="text-center" width="3%">{{ $i++ }}</td>

                                                @if ($item->com_repaire_speed == '1')
                                                    <td class="text-center" width="10%">
                                                        <div class="badge bg-info" style="font-size:12px">ปกติ</div>
                                                    </td>
                                                @elseif ($item->com_repaire_speed == '2')
                                                    <td class="text-center" width="10%">
                                                        <div class="badge bg-warning" style="font-size:12px">ด่วน</div>
                                                    </td>
                                                @elseif ($item->com_repaire_speed == '3')
                                                    <td class="text-center" width="10%">
                                                        <div class="badge" style="background: rgb(7, 215, 158)"
                                                            style="font-size:12px">ด่วนมาก</div>
                                                    </td>
                                                @else
                                                    <td class="text-center" width="10%">
                                                        <div class="badge bg-danger" style="font-size:12px">ด่วนที่สุด</div>
                                                    </td>
                                                @endif

                                                @if ($item->com_repaire_status == 'notifyrepair')
                                                    <td class="text-center" width="7%">
                                                        <div class="badge bg-warning" style="font-size:12px">แจ้งซ่อม</div>
                                                    </td>
                                                @elseif ($item->com_repaire_status == 'carry_out')
                                                    <td class="text-center" width="7%">
                                                        <div class="badge" style="background-color: #592DF7"
                                                            style="font-size:12px">ดำเนินการ</div>
                                                    </td>
                                                @elseif ($item->com_repaire_status == 'waiting')
                                                    <td class="text-center" width="7%">
                                                        <div class="badge" style="background: rgb(7, 215, 158)"
                                                            style="font-size:12px">รออะไหล่</div>
                                                    </td>
                                                @elseif ($item->com_repaire_status == 'sendout')
                                                    <td class="text-center" width="7%">
                                                        <div class="badge" style="background: rgb(232,13,239)"
                                                            style="font-size:12px">ส่งซ่อมนอก</div>
                                                    </td>
                                                @elseif ($item->com_repaire_status == 'cancel')
                                                    <td class="text-center" width="7%">
                                                        <div class="badge bg-danger" style="font-size:12px">แจ้งยกเลิก</div>
                                                    </td>
                                                @elseif ($item->com_repaire_status == 'confirmcancel')
                                                    <td class="text-center" width="7%">
                                                        <div class="badge " style="background: rgb(141, 140, 138)"
                                                            style="font-size:12px">ยกเลิก</div>
                                                    </td>
                                                @else
                                                    <td class="text-center" width="7%">
                                                        <div class="badge bg-success" style="font-size:12px">ซ่อมเสร็จ</div>
                                                    </td>
                                                @endif

                                                <td class="p-2" width="10%" style="font-size:13px">
                                                    {{ dateThai($item->com_repaire_date) }} </td>
                                                <td class="p-2" style="font-size:13px">{{ $item->com_repaire_detail }}
                                                </td>
                                                <td class="p-2" width="15%" style="font-size:13px">
                                                    {{ $item->com_repaire_user_name }}</td>
                                                <td class="p-2" width="10%" style="font-size:13px">
                                                    {{ dateThai($item->com_repaire_work_date) }} </td>
                                                <td class="p-2" width="15%" style="font-size:13px">
                                                    {{ $item->com_repaire_tec_name }}</td>
                                                <td class="text-center" width="10%">
                                                    <div class="dropdown"> 
                                                        <div class="dropdown d-inline-block">
                                                            <button type="button" aria-haspopup="true"
                                                                aria-expanded="false" data-bs-toggle="dropdown"
                                                                class="mb-2 me-2 dropdown-toggle btn btn-outline-secondary btn-sm"
                                                                style="font-size:13px">
                                                                ทำรายการ
                                                            </button>
                                                            <div tabindex="-1" role="menu" aria-hidden="true"
                                                                class="dropdown-menu-hover-link dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item menu" data-bs-toggle="modal"
                                                                        data-bs-target="#comdetailModal{{ $item->com_repaire_id }}"
                                                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                                                        title="รายละเอียด">
                                                                        <i
                                                                            class="fa-solid fa-file-pen mt-2 ms-2 mb-2 me-2 text-info"></i>
                                                                        <label for=""
                                                                            style="color: rgb(33, 187, 248)"
                                                                            style="font-size:13px">รายละเอียด</label>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item menu"
                                                                        href="{{ url('user_com/repair_com_edit/' . $item->com_repaire_id) }}"
                                                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                                                        title="แก้ไข">
                                                                        <i
                                                                            class="fa-solid fa-edit mt-2 ms-2 mb-2 me-2 text-warning"></i>
                                                                        <label for=""
                                                                            style="color: rgb(252, 127, 24)"
                                                                            style="font-size:13px">แก้ไข</label>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item menu"
                                                                        href="{{ url('computer/com_staff_print/' . $item->com_repaire_id) }}"
                                                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                                                        title="Print" target="_blank">
                                                                        <i
                                                                            class="fa-solid fa-print mt-2 ms-2 mb-2 me-2 text-primary"></i>
                                                                        <label for=""
                                                                            style="color: rgb(24, 115, 252)"
                                                                            style="font-size:13px">Print</label>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item menu"
                                                                        href="javascript:void(0)"
                                                                        onclick="repair_com_cancel({{ $item->com_repaire_id }})"
                                                                        data-bs-toggle="tooltip" data-bs-placement="left"
                                                                        title="แจ้งยกเลิก">

                                                                        <i
                                                                            class="fa-solid fa-xmark me-2 mt-2 ms-2 mb-2 text-danger"></i>
                                                                        <label for=""
                                                                            style="color: rgb(255, 22, 22)"
                                                                            style="font-size:13px">แจ้งยกเลิก</label>
                                                                    </a>
                                                                </li>
                                                                </ul>
                                                            </div>
                                                </td>
                                            </tr>

                                            <div class="modal fade"
                                                        id="comdetailModal{{ $item->com_repaire_id }}" tabindex="-1"
                                                        aria-labelledby="comdetailModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xls">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="comdetailModalLabel">
                                                                        รายละเอียดแจ้งซ่อมคอมพิวเตอร์
                                                                        {{ $item->car_service_register }}</h5>

                                                                </div>
                                                                <div class="modal-body">

                                                                    <div class="row">
                                                                        <div class="col-md-2 ">
                                                                            <label for=""><b>รหัสแจ้งซ่อม
                                                                                    :</b></label>
                                                                        </div>
                                                                        <div class="col-md-4">

                                                                            <label
                                                                                for="com_repaire_no">{{ $item->com_repaire_no }}</label>

                                                                        </div>
                                                                        <div class="col-md-2 ">
                                                                            <label for=""><b>ปีงบประมาณ
                                                                                    :</b></label>
                                                                        </div>
                                                                        <div class="col-md-3">

                                                                            <label
                                                                                for="com_repaire_year">{{ $item->com_repaire_year }}</label>

                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-3">
                                                                        <div class="col-md-2">
                                                                            <label for=""><b>วันที่แจ้ง
                                                                                    :</b></label>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="com_repaire_date">{{ DateThai($item->com_repaire_date) }}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2 ">
                                                                            <label for=""><b>เวลา :</b></label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="com_repaire_time">{{ formatetime($item->com_repaire_time) }}</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-3">
                                                                        <div class="col-md-2">
                                                                            <label for=""><b>ผู้แจ้ง :</b></label>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="com_repaire_user_name">{{ $item->com_repaire_user_name }}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label for=""><b>หน่วยงาน :</b></label>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="com_repaire_debsubsub_name">{{ $item->com_repaire_debsubsub_name }}</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-3">
                                                                        <div class="col-md-2">
                                                                            <label for=""><b>ความเร่งด่วน
                                                                                    :</b></label>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="com_repaire_user_name">{{ $item->status_name }}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label for=""><b>รายการที่แจ้งซ่อม
                                                                                    :</b></label>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="com_repaire_user_name">{{ $item->article_name }}</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-3">
                                                                        <div class="col-md-2">
                                                                            <label for=""><b>รายละเอียดแจ้งซ่อม
                                                                                    :</b></label>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="com_repaire_detail">{{ $item->com_repaire_detail }}</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <hr>

                                                                    <div class="row mt-3">
                                                                        <div class="col-md-2">
                                                                            <label for=""><b>วันที่ซ่อม
                                                                                    :</b></label>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="com_repaire_date">{{ DateThai($item->com_repaire_work_date) }}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2 ">
                                                                            <label for=""><b>เวลา :</b></label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="com_repaire_time">{{ formatetime($item->com_repaire_work_time) }}</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-3">
                                                                        <div class="col-md-2">
                                                                            <label for=""><b>ช่างซ่อม :</b></label>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="com_repaire_user_name">{{ $item->com_repaire_tec_name }}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label for=""><b>รายละเอียดซ่อม
                                                                                    :</b></label>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="com_repaire_debsubsub_name">{{ $item->com_repaire_detail_tech }}</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <hr>

                                                                    <div class="row mt-3">
                                                                        <div class="col-md-2">
                                                                            <label
                                                                                for=""><b>วันที่ส่งงาน:</b></label>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="com_repaire_date">{{ DateThai($item->com_repaire_send_date) }}</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2 ">
                                                                            <label for=""><b>เวลา :</b></label>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="com_repaire_time">{{ formatetime($item->com_repaire_send_time) }}</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-3">
                                                                        <div class="col-md-2">
                                                                            <label for=""><b>ผู้รับงาน
                                                                                    :</b></label>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label
                                                                                    for="com_repaire_rep_name">{{ $item->com_repaire_rep_name }}</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <hr>

                                                                    <?php $ii = 1;
                                                                          $sing = DB::table('com_repaire_signature')->where('com_repaire_id','=',$item->com_repaire_id)->first();
                                                                          $sing1 = $sing->signature_name_usertext;
                                                                          $sing2 = $sing->signature_name_stafftext;
                                                                          $sing3 = $sing->signature_name_reptext;
                                                                          if ($sing1 != '') {
                                                                            $singuser = base64_encode(file_get_contents($sing1));
                                                                          } else {
                                                                            $singuser = '';
                                                                          }
                                                                          if ($sing2 != '') {
                                                                            $singstaff = base64_encode(file_get_contents($sing2));
                                                                          } else {
                                                                            $singstaff = '';
                                                                          }
                                                                          if ($sing3 != '') {
                                                                            $singrep = base64_encode(file_get_contents($sing3));
                                                                          } else {
                                                                            $singrep = '';
                                                                          }                                                                            
                                                                    ?>

                                                                    <div class="row mt-3">
                                                                      <div class="col-md-2">
                                                                          <label
                                                                              for=""><b>ลายมือผู้แจ้ง:</b></label>
                                                                      </div>
                                                                      <div class="col-md-1 text-start">
                                                                          <div class="form-group ">
                                                                            <img src="data:image/png;base64,{{$singuser}}" alt="" width="120px">
                                                                          </div>
                                                                      </div>
                                                                      <div class="col"></div>
                                                                      <div class="col-md-2 ">
                                                                          <label for=""><b>ลายมือผู้รับ:</b></label>
                                                                      </div>
                                                                      <div class="col-md-1 text-start">
                                                                          <div class="form-group ">
                                                                            <img src="data:image/png;base64,{{$singrep}}" alt="" width="120px">
                                                                          </div>
                                                                      </div>
                                                                      <div class="col"></div>

                                                                      <div class="col-md-2 ">
                                                                        <label for=""><b>ลายมือผู้ส่งงาน:</b></label>
                                                                    </div>
                                                                    <div class="col-md-1 text-start">
                                                                        <div class="form-group ">
                                                                          <img src="data:image/png;base64,{{$singstaff}}" alt="" width="120px">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col"></div>
                                                                  </div>



                                                                </div>
                                                                <div class="modal-footer">

                                                                    <button type="button" class="btn btn-danger btn-sm"
                                                                        data-bs-dismiss="modal" id="closebtn">
                                                                        <i class="fa-solid fa-xmark me-2"></i>
                                                                        ปิด
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

  @endsection
@section('footer')

@endsection
