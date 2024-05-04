<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' /> 
    <link href='https://fonts.googleapis.com/css?family=Kanit&subset=thai,latin' rel='stylesheet' type='text/css'>
     <!-- Bootstrap Css -->
     <link href="{{ asset('pkclaim/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" /> 
     <!-- App Css-->
     <link href="{{ asset('pkclaim/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<style>
    body {
        font-family: 'Kanit', sans-serif;
        font-size: 14px;   
        }
</style>
<?php

    use SimpleSoftwareIO\QrCode\Facades\QrCode;

    ?>
    {{-- <body onload="window.print()"> --}}
<body>
    <div class="row mt-2">
        <div class="col-md-3">
            <table> 
                <thead>
                    <tr>          
                        <th width="3%" class="text-center">ลำดับ</th>  
                        <th class="text-center" width="7%">วันที่</th> 
                        <th class="text-center" >รหัส-รายการ</th>  
                        <th class="text-center" width="7%">สายฉีด</th>  
                        <th class="text-center" width="7%">คันบังคับ</th> 
                        <th class="text-center" width="7%">ตัวถัง</th> 
                        <th class="text-center" width="7%">เกจความดัน</th>  
                        <th class="text-center" width="7%">สิ่งกีดขวาง</th> 
                        <th class="text-center" width="10%">ผู้ตรวจ</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($data_detail as $item) 
                        <tr>                                                  
                            <td class="text-center" width="3%">{{ $i++ }}</td>  
                           
                            <td class="text-center" width="7%">{{ $item->check_date }}</td>  
                            <td class="p-2">{{ $item->fire_num }}-{{ $item->fire_name }}</td> 
                            <td class="text-center" width="7%"> 
                                @if ($item->fire_check_injection == '0')
                                    <span class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-success">ปกติ</span> 
                                @else
                                    <span class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-danger">ชำรุด</span>
                                @endif
                            </td> 
                            <td class="text-center" width="7%"> 
                                @if ($item->fire_check_joystick == '0')
                                    <span class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-success">ปกติ</span> 
                                @else
                                    <span class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-danger">ชำรุด</span>
                                @endif
                            </td> 
                            <td class="text-center" width="7%"> 
                                @if ($item->fire_check_body == '0')
                                    <span class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-success">ปกติ</span> 
                                @else
                                    <span class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-danger">ชำรุด</span>
                                @endif
                            </td> 
                            <td class="text-center" width="7%"> 
                                @if ($item->fire_check_gauge == '0')
                                    <span class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-success">ปกติ</span> 
                                @else
                                    <span class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-danger">ชำรุด</span>
                                @endif
                            </td> 
                            <td class="text-center" width="7%"> 
                                @if ($item->fire_check_drawback == '0')
                                <span class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-success">ปกติ</span> 
                                @else
                                    <span class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-danger">ชำรุด</span>
                                @endif
                            </td> 
                        </tr>
                    </tbody>
            </table> 
        </div>
        
         
    </div>

     
    
     
     
     <!-- JAVASCRIPT -->
     <script src="{{ asset('pkclaim/libs/jquery/jquery.min.js') }}"></script>

     <script src="{{ asset('pkclaim/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script> 
</body>
        
                     
