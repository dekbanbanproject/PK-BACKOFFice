@extends('layouts.medicine')
@section('title', 'PK-BACKOFFice || แพทย์แผนไทย')

     <?php
     use App\Http\Controllers\StaticController;
     use Illuminate\Support\Facades\DB;   
     $count_meettingroom = StaticController::count_meettingroom();
 ?>


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
    <div class="container-fluid">
        <div class="row">
            <div class="col"></div>
            <div class="col-md-7">
                <div class="main-card mb-3 card">
                    <div class="card-header ">
                        หัตถการแพทย์แผนไทย 
                        <div class="btn-actions-pane-right">
                                <!-- Button trigger modal -->
                                <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Launch demo modal
                                </button> -->
                        </div>
                       
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>                                           
                                    <tr>
                                        <th width="5%" class="text-center">ลำดับ</th>
                                        <th class="text-center">วันที่</th>
                                        <th class="text-center">หัตถการ</th>                                                    
                                    </tr>                                            
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($datashow as $item)
                                        <tr>
                                            <td class="text-center">{{$i++}}</td> 
                                            <td class="text-center">{{DateThai($item->service_date)}}</td> 
                                            <td class="text-center">{{$item->icd10tm}} </td>  
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
    </div>
    </div>
  

@endsection
 