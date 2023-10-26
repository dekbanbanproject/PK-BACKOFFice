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
        <div class="row mt-3 mb-5">
            <div class="col-xl-12">
                <div class="card">   
                    <div class="card-header ">
                        แก้ไขทะเบียนควบคุมแผนงานโครงการ
                        <div class="btn-actions-pane-right">
                            <button type="button" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info" id="Updatedata">
                                <i class="pe-7s-diskette btn-icon-wrapper"></i>Update 
                            </button>
                            <a href="{{ url('plan_control') }}" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-danger me-2">
                                <i class="fa-solid fa-xmark me-2"></i>
                            Back
                        </a>
                        </div> 
                       
                    </div>   
                    <div class="card-body py-0 px-2 mt-2"> 
                        <div class="row mt-2">
                            {{-- <div class="col"></div> --}}
                            <div class="col-md-12">
                                <div class="card mb-5">
                                    <div class="card-header">  
                                        <h5 class="modal-title me-3" id="editModalLabel">แก้ไขทะเบียนควบคุม</h5>  
                                        <div class="btn-actions-pane-right">   
                                        <h6 class="mt-2 me-3"> เลขที่ {{$plan_control->billno}}</h6> 
                                        <input type="hidden" id="billno" name="billno" value="{{$plan_control->billno}}">
                                    </div>  
                                    </div>                 
                                    <div class="card-body"> 
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <label for="">ชื่อโครงการ</label>
                                                <div class="form-group">
                                                <input id="plan_name" class="form-control form-control-sm" name="plan_name" value="{{$plan_control->plan_name}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-3 ">
                                                <label for="">ระยะเวลา วันที่</label>
                                                <div class="form-group"> 
                                                    <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy"
                                                        data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker1'>
                                                        <input type="text" class="form-control" name="startdate" id="startdate" placeholder="Start Date"
                                                            data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true"
                                                            autocomplete="off" data-date-language="th-th" value="{{ $plan_control->plan_starttime }}" required />
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 ">
                                                <label for="">ถึง </label>
                                                <div class="form-group"> 
                                                    <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy"
                                                        data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker1'>
                                                       
                                                        <input type="text" class="form-control" name="enddate" placeholder="End Date" id="enddate"
                                                            data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true"
                                                            autocomplete="off" data-date-language="th-th" value="{{ $plan_control->plan_endtime }}" /> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 ">
                                                <label for="">งบประมาณ </label>
                                                <div class="form-group">
                                                    <input id="plan_price" class="form-control form-control-sm" name="plan_price" value="{{ $plan_control->plan_price }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3 ">
                                                <label for="">แหล่งงบ </label>
                                                <div class="form-group">
                                                    <select name="plan_type" id="plan_type" class="form-control form-control-sm" style="width: 100%"> 
                                                        @foreach ($plan_control_type as $item2)
                                                        @if ($plan_control->plan_type)
                                                        <option value="{{$item2->plan_control_type_id}}" selected>{{$item2->plan_control_typename}}</option>
                                                        @else
                                                        <option value="{{$item2->plan_control_type_id}}">{{$item2->plan_control_typename}}</option>
                                                        @endif
                                                       
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
                                                        @if ($plan_control->department == $item->DEPARTMENT_SUB_SUB_ID)
                                                        <option value="{{$item->DEPARTMENT_SUB_SUB_ID}}" selected>{{$item->DEPARTMENT_SUB_SUB_NAME}}</option>
                                                        @else
                                                        <option value="{{$item->DEPARTMENT_SUB_SUB_ID}}">{{$item->DEPARTMENT_SUB_SUB_NAME}}</option>
                                                        @endif
                                                            
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <input type="hidden" id="plan_control_id" name="plan_control_id" value="{{$plan_control->plan_control_id}}">
                                            <div class="col-md-4 ">
                                                <label for="">ผู้รับผิดชอบ </label>
                                                <div class="form-group">
                                                    <select name="user_id" id="user_id" class="form-control form-control-sm" style="width: 100%"> 
                                                        @foreach ($users as $item3)
                                                        @if ($plan_control->user_id == $item3->id)
                                                        <option value="{{$item3->id}}" selected>{{$item3->fname}} {{$item3->lname}}</option>
                                                        @else
                                                        <option value="{{$item3->id}}">{{$item3->fname}} {{$item3->lname}}</option>
                                                        @endif
                                                       
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <div class="row"> 
                                            <div class="col-md-6">
                                                <label for="ptname" class="form-label">ชื่อ-นามสกุล</label><label for="tel" class="form-label" style="color: red">*</label>
                                                <div class="input-group input-group-sm">  
                                                    <select class="form-control" id="ptname" name="ptname" style="width: 100%">
                                                        @foreach ($users as $item)
                                                        @if ($iduser == $item->id)
                                                        <option value="{{$item->id}}" selected>{{$item->fname}} {{$item->lname}}</option>
                                                        @else
                                                        <option value="{{$item->id}}">{{$item->fname}} {{$item->lname}}</option>
                                                        @endif                                            
                                                        @endforeach
                                                    </select>
                                                
                                                </div>
                                            </div>  
                                            <div class="col-md-2">
                                                <label for="tel" class="form-label"> เบอร์โทร</label><label for="tel" class="form-label" style="color: red">*</label>
                                                <div class="input-group input-group-sm">  
                                                
                                                        <input type="text" class="form-control" id="tel" name="tel" value="{{$plan_control->tel}}">   
                                                    
                                                </div>
                                            </div> 
                                            <div class="col-md-2">
                                                <label for="work_order_date" class="form-label" >วันที่สั่งงาน </label><label for="tel" class="form-label" style="color: red">*</label>
                                                <div class="input-group input-group-sm"> 
                                                    <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                                                    <input type="text" class="form-control" name="work_order_date" id="datepicker" placeholder="Start Date" data-date-container='#datepicker1' autocomplete="off"
                                                    data-provide="datepicker" data-date-autoclose="true" data-date-language="th-th" value="{{ $datenow }}"/>
                                                </div> 
                                                </div>
                                            </div> 
                                            <div class="col-md-2">
                                                <label for="job_request_date" class="form-label" >วันที่ขอรับงาน </label><label for="tel" class="form-label" style="color: red">*</label>
                                                <div class="input-group input-group-sm"> 
                                                    <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                                                    <input type="text" class="form-control" name="job_request_date" placeholder="End Date" id="datepicker2" data-date-container='#datepicker1' autocomplete="off"
                                                    data-provide="datepicker" data-date-autoclose="true" data-date-language="th-th" value="{{ $datenow }}"/> 
                                                </div>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="row mt-2"> 
                                            <div class="col-md-6">
                                                <label for="department" class="form-label">หน่วยงาน</label><label for="tel" class="form-label" style="color: red">*</label>
                                                <div class="input-group input-group-sm">  
                                                    <select class="form-control" id="department" name="department" style="width: 100%">
                                                        @foreach ($department_sub_sub as $item2)
                                                        @if ($debsubsub == $item2->DEPARTMENT_SUB_SUB_ID)
                                                        <option value="{{$item2->DEPARTMENT_SUB_SUB_ID}}" selected>{{$item2->DEPARTMENT_SUB_SUB_NAME}} </option>
                                                        @else
                                                        <option value="{{$item2->DEPARTMENT_SUB_SUB_ID}}"> {{$item2->DEPARTMENT_SUB_SUB_NAME}}</option>
                                                        @endif                                            
                                                        @endforeach
                                                    </select>
                                                
                                                </div>
                                            </div>  
                                            <div class="col-md-6">
                                                <label for="audiovisual_type" class="form-label" >ชนิดของงาน</label><label for="tel" class="form-label" style="color: red">*</label>
                                                <div class="input-group input-group-sm">  
                                                    <select class="form-control" id="audiovisual_type" name="audiovisual_type" style="width: 100%">
                                                        @foreach ($audiovisual_type as $item3)  
                                                        <option value="{{$item3->audiovisual_type_id}}"> {{$item3->audiovisual_typename}}</option> 
                                                        @endforeach
                                                    </select>
                                                
                                                </div>
                                            </div>   
                                        </div>  
        
                                        <div class="row mt-2"> 
                                            <div class="col-md-10">
                                                <label for="audiovisual_name" class="form-label" >ชื่อชิ้นงาน </label><label for="tel" class="form-label" style="color: red">*</label>
                                                <div class="input-group input-group-sm"> 
                                                    <input type="text" class="form-control" id="audiovisual_name" name="audiovisual_name" >  
                                                </div>
                                            </div>  
                                            
                                            <div class="col-md-2">
                                                <label for="audiovisual_qty" class="form-label" >จำนวนชิ้นงาน</label>
                                                <div class="input-group input-group-sm"> 
                                                    <input type="text" class="form-control" id="audiovisual_qty" name="audiovisual_qty" >  
                                                </div>
                                            </div>  
                                        </div> 
                                        <div class="row mt-2"> 
                                            <div class="col-md-12">
                                                <label for="audiovisual_detail" class="form-label" >รายละเอียดงาน (เช่นขนาดงาน สถานที่ )</label><label for="tel" class="form-label" style="color: red">*</label>
                                                <div class="input-group input-group-sm"> 
                                                    <textarea id="audiovisual_detail" name="audiovisual_detail" cols="30" rows="3" class="form-control form-control-sm" ></textarea> 
                                                </div>
                                            </div>  
                                     
                                        </div>  --}}
                                    </div>
                                   
                                </div>   
                            </div> 
                            <div class="col"></div>
                        </div>
                    </div> 
                    <br>  <br> <br> <br>                  
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

                $('select').select2();
              
                $('#startdate').datepicker({
                    format: 'yyyy-mm-dd'
                });
                $('#enddate').datepicker({
                    format: 'yyyy-mm-dd'
                });
              
                $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
 
            $("#spinner-div").hide(); //Request is complete so hide spinner
  

            $('#Updatedata').click(function() {
                var plan_name    = $('#plan_name').val();
                    var datepicker1  = $('#startdate').val();
                    var datepicker2  = $('#enddate').val();
                    var plan_price   = $('#plan_price').val();
                    var department   = $('#department').val();
                    var plan_type    = $('#plan_type').val();
                    var user_id      = $('#user_id').val();
                    var billno      = $('#billno').val();
                    var plan_control_id      = $('#plan_control_id').val();
                $.ajax({
                    url: "{{ route('p.plan_control_update') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        plan_name,datepicker1,datepicker2,plan_price,department,plan_type,user_id,billno,plan_control_id 
                    },
                    success: function(data) {
                        if (data.status == 200) {
                            Swal.fire({
                                title: 'แก้ไขข้อมูลสำเร็จ',
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
                                        window.location="{{url('plan_control')}}";
                                    // window.location
                                    //     .reload();
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
