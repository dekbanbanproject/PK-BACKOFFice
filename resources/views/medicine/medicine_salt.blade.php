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
                        <div class="col-md-2 text-center">
                            <div class="input-group" id="datepicker1">
                                <input type="text" class="form-control" name="startdate" id="datepicker"  data-date-container='#datepicker1'
                                    data-provide="datepicker" data-date-autoclose="true" data-date-language="th-th"
                                    value="{{ $start }}">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                {{-- <input id="startdate" name="startdate" class="form-control form-control-sm" type="date" value="{{$start}}"> --}}
                            </div>
                        </div>
                        <div class="col-md-1 text-center">ถึงวันที่</div>
                        <div class="col-md-2 text-center">
                            <div class="input-group" id="datepicker1">
                                {{-- <input id="enddate" name="enddate" class="form-control form-control-sm" type="date" value="{{$end}}">  --}}
                                <input type="text" class="form-control" name="enddate" id="datepicker2" data-date-container='#datepicker1'
                                    data-provide="datepicker" data-date-autoclose="true" data-date-language="th-th"
                                    value="{{ $end }}">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                            </div>
                        </div>
                         
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
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
                <div class="card">
                    <div class="card-header ">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>การลงข้อมูล ทับหม้อเกลือ บัตรทองในเขต 
                                    {{-- <br>  {{$output_show}} --}}
                                </h5>
                            </div>
                            <div class="col"></div> 
                        </div>
                    </div>
                    <div class="card-body shadow-lg">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-sm myTable" style="width: 100%;"> 
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
