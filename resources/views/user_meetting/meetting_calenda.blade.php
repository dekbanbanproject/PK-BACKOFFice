@extends('layouts.user')
@section('title', 'ZOffice || ช้อมูลการจองห้องประชุม')

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
    
    $m_budget = date('m');
    if ($m_budget > 9) {
        $yearbudget = date('Y') + 544;
    } else {
        $yearbudget = date('Y') + 543;
    }
    ?>
    <style>
        .btn {
            font-size: 15px;
        }

        tr.detailservice {
            cursor: pointer;
        }

        tr.detailservice:hover {
            background: #AED6F1 !important;
        }

        .meetroom:hover {
            background: #AED6F1;
        }

        .tr-header {
            background: #dcdcdc !important;
        }

        .fc-content {
            cursor: pointer;
        }

        #calendar {
            max-width: 95%;
            margin: 0 auto;
            font-size: 15px;
        }

        body {
            font-family: 'Kanit', sans-serif;
            font-size: 14px;
        }
    </style>
    <div class="container-fluid">
        {{-- <div class="px-0 py-0 mb-2">
            <div class="d-flex flex-wrap justify-content-center">
                <a class="col-4 col-lg-auto mb-2 mb-lg-0 me-lg-auto text-white me-2"></a>
                <div class="text-end"> 
                    <a href="{{ url('user_meetting/meetting_calenda') }}"
                        class="btn btn-info btn-sm text-white me-2">ปฎิทิน</a>
                    <a href="{{ url('user_meetting/meetting_index') }}"
                        class="btn btn-light btn-sm text-dark me-2">ช้อมูลการจองห้องประชุม</a>
                </div>
            </div>
        </div> --}}
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 mb-2">
                                <div class="card bg-info p-1 mx-0">
                                    <div class="card-header px-3 py-2 text-white">
                                        ห้องประชุม
                                    </div>
                                    <div class="card-body bg-white">
                                        @foreach ($building_level_room as $row)
                                            <a class="dropdown-item meetroom"
                                                href="{{ url('user_meetting/meetting_calenda_add/'.$row->room_id)}}">{{ $row->room_name }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9 mb-5">
                                <div class="card bg-info p-1 mx-0">
                                    <div class="panel-header text-left px-3 py-2 text-white">
                                        ปฎิทินข้อมูลการใช้บริการห้องประชุม<span
                                            class="fw-3 fs-18 text-white bg-sl-r2 px-2 radius-5"> </span>
                                    </div>
                                    <div class="panel-body bg-white">

                                        <div id='calendar'></div>

                                    </div>
                                    <div class="panel-footer text-end pr-5 py-2 bg-white ">
                                        <p class="m-0 fa fa-circle me-2" style="color:#A3DCA6;"></p> อนุมัติ<label
                                            class="me-3"></label>
                                        <p class="m-0 fa fa-circle me-2" style="color:#814ef7;"></p> จัดสรร<label
                                            class="me-3"></label>
                                        <p class="m-0 fa fa-circle me-2" style="color:#fa861a;"></p>ร้องขอ <label
                                            class="me-5"></label>
                                    </div>
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
            $('select').select2();
            $('#meetting_year').select2({
                dropdownParent: $('#meettingModal')
            });
            $('#meeting_objective_id').select2({
                dropdownParent: $('#meettingModal')
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(function() {

                var meetting = @json($events);

                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today', //  prevYear nextYea
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay',
                    },
                    events: meetting,
                    selectable: true,
                    selectHelper: true,
                    select: function(start, end, allDays) {
                        console.log(start)
                        $('#meettingModal').modal('toggle');

                        $('#saveBtn').click(function() {
                            // let request_hn_name = $(this).data('request_hn_name'); 
                            // $('#meeting_date_begin').val(start);

                            var meettingtitle = $('#meetting_title').val();
                            var status = $('#status').val();
                            var meettingyear = $('#meetting_year').val();
                            var meettingtarget = $('#meetting_target').val();
                            var meettingpersonqty = $('#meetting_person_qty').val();
                            // var meetingdateend = $('#meeting_date_end').val();
                            var meetingobj = $('#meeting_objective_id').val();
                            var meetingtel = $('#meeting_tel').val();
                            var userid = $('#userid').val();
                            //     console.log(meettingtitle)
                            // alert(status);
                            // meetingdateend,
                            var start_date = moment(start).format('YYYY-MM-DD');
                            var end_date = moment(end).format('YYYY-MM-DD');
                           
                        });
                    },
                    
                    selectAllow: function(event) {
                        return moment(event.start).utcOffset(false).isSame(moment(event.end)
                            .subtract(1, 'second').utcOffset(false), 'day');
                    },
                });

                $("#meettingModal").on("hidden.bs.modal", function () {
                $('#saveBtn').unbind();
            });

            });


        });
    </script>

@endsection
