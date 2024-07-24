@extends('layouts.support_prs_airback')
@section('title', 'PK-OFFICER || Air-Service')

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
        $ynow = date('Y') + 543;
        $yb = date('Y') + 542;
    ?>

<div class="tabs-animation">
 
    <div id="preloader">
        <div id="status">
            <div id="container_spin">
                <svg viewBox="0 0 100 100">
                    <defs>
                        <filter id="shadow">
                        <feDropShadow dx="0" dy="0" stdDeviation="2.5" 
                            flood-color="#fc6767"/>
                        </filter>
                    </defs>
                    <circle id="spinner" style="fill:transparent;stroke:#dd2476;stroke-width: 7px;stroke-linecap: round;filter:url(#shadow);" cx="50" cy="50" r="45"/>
                </svg>
            </div>
        </div>
    </div>
    <form action="{{ url('air_setting') }}" method="GET">
        @csrf
        <div class="row"> 
            <div class="col-md-6">
                <h4 style="color:rgb(10, 151, 85)">Import Data Plan</h4> 
            </div>
             
            <div class="col"></div>                   
            <div class="col-md-2"> 
                <select name="air_plan_month_id" id="air_plan_month_id" class="form-control inputmedsalt text-center bt_prs" style="width: 100%;font-size:13px">
                    @foreach ($years_show as $item_y) 
                            <option value="{{$item_y->air_plan_month_id}}">{{$item_y->air_plan_name}} {{$item_y->air_plan_year}} ปีงบประมาณ {{$item_y->years}}</option> 
                    @endforeach
                </select>
            </div>                     
            <div class="col-md-2 text-start">  
                <button type="submit" class="ladda-button btn-pill btn btn-primary bt_prs me-2" data-style="expand-left">
                    <span class="ladda-label"> <i class="fa-solid fa-magnifying-glass text-white me-2"></i>ค้นหา</span>
                    <span class="ladda-spinner"></span>
                </button>  
                <button type="button" class="ladda-button btn-pill btn btn-info bt_prs" data-bs-toggle="modal" target="_blank" data-bs-target="#exampleModal">   
                    <i class="fas fa-file-excel text-white me-2" style="font-size:13px"></i>
                    <span>Import</span> 
                </button>  
            </div>   
        </div>  
    </form>
 
        <div class="row mt-3">
            <div class="col-xl-12">
                <div class="card card_prs_4">
                    <div class="card-body">  
                       
                            <p class="mb-0">
                                <div class="table-responsive">
                                   
                                    <table id="example" class="table table-hover table-sm" style=" border-spacing: 0; width: 100%;">                       
                                        <thead>                             
                                                <tr style="font-size:13px"> 
                                                    <th class="text-center" width="5%">ลำดับ</th>  
                                                    <th class="text-center" width="10%">ปีงบประมาณ</th> 
                                                    <th class="text-center" width="12%">เดือน</th> 
                                                    <th class="text-center">รายการเครื่องปรับอากาศ</th>   
                                                    <th class="text-center" width="15%">แผนการบำรุงรักษา</th>   
                                                    {{-- <th class="text-center" width="10%">active</th>   --}}
                                                </tr>  
                                        </thead>
                                        <tbody>
                                            <?php $i = 0; ?>
                                            @foreach ($datashow as $item) 
                                            <?php $i++  ?> 
                                                <tr id="sid{{ $item->air_plan_month_id }}">                                                  
                                                    <td class="text-center" style="font-size:13px;width: 5%;color: rgb(13, 134, 185)">{{$i}}</td>
                                                    <td class="text-center" style="font-size:14px;color: rgb(2, 95, 182)" width="10%">{{$item->years}}</td> 
                                                    <td class="text-center" style="font-size:14px;color: rgb(2, 95, 182)" width="12%">{{$item->air_plan_name}}</td> 
                                                    <td class="text-start" style="font-size:13px;color: rgb(112, 5, 98)" > {{$item->air_list_num}}</td>
                                                    <td class="text-start" style="font-size:13px;color: rgb(253, 65, 81)" width="15%">{{$item->air_repaire_typename}} </td>
                                                    {{-- <td class="text-center" style="font-size:13px;color: rgb(252, 90, 203)" width="10%">
                                                        @if($item->active == 'Y' )
                                                        <input type="checkbox" id="{{ $item->air_plan_month_id }}" name="{{ $item->air_plan_month_id }}" switch="none" onchange="switchactive({{ $item->air_plan_month_id }});" checked />
                                                        @else
                                                        <input type="checkbox" id="{{ $item->air_plan_month_id }}" name="{{ $item->air_plan_month_id }}" switch="none" onchange="switchactive({{ $item->air_plan_month_id }});" />
                                                        @endif
                                                        <label for="{{ $item-> air_plan_month_id }}" data-on-label="On" data-off-label="Off"></label>
                                                    </td>  --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </p>
                  
                    </div>
                </div>
            </div>
        </div> 

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"> 
            <div class="modal-dialog">
                <div class="modal-content"> 
                    <div class="modal-body">
                        <form method="POST" action="{{ route('prs.air_setting_importplan') }}" id="insert_Form" enctype="multipart/form-data">
                            @csrf
                            <br>                            
                                <div class="container"> 
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{-- <img src="{{ asset('assets/images/default-image.jpg') }}" id="add_upload_preview" alt="Image" class="img-thumbnail" width="450px" height="380px"> --}}
                                                <i class="fas fa-7x fa-file-excel text-white me-2"></i>
                                                <br>
                                                <div class="input-group mt-3"> 
                                                    <input type="file" class="form-control" id="img" name="img" onchange="addimg(this)">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">  
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="row mt-4">
                                        <div class="col"></div>
                                        <div class="col-md-2 text-end">ชื่อ</div>
                                        <div class="col-md-9"> 
                                            <input type="text" class="form-control" name="document_name" id="document_name" required>   
                                        </div> 
                                        <div class="col"></div>
                                    </div> --}}
                                    {{-- <div class="row mt-3">
                                        <div class="col"></div>
                                        <div class="col-md-2 text-end">กองทุน</div>
                                        <div class="col-md-9"> 
                                            <input type="text" class="form-control" name="hip_code" id="hip_code">   
                                        </div> 
                                        <div class="col"></div>
                                    </div> --}}
                                    {{-- <div class="row mt-3">
                                        <div class="col"></div> 
                                        <div class="col-md-3 text-end"></div>
                                        <div class="col-md-6"> 
                                                <button type="submit" class="ladda-button btn-pill btn btn-info">
                                                    <i class="fa-solid fa-floppy-disk me-2"></i>
                                                    บันทึกข้อมูล
                                                </button> 
                                        </div> 
                                        <div class="col"></div>
                                    </div>  --}}
                                </div>   
                            <br> 
                        </form>   
                    </div>         
                </div>
            </div>
        </div>


    </div>
</div>

 
@endsection
@section('footer')
<script>
    function switchactive(idfunc){
            // var nameVar = document.getElementById("name").value;
            var checkBox = document.getElementById(idfunc);
            var onoff;
            
            if (checkBox.checked == true){
                onoff = "TRUE";
            } else {
                onoff = "FALSE";
            }
 
            var _token=$('input[name="_token"]').val();
                // $.ajax({
                        // url:"{{route('env.env_water_parameter_switchactive')}}",
                        // method:"GET",
                        // data:{onoff:onoff,idfunc:idfunc,_token:_token}
                // })
       }
</script>
    <script>
        $(document).ready(function() {
                  
            $('#insert_Form').on('submit',function(e){
                  e.preventDefault();
              
                  var form = this;
                    //   alert('OJJJJOL');
                  $.ajax({
                    url:$(form).attr('action'),
                    method:$(form).attr('method'),
                    data:new FormData(form),
                    processData:false,
                    dataType:'json',
                    contentType:false,
                    beforeSend:function(){
                      $(form).find('span.error-text').text('');
                    },
                    success:function(data){
                      if (data.status == 0 ) {
                        
                      } else {          
                        Swal.fire({
                            position: "top-end",
                          position: "top-end",
                          title: 'นำเข้าข้อมูลสำเร็จ',
                          text: "You Import data success",
                          icon: 'success',
                          showCancelButton: false,
                          confirmButtonColor: '#06D177',
                          // cancelButtonColor: '#d33',
                          confirmButtonText: 'เรียบร้อย'
                        }).then((result) => {
                          if (result.isConfirmed) {         
                            window.location.reload();  
                            // window.location="{{url('air_main')}}"; 
                          }
                        })      
                      }
                    }
                  });
            });

        });
    </script>

@endsection
