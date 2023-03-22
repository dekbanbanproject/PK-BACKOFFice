@extends('layouts.envnew')
@section('title', 'PK-BACKOFFice || ENV')

     

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
        #button{
               display:block;
               margin:20px auto;
               padding:30px 30px;
               background-color:#eee;
               border:solid #ccc 1px;
               cursor: pointer;
               }
               #overlay{	
               position: fixed;
               top: 0;
               z-index: 100;
               width: 100%;
               height:100%;
               display: none;
               background: rgba(0,0,0,0.6);
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
               border-top: 10px #fd6812 solid;
               border-radius: 50%;
               animation: sp-anime 0.8s infinite linear;
               }
               @keyframes sp-anime {
               100% { 
                   transform: rotate(360deg); 
               }
               }
               .is-hide{
               display:none;
               }
    </style>
     <?php
     use App\Http\Controllers\StaticController;
     use Illuminate\Support\Facades\DB;   
     $count_meettingroom = StaticController::count_meettingroom();
 ?>
    <div class="container-fluid">
     
        <div id="preloader">
            <div id="status">
                <div class="spinner">
                    
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body shadow-lg">
                      
                        
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
            $('#example').DataTable();
            $('#example2').DataTable();
            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
            $('#datepicker2').datepicker({
                format: 'yyyy-mm-dd'
            });
            // $('#stamp').on('click', function(e) {
            // if($(this).is(':checked',true))  
            // {
            //     $(".sub_chk").prop('checked', true);  
            // } else {  
            //     $(".sub_chk").prop('checked',false);  
            // }  
            // }); 
            // $.ajaxSetup({
            //     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            // });
            
           
            
             

            $("#spinner-div").hide(); //Request is complete so hide spinner

            $('#Save_opd222').click(function() {
                var datepicker = $('#datepicker').val(); 
                var datepicker2 = $('#datepicker2').val(); 
                //    alert(datepicker);
                $.ajax({
                    url: "{{ route('acc.account_pksave') }}",
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
                                    // window.location="{{url('warehouse/warehouse_index')}}";
                                }
                            })
                        } else {
                            
                        }

                    },
                });
            });
           
        });
    </script>
    @endsection
