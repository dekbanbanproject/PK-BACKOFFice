@extends('layouts.accountpk')
@section('title', 'PK-OFFICER || ACCOUNT')
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
            border-top: 10px #1fdab1 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }

        @keyframes sp-anime {
            100% {
                transform: rotate(390deg);
            }
        }

        .is-hide {
            display: none;
        }
    </style>

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
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h5 class="card-title" style="color:green">Debit data 1102050101.307</h5>
                       
                        <form action="{{ route('acc.account_307_search') }}" method="GET">
                            @csrf
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                                        <input type="text" class="form-control inputacc" name="startdate" id="datepicker" placeholder="Start Date"
                                            data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                                            data-date-language="th-th" value="{{ $startdate }}" required/>
                                        <input type="text" class="form-control inputacc" name="enddate" placeholder="End Date" id="datepicker2"
                                            data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                                            data-date-language="th-th" value="{{ $enddate }}" required/>
                                            
                                        <button type="submit" class="ladda-button btn-pill btn btn-primary cardacc" data-style="expand-left">
                                            <span class="ladda-label"> <i class="fa-solid fa-magnifying-glass text-white me-2"></i>ค้นหา</span>
                                            <span class="ladda-spinner"></span>
                                        </button> 
                                    </div> 
                                </ol>
                            </div>
    
                    </div>
                </div>
            </div>
            <!-- end page title -->
        </div> <!-- container-fluid -->
        
        <div class="row ">
            <div class="col-md-12">
                <div class="card card_audit_4c"> 
                    <div class="card-body">  
                        <div class="table-responsive">  
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;"> 
                                <thead>
                                    <tr>
                                        <th class="text-center">ลำดับ</th>
                                        <th class="text-center">cid</th> 
                                        <th class="text-center">vn</th>
                                        <th class="text-center" >hn</th> 
                                        <th class="text-center">pttype</th>
                                        <th class="text-center">ptname</th>  
                                        <th class="text-center">vstdate</th> 
                                        <th class="text-center">dchdate</th>  
                                        <th class="text-center">ลูกหนี้</th>  
                                        <th class="text-center">Stm</th> 
                                        <th class="text-center">ส่วนต่าง</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $number = 0; 
                                    $total1 = 0; $total2 = 0;$total3 = 0;$total4 = 0;$total5 = 0;$total6 = 0;$total7 = 0;$total8 = 0;$total9 = 0;
                                    ?>
                                    @foreach ($datashow as $item)
                                        <?php $number++; ?>
                                        <tr height="20" style="font-size: 14px;">
                                            <td class="text-font" style="text-align: center;" width="4%">{{ $number }}</td> 
                                            <td class="text-center" width="5%">{{ $item->cid }}</td>  
                                            <td class="text-center" width="6%">{{ $item->vn }}</td> 
                                            <td class="text-center" width="4%">{{ $item->hn }}</td> 
                                            <td class="text-center" width="6%">{{ $item->pttype }}</td>   
                                            <td class="p-2" >{{ $item->ptname }}</td>   
                                            <td class="text-center" width="6%">{{ $item->vstdate }}</td>
                                            <td class="text-center" width="6%">{{ $item->dchdate }}</td> 
                                            <td class="text-end" style="color:rgb(38, 133, 241)" width="6%">{{ number_format($item->debit_total,2)}}</td>                                        
                                            <td class="text-end" style="color:rgb(5, 177, 162)" width="6%">{{ number_format($item->recieve_true,2)}}</td> 
                                            <td class="text-end" style="color:rgb(184, 12, 169)" width="6%">{{ number_format(($item->debit_total-$item->recieve_true),2)}}</td> 
                                            
                                        </tr>
                                            <?php                                        
                                                $total6 = $total6 + $item->debit_total;                                         
                                                $total8 = $total8 + $item->recieve_true;
                                                $total7 = $total7 + ($item->debit_total-$item->recieve_true); 
                                            ?>                                 
                                    @endforeach  
                                
                                </tbody>
                                    <tr style="background-color: #f3fca1">
                                        <td colspan="8" class="text-end" style="background-color: #ff9d9d"></td>
                                        <td class="text-end" style="background-color: #23a1eb;color:white">{{ number_format($total6,2)}}</td> 
                                        <td class="text-end" style="background-color: #0cc1ce;color:white">{{ number_format($total8,2)}}</td> 
                                        <td class="text-end" style="background-color: #b34c99;color:white">{{ number_format($total7,2)}}</td>  
                                    </tr>  
                            </table>
                        </div>
                    </div>
                </div>
            </div> 
        </div>

        <div class="row ">
            <div class="col-md-12">
                <div class="card card_audit_4c"> 
                    <div class="card-body">  
                        <div class="table-responsive">  
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;"> 
                                <thead>
                                    <tr>
                                        <th class="text-center">ลำดับ</th>
                                        <th class="text-center">cid</th> 
                                        {{-- <th class="text-center">vn</th> --}}
                                        <th class="text-center" >hn</th> 
                                        <th class="text-center">an</th>
                                        <th class="text-center">pttype</th>
                                        <th class="text-center">ptname</th>  
                                        <th class="text-center">vstdate</th> 
                                        <th class="text-center">dchdate</th>  
                                        <th class="text-center">ลูกหนี้</th>  
                                        <th class="text-center">Stm</th> 
                                        <th class="text-center">ส่วนต่าง</th>  
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $number2 = 0; 
                                    $total1 = 0; $total2 = 0;$total3 = 0;$total4 = 0;$total5 = 0;$total6 = 0;$total7 = 0;$total8 = 0;$total9 = 0;
                                    ?>
                                    @foreach ($datashow_ipd as $item2)
                                        <?php $number2++; ?>
                                        <tr height="20" style="font-size: 14px;">
                                            <td class="text-font" style="text-align: center;" width="4%">{{ $number2 }}</td> 
                                            <td class="text-center" width="5%">{{ $item2->cid }}</td>  
                                            {{-- <td class="text-center" width="6%">{{ $item2->vn }}</td>  --}}
                                            <td class="text-center" width="4%">{{ $item2->hn }}</td> 
                                            <td class="text-center" width="6%">{{ $item2->an }}</td>   
                                            <td class="text-center" width="6%">{{ $item2->pttype }}</td>  
                                            <td class="p-2" width="8%">{{ $item2->ptname }}</td>   
                                            <td class="text-center" width="6%">{{ $item2->vstdate }}</td>
                                            <td class="text-center" width="6%">{{ $item2->dchdate }}</td> 
                                            <td class="text-end" style="color:rgb(38, 133, 241)" width="6%">{{ number_format($item2->debit_total,2)}}</td>                                        
                                            <td class="text-end" style="color:rgb(5, 177, 162)" width="6%">{{ number_format($item2->recieve_true,2)}}</td> 
                                            <td class="text-end" style="color:rgb(184, 12, 169)" width="6%">{{ number_format(($item2->debit_total-$item2->recieve_true),2)}}</td> 
                                            
                                        </tr>
                                            <?php                                        
                                                $total6 = $total6 + $item2->debit_total;                                         
                                                $total8 = $total8 + $item2->recieve_true;
                                                $total7 = $total7 + ($item2->debit_total-$item2->recieve_true); 
                                            ?>                                 
                                    @endforeach  
                                
                                </tbody>
                                    <tr style="background-color: #f3fca1">
                                        <td colspan="8" class="text-end" style="background-color: #ff9d9d"></td>
                                        <td class="text-end" style="background-color: #23a1eb;color:white">{{ number_format($total6,2)}}</td> 
                                        <td class="text-end" style="background-color: #0cc1ce;color:white">{{ number_format($total8,2)}}</td> 
                                        <td class="text-end" style="background-color: #b34c99;color:white">{{ number_format($total7,2)}}</td>  
                                    </tr>  
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

            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
            $('#datepicker2').datepicker({
                format: 'yyyy-mm-dd'
            });

            $('#example').DataTable();
            $('#hospcode').select2({
                placeholder: "--เลือก--",
                allowClear: true
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
 
        });
    </script>
@endsection
