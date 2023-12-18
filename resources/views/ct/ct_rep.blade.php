@extends('layouts.ctnew')
@section('title', 'PK-BACKOFFice || CT')
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

        .modal-dialog {
            max-width: 60%;
        }

        .modal-dialog-slideout {
            min-height: 100%;
            margin:auto 0 0 0 ;   /*  ซ้าย ขวา */
            background: #fff;
        }

        .modal.fade .modal-dialog.modal-dialog-slideout {
            -webkit-transform: translate(100%, 0)scale(30);
            transform: translate(100%, 0)scale(5);
        }

        .modal.fade.show .modal-dialog.modal-dialog-slideout {
            -webkit-transform: translate(0, 0);
            transform: translate(0, 0);
            display: flex;
            align-items: stretch;
            -webkit-box-align: stretch;
            height: 100%;
        }

        .modal.fade.show .modal-dialog.modal-dialog-slideout .modal-body {
            overflow-y: auto;
            overflow-x: hidden;

            /* overflow-y: hidden;
            overflow-x: auto; */
        }

        .modal-dialog-slideout .modal-content {
            border: 0;
        }

        .modal-dialog-slideout .modal-header,
        .modal-dialog-slideout .modal-footer {
            height: 4rem;
            display: block;
        }

        .datepicker {
            z-index: 2051 !important;
        }

    </style>
    <script>
        function TypeAdmin() {
            window.location.href = '{{ route('index') }}';
        }
    </script>
    <?php
    if (Auth::check()) {
        $type = Auth::user()->type;
        $iduser = Auth::user()->id;
        $iddep = Auth::user()->dep_subsubtrueid;
    } else {
        echo "<body onload=\"TypeAdmin()\"></body>";
        exit();
    }
    $url = Request::url();
    $pos = strrpos($url, '/') + 1;
    
    $datenow = date('Y-m-d');
    $y = date('Y') + 543;
    $newweek = date('Y-m-d', strtotime($datenow . ' -1 week')); //ย้อนหลัง 1 สัปดาห์
    $newDate = date('Y-m-d', strtotime($datenow . ' -1 months')); //ย้อนหลัง 1 เดือน
    use Illuminate\Support\Facades\DB;
    use App\Http\Controllers\PlanController;
    use App\Models\Plan_control_money;
    $refnumber = PlanController::refnumber();
    ?>
    
    
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
        <form action="{{ url('ct_rep') }}" method="GET">
            @csrf
        <div class="row"> 
            <div class="col-md-4"> 
                <h4 class="card-title" style="color:rgb(10, 151, 85)">Detail CT</h4>
                <p class="card-title-desc">รายละเอียด</p>
            </div>
            <div class="col"></div>
            <div class="col-md-1 text-end mt-2">วันที่</div>
            <div class="col-md-5 text-end">
                <div class="input-daterange input-group" id="datepicker1" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker1'>
                    <input type="text" class="form-control d-shadow" name="startdate" id="datepicker" placeholder="Start Date" data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                        data-date-language="th-th" value="{{ $startdate }}" required/>
                    <input type="text" class="form-control d-shadow" name="enddate" placeholder="End Date" id="datepicker2" data-date-container='#datepicker1' data-provide="datepicker" data-date-autoclose="true" autocomplete="off"
                        data-date-language="th-th" value="{{ $enddate }}"/>  
                  
                        <button type="submit" class="ladda-button me-2 btn-pill btn btn-primary d-shadow" data-style="expand-left">
                            <span class="ladda-label"> <i class="fa-solid fa-magnifying-glass text-white me-2"></i>ค้นหา</span>
                            <span class="ladda-spinner"></span>
                        </button> 
                </div> 
            </div>
        </div>      
        </form>
        <div class="row">
            <div class="col-xl-12">
                <div class="card cardshadow">
                    <div class="card-body">  
                        <p class="mb-0">
                            <div class="table-responsive">
                                <table id="example" class="table table-hover table-sm dt-responsive nowrap"
                                style=" border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr> 
                                            <th width="5%" class="text-center">ลำดับ</th>  
                                            <th class="text-center" width="5%">vn</th> 
                                            <th class="text-center">an</th>
                                            <th class="text-center" >hn</th>
                                            <th class="text-center" >cid</th>
                                            <th class="text-center">ptname</th>
                                            <th class="text-center">dchdate</th>  
                                            <th class="text-center">pttype</th> 
                                            <th class="text-center">spsch</th> 
                                            <th class="text-center">income</th>
                                            <th class="text-center">ลูกหนี้</th>  
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        {{-- @foreach ($acc_debtor as $item) 
                                            <tr id="tr_{{$item->acc_debtor_id}}">                                                  
                                                <td class="text-center" width="5%">{{ $i++ }}</td>   
                                                <td class="text-center" width="5%">{{ $item->vn }}</td> 
                                                <td class="text-center" width="5%">{{ $item->an }}</td> 
                                                <td class="text-center" width="5%">{{ $item->hn }}</td>  
                                                <td class="text-center" width="10%">{{ $item->cid }}</td>  
                                                <td class="p-2" >{{ $item->ptname }}</td> 
                                                <td class="text-center" width="10%">{{ $item->dchdate }}</td>   
                                                <td class="text-center" style="color:rgb(73, 147, 231)" width="5%">{{ $item->pttype }}</td>  
                                                <td class="text-center" style="color:rgb(216, 95, 14)" width="5%">{{ $item->subinscl }}</td>  
                                                <td class="text-center" width="10%">{{ number_format($item->income, 2) }}</td> 
                                                <td class="text-center" width="10%">{{ number_format($item->debit_total, 2) }}</td>  
                                            </tr>
                                        @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                        </p>
                    </div>
                </div>
            </div>
        </div>
 
    </div>
 
 

@endsection
@section('footer')
    <script>
       
        $(document).ready(function() {
            $('#example').DataTable();
            $('#example2').DataTable();
            $('#example3').DataTable();
           
            $('#startdate').datepicker({
                format: 'yyyy-mm-dd'
            });
            $('#enddate').datepicker({
                format: 'yyyy-mm-dd'
            });

            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
            $('#datepicker2').datepicker({
                format: 'yyyy-mm-dd'
            });
           
            $('[data-toggle="datepicker"]').datepicker({ 
                autoHide: true,
                zIndex: 2048,
            });
             
            $('select').select2();
            $('#plan_control_moneyuser_id').select2({
                dropdownParent: $('#MoneyModal')
            });

            $('#edit_plan_type').select2({
                dropdownParent: $('#UpdateModal')
            });
            
    
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

           

        });
    </script>

@endsection
