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
        
        <div class="row mt-3">
          
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header ">
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
                                        <th class="text-center">HN</th>
                                        <th class="text-center">AN</th>
                                        <th class="text-center">cid</th>
                                        <th class="text-center">วันคลอด</th>
                                        <th class="text-center">ประเภทการคลอด</th>
                                        <th class="text-center">วันคลอด บัญชี2</th>
                                        <th class="text-center">วันที่รับบริการ</th>
                                        <th class="text-center">ชื่อ - สกุล</th>
                                        <th class="text-center">สิทธิ</th>
                                        <th class="text-center">ที่อยู่</th>
                                        <th class="text-center">รพ.สต.ตามบัตรทอง</th>
                                        <th class="text-center">เบอร์โทร</th>     
                                        <th class="text-center">หัตถการ 9007712,9007713,9007714,9007716,9007730</th>                                                
                                    </tr>                                            
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($datashow as $item)
                                        <tr>
                                            <td class="text-center">{{$i++}}</td> 
                                            <td class="text-center">
                                                <a href="{{ url('medicine_salt_subhn/'.$item->hn) }}" target="_blank">{{ $item->hn }}</a>  
                                            </td>
                                            <td class="text-center">{{$item->an}}</td>
                                            <td class="text-center">{{$item->cid}}</td>
                                            <td class="text-center">{{$item->labor_date}}</td>
                                            <td class="text-center">{{$item->deliver_name}}</td>
                                            <td class="text-center">{{$item->dlabor_date}}</td>
                                            <td class="text-center">{{$item->service_date}}</td>
                                            <td class="p-2">{{$item->fullname}}</td>
                                            <td class="text-center">{{$item->pttype}}</td>
                                            <td class="p-2">{{$item->fulladdressname}}</td>
                                            <td class="p-2">{{$item->hname}}</td>
                                            <td class="p-2">{{$item->informtel}}</td>
                                            <td class="p-2">{{$item->icd10tm}}</td>
                                           
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
