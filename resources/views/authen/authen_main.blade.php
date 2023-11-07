<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PK-AUTHEN</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('fontawesome/css/all.css') }}" rel="stylesheet">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/logo150.ico') }}">

    {{-- <link href="{{ asset('bt52/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" /> --}}
    <!-- Plugin css -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('pkclaim/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('pkclaim/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('pkclaim/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('disacc/styles/css/base.css') }}" rel="stylesheet">
 
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            font-size: 13px;
        }
        /* .label{
                font-size: 12px;
            } */
    </style>
</head>

<body>
    <?php
        $org = DB::connection('mysql')->select('SELECT * FROM orginfo WHERE orginfo_id = 1');
    ?>
 
    <div class="tabs-animation mt-3">
            <div class="row text-center">
                <div id="overlay">
                    <div class="cv-spinner">
                        <span class="spinner"></span>
                    </div>
                </div> 
            </div>
    
            @if ($smartcardcon == 'NO_CID')  
            <div class="row mt-2">
                <div class="col"></div>
                <div class="col-xl-4">
                    <img src="{{ asset('images/spsch.jpg') }}" alt="Image" class="img-thumbnail" width="auto;" height="auto;"> 
                </div>   
                <div class="col"></div>
            </div>                           
            @else  
            <div class="row mt-2">
                <div class="col"></div>
                <div class="col-xl-4">
                    <img src="{{ asset('images/spsch.jpg') }}" alt="Image" class="img-thumbnail" width="auto;" height="auto;"> 
                </div>   
                
                @if ($smartcard == 'NO_CONNECT')
                    
                @else
                    <div class="col-xl-1">   
                        <img class="ms-4" src="data:image/png;base64,{{ $collection12 }}" alt="">
                    </div>
                @endif     
                <div class="col"></div>
            </div>  
                
            @endif
               
            <div class="row mt-2">
                <div class="col"></div>
                <div class="col-xl-6">
                    <div class="card shadow-lg">
                        <div class="card-header">
                           
                             Authen Code
                            
                        </div>
                        <div class="card-body"> 
                                    @if ($smartcard == 'NO_CONNECT')
                                        <div class="row">
                                            <div class="col"></div>
                                            <div class="col-md-4 text-center">
                                                <img src="http://localhost:8189/assets/images/smartcard-connected.png" alt=""
                                                    width="320px"><br> <br>
                                                <label for="" class="form-label "
                                                    style="color: rgb(197, 8, 33);font-size:30px" >ไม่พบเครื่องอ่านบัตร</label>
                                                <br>
                                            </div>
                                            <div class="col"></div>
                                        </div>
                          
                                    @else
                                            @if ($smartcardcon == 'NO_CID')
                                                <div class="row mt-4">
                                                    <div class="col"></div>
                                                    <div class="col-md-4 text-center">
                                                        <img src="{{ asset('images/card1.jpg') }}" alt="Image"
                                                            class="img-thumbnail shadow-lg" width="320px">
                                                        <br><br>
                                                        <label for="pid" class="form-label"
                                                            style="color: rgb(197, 8, 33);font-size:24px">กรุณาเสียบบัตรประชาชน</label>
                                                        <br>
                                                    </div>
                                                    <div class="col"></div>
                                                </div>
                                            @else
                                            
                                                <form action="{{ route('authencode') }}" method="POST" id="insert_AuthencodeForm">
                                                    @csrf

                                                    <div class="row mt-3">
                                                        <div class="col-md-3 text-end">
                                                            <div class="mb-2">
                                                                <label for="pid" class="form-label">เลขบัตรประชาชน :</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-2">
                                                                <label for="pid" class="form-label"
                                                                    style="color: rgb(197, 8, 33)">{{ $collection1 }}</label>
                                                                <input type="hidden" class="form-control" id="pid"
                                                                    name="pid" value="{{ $collection1 }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 text-end">
                                                            <div class="mb-2">
                                                                <label for="fname" class="form-label">ชื่อ-นามสกุล :</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="mb-2">
                                                                <label for="fname" class="form-label"
                                                                    style="color: rgb(197, 8, 33)">{{ $collection2 }}
                                                                    {{ $collection3 }}</label>
                                                                <input type="hidden" class="form-control" id="fname"
                                                                    value="{{ $collection2 }}">
                                                                <input type="hidden" class="form-control" id="lname"
                                                                    value="{{ $collection3 }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 text-end">
                                                            <div class="mb-2">
                                                                <label for="mainInscl" class="form-label">สิทธิหลัก :</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-2">
                                                                <label for="mainInscl" class="form-label"
                                                                    style="color: rgb(197, 8, 33)">{{ $collection6 }}</label>
                                                                <input type="hidden" class="form-control" id="mainInscl"
                                                                    value="{{ $collection6 }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 text-end">
                                                            <div class="mb-2">
                                                                <label for="birthDate" class="form-label">วันเกิด :</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="mb-2">
                                                                <label for="birthDate" class="form-label"
                                                                    style="color: rgb(197, 8, 33)">{{ $collection4 }}</label>
                                                                <input type="hidden" class="form-control" id="birthDate"
                                                                    value="{{ $collection4 }}">
                                                            </div>
                                                        </div> 
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-3 text-end">
                                                            <div class="mb-2">
                                                                <label for="subInscl" class="form-label">สิทธิ์ย่อย :</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-2">
                                                                <label for="subInscl" class="form-label"
                                                                    style="color: rgb(197, 8, 33)">{{ $collection7 }}</label>
                                                                <input type="hidden" class="form-control" id="subInscl"
                                                                    value="{{ $collection7 }}">
                                                            </div>
                                                        </div> 
                                                        <div class="col-md-2 text-end">
                                                            <div class="mb-2">
                                                                <label for="checkDate" class="form-label">อายุ :</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="mb-2">
                                                                <label for="checkDate" class="form-label"
                                                                    style="color: rgb(197, 8, 33)">{{ $collection8 }}</label>
                                                                <input type="hidden" class="form-control" id="checkDate"
                                                                    value="{{ $collection8 }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr> 
                                                    <div class="row">
                                                        <div class="col-md-3 text-end">
                                                            <div class="mb-2">
                                                                <label for="claimType" class="form-label">เลือกประเภทเข้ารับบริการ
                                                                    :</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-9 ">
                                                            <div class="mb-2">
                                                                <input class="form-check-input me-3" type="radio"
                                                                    name="claimType" id="claimType" value="PG0060001" checked>
                                                                <label class="form-check-label" for="claimType">
                                                                    เข้ารับบริการรักษาทั่วไป (OPD/ IPD/ PP)
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 text-end">
                                                            <div class="mb-2">
                                                                <label for="claimType2" class="form-label"> </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-9 ">
                                                            <div class="mb-2">
                                                                <input class="form-check-input me-3" type="radio"
                                                                    name="claimType" id="claimType2" value="PG0120001">
                                                                <label class="form-check-label" for="claimType2">
                                                                    UCEP PLUS (ผู้ป่วยกลุ่มอาการสีเหลืองและสีแดง)
                                                                </label>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 text-end">
                                                            <div class="mb-2">
                                                                <label for="claimType3" class="form-label"> </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-9 ">
                                                            <div class="mb-2">
                                                                <input class="form-check-input me-3" type="radio"
                                                                    name="claimType" id="claimType3" value="PG0130001">
                                                                <label class="form-check-label" for="claimType3">
                                                                    บริการฟอกเลือดด้วยเครื่องไตเทียม (HD)
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @foreach ($patient as $item)
                                                        <?php
                                                        $datacid = DB::connection('mysql10')
                                                            ->table('patient')
                                                            ->where('cid', '=', $collection1)
                                                            ->first();
                                                        if ($datacid->hometel == null) {
                                                            $cid = '';
                                                            $hn = '';
                                                        } else {
                                                            $cid   = $datacid->hometel;
                                                            $hn    = $datacid->hn;
                                                        }
                                                        ?>
                                                    @endforeach
                                                    <div class="row">
                                                        <div class="col-md-3 text-end">
                                                            <div class="mb-2">
                                                                <label for="mobile" class="form-label">ยืนยันเบอร์โทรศัพท์
                                                                    :</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="mb-2">
                                                                @if ($cid == '')
                                                                    <input type="text" class="form-control" id="mobile" name="mobile" required style="background-color: rgb(252, 163, 157)">
                                                                @else
                                                                    <input type="text" class="form-control" id="mobile" name="mobile" value="{{ $cid }}" style="background-color: aquamarine">
                                                                @endif 
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 text-end">
                                                            <div class="mb-2">
                                                                <label for="mobile" class="form-label">HN
                                                                    :</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="mb-2">
                                                                @if ($hn == '')
                                                                    <input type="text" class="form-control" id="hn" name="hn" style="background-color: rgb(252, 163, 157)">
                                                                @else
                                                                    <input type="text" class="form-control" id="hn" name="hn" value="{{ $hn }}" style="background-color: aquamarine">
                                                                @endif 
                                                            </div>
                                                        </div>
                                                        <div class="col"></div>
                                                      
                                                    </div>
                                                    <hr>

                                                    <div class="row">
                                                        <div class="col-md-1">vn</div>
                                                        <div class="col-md-2"> <input type="text" class="form-control" id="vn" name="vn" value="{{ $vn }}"></div>
                                                        <div class="col-md-1">hos_guid</div>
                                                        <div class="col-md-4"> <input type="text" class="form-control" id="hos_guid" name="hos_guid" value="{{ $hos_guid }}"></div>
                                                        <div class="col-md-2">
                                                            <select name="ovstist" id="ovstist">
                                                                @foreach ($ovstist as $item)
                                                                <option value="{{$item->ovstist}}">{{$item->name}}</option>
                                                                @endforeach                                                                
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="spclty" id="spclty">
                                                                @foreach ($spclty as $item2)
                                                                <option value="{{$item2->spclty}}">{{$item2->name}}</option>
                                                                @endforeach                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-1">hcode</div>
                                                        <div class="col-md-2"> <input type="text" class="form-control" id="hcode" name="hcode" value="{{ $hcode }}"></div>
                                                        <div class="col-md-1">hos_guid</div>
                                                        <div class="col-md-4"> <input type="text" class="form-control" id="hos_guid" name="hos_guid" value="{{ $hos_guid }}"></div>
                                                        <div class="col-md-2">
                                                            {{-- <select name="ovstist" id="ovstist">
                                                                @foreach ($ovstist as $item)
                                                                <option value="{{$item->ovstist}}">{{$item->name}}</option>
                                                                @endforeach                                                                
                                                            </select> --}}
                                                        </div>
                                                        <div class="col-md-2">
                                                            {{-- <select name="spclty" id="spclty">
                                                                @foreach ($spclty as $item2)
                                                                <option value="{{$item2->spclty}}">{{$item2->name}}</option>
                                                                @endforeach                                                                
                                                            </select> --}}
                                                        </div>
                                                    </div>




                                                    <hr>
                                                    <div class="row">
                                                        <div class="col"></div>
                                                        <div class="col-md-8">
                                                            <button type="submit" class="btn-icon btn-shadow btn-dashed btn btn-outline-primary shadow-lg"><i
                                                                    class="fa-brands fa-medrt me-2"></i>Authen Code Only</button>
                                                            <button type="button" class="btn-icon btn-shadow btn-dashed btn btn-outline-success shadow-lg"><i class="fa-brands fa-medrt me-2"></i>Authen Code + Visit</button>
                                                            <a href="{{ url('/login') }}" class="btn-icon btn-shadow btn-dashed btn btn-outline-danger shadow-lg"><i
                                                                    class="fa-solid fa-circle-arrow-left me-2"></i>ย้อนกลับ</a>

                                                        </div>
                                                       
                                                    </div>

                                                </form>

                                            @endif
                                
                                    @endif

                        </div>
                        </form>

                    </div>
                </div>
                <div class="col"></div>
            </div>
          
            <div class="row">
                <div class="col"></div>
                <div class="col-sm-3"> 
                    @foreach ($org as $item)   
                            <h6 style="color:rgb(220, 134, 247)" class="mt-2">
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> 
                                © {{$item->orginfo_name}}
                            </h6>  
                    @endforeach
                </div>
                <div class="col-sm-3">
                    <div class="text-sm-end d-none d-sm-block">
                        Created with <i class="mdi mdi-heart text-danger"></i> by ทีมพัฒนา
                        
                         <h6 style="color:rgb(220, 134, 247)" class="mt-2"> PK-HOS</h6> 
                    </div>
                </div>
                <div class="col"></div>
            </div>



    </div>
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                $('#insert_AuthencodeForm').on('submit', function(e) {
                    e.preventDefault();
                    var form = this;
                    $.ajax({
                        url: $(form).attr('action'),
                        method: $(form).attr('method'),
                        data: new FormData(form),
                        processData: false,
                        dataType: 'json',
                        contentType: false,
                        beforeSend: function() {
                            $(form).find('span.error-text').text('');
                        },
                        success: function(data) {
                            if (data.status == 0) {

                            } else {
                                Swal.fire({
                                    title: 'ออก Authen Code สำเร็จ',
                                    text: "You Get Authen Code success",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#06D177',
                                    // cancelButtonColor: '#d33',
                                    confirmButtonText: 'เรียบร้อย'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // window.location.reload();  
                                        window.location = "{{ url('authen_main') }}";
                                    }
                                })
                            }
                        }
                    });
                });

            });
        </script>

</body>

</html>
