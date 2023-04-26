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
            <div class="col"></div>
            <div class="col-md-6">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        ผู้ป่วยนอกรับ Refer แยก รพ. (visit ซ้ำ)
                        <div class="btn-actions-pane-right">
                            <div role="group" class="btn-group-sm btn-group">

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
 
                        <div class="table-responsive mt-3">
                            <table class="align-middle mb-0 table table-borderless table-striped table-hover" >
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>vn</th>
                                        <th>hn</th> 
                                        <th>วันที่รักษา</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $number = 0; ?>
                                    @foreach ($datashow_ as $item)
                                        <?php $number++; ?>
                                        <tr height="20">
                                            <td class="text-font" style="text-align: center;">{{ $number }}</td> 
                                            <td class="text-font text-pedding" style="text-align: left;"> {{ $item->vn }}</td> 
                                            <td class="text-font text-pedding" style="text-align: left;"> {{ $item->hn }}</td> 
                                            <td class="text-font text-pedding" style="text-align: left;"> {{ $item->vstdate }}</td> 
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-header">
                        ผู้ป่วยนอกรับ Refer แยก รพ.
                        <div class="btn-actions-pane-right">
                            <div role="group" class="btn-group-sm btn-group">

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                       
                        {{-- <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="example"> --}}
                            <table id="example" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-center">ลำดับ</th>
                                    <th class="text-center">vn</th>
                                    <th class="text-center">วันที่รักษา</th> 
                                    <th class="text-center">hn</th> 
                                    <th class="text-center">pdx</th> 
                                    <th class="text-center">เรื้อรัง</th> 
                                    <th class="text-center">สิทธิ์ hos</th> 
                                    <th class="text-center">สิทธิ์ สปสช</th> 
                                    <th class="text-center">รพ สปสช</th>
                                    <th class="text-center">ชื่อ</th>
                                    <th class="text-center">cid</th> 
                                    <th class="text-center">ค่าเตียงสังเกตุอาการ</th> 
                                    <th class="text-center">ชันสูตร</th> 
                                    <th class="text-center">รังสีรักษา</th> 
                                    <th class="text-center">ตรวจวินิจฉัย</th> 
                                    <th class="text-center">ผ่าตัดหัตถการ</th> 
                                    <th class="text-center">อุปกรณ์อวัยวะเที่ยม</th> 
                                    <th class="text-center">ตรวจพิเศษ</th> 
                                    <th class="text-center">เวชภัณฑ์มิใช่ยา</th> 
                                    <th class="text-center">ยาและเวชภัณฑ์</th> 
                                    <th class="text-center">เวชกรรมฟื้นฟู</th> 
                                    <th class="text-center">ฝังเข็มบำบัดอื่นฯ</th> 
                                    <th class="text-center">ค่าบริการตรวจทั่วไป</th> 
                                    <th class="text-center">ค่าบริการตรวทันตกรรม</th> 
                                    <th class="text-center">รวมทั้งสิ้น</th> 
                                    <th class="text-center">CTลบออก</th> 
                                    <th class="text-center">MRIลบออก</th> 
                                    <th class="text-center">อุปกรณ์ลบออก</th> 
                                    <th class="text-center">ฟอกเลือดลบออก</th> 
                                    <th class="text-center">LABฟอกเลือดลบออก</th> 
                                    <th class="text-center">B24ถ้ามีต้องลบออก</th> 
                                    <th class="text-center">COVID19ถ้ามีต้องลบออก</th> 

                                    <th class="text-center">IVP รวมเข้าหลังคิด 700</th> 
                                    <th class="text-center">REFER รวมเข้าหลังคิด 700</th> 
                                    <th class="text-center">โรคเรื้อรัง</th> 
                                    <th class="text-center">ค่าใช้จ่ายเงื่อนไขข้อตกลงในจังหวัด</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $number2 = 0; ?>
                                @foreach ($datashow_2 as $item2)
                                    <?php $number2++; ?>
                                    <tr height="20">
                                        <td class="text-font" style="text-align: center;">{{ $number2 }}</td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->vn }}</td>   
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->vstdate }}</td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->hn }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->pdx }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->icd10 }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->pttype }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->check_sit_subinscl }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->check_sit_hmain }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->fullname }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->cid }} </td> 
                                        <?php 
                                            $ct_ = DB::connection('mysql3')->select('select sum(oo.sum_price) as sum_price from eclaimdb.opitemrece_refer oo LEFT JOIN nondrugitems n on n.icode = oo.icode where oo.vn = "'.$item2->vn.'" and n.name like "ct%" limit 1');
                                            foreach ($ct_ as $it1){
                                                $ctt = $it1->sum_price;
                                            }  
                                        ?>  
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->inc01 }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->inc04 }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->inc05 }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->inc06 }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;">   </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->inc14 }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->inc13 }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->inc12 }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->inc11 }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{$ctt}} </td> 
                                       
                                        {{-- 650908073723 --}}
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->inc17 }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> 0 </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ number_format($item2->paid_money ,2)}} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->vn }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->vn }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->vn }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->vn }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->vn }} </td> 
                                        <td class="text-font text-pedding" style="text-align: center;"> {{ number_format($item2->paid_money ,2)}}</td> 
                                        {{-- <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->ovn}}</td>  --}}
                                                                              
                                         <td class="text-font text-pedding" style="text-align: center;"> {{$ctt}} </td> 
                                         <td class="text-font text-pedding" style="text-align: center;"> {{ $item2->inc16 }} </td> 

                                         <td class="text-font text-pedding" style="text-align: center;"> 0 </td> 
                                         <td class="text-font text-pedding" style="text-align: center;"> 0 </td> 
                                         <td class="text-font text-pedding" style="text-align: center;"> 0 </td> 
                                         <td class="text-font text-pedding" style="text-align: center;"> 0 </td> 
                                    </tr>
                                @endforeach
                                {{-- ,c.check_sit_subinscl,c.check_sit_hmain --}}
                                {{-- <th width="60" class="sortable-numeric fd-column-3" style="-moz-user-select: none; font-size: 12px; text-align: center;">สิทธิ HOSxP</th>
                                <th width="60" class="sortable-numeric fd-column-3" style="-moz-user-select: none; font-size: 12px; text-align: center;">สิทธิ สปสช</th> --}}
                                {{-- left outer join money_pk.check_sit c on c.check_sit_vn = v.vn --}}

                                {{-- <td align='center' style=' font-size: 14px;'>".$row[7]."</a></td>
                                <td align='center' style=' font-size: 14px;'>".$row[8]."</a></td> --}}
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

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
 
        });
    </script>
@endsection
