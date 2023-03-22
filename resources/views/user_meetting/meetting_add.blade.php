@extends('layouts.user')
@section('title','ZOffice || ช้อมูลการจองห้องประชุม')
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
  <style>
    .btn{
       font-size:15px;
     }
  </style>
<div class="container-fluid" >
  <div class="px-0 py-0 mb-2">
    <div class="d-flex flex-wrap justify-content-center">  
      <a class="col-4 col-lg-auto mb-2 mb-lg-0 me-lg-auto text-white me-2"></a>
    
      <div class="text-end">
        {{-- <a href="{{url('user_meetting/meetting_dashboard')}}" class="btn btn-light btn-sm text-dark me-2">dashboard</a> --}}
        <a href="{{url('user_meetting/meetting_calenda')}}" class="btn btn-light btn-sm text-dark me-2">ปฎิทิน</a>
        <a href="{{url('user_meetting/meetting_index')}}" class="btn btn-light btn-sm text-dark me-2">ช้อมูลการจองห้องประชุม</a>
        <a href="{{url('user_meetting/meetting_add')}}" class="btn btn-info btn-sm text-white me-2">จองห้องประชุม</a> 
      </div>
    </div>
  </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg">
               
                <div class="card-body">  
                    <div class="row">
                          @foreach ( $building_level_room as $items )
                              <div class="col-md-3 mt-3">
                                    <div class="bg-image hover-overlay ripple">
                                          <a href="{{url('user_meetting/meetting_choose/'.$items->room_id)}}">
                                                <img src="{{asset('storage/meetting/'.$items->room_img)}}" height="450px" width="350px" alt="Image" class="img-thumbnail"> 
                                                <div class="mask" style="background-color: rgba(57, 192, 237, 0.2);"></div>
                                          </a>                                
                                    </div>
                              </div>
                          @endforeach                   
                    </div>                  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
