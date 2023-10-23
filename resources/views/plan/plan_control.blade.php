@extends('layouts.plannew')
@section('title','PK-BACKOFFice || Plan')
@section('content')
<style>
    #button{
           display:block;
           margin:20px auto;
           padding:30px 30px;
           background-color:#eee;
           border:solid #ccc 1px;
           cursor: pointer;
           }
           #overlay{	
           position: fixed;
           top: 0;
           z-index: 100;
           width: 100%;
           height:100%;
           display: none;
           background: rgba(0,0,0,0.6);
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
           .is-hide{
           display:none;
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
        $iddep =  Auth::user()->dep_subsubtrueid;
    } else {
        echo "<body onload=\"TypeAdmin()\"></body>";
        exit();
    }
    $url = Request::url();
    $pos = strrpos($url, '/') + 1;

    $datenow = date("Y-m-d");
    $y = date('Y') + 543;
    $newweek = date('Y-m-d', strtotime($datenow . ' -1 week')); //ย้อนหลัง 1 สัปดาห์  
    $newDate = date('Y-m-d', strtotime($datenow . ' -1 months')); //ย้อนหลัง 1 เดือน 
    use Illuminate\Support\Facades\DB;
    use App\Http\Controllers\PlanController; 
    $refnumber = PlanController::refnumber();
?>
<div class="tabs-animation">
    <div id="preloader">
        <div id="status">
            <div class="spinner">
            </div>
        </div>
    </div>
        <div class="row mt-3">
            <div class="col-xl-12">
                <div class="card">   
                    <div class="card-header ">
                        ทะเบียนควบคุมแผนงานโครงการ
                        <div class="btn-actions-pane-right">
                            <a href="{{ url('plan_control_add') }}" class="btn-icon btn-shadow btn-dashed btn btn-sm btn-outline-info"  data-bs-toggle="modal" data-bs-target="#insertModal"> 
                                <i class="fa-solid fa-folder-plus text-info me-2"></i>
                                เพิ่มทะเบียน
                                </a> 
                        </div> 
                       
                    </div>   
                    <div class="card-body py-0 px-2 mt-2"> 
                        <div class="table-responsive"> 
                            <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="example">
                                <thead>                                           
                                    <tr>
                                        <th width="5%" class="text-center">ลำดับ</th>
                                        <th class="text-center"> แผนงาน/โครงการ</th>
                                        <th class="text-center">วัตถุประสงค์ /ตัวชี้วัด</th> 
                                        <th class="text-center">แหล่งงบประมาณ</th> 
                                        <th class="text-center">งบประมาณ</th> 
                                        <th class="text-center">ระยะเวลา</th>      
                                        <th class="text-center">กลุ่มงาน</th>  
                                        <th class="text-center">รวมเบิก</th>                                                
                                    </tr>                                            
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($plan_control as $va)
                                        <tr>
                                            <td class="text-center">{{$i++}}</td> 
                                            <td class="text-start">{{$va->plan_name}}</td>
                                            <td class="text-center">{{$va->plan_obj}}</td>
                                            <td class="text-center">{{$va->plan_type}}</td>
                                            <td class="text-center">{{$va->plan_price}}</td>
                                            <td class="text-center">{{DateThai($va->plan_starttime)}}ถึง{{DateThai($va->plan_endtime)}}</td> 
                                            <td class="text-center">{{$va->department}}</td>
                                            <td class="text-center">{{$va->plan_reqtotal}}</td> 
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
 
     <!--  Modal content for the insert example -->
     <div class="modal fade" id="insertModal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">เพิ่มทะเบียนควบคุมแผนงานโครงการ</h5><h6 class="mt-2">{{$refnumber}} </h6>
                    <input id="billno" class="form-control form-control-sm" name="billno" type="hidden" value="{{$refnumber}}"> 
                </div>
                <div class="modal-body"> 
                    <div class="row">
                        <div class="col-md-12 ">
                            <label for="">ชื่อโครงการ</label>
                            <div class="form-group">
                            <input id="plan_name" class="form-control form-control-sm" name="plan_name">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3 ">
                            <label for="">ระยะเวลา วันที่</label>
                            <div class="form-group">
                                {{-- <input id="plan_starttime" class="form-control form-control-sm" name="plan_starttime"> --}}
                                <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy"
                                    data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker1'>
                                    <input type="text" class="form-control" name="startdate" id="startdate" placeholder="Start Date"
                                        data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true"
                                        autocomplete="off" data-date-language="th-th" value="{{ $datenow }}" required />
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <label for="">ถึง </label>
                            <div class="form-group">
                                {{-- <input id="plan_endtime" class="form-control form-control-sm" name="plan_endtime"> --}}
                                <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy"
                                    data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker1'>
                                   
                                    <input type="text" class="form-control" name="enddate" placeholder="End Date" id="enddate"
                                        data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true"
                                        autocomplete="off" data-date-language="th-th" value="{{ $datenow }}" /> 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <label for="">งบประมาณ </label>
                            <div class="form-group">
                                <input id="plan_price" class="form-control form-control-sm" name="plan_price">
                            </div>
                        </div>
                        <div class="col-md-3 ">
                            <label for="">แหล่งงบ </label>
                            <div class="form-group">
                                <select name="plan_type" id="plan_type" class="form-control form-control-sm" style="width: 100%"> 
                                    @foreach ($plan_control_type as $item2)
                                    <option value="{{$item2->plan_control_type_id}}">{{$item2->plan_control_typename}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row mt-2"> 
                        <div class="col-md-8 ">
                            <label for="">กลุ่มงาน </label>
                            <div class="form-group">
                                <select name="department" id="department" class="form-control form-control-sm" style="width: 100%">
                                    {{-- <option value="">=เลือก=</option> --}}
                                    @foreach ($department_sub_sub as $item)
                                    @if ($iddep == $item->DEPARTMENT_SUB_SUB_ID)
                                    <option value="{{$item->DEPARTMENT_SUB_SUB_ID}}" selected>{{$item->DEPARTMENT_SUB_SUB_NAME}}</option>
                                    @else
                                    <option value="{{$item->DEPARTMENT_SUB_SUB_ID}}">{{$item->DEPARTMENT_SUB_SUB_NAME}}</option>
                                    @endif
                                        
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       
                        <div class="col-md-4 ">
                            <label for="">ผู้รับผิดชอบ </label>
                            <div class="form-group">
                                <select name="user_id" id="user_id" class="form-control form-control-sm" style="width: 100%"> 
                                    @foreach ($users as $item3)
                                    <option value="{{$item3->id}}">{{$item3->fname}} {{$item3->lname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="col-md-12 text-end">
                        <div class="form-group">
                            <button type="button" id="SaveBtn" class="btn-icon btn-shadow btn-dashed btn btn-sm btn-outline-info">
                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                บันทึกข้อมูล
                            </button>
                            <button type="button" class="btn-icon btn-shadow btn-dashed btn btn-sm btn-outline-danger" data-bs-dismiss="modal"><i
                                    class="fa-solid fa-xmark me-2"></i>Close</button>

                        </div>
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


                $('select').select2();
                $('#plan_type').select2({
                    dropdownParent: $('#insertModal')
                });
                $('#department').select2({
                    dropdownParent: $('#insertModal')
                });
                $('#user_id').select2({
                    dropdownParent: $('#insertModal')
                });
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('#SaveBtn').click(function() { 
                    var plan_name    = $('#plan_name').val();
                    var datepicker1  = $('#startdate').val();
                    var datepicker2  = $('#enddate').val();
                    var plan_price   = $('#plan_price').val();
                    var department   = $('#department').val();
                    var plan_type    = $('#plan_type').val();
                    var user_id      = $('#user_id').val();
                    var billno      = $('#billno').val();
                    // alert(datepicker1);
                    $.ajax({
                        url: "{{ route('p.plan_control_save') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {
                        plan_name,datepicker1,datepicker2,plan_price,department,plan_type,user_id,billno 
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

                                        window.location
                                            .reload();
                                    }
                                })
                            } else {

                            }

                        },
                    });
                });

                              
            });
           
        </script>    
       
    @endsection
