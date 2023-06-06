@extends('layouts.plannew')
@section('title','PK-BACKOFFice || Plan')
@section('content')
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
     use App\Http\Controllers\karnController;
     use Illuminate\Support\Facades\DB; 
 ?>
  <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">                    
                <div class="row">                   
                    <div class="col"><h5 class="mb-sm-0">plan_development </h5></div> 
                    <div class="col-md-2 text-center"></div> 
                    <div class="col-md-2 text-center"></div> 
                    <div class="col"></div> 
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-xl-12">
                <div class="card">                     
                    <div class="card-body py-0 px-2 mt-2"> 
                        <div class="table-responsive">
                            <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="example">
                                <thead>                                           
                                    <tr>
                                        <th width="5%" class="text-center">ลำดับ</th>
                                        <th class="text-center">วันที่</th>
                                        <th class="text-center">จำนวนผู้ป่วย(ครั้ง)</th>                                                    
                                    </tr>                                            
                                </thead>
                                {{-- <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($data_prb as $item)
                                        <tr>
                                            <td class="text-center">{{$i++}}</td> 
                                            <td class="text-center">{{DateThai($item->vstdate)}}</td> 
                                            <td class="text-center">
                                                <a href="{{url('prb_opd_subsub/'.$item->vstdate.'/'.$months.'/'.$startdate.'/'.$enddate)}}" target="_blank">{{ $item->countvn }}</a> 
                                            </td>  
                                        </tr>
                                    @endforeach
                                </tbody> --}}
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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                              
            });
           
        </script>    
       
    @endsection
