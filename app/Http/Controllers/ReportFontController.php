<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Validator;
use App\Models\User;
use PDF;
use setasign\Fpdi\Fpdi;
use App\Models\Budget_year;
use Illuminate\Support\Facades\File;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;

class ReportFontController extends Controller
{
    public function report_dashboard(Request $request)
    {
        $year_id = $request->year_id;
        $date = date('Y-m-d');
        $y = date('Y') + 543;
        $newweek = date('Y-m-d', strtotime($date . ' -1 week')); //ย้อนหลัง 1 สัปดาห์  
        // $newDate = date('Y-m-d', strtotime($date . ' -1 months')); //ย้อนหลัง 1 เดือน 
        $newDate = date('Y-m-d', strtotime($date . ' 1 months')); // 1 เดือน 
        // $newDate = date('Y-m-d') ; //
        // dd($date);
        $startdate = $request->startdate;
        $enddate = $request->enddate;  
 
        $datashow_ = DB::connection('mysql3')->select('   
                SELECT
                    month(a.dchdate) as months
                    ,o.vn,o.hn,o.an,pt.cid,ptname(o.hn,1) ptname
                    ,ce2be(o.vstdate) vstdate ,a.dchdate ,ptt.pttype inscl,a.pdx
                    ,w.name as ward ,oo.icode ,oit.name as ERCP ,a.uc_money 
                    ,a.income-a.discount_money-a.rcpt_money debit 
                    ,a.rcpno_list rcpno,s.AMOUNTPAY as "ชดเชย"

                    from ovst o
                    LEFT JOIN an_stat a on a.an=o.an
                    LEFT JOIN patient pt on pt.hn=o.hn
                    LEFT JOIN pttype ptt on ptt.pttype=o.pttype
                    LEFT JOIN operation_list ol ON a.an = ol.an
                    LEFT JOIN operation_detail od ON od.operation_id=ol.operation_id
                    LEFT JOIN opitemrece oo on oo.an=o.an
                    LEFT JOIN operation_item oit on oit.icode=oo.icode
                    LEFT JOIN drugitems d on d.icode=oo.icode 
                    LEFT JOIN ward w on w.ward=a.ward
                    LEFT JOIN eclaimdb.m_registerdata m on m.opdseq = o.an 
                    left outer join hshooterdb.m_rep_ucs s1 on s1.an=o.an and s1.error_code ="P" and s1.nhso_pay >"0"
                    LEFT JOIN hshooterdb.m_color_ref mc ON mc.an=o.an
                    LEFT JOIN hshooterdb.claim_status c on c.status_id=m.`STATUS` or  c.status_id=mc.`STATUS`
                    left join hshooterdb.m_stm s on s.an = o.an
       
                    where a.dchdate between "' . $newweek . '" and "' . $date . '"         
                    AND oo.icode ="3010777"
        ');
        $datashow_count = DB::connection('mysql3')->select('   
                SELECT
                    count(o.vn) as vn
                    from ovst o
                    LEFT JOIN an_stat a on a.an=o.an
                    LEFT JOIN patient pt on pt.hn=o.hn
                    LEFT JOIN pttype ptt on ptt.pttype=o.pttype
                    LEFT JOIN operation_list ol ON a.an = ol.an
                    LEFT JOIN operation_detail od ON od.operation_id=ol.operation_id
                    LEFT JOIN opitemrece oo on oo.an=o.an
                    LEFT JOIN operation_item oit on oit.icode=oo.icode
                    LEFT JOIN drugitems d on d.icode=oo.icode 
                    LEFT JOIN ward w on w.ward=a.ward
                    LEFT JOIN eclaimdb.m_registerdata m on m.opdseq = o.an 
                    left outer join hshooterdb.m_rep_ucs s1 on s1.an=o.an and s1.error_code ="P" and s1.nhso_pay >"0"
                    LEFT JOIN hshooterdb.m_color_ref mc ON mc.an=o.an
                    LEFT JOIN hshooterdb.claim_status c on c.status_id=m.`STATUS` or  c.status_id=mc.`STATUS`
                    left join hshooterdb.m_stm s on s.an = o.an
       
                    where a.dchdate between "' . $newweek . '" and "' . $date . '"         
                    AND oo.icode ="3010777"
        ');
        foreach ($datashow_count as $key => $value) {
            $count = $value->vn;
        }
        $year = DB::table('budget_year')->orderBy('leave_year_id', 'DESC')->get();
        $leave_month_year = DB::table('leave_month_year')->get();
        // $count = DB::connection('mysql3')->DB::table('budget_year')->count();

        return view('dashboard.report_dashboard', [ 
            'datashow_'      =>  $datashow_,
            'year'           =>  $year, 
            'year_ids'       =>  $year_id,
            'leave_month_year' =>  $leave_month_year,
            'count'          =>  $count
        ]);
    }
    public function report_or(Request $request)
    {
        $year_id = $request->year_id;
        $date = date('Y-m-d');
        $y = date('Y') + 543;
        $newweek = date('Y-m-d', strtotime($date . ' -1 week')); //ย้อนหลัง 1 สัปดาห์  
        // $newDate = date('Y-m-d', strtotime($date . ' -1 months')); //ย้อนหลัง 1 เดือน 
        $newDate = date('Y-m-d', strtotime($date . ' 1 months')); // 1 เดือน 
        // $newDate = date('Y-m-d') ; //
        // dd($date);
        $startdate = $request->startdate;
        $enddate = $request->enddate;  
 
        $datashow_ = DB::connection('mysql3')->select('   
                SELECT
                    month(a.dchdate) as months,count(o.vn) as cvn
                    ,o.vn,o.hn,o.an,pt.cid,ptname(o.hn,1) ptname
                    ,ce2be(o.vstdate) vstdate ,a.dchdate ,ptt.pttype inscl,a.pdx
                    ,w.name as ward ,oo.icode ,oit.name as ERCP ,a.uc_money 
                    ,a.income-a.discount_money-a.rcpt_money debit 
                    ,a.rcpno_list rcpno,s.AMOUNTPAY as "ชดเชย"

                    from ovst o
                    LEFT JOIN an_stat a on a.an=o.an
                    LEFT JOIN patient pt on pt.hn=o.hn
                    LEFT JOIN pttype ptt on ptt.pttype=o.pttype
                    LEFT JOIN operation_list ol ON a.an = ol.an
                    LEFT JOIN operation_detail od ON od.operation_id=ol.operation_id
                    LEFT JOIN opitemrece oo on oo.an=o.an
                    LEFT JOIN operation_item oit on oit.icode=oo.icode
                    LEFT JOIN drugitems d on d.icode=oo.icode 
                    LEFT JOIN ward w on w.ward=a.ward
                    LEFT JOIN eclaimdb.m_registerdata m on m.opdseq = o.an 
                    left outer join hshooterdb.m_rep_ucs s1 on s1.an=o.an and s1.error_code ="P" and s1.nhso_pay >"0"
                    LEFT JOIN hshooterdb.m_color_ref mc ON mc.an=o.an
                    LEFT JOIN hshooterdb.claim_status c on c.status_id=m.`STATUS` or  c.status_id=mc.`STATUS`
                    left join hshooterdb.m_stm s on s.an = o.an
                           
                    where a.dchdate between "2022-10-01" and "2023-09-30" 
                    AND oo.icode ="3010777"
                    group by month(a.dchdate)
        ');
        // month(a.dchdate) as months,count(o.vn) as cvn
        // ,o.vn,o.hn,o.an,pt.cid,ptname(o.hn,1) ptname
        // ,ce2be(o.vstdate) vstdate ,a.dchdate ,ptt.pttype inscl,a.pdx
        // ,w.name as ward ,oo.icode ,oit.name as ERCP ,a.uc_money 
        // ,a.income-a.discount_money-a.rcpt_money debit 
        // ,a.rcpno_list rcpno,s.AMOUNTPAY as "ชดเชย"

        // where a.dchdate between "' . $newweek . '" and "' . $date . '"         
        //             AND oo.icode ="3010777"
        // $datashow_count = DB::connection('mysql3')->select('   
        //         SELECT
        //             count(o.vn) as vn
        //             from ovst o
        //             LEFT JOIN an_stat a on a.an=o.an
        //             LEFT JOIN patient pt on pt.hn=o.hn
        //             LEFT JOIN pttype ptt on ptt.pttype=o.pttype
        //             LEFT JOIN operation_list ol ON a.an = ol.an
        //             LEFT JOIN operation_detail od ON od.operation_id=ol.operation_id
        //             LEFT JOIN opitemrece oo on oo.an=o.an
        //             LEFT JOIN operation_item oit on oit.icode=oo.icode
        //             LEFT JOIN drugitems d on d.icode=oo.icode 
        //             LEFT JOIN ward w on w.ward=a.ward
        //             LEFT JOIN eclaimdb.m_registerdata m on m.opdseq = o.an 
        //             left outer join hshooterdb.m_rep_ucs s1 on s1.an=o.an and s1.error_code ="P" and s1.nhso_pay >"0"
        //             LEFT JOIN hshooterdb.m_color_ref mc ON mc.an=o.an
        //             LEFT JOIN hshooterdb.claim_status c on c.status_id=m.`STATUS` or  c.status_id=mc.`STATUS`
        //             left join hshooterdb.m_stm s on s.an = o.an
       
        //             where oo.icode ="3010777"
        // ');
        $datashow_count = DB::connection('mysql3')->select('   
                SELECT
                    month(a.dchdate),
                    count(o.vn) as vn
                    from ovst o
                    LEFT JOIN an_stat a on a.an=o.an
                    LEFT JOIN patient pt on pt.hn=o.hn
                    LEFT JOIN pttype ptt on ptt.pttype=o.pttype
                    LEFT JOIN operation_list ol ON a.an = ol.an
                    LEFT JOIN operation_detail od ON od.operation_id=ol.operation_id
                    LEFT JOIN opitemrece oo on oo.an=o.an
                    LEFT JOIN operation_item oit on oit.icode=oo.icode
                    LEFT JOIN drugitems d on d.icode=oo.icode 
                    LEFT JOIN ward w on w.ward=a.ward
                    LEFT JOIN eclaimdb.m_registerdata m on m.opdseq = o.an 
                    left outer join hshooterdb.m_rep_ucs s1 on s1.an=o.an and s1.error_code ="P" and s1.nhso_pay >"0"
                    LEFT JOIN hshooterdb.m_color_ref mc ON mc.an=o.an
                    LEFT JOIN hshooterdb.claim_status c on c.status_id=m.`STATUS` or  c.status_id=mc.`STATUS`
                    left join hshooterdb.m_stm s on s.an = o.an
       
                    where a.dchdate between "2022-10-01" and "2023-09-30" 
                    AND oo.icode ="3010777"
                    group by month(a.dchdate)
        ');
        // where a.dchdate between "' . $newweek . '" and "' . $date . '"         
        //             AND oo.icode ="3010777"
        foreach ($datashow_count as $key => $value) {
            $count = $value->vn;
        }
        $year = DB::table('budget_year')->orderBy('leave_year_id', 'DESC')->get();
        $leave_month_year = DB::table('leave_month_year')->get();
        // $count = DB::connection('mysql3')->DB::table('budget_year')->count();

        return view('dashboard.report_or', [ 
            'datashow_'      =>  $datashow_,
            'year'           =>  $year, 
            'year_ids'       =>  $year_id,
            'leave_month_year' =>  $leave_month_year,
            'count'          =>  $count
        ]);
    }
    public function report_ormonth(Request $request,$month)
    {
        $year_id = $request->year_id;
        $date = date('Y-m-d'); 
 
        $datashow_ = DB::connection('mysql3')->select('   
            SELECT
                o.vn 
                ,o.hn,o.an,pt.cid,ptname(o.hn,1) ptname
                ,ce2be(o.vstdate) vstdate
                ,a.dchdate 
                ,ptt.pttype inscl 
                ,a.pdx
                ,w.name as ward
                ,oo.icode
                ,oit.name as ERCP
                ,a.uc_money 
                ,a.income-a.discount_money-a.rcpt_money debit 
                ,a.rcpno_list rcpno 
                ,s.AMOUNTPAY as "ชดเชย"
                
                from ovst o
                LEFT JOIN an_stat a on a.an=o.an
                LEFT JOIN patient pt on pt.hn=o.hn
                LEFT JOIN pttype ptt on ptt.pttype=o.pttype
                LEFT JOIN operation_list ol ON a.an = ol.an
                LEFT JOIN operation_detail od ON od.operation_id=ol.operation_id
                LEFT JOIN opitemrece oo on oo.an=o.an
                LEFT JOIN operation_item oit on oit.icode=oo.icode
                LEFT JOIN drugitems d on d.icode=oo.icode 
                LEFT JOIN ward w on w.ward=a.ward
                LEFT JOIN eclaimdb.m_registerdata m on m.opdseq = o.an  
                LEFT JOIN hshooterdb.m_stm s on s.an = o.an 
                
                where a.dchdate between "2022-10-01" and "2023-09-30" 
                AND oo.icode ="3010777"
                AND month(a.dchdate) ="'.$month.'" 
        ');
        // month(a.dchdate) as months,count(o.vn) as cvn
        // ,o.vn,o.hn,o.an,pt.cid,ptname(o.hn,1) ptname
        // ,ce2be(o.vstdate) vstdate ,a.dchdate ,ptt.pttype inscl,a.pdx
        // ,w.name as ward ,oo.icode ,oit.name as ERCP ,a.uc_money 
        // ,a.income-a.discount_money-a.rcpt_money debit 
        // ,a.rcpno_list rcpno,s.AMOUNTPAY as "ชดเชย"
 
        return view('dashboard.report_ormonth', [ 
            'datashow_'      =>  $datashow_, 
            'year_ids'       =>  $year_id, 
        ]);
    }
    
}
