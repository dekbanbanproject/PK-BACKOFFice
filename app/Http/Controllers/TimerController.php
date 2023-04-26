<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Validator;
use App\Models\User;
use App\Models\m_registerdata;
use PDF;
use setasign\Fpdi\Fpdi;
use App\Models\Budget_year;
use Illuminate\Support\Facades\File;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;

class TimerController extends Controller
{
    public function time_index(Request $request)
    { 
        $startdate = $request->startdate;
        $enddate = $request->enddate; 
        $datashow_ = DB::connection('mysql6')->select(' 
            SELECT
                c.CHECKIN_PERSON_ID
                ,c.CHEACKIN_DATE
                ,CONCAT(f.HR_PREFIX_NAME,p.HR_FNAME," ",p.HR_LNAME) as hrname
                ,c.CHEACKIN_TIME
                
                FROM checkin_index c
                LEFT JOIN hrd_person p on p.ID=c.CHECKIN_PERSON_ID
                LEFT JOIN hrd_department_sub_sub d on d.HR_DEPARTMENT_SUB_SUB_ID=p.HR_DEPARTMENT_SUB_SUB_ID
                LEFT OUTER JOIN hrd_department_sub hs on hs.HR_DEPARTMENT_SUB_ID=p.HR_DEPARTMENT_SUB_ID
                LEFT JOIN checkin_type t on t.CHECKIN_TYPE_ID=c.CHECKIN_TYPE_ID
                LEFT JOIN operate_job j on j.OPERATE_JOB_ID=c.OPERATE_JOB_ID
                LEFT JOIN operate_type ot on ot.OPERATE_TYPE_ID=j.OPERATE_JOB_TYPE_ID
                LEFT JOIN hrd_prefix f on f.HR_PREFIX_ID=p.HR_PREFIX_ID
                WHERE c.CHEACKIN_DATE BETWEEN "2023-04-01" AND "2023-04-31"
                AND d.HR_DEPARTMENT_SUB_ID = "22"
                AND d.HR_DEPARTMENT_SUB_SUB_ID = "29"
                GROUP BY c.CHEACKIN_DATE,c.CHECKIN_TYPE_ID
        ');
        
        return view('timer.time_index', [
            'datashow_'        => $datashow_,
            'startdate'        => $startdate,
            'enddate'          => $enddate ,
        ]);
    }
    public function time_dashboard(Request $request)
    { 
        $bookings = Booking::all();
        
        return view('timer.time_dashboard', [
            'bookings' => $bookings
        ]);
    }
    
}