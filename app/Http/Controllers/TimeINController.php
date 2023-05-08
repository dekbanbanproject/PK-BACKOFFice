<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Validator;
use App\Models\User;
use App\Models\Checkin_index;
use App\Models\Operate_time;
use App\Models\Line_checktime;
use App\Models\m_registerdata;
use PDF;
use Auth;
use setasign\Fpdi\Fpdi;
use App\Models\Budget_year;
use Illuminate\Support\Facades\File;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;

class TimeINController extends Controller
{
    public function timein(Request $request)
    { 
        $startdate = $request->startdate;
        $enddate = $request->enddate; 
        $iddep =  Auth::user()->dep_subsubtrueid;
        $oper_ = DB::connection('mysql6')->table('operate_job')->where('OPERATE_DEPARTMENT_SUB_SUB_ID', '=', $iddep)->get();
        // dd($debsub);
        $datashow_ = DB::connection('mysql6')->select('   
                SELECT p.ID 
                    ,CONCAT(DAY(c.CHEACKIN_DATE), "-",MONTH(c.CHEACKIN_DATE), "-", YEAR(c.CHEACKIN_DATE)+543) AS CHEACKIN_DATE
                    ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "1" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKINTIME
                    ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "2" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKOUTTIME
                    ,CONCAT(f.HR_PREFIX_NAME,p.HR_FNAME," ",p.HR_LNAME) as hrname
                    ,h.HR_DEPARTMENT_ID,h.HR_DEPARTMENT_NAME
                    ,hs.HR_DEPARTMENT_SUB_ID,hs.HR_DEPARTMENT_SUB_NAME,d.HR_DEPARTMENT_SUB_SUB_ID,d.HR_DEPARTMENT_SUB_SUB_NAME
                    ,ot.OPERATE_TYPE_NAME,ot.OPERATE_TYPE_ID 
                FROM checkin_index c
                LEFT JOIN hrd_person p on p.ID=c.CHECKIN_PERSON_ID
                LEFT JOIN hrd_department h on h.HR_DEPARTMENT_ID=p.HR_DEPARTMENT_ID
                LEFT JOIN hrd_department_sub hs on hs.HR_DEPARTMENT_SUB_ID=p.HR_DEPARTMENT_SUB_ID
                LEFT JOIN hrd_department_sub_sub d on d.HR_DEPARTMENT_SUB_SUB_ID=p.HR_DEPARTMENT_SUB_SUB_ID
                LEFT JOIN operate_job j on j.OPERATE_JOB_ID=c.OPERATE_JOB_ID
                LEFT JOIN operate_type ot on ot.OPERATE_TYPE_ID=j.OPERATE_JOB_TYPE_ID
                LEFT JOIN hrd_prefix f on f.HR_PREFIX_ID=p.HR_PREFIX_ID
                WHERE c.CHEACKIN_DATE BETWEEN "'.$startdate.'" and "'.$enddate.'"   
                GROUP BY p.ID,ot.OPERATE_TYPE_ID,c.CHEACKIN_DATE
                ORDER BY c.CHEACKIN_DATE,d.HR_DEPARTMENT_SUB_SUB_ID,p.ID,ot.OPERATE_TYPE_ID
        ');
        
        Operate_time::truncate();
        foreach ($datashow_ as $key => $value) {  
        
            $start = strtotime($value->CHEACKINTIME);
            $end = strtotime($value->CHEACKOUTTIME);

            if ($end == '') {
                $tot = ''; 
            }elseif ($start == '') { 
                $tot = ''; 
            }elseif ($end < $start) { 
                $tot = ''; 
            } else {
                $tot_ = ($end - $start) / 3600; 
                $tot = number_format($tot_,2);
            }
  
            $date1 = date_create($value->CHEACKINTIME);
            $date2 = date_create($value->CHEACKOUTTIME);
            
            $diff = date_diff($date1, $date2);
            $totalhr = $diff->format('%R%H ชม.');

            Operate_time::insert([
                'operate_time_date'        => $value->CHEACKIN_DATE,
                'operate_time_personid'    => $value->ID,
                'operate_time_person'      => $value->hrname,
                'operate_time_typeid'      => $value->OPERATE_TYPE_ID,
                'operate_time_typename'    => $value->OPERATE_TYPE_NAME,
                'operate_time_in'          => $value->CHEACKINTIME,
                'operate_time_out'         => $value->CHEACKOUTTIME,
                'operate_time_otin'        => '',
                'operate_time_otout'       => '',
                'totaltime_narmal'         => $tot,
                'totaltime_ot'             => '' 
            ]);  
       
        }
         
        return view('timer.timein', [
            'datashow_'        => $datashow_,
            'startdate'        => $startdate,
            'enddate'          => $enddate,
            'oper_'            => $oper_,
        ]);
    }
     
    public function timein_save(Request $request)
    { 
        $checkindatetime = $request->CHEACKIN_DATE;
        // $checkintime = $request->CHEACKIN_TIME;

        $dateonly = date('Y-m-d', strtotime($checkindatetime)); 
        $timeonly = date('h-m-s', strtotime($checkindatetime)); 
        // dd($timeonly);

        $typeid = $request->CHECKIN_TYPE_ID; 
        $jobid = $request->OPERATE_JOB_ID; 
        $iduser =  Auth::user()->id;
        $fulname =  Auth::user()->fname.' '.Auth::user()->lname;
        $poid =  Auth::user()->position_id;
        $ssid =  Auth::user()->dep_subsubtrueid;
        $cid =  Auth::user()->cid;
     
            Operate_time::insert([
                'operate_time_date'        => $dateonly,
                'operate_time_personid'    => $iduser,
                'operate_time_person'      => $fulname,
                'operate_time_typeid'      => $typeid,
                'operate_time_typename'    => '',
                'operate_time_in'          => $timeonly,
                'operate_time_out'         => '',
                'operate_time_otin'        => '',
                'operate_time_otout'       => '',
                'totaltime_narmal'         => '',
                'totaltime_ot'             => '' 
            ]); 

            Checkin_index::insert([
                'CHECKIN_PERSON_ID'        => $iduser,
                'HR_POSITION_ID'           => $poid,
                'HR_DEPARTMENT_SUB_SUB_ID' => $ssid,
                'CHEACKIN_DATE'            => $dateonly,
                'CHEACKIN_TIME'            => $timeonly,
                'CHECKIN_TYPE_ID'          => $typeid,
                'OPERATE_JOB_ID'           => $jobid,
                'CHECKIN_IP'               => 'LINE', 
            ]);  
            
            Line_checktime::insert([
                'enrollnumber'       => $iduser,
                'cid'                => $cid,
                'tdate'              => $dateonly,
                'checktime'          => $checkindatetime,
                'checktype'          => 'LINE',
                'latitude'           => '16.3747917',
                'longitude'          => '102.1287445',
                'location_id'        => '2' 
            ]);   
    
        // return redirect()->back();
        return response()->json([
            'status'     => '200'
        ]);
        
    }
    
   
    public function time_depsubsub(Request $request)
    { 
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $deb = $request->HR_DEPARTMENT_ID; 
        $debsub = $request->HR_DEPARTMENT_SUB_ID;
        $debsubsub = $request->HR_DEPARTMENT_SUB_SUB_ID;
        $org_ = DB::connection('mysql')->table('orginfo')->where('orginfo_id', '=', 1)->first();
 
        $datashow_ = DB::connection('mysql6')->select('  
                SELECT p.ID 
                    ,CONCAT(DAY(c.CHEACKIN_DATE), "-",MONTH(c.CHEACKIN_DATE), "-", YEAR(c.CHEACKIN_DATE)+543) AS CHEACKIN_DATE
                    ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "1" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKINTIME
                    ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "2" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKOUTTIME
                    ,CONCAT(f.HR_PREFIX_NAME,p.HR_FNAME," ",p.HR_LNAME) as hrname
                    ,h.HR_DEPARTMENT_ID,h.HR_DEPARTMENT_NAME
                    ,hs.HR_DEPARTMENT_SUB_ID,hs.HR_DEPARTMENT_SUB_NAME,d.HR_DEPARTMENT_SUB_SUB_ID,d.HR_DEPARTMENT_SUB_SUB_NAME
                    ,ot.OPERATE_TYPE_NAME,ot.OPERATE_TYPE_ID 
                FROM checkin_index c
                LEFT JOIN hrd_person p on p.ID=c.CHECKIN_PERSON_ID
                LEFT JOIN hrd_department h on h.HR_DEPARTMENT_ID=p.HR_DEPARTMENT_ID
                LEFT JOIN hrd_department_sub hs on hs.HR_DEPARTMENT_SUB_ID=p.HR_DEPARTMENT_SUB_ID
                LEFT JOIN hrd_department_sub_sub d on d.HR_DEPARTMENT_SUB_SUB_ID=p.HR_DEPARTMENT_SUB_SUB_ID
                LEFT JOIN operate_job j on j.OPERATE_JOB_ID=c.OPERATE_JOB_ID
                LEFT JOIN operate_type ot on ot.OPERATE_TYPE_ID=j.OPERATE_JOB_TYPE_ID
                LEFT JOIN hrd_prefix f on f.HR_PREFIX_ID=p.HR_PREFIX_ID
                WHERE c.CHEACKIN_DATE BETWEEN "'.$startdate.'" and "'.$enddate.'"            
                AND d.HR_DEPARTMENT_SUB_SUB_ID = "'.$debsubsub.'"             
                GROUP BY p.ID,ot.OPERATE_TYPE_ID,c.CHEACKIN_DATE
                ORDER BY c.CHEACKIN_DATE,d.HR_DEPARTMENT_SUB_SUB_ID,p.ID,ot.OPERATE_TYPE_ID
        '); 
        $department_subsub = DB::connection('mysql6')->select('
            SELECT * FROM hrd_department_sub_sub
        '); 
        
        return view('timer.time_depsubsub', [
            'datashow_'        => $datashow_,
            'startdate'        => $startdate,
            'enddate'          => $enddate,          
            'department_subsub'   => $department_subsub,        
            'deb'              => $deb,
            'debsub'           => $debsub,
            'debsubsub'        => $debsubsub
        ]);
    }
 
    
}