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
<body onload="window.print()"> 
    {{-- <body>  --}}
        <div class="container">
            <div class="row mt-5">
                @foreach ($dataprint_main as $item) 
                <div class="col-md-2 text-center">
                    <div class="card">
                        <div class="body"><br>
                            
                            <!-- {!!QrCode::size(112)->generate(asset('http://smarthos-phukieohos.moph.go.th/pkbackoffice/public/fire_detail/'.$item->fire_id))!!}  -->
                            {!! QrCode::size(112)->style('round')->generate('http://smarthos-phukieohos.moph.go.th/pkbackoffice/public/fire_detail/'.$item->fire_num)!!}
                            {{-- {!!QrCode::size(112)->format('png')->merge('/public/images/logo150.png', .4)->generate('http://smarthos-phukieohos.moph.go.th/pkbackoffice/public/fire_detail/'.$item->fire_id)!!}  --}}
                            {{-- QrCode::size(112)->format('png')->merge('/public/img/logo.png', .4)->generate('https://www.binaryboxtuts.com/'); --}}
                            <p style="font-size: 16px"> รหัส {{ $item->fire_num }} <br>
                                แสกนดูผลตรวจสอบ</p> 

                                       {{-- {!!QrCode::size(112)->generate(" $item->fire_id ")!!}   --}}
                            {{-- {!!QrCode::size(112)->color(216, 86, 143)->generate('http://192.168.0.217/pkbackoffice/public/fire_detail/'.$item->fire_id);!!}  --}}
                            {{-- {!!QrCode::size(112)->generate(asset('http://smarthos-phukieohos.moph.go.th/pkbackoffice/public/fire_detail/'.$item->fire_id));!!}  --}}
                            {{-- {!!QrCode::size(112)->style('round')->generate('http://192.168.0.217/pkbackoffice/public/fire_detail/'.$item->fire_num);!!}  --}}
                            {{-- {!! QrCode::size(112)->encoding('UTF-8')->generate(asset('http://smarthos-phukieohos.moph.go.th/pkbackoffice/public/fire_detail/'.$item->fire_id));!!} --}}
                        </div> 
                    </div> 
                </div> 
                @endforeach

            </div>  
        </div>
      
     <!-- JAVASCRIPT -->
     <script src="{{ asset('pkclaim/libs/jquery/jquery.min.js') }}"></script>

     <script src="{{ asset('pkclaim/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script> 
</body>
        
                     
