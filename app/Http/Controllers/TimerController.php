<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Validator;
use App\Models\User;
use App\Models\Operate_time;
use App\Models\Hrd_person;
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
        $deb = $request->HR_DEPARTMENT_ID; 
        $debsub = $request->HR_DEPARTMENT_SUB_ID;
        $debsubsub = $request->HR_DEPARTMENT_SUB_SUB_ID;

        // dd($debsub);
        $datashow_ = DB::connection('mysql6')->select(' 
            SELECT p.ID,c.CHEACKIN_DATE
            ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "1" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKINTIME
            ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "2" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKOUTTIME
            ,CONCAT(f.HR_PREFIX_NAME,p.HR_FNAME," ",p.HR_LNAME) as hrname,hp.HR_POSITION_NAME,ct.CHECKIN_TYPE_NAME
            ,hs.HR_DEPARTMENT_SUB_NAME,d.HR_DEPARTMENT_SUB_SUB_NAME,d.HR_DEPARTMENT_SUB_ID
            ,ot.OPERATE_TYPE_NAME,c.CHECKIN_TYPE_ID,hs.HR_DEPARTMENT_SUB_NAME,d.HR_DEPARTMENT_SUB_SUB_NAME,ot.OPERATE_TYPE_NAME
            FROM checkin_index c
            LEFT JOIN checkin_type ct on ct.CHECKIN_TYPE_ID=c.CHECKIN_TYPE_ID
            LEFT JOIN hrd_person p on p.ID=c.CHECKIN_PERSON_ID
            LEFT JOIN hrd_department_sub_sub d on d.HR_DEPARTMENT_SUB_SUB_ID=p.HR_DEPARTMENT_SUB_SUB_ID
            LEFT JOIN hrd_department_sub hs on hs.HR_DEPARTMENT_SUB_ID=p.HR_DEPARTMENT_SUB_ID
            LEFT JOIN operate_job j on j.OPERATE_JOB_ID=c.OPERATE_JOB_ID
            LEFT JOIN operate_type ot on ot.OPERATE_TYPE_ID=j.OPERATE_JOB_TYPE_ID
            LEFT JOIN hrd_prefix f on f.HR_PREFIX_ID=p.HR_PREFIX_ID
            LEFT JOIN hrd_position hp on hp.HR_POSITION_ID=p.HR_POSITION_ID
            WHERE c.CHEACKIN_DATE BETWEEN "'.$startdate.'" and "'.$enddate.'" 
            AND d.HR_DEPARTMENT_SUB_ID = "'.$debsub.'"
            AND d.HR_DEPARTMENT_SUB_SUB_ID = "'.$debsubsub.'"
            GROUP BY p.ID,j.OPERATE_JOB_ID,c.CHEACKIN_DATE
            ORDER BY c.CHEACKIN_DATE,c.CHECKIN_TYPE_ID             
        ');

        Operate_time::truncate();
        foreach ($datashow_ as $key => $value) {  
           
            // $add = new Operate_time();
            // $add->operate_time_date = $value->CHEACKIN_DATE; 
            // $add->operate_time_personid = $value->ID;
            // $add->operate_time_person = $value->hrname;   
            // $add->operate_time_typeid = $value->CHECKIN_TYPE_ID;
            // $add->operate_time_in = $value->CHEACKINTIME;
            // $add->operate_time_out = $value->CHEACKOUTTIME;
            // $add->save();
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
                'operate_time_typeid'      => $value->CHECKIN_TYPE_ID,
                'operate_time_typename'    => $value->CHECKIN_TYPE_NAME,
                'operate_time_in'          => $value->CHEACKINTIME,
                'operate_time_out'         => $value->CHEACKOUTTIME,
                'operate_time_otin'        => '',
                'operate_time_otout'       => '',
                'totaltime_narmal'         => $tot,
                'totaltime_ot'             => '' 
            ]);                
        }
        $department = DB::connection('mysql6')->select('
            SELECT * FROM hrd_department
        ');
        $department_sub = DB::connection('mysql6')->select('
            SELECT * FROM hrd_department_sub
        ');
        $department_subsub = DB::connection('mysql6')->select('
            SELECT * FROM hrd_department_sub_sub
        ');
        
        return view('timer.time_index', [
            'datashow_'        => $datashow_,
            'startdate'        => $startdate,
            'enddate'          => $enddate,
            'department'       => $department,
            'department_sub'   => $department_sub,
            'department_subsub'=> $department_subsub,
            'deb'              => $deb,
            'debsub'           => $debsub,
            'debsubsub'        => $debsubsub
        ]);
    }
    public function time_index_search(Request $request)
    { 
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $deb = $request->HR_DEPARTMENT_ID; 
        $debsub = $request->HR_DEPARTMENT_SUB_ID;
        $debsubsub = $request->HR_DEPARTMENT_SUB_SUB_ID;

        // dd($debsub);
        $datashow_ = DB::connection('mysql6')->select(' 
            SELECT p.ID,c.CHEACKIN_DATE
            ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "1" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKINTIME
            ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "2" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKOUTTIME
            ,CONCAT(f.HR_PREFIX_NAME,p.HR_FNAME," ",p.HR_LNAME) as hrname,hp.HR_POSITION_NAME,ct.CHECKIN_TYPE_NAME
            ,hs.HR_DEPARTMENT_SUB_NAME,d.HR_DEPARTMENT_SUB_SUB_NAME,d.HR_DEPARTMENT_SUB_ID
            ,ot.OPERATE_TYPE_NAME,c.CHECKIN_TYPE_ID,hs.HR_DEPARTMENT_SUB_NAME,d.HR_DEPARTMENT_SUB_SUB_NAME,ot.OPERATE_TYPE_NAME
            FROM checkin_index c
            LEFT JOIN checkin_type ct on ct.CHECKIN_TYPE_ID=c.CHECKIN_TYPE_ID
            LEFT JOIN hrd_person p on p.ID=c.CHECKIN_PERSON_ID
            LEFT JOIN hrd_department_sub_sub d on d.HR_DEPARTMENT_SUB_SUB_ID=p.HR_DEPARTMENT_SUB_SUB_ID
            LEFT JOIN hrd_department_sub hs on hs.HR_DEPARTMENT_SUB_ID=p.HR_DEPARTMENT_SUB_ID
            LEFT JOIN operate_job j on j.OPERATE_JOB_ID=c.OPERATE_JOB_ID
            LEFT JOIN operate_type ot on ot.OPERATE_TYPE_ID=j.OPERATE_JOB_TYPE_ID
            LEFT JOIN hrd_prefix f on f.HR_PREFIX_ID=p.HR_PREFIX_ID
            LEFT JOIN hrd_position hp on hp.HR_POSITION_ID=p.HR_POSITION_ID
            WHERE c.CHEACKIN_DATE BETWEEN "'.$startdate.'" and "'.$enddate.'" 
            AND d.HR_DEPARTMENT_SUB_ID = "'.$debsub.'"
            AND d.HR_DEPARTMENT_SUB_SUB_ID = "'.$debsubsub.'"
            GROUP BY p.ID,j.OPERATE_JOB_ID,c.CHEACKIN_DATE
            ORDER BY c.CHEACKIN_DATE,c.CHECKIN_TYPE_ID             
        ');

        Operate_time::truncate();
        foreach ($datashow_ as $key => $value) {  
           
            // $add = new Operate_time();
            // $add->operate_time_date = $value->CHEACKIN_DATE; 
            // $add->operate_time_personid = $value->ID;
            // $add->operate_time_person = $value->hrname;   
            // $add->operate_time_typeid = $value->CHECKIN_TYPE_ID;
            // $add->operate_time_in = $value->CHEACKINTIME;
            // $add->operate_time_out = $value->CHEACKOUTTIME;
            // $add->save();
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
                'operate_time_typeid'      => $value->CHECKIN_TYPE_ID,
                'operate_time_typename'    => $value->CHECKIN_TYPE_NAME,
                'operate_time_in'          => $value->CHEACKINTIME,
                'operate_time_out'         => $value->CHEACKOUTTIME,
                'operate_time_otin'        => '',
                'operate_time_otout'       => '',
                'totaltime_narmal'         => $tot,
                'totaltime_ot'             => '' 
            ]);                
        }
        $department = DB::connection('mysql6')->select('
            SELECT * FROM hrd_department
        ');
        $department_sub = DB::connection('mysql6')->select('
            SELECT * FROM hrd_department_sub
        ');
        $department_subsub = DB::connection('mysql6')->select('
            SELECT * FROM hrd_department_sub_sub
        ');
        
        return view('timer.time_index', [
            'datashow_'        => $datashow_,
            'startdate'        => $startdate,
            'enddate'          => $enddate,
            'department'       => $department,
            'department_sub'   => $department_sub,
            'department_subsub'=> $department_subsub,
            'deb'              => $deb,
            'debsub'           => $debsub,
            'debsubsub'        => $debsubsub
        ]);
    }
    public function time_index_excel (Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate; 
        $debsub = $request->HR_DEPARTMENT_SUB_ID;
        $debsubsub = $request->HR_DEPARTMENT_SUB_SUB_ID; 
     
             
            $datashow_ = DB::connection('mysql6')->select(' 
                SELECT p.ID as PID,c.CHEACKIN_DATE
                    ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "1" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKINTIME
                    ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "2" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKOUTTIME
                    ,CONCAT(f.HR_PREFIX_NAME,p.HR_FNAME," ",p.HR_LNAME) as hrname,hp.HR_POSITION_NAME
                    ,hs.HR_DEPARTMENT_SUB_NAME,d.HR_DEPARTMENT_SUB_SUB_NAME,d.HR_DEPARTMENT_SUB_ID
                    ,ot.OPERATE_TYPE_NAME,c.CHECKIN_TYPE_ID
                    FROM checkin_index c
                    LEFT JOIN hrd_person p on p.ID=c.CHECKIN_PERSON_ID
                    LEFT JOIN hrd_department_sub_sub d on d.HR_DEPARTMENT_SUB_SUB_ID=p.HR_DEPARTMENT_SUB_SUB_ID
                    LEFT JOIN hrd_department_sub hs on hs.HR_DEPARTMENT_SUB_ID=p.HR_DEPARTMENT_SUB_ID
                    LEFT JOIN operate_job j on j.OPERATE_JOB_ID=c.OPERATE_JOB_ID
                    LEFT JOIN operate_type ot on ot.OPERATE_TYPE_ID=j.OPERATE_JOB_TYPE_ID
                    LEFT JOIN hrd_prefix f on f.HR_PREFIX_ID=p.HR_PREFIX_ID
                    LEFT JOIN hrd_position hp on hp.HR_POSITION_ID=p.HR_POSITION_ID
                    WHERE c.CHEACKIN_DATE BETWEEN "'.$startdate.'" and "'.$enddate.'" 
                    AND d.HR_DEPARTMENT_SUB_ID = "'.$debsub.'"
                    AND d.HR_DEPARTMENT_SUB_SUB_ID = "'.$debsubsub.'"
                    GROUP BY p.ID,j.OPERATE_JOB_ID,c.CHEACKIN_DATE
                    ORDER BY c.CHEACKIN_DATE,c.CHECKIN_TYPE_ID             
            ');
          
            $department = DB::connection('mysql6')->select('
                SELECT * FROM hrd_department
            ');
            $department_sub = DB::connection('mysql6')->select('
                SELECT * FROM hrd_department_sub
            ');
            $department_subsub = DB::connection('mysql6')->select('
                SELECT * FROM hrd_department_sub_sub
            ');
            $export = DB::connection('mysql')->select('
                SELECT * FROM operate_time
            ');
        return view('timer.time_index_excel',[
            'datashow_'        => $datashow_,
            'startdate'        => $startdate,
            'enddate'          => $enddate,
            'department_sub'   => $department_sub,
            'debsub'           => $debsub,
            'debsubsub'        => $debsubsub,
            'export'           => $export
        ]);
    }
    public function time_dashboard(Request $request)
    { 
        $department_ = DB::connection('mysql6')->select('
            SELECT * FROM hrd_department
        ');      
        $dep_count_all_ = DB::connection('mysql6')->select(' 
            SELECT COUNT(DISTINCT p.ID) as COuntid
                FROM checkin_index c
                LEFT JOIN checkin_type ct on ct.CHECKIN_TYPE_ID=c.CHECKIN_TYPE_ID
                LEFT JOIN hrd_person p on p.ID=c.CHECKIN_PERSON_ID
                LEFT JOIN hrd_department h on h.HR_DEPARTMENT_ID = p.HR_DEPARTMENT_ID
                LEFT JOIN hrd_department_sub hs on hs.HR_DEPARTMENT_SUB_ID=p.HR_DEPARTMENT_SUB_ID
                LEFT JOIN hrd_department_sub_sub d on d.HR_DEPARTMENT_SUB_SUB_ID=p.HR_DEPARTMENT_SUB_SUB_ID
                
                LEFT JOIN operate_job j on j.OPERATE_JOB_ID=c.OPERATE_JOB_ID
                LEFT JOIN operate_type ot on ot.OPERATE_TYPE_ID=j.OPERATE_JOB_TYPE_ID
                LEFT JOIN hrd_prefix f on f.HR_PREFIX_ID=p.HR_PREFIX_ID
                LEFT JOIN hrd_position hp on hp.HR_POSITION_ID=p.HR_POSITION_ID
                WHERE c.CHEACKIN_DATE = CURDATE()
        ');
        foreach ($dep_count_all_ as $key => $value) {
        $dep_count_all = $value->COuntid;
        }

        $datashow_ = DB::connection('mysql6')->select(' 
            SELECT p.ID,c.CHEACKIN_DATE
                ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "1" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKINTIME
                ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "2" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKOUTTIME
                ,CONCAT(f.HR_PREFIX_NAME,p.HR_FNAME," ",p.HR_LNAME) as hrname,hp.HR_POSITION_NAME 
                ,ot.OPERATE_TYPE_NAME 
                FROM checkin_index c
                LEFT JOIN checkin_type ct on ct.CHECKIN_TYPE_ID=c.CHECKIN_TYPE_ID
                LEFT JOIN hrd_person p on p.ID=c.CHECKIN_PERSON_ID
                LEFT JOIN hrd_department h on h.HR_DEPARTMENT_ID = p.HR_DEPARTMENT_ID
                LEFT JOIN hrd_department_sub hs on hs.HR_DEPARTMENT_SUB_ID=p.HR_DEPARTMENT_SUB_ID
                LEFT JOIN hrd_department_sub_sub d on d.HR_DEPARTMENT_SUB_SUB_ID=p.HR_DEPARTMENT_SUB_SUB_ID
                LEFT JOIN operate_job j on j.OPERATE_JOB_ID=c.OPERATE_JOB_ID
                LEFT JOIN operate_type ot on ot.OPERATE_TYPE_ID=j.OPERATE_JOB_TYPE_ID
                LEFT JOIN hrd_prefix f on f.HR_PREFIX_ID=p.HR_PREFIX_ID
                LEFT JOIN hrd_position hp on hp.HR_POSITION_ID=p.HR_POSITION_ID
                WHERE c.CHEACKIN_DATE = CURDATE()        
                GROUP BY c.CHECKIN_PERSON_ID
                ORDER BY CHEACKINTIME DESC           
        ');

        $per_ = DB::connection('mysql6')->select('
            SELECT COUNT(DISTINCT ID) as cc
            FROM hrd_person WHERE HR_STATUS_ID ="1"            
        ');
        foreach ($per_ as $key => $value3) {
            $per = $value3->cc;
        }
        // LEFT JOIN users u on u.PERSON_ID = p.ID
        // dd( $dep_count_all);
        return view('timer.time_dashboard', [
            'department'    =>  $department_,
            'dep_count_all' =>  $dep_count_all,
            'datashow_'     =>  $datashow_,
            'per'     =>  $per
        ]);
    }
    public function time_dashboard_detail(Request $request,$id)
    {  
            $dep_show_all_ = DB::connection('mysql6')->select(' 
                SELECT h.HR_DEPARTMENT_ID,hs.HR_DEPARTMENT_SUB_ID,hs.HR_DEPARTMENT_SUB_NAME
                    FROM checkin_index c
                    LEFT JOIN checkin_type ct on ct.CHECKIN_TYPE_ID=c.CHECKIN_TYPE_ID
                    LEFT JOIN hrd_person p on p.ID=c.CHECKIN_PERSON_ID
                    LEFT JOIN hrd_department h on h.HR_DEPARTMENT_ID = p.HR_DEPARTMENT_ID
                    LEFT JOIN hrd_department_sub_sub d on d.HR_DEPARTMENT_SUB_SUB_ID=p.HR_DEPARTMENT_SUB_SUB_ID
                    LEFT JOIN hrd_department_sub hs on hs.HR_DEPARTMENT_SUB_ID = p.HR_DEPARTMENT_SUB_ID
                    LEFT JOIN operate_job j on j.OPERATE_JOB_ID=c.OPERATE_JOB_ID
                    LEFT JOIN operate_type ot on ot.OPERATE_TYPE_ID=j.OPERATE_JOB_TYPE_ID
                    LEFT JOIN hrd_prefix f on f.HR_PREFIX_ID=p.HR_PREFIX_ID
                    LEFT JOIN hrd_position hp on hp.HR_POSITION_ID=p.HR_POSITION_ID
                    WHERE c.CHEACKIN_DATE = CURDATE()
                    AND h.HR_DEPARTMENT_ID ="'.$id.'" 
                    GROUP BY hs.HR_DEPARTMENT_SUB_ID
            ');
            
            $datashow_ = DB::connection('mysql6')->select(' 
                SELECT p.ID,c.CHEACKIN_DATE
                    ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "1" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKINTIME
                    ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "2" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKOUTTIME
                    ,CONCAT(f.HR_PREFIX_NAME,p.HR_FNAME," ",p.HR_LNAME) as hrname,hp.HR_POSITION_NAME 
                    ,ot.OPERATE_TYPE_NAME 
                    FROM checkin_index c
                    LEFT JOIN checkin_type ct on ct.CHECKIN_TYPE_ID=c.CHECKIN_TYPE_ID
                    LEFT JOIN hrd_person p on p.ID=c.CHECKIN_PERSON_ID
                    LEFT JOIN hrd_department h on h.HR_DEPARTMENT_ID = p.HR_DEPARTMENT_ID
                    LEFT JOIN hrd_department_sub hs on hs.HR_DEPARTMENT_SUB_ID=p.HR_DEPARTMENT_SUB_ID
                    LEFT JOIN hrd_department_sub_sub d on d.HR_DEPARTMENT_SUB_SUB_ID=p.HR_DEPARTMENT_SUB_SUB_ID
                    LEFT JOIN operate_job j on j.OPERATE_JOB_ID=c.OPERATE_JOB_ID
                    LEFT JOIN operate_type ot on ot.OPERATE_TYPE_ID=j.OPERATE_JOB_TYPE_ID
                    LEFT JOIN hrd_prefix f on f.HR_PREFIX_ID=p.HR_PREFIX_ID
                    LEFT JOIN hrd_position hp on hp.HR_POSITION_ID=p.HR_POSITION_ID
                    WHERE c.CHEACKIN_DATE = CURDATE()  
                    AND h.HR_DEPARTMENT_ID ="'.$id.'"       
                    GROUP BY c.CHECKIN_PERSON_ID
                    ORDER BY CHEACKINTIME DESC           
            ');
        return view('timer.time_dashboard_detail', [ 
            'dep_show_all' =>  $dep_show_all_,
            'datashow_'     =>  $datashow_
        ]);
    }
    public function time_dashboard_detail_sub(Request $request,$id)
    {  
            $depsub_show_all_ = DB::connection('mysql6')->select(' 
                SELECT h.HR_DEPARTMENT_ID,hs.HR_DEPARTMENT_SUB_ID,hs.HR_DEPARTMENT_SUB_NAME,d.HR_DEPARTMENT_SUB_SUB_NAME,d.HR_DEPARTMENT_SUB_SUB_ID
                    FROM checkin_index c
                    LEFT JOIN checkin_type ct on ct.CHECKIN_TYPE_ID=c.CHECKIN_TYPE_ID
                    LEFT JOIN hrd_person p on p.ID=c.CHECKIN_PERSON_ID
                    LEFT JOIN hrd_department h on h.HR_DEPARTMENT_ID = p.HR_DEPARTMENT_ID
                    LEFT JOIN hrd_department_sub_sub d on d.HR_DEPARTMENT_SUB_SUB_ID=p.HR_DEPARTMENT_SUB_SUB_ID
                    LEFT JOIN hrd_department_sub hs on hs.HR_DEPARTMENT_SUB_ID = p.HR_DEPARTMENT_SUB_ID
                    LEFT JOIN operate_job j on j.OPERATE_JOB_ID=c.OPERATE_JOB_ID
                    LEFT JOIN operate_type ot on ot.OPERATE_TYPE_ID=j.OPERATE_JOB_TYPE_ID
                    LEFT JOIN hrd_prefix f on f.HR_PREFIX_ID=p.HR_PREFIX_ID
                    LEFT JOIN hrd_position hp on hp.HR_POSITION_ID=p.HR_POSITION_ID
                    WHERE c.CHEACKIN_DATE = CURDATE()
                    AND  hs.HR_DEPARTMENT_SUB_ID ="'.$id.'" 
                    GROUP BY d.HR_DEPARTMENT_SUB_SUB_ID
            ');

            $datashow_ = DB::connection('mysql6')->select(' 
                SELECT p.ID,c.CHEACKIN_DATE
                    ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "1" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKINTIME
                    ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "2" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKOUTTIME
                    ,CONCAT(f.HR_PREFIX_NAME,p.HR_FNAME," ",p.HR_LNAME) as hrname,hp.HR_POSITION_NAME 
                    ,ot.OPERATE_TYPE_NAME 
                    FROM checkin_index c
                    LEFT JOIN checkin_type ct on ct.CHECKIN_TYPE_ID=c.CHECKIN_TYPE_ID
                    LEFT JOIN hrd_person p on p.ID=c.CHECKIN_PERSON_ID
                    LEFT JOIN hrd_department h on h.HR_DEPARTMENT_ID = p.HR_DEPARTMENT_ID
                    LEFT JOIN hrd_department_sub hs on hs.HR_DEPARTMENT_SUB_ID=p.HR_DEPARTMENT_SUB_ID
                    LEFT JOIN hrd_department_sub_sub d on d.HR_DEPARTMENT_SUB_SUB_ID=p.HR_DEPARTMENT_SUB_SUB_ID
                    LEFT JOIN operate_job j on j.OPERATE_JOB_ID=c.OPERATE_JOB_ID
                    LEFT JOIN operate_type ot on ot.OPERATE_TYPE_ID=j.OPERATE_JOB_TYPE_ID
                    LEFT JOIN hrd_prefix f on f.HR_PREFIX_ID=p.HR_PREFIX_ID
                    LEFT JOIN hrd_position hp on hp.HR_POSITION_ID=p.HR_POSITION_ID
                    WHERE c.CHEACKIN_DATE = CURDATE()  
                    AND hs.HR_DEPARTMENT_SUB_ID ="'.$id.'"       
                    GROUP BY c.CHECKIN_PERSON_ID
                    ORDER BY CHEACKINTIME DESC           
            ');
             
        return view('timer.time_dashboard_detail_sub', [ 
            'depsub_show_all' =>  $depsub_show_all_,
            'datashow_'     =>  $datashow_
        ]);
    }
    public function time_dashboard_detail_sub_person(Request $request,$id)
    {  
            $depsub_person_show_all_ = DB::connection('mysql6')->select(' 
                SELECT p.ID,c.CHEACKIN_DATE
                ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "1" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKINTIME
                ,SUBSTRING_INDEX(GROUP_CONCAT((SELECT CONCAT(c.CHEACKIN_TIME) WHERE c.CHECKIN_TYPE_ID = "2" AND c.CHECKIN_PERSON_ID = p.ID)),",",1) AS CHEACKOUTTIME
                ,CONCAT(f.HR_PREFIX_NAME,p.HR_FNAME," ",p.HR_LNAME) as hrname,hp.HR_POSITION_NAME,ct.CHECKIN_TYPE_NAME
                ,hs.HR_DEPARTMENT_SUB_NAME,d.HR_DEPARTMENT_SUB_SUB_NAME,d.HR_DEPARTMENT_SUB_ID
                ,ot.OPERATE_TYPE_NAME 
               
                    FROM checkin_index c
                    LEFT JOIN checkin_type ct on ct.CHECKIN_TYPE_ID=c.CHECKIN_TYPE_ID
                    LEFT JOIN hrd_person p on p.ID=c.CHECKIN_PERSON_ID
                    LEFT JOIN hrd_department h on h.HR_DEPARTMENT_ID = p.HR_DEPARTMENT_ID
                    LEFT JOIN hrd_department_sub_sub d on d.HR_DEPARTMENT_SUB_SUB_ID=p.HR_DEPARTMENT_SUB_SUB_ID
                    LEFT JOIN hrd_department_sub hs on hs.HR_DEPARTMENT_SUB_ID = p.HR_DEPARTMENT_SUB_ID
                    LEFT JOIN operate_job j on j.OPERATE_JOB_ID=c.OPERATE_JOB_ID
                    LEFT JOIN operate_type ot on ot.OPERATE_TYPE_ID=j.OPERATE_JOB_TYPE_ID
                    LEFT JOIN hrd_prefix f on f.HR_PREFIX_ID=p.HR_PREFIX_ID
                    LEFT JOIN hrd_position hp on hp.HR_POSITION_ID=p.HR_POSITION_ID


                    WHERE c.CHEACKIN_DATE = CURDATE()
                    AND  d.HR_DEPARTMENT_SUB_SUB_ID ="'.$id.'" 
                    GROUP BY p.ID,j.OPERATE_JOB_ID,c.CHEACKIN_DATE
                    ORDER BY c.CHEACKIN_DATE,c.CHECKIN_TYPE_ID 
            ');
            // h.HR_DEPARTMENT_ID,hs.HR_DEPARTMENT_SUB_ID,hs.HR_DEPARTMENT_SUB_NAME,d.HR_DEPARTMENT_SUB_SUB_NAME,d.HR_DEPARTMENT_SUB_SUB_ID
             
        return view('timer.time_dashboard_detail_sub_person', [ 
            'depsub_person_show_all' =>  $depsub_person_show_all_
        ]);
    }
    
}