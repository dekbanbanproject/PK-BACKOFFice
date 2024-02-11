@extends('layouts.accountnew')
@section('title', 'PK-BACKOFFice || Account')
@section('content')
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
            border-top: 10px #1fdab1 solid;
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

        .modal-dialog {
            max-width: 60%;
        }

        .modal-dialog-slideout {
            min-height: 100%;
            margin:auto 0 0 0 ;   /*  ซ้าย ขวา */
            background: #fff;
        }

        .modal.fade .modal-dialog.modal-dialog-slideout {
            -webkit-transform: translate(100%, 0)scale(30);
            transform: translate(100%, 0)scale(5);
        }

        .modal.fade.show .modal-dialog.modal-dialog-slideout {
            -webkit-transform: translate(0, 0);
            transform: translate(0, 0);
            display: flex;
            align-items: stretch;
            -webkit-box-align: stretch;
            height: 100%;
        }

        .modal.fade.show .modal-dialog.modal-dialog-slideout .modal-body {
            overflow-y: auto;
            overflow-x: hidden;

            /* overflow-y: hidden;
            overflow-x: auto; */
        }

        .modal-dialog-slideout .modal-content {
            border: 0;
        }

        .modal-dialog-slideout .modal-header,
        .modal-dialog-slideout .modal-footer {
            height: 4rem;
            display: block;
        }

        .datepicker {
            z-index: 2051 !important;
        }
        #request{
                    width: 40px;
                    height: 40px;
                    background-color: rgb(248, 209, 163);
                    border-radius: 100%;
                    margin: 0% auto;
                    -webkit-animation: pulse 3s infinite ease-in-out;
                    -o-animation: pulse 3s infinite ease-in-out;
                    -ms-animation: pulse 3s infinite ease-in-out;
                    -moz-animation: pulse 3s infinite ease-in-out;
                    animation: pulse 3s infinite ease-in-out;
            }
            #accept{
                    width: 40px;
                    height: 40px;
                    background-color: rgb(200, 233, 248);
                    border-radius: 100%;
                    margin: 0% auto;
                    -webkit-animation: pulse 3s infinite ease-in-out;
                    -o-animation: pulse 3s infinite ease-in-out;
                    -ms-animation: pulse 3s infinite ease-in-out;
                    -moz-animation: pulse 3s infinite ease-in-out;
                    animation: pulse 3s infinite ease-in-out;
            }
            #acceptpo{
                    width: 40px;
                    height: 40px;
                    background-color: rgb(209, 200, 248);
                    border-radius: 100%;
                    margin: 0% auto;
                    -webkit-animation: pulse 3s infinite ease-in-out;
                    -o-animation: pulse 3s infinite ease-in-out;
                    -ms-animation: pulse 3s infinite ease-in-out;
                    -moz-animation: pulse 3s infinite ease-in-out;
                    animation: pulse 3s infinite ease-in-out;
            }
            #finish{
                    width: 40px;
                    height: 40px;
                    background-color: rgb(194, 250, 219);
                    border-radius: 100%;
                    margin: 0% auto;
                    -webkit-animation: pulse 3s infinite ease-in-out;
                    -o-animation: pulse 3s infinite ease-in-out;
                    -ms-animation: pulse 3s infinite ease-in-out;
                    -moz-animation: pulse 3s infinite ease-in-out;
                    animation: pulse 3s infinite ease-in-out;
            }

    </style>
    <script>
        function TypeAdmin() {
            window.location.href = '{{ route('index') }}';
        }
        
    </script>
    <?php
    if (Auth::check()) {
        $type = Auth::user()->type;
        $iduser = Auth::user()->id;
        $iddep = Auth::user()->dep_subsubtrueid;
    } else {
        echo "<body onload=\"TypeAdmin()\"></body>";
        exit();
    }
    $url = Request::url();
    $pos = strrpos($url, '/') + 1;
 
    ?>
    <div class="tabs-animation">
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
       
        <div class="row">
            <div class="col-xl-12">
                <form action="{{ route('acc.account_plane') }}" method="GET">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="card-title">Detail Project plan control register</h5>
                            <p class="card-title-desc">ทะเบียนควบคุมแผนงานโครงการ</p>
                        </div>
                        {{-- <div class="col"></div> --}}
                        <div class="col-md-3">
                            <select name="departmentsub" id="departmentsub" class="form-control inputmedsalt" style="width: 100%" required>
                                <option value="">--กลุ่มงาน--</option>
                                @foreach ($department_sub as $item_s)
                                @if ($departmentsub == $item_s->DEPARTMENT_SUB_ID )
                                    <option value="{{$item_s->DEPARTMENT_SUB_ID}}" selected>{{$item_s->DEPARTMENT_SUB_NAME}}</option>
                                @else
                                    <option value="{{$item_s->DEPARTMENT_SUB_ID}}">{{$item_s->DEPARTMENT_SUB_NAME}}</option>
                                @endif
                                   
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 text-end mt-2">วันที่</div>
                        <div class="col-md-4 text-end">
                            <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                                <input type="text" class="form-control inputmedsalt" name="startdate" id="startdate" placeholder="Start Date"
                                    data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                                    data-date-language="th-th" value="{{ $startdate }}" required/>
                                <input type="text" class="form-control inputmedsalt" name="enddate" placeholder="End Date" id="enddate"
                                    data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                                    data-date-language="th-th" value="{{ $enddate }}" required/>
                                <button type="submit" class="ladda-button me-2 btn-pill btn btn-primary inputmedsalt" data-style="expand-left" id="Pulldata">
                                    <span class="ladda-label"><i class="pe-7s-search btn-icon-wrapper me-2"></i>ค้นหา</span>
                                    <span class="ladda-spinner"></span>
                                </button> 
                          
                        </div>
                    </div>
                        
                </form>
            
       
        </div>
       
        <div class="row">
            <div class="col-xl-12">
                <div class="card cardfinan">
                    <div class="card-body"> 

                        <div class="row mb-3"> 
                            <div class="col-md-7 text-start"> 
                                <button type="button" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-secondary" style="background-color: rgb(248, 209, 163);border-radius: 3em 3em 3em 3em"> 
                                    ยังไม่ดำเนินการ
                                </button>
                                <button type="button" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-secondary" style="background-color: rgb(200, 233, 248);border-radius: 3em 3em 3em 3em"> 
                                    รอ สสจ.อนุมัติ
                                </button>
                                <button type="button" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-secondary" style="background-color: rgb(209, 200, 248);border-radius: 3em 3em 3em 3em"> 
                                    รอ ผอ. อนุมัติ
                                </button>
                                <button type="button" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-secondary" style="background-color: rgb(194, 250, 219);border-radius: 3em 3em 3em 3em"> 
                                    อนุมัติ
                                </button>
                            </div>
                            <div class="col"></div>
                            <div class="col-md-2 text-end"> 
                                {{-- <a href="{{ url('plan_control_subhos_add/'.$id) }}" class="ladda-button me-2 btn-pill btn btn-primary cardacc" target="_blank">
                                    <i class="fa-solid fa-folder-plus me-2"></i>
                                    เพิ่มทะเบียน
                                </a>  --}}
                            </div>
                        </div>

                        <table id="example" class="table table-striped table-bordered myTable" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr style="font-size: 13px">
                                    <th width="5%" class="text-center">ลำดับ</th>
                                    <th class="text-center">สถานะ</th> 
                                    <th class="text-center"> แผนงาน/โครงการ</th> 
                                    <th class="text-center">งบประมาณ</th> 
                                    <th class="text-center">เบิก</th> 
                                    <th class="text-center">คงเหลือ</th> 
                                    <th width="10%" class="text-center">จัดการ</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($plan_control as $va)
                                
                                    <tr id="sid{{ $va->plan_control_id }}">
                                        <td class="text-center" width="4%">{{ $i++ }}</td>
                                        <td width="5%">
                                            @if ($va->status == 'REQUEST')
                                                <div id="request"> 
                                                    <span class="badge badge badge-secondary"></span>
                                                </div>
                                            {{-- @elseif ($va->status == 'ACCEPT')
                                                <div id="accept"> 
                                                    <span class="badge badge badge-secondary"></span>
                                                </div> --}}
                                            @elseif ($va->status == 'INPROGRESS_SSJ')
                                                <div id="accept"> 
                                                    <span class="badge badge badge-secondary"></span>
                                                </div>
                                            @elseif ($va->status == 'INPROGRESS_PO')
                                                <div id="acceptpo"> 
                                                    <span class="badge badge badge-secondary"></span>
                                                </div>
                                            @elseif ($va->status == 'FINISH')
                                                <div id="finish"> 
                                                    <span class="badge badge badge-secondary"></span>
                                                </div>
                                            @else
                                                
                                            @endif
                                            
                                        </td>
                                        <td class="text-start" >   
                                            <?php 
                                                $data_sub_ = DB::connection('mysql')->select('SELECT * from plan_control_kpi WHERE plan_control_id = "'.$va->plan_control_id.'"'); 
                                                $data_subobj_ = DB::connection('mysql')->select('SELECT * from plan_control_obj WHERE plan_control_id = "'.$va->plan_control_id.'"'); 
                                                $data_sumprice_ = DB::connection('mysql')->select('SELECT sum(budget_price) as budget_price from plan_control_activity WHERE plan_control_id = "'.$va->plan_control_id.'"'); 
                                                foreach ($data_sumprice_ as $key => $value_price) {
                                                    $plan_price = $value_price->budget_price;
                                                }
                                            ?>  
                                            <div id="headingTwo" class="b-radius-0">                                                         
                                                    <button type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseOne2{{ $va->plan_control_id }}" aria-expanded="false"
                                                        aria-controls="collapseTwo" class="text-start m-0 p-0 btn btn-link btn-block">
                                                        <h6 style="color: rgb(66, 63, 63)">{{ $va->plan_name }}</h6> 
                                                    </button>  
                                            </div>                                                     
                                            {{-- <div id="headingTwo" class="b-radius-0">                                                         
                                                    <button type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseOne2{{ $va->plan_control_id }}" aria-expanded="false"
                                                        aria-controls="collapseTwo" class="text-start m-0 p-0 btn btn-link btn-block">
                                                        <h6 style="color: rgb(66, 63, 63)">{{ $va->plan_name }} ||<label for="" style="color: red">_KPI_ </label>||</h6> 
                                                    </button> 
                                                    <button type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapseOne3{{ $va->plan_control_id }}" aria-expanded="false"
                                                        aria-controls="collapseTree" class="text-start m-0 p-0 btn btn-link btn-block">
                                                        <h6 style="color: rgb(66, 63, 63)"><label for="" style="color: rgb(16, 130, 236)">_วัตถุประสงค์_</label>||</h6> 
                                                    </button> 
                                            </div>                                                     --}}
                                            {{-- <div data-parent="#accordion" id="collapseOne2{{ $va->plan_control_id }}" class="collapse">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <h6 style="color: red"> *** KPI</h6> 
                                                        @foreach ($data_sub_ as $itemsub)
                                                            <div class="col-md-12 mb-2">
                                                                @if ($itemsub->plan_control_kpi_name != '')
                                                                    <button type="button" class="btn-icon btn-shadow btn-dashed btn btn-outline-white" onclick="subkpi_destroy({{ $va->plan_control_id }})">
                                                                    <h6 style="color: red"> {{$itemsub->plan_control_kpi_name}} </h6>
                                                                    </button>
                                                                @else                                                                    
                                                                @endif 
                                                            </div> 
                                                        @endforeach 
                                                    </div>
                                                </div>
                                            </div> 
                                            <div data-parent="#accordion" id="collapseOne3{{ $va->plan_control_id }}" class="collapse">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <h6 style="color: rgb(16, 130, 236)"> *** วัตถุประสงค์</h6> 
                                                        @foreach ($data_subobj_ as $sub_obj)
                                                            <div class="col-md-12 mb-2">
                                                                @if ($sub_obj->plan_control_obj_name != '')
                                                                    <button type="button" class="btn-icon btn-shadow btn-dashed btn btn-outline-white" onclick="subobj_destroy({{ $va->plan_control_id }})">
                                                                    <h6 style="color: rgb(16, 130, 236)"> {{$sub_obj->plan_control_obj_name}} </h6>
                                                                    </button>
                                                                @else                                                                    
                                                                @endif 
                                                            </div> 
                                                        @endforeach 
                                                    </div>
                                                </div>
                                            </div>  --}}
                                        </td> 
                                        <td class="text-center" width="8%">{{ number_format($plan_price, 2) }}</td>
                                        <td class="text-center" width="5%">{{$va->plan_req_no}}</td>
                                        <td class="text-center" width="8%">{{ number_format($va->plan_price_total, 2) }}</td>
                                        <td width="5%">
                                            <div class="dropdown">
                                                <button class="btn btn-outline-primary dropdown-toggle menu btn-sm"
                                                    type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false">ทำรายการ</button>
                                                <ul class="dropdown-menu">
                                                        {{-- <button type="button" class="dropdown-item menu btn btn-outline-info btn-sm MoneyModal_"  value="{{ $va->plan_control_id }}" data-bs-toggle="tooltip" data-bs-placement="left" title="เบิกเงิน"> 
                                                            <i class="fa-brands fa-bitcoin me-3 mb-1" style="font-size:17px;color: rgb(20, 199, 190)"></i> 
                                                            <label for=""
                                                            style="color: rgb(20, 199, 190);font-size:13px">เบิกเงิน</label>
                                                        </button>  --}}
                                                        <a type="button" href="{{ url('account_plane_activity/'.$va->plan_control_id) }}"
                                                            class="dropdown-item menu btn btn-outline-warning btn-sm" data-bs-toggle="tooltip"
                                                            data-bs-placement="left" title="กิจกรรม/เบิกเงิน" target="_blank">
                                                            <i class="fa-solid fa-people-robbery me-3 mb-1" style="color: rgb(211, 31, 172);font-size:13px"></i>
                                                                <label for=""
                                                                style="color: rgb(211, 31, 172);font-size:13px">กิจกรรม/เบิกเงิน</label> 
                                                        </a>
                                                </ul>
                                            </div>
                                        </td>        
                                    </tr>
 
                                @endforeach
                            </tbody>
                        </table>
                       

                    </div>
                </div>
            </div>
        </div>
        
    </div>

  
 
@endsection
@section('footer')
    <script>
     
        $(document).ready(function() {
            $('#example').DataTable();
            $('#example2').DataTable();
            $('#example3').DataTable();
           
            $('#startdate').datepicker({
                format: 'yyyy-mm-dd'
            });
            $('#enddate').datepicker({
                format: 'yyyy-mm-dd'
            });
            $('#datepicker1').datepicker({
                format: 'yyyy-mm-dd'
            });
            $('#datepicker2').datepicker({
                format: 'yyyy-mm-dd'
            });
           
        
            $('select').select2();
          
            // $('#plan_control_moneyuser_id').select2({
            //     dropdownParent: $('#MoneyModal')
            // });

            // $('#edit_plan_type').select2({
            //     dropdownParent: $('#UpdateModal')
            // });
            
          
            // $('#plan_type').select2({
            //     dropdownParent: $('#insertModal')
            // });
            // $('#department').select2({
            //     dropdownParent: $('#insertModal')
            // });
            // $('#user_id').select2({
            //     dropdownParent: $('#insertModal')
            // });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // $('#SaveBtn').click(function() {
            //     var plan_name = $('#plan_name').val();
            //     var datepicker1 = $('#startdate').val();
            //     var datepicker2 = $('#enddate').val();
            //     var plan_price = $('#plan_price').val();
            //     var department = $('#department').val();
            //     var plan_type = $('#plan_type').val();
            //     var user_id = $('#user_id').val();
            //     var billno = $('#billno').val();
            //     // alert(datepicker1);
            //     $.ajax({
            //         url: "{{ route('p.plan_control_save') }}",
            //         type: "POST",
            //         dataType: 'json',
            //         data: {
            //             plan_name,
            //             datepicker1,
            //             datepicker2,
            //             plan_price,
            //             department,
            //             plan_type,
            //             user_id,
            //             billno
            //         },
            //         success: function(data) {
            //             if (data.status == 200) {
            //                 Swal.fire({
            //                     title: 'เพิ่มข้อมูลสำเร็จ',
            //                     text: "You Insert data success",
            //                     icon: 'success',
            //                     showCancelButton: false,
            //                     confirmButtonColor: '#06D177',
            //                     confirmButtonText: 'เรียบร้อย'
            //                 }).then((result) => {
            //                     if (result
            //                         .isConfirmed) {
            //                         console.log(
            //                             data);

            //                         window.location
            //                             .reload();
            //                     }
            //                 })
            //             } else {

            //             }

            //         },
            //     });
            // });

            // $(document).on('click', '.edit_data', function() {
            //     var audiovisual_id = $(this).val();
            //     $('#UpdateModal').modal('show');
            //     $.ajax({
            //         type: "GET",
            //         url: "{{ url('audiovisual_work_detail') }}" + '/' + audiovisual_id,
            //         success: function(data) {
            //             $('#edit_ptname').val(data.work.ptname)
            //             $('#edit_tel').val(data.work.tel)
            //             $('#edit_work_order_date').val(data.work.work_order_date)
            //             $('#edit_job_request_date').val(data.work.job_request_date)
            //             $('#edit_department').val(data.work.department)
            //             $('#edit_audiovisual_type').val(data.work.audiovisual_type)
            //             $('#edit_audiovisual_name').val(data.work.audiovisual_name)
            //             $('#edit_audiovisual_qty').val(data.work.audiovisual_qty)
            //             $('#edit_audiovisual_detail').val(data.work.audiovisual_detail)
            //             $('#edit_audiovisual_id').val(data.work.audiovisual_id)
            //         },
            //     });
            // });

            // $(document).on('click', '.MoneyModal_', function() {
            //     var plan_control_id = $(this).val();
            //     $('#plan_control_moneydate').datepicker();
            //     // alert(plan_control_id);
            //     $('#MoneyModal').modal('show');
                
            //     $.ajax({
            //         type: "GET",
            //         url: "{{ url('plan_control_moneyedit') }}" + '/' + plan_control_id,
            //         success: function(data) { 
            //             $('#update_plan_control_id').val(data.data_show.plan_control_id)
            //             $('#data_sub_count').val(data.data_show.plan_control_money_no)
            //         },
            //     });
            // });

            // $('#SaveMoneyBtn').click(function() {
            //     var plan_control_money_no = $('#plan_control_money_no').val();
            //     var plan_control_moneydate = $('#plan_control_moneydate').val();
            //     var plan_control_moneyprice = $('#plan_control_moneyprice').val();
            //     var plan_control_moneyuser_id = $('#plan_control_moneyuser_id').val();
            //     var plan_control_moneycomment = $('#plan_control_moneycomment').val();
            //     var update_plan_control_id = $('#update_plan_control_id').val();
                
            //     $.ajax({
            //         url: "{{ route('p.plan_control_repmoney') }}",
            //         type: "POST",
            //         dataType: 'json',
            //         data: {
            //             plan_control_money_no,
            //             plan_control_moneydate,
            //             plan_control_moneyprice,
            //             plan_control_moneyuser_id,
            //             plan_control_moneycomment ,
            //             update_plan_control_id
            //         },
            //         success: function(data) {
            //             if (data.status == 200) {
            //                 Swal.fire({
            //                     title: 'เบิกเงินสำเร็จ',
            //                     text: "You Request Money success",
            //                     icon: 'success',
            //                     showCancelButton: false,
            //                     confirmButtonColor: '#06D177',
            //                     confirmButtonText: 'เรียบร้อย'
            //                 }).then((result) => {
            //                     if (result
            //                         .isConfirmed) {
            //                         console.log(
            //                             data);

            //                         window.location
            //                             .reload();
            //                     }
            //                 })
            //             } else {

            //             }

            //         },
            //     });
            // });

            // $(document).on('click', '.kpiModal_', function() {
            //     var plan_control_id = $(this).val(); 
            //     $('#kpiModalModal').modal('show');
                
            //     $.ajax({
            //         type: "GET",
            //         url: "{{ url('plan_control_moneyedit') }}" + '/' + plan_control_id,
            //         success: function(data) { 
            //             $('#kpi_plan_control_id').val(data.data_show.plan_control_id)
            //             $('#kpi_billno').val(data.data_show.billno)
            //         },
            //     });
            // });
            // $('#SaveKpiBtn').click(function() { 
            //     var plan_control_kpi_name    = $('#plan_control_kpi_name').val();
            //     var kpi_plan_control_id      = $('#kpi_plan_control_id').val();
            //     var kpi_billno               = $('#kpi_billno').val();
            //     // alert(kpi_billno);
            //     $.ajax({
            //         url: "{{ route('p.plan_control_kpi_save') }}",
            //         type: "POST",
            //         dataType: 'json',
            //         data: { kpi_plan_control_id, plan_control_kpi_name,kpi_billno},
            //         success: function(data) {
            //             if (data.status == 200) {
            //                 Swal.fire({
            //                     title: 'เพิ่มตัวชี้วัดสำเร็จ',
            //                     text: "You Insert KPI success",
            //                     icon: 'success',
            //                     showCancelButton: false,
            //                     confirmButtonColor: '#06D177',
            //                     confirmButtonText: 'เรียบร้อย'
            //                 }).then((result) => {
            //                     if (result
            //                         .isConfirmed) {
            //                         console.log(
            //                             data);

            //                         window.location
            //                             .reload();
            //                     }
            //                 })
            //             } else {

            //             }

            //         },
            //     });
            // });

            // $(document).on('click', '.ojectModal_', function() {
            //     var plan_control_id = $(this).val(); 
            //     $('#ObjModalModal').modal('show');
                
            //     $.ajax({
            //         type: "GET",
            //         url: "{{ url('plan_control_moneyedit') }}" + '/' + plan_control_id,
            //         success: function(data) { 
            //             $('#obj_plan_control_id').val(data.data_show.plan_control_id)
            //             $('#obj_billno').val(data.data_show.billno)
            //         },
            //     });
            // });
            // $('#SaveObjectBtn').click(function() { 
            //     var plan_control_obj_name    = $('#plan_control_obj_name').val();
            //     var obj_plan_control_id      = $('#obj_plan_control_id').val();
            //     var obj_billno               = $('#obj_billno').val();
            //     alert(obj_billno);
            //     $.ajax({
            //         url: "{{ route('p.plan_control_obj_save') }}",
            //         type: "POST",
            //         dataType: 'json',
            //         data: { obj_plan_control_id, plan_control_obj_name,obj_billno},
            //         success: function(data) {
            //             if (data.status == 200) {
            //                 Swal.fire({
            //                     title: 'เพิ่มวัตถุประสงค์สำเร็จ',
            //                     text: "You Insert Obj success",
            //                     icon: 'success',
            //                     showCancelButton: false,
            //                     confirmButtonColor: '#06D177',
            //                     confirmButtonText: 'เรียบร้อย'
            //                 }).then((result) => {
            //                     if (result
            //                         .isConfirmed) {
            //                         console.log(
            //                             data);

            //                         window.location
            //                             .reload();
            //                     }
            //                 })
            //             } else {

            //             }

            //         },
            //     });
            // });

            


        });
    </script>

@endsection
