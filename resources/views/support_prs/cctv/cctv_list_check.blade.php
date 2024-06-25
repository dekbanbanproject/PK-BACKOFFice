@extends('layouts.support_prs')
@section('title', 'PK-OFFICER || CCTV')

<style>
    .btn {
        font-size: 15px;
    }

    .bgc {
        background-color: #264886;
    }

    .bga {
        background-color: #fbff7d;
    }
</style>
<?php
use App\Http\Controllers\StaticController;
use Illuminate\Support\Facades\DB;
$count_land = StaticController::count_land();
$count_building = StaticController::count_building();
$count_article = StaticController::count_article();
?>


@section('content')
    <script>
        function TypeAdmin() {
            window.location.href = '{{ route('index') }}';
        }

        function addarticle(input) {
            var fileInput = document.getElementById('article_img');
            var url = input.value;
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#add_upload_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                alert('กรุณาอัพโหลดไฟล์ประเภทรูปภาพ .jpeg/.jpg/.png/.gif .');
                fileInput.value = '';
                return false;
            }
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

    date_default_timezone_set('Asia/Bangkok');
$date = date('Y') + 543;
$datefull = date('Y-m-d H:i:s');
$time = date("H:i:s");
$loter = $date.''.$time
    
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
   
    <div class="row"> 
        <div class="col-md-3">
            <h4 class="card-title" style="color:rgb(10, 151, 85)">CHECK CCTV</h4>
            <p class="card-title-desc" style="font-size: 15px;">บันทึกข้อมูลกล้องวงจรปิด</p>
        </div>
        <div class="col"></div>
         
    </div> 
   
        <div class="row">
            <div class="col-md-12">
                <div class="card card_prs_4">
                   
                    <div class="card-body">
                        <div class="card-body">
                            <div class="table-responsive">  
                                    <table id="Tabledit" class="table table-bordered border-primary table-hover table-sm" style="border-collapse: collapse;border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr style="font-size: 13px">                                        
                                            <th class="text-center" style="background: #fdf7e4">รหัสกล้อง</th>
                                            <th class="text-center" width="15%" style="background: #fdf7e4">สถานที่ติดตั้ง</th> 
                                            <th class="text-center" style="background: #e4fdfc">สถานะการตรวจเช็ค</th> 
                                            <th class="text-center" style="background: #e4fdfc">จอกล้อง</th> 
                                            <th class="text-center" style="background: #e4fdfc">มุมกล้อง</th>
                                            <th class="text-center" style="background: #e4fdfc">สิ่งกีดขวาง</th>
                                            <th class="text-center" style="background: #dadffa">การบันทึก</th> 
                                            <th class="text-center" style="background: #dadffa">การสำรองไฟ</th> 
                                        </tr>
                                       
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;
                                            $total1 = 0; $total2 = 0; $total3 = 0; $total4 = 0; $total5 = 0; $total6 = 0;
                                            $total7 = 0; $total8 = 0; $total9 = 0; $total10 = 0; $total11 = 0; $total12 = 0;
                                        ?>
                                        @foreach ($datashow as $item) 
                                        <?php
                                                $dashboard_ = DB::select(
                                                    'SELECT * FROM cctv_check WHERE article_num = "'.$item->cctv_list_num.'"  
                                                ');  
                                                // foreach ($dashboard_ as $key => $value) {
                                                //    $cctv_camera_screen       = $value->cctv_camera_screen;
                                                //    $cctv_camera_corner       = $value->cctv_camera_corner;
                                                //    $cctv_camera_drawback     = $value->cctv_camera_drawback;
                                                //    $cctv_camera_save         = $value->cctv_camera_save;
                                                //    $cctv_camera_power_backup = $value->cctv_camera_power_backup;
                                                // }
                                                // $dataedit = DB::table('cctv_check')->where('article_num', '=', $item->cctv_list_num)->first();
                                                //     $cctv_camera_screen       = $dataedit->cctv_camera_screen;
                                                //     $cctv_camera_corner       = $dataedit->cctv_camera_corner;
                                                //     $cctv_camera_drawback     = $dataedit->cctv_camera_drawback;
                                                //     $cctv_camera_save         = $dataedit->cctv_camera_save;
                                                //     $cctv_camera_power_backup = $dataedit->cctv_camera_power_backup;
                                        ?>
                                            <tr style="font-size:13px"> 
                                                <td class="text-center" width="7%" >{{ $item->cctv_list_num }} </td>
                                                <td class="p-2"> {{ $item->cctv_location }}</td>  
                                                <td class="text-center" width="7%">{{$item->cctv_check}}</td>  
                                                <td class="text-center" width="7%">{{$item->cctv_camera_screen}}</td> 
                                                <td class="text-center" width="7%">{{$item->cctv_camera_corner}}</td> 
                                                <td class="text-center" width="7%">{{$item->cctv_camera_drawback}}</td>
                                                <td class="text-center" width="7%">{{$item->cctv_camera_save}}</td>   
                                                <td class="text-center" width="7%">{{$item->cctv_camera_power_backup}}</td>  
                                          
                                               
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
          
    </div>
 
@endsection
@section('footer')
<script>
     $(document).ready(function () {
          $('#example').DataTable();
          $('#example2').DataTable();
          $('#example3').DataTable();
          $('#example4').DataTable();
          $('#example5').DataTable();  
          $('#table_id').DataTable();
         
          $('#building_userid').select2({
              placeholder:"--เลือก--",
              allowClear:true
          });
          $('#article_year').select2({
              placeholder:"--เลือก--",
              allowClear:true
          });
           
          $('#Tabledit').Tabledit({
                url:'{{route("tec.cctv_list_editcheck")}}',
                dataType:"json",
                // editButton: true,
                removeButton: false,
                columns:{
                    identifier:[0,'cctv_list_num'],
                    // editable:[[1,'group2'],[2,'fbillcode'],[3,'nbillcode'],[4,'dname'],[5,'pay_rate'],[6,'price'],[7,'price2'],[8,'price3'], [9, 'gender', '{"1":"Male", "2":"Female"}']]
                    editable: [[3, 'cctv_camera_screen', '{"0":"ปกติ", "1":"ชำรุด"}'], [4, 'cctv_camera_corner', '{"0":"ปกติ", "1":"ชำรุด"}'], [5, 'cctv_camera_drawback', '{"0":"ปกติ", "1":"ชำรุด"}'], [6, 'cctv_camera_save', '{"0":"ปกติ", "1":"ชำรุด"}'], [7, 'cctv_camera_power_backup', '{"0":"ปกติ", "1":"ชำรุด"}']]
                },
                // restoreButton:false,
                deleteButton: false,
                saveButton: false,
                autoFocus: false,
                buttons: {
                    edit: {
                        // class: 'btn-icon btn-shadow btn-dashed btn btn-outline-warning',
                        html: '<i class="fa-regular fa-pen-to-square text-danger"></i>',
                        action: 'Edit'
                    }
                },
                onSuccess:function(data,textStatus,jqXHR)
                {
                   
                    if (data.action == 'Edit') 
                    {
                        // $('#'+data.icode).remove();
                        window.location.reload();
                    }
                }

            });

            
          
      });
</script>
@endsection