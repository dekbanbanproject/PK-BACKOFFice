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
                                            <tr style="font-size:13px"> 
                                                <td class="text-center" width="10%" >{{ $item->article_num }} </td>
                                                <td class="p-2"> {{ $item->cctv_location }}</td>    
                                                <td class="text-center" width="7%"></td> 
                                                <td class="text-center" width="7%"> </td> 
                                                <td class="text-center" width="7%"> </td>
                                                <td class="text-center" width="7%"> </td>   
                                                <td class="text-center" width="7%"> </td>  
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
                // url:'{{route("d.nurse_index_editable")}}',
                dataType:"json",
                // editButton: true,
                removeButton: false,
                columns:{
                    identifier:[0,'ward'],
                    // editable:[[1,'group2'],[2,'fbillcode'],[3,'nbillcode'],[4,'dname'],[5,'pay_rate'],[6,'price'],[7,'price2'],[8,'price3']]
                    editable: [[4, 'np_a'], [8, 'np_b'], [12, 'np_c']]
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