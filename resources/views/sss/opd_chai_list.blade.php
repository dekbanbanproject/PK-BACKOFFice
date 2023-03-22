@extends('layouts.pkclaim')
@section('title', 'PK-BACKOFFice || ประกันสังคม')
@section('content')
<script>
    function TypeAdmin() {
        window.location.href = '{{ route('index') }}';
    }
    
</script>
    <style>
        .table th {
            font-family: sans-serif;
            font-size: 12px;
        }

        .table td {
            font-family: sans-serif;
            font-size: 12px;
        }
    </style>
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
            <form action="{{ route('sss.opd_chai_list') }}" method="POST" >
                @csrf
            <div class="row">                   
                    <div class="col"></div>
                    <div class="col-md-1 text-end">ประเภท </div>
                    <div class="col-md-2 text-center"> 
                        <select id="typesick" name="typesick" class="form-control" style="width: 100%" required>
                            <option value="">--เลือกประเภท--</option>
                            @if ($typesick == 'OPD')
                                <option value="OPD" selected>--OPD--</option>
                                <option value="IPD">--IPD--</option>
                            @else
                                <option value="OPD">--OPD--</option>
                                <option value="IPD" selected>--IPD--</option>
                            @endif
                            
                        </select>
                    </div>
                    <div class="col-md-1 text-end">วันที่</div>
                    <div class="col-md-2 text-center"> 
                        <div class="input-group" id="datepicker1">
                            <input type="text" class="form-control" placeholder="yyyy-mm-dd" name="startdate" id="startdate"
                                data-date-format="yyyy-mm-dd" data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" value="{{$startdate}}">

                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        </div>  
                    </div>
                    <div class="col-md-1 text-center">ถึงวันที่</div>
                    <div class="col-md-2 text-center"> 
                        <div class="input-group" id="datepicker1">
                            <input type="text" class="form-control" placeholder="yyyy-mm-dd" name="enddate" id="enddate"
                                data-date-format="yyyy-mm-dd" data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" value="{{$enddate}}">

                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                        </div> 
                    </div>
                    <div class="col-md-2"> 
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-magnifying-gla
                            ss me-2"></i>
                            ค้นหา
                        </button>
                    </div>
                    <div class="col"></div>
                </form>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <h5 class="mb-sm-0">ปกส.ชัยภูมิ {{$typesick}} อุปกรณ์</h5>
        <div class="col-xl-12 mt-2">
            <div class="card">
                <div class="card-body py-0 px-2 mt-2">
                    <div class="table-responsive"> 
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">ลำดับ</th>
                                    <th class="text-center">ปี</th>
                                    <th class="text-center">เดือน</th>
                                    {{-- <th class="text-center">ผู้ป่วย(คน)</th> --}}
                                    <th class="text-center">ผู้ป่วย(ครั้ง)</th>
                                    <th class="text-center">เรียกเก็บ</th>
                                    <th class="text-center">ไม่ได้เรียกเก็บ</th>
                                    <th class="text-center">จำนวนเงิน</th> 
                                </tr>
                            </thead>
                            @if ($typesick =='OPD')
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($datashow as $item)                                            
                                            <tr>
                                                <td>{{$i++ }}</td>
                                                <td>{{$item->year}}</td> 

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
 
                                                <td>
                                                    <a href="{{url('opd_chai_listvn/'.$item->months.'/'.$startdate.'/'.$enddate)}}" target="_blank">{{ $item->vn }}</a>  
                                                </td>                                            
                                                <td class="text-center" >
                                                    <a href="{{url('opd_chai_listrep/'.$item->months.'/'.$startdate.'/'.$enddate)}}" target="_blank">{{ $item->repvn }}</a>
                                                   
                                                </td>
                                                <td class="text-center" >
                                                    <a href="{{url('opd_chai_listrep/'.$item->months.'/'.$startdate.'/'.$enddate)}}" target="_blank">{{ $item->norep }}</a>
                                                   
                                                </td>   
                                                
                                                <td class="text-center">{{ $item->summony }}</td>  
                                            </tr>
                                    @endforeach
                                </tbody>
                            @else
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($datashow as $item)                                            
                                        <tr>
                                            <td>{{$i++ }}</td>
                                            <td>{{$item->year}}</td> 

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

                                            <td> 
                                                <a href="{{url('ipd_chai_vn/'.$item->months.'/'.$startdate.'/'.$enddate)}}" target="_blank">{{ $item->icode }}</a> 
                                            </td> 
                                            <td>
                                                <a href="{{url('ipd_chai_rep/'.$item->months.'/'.$startdate.'/'.$enddate)}}" target="_blank">{{ $item->vn }}</a>  
                                             </td>                                            
                                            <td class="text-center" >
                                                {{-- <a href="{{url('ipd_chai_norep/'.$item->months.'/'.$startdate.'/'.$enddate)}}" target="_blank">{{ $item->disvn }}</a>  --}}
                                                {{ $item->disvn }}
                                            </td>   
                                            <td class="text-center">{{ $item->summony }}</td>  
                                        </tr>
                                @endforeach
                            </tbody>
                            @endif
                           
                        </table>
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

            $('select').select2();
            $('#ECLAIM_STATUS').select2({
                dropdownParent: $('#detailclaim')
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });
    </script>

@endsection
