@extends('layouts.medicine')
@section('title', 'PK-BACKOFFice || แพทย์แผนไทย')

     <?php
     use App\Http\Controllers\StaticController;
     use Illuminate\Support\Facades\DB;   
     $count_meettingroom = StaticController::count_meettingroom();
 ?>


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
    } else {
        echo "<body onload=\"TypeAdmin()\"></body>";
        exit();
    }
    $url = Request::url();
    $pos = strrpos($url, '/') + 1;
    ?>
    <div class="container-fluid">
        <div class="row"> 
            <div class="col-xl-12">
                <form action="{{ route('me.medicine_saltsearch') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col"></div>
                        <div class="col-md-1 text-end">วันที่</div>
                        <div class="col-md-4 text-center">
                            <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker1'>
                                <input type="text" class="form-control" name="startdate" id="datepicker" placeholder="Start Date" data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                                    data-date-language="th-th" value="{{ $start }}" required/>
                                <input type="text" class="form-control" name="enddate" placeholder="End Date" id="datepicker2" data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                                    data-date-language="th-th" value="{{ $end }}"/>  
                            </div> 

                            {{-- <div class="input-group" id="datepicker1">
                                <input type="text" class="form-control" name="startdate" id="datepicker"  data-date-container='#datepicker1'
                                    data-provide="datepicker" data-date-autoclose="true" data-date-language="th-th"
                                    value="{{ $start }}">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span> 
                            </div> --}}
                        </div>
                        {{-- <div class="col-md-1 text-center">ถึงวันที่</div>
                        <div class="col-md-2 text-center">
                            <div class="input-group" id="datepicker1"> 
                                <input type="text" class="form-control" name="enddate" id="datepicker2" data-date-container='#datepicker1'
                                    data-provide="datepicker" data-date-autoclose="true" data-date-language="th-th"
                                    value="{{ $end }}">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div> --}}
                         
                        <div class="col-md-2">
                            <button type="submit" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info">
                                <i class="fa-solid fa-magnifying-glass me-2"></i>
                                ค้นหา
                            </button>
                        </div>
                        <div class="col"></div>
                </form>
            </div>

        </div>
        <div class="row mt-3">
            <div class="col"></div>
            <div class="col-md-7">
                <div class="main-card mb-3 card">
                    {{-- <div class="card-header ">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>การลงข้อมูล ทับหม้อเกลือ บัตรทองในเขต </h5>
                            </div> 
                        </div>
                    </div> --}}
                    <div class="card-header">
                        การลงข้อมูล ทับหม้อเกลือ บัตรทองในเขต  
                        <div class="btn-actions-pane-right">
                                <!-- Button trigger modal -->
                                <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Launch demo modal
                                </button> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">ลำดับ</th>
                                    <th class="text-center">ปี</th>
                                    <th class="text-center">เดือน</th>
                                    <th class="text-center">จำนวนผู้ป่วย(ครั้ง)</th>  
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                    @foreach ($datashow as $item)
                                        <tr>
                                            <td class="text-center">{{$i++}}</td> 
                                            <td class="text-center">{{$item->years}}</td>

                                            @if ($item->months == '1')
                                            <td width="15%" class="text-center">มกราคม</td>
                                            @elseif ($item->months == '2')
                                                <td width="15%" class="text-center">กุมภาพันธ์</td>
                                            @elseif ($item->months == '3')
                                                <td width="15%" class="text-center">มีนาคม</td>
                                            @elseif ($item->months == '4')
                                                <td width="15%" class="text-center">เมษายน</td>
                                            @elseif ($item->months == '5')
                                                <td width="15%" class="text-center">พฤษภาคม</td>
                                            @elseif ($item->months == '6')
                                                <td width="15%" class="text-center">มิถุนายน</td>
                                            @elseif ($item->months == '7')
                                                <td width="15%" class="text-center">กรกฎาคม</td>
                                            @elseif ($item->months == '8')
                                                <td width="15%" class="text-center">สิงหาคม</td>
                                            @elseif ($item->months == '9')
                                                <td width="15%" class="text-center">กันยายน</td>
                                            @elseif ($item->months == '10')
                                                <td width="15%" class="text-center">ตุลาคม</td>
                                            @elseif ($item->months == '11')
                                                <td width="15%" class="text-center">พฤษจิกายน</td>
                                            @else
                                            <td width="15%" class="text-center">ธันวาคม</td>
                                            @endif

                                            <td class="text-center">
                                                <a href="{{ url('medicine_salt_sub/'.$item->months.'/'.$start.'/'.$end) }}" target="_blank">{{ $item->countan }}</a> 
                                            </td>  
                                        </tr>
                                    @endforeach
    
                            </tbody>
                        </table>
                            
                    </div>
                </div>
            </div>
            <div class="col"></div>
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
                $('#ECLAIM_STATUS').select2({
                    dropdownParent: $('#detailclaim')
                });
    
                $('#users_group_id').select2({
                    placeholder: "--เลือก-- ",
                    allowClear: true
                });
    
                $('#datepicker').datepicker({
                    format: 'yyyy-mm-dd'
                });
                $('#datepicker2').datepicker({
                    format: 'yyyy-mm-dd'
                });
    
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
    
                $(document).on('click', '#printBtn', function() {
                    var month_id = $(this).val();
                    alert(month_id);
                    
                });
     
               
            });
        </script>
    
    @endsection
