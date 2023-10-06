@extends('layouts.pkclaim')
@section('title', 'PK-BACKOFFice || AIPN')

@section('content')
    <script>
        function TypeAdmin() {
            window.location.href = '{{ route('index') }}';
        }

        function aipn_billitems_destroy(d_abillitems_id) {
            Swal.fire({
                title: 'ต้องการลบข้อมูลใช่ไหม?',
                text: "ข้อมูลนี้จะถูกลบ",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ใช่ ',
                cancelButtonText: 'ไม่'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('aipn_billitems_destroy') }}" + '/' + d_abillitems_id,
                        type: "GET",
                        data: {
                            _token: $("input[name=_token]").val()
                        },
                        success: function(response) {
                            if (response == 200) {
                                Swal.fire({
                                    title: 'ลบข้อมูลสำเร็จ !!',
                                    text: "Delete Data Success",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#06D177',
                                    confirmButtonText: 'เรียบร้อย'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $("#sidb" + d_abillitems_id).remove();
                                        window.location.reload();
                                        // window.location="{{ url('aipn') }}"; 
                                    }
                                })
                            } else {}
                        }
                    })
                }
            })
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
    use App\Http\Controllers\StaticController;
    use Illuminate\Support\Facades\DB;
    $count_meettingroom = StaticController::count_meettingroom();
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
 
            <div class="row">
                <div class="col-md-2 text-end">วันที่</div>
                <div class="col-md-6 text-center">
                    <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy"
                        data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                        <input type="text" class="form-control" name="startdate" id="datepicker" placeholder="Start Date"
                            data-date-container='#datepicker1' autocomplete="off" data-provide="datepicker"
                            data-date-autoclose="true" data-date-language="th-th" value="{{ $startdate }}" />
                        <input type="text" class="form-control" name="enddate" placeholder="End Date" id="datepicker2"
                            data-date-container='#datepicker1' autocomplete="off" data-provide="datepicker" 
                            data-date-autoclose="true" data-date-language="th-th" value="{{ $enddate }}" />
                        <button type="button" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info" id="Searchdata">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            ดึงข้อมูล
                        </button>
                        <button type="button" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info" id="Processdata">
                            <i class="fa-solid fa-spinner text-info me-2"></i>
                            ประมวลผล
                        </button>
                        <button type="button" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-success" id="Exportdata">
                            <i class="fa-solid fa-arrow-up-right-from-square text-success me-2"></i>
                            ส่งออก
                        </button> 
                        <a href="{{ url('aipn_zip') }}" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-danger"><i class="fa-solid fa-file-zipper me-2"></i>ZipFile</a>
                        {{-- <button type="button" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-danger" id="Zipdata">
                            <i class="fa-solid fa-arrow-up-right-from-square text-danger me-2"></i>
                            ZipFile
                        </button>  --}}
                       
                    </div>
                </div>
                {{-- <div class="col"></div> --}}
            </div>
 
        <div class="row mt-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
 
                        <div class="row">
                            <div class="col-md-4">
                                <h4 class="card-title">Detail AIPN</h4>
                                <p class="card-title-desc">รายละเอียดประกันสังคมผู้ป่วยใน</p>
                            </div>
                            <div class="col"></div>
                            <div class="col-md-2 text-end">
                                {{-- <button class="btn btn-secondary" id="Changbillitems"><i class="fa-solid fa-wand-magic-sparkles me-3"></i>ปรับ bilitems</button>  --}}

                            </div>
                        </div>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            {{-- <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#BillItems" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">BillItems</span>    
                                </a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#AIPN" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">AIPN</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#IPADT" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">IPADT</span>    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#IPDx" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">IPDx</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#IPOp" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">IPOp</span>
                                </a>
                            </li>

                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="AIPN" role="tabpanel">
                                <p class="mb-0">
                                    <div class="table-responsive">
                                        <table id="selection-datatable" class="table table-striped table-bordered dt-responsive nowrap"
                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th width="5%" class="text-center">ลำดับ</th>   
                                                    <th class="text-center">vn</th>
                                                    <th class="text-center">an</th>
                                                    <th class="text-center">hn</th>
                                                    <th class="text-center">dchdate</th>
                                                    <th class="text-center">debit</th>   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($d_aipn_main as $ite) 
                                                    <tr id="sidb{{$ite->d_aipn_main_id }}">   
                                                        <td class="text-center">{{ $i++ }}</td>  
                                                        <td class="text-center">{{ $ite->vn }} </td>
                                                        <td class="text-center">{{ $ite->an }}</td>
                                                        <td class="text-center">{{ $ite->hn }} </td>
                                                        <td class="text-center">{{ $ite->dchdate }} </td>    
                                                        <td class="text-center">{{ number_format($ite->debit, 2) }}</td> 
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </p>
                            </div>
                            <div class="tab-pane" id="IPADT" role="tabpanel">
                                <p class="mb-0">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">ลำดับ</th>
                                                <th class="text-center" width="5%">AN</th>
                                                <th class="text-center">HN</th>
                                                <th class="text-center">IDTYPE</th>
                                                <th class="text-center">PIDPAT</th>
                                                <th class="text-center">TITLE</th>
                                                <th class="text-center">NAMEPAT</th>
                                                <th class="text-center">DOB</th>
                                                <th class="text-center" width="7%">SEX</th>
                                                <th class="text-center">MARRIAGE</th>
                                                <th class="text-center">CHANGWAT</th>
                                                <th class="text-center" width="8%">AMPHUR</th>
                                                <th class="text-center" width="8%">NATION</th>
                                                <th class="text-center" width="10%">AdmType</th>
                                                <th class="text-center">AdmSource</th>
                                                <th class="text-center">DTAdm_d</th>
                                                <th class="text-center">DTDisch_d</th>
                                                <th class="text-center">LeaveDay</th>

                                                <th class="text-center">DischStat</th>
                                                <th class="text-center">DishType</th>
                                                <th class="text-center">AdmWt</th>
                                                <th class="text-center">DishWard</th>
                                                <th class="text-center">Dept</th>
                                                <th class="text-center">HMAIN</th>
                                                <th class="text-center">ServiceType</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($d_aipadt as $item)
                                                <tr style="font-size: 12px;">
                                                    <td class="text-center">{{ $i++ }}</td>
                                                    <td class="text-center" width="5%">{{ $item->AN }}</td>
                                                    <td class="text-center">{{ $item->HN }}</td>
                                                    <td class="text-center">{{ $item->IDTYPE }}</td>
                                                    <td class="text-center">{{ $item->PIDPAT }}</td>
                                                    <td class="text-center">{{ $item->TITLE }}</td>
                                                    <td class="text-center">{{ $item->NAMEPAT }}</td>
                                                    <td class="text-center">{{ $item->DOB }}</td>
                                                    <td class="text-center">{{ $item->SEX }}</td>
                                                    <td class="text-center">{{ $item->MARRIAGE }}</td>
                                                    <td class="p-2">{{ $item->CHANGWAT }}</td>
                                                    {{-- <td class="text-center">{{ number_format($item->Amount, 2) }}</td>  --}}
                                                    <td class="text-center">{{ $item->AMPHUR }}</td>
                                                    <td class="text-center">{{ $item->NATION }}</td>
                                                    <td class="text-center">{{ $item->AdmType }}</td>
                                                    <td class="text-center">{{ $item->AdmSource }}</td>
                                                    <td class="text-center">{{ $item->DTAdm_d }}</td>
                                                    <td class="text-center">{{ $item->DTDisch_d }}</td>
                                                    <td class="text-center">{{ $item->LeaveDay }}</td>

                                                    <td class="text-center">{{ $item->DischStat }}</td>
                                                    <td class="text-center">{{ $item->DishType }}</td>
                                                    <td class="text-center">{{ $item->AdmWt }}</td>
                                                    <td class="text-center">{{ $item->DishWard }}</td>
                                                    <td class="text-center">{{ $item->Dept }}</td>
                                                    <td class="text-center">{{ $item->HMAIN }}</td>
                                                    <td class="text-center">{{ $item->ServiceType }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                </p>
                            </div>
                            <div class="tab-pane" id="IPDx" role="tabpanel">
                                <p class="mb-0">
                                <div class="table-responsive">
                                    <table id="key-datatable"
                                        class="table table-striped table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">ลำดับ</th>
                                                <th class="text-center">an</th>
                                                <th class="text-center">sequence</th>
                                                <th class="text-center">DxType</th>
                                                <th class="text-center">CodeSys</th>
                                                <th class="text-center">Dcode</th>
                                                <th class="text-center">DiagTerm</th>
                                                <th class="text-center">DR</th>
                                                <th class="text-center">datediag</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($d_aipdx as $item3)
                                                <tr style="font-size: 12px;">
                                                    <td class="text-center">{{ $i++ }}</td>
                                                    <td class="text-center">{{ $item3->an }} </td>
                                                    <td class="text-center">{{ $item3->sequence }}</td>
                                                    <td class="text-center">{{ $item3->DxType }} </td>
                                                    <td class="text-center">{{ $item3->CodeSys }} </td>
                                                    <td class="text-center">{{ $item3->Dcode }} </td>
                                                    <td class="p-2">{{ $item3->DiagTerm }}</td>
                                                    <td class="text-center">{{ $item3->DR }}</td>
                                                    <td class="text-center">{{ $item3->datediag }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                </p>
                            </div>

                            <div class="tab-pane" id="IPOp" role="tabpanel">
                                <p class="mb-0">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">ลำดับ</th>
                                                <th class="text-center">an</th>
                                                <th class="text-center">sequence</th>
                                                <th class="text-center">CodeSys</th>
                                                <th class="text-center">Code</th>
                                                <th class="text-center">Procterm</th>
                                                <th class="text-center">DR</th>
                                                <th class="text-center">DateIn</th>
                                                <th class="text-center">DateOut</th>
                                                <th class="text-center">Location</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($d_aipop as $item4)
                                                <tr style="font-size: 12px;">
                                                    <td class="text-center">{{ $i++ }}</td>
                                                    <td class="text-center">{{ $item4->an }}</td>
                                                    <td class="text-center">{{ $item4->sequence }} </td>
                                                    <td class="text-center">{{ $item4->CodeSys }} </td>
                                                    <td class="text-center">{{ $item4->Code }} </td>
                                                    <td class="p-2">{{ $item4->Procterm }}</td>
                                                    <td class="p-2">{{ $item4->DR }}</td>
                                                    <td class="text-center">{{ $item4->DateIn }}</td>
                                                    <td class="p-2" width="15%">{{ $item4->DateOut }}</td>
                                                    <td class="text-center">{{ $item4->Location }}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                </p>
                            </div>

                        </div>
 
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-2 ms-3">
                            <div class="col-md-4">
                                <h4 class="card-title">Detail BillItems</h4>
                                <p class="card-title-desc">รายละเอียด BillItems ผู้ป่วยใน</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="ex_1" class="table table-striped" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">ลำดับ</th>
                                                <th class="text-center">AN</th>
                                                {{-- <th class="text-center">sequence</th> --}}
                                                {{-- <th class="text-center">ServDate</th> --}}
                                                {{-- <th class="text-center">ServTime</th> --}}
                                                <th class="text-center">BillGr</th>
                                                <th class="text-center">BillGrCS</th>
                                                <th class="text-center">CodeSys</th>
                                                <th class="text-center">CSCode</th>
                                                <th class="text-center">STDCode</th>
                                                <th class="text-center">ClaimCat</th>
                                                <th class="text-center">LCCode</th>
                                                <th class="text-center">Descript</th>
                                                <th class="text-center">QTY</th>
                                                <th class="text-center">UnitPrice</th>
                                                <th class="text-center">ChargeAmt</th>
                                                {{-- <th class="text-center">Discount</th> 
                                                <th class="text-center">ClaimUP</th> 
                                                <th class="text-center">ClaimAmt</th>  --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($d_abillitems as $item2)
                                                <tr id="sidb{{ $item2->d_abillitems_id }}" style="font-size: 12px;">
                                                    <td class="text-center">{{ $i++ }}</td>
                                                    <td class="text-center">{{ $item2->AN }} </td>
                                                    {{-- <td class="text-center">{{ $item2->sequence }}</td> --}}
                                                    {{-- <td class="text-center">{{ $item2->ServDate }} </td> --}}
                                                    {{-- <td class="text-center">{{ $item2->ServTime }} </td> --}}
                                                    <td class="text-center" width="5%">{{ $item2->BillGr }} </td>
                                                    <td class="text-center" width="5%">{{ $item2->BillGrCS }}</td>
                                                    @if ($item2->CodeSys == 'TMT' && $item2->STDCode == '')
                                                        <td class="text-center" style="background-color: rgb(241, 6, 45)"
                                                            width="5%">{{ $item2->CodeSys }} </td>
                                                    @elseif ($item2->CodeSys == 'TMLT' && $item2->STDCode == '')
                                                        <td class="text-center" style="background-color: rgb(252, 2, 44)"
                                                            width="5%">{{ $item2->CodeSys }} </td>
                                                    @else
                                                        <td class="text-center" width="5%">{{ $item2->CodeSys }}
                                                        </td>
                                                    @endif
                                                    <td class="text-center" width="5%">{{ $item2->CSCode }}</td>
                                                    <td class="text-center" width="5%">{{ $item2->STDCode }} </td>
                                                    <td class="text-center" width="5%">{{ $item2->ClaimCat }} </td>
                                                    <td class="text-center" width="10%">
                                                        {{-- <a href="javascript:void(0)" onclick="aipn_billitems_destroy({{ $item2->aipn_billitems_id }})" 
                                                            data-bs-toggle="tooltip" data-bs-placement="left" title="ลบ" 
                                                            class="btn btn-outline-danger btn-sm"> 
                                                            <i class="fa-solid fa-trash-can text-danger me-2"></i> 
                                                            {{ $item2->LCCode }} 
                                                        </a>   --}}
                                                        <a href="{{ url('aipn_billitems_destroy/' . $item2->d_abillitems_id) }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="left"
                                                            title="ลบ" class="btn btn-outline-danger btn-sm">
                                                            <i class="fa-solid fa-trash-can text-danger me-2"></i>
                                                            {{ $item2->LCCode }}
                                                        </a>
                                                    </td>
                                                    <td class="p-2">{{ $item2->Descript }}</td>
                                                    <td class="text-center" width="5%">{{ $item2->QTY }}</td>
                                                    <td class="text-center" width="5%">
                                                        {{ number_format($item2->UnitPrice, 2) }}</td>
                                                    <td class="text-center" width="5%">
                                                        {{ number_format($item2->ChargeAmt, 2) }}</td>
                                                    {{-- <td class="text-center" width="5%">{{ number_format($item2->Discount, 2) }}</td> 
                                                    <td class="text-center" width="5%">{{ number_format($item2->ClaimUP, 2) }}</td>
                                                    <td class="text-center" width="5%">{{ number_format($item2->ClaimAmt, 2) }}</td>  --}}
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

            $('#ex_1').DataTable();
            $('#ex_2').DataTable();
            $('#ex_3').DataTable();

            $("#spinner-div").hide(); //Request is complete so hide spinner
           
            $('#Searchdata').click(function() {
                    var datepicker = $('#datepicker').val(); 
                    var datepicker2 = $('#datepicker2').val(); 
                    Swal.fire({
                            title: 'ต้องการดึงข้อมูลใช่ไหม ?',
                            text: "You Warn Pull Data!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, pull it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $("#overlay").fadeIn(300);　
                                    $("#spinner").show(); //Load button clicked show spinner 
                                    
                                    $.ajax({
                                        url: "{{ route('claim.aipn_main') }}",
                                        type: "POST",
                                        dataType: 'json',
                                        data: {
                                            datepicker,
                                            datepicker2                        
                                        },
                                        success: function(data) {
                                            if (data.status == 200) { 
                                                Swal.fire({
                                                    title: 'ดึงข้อมูลสำเร็จ',
                                                    text: "You Pull data success",
                                                    icon: 'success',
                                                    showCancelButton: false,
                                                    confirmButtonColor: '#06D177',
                                                    confirmButtonText: 'เรียบร้อย'
                                                }).then((result) => {
                                                    if (result
                                                        .isConfirmed) {
                                                        console.log(
                                                            data);
                                                        window.location.reload();
                                                        $('#spinner').hide();//Request is complete so hide spinner
                                                            setTimeout(function(){
                                                                $("#overlay").fadeOut(300);
                                                            },500);
                                                    }
                                                })
                                            } else {
                                                
                                            }
                                        },
                                    });
                                    
                                }
                    })
            });
            $('#Exportdata').click(function() {
                    var datepicker = $('#datepicker').val(); 
                    var datepicker2 = $('#datepicker2').val(); 
                    Swal.fire({
                            title: 'ต้องการส่งออกข้อมูลใช่ไหม ?',
                            text: "You Warn Export Data!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, pull it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $("#overlay").fadeIn(300);　
                                    $("#spinner").show(); //Load button clicked show spinner 
                                    
                                    $.ajax({
                                        url: "{{ route('claim.aipn_export') }}",
                                        type: "POST",
                                        dataType: 'json',
                                        data: {
                                            datepicker,
                                            datepicker2                        
                                        },
                                        success: function(data) {
                                            if (data.status == 200) { 
                                                Swal.fire({
                                                    title: 'ส่งออกข้อมูลสำเร็จ',
                                                    text: "You Export data success",
                                                    icon: 'success',
                                                    showCancelButton: false,
                                                    confirmButtonColor: '#06D177',
                                                    confirmButtonText: 'เรียบร้อย'
                                                }).then((result) => {
                                                    if (result
                                                        .isConfirmed) {
                                                        console.log(
                                                            data);
                                                        window.location.reload();
                                                        $('#spinner').hide();//Request is complete so hide spinner
                                                            setTimeout(function(){
                                                                $("#overlay").fadeOut(300);
                                                            },500);
                                                    }
                                                })
                                            } else {
                                                
                                            }
                                        },
                                    });
                                    
                                }
                    })
            });

            $('#Processdata').click(function() {
                    var datepicker = $('#datepicker').val(); 
                    var datepicker2 = $('#datepicker2').val(); 
                    Swal.fire({
                            title: 'ต้องการประมวลผลข้อมูลใช่ไหม ?',
                            text: "You Warn Process Data!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, pull it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $("#overlay").fadeIn(300);　
                                    $("#spinner").show(); //Load button clicked show spinner 
                                    
                                    $.ajax({
                                        url: "{{ route('claim.aipn_process') }}",
                                        type: "POST",
                                        dataType: 'json',
                                        data: {
                                            datepicker,
                                            datepicker2                        
                                        },
                                        success: function(data) {
                                            if (data.status == 200) { 
                                                Swal.fire({
                                                    title: 'ประมวลผลข้อมูลสำเร็จ',
                                                    text: "You Process data success",
                                                    icon: 'success',
                                                    showCancelButton: false,
                                                    confirmButtonColor: '#06D177',
                                                    confirmButtonText: 'เรียบร้อย'
                                                }).then((result) => {
                                                    if (result
                                                        .isConfirmed) {
                                                        console.log(
                                                            data);
                                                        window.location.reload();
                                                        $('#spinner').hide();//Request is complete so hide spinner
                                                            setTimeout(function(){
                                                                $("#overlay").fadeOut(300);
                                                            },500);
                                                    }
                                                })
                                            } else {
                                                
                                            }
                                        },
                                    });
                                    
                                }
                    })
            });

            $('#Zipdata').click(function() {
                    var datepicker = $('#datepicker').val(); 
                    var datepicker2 = $('#datepicker2').val(); 
                    Swal.fire({
                            title: 'ต้องการสร้างไฟล์ Zip ใช่ไหม ?',
                            text: "You Warn Create Zip File!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, pull it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $("#overlay").fadeIn(300);　
                                    $("#spinner").show(); //Load button clicked show spinner 
                                    
                                    $.ajax({
                                        url: "{{ route('claim.aipn_zip') }}",
                                        type: "POST",
                                        dataType: 'json',
                                        data: {
                                            datepicker,
                                            datepicker2                        
                                        },
                                        success: function(data) {
                                            if (data.status == 200) { 
                                                Swal.fire({
                                                    title: 'สร้างไฟล์ Zip สำเร็จ',
                                                    text: "You Create Zip File success",
                                                    icon: 'success',
                                                    showCancelButton: false,
                                                    confirmButtonColor: '#06D177',
                                                    confirmButtonText: 'เรียบร้อย'
                                                }).then((result) => {
                                                    if (result
                                                        .isConfirmed) {
                                                        console.log(
                                                            data);
                                                        window.location.reload();
                                                        $('#spinner').hide();//Request is complete so hide spinner
                                                            setTimeout(function(){
                                                                $("#overlay").fadeOut(300);
                                                            },500);
                                                    }
                                                })
                                            } else {
                                                
                                            }
                                        },
                                    });
                                    
                                }
                    })
            });





        });
    </script>
@endsection
