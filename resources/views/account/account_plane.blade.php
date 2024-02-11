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
                        <div class="col"></div>
                        <div class="col-md-2">
                            <select name="departmentsub" id="departmentsub" class="form-control form-control-sm inputmedsalt" style="width: 100%">

                            </select>
                        </div>
                        <div class="col-md-1 text-end mt-2">วันที่</div>
                        <div class="col-md-4 text-end">
                            <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                                <input type="text" class="form-control inputmedsalt" name="startdate" id="datepicker" placeholder="Start Date"
                                    data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                                    data-date-language="th-th" value="{{ $startdate }}" required/>
                                <input type="text" class="form-control inputmedsalt" name="enddate" placeholder="End Date" id="datepicker2"
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
                            <div class="col-md-4">
                                {{-- <h4 class="card-title">Detail Account ผัง 1102050101.217</h4>
                                <p class="card-title-desc">รายละเอียดตั้งลูกหนี้</p> --}}
                            </div>
                            <div class="col"></div>
                            <div class="col-md-2 text-end">
                                
                            </div>
                        </div>

                         


                    </div>
                </div>
            </div>
        </div>
        
    </div>

  
 
@endsection
@section('footer')
    <script>
        function details(id){
                $.ajax({
                        url:"{{route('p.detail_plan')}}",
                        method:"GET",
                        data:{id:id},
                        success:function(result){
                            $('#details').html(result); 
                        } 
                }) 
            }
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
