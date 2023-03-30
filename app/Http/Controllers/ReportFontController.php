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
 
        $datashow_ipd = DB::connection('mysql3')->select('   
            select month(v.dchdate) as months
            ,count(distinct v.hn) as HN 
            ,count(distinct v.an) as AN
            ,count(distinct ii.an) as IMC
            ,count(distinct i1.an) as IMC_BRAIN
            ,count(distinct i2.an) as IMC_INJURY 
            from hos.an_stat v
            left outer join hos.ipt i on i.an = v.an
            inner join hos.referout r on r.vn = i.an
            left outer join  hos.opitemrece o on o.an = v.an
            left outer join  hos.nondrugitems n on n.icode = o.icode
            left outer join eclaimdb.m_registerdata m on m.an = v.an
            left outer join eclaimdb.m_sumfund mmm on mmm.eclaim_no = m.eclaim_no
            left join hos.iptdiag ii on ii.an = v.an 
            and (ii.icd10 between "i60" and "i64" or ii.icd10 between "g81" and "g83" or ii.icd10 between "g47" and "g48" or ii.icd10 between "g41" 
            and "g419" or ii.icd10 between "g41" and "g419" or ii.icd10 between "g27" and "g279")
            left join hos.iptdiag i1 on i1.an = v.an 
            and (i1.icd10 between "s60" and "s609" or i1.icd10 = "t902"  or i1.icd10 = "t905")
            left join hos.iptdiag i2 on i2.an = v.an 
            and (i2.icd10 between "s14" and "s149" or i2.icd10 = "t913"  or i2.icd10 between "s24" and "s249" or i2.icd10 between "s34" and "s349")
            where v.dchdate between "' . $startdate . '" and "' . $enddate . '"         
            group by month(v.dchdate)
        ');
   
        $year = DB::table('budget_year')->orderBy('leave_year_id', 'DESC')->get();

        return view('dashboard.report_dashboard', [ 
            'datashow_ipd'      =>  $datashow_ipd,
            'year'              =>  $year, 
            'year_ids'           =>  $year_id
        ]);
    }
    
}
