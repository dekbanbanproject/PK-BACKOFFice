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
    <div class="container-fluid"> 
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">แผนงาน/กิจกรรมสำคัญ</h4>
    
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">แผนงาน/กิจกรรมสำคัญ</a></li>
                            <li class="breadcrumb-item active">เพิ่มกิจกรรมสำคัญ</li>
                        </ol>
                    </div>
    
                </div>
            </div>
        </div> 
    </div> 
        <div class="row">
            <div class="col-xl-12">
                <div class="card cardplan">    
                    <div class="card-body ">  
                        <div class="row">                          
                            <div class="col-md-12"> 
                                    <div class="card-header">  
                                        <h5 class="modal-title me-3" id="editModalLabel">แผนงาน/กิจกรรมสำคัญ  {{$plan_control->plan_name}}</h5>  
                                        <div class="btn-actions-pane-right">   
                                        <h6 class="mt-2 me-3"> เลขที่ {{$plan_control->billno}}</h6> 
                                        <input type="hidden" id="billno" name="billno" value="{{$plan_control->billno}}">
                                    </div>  
                                    </div>                 
                                    <div class="card-body"> 

                                        <div class="row">
                                            <div class="col-md-8 ">
                                                <label for="">ชื่อแผนงาน/กิจกรรมสำคัญ</label>
                                                <div class="form-group">
                                                <input id="plan_control_activity_name" class="form-control form-control-sm" name="plan_control_activity_name">
                                                </div>
                                            </div>
                                            <div class="col-md-3 ">
                                                <label for="">กลุ่มเป้าหมาย</label>
                                                <div class="form-group"> 
                                                    <input id="plan_control_activity_group" class="form-control form-control-sm" name="plan_control_activity_group">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <label for="">จำนวน(คน) </label>
                                                <div class="form-group"> 
                                                    <input id="qty" class="form-control form-control-sm" name="qty">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-8 ">
                                                <label for="">รายละเอียดงบประมาณ</label>
                                                <div class="form-group"> 
                                                    <input id="budget_detail" class="form-control form-control-sm" name="budget_detail">
                                                </div>
                                            </div>
                                            <div class="col-md-1 ">
                                                <label for="">บาท</label>
                                                <div class="form-group"> 
                                                    <input id="qty" class="form-control form-control-sm" name="qty">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3 ">
                                                <label for="">แหล่งงบประมาณ </label>
                                                <div class="form-group">
                                                    <select name="budget_source" id="budget_source" class="form-control form-control-sm" style="width: 100%"> 
                                                        @foreach ($plan_control_type as $item2)
                                                        @if ($plan_control->plan_type == $item2->plan_control_type_id)
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
                                            
                                            <div class="col-md-2">
                                                <label for="">ไตรมาสที่ 1 </label>
                                                <div class="form-group">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="trimart_11" id="trimart_11" value="trimart_11">
                                                        <label class="form-check-label" for="trimart_11">ต.ค.</label>
                                                    </div> 
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="trimart_12" id="trimart_12" value="trimart_12">
                                                        <label class="form-check-label" for="trimart_12">พ.ย.</label>
                                                    </div> 
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="trimart_13" id="trimart_13" value="trimart_13">
                                                        <label class="form-check-label" for="trimart_13">ธ.ค.</label>
                                                    </div> 
                                                </div>
                                            </div>  
                                            <div class="col-md-2">
                                                <label for="">ไตรมาสที่ 2 </label>
                                                <div class="form-group">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="trimart_21" id="trimart_21" value="trimart_21">
                                                        <label class="form-check-label" for="trimart_21">ม.ค.</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="trimart_22" id="trimart_22" value="trimart_22">
                                                        <label class="form-check-label" for="trimart_22">ก.พ.</label>
                                                    </div> 
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="trimart_23" id="trimart_23" value="trimart_23">
                                                        <label class="form-check-label" for="trimart_23">มี.ค.</label>
                                                    </div> 
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="">ไตรมาสที่ 3 </label>
                                                <div class="form-group">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="trimart_31" id="trimart_31" value="trimart_31">
                                                        <label class="form-check-label" for="trimart_31">เม.ย.</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="trimart_32" id="trimart_32" value="trimart_32">
                                                        <label class="form-check-label" for="trimart_32">พ.ค.</label>
                                                    </div> 
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="trimart_33" id="trimart_33" value="trimart_33">
                                                        <label class="form-check-label" for="trimart_33">มิ.ย.</label>
                                                    </div> 
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="">ไตรมาสที่ 4 </label>
                                                <div class="form-group">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="trimart_41" id="trimart_41" value="trimart_41">
                                                        <label class="form-check-label" for="trimart_41">ก.ค.</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="trimart_42" id="trimart_42" value="trimart_42">
                                                        <label class="form-check-label" for="trimart_42">ส.ค.</label>
                                                    </div> 
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="trimart_43" id="trimart_43" value="trimart_43">
                                                        <label class="form-check-label" for="trimart_43">ก.ย.</label>
                                                    </div> 
                                                </div>
                                            </div> 

                                            <div class="col-md-4">
                                                <label for="">ผู้รับผิดชอบ </label>
                                                <div class="form-group">
                                                    <select name="responsible_person" id="responsible_person" class="form-control form-control-sm" style="width: 100%">                                                    
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
                                           
                                        </div> 
                                    </div>
                                    <div class="card-footer mt-2">
                                        <div class="btn-actions-pane-right mt-2">
                                            <button type="button" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info" id="Updatedata">
                                                <i class="pe-7s-diskette btn-icon-wrapper"></i>เพิ่ม 
                                            </button>
                                            <a href="{{ url('plan_control') }}" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-danger me-2">
                                                <i class="fa-solid fa-xmark me-2"></i>
                                                Back
                                            </a>
                                        </div>
                                    </div>                               
                            </div>                              
                        </div>                
                    </div>                                
                </div>
            </div>
        </div>  
        <div class="row">
            <div class="col-xl-12">
                <div class="card cardplan"> 
                    <div class="card-body "> 
                        <div class="row">                          
                            <div class="col-md-12"> 
                                <div class="card-header">  
                                    <h5 class="modal-title me-3" id="editModalLabel">แผนงาน/กิจกรรมสำคัญ  {{$plan_control->plan_name}}</h5>  
                                    <div class="btn-actions-pane-right">   
                                    <h6 class="mt-2 me-3"> เลขที่ {{$plan_control->billno}}</h6> 
                                    <input type="hidden" id="billno" name="billno" value="{{$plan_control->billno}}">
                                </div>  
                                </div>                 
                                <div class="card-body"> 
                                    <div class="row mt-2">                          
                                        <div class="col-md-12"> 
                                            <table id="example" class="table table-striped table-bordered dt-responsive nowrap myTable" style="border-collapse: collapse; width: 100%;border-color: rgb(140, 137, 137)">
                                                <thead style="border-color: rgb(69, 68, 68)">
                                                    <tr style="font-size: 13px;background-color: rgb(255, 231, 226);border-color: rgb(183, 180, 180)">
                                                        <th rowspan="3" colspan="1" class="text-center" width="4%">ลำดับ</th>
                                                        <th rowspan="3" colspan="1" class="text-center"> แผนงาน/กิจกรรมสำคัญ</th> 
                                                        <th rowspan="3" colspan="1" class="text-center">กลุ่มเป้าหมาย</th> 
                                                        <th rowspan="3" colspan="1" class="text-center" width="4%">จำนวน(คน) </th> 
                                                        <th colspan="12" class="text-center" style="border-color: rgb(183, 180, 180)">เป้าหมายการดำเนินงาน (1ต.ค.66 - 30 ก.ย.67)</th> 
                                                    
                                                    </tr>
                                                    <tr style="font-size: 13px;background-color: rgb(255, 231, 226);border-color: rgb(183, 180, 180)">
                                                        <td colspan="3" style="text-align: center;border-color: rgb(183, 180, 180)">ไตรมาสที่ 1</td>
                                                        <td colspan="3" style="text-align: center;border-color: rgb(183, 180, 180)">ไตรมาสที่ 2</td>
                                                        <td colspan="3" style="text-align: center;border-color: rgb(183, 180, 180)">ไตรมาสที่ 3</td>       
                                                        <td colspan="3" style="text-align: center;border-color: rgb(183, 180, 180)">ไตรมาสที่ 4</td>   
                                                        <th colspan="3" colspan="2" class="text-center">งบประมาณ</th>                           
                                                    </tr>
                                                    <tr style="font-size: 13px;background-color: rgb(255, 231, 226);border-color: rgb(183, 180, 180)">
                                                        <td style="text-align: center;border-color: rgb(183, 180, 180)"">ต.ค</td>
                                                        <td style="text-align: center;border-color: rgb(183, 180, 180)"">พ.ย.</td>
                                                        <td style="text-align: center;border-color: rgb(183, 180, 180)"">ธ.ค.</td>                                          
                                                        <td style="text-align: center;border-color: rgb(183, 180, 180)"">ม.ค.</td>
                                                        <td style="text-align: center;border-color: rgb(183, 180, 180)"">ก.พ.</td>
                                                        <td style="text-align: center;border-color: rgb(183, 180, 180)"">มี.ค.</td>                                         
                                                        <td style="text-align: center;border-color: rgb(183, 180, 180)"">เม.ย.</td>
                                                        <td style="text-align: center;border-color: rgb(183, 180, 180)"">พ.ค.</td>
                                                        <td style="text-align: center;border-color: rgb(183, 180, 180)"">มิ.ย.</td>                                          
                                                        <td style="text-align: center;border-color: rgb(183, 180, 180)"">ก.ค.</td>
                                                        <td style="text-align: center;border-color: rgb(183, 180, 180)"">ส.ค.</td>
                                                        <td style="text-align: center;border-color: rgb(183, 180, 180)"">ก.ย.</td>  
                                                
                                                        <td style="text-align: center;border-color: rgb(183, 180, 180)"">รายละเอียด</td>
                                                        <td style="text-align: center;border-color: rgb(183, 180, 180)"">บาท</td> 
                                                    </tr> 
                                                </thead>
                                                <tbody style="font-size: 13px;border-color: rgb(183, 180, 180)">
                                                    <tr>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" >1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">1</td>
                                                        <td class="text-center" width="4%">10</td>
                                                    </tr>
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
                    var plan_name              = $('#plan_name').val();
                    var datepicker1            = $('#startdate').val();
                    var datepicker2            = $('#enddate').val();
                    var plan_price             = $('#plan_price').val();
                    var department             = $('#department').val();
                    var plan_type              = $('#plan_type').val();
                    var user_id                = $('#user_id').val();
                    var billno                 = $('#billno').val();
                    var plan_strategic_id      = $('#plan_strategic_id').val();
                    var plan_control_id        = $('#plan_control_id').val();
                $.ajax({
                    url: "{{ route('p.plan_control_update') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        plan_name,datepicker1,datepicker2,plan_price,department,plan_type,user_id,billno,plan_control_id,plan_strategic_id
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
