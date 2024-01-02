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

    <div class="tabs-animation mb-5">

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
            <div class="col-md-4">
                <h4 class="card-title" style="color:rgb(10, 151, 85)">Detail 1102050101.310</h4>
                <p class="card-title-desc">รายละเอียดข้อมูล ผัง 1102050101.310</p>
            </div>
            <div class="col"></div>
           
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card cardacc">
                    {{-- <div class="card-header">
                        รายละเอียด 1102050101.310
                        <div class="btn-actions-pane-right">

                        </div>
                    </div> --}}
                    <div class="card-body">
                        <div class="table-responsive">
                        {{-- <h4 class="card-title" style="color:rgb(10, 151, 85)">รายละเอียด 1102050101.310</h4> --}}
                       
                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-center">ลำดับ</th>
                                    <th class="text-center">an</th>
                                    <th class="text-center">hn</th>
                                    <th class="text-center">cid</th>
                                    <th class="text-center">ptname</th>
                                    <th class="text-center">dchdate</th>
                                    <th class="text-center">pttype</th>
                                    <th class="text-center">income</th>
                                    <th class="text-center">ลูกหนี้</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $number = 0;  $total1 = 0;$total2 = 0;?>
                                @foreach ($data as $item)
                                    <?php $number++; ?>

                                    <tr height="20" style="font-size: 14px;">
                                        <td class="text-font" style="text-align: center;" width="4%">{{ $number }}
                                        </td>
                                        <td class="text-center" width="10%">{{ $item->an }}</td>
                                        <td class="text-center" width="10%">{{ $item->hn }}</td>
                                        <td class="text-center" width="10%">{{ $item->cid }}</td>
                                        <td class="p-2">{{ $item->ptname }}</td>
                                        <td class="text-center" width="10%">{{ $item->dchdate }}</td>
                                        <td class="text-center" width="10%">{{ $item->pttype }}</td>
                                        <td class="text-end" style="color:rgb(73, 147, 231)" width="7%">
                                            {{ number_format($item->debit, 2) }}</td>
                                        <td class="text-end" width="10%" style="color:rgb(216, 95, 14)">
                                            {{ number_format($item->debit_total, 2) }} </td>
                                    </tr>
                                    <?php
                                    $total1 = $total1 + $item->debit; 
                                    $total2 = $total2 + $item->debit_total; 
                                  ?>
                                @endforeach

                            </tbody>
                            <tr style="background-color: #f3fca1">
                                <td colspan="7" class="text-end" style="background-color: #fca1a1"></td>
                                <td class="text-center" style="background-color: #47A4FA"><label for="" style="color: #FFFFFF">{{ number_format($total1, 2) }}</label></td> 
                                <td class="text-center" style="background-color:rgb(226, 118, 45)"><label for="" style="color: #FFFFFF">{{ number_format($total2, 2) }}</label></td> 
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
