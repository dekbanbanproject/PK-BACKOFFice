@extends('layouts.accountpk')
@section('title', 'PK-BACKOFFice || ACCOUNT')
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

    <div class="tabs-animation mb-5">

        <div class="row text-center">
            <div id="overlay">
                <div class="cv-spinner">
                    <span class="spinner"></span>
                </div>
            </div>

        </div>
        
        <div class="row ms-3 me-3">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header"> 
                       รายละเอียดตั้งลูกหนี้ผัง 1102050101.216
                        <div class="btn-actions-pane-right">
                           
                        </div>
                    </div>
                    <div class="card-body">  
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            {{-- <table id="example" class="table table-striped table-bordered "
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;"> --}}
                            <thead>
                                <tr>
                                    <th class="text-center">ลำดับ</th>
                                    <th class="text-center">tranid</th>
                                    <th class="text-center" width="5%">vn</th> 
                                    {{-- <th class="text-center">an</th> --}}
                                    <th class="text-center" >hn</th>
                                    {{-- <th class="text-center" >cid</th> --}}
                                    <th class="text-center">ptname</th>
                                    <th class="text-center">vstdate</th>  
                                    {{-- <th class="text-center">dchdate</th>   --}}
                                    {{-- <th class="text-center">income</th>  --}}
                                    <th class="text-center">ลูกหนี้</th> 
                                    <th class="text-center">Stm 216</th> 
                                    <th class="text-center">ส่วนต่าง</th> 
                                    <th class="text-center">Stm 202</th> 
                                    <th class="text-center">ยอดชดเชยทั้งสิ้น</th>  
                                    <th class="text-center">STMdoc</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $number = 0; $total1 = 0; $total2 = 0;$total3 = 0;?>
                                @foreach ($datashow as $item)
                                    <?php $number++; ?>
                                    <tr height="20" style="font-size: 14px;">
                                        <td class="text-font" style="text-align: center;" width="4%">{{ $number }}</td> 
                                        <td class="text-center" width="6%">{{ $item->tranid }}</td>  
                                        <td class="text-center" width="6%">{{ $item->vn }}</td> 
                                        {{-- <td class="text-center" width="6%">{{ $item->an }}</td>  --}}
                                        <td class="text-center" width="5%">{{ $item->hn }}</td>   
                                        <td class="p-2" width="10%">{{ $item->ptname }}</td>  
                                        {{-- <td class="text-center" width="6%">{{ $item->dchdate }}</td>  --}}
                                        <td class="text-center" width="7%">{{ $item->vstdate }}</td>   
                                        <td class="text-end" style="color:rgb(73, 147, 231)" width="7%">{{ number_format($item->debit_total,2)}}</td>

                                        @if ($item->inst == '0' || $item->hc == '0'|| $item->hc_drug == '0'|| $item->ae == '0'|| $item->ae_drug == '0') 
                                        <td class="text-end" style="color:rgb(216, 95, 14)" width="7%">{{ number_format(($item->stm216),2)}}</td> 
                                        @else 
                                        <td class="text-end" style="color:rgb(243, 12, 12)" width="7%"></td> 
                                      
                                        @endif                                        

                                        <td class="text-end" style="color:rgb(184, 12, 169)" width="7%">{{ number_format(($item->debit_total-$item->stm216),2)}}</td> 
                                        <td class="text-end" style="color:rgb(216, 95, 14)" width="7%">{{ number_format($item->STM202,2)}}</td> 
                                        <td class="text-end" style="color:rgb(9, 196, 180)" width="8%">{{ number_format($item->STM_TOTAL,2)}}</td>  
                                        <td class="p-2" width="10%">{{ $item->STMdoc }}</td> 
                                      
                                    </tr>
                                        <?php
                                            $total1 = $total1 + ($item->debit_total-$item->inst); 
                                            $total2 = $total2 + $item->STM202;
                                            $total3 = $total3 + $item->STM_TOTAL;
                                        ?>
 
                                @endforeach  
                               
                            </tbody>
                                     
                        </table>
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
