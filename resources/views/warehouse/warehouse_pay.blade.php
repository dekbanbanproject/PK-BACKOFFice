@extends('layouts.warehouse_new')
@section('title', 'PK-BACKOFFice || คลังวัสดุ')
@section('content')
    <script>
        function TypeAdmin() {
            window.location.href = '{{ route('index') }}';
        }
    </script>
    <?php
    if (Auth::check()) {
        $type = Auth::user()->type;
        $iduser = Auth::user()->id;
        $storeid = Auth::user()->store_id;
    } else {
        echo "<body onload=\"TypeAdmin()\"></body>";
        exit();
    }
    $url = Request::url();
    $pos = strrpos($url, '/') + 1;
    
    ?>
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
    <?php
    use Illuminate\Support\Facades\DB;
    use App\Http\Controllers\WarehousePayController;
    use App\Http\Controllers\StaticController;
        $refnumber = WarehousePayController::refnumber();
        date_default_timezone_set('Asia/Bangkok');
        $dateyear = date('Y') + 543;
        $datefull = date('Y-m-d H:i:s');
        $time = date("H:i:s");
        $loter = $dateyear.''.$time;
        $date = date('Y-m-d');
    ?>


<div class="tabs-animation">
    <div id="preloader">
        <div id="status">
            <div class="spinner">
            </div>
        </div>
    </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        
                        <div class="card-header">
                            รายการเบิกจ่ายวัสดุ
                            <div class="btn-actions-pane-right">
                                <div role="group" class="btn-group-sm btn-group">
                                    {{-- <a href="" class="mb-2 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info"
                                        data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <i class="pe-7s-shuffle btn-icon-wrapper"></i>เปิดบิล
                                </a> --}}
                                    <button type="button" class="mb-2 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info"
                                        data-bs-toggle="modal" data-bs-target="#insertpaydata">
                                        <i class="pe-7s-shuffle btn-icon-wrapper"></i>สร้างใบเบิก
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body shadow-lg"> 
                                {{-- <table style="width: 100%;" id="example"
                                    class="table table-hover table-striped table-bordered myTable" 
                                    > --}}
                                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="3%" class="text-center">ลำดับ</th> 
                                                <th width="9%" class="text-center">เลขที่บิล</th>
                                                <th width="4%" class="text-center">ปีงบ</th>
                                                <th width="9%" class="text-center">วันที่จ่าย</th>
                                                <th class="text-center">จากคลัง</th>
                                                <th class="text-center">รับเข้าคลัง</th> 
                                                <th width="8%" class="text-center">สถานะคลัง</th>
                                                <th width="10%" class="text-center">ผู้รับ</th>
                                                <th width="10%" class="text-center">ผู้จ่าย</th>
                                                <th width="5%" class="text-center">จัดการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1;
                                            $date = date('Y');
                                            ?>
                                            @foreach ($warehouse_pay as $item)
                                                <tr id="sid{{ $item->warehouse_pay_id }}">
                                                    <td class="text-center" width="3%">{{ $i++ }}</td> 
                                                    <td class="text-center" width="9%">{{ $item->warehouse_pay_code }} </td>
                                                    <td class="text-center" width="4%">{{ $item->warehouse_pay_year }}</td>
                                                    <td class="text-center" width="9%">
                                                        {{ DateThai($item->warehouse_pay_date) }}
                                                    </td>
                                                    <?php  
                                                    $pay_frominven = DB::table('warehouse_inven')->where('warehouse_inven_id', '=', $item->warehouse_pay_frominven_id)->first();
                                                    $pay_frominven_name = $pay_frominven->warehouse_inven_name;
                                                    
                                                    $pay_fromuser = DB::table('users')->where('id', '=', $item->warehouse_pay_fromuser_id)->first();
                                                    $pay_fromuser_name = $pay_fromuser->fname.' '.$pay_fromuser->lname;
                                                    ?>
                                                    <td class="p-2">{{ $pay_frominven_name }}</td>     
                                                    <td class="p-2" width="14%">{{ $item->warehouse_inven_name }}</td>
                                                    <td class="text-center" width="9%">{{ $item->warehouse_pay_status_name }}</td>
                                                    <td class="text-center" width="10%">{{ $item->fname }} {{ $item->lname }}</td>
                                                    <td class="text-center" width="10%">{{ $pay_fromuser_name}}</td>
                                                
                                                    <td class="text-center" width="5%">
                                                        
                                                        {{-- <div class="btn-group">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> 
                                                                <label for="" style="color: rgb(57, 57, 57);font-size:13px">ทำรายการ</label>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item text-warning" href="{{url('warehouse/warehouse_pay_edit/'.$item->warehouse_pay_id)}}"  
                                                                    data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="custom-tooltip" title="แก้ไข">
                                                                    <i class="fa-solid fa-pen-to-square me-2"></i>
                                                                    <label for="" style="color: rgb(252, 185, 0);font-size:13px">แก้ไข</label>
                                                                </a>
                                                                <div class="dropdown-divider"></div>
                                                                <a class="dropdown-item text-warning" href="{{url('warehouse/warehouse_pay_sub/'.$item->warehouse_pay_id)}}"
                                                                    data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="custom-tooltip" title="เพิ่มรายการจ่าย">
                                                                    <i class="fa-solid fa-folder-plus me-2" style="color: rgb(112, 34, 238)"></i>
                                                                    <label for="" style="color: rgb(112, 34, 238);font-size:13px">เพิ่มรายการจ่าย</label>
                                                                </a> 
                                                        </div> --}}
                                                        <div class="dropdown d-inline-block">
                                                            <button type="button" aria-haspopup="true" aria-expanded="false"
                                                                data-bs-toggle="dropdown"
                                                                class="me-2 dropdown-toggle btn btn-outline-secondary btn-sm">
                                                                ทำรายการ
                                                            </button>
                                                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-hover-link dropdown-menu">
                                                                <a class="dropdown-item text-warning"
                                                                        href="{{url('warehouse/warehouse_pay_edit/'.$item->warehouse_pay_id)}}" style="font-size:13px" target="blank">
                                                                        <i class="fa-solid fa-clipboard-check me-2 text-warning"
                                                                            style="font-size:13px"></i>
                                                                        <span>แก้ไข</span>
                                                                    </a>
                                                                <div class="dropdown-divider"></div>
                                                                    <a class="dropdown-item text-primary"
                                                                    href="{{ url('warehouse_payadd/' . $item->warehouse_pay_id) }}"
                                                                    style="font-size:13px" target="blank">
                                                                    <i class="fa-solid fa-clipboard-check me-2 text-primary"
                                                                        style="font-size:13px"></i>
                                                                    <span>เพิ่มรายการ</span>
                                                                </a>
                                                            </div>
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

        <!--  Modal content for the insertborrowdata example -->
        <div class="modal fade" id="insertpaydata" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myExtraLargeModalLabel">สร้างใบเบิก</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2 text-end">
                                <label for="pay_code">เลขที่บิล :</label>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input id="pay_code" type="text"
                                        class="form-control form-control-sm" name="pay_code"
                                        value="{{ $refnumber }}"
                                         readonly>
                                </div>
                            </div>
                         
                            
                            <div class="col-md-2 text-end">
                                <label for="pay_year">ปีงบ :</label>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select id="pay_year" name="pay_year"
                                        class="form-select form-select-lg" style="width: 100%">
                                        <option value="">--เลือก--</option>
                                        @foreach ($budget_year as $ye)
                                            @if ($ye->leave_year_id == $dateyear)
                                                <option value="{{ $ye->leave_year_id }}" selected>
                                                    {{ $ye->leave_year_id }} </option>
                                            @else
                                                <option value="{{ $ye->leave_year_id }}"> {{ $ye->leave_year_id }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>  

                            <div class="col-md-2 text-end">
                                <label for="pay_date">วันที่จ่าย  :</label>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input id="pay_date" type="date"
                                        class="form-control form-control-sm" name="pay_date" value="{{ $date }}">
                                </div>
                            </div>
                            
                            
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-2 text-end">
                                <label for="pay_payuser_id">ผู้จ่าย :</label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"> 
                                        <select id="pay_payuser_id" name="pay_payuser_id"
                                        class="form-select form-select-lg" style="width: 100%">
                                        <option value="">--เลือก--</option>
                                        @foreach ($users as $ue)
                                            @if ($ue->id == $iduser)
                                                <option value="{{ $ue->id }}" selected> {{ $ue->fname }}  {{ $ue->lname }} </option>
                                            @else
                                                <option value="{{ $ue->id }}"> {{ $ue->fname }}  {{ $ue->lname }} </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 text-end">
                                <label for="pay_user_id">ผู้รับ :</label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select id="pay_user_id" name="pay_user_id"
                                        class="form-select form-select-lg" style="width: 100%">
                                        <option value="">--เลือก--</option>
                                        @foreach ($users as $ue)                                               
                                            <option value="{{ $ue->id }}"> {{ $ue->fname }}  {{ $ue->lname }} </option>                                             
                                        @endforeach
                                    </select>                                    
                                </div>
                            </div>
                           
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-2 text-end">
                                <label for="payout_inven_id">จ่ายจากคลัง :</label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group"> 
                                    <select id="payout_inven_id" name="payout_inven_id"
                                    class="form-select form-select-lg" style="width: 100%">
                                    <option value="">--เลือก--</option>
                                    @foreach ($warehouse_inven as $inven)
                                        <option value="{{ $inven->warehouse_inven_id }}">
                                            {{ $inven->warehouse_inven_name }}
                                        </option>
                                    @endforeach 
                                </select> 
                                </div>
                            </div>
                            <div class="col-md-2 text-end">
                                <label for="payin_inven_id">รับเข้าคลัง :</label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select id="payin_inven_id" name="payin_inven_id"
                                    class="form-select form-select-lg" style="width: 100%">
                                    <option value="">--เลือก--</option>
                                    @foreach ($department_sub_sub as $dep)
                                        <option value="{{ $dep->DEPARTMENT_SUB_SUB_ID }}">
                                            {{ $dep->DEPARTMENT_SUB_SUB_NAME }}
                                        </option>
                                    @endforeach
                                </select>                  
                                </div>
                            </div> 
                        </div>

                    </div>
                    <input type="hidden" name="store_id" id="store_id" value="{{$storeid}}">

                    <div class="modal-footer">
                        <div class="col-md-12 text-end">
                            <div class="form-group">
                                <button type="button" id="Savebtn" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-floppy-disk me-2"></i>
                                    บันทึกข้อมูล
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i
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
                // $('select').select2();
                $('#warehouse_pay_year').select2({
                dropdownParent: $('#insertpaydata')
                });
                $('#warehouse_pay_repuser_id').select2({
                dropdownParent: $('#insertpaydata')
                });
                $('#warehouse_pay_fromuser_id').select2({
                dropdownParent: $('#insertpaydata')
                });
                $('#warehouse_pay_inven_id').select2({
                dropdownParent: $('#insertpaydata')
                });
                $('#warehouse_pay_frominven_id').select2({
                dropdownParent: $('#insertpaydata')
                });
  
                $('#warehouse_pay_year2').select2({
                    placeholder: "--เลือก--",
                    allowClear: true
                });

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#Savebtn').click(function() {
                    var pay_code = $('#pay_code').val();
                    // var warehouse_pay_no_bill = $('#warehouse_pay_no_bill').val();
                    var pay_year = $('#pay_year').val();
                    var pay_payuser_id = $('#pay_payuser_id').val();
                    var pay_user_id = $('#pay_user_id').val();
                    var pay_date = $('#pay_date').val();
                    var payin_inven_id = $('#payin_inven_id').val();
                    var payout_inven_id = $('#payout_inven_id').val();
                    var store_id = $('#store_id').val();
                    $.ajax({
                        url: "{{ route('pay.warehouse_paysave') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            pay_code,
                            // warehouse_pay_no_bill,
                            pay_year,
                            pay_payuser_id,
                            pay_user_id,
                            pay_date,
                            payout_inven_id,
                            payin_inven_id,
                            store_id
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
