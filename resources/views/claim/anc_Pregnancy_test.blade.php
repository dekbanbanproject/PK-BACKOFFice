@extends('layouts.pkclaim')
@section('title', 'PK-BACKOFFice || KTB')

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
        <div id="preloader">
            <div id="status">
                <div class="spinner">

                </div>
            </div>
        </div>
 
        <form action="{{ route('k.anc_Pregnancy_testsearch') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-2 text-end"></div>
                <div class="col-md-1 text-end">วันที่</div>
                <div class="col-md-6 text-center">
                    <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy"
                        data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker6'>
                        <input type="text" class="form-control" name="startdate" id="datepicker" placeholder="Start Date"
                            data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true"
                            data-date-language="th-th" value="{{ $start }}" />
                        <input type="text" class="form-control" name="enddate" placeholder="End Date" id="datepicker2"
                            data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true"
                            data-date-language="th-th" value="{{ $end }}" />
                        <button type="submit" class="btn btn-info">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            ดึงข้อมูล
                        </button>
                        <a href="{{ url('ssop_send') }}" class="btn btn-success"><i
                                class="fa-solid fa-arrow-up-right-from-square me-2"></i>ส่งออก</a>
                        {{-- <a href="{{ url('ssop_zip') }}" class="btn btn-danger"><i
                                class="fa-solid fa-file-zipper me-2"></i>ZipFile</a> --}}
                    </div>
                </div>
                <div class="col"></div>
            </div>
        </form>


        <div class="row mt-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <h4 class="card-title">Detail KTB</h4>
                                <p class="card-title-desc">รายละเอียด</p>
                            </div>
                            <div class="col"></div>
                            <div class="col-md-3 text-end">

                                {{-- <button type="button" class="btn btn-outline-danger btn-sm Updateprescb"
                                    data-url="{{ url('ssop_prescb_update') }}">
                                    <i class="fa-solid fa-file-waveform me-2"></i>
                                    Update Prescb
                                </button> --}}
                                {{-- <button type="button" class="btn btn-outline-warning btn-sm Updatesvpid"
                                    data-url="{{ url('ssop_svpid_update') }}">
                                    <i class="fa-solid fa-file-waveform me-2"></i>
                                    Update SvPID
                                </button> --}}
                            </div>
                        </div>



                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#Ins" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">INS</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#Pat" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">PAT</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#Opd" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">OPD</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#Odx" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">ODX</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#Adp" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">ADP</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#Dru" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                    <span class="d-none d-sm-block">DRU</span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="Ins" role="tabpanel">
                                <p class="mb-0">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">ลำดับ</th>
                                                <th class="text-center" width="5%">HN</th>
                                                <th class="text-center">INSCL</th>
                                                <th class="text-center">SUBTYPE</th>
                                                <th class="text-center">CID</th>
                                                <th class="text-center">DATEIN</th>
                                                <th class="text-center">DATEEXP</th>
                                                <th class="text-center">HOSPMAIN</th>
                                                <th class="text-center" width="7%">HOSPSUB</th>
                                                <th class="text-center">GOVCODE</th>
                                                <th class="text-center">GOVNAME </th>
                                                <th class="text-center" width="8%">PERMITNO</th>
                                                <th class="text-center" width="8%">DOCNO</th>
                                                <th class="text-center" width="10%">OWNRPID</th>
                                                <th class="text-center">OWNRNAME</th>
                                                <th class="text-center">AN</th>
                                                <th class="text-center">SEQ</th>
                                                <th class="text-center">SUBINSCL</th>
                                                <th class="text-center">RELINSCL</th>
                                                <th class="text-center">HTYPE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($ins_ as $item)
                                                <tr>
                                                    <td class="text-center">{{ $i++ }}</td>
                                                    <td class="text-center" width="5%">{{ $item->HN }}</td>
                                                    <td class="text-center">{{ $item->INSCL }}</td>
                                                    <td class="text-center">{{ $item->SUBTYPE }}</td>
                                                    <td class="text-center">{{ $item->CID }}</td>
                                                    <td class="text-center">{{ $item->DATEIN }}</td>
                                                    <td class="text-center">{{ $item->DATEEXP }}</td>
                                                    <td class="text-center">{{ $item->HOSPMAIN }}</td>
                                                    <td class="text-center">{{ $item->HOSPSUB }}</td>
                                                    <td class="text-center">{{ $item->GOVCODE }}</td>
                                                    <td class="p-2">{{ $item->GOVNAME }}</td>
                                                    <td class="text-center">{{ $item->PERMITNO }}</td>
                                                    <td class="text-center">{{ $item->DOCNO }}</td>
                                                    <td class="text-center">{{ $item->OWNRPID }}</td>
                                                    <td class="text-center">{{ $item->OWNRNAME }}</td>
                                                    <td class="text-center">{{ $item->AN }}</td>
                                                    <td class="text-center">{{ $item->SEQ }}</td>
                                                    <td class="text-center">{{ $item->SUBINSCL }}</td>
                                                    <td class="text-center">{{ $item->RELINSCL }}</td>
                                                    <td class="text-center">{{ $item->HTYPE }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                </p>
                            </div>

                            <div class="tab-pane" id="Pat" role="tabpanel">
                                <p class="mb-0">
                                <div class="table-responsive">
                                    <table id="selection-datatable"
                                        class="table table-striped table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">ลำดับ</th>
                                                <th class="text-center">Invno</th>
                                                <th class="text-center">SvDate</th>
                                                <th class="text-center">BillMuad</th>
                                                <th class="text-center">LCCode</th>
                                                <th class="text-center">STDCode</th>
                                                <th class="text-center">Desc</th>
                                                <th class="text-center">QTY</th>
                                                <th class="text-center">UnitPrice</th>
                                                <th class="text-center">ChargeAmt</th>
                                                <th class="text-center">ClaimUP</th>
                                                <th class="text-center">ClaimAmount</th>
                                                <th class="text-center">SvRefID</th>
                                                <th class="text-center">ClaimCat</th>
                                                <th class="text-center">paidst</th>
                                            </tr>
                                        </thead>
                                        {{-- <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($ssop_billitems as $item2)
                                                <tr>
                                                    <td class="text-center">{{ $i++ }}</td>
                                                    <td class="text-center">{{ $item2->Invno }} </td>
                                                    <td class="text-center">{{ $item2->SvDate }}</td>
                                                    <td class="text-center">{{ $item2->BillMuad }} </td>
                                                    <td class="text-center">{{ $item2->LCCode }} </td>
                                                    <td class="text-center">{{ $item2->STDCode }} </td>
                                                    <td class="p-2">{{ $item2->Desc }}</td>
                                                    <td class="text-center">{{ $item2->QTY }}</td>
                                                    <td class="text-center">{{ number_format($item2->UnitPrice, 2) }}</td>
                                                    <td class="text-center">{{ number_format($item2->ChargeAmt, 2) }}</td>
                                                    <td class="text-center">{{ number_format($item2->ClaimUP, 2) }}</td>
                                                    <td class="text-center">{{ number_format($item2->ClaimAmount, 2) }}
                                                    </td>
                                                    <td class="text-center">{{ $item2->SvRefID }}</td>
                                                    <td class="text-center">{{ $item2->ClaimCat }}</td>
                                                    <td class="text-center">{{ $item2->paidst }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody> --}}
                                    </table>
                                </div>
                                </p>
                            </div>

                            <div class="tab-pane" id="Opd" role="tabpanel">
                                <p class="mb-0">
                                <div class="table-responsive">
                                    <table id="key-datatable"
                                        class="table table-striped table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">ลำดับ</th>
                                                <th class="text-center">ProviderID</th>
                                                <th class="text-center">DispID</th>
                                                <th class="text-center">Invno</th>
                                                <th class="text-center">HN</th>
                                                <th class="text-center">PID</th>
                                                <th class="text-center">Prescdt</th>
                                                <th class="text-center">Dispdt</th>
                                                <th class="text-center"><input type="checkbox" name="stamp"
                                                        id="stamp" class="me-2">Prescb</th>
                                                <th class="text-center">Itemcnt</th>
                                                <th class="text-center">ChargeAmt</th>
                                                <th class="text-center">ClaimAmt</th>
                                                <th class="text-center">Paid</th>
                                                <th class="text-center">OtherPay</th>
                                                <th class="text-center">Reimburser</th>
                                                <th class="text-center">BenefitPlan</th>
                                                <th class="text-center">DispeStat</th>
                                                <th class="text-center">SvID</th>
                                                <th class="text-center">DayCover</th>
                                            </tr>
                                        </thead>
                                        {{-- <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($ssop_dispensing as $item3)
                                                <tr id="prescbid{{ $item3->ssop_dispensing_id }}">
                                                    <td class="text-center">{{ $i++ }}</td>
                                                    <td class="text-center">{{ $item3->ProviderID }} </td>
                                                    <td class="text-center">{{ $item3->DispID }}</td>
                                                    <td class="text-center">{{ $item3->Invno }} </td>
                                                    <td class="text-center">{{ $item3->HN }} </td>
                                                    <td class="text-center">{{ $item3->PID }} </td>
                                                    <td class="text-center">{{ $item3->Prescdt }}</td>
                                                    <td class="text-center">{{ $item3->Dispdt }}</td>
                                                    <td class="p-2">
                                                        <input type="checkbox" class="sub_chk me-2"
                                                            data-id="{{ $item3->ssop_dispensing_id }}">
                                                        <button
                                                            type="button"class="btn btn-outline-danger btn-sm Edit_prescb"
                                                            value="{{ $item3->ssop_dispensing_id }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="left"
                                                            title="แก้ไข">
                                                            <i class="fa-solid fa-pen-to-square me-2 text-danger"></i>
                                                            <label for="" class="text-danger"
                                                                style="font-size:13px;"> {{ $item3->Prescb }}</label>
                                                        </button>
                                                    </td>
                                                    <td class="text-center">{{ $item3->Itemcnt }}</td>
                                                    <td class="text-center">{{ number_format($item3->ChargeAmt, 2) }}</td>
                                                    <td class="text-center">{{ number_format($item3->ClaimAmt, 2) }}</td>
                                                    <td class="text-center">{{ number_format($item3->Paid, 2) }}</td>
                                                    <td class="text-center">{{ number_format($item3->OtherPay, 2) }}</td>
                                                    <td class="text-center">{{ $item3->Reimburser }}</td>
                                                    <td class="text-center">{{ $item3->BenefitPlan }}</td>
                                                    <td class="text-center">{{ $item3->DispeStat }}</td>
                                                    <td class="text-center">{{ $item3->SvID }}</td>
                                                    <td class="text-center">{{ $item3->DayCover }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody> --}}
                                    </table>
                                </div>
                                </p>
                            </div>

                            <div class="tab-pane" id="Odx" role="tabpanel">
                                <p class="mb-0">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">ลำดับ</th>
                                                <th class="text-center">DispID</th>
                                                <th class="text-center">PrdCat</th>
                                                <th class="text-center">HospDrgID</th>
                                                <th class="text-center">DrgID</th>
                                                <th class="text-center">dfsText</th>
                                                <th class="text-center">Packsize</th>
                                                <th class="text-center">sigCode</th>
                                                <th class="text-center">sigText</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">UnitPrice</th>
                                                <th class="text-center">ChargeAmt</th>
                                                <th class="text-center">ReimbPrice</th>
                                                <th class="text-center">ReimbAmt</th>
                                                <th class="text-center">PrdSeCode</th>
                                                <th class="text-center">Claimcont</th>
                                                <th class="text-center">ClaimCat</th>
                                                <th class="text-center">paidst</th>
                                            </tr>
                                        </thead>
                                        {{-- <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($ssop_dispenseditems as $item4)
                                                <tr>
                                                    <td class="text-center">{{ $i++ }}</td>
                                                    <td class="text-center">{{ $item4->DispID }}</td>
                                                    <td class="text-center">{{ $item4->PrdCat }} </td>
                                                    <td class="text-center">{{ $item4->HospDrgID }} </td>
                                                    <td class="text-center">{{ $item4->DrgID }} </td>
                                                    <td class="p-2">{{ $item4->dfsText }}</td>
                                                    <td class="p-2">{{ $item4->Packsize }}</td>
                                                    <td class="text-center">{{ $item4->sigCode }}</td>
                                                    <td class="p-2" width="15%">{{ $item4->sigText }}</td>
                                                    <td class="text-center">{{ $item4->Quantity }}</td>
                                                    <td class="text-center">{{ number_format($item4->UnitPrice, 2) }}</td>
                                                    <td class="text-center">{{ number_format($item4->ChargeAmt, 2) }}</td>
                                                    <td class="text-center">{{ number_format($item4->ReimbPrice, 2) }}
                                                    </td>
                                                    <td class="text-center">{{ number_format($item4->ReimbAmt, 2) }}</td>
                                                    <td class="text-center">{{ $item4->PrdSeCode }}</td>
                                                    <td class="text-center">{{ $item4->Claimcont }}</td>
                                                    <td class="text-center">{{ $item4->ClaimCat }}</td>
                                                    <td class="text-center">{{ $item4->paidst }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody> --}}
                                    </table>
                                </div>
                                </p>
                            </div>
                            <div class="tab-pane" id="Adp" role="tabpanel">
                                <p class="mb-0">
                                <div class="table-responsive">
                                    <table id="example2" class="table table-striped table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">ลำดับ</th>
                                                <th class="text-center">Invno</th>
                                                <th class="text-center">SvID</th>
                                                <th class="text-center">Class</th>
                                                <th class="text-center">Hcode</th>
                                                <th class="text-center">HN</th>
                                                <th class="text-center">PID</th>
                                                <th class="text-center">CareAccount</th>
                                                <th class="text-center">TypeServ</th>
                                                <th class="text-center">TypeIn</th>
                                                <th class="text-center">TypeOut</th>
                                                <th class="text-center">DTAppoint</th>
                                                <th class="p-2"><input type="checkbox" name="stamp2" id="stamp2"
                                                        class="me-2">SvPID</th>
                                                <th class="text-center">Clinic</th>
                                                <th class="text-center">BegDT</th>
                                                <th class="text-center">EndDT</th>
                                                <th class="text-center">LcCode</th>
                                                <th class="text-center">CodeSet</th>
                                                <th class="text-center">STDCode</th>
                                                <th class="text-center">SvCharge</th>
                                                <th class="text-center">Completion</th>
                                                <th class="text-center">SvTxCode</th>
                                                <th class="text-center">ClaimCat</th>
                                            </tr>
                                        </thead>
                                        {{-- <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($ssop_opservices as $item5)
                                                <tr id="prescbid{{ $item5->ssop_opservices_id }}">
                                                    <td class="text-center">{{ $i++ }}</td>
                                                    <td class="text-center">{{ $item5->Invno }}</td>
                                                    <td class="text-center">{{ $item5->SvID }} </td>
                                                    <td class="text-center">{{ $item5->Class }} </td>
                                                    <td class="text-center">{{ $item5->Hcode }} </td>
                                                    <td class="p-2">{{ $item5->HN }}</td>
                                                    <td class="p-2">{{ $item5->PID }}</td>
                                                    <td class="text-center">{{ $item5->CareAccount }}</td>
                                                    <td class="p-2">{{ $item5->TypeServ }}</td>
                                                    <td class="text-center">{{ $item5->TypeIn }}</td>
                                                    <td class="text-center">{{ $item5->TypeOut }}</td>
                                                    <td class="text-center">{{ $item5->DTAppoint }}</td>
                                                    <td class="p-2">
                                                        <input type="checkbox" class="sub_chk2 me-2"
                                                            data-id="{{ $item5->ssop_opservices_id }}">
                                                        <button
                                                            type="button"class="btn btn-outline-warning btn-sm Edit_svpid"
                                                            value="{{ $item5->ssop_opservices_id }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="left"
                                                            title="แก้ไข">
                                                            <i class="fa-solid fa-pen-to-square me-2 text-warning"></i>
                                                            <label for="" class="text-warning"
                                                                style="font-size:13px;"> {{ $item5->SvPID }}</label>
                                                        </button>
                                                    </td>
                                                    <td class="text-center">{{ $item5->Clinic }}</td>
                                                    <td class="text-center" width="15%">{{ $item5->BegDT }}</td>
                                                    <td class="text-center" width="15%">{{ $item5->EndDT }}</td>
                                                    <td class="text-center">{{ $item5->LcCode }}</td>
                                                    <td class="text-center">{{ $item5->CodeSet }}</td>
                                                    <td class="text-center">{{ $item5->STDCode }}</td>
                                                    <td class="text-center">{{ $item5->SvCharge }}</td>
                                                    <td class="text-center">{{ $item5->Completion }}</td>
                                                    <td class="text-center">{{ $item5->SvTxCode }}</td>
                                                    <td class="text-center">{{ $item5->ClaimCat }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody> --}}
                                    </table>
                                </div>
                                </p>
                            </div>
                            <div class="tab-pane" id="Dru" role="tabpanel">
                                <p class="mb-0">
                                <div class="table-responsive">
                                    <table id="example3" class="table table-striped table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">ลำดับ</th>
                                                <th class="text-center">Class</th>
                                                <th class="text-center">SvID</th>
                                                <th class="text-center" width="15%">SL</th>
                                                <th class="text-center" width="15%">CodeSet</th>
                                                <th class="text-center">code</th>
                                                <th class="text-center">Desc</th>
                                            </tr>
                                        </thead>
                                        {{-- <tbody>
                                            <?php $i = 1; ?>
                                            @foreach ($ssop_opdx_ as $item6)
                                                <tr>
                                                    <td class="text-center">{{ $i++ }}</td>
                                                    <td class="text-center">{{ $item6->Class }}</td>
                                                    <td class="text-center">{{ $item6->SvID }} </td>
                                                    <td class="text-center">{{ $item6->SL }} </td>
                                                    <td class="text-center">{{ $item6->CodeSet }} </td>
                                                    <td class="text-center">{{ $item6->code }}</td>
                                                    <td class="text-center">{{ $item6->Desc }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody> --}}

                                    </table>
                                </div>
                                </p>
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

            $('#stamp').on('click', function(e) {
                if ($(this).is(':checked', true)) {
                    $(".sub_chk").prop('checked', true);
                } else {
                    $(".sub_chk").prop('checked', false);
                }
            });
            $('.Updateprescb').on('click', function(e) {
                // alert('oo');
                var allValls = [];
                $(".sub_chk:checked").each(function() {
                    allValls.push($(this).attr('data-id'));
                });
                if (allValls.length <= 0) {
                    // alert("SSSS");
                    Swal.fire({
                        title: 'คุณยังไม่ได้เลือกรายการ ?',
                        text: "กรุณาเลือกรายการก่อน",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                    }).then((result) => {

                    })
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "คุณต้องการปรับรายการที่เลือกใช่ไหม!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, UPdate it.!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var check = true;
                            if (check == true) {
                                var join_selected_values = allValls.join(",");
                                $.ajax({
                                    url: $(this).data('url'),
                                    type: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                            'content')
                                    },
                                    data: 'ids=' + join_selected_values,
                                    success: function(data) {
                                        if (data.status == 200) {
                                            $(".sub_chk:checked").each(function() {
                                                $(this).parents("tr").remove();
                                            });
                                            Swal.fire({
                                                title: 'ปรับ Prescbสำเร็จ',
                                                text: "You Debtor data success",
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
                                                    // window.location="{{ url('warehouse/warehouse_index') }}";
                                                }
                                            })
                                        } else {

                                        }
                                    }
                                });
                                $.each(allValls, function(index, value) {
                                    $('table tr').filter("[data-row-id='" + value + "']")
                                        .remove();
                                });
                            }
                        }
                    })

                }
            });


            $('#stamp2').on('click', function(e) {
                if ($(this).is(':checked', true)) {
                    $(".sub_chk2").prop('checked', true);
                } else {
                    $(".sub_chk2").prop('checked', false);
                }
            });
            $('.Updatesvpid').on('click', function(e) {
                // alert('oo');
                var allValls = [];
                $(".sub_chk2:checked").each(function() {
                    allValls.push($(this).attr('data-id'));
                });
                if (allValls.length <= 0) {
                    // alert("SSSS");
                    Swal.fire({
                        title: 'คุณยังไม่ได้เลือกรายการ ?',
                        text: "กรุณาเลือกรายการก่อน",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                    }).then((result) => {

                    })
                } else {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "คุณต้องการปรับรายการที่เลือกใช่ไหม!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, UPdate it.!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var check = true;
                            if (check == true) {
                                var join_selected_values = allValls.join(",");
                                $.ajax({
                                    url: $(this).data('url'),
                                    type: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                            'content')
                                    },
                                    data: 'ids2=' + join_selected_values,
                                    success: function(data) {
                                        if (data.status == 200) {
                                            $(".sub_chk2:checked").each(function() {
                                                $(this).parents("tr").remove();
                                            });
                                            Swal.fire({
                                                title: 'ปรับ SvPID สำเร็จ',
                                                text: "You Debtor data success",
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
                                                    // window.location="{{ url('warehouse/warehouse_index') }}";
                                                }
                                            })
                                        } else {

                                        }
                                    }
                                });
                                $.each(allValls, function(index, value) {
                                    $('table tr').filter("[data-row-id='" + value + "']")
                                        .remove();
                                });
                            }
                        }
                    })

                }
            });
        });
        $(document).on('click', '.Edit_prescb', function() {
            var ssop_dispensing_id = $(this).val();
            $.ajax({
                type: "POST",
                url: "{{ url('ssop_edit_prescb') }}" + '/' + ssop_dispensing_id,
                success: function(data) {
                    if (data.status == 200) {
                        Swal.fire({
                            title: 'แก้ไขข้อมูลสำเร็จ',
                            text: "You edit data success",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#06D177',
                            confirmButtonText: 'เรียบร้อย'
                        }).then((result) => {
                            if (result
                                .isConfirmed) {
                                console.log(
                                    data);

                                window.location
                                    .reload();
                            }
                        })
                    } else {

                    }
                },
            });
        });

        $(document).on('click', '.Edit_svpid', function() {
            var ssop_opservices_id = $(this).val();
            $.ajax({
                type: "POST",
                url: "{{ url('ssop_edit_svpid') }}" + '/' + ssop_opservices_id,
                success: function(data) {
                    if (data.status == 200) {
                        Swal.fire({
                            title: 'แก้ไขข้อมูลสำเร็จ',
                            text: "You edit data success",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#06D177',
                            confirmButtonText: 'เรียบร้อย'
                        }).then((result) => {
                            if (result
                                .isConfirmed) {
                                console.log(
                                    data);

                                window.location
                                    .reload();
                            }
                        })
                    } else {

                    }
                },
            });
        });
    </script>
@endsection
