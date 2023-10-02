@extends('layouts.pkclaim')
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
    .modal-dis {
        width: 1350px;
        margin: auto;
    }
    @media (min-width: 1200px) {
        .modal-xlg {
            width: 90%; 
        }
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
    <form action="{{ url('ucep24_claim') }}" method="POST">
            @csrf
    <div class="row"> 
            <div class="col"></div>
            <div class="col-md-1 text-end mt-2">วันที่</div>
            <div class="col-md-4 text-end">
                <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker1'>
                    <input type="text" class="form-control" name="startdate" id="datepicker" placeholder="Start Date" data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                        data-date-language="th-th" value="{{ $startdate }}" required/>
                    <input type="text" class="form-control" name="enddate" placeholder="End Date" id="datepicker2" data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                        data-date-language="th-th" value="{{ $enddate }}"/>  
                </div> 
            </div>
            <div class="col-md-3"> 
                    <button type="submit" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info">
                        <i class="fa-solid fa-magnifying-glass text-info me-2"></i>
                        ค้นหา
                    </button> 
                    <a href="" class="me-2 btn-icon btn-shadow btn-dashed btn btn-outline-info">
                        <i class="fa-solid fa-magnifying-glass text-info me-2"></i>
                        ประมวลผล
                    </a>
                  
            </div>
          
        </div>
    </form>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    รายละเอียด UCEP 24
                    <div class="btn-actions-pane-right">
                            
                    </div>
                </div>
                <div class="card-body">
                    <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#Main" role="tab">
                                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                    <span class="d-none d-sm-block">VN UCEP24</span>    
                                </a>
                            </li>   
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#OPD" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">OPD</span>    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#ORF" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">ORF</span>    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#OOP" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">OOP</span>    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#ODX" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">OOP</span>    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#IDX" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">IDX</span>    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#IPD" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">IPD</span>    
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#IRF" role="tab">
                                    <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                    <span class="d-none d-sm-block">IRF</span>    
                                </a>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content p-3 text-muted">
                            <div class="tab-pane active" id="Main" role="tabpanel">
                                <p class="mb-0">
                                    <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ลำดับ</th>
                                                <th class="text-center">an</th>
                                                <th class="text-center">hn</th>
                                                <th class="text-center">vn</th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $number = 0; ?>
                                            @foreach ($data_main as $item1)
                                            <?php $number++; ?>
                
                                            <tr height="20" style="font-size: 14px;">
                                                <td class="text-font" style="text-align: center;" width="5%">{{ $number }}</td>
                                                <td class="text-center" width="10%">  {{ $item1->an }}  </td>
                                                <td class="text-center" width="10%">{{ $item1->hn }}</td>
                                                <td class="text-center" width="10%">{{ $item1->vn }}</td>  
                                            </tr>
                
                
                
                                            @endforeach
                
                                        </tbody>
                                    </table>
                                </p>
                            </div>
                            <div class="tab-pane" id="OPD" role="tabpanel">
                                <p class="mb-0">
                                    <table id="example2" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ลำดับ</th>
                                                <th class="text-center">HN</th>
                                                <th class="text-center">CLINIC</th>
                                                <th class="text-center">DATEOPD</th>  
                                                <th class="text-center">TIMEOPD</th> 
                                                <th class="text-center">SEQ</th> 
                                                <th class="text-center">UUC</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 0; ?>
                                            @foreach ($data_opd as $itemo)
                                            <?php $i++; ?> 
                                                <tr height="20" style="font-size: 14px;">
                                                    <td class="text-font" style="text-align: center;" width="5%">{{ $i }}</td>
                                                    <td class="text-center" width="10%">  {{ $itemo->HN }}  </td>
                                                    <td class="text-center" width="10%">{{ $itemo->CLINIC }}</td>
                                                    <td class="text-center" width="10%">{{ $itemo->DATEOPD }}</td>  
                                                    <td class="text-center" width="10%">{{ $itemo->TIMEOPD }}</td> 
                                                    <td class="text-center" width="10%">{{ $itemo->SEQ }}</td> 
                                                    <td class="text-center" width="10%">{{ $itemo->UUC }}</td> 
                                                </tr>  
                                            @endforeach 
                                        </tbody>
                                    </table>
                                </p>
                            </div>
                            <div class="tab-pane" id="ORF" role="tabpanel">
                                <p class="mb-0">
                                    <table id="example3" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ลำดับ</th>
                                                <th class="text-center">HN</th>
                                                <th class="text-center">CLINIC</th>
                                                <th class="text-center">DATEOPD</th>  
                                                <th class="text-center">REFER</th> 
                                                <th class="text-center">SEQ</th> 
                                                <th class="text-center">REFERTYPE</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $a = 0; ?>
                                            @foreach ($data_orf as $itemorf)
                                            <?php $a++; ?> 
                                                <tr height="20" style="font-size: 14px;">
                                                    <td class="text-font" style="text-align: center;" width="5%">{{ $a }}</td>
                                                    <td class="text-center" width="10%"> {{ $itemorf->HN }}  </td>
                                                    <td class="text-center" width="10%">{{ $itemorf->CLINIC }}</td>
                                                    <td class="text-center" width="10%">{{ $itemorf->DATEOPD }}</td>  
                                                    <td class="text-center" width="10%">{{ $itemorf->REFER }}</td> 
                                                    <td class="text-center" width="10%">{{ $itemorf->SEQ }}</td> 
                                                    <td class="text-center" width="10%">{{ $itemorf->REFERTYPE }}</td> 
                                                </tr>  
                                            @endforeach 
                                        </tbody>
                                    </table>
                                </p>
                            </div>
                            <div class="tab-pane" id="OOP" role="tabpanel">
                                <p class="mb-0">
                                    <table id="example4" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ลำดับ</th>
                                                <th class="text-center">HN</th>
                                                <th class="text-center">CLINIC</th>
                                                <th class="text-center">DATEOPD</th>  
                                                <th class="text-center">OPER</th> 
                                                <th class="text-center">DROPID</th> 
                                                <th class="text-center">PERSON_ID</th> 
                                                <th class="text-center">SEQ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $b = 0; ?>
                                            @foreach ($data_oop as $itemoop)
                                            <?php $b++; ?> 
                                                <tr height="20" style="font-size: 14px;">
                                                    <td class="text-font" style="text-align: center;" width="5%">{{ $b }}</td>
                                                    <td class="text-center" width="10%">{{ $itemoop->HN }}</td>
                                                    <td class="text-center" width="10%">{{ $itemoop->CLINIC }}</td>
                                                    <td class="text-center" width="10%">{{ $itemoop->DATEOPD }}</td>  
                                                    <td class="text-center" width="10%">{{ $itemoop->OPER }}</td> 
                                                    <td class="text-center" width="10%">{{ $itemoop->DROPID }}</td> 
                                                    <td class="text-center" width="10%">{{ $itemoop->PERSON_ID }}</td> 
                                                    <td class="text-center" width="10%">{{ $itemoop->SEQ }}</td> 
                                                </tr>  
                                            @endforeach 
                                        </tbody>
                                    </table>
                                </p>
                            </div>
                            <div class="tab-pane" id="ODX" role="tabpanel">
                                <p class="mb-0">
                                    <table id="example5" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ลำดับ</th>
                                                <th class="text-center">HN</th>
                                                <th class="text-center">CLINIC</th>
                                                <th class="text-center">DATEDX</th>  
                                                <th class="text-center">DIAG</th> 
                                                <th class="text-center">DXTYPE</th> 
                                                <th class="text-center">DRDX</th> 
                                                <th class="text-center">PERSON_ID</th>
                                                <th class="text-center">SEQ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $c = 0; ?>
                                            @foreach ($data_odx as $itemodx)
                                            <?php $c++; ?> 
                                                <tr height="20" style="font-size: 14px;">
                                                    <td class="text-font" style="text-align: center;" width="5%">{{ $c }}</td>
                                                    <td class="text-center" width="10%">{{ $itemodx->HN }}</td>
                                                    <td class="text-center" width="10%">{{ $itemodx->CLINIC }}</td>
                                                    <td class="text-center" width="10%">{{ $itemodx->DATEDX }}</td>  
                                                    <td class="text-center" width="10%">{{ $itemodx->DIAG }}</td> 
                                                    <td class="text-center" width="10%">{{ $itemodx->DXTYPE }}</td> 
                                                    <td class="text-center" width="10%">{{ $itemodx->DRDX }}</td> 
                                                    <td class="text-center" width="10%">{{ $itemodx->PERSON_ID }}</td> 
                                                    <td class="text-center" width="10%">{{ $itemodx->SEQ }}</td> 
                                                </tr>  
                                            @endforeach 
                                        </tbody>
                                    </table>
                                </p>
                            </div>
                            <div class="tab-pane" id="IDX" role="tabpanel">
                                <p class="mb-0">
                                    <table id="example6" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ลำดับ</th>
                                                <th class="text-center">AN</th>
                                                <th class="text-center">DIAG</th>
                                                <th class="text-center">DXTYPE</th>
                                                <th class="text-center">DRDX</th>  
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $d = 0; ?>
                                            @foreach ($data_idx as $itemidx)
                                            <?php $d++; ?> 
                                                <tr height="20" style="font-size: 14px;">
                                                    <td class="text-font" style="text-align: center;" width="5%">{{ $d }}</td>
                                                    <td class="text-center" width="10%">{{ $itemidx->AN }}</td>
                                                    <td class="text-center" width="10%">{{ $itemidx->DIAG }}</td>
                                                    <td class="text-center" width="10%">{{ $itemidx->DXTYPE }}</td>
                                                    <td class="text-center" width="10%">{{ $itemidx->DRDX }}</td>  
                                                </tr>  
                                            @endforeach 
                                        </tbody>
                                    </table>
                                </p>
                            </div>
                            <div class="tab-pane" id="IPD" role="tabpanel">
                                <p class="mb-0">
                                    <table id="example7" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ลำดับ</th>
                                                <th class="text-center">AN</th>
                                                <th class="text-center">HN</th>
                                                <th class="text-center">DATEADM</th>
                                                <th class="text-center">TIMEADM</th>  
                                                <th class="text-center">DATEDSC</th>
                                                <th class="text-center">TIMEDSC</th>
                                                <th class="text-center">DISCHS</th>
                                                <th class="text-center">DISCHT</th>
                                                <th class="text-center">DEPT</th>
                                                <th class="text-center">ADM_W</th>
                                                <th class="text-center">UUC</th>
                                                <th class="text-center">SVCTYPE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $d = 0; ?>
                                            @foreach ($data_ipd as $itemipd)
                                            <?php $d++; ?> 
                                                <tr height="20" style="font-size: 14px;">
                                                    <td class="text-font" style="text-align: center;" width="5%">{{ $d }}</td>
                                                    <td class="text-center" width="10%">{{ $itemipd->AN }}</td>
                                                    <td class="text-center" width="10%">{{ $itemipd->HN }}</td>
                                                    <td class="text-center" width="5%">{{ $itemipd->DATEADM }}</td>
                                                    <td class="text-center" width="5%">{{ $itemipd->TIMEADM }}</td> 
                                                    <td class="text-center" width="5%">{{ $itemipd->DATEDSC }}</td>  
                                                    <td class="text-center" width="5%">{{ $itemipd->TIMEDSC }}</td> 
                                                    <td class="text-center" width="10%">{{ $itemipd->DISCHS }}</td> 
                                                    <td class="text-center" width="5%">{{ $itemipd->DISCHT }}</td> 
                                                    <td class="text-center" width="5%">{{ $itemipd->DEPT }}</td> 
                                                    <td class="text-center" width="5%">{{ $itemipd->ADM_W }}</td>
                                                    <td class="text-center" width="5%">{{ $itemipd->UUC }}</td>
                                                    <td class="text-center" width="5%">{{ $itemipd->SVCTYPE }}</td>
                                                </tr>  
                                            @endforeach 
                                        </tbody>
                                    </table>
                                </p>
                            </div>
                            <div class="tab-pane" id="IRF" role="tabpanel">
                                <p class="mb-0">
                                    <table id="example8" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ลำดับ</th>
                                                <th class="text-center">AN</th>
                                                <th class="text-center">REFER</th>
                                                <th class="text-center">REFERTYPE</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $d = 0; ?>
                                            @foreach ($data_irf as $itemirf)
                                            <?php $d++; ?> 
                                                <tr height="20" style="font-size: 14px;">
                                                    <td class="text-font" style="text-align: center;" width="5%">{{ $d }}</td>
                                                    <td class="text-center" width="10%">{{ $itemirf->AN }}</td>
                                                    <td class="text-center" width="10%">{{ $itemirf->REFER }}</td>
                                                    <td class="text-center" width="5%">{{ $itemirf->REFERTYPE }}</td> 
                                                </tr>  
                                            @endforeach 
                                        </tbody>
                                    </table>
                                </p>
                            </div>
                            {{-- <div class="tab-pane" id="OPItem" role="tabpanel">
                                <p class="mb-0">
                                    <table id="example3" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ลำดับ</th>
                                                <th class="text-center">an</th>
                                                <th class="text-center">hn</th>
                                                <th class="text-center">vn</th> 
                                                <th class="text-center">dchdate</th> 
                                                <th class="text-center">icode</th> 
                                                <th class="text-center">name</th> 
                                                <th class="text-center">qty</th> 
                                                <th class="text-center">unitprice</th> 
                                                <th class="text-center">sum_price</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $number = 0; ?>
                                            @foreach ($data as $item)
                                            <?php $number++; ?>
                
                                            <tr height="20" style="font-size: 14px;">
                                                <td class="text-font" style="text-align: center;" width="4%">{{ $number }}</td>
                                                <td class="text-center" width="8%"> {{ $item->an }} </td>
                                                <td class="text-center" width="5%">{{ $item->hn }}</td>
                                                <td class="text-center" width="8%">{{ $item->vn }}</td> 
                                                <td class="text-center" width="8%">{{ $item->dchdate }}</td>  
                                                <td class="text-center" width="10%">{{ $item->icode }}</td> 
                                                <td class="p-2" >{{ $item->name }}</td> 
                                                <td class="text-center" width="5%">{{ $item->qty }}</td> 
                                                <td class="text-center" width="5%">{{ $item->unitprice }}</td> 
                                                <td class="text-center" width="5%">{{ $item->sum_price }}</td> 
                                            </tr>
                
                
                
                                            @endforeach
                
                                        </tbody>
                                    </table>
                                </p>
                            </div> --}}
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