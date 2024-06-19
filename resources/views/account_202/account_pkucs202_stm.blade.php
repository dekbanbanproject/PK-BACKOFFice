@extends('layouts.accountpk')
@section('title', 'PK-OFFICER || ACCOUNT')
@section('content')
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
                        <h4 class="mb-sm-0" style="color:rgb(10, 151, 85)">Detail STM 1102050101.202</h4>
    
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Detail STM</a></li>
                                <li class="breadcrumb-item active">1102050101.202</li>
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
                    {{-- <div class="card-header"> 
                       รายละเอียดตั้งลูกหนี้ผัง 1102050101.202
                        <div class="btn-actions-pane-right">                           
                        </div>
                    </div> --}}
                    <div class="card-body">  
                        <div class="table-responsive"> 

                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            {{-- <table id="example" class="table table-striped table-bordered "
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;"> --}}
                            <thead>
                                <tr>
                                    <th class="text-center">ลำดับ</th>
                                    <th class="text-center">tranid</th>
                                    {{-- <th class="text-center" width="5%">vn</th>  --}}
                                    <th class="text-center">an</th>
                                    <th class="text-center" >hn</th> 
                                    <th class="text-center">ptname</th>
                                    {{-- <th class="text-center">Adjrw</th>  --}}
                                    <th class="text-center">Adjrw*8350</th>
                                    <th class="text-center">dchdate</th>   
                                    <th class="text-center">ลูกหนี้</th>  
                                    <th class="text-center">ส่วนต่าง</th> 
                                    <th class="text-center">Stm 202</th> 
                                    <th class="text-center">ยอดชดเชยทั้งสิ้น</th>  
                                    <th class="text-center">STMdoc</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $number = 0; 
                                $total1 = 0; $total2 = 0;$total3 = 0;$total4 = 0;
                                ?>
                                @foreach ($datashow as $item)
                                    <?php $number++; ?>
                                    <tr height="20" style="font-size: 14px;">
                                        <td class="text-font" style="text-align: center;" width="4%">{{ $number }}</td> 
                                        <td class="text-center" width="6%">{{ $item->stm_trainid }}</td> 
                                        {{-- <td class="text-center" width="6%">{{ $item->vn }}</td>   --}}
                                        <td class="text-center" width="6%">{{ $item->an }}</td> 
                                        <td class="text-center" width="5%">{{ $item->hn }}</td>   
                                        <td class="p-2" width="10%">{{ $item->ptname }}</td>  
                                        {{-- <td class="text-center" width="7%">{{ $item->adjrw }}</td> --}}
                                        <td class="text-center" width="7%">{{ $item->total_adjrw_income }}</td>
                                        <td class="text-center" width="6%">{{ $item->dchdate }}</td>  
                                        <td class="text-end" style="color:rgb(31, 128, 240)" width="7%">{{ number_format($item->debit_total,2)}}</td> 
                                        <td class="text-end" style="color:rgb(184, 12, 169)" width="7%">{{ number_format(($item->debit_total-$item->stm_money),2)}}</td> 
                                        <td class="text-end" style="color:rgb(216, 95, 14)" width="7%">{{ number_format($item->stm_money,2)}}</td> 
                                        <td class="text-end" style="color:rgb(6, 155, 142)" width="8%">{{ number_format($item->stm_total,2)}}</td>  
                                        <td class="p-2" width="10%">{{ $item->STMdoc }}</td>  
                                    
                                    </tr>
                                        <?php
                                            $total1 = $total1 + $item->debit_total;
                                            $total2 = $total2 + ($item->debit_total-$item->stm_money); 
                                            $total3 = $total3 + $item->stm_money;
                                            $total4 = $total4 + $item->stm_total;
                                        ?>

                                 
                                @endforeach  
                               
                            </tbody>
                                        <tr style="background-color: #f3fca1">
                                            <td colspan="7" class="text-end" style="background-color: #ff9d9d"></td>
                                            <td class="text-end" style="background-color: #19abe6;color:white">{{ number_format($total1,2)}}</td> 
                                            <td class="text-end" style="background-color: #e35df5;color:white">{{ number_format($total2,2)}}</td> 
                                            <td class="text-end" style="background-color: #f5a382;color:white">{{ number_format($total3,2)}}</td> 
                                            <td class="text-end" style="background-color: #068d92;color:white">{{ number_format($total4,2)}}</td>  
                                            <td class="text-end" style="background-color: #ff9d9d"></td> 
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
