@extends('layouts.report_font')
@section('title', 'PK-BACKOFFice || REFER')
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

        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        Referข้าม CUP ภายในจังหวัด
                        <div class="btn-actions-pane-right">

                        </div>
                    </div>
                    <div class="card-body">
                            <form action="{{ route('rep.refer_opds_cross') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col"></div>
                                    <div class="col-md-1 text-end">วันที่</div>
                                    <div class="col-md-4 text-center">
                                        <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy"
                                            data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                                            <input type="text" class="form-control" name="startdate" id="datepicker" placeholder="Start Date"
                                                data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true"
                                                data-date-language="th-th" value="{{ $startdate }}" />
                                            <input type="text" class="form-control" name="enddate" placeholder="End Date" id="datepicker2"
                                                data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true"
                                                data-date-language="th-th" value="{{ $enddate }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-1 text-center mt-1">โรงพยาบาล</div>
                                        <div class="col-md-2 text-center mt-1">
                                            <div class="input-group">
                                                <select id="hospcode" name="hospcode" class="form-select form-select-lg" style="width: 100%">
                                                    @foreach ($hosshow as $items)
                                                        @if ($hospcode == $items->hospcode)
                                                            <option value="{{ $items->hospcode }}" selected> {{ $items->hosname }} </option>
                                                        @else
                                                            <option value="{{ $items->hospcode }}"> {{ $items->hosname }} </option>
                                                        @endif
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="mb-2 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info">
                                            <i class="pe-7s-search btn-icon-wrapper"></i>ค้นหา
                                        </button>
                                        <a href="{{url('cross_exportexcel')}}" class="mb-2 me-2 btn-icon btn-shadow btn-dashed btn btn-outline-success">
                                            <i class="fa-solid fa-file-excel me-2"></i>
                                            Export
                                        </a>
                                    </div>
                                    <div class="col"></div>
                                </div>
                            </form>
                            <br>
                            {{-- <table id="example" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;"> --}}
                            <table id="example" class="table table-striped table-bordered " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-center">ลำดับ</th>
                                    <th class="text-center">cid</th>
                                    <th class="text-center">hn</th>
                                    <th class="text-center">ชื่อ-นามสกุล</th>
                                    <th class="text-center">โรงพยาบาล</th>
                                    <th class="text-center">สิทธิ์ Hos</th>
                                    <th class="text-center">วันที่รับบริการ</th>
                                    {{-- <th class="text-center">เวลารับบริการ</th>--}}
                                    <th class="text-center">pdx</th>
                                    <th class="text-center">dx0</th>
                                    <th class="text-center">dx1</th>
                                    <th class="text-center">income</th>
                                    <th class="text-center">ยอดเรียกเก็บ</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $number = 0;$total1 = 0; $total2 = 0;$total3 = 0; ?>
                                @foreach ($datashow_ as $item)
                                    <?php $number++; ?>
                                    <tr height="20">
                                        <td class="text-font" style="text-align: center;width: 5%;">{{ $number }}</td>
                                        <td class="text-font text-pedding" style="text-align: center;width: 10%;" > {{ $item->cid }}</td>
                                        <td class="text-font text-pedding" style="text-align: center;width: 7%;"> {{ $item->hn }}</td>
                                        <td class="text-font text-pedding" style="text-align: left;"> {{ $item->ptname }} </td>
                                        <td class="text-font text-pedding" style="text-align: left;width: 15%;"> {{ $item->hospmain }} </td>
                                        <td class="text-font text-pedding" style="text-align: center;width: 7%;"> {{ $item->pttype }} </td>
                                        <td class="text-font text-pedding" style="text-align: center;width: 10%;"> {{ $item->vstdate }}</td>
                                        {{-- <td class="text-font text-pedding" style="text-align: center;"> {{ $item->vsttime }} </td>  --}}
                                        <td class="text-font text-pedding" style="text-align: center;width: 5%;"> {{ $item->pdx }} </td>
                                        <td class="text-font text-pedding" style="text-align: center;width: 5%;"> {{ $item->dx0 }} </td>
                                        <td class="text-font text-pedding" style="text-align: center;width: 5%;"> {{ $item->dx1 }} </td>
                                        <td class="text-font text-pedding" style="text-align: right;width: 7%;color:#b00ec5">&nbsp;&nbsp; {{ number_format($item->income,2) }} </td>
                                        <td class="text-font text-pedding" style="text-align: right;width: 7%;color:#f1632b"> &nbsp;&nbsp;{{ number_format($item->refer,2) }} </td>
                                        <td class="text-font text-pedding" style="text-align: right;width: 7%;color:#0bc597"> &nbsp;&nbsp;{{ number_format(($item->total),2) }} </td>
                                    </tr>
                                        <?php
                                            $total1 = $total1 + ($item->income);
                                            $total2 = $total2 + $item->refer;
                                            $total3 = $total3 + $item->total;
                                        ?>
                                @endforeach

                            </tbody>
                            <tr style="background-color: #f3fca1">
                                <td colspan="10" class="text-end" style="background-color: #ff9d9d"></td>
                                <td class="text-end" style="background-color: #e09be9">{{ number_format($total1,2)}}</td>
                                <td class="text-end" style="background-color: #f5a382">{{ number_format($total2,2)}}</td>
                                <td class="text-end" style="background-color: #bbf0e3">{{ number_format($total3,2)}}</td>
                            </tr>
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
