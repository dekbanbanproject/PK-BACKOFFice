@extends('layouts.accountpk')
@section('title', 'PK-BACKOFFice || ACCOUNT')

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

    <style>
        #button {
            display: block;
            margin: 20px auto;
            padding: 30px 30px;
            background-color: #eee;
            border: solid #ccc 1px;
            cursor: pointer;
        }

        #overlay {
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.6);
        }

        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 250px;
            height: 250px;
            border: 10px #ddd solid;
            border-top: 10px #fd6812 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }

        @keyframes sp-anime {
            100% {
                transform: rotate(360deg);
            }
        }

        .is-hide {
            display: none;
        }
        .bar{
            height: 50px;
            background-color: rgb(10, 218, 55);
        }
        .percent{
            position: absolute;
            left: 50%;
            color: black;
        }       
        .card{
            border-radius: 3em 3em 3em 3em;
            /* box-shadow: 0 0 10px teal; */
        }
        .card-ucs{
            border-radius: 3em 3em 3em 3em;
            box-shadow: 0 0 10px rgb(3, 136, 252);
        }
        .card-ofc{
            border-radius: 3em 3em 3em 3em;
            box-shadow: 0 0 10px rgb(10, 110, 223);
        }
        .card-lgo{
            border-radius: 3em 3em 3em 3em;
            box-shadow: 0 0 10px teal;
        }
        .card-ucsti{
            border-radius: 3em 3em 3em 3em;
            box-shadow: 0 0 10px rgb(252, 144, 3);
        }
        .card-ofcti{
            border-radius: 3em 3em 3em 3em;
            box-shadow: 0 0 10px rgb(252, 3, 82);
        }
        .card-sssti{
            border-radius: 3em 3em 3em 3em;
            box-shadow: 0 0 10px rgb(94, 93, 93);
        }
        .card-lgoti{
            border-radius: 3em 3em 3em 3em;
            box-shadow: 0 0 10px teal;
        }
        .nav{
         
            border-radius: 3em 3em 20 20;
            background-color: aliceblue;
            /* box-shadow: 0 0 10px teal; */
        }
        .nav-link{
            border-radius: 20 20 10 10;
            box-shadow: 0 0 10px teal;
        }
    </style>
    <?php
    use App\Http\Controllers\StaticController;
    use Illuminate\Support\Facades\DB;
    $count_meettingroom = StaticController::count_meettingroom();
    ?>

        <div class="tabs-animation">
            <div id="preloader">
                <div id="status">
                    <div class="spinner">
    
                    </div>
                </div>
            </div>
            <div class="row text-center">
                <div id="overlay">
                    <div class="cv-spinner">
                        <span class="spinner"></span>
                    </div>
                </div>
        
            </div>

        <div class="row">

            <div class="col-md-12">
                {{-- <div class="card shadow-lg rounded-pill"> --}}
                    <div class="card-body ">

                        <h4 class="card-title">STM DETAIL GROUP</h4>
                        <p class="card-title-desc">รายการ stm ที่อัพโหลดแล้ว</p>
                        {{-- btn-shadow btn-dashed btn btn-outline-info bt mb-2 --}} 
                        {{-- <button type="button" class="btn btn-info btn-rounded waves-effect waves-light">Info</button> --}}
                        <div class="row">
                            <div class="col-md-1">
                                {{-- <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical"> --}}
                                <div class="nav flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link btn btn-info btn-rounded waves-effect waves-light mb-2 active" id="v-pills-ucs-tab" data-bs-toggle="pill" href="#v-pills-ucs" role="tab" aria-controls="v-pills-ucs" aria-selected="true">UCS</a>
                                <a class="nav-link btn btn-primary btn-rounded waves-effect waves-light mb-2" id="v-pills-ofc-tab" data-bs-toggle="pill" href="#v-pills-ofc" role="tab" aria-controls="v-pills-ofc" aria-selected="false">OFC</a>
                                <a class="nav-link btn btn-success btn-rounded waves-effect waves-light mb-2" id="v-pills-lgo-tab" data-bs-toggle="pill" href="#v-pills-lgo" role="tab" aria-controls="v-pills-lgo" aria-selected="false">LGO</a>
                                <a class="nav-link btn btn-warning btn-rounded waves-effect waves-light mb-2" id="v-pills-ucsti-tab" data-bs-toggle="pill" href="#v-pills-ucsti" role="tab" aria-controls="v-pills-ucsti" aria-selected="false">UCS ไต</a>
                                <a class="nav-link btn btn-danger btn-rounded waves-effect waves-light mb-2" id="v-pills-ofcti-tab" data-bs-toggle="pill" href="#v-pills-ofcti" role="tab" aria-controls="v-pills-ofcti" aria-selected="false">OFC ไต</a>
                                <a class="nav-link btn btn-secondary btn-rounded waves-effect waves-light mb-2" id="v-pills-sssti-tab" data-bs-toggle="pill" href="#v-pills-sssti" role="tab" aria-controls="v-pills-sssti" aria-selected="false">SSS ไต</a>
                                <a class="nav-link btn btn-success btn-rounded waves-effect waves-light mb-2" id="v-pills-lgoti-tab" data-bs-toggle="pill" href="#v-pills-lgoti" role="tab" aria-controls="v-pills-lgoti" aria-selected="false">LGO ไต</a>
                                {{-- <a class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a> --}}
                                </div>  
                            </div>
                            <div class="col-md-11">
                                <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-ucs" role="tabpanel" aria-labelledby="v-pills-ucs-tab">
                                        <div class="row"> 
                                            <div class="col-md-6">
                                                <div class="card p-4 card-ucs">
                                                    <h4 class="card-title" style="color:rgb(10, 151, 85)">STM DETAIL UCS OPD</h4>
                                                    <div class="table-responsive">
                                                        <table id="example" class="table table-striped table-bordered "
                                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">ลำดับ</th> 
                                                                    <th class="text-center">STMDoc</th> 
                                                                    <th class="text-center">total</th>
                                
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $number = 0;
                                                                $total1 = 0; ?>
                                                                @foreach ($datashow as $item)
                                                                    <?php $number++; ?>
                                
                                                                    <tr height="20">
                                                                        <td class="text-font" style="text-align: center;" width="4%" style="color:rgb(248, 12, 12)">{{ $number }}</td>
                                                                        <td class="text-start" style="color:rgb(34, 90, 243);font-size:15px"> {{ $item->STMDoc }}</td>  
                                                                        <td class="text-end" style="color:rgb(10, 151, 85);font-size:15px" width="30%">{{ number_format($item->total, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                
                                                            </tbody> 
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card p-4 card-ucs">
                                                    <h4 class="card-title" style="color:rgb(10, 151, 85)">STM DETAIL UCS IPD</h4>
                                                    <div class="table-responsive">
                                                        <table id="example2" class="table table-striped table-bordered "
                                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">ลำดับ</th> 
                                                                    <th class="text-center">STMDoc</th> 
                                                                    <th class="text-center">total</th>
                                
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $number = 0;
                                                                $total1 = 0; ?>
                                                                @foreach ($ucs_ipd as $item_ip)
                                                                    <?php $number++; ?>
                                
                                                                    <tr height="20">
                                                                        <td class="text-font" style="text-align: center;" width="4%" style="color:rgb(248, 12, 12)">{{ $number }}</td>
                                                                        <td class="text-start" style="color:rgb(252, 53, 129);font-size:15px"> {{ $item_ip->STMDoc }}</td>  
                                                                        <td class="text-end" style="color:rgb(10, 151, 85);font-size:15px" width="30%">{{ number_format($item_ip->total, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                
                                                            </tbody> 
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>

                                    <div class="tab-pane fade" id="v-pills-ofc" role="tabpanel" aria-labelledby="v-pills-ofc-tab">
                                        <div class="row">
                                            <div class="col-md-6"> 
                                                <div class="card p-4 card-ofc">
                                                    <h4 class="card-title" style="color:rgb(10, 151, 85)">STM DETAIL OFC OPD</h4>
                                                    <div class="table-responsive">
                                                        <table id="example3" class="table table-striped table-bordered " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">ลำดับ</th> 
                                                                    <th class="text-center">STMDoc</th>
                                                                    <th class="text-center">total</th>
                                
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $number = 0;
                                                                $total1 = 0; ?>
                                                                @foreach ($ofc_opd as $item2)
                                                                    <?php $number++; ?>
                                
                                                                    <tr height="20">
                                                                        <td class="text-font" style="text-align: center;" width="4%" style="color:rgb(248, 12, 12)">{{ $number }}</td>
                                                                        <td class="text-start" style="color:rgb(34, 90, 243);font-size:15px"> {{ $item2->STMDoc }}</td> 
                                                                        <td class="text-end" style="color:rgb(10, 151, 85);font-size:15px" width="20%">{{ number_format($item2->total, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                
                                                            </tbody> 
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card p-4 card-ofc">
                                                    <h4 class="card-title" style="color:rgb(10, 151, 85)">STM DETAIL OFC IPD</h4>
                                                    <div class="table-responsive">
                                                        <table id="example4" class="table table-striped table-bordered " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">ลำดับ</th> 
                                                                    <th class="text-center">STMDoc</th>
                                                                    <th class="text-center">total</th>
                                
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $number = 0;
                                                                $total1 = 0; ?>
                                                                @foreach ($ofc_ipd as $item_ofcipd)
                                                                    <?php $number++; ?>
                                
                                                                    <tr height="20">
                                                                        <td class="text-font" style="text-align: center;" width="4%" style="color:rgb(248, 12, 12)">{{ $number }}</td>
                                                                        <td class="text-start" style="color:rgb(252, 53, 129);font-size:15px"> {{ $item_ofcipd->STMDoc }}</td> 
                                                                        <td class="text-end" style="color:rgb(10, 151, 85);font-size:15px" width="20%">{{ number_format($item_ofcipd->total, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                
                                                            </tbody> 
                                                        </table> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>

                                    <div class="tab-pane fade" id="v-pills-lgo" role="tabpanel" aria-labelledby="v-pills-lgo-tab">
                                        <div class="row">
                                            <div class="col-md-6"> 
                                                <div class="card p-4 card-lgo">
                                                    <h4 class="card-title" style="color:rgb(10, 151, 85)">STM DETAIL LGO OPD</h4>
                                                    <div class="table-responsive">
                                                        <table id="example5" class="table table-striped table-bordered " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">ลำดับ</th> 
                                                                    <th class="text-center">STMDoc</th>
                                                                    <th class="text-center">total</th>
                                
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $number = 0;
                                                                $total1 = 0; ?>
                                                                @foreach ($lgo_opd as $item3)
                                                                    <?php $number++; ?>
                                
                                                                    <tr height="20">
                                                                        <td class="text-font" style="text-align: center;" width="4%" style="color:rgb(248, 12, 12)">{{ $number }}</td>
                                                                        <td class="text-start" style="color:rgb(107, 67, 250);font-size:15px"> 
                                                                          <a href="{{url('upstm_lgo_detail_opd/'.$item3->STMDoc)}}"> {{ $item3->STMDoc }}</a>  
                                                                           
                                                                        </td> 
                                                                        <td class="text-end" style="color:rgb(10, 151, 85);font-size:15px" width="20%">{{ number_format($item3->total, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                
                                                            </tbody> 
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6"> 
                                                <div class="card p-4 card-lgo">
                                                    <h4 class="card-title" style="color:rgb(10, 151, 85)">STM DETAIL LGO IPD</h4>
                                                    <div class="table-responsive">
                                                    <table id="example6" class="table table-striped table-bordered " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">ลำดับ</th> 
                                                                <th class="text-center">STMDoc</th>
                                                                <th class="text-center">total</th>
                            
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $number = 0;
                                                            $total1 = 0; ?>
                                                            @foreach ($lgo_ipd as $item_lgoi)
                                                                <?php $number++; ?>
                            
                                                                <tr height="20">
                                                                    <td class="text-font" style="text-align: center;" width="4%" style="color:rgb(248, 12, 12)">{{ $number }}</td>
                                                                    <td class="text-start" style="color:rgb(252, 53, 129);font-size:15px"> 
                                                                         <a href="{{url('upstm_lgo_detail_ipd/'.$item_lgoi->STMDoc)}}"> {{ $item_lgoi->STMDoc }}</a> 
                                                                    </td> 
                                                                    <td class="text-end" style="color:rgb(10, 151, 85);font-size:15px" width="20%">{{ number_format($item_lgoi->total, 2) }}</td>
                                                                </tr>
                                                            @endforeach
                            
                                                        </tbody> 
                                                    </table>
                                                </div>
                                                </div>
                                            </div>
                                        </div> 
                                        
                                    </div>

                                    <div class="tab-pane fade" id="v-pills-ucsti" role="tabpanel" aria-labelledby="v-pills-ucsti-tab">
                                        <div class="row">
                                            <div class="col-md-8"> 
                                                <div class="card p-4 card-ucsti">
                                                    <h4 class="card-title" style="color:rgb(10, 151, 85)">STM DETAIL UCS TI</h4>
                                                    <div class="table-responsive">
                                                        <table id="example7" class="table table-striped table-bordered " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">ลำดับ</th> 
                                                                    <th class="text-center">STMDoc</th>
                                                                    <th class="text-center">total</th>
                                
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $number = 0;
                                                                $total1 = 0; ?>
                                                                @foreach ($ucs_ti as $itemucsti)
                                                                    <?php $number++; ?>
                                
                                                                    <tr height="20">
                                                                        <td class="text-font" style="text-align: center;" width="4%" style="color:rgb(248, 12, 12)">{{ $number }}</td>
                                                                        <td class="text-start" style="color:rgb(107, 67, 250);font-size:15px"> {{ $itemucsti->STMDoc }}</td> 
                                                                        <td class="text-end" style="color:rgb(10, 151, 85);font-size:15px" width="20%">{{ number_format($itemucsti->total, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                
                                                            </tbody> 
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                             
                                        </div>                                         
                                    </div>

                                    <div class="tab-pane fade" id="v-pills-ofcti" role="tabpanel" aria-labelledby="v-pills-ofcti-tab">
                                        <div class="row">
                                            <div class="col-md-8"> 
                                                <div class="card p-4 card-ofcti">
                                                    <h4 class="card-title" style="color:rgb(10, 151, 85)">STM DETAIL OFC TI</h4>
                                                    <div class="table-responsive">
                                                        <table id="example8" class="table table-striped table-bordered " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">ลำดับ</th> 
                                                                    <th class="text-center">STMDoc</th>
                                                                    <th class="text-center">total</th>
                                
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $number = 0;
                                                                $total1 = 0; ?>
                                                                @foreach ($ofc_ti as $itemofcti)
                                                                    <?php $number++; ?>
                                
                                                                    <tr height="20">
                                                                        <td class="text-font" style="text-align: center;" width="4%" style="color:rgb(248, 12, 12)">{{ $number }}</td>
                                                                        <td class="text-start" style="color:rgb(107, 67, 250);font-size:15px"> {{ $itemofcti->STMDoc }}</td> 
                                                                        <td class="text-end" style="color:rgb(10, 151, 85);font-size:15px" width="20%">{{ number_format($itemofcti->total, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                
                                                            </tbody> 
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                             
                                        </div>                                         
                                    </div>

                                    <div class="tab-pane fade" id="v-pills-sssti" role="tabpanel" aria-labelledby="v-pills-sssti-tab">
                                        <div class="row">
                                            <div class="col-md-8"> 
                                                <div class="card p-4 card-sssti">
                                                    <h4 class="card-title" style="color:rgb(10, 151, 85)">STM DETAIL SSS TI</h4>
                                                    <div class="table-responsive">
                                                        <table id="example9" class="table table-striped table-bordered " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">ลำดับ</th> 
                                                                    <th class="text-center">STMDoc</th>
                                                                    <th class="text-center">total</th>
                                
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $number = 0;
                                                                $total1 = 0; ?>
                                                                @foreach ($sss_ti as $itemsssti)
                                                                    <?php $number++; ?>
                                
                                                                    <tr height="20">
                                                                        <td class="text-font" style="text-align: center;" width="4%" style="color:rgb(248, 12, 12)">{{ $number }}</td>
                                                                        <td class="text-start" style="color:rgb(107, 67, 250);font-size:15px"> {{ $itemsssti->STMDoc }}</td> 
                                                                        <td class="text-end" style="color:rgb(10, 151, 85);font-size:15px" width="20%">{{ number_format($itemsssti->total, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                
                                                            </tbody> 
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                             
                                        </div>                                         
                                    </div>

                                    
                                    <div class="tab-pane fade" id="v-pills-lgoti" role="tabpanel" aria-labelledby="v-pills-lgoti-tab">
                                        <div class="row">
                                            <div class="col-md-6"> 
                                                <div class="card p-4 card-lgoti">
                                                    <h4 class="card-title" style="color:rgb(10, 151, 85)">STM DETAIL LGO TI OPD</h4>
                                                    <div class="table-responsive">
                                                        <table id="example10" class="table table-striped table-bordered " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">ลำดับ</th> 
                                                                    <th class="text-center">STMDoc</th>
                                                                    <th class="text-center">total</th>
                                
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $number = 0;
                                                                $total1 = 0; ?>
                                                                @foreach ($lgo_opdti as $lgoopdti)
                                                                    <?php $number++; ?>
                                
                                                                    <tr height="20">
                                                                        <td class="text-font" style="text-align: center;" width="4%" style="color:rgb(248, 12, 12)">{{ $number }}</td>
                                                                        <td class="text-start" style="color:rgb(107, 67, 250);font-size:15px"> {{ $lgoopdti->STMDoc }}</td> 
                                                                        <td class="text-end" style="color:rgb(10, 151, 85);font-size:15px" width="20%">{{ number_format($lgoopdti->total, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                
                                                            </tbody> 
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6"> 
                                                <div class="card p-4 card-lgoti">
                                                    <h4 class="card-title" style="color:rgb(10, 151, 85)">STM DETAIL LGO TI IPD</h4>
                                                    <div class="table-responsive">
                                                        <table id="example11" class="table table-striped table-bordered " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">ลำดับ</th> 
                                                                    <th class="text-center">STMDoc</th>
                                                                    <th class="text-center">total</th>
                                
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $number = 0;
                                                                $total1 = 0; ?>
                                                                @foreach ($lgo_ipdti as $lgoipdti)
                                                                    <?php $number++; ?>
                                
                                                                    <tr height="20">
                                                                        <td class="text-font" style="text-align: center;" width="4%" style="color:rgb(248, 12, 12)">{{ $number }}</td>
                                                                        <td class="text-start" style="color:rgb(107, 67, 250);font-size:15px"> {{ $lgoipdti->STMDoc }}</td> 
                                                                        <td class="text-end" style="color:rgb(10, 151, 85);font-size:15px" width="20%">{{ number_format($lgoipdti->total, 2) }}</td>
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
                        </div>
                    </div>
                {{-- </div> --}}
                <!-- end card -->
            </div>
        </div>
  
        {{-- <div class="row">

            <div class="col"></div>
            <div class="col-xl-8 col-md-6">
                <div class="main-card card p-3">
                    <div class="grid-menu-col">
 
                        <table id="example" class="table table-striped table-bordered "
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-center">ลำดับ</th>
                                    <th class="text-center">rep</th>
                                    <th class="text-center">months</th>
                                    <th class="text-center">filename</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $number = 0;
                                $total1 = 0; ?>
                                @foreach ($datashow as $item)
                                    <?php $number++; ?>

                                    <tr height="20" style="font-size: 14px;color:rgb(235, 6, 6)">
                                        <td class="text-font" style="text-align: center;" width="4%" style="color:rgb(248, 12, 12)">{{ $number }}</td>
                                        <td class="text-center" width="10%" style="color:rgb(248, 12, 12)"> {{ $item->rep }}</td>
                                        @if ($item->months == '1')
                                        <td width="10%" class="text-center" >มกราคม </td>
                                    @elseif ($item->months == '2')
                                        <td width="10%" class="text-center">กุมภาพันธ์  </td>
                                    @elseif ($item->months == '3')
                                        <td width="10%" class="text-center">มีนาคม  </td>
                                    @elseif ($item->months == '4')
                                        <td width="10%" class="text-center">เมษายน </td>
                                    @elseif ($item->months == '5')
                                        <td width="10%" class="text-center">พฤษภาคม</td>
                                    @elseif ($item->months == '6')
                                        <td width="10%" class="text-center">มิถุนายน </td>
                                    @elseif ($item->months == '7')
                                        <td width="10%" class="text-center">กรกฎาคม</td>
                                    @elseif ($item->months == '8')
                                        <td width="10%" class="text-center">สิงหาคม </td>
                                    @elseif ($item->months == '9')
                                        <td width="10%" class="text-center">กันยายน </td>
                                    @elseif ($item->months == '10')
                                        <td width="10%" class="text-center">ตุลาคม </td>
                                    @elseif ($item->months == '11')
                                        <td width="10%" class="text-center">พฤษจิกายน </td>
                                    @else
                                        <td width="10%" class="text-center">ธันวาคม</td>
                                    @endif
                                        <td class="text-end" style="color:rgb(248, 12, 12)" width="7%"> {{ $item->STMdoc }}</td>
                                    </tr>
                                @endforeach

                            </tbody> 
                        </table>

                    </div>
                </div>
            </div>
            <div class="col"></div>
        </div>
 
    </div> --}}


@endsection
@section('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            $('#example2').DataTable();
            $('#example3').DataTable();
            $('#example4').DataTable();
            $('#example5').DataTable();
            $('#example6').DataTable();
            $('#example7').DataTable();
            $('#example8').DataTable();
            $('#example9').DataTable();
            $('#example10').DataTable();
            $('#example11').DataTable();

            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
            $('#datepicker2').datepicker({
                format: 'yyyy-mm-dd'
            });

            var bar = $('.bar');
            var percent = $('.percent');
            $('form').ajaxForm({
                beforeSend: function() {
                    var percentVal = '0%';
                    bar.width(percentVal);
                    percent.html(percentVal);
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete+'%';
                    bar.width(percentVal);
                    percent.html(percentVal);
                },
                complete: function(xhr) { 
                    Swal.fire({
                        title: 'UP STM สำเร็จ',
                        text: "You UP STM success",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#06D177',
                        // cancelButtonColor: '#d33',
                        confirmButtonText: 'เรียบร้อย'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = "{{ url('upstm_ucs') }}";
                        }
                    })
                }
            })

            $('#Upstm').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                // alert('OJJJJOL');
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
                        if (data.status == 200) {
                            Swal.fire({
                                title: 'Up Statment สำเร็จ',
                                text: "You Up Statment data success",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#06D177',
                                // cancelButtonColor: '#d33',
                                confirmButtonText: 'เรียบร้อย'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            })

                        } else {
                            Swal.fire({
                                title: 'UP Statment ซ้ำ',
                                text: "You Up Statment data success",
                                icon: 'warning',
                                showCancelButton: false,
                                confirmButtonColor: '#06D177',
                                // cancelButtonColor: '#d33',
                                confirmButtonText: 'เรียบร้อย'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.reload();
                                }
                            })
                        }
                    }
                });
            });

            //    $('#UpdateHN').click(function() {
            //             var datepicker = $('#datepicker').val();
            //             var datepicker2 = $('#datepicker2').val();

            //             $.ajax({
            //                 url: "{{ route('acc.upstm_hn') }}",
            //                 type: "POST",
            //                 dataType: 'json',
            //                 data: {
            //                     datepicker,datepicker2
            //                 },
            //                 success: function(data) {
            //                     if (data.status == 200) {
            //                         Swal.fire({
            //                             title: 'อัพเดทข้อมูลสำเร็จ',
            //                             text: "You Update data success",
            //                             icon: 'success',
            //                             showCancelButton: false,
            //                             confirmButtonColor: '#06D177',
            //                             confirmButtonText: 'เรียบร้อย'
            //                         }).then((result) => {
            //                             if (result
            //                                 .isConfirmed) {
            //                                 console.log(
            //                                     data);
            //                                 window.location.reload();
            //                                 // window.location="{{ url('warehouse/warehouse_index') }}";
            //                             }
            //                         })
            //                     } else {

            //                     }

            //                 },
            //             });
            //     });

        });
    </script>
@endsection
