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
        $datenow = date('Y-m-d');
        $y = date('Y') + 543;
        $newweek = date('Y-m-d', strtotime($datenow . ' -1 week')); //ย้อนหลัง 1 สัปดาห์  
        $newDate = date('Y-m-d', strtotime($datenow . ' -1 months')); //ย้อนหลัง 1 เดือน 
        // $newDate = date('Y-m-d', strtotime($date . ' 1 months')); // 1 เดือน   
        $startdate = $request->startdate;
        $enddate = $request->enddate;  
        // dd($date);
        $dataopd_ = DB::connection('mysql3')->select('   
            select COUNT(ro.hn) as OHN
                from referout ro    
                where ro.department = "OPD" and ro.refer_date=CURDATE() 
        ');
        $dataipd_ = DB::connection('mysql3')->select(' 
                select COUNT(ro.hn) as IPH
                from referout ro 
                where ro.department = "IPD" and ro.refer_date=CURDATE()
        ');
        foreach ($dataopd_ as $key => $value1) {
            $dataopd_ = $value1->OHN;
        }
        foreach ($dataipd_ as $key => $value2) {
            $dataipd_ = $value2->IPH;
        }
        $total_refer = $dataopd_ + $dataipd_;

        $refer_ = DB::connection('mysql8')->select(' 
            SELECT COUNT(hn) as HN FROM referout 
            WHERE loads_id="02"
            AND refer_date BETWEEN "'.$newDate.'" AND "'.$datenow.'" 
        ');
        foreach ($refer_ as $key => $value3) {
            $refer = $value3->HN;
        }
        $dataknee_ = DB::connection('mysql3')->select(' 
                SELECT COUNT(e.an) as AN                
                from an_stat e
                left outer join patient pt on pt.hn = e.hn
                left outer join pttype p on p.pttype = e.pttype
                left outer join iptdiag im on im.an=e.an
                left join ipt ip on ip.an = e.an
                left join ipt_pttype it2 on it2.an=e.an  
                left join hos.ipdrent ir on ir.an =e.an
                left outer join hos.opitemrece oo on oo.an = e.an 
                LEFT JOIN hos.rent_reason r on r.id = ir.rent_reason_id  
                left join hos.nondrugitems n1 on n1.icode = oo.icode
                left join hos.s_drugitems sd on sd.icode = oo.icode
                where e.dchdate BETWEEN "'.$newDate.'" AND "'.$datenow.'"                  
                and oo.icode IN("3009737","3010372","3010569");
        '); 
        foreach ($dataknee_ as $value4) {
            $dataknee = $value4->AN;
        }
        $Opdknee_ = DB::connection('mysql3')->select(' 
                SELECT COUNT(v.vn) as VN           
                from vn_stat v 
                left outer join hos.opitemrece oo on oo.vn = v.vn   
                left join hos.nondrugitems n1 on n1.icode = oo.icode
                left join hos.s_drugitems sd on sd.icode = oo.icode
                where v.vstdate BETWEEN "'.$newDate.'" AND "'.$datenow.'"                  
                and oo.icode IN("3009737","3010372","3010569");
        ');
        foreach ($Opdknee_ as $value5) {
            $Opdknee = $value5->VN;
        }
        $countsaphok_ = DB::connection('mysql3')->select('
                SELECT COUNT(DISTINCT a.an) as AN   
                from an_stat a 
                left outer join hos.opitemrece oo on oo.an = a.an  
                where a.dchdate BETWEEN "'.$newDate.'" AND "'.$datenow.'"                  
                and oo.icode IN("3009738","3009739","3010896","3009740","3010228");
        ');
        foreach ($countsaphok_ as $value6) {
            $countsaphok = $value6->AN;
        }
        $countkradook_ = DB::connection('mysql3')->select('
                SELECT COUNT(DISTINCT a.an) as AN  
                from an_stat a 
                left join ipt ip on ip.an = a.an
                left outer join hos.opitemrece oo on oo.an = a.an  
                where a.dchdate BETWEEN "'.$newDate.'" AND "'.$datenow.'"                  
                and oo.icode IN("3011002","3009749");  
        ');
         
        foreach ($countkradook_ as $value7) {
            $countkradook = $value7->AN;
        }
        // dd($datenow);
        return view('dashboard.report_dashboard', [ 
            'dataknee'          =>  $dataknee,
            'refer'             =>  $refer, 
            'total_refer'       =>  $total_refer, 
            'Opdknee'           =>  $Opdknee,
            'newDate'           =>  $newDate,
            'datenow'           =>  $datenow,
            'countsaphok'       =>  $countsaphok,
            'countkradook'      =>  $countkradook,
        ]);
    }
    public function check_knee_ipddetail(Request $request,$newDate,$datenow)
    { 
        $dataknee_ = DB::connection('mysql3')->select('   
                SELECT ip.vn,e.hn,e.an,e.regdate,e.dchdate,group_concat(distinct it2.pttype) as pttype
                ,concat(pt.pname,pt.fname," ",pt.lname) as fullname,oo.icode,e.pdx,e.dx0,e.dx1,e.dx2,e.dx3,e.dx4
                ,sd.name as s_name,e.inc08 as INCOMEKNEE,e.income as INCOME,e.paid_money as PAY,sum(distinct oo.sum_price) as Priceknee
                ,group_concat(distinct n1.name) as Nameknee,e.uc_money,ip.pttype,pt.cid  
                from an_stat e
                    left outer join patient pt on pt.hn = e.hn
                    left outer join pttype p on p.pttype = e.pttype
                    left outer join iptdiag im on im.an=e.an
                    left join ipt ip on ip.an = e.an
                    left join ipt_pttype it2 on it2.an=e.an  
                    left join hos.ipdrent ir on ir.an =e.an
                    left outer join hos.opitemrece oo on oo.an = e.an 
                    LEFT JOIN hos.rent_reason r on r.id = ir.rent_reason_id  
                    left join hos.nondrugitems n1 on n1.icode = oo.icode
                    left join hos.s_drugitems sd on sd.icode = oo.icode
                    where e.dchdate BETWEEN "'.$newDate.'" AND "'.$datenow.'"             
                    and oo.icode IN("3009737","3010372","3010569")
                    group by e.an;
        ');
       
        
        return view('dashboard.check_knee_detail', [ 
            'dataknee_'      =>  $dataknee_,
            'newDate'        =>  $newDate,
            'datenow'        =>  $datenow,
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

    public function report_refer(Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate; 
        $datashow_ = DB::connection('mysql6')->select(' 
                SELECT ID,STATUS as REFER,CAR_GO_MILE,CAR_BACK_MILE ,OUT_DATE,OUT_TIME,BACK_DATE,BACK_TIME,DRIVER_NAME,USER_REQUEST_NAME,ADD_OIL_BATH,COMMENT,CAR_REG,REFER_TYPE_ID 
                FROM vehicle_car_refer v
                LEFT JOIN vehicle_car_index vc ON vc.CAR_ID = v.CAR_ID
                WHERE REFER_TYPE_ID = "1"
                AND OUT_DATE BETWEEN "'.$startdate.'" and "'.$enddate.'"  
        ');
        
        // dd($total_refer);
        return view('dashboard.report_refer',[
            'start'        => $startdate,
            'end'          => $enddate ,
            // 'total_refer'  => $total_refer ,
            'datashow_'    => $datashow_
        ]);
    }
    public function report_refer_hos(Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate; 
        $datashow_ = DB::connection('mysql3')->select(' 
            SELECT  
                ro.department,ro.hn,ro.vn,concat(p.pname,p.fname," ",p.lname) as ptname,
                ro.refer_date,o.vstdate,o.vsttime,d.name as doctor_name,o.hospmain,
                concat(h.hosptype," ",h.name) as hospname,h.province_name,h.area_code,
                ro.with_ambulance,ro.with_nurse,pe.name as pttype_name,r.name as refername, 
                ro.refer_point,concat(ro.pdx," : ",ic.name) as icd_name,ot.unitprice,ot.qty,ot.sum_price,s.nhso_adp_code
                FROM referout ro  
                LEFT OUTER JOIN ovst o on o.vn = ro.vn  
                LEFT OUTER JOIN patient p on p.hn=ro.hn  
                LEFT OUTER JOIN hospcode h on h.hospcode = ro.refer_hospcode  
                LEFT OUTER JOIN rfrcs r on r.rfrcs = ro.rfrcs  
                LEFT OUTER JOIN doctor d on d.code = ro.doctor  
                LEFT OUTER JOIN pttype pe on pe.pttype = o.pttype  
                LEFT OUTER JOIN icd101 ic on ic.code = ro.pdx
                left outer join opitemrece ot ON ot.vn = ro.vn  
                left outer join s_drugitems s on s.icode=ot.icode  
                left outer join drugusage du on du.drugusage=ot.drugusage  
                left outer join sp_use u on u.sp_use = ot.sp_use  
                left outer join drugitems i on i.icode=ot.icode
                WHERE ro.refer_date BETWEEN "'.$startdate.'" AND "'.$enddate.'"
                AND ro.department = "OPD"  
                GROUP BY ro.vn

                UNION 

            SELECT 
                ro.department,ro.hn,ro.vn,concat(p.pname,p.fname," ",p.lname) as ptname,
                ro.refer_date,o.regdate as vstdate,o.regtime as vsttime,d.name as doctor_name,"" as hospmain
                ,concat(h.hosptype," ",h.name) as hospname,h.province_name,h.area_code,
                ro.with_ambulance,ro.with_nurse,pe.name as pttype_name,  
                r.name as refername,ro.refer_point,concat(ro.pdx," : ",ic.name) as icd_name,ot.unitprice,ot.qty,ot.sum_price,s.nhso_adp_code
                from referout ro  
                LEFT OUTER JOIN ipt o on o.an = ro.vn  
                LEFT OUTER JOIN patient p on p.hn=ro.hn  
                LEFT OUTER JOIN hospcode h on h.hospcode = ro.refer_hospcode 
                LEFT OUTER JOIN rfrcs r on r.rfrcs = ro.rfrcs  
                LEFT OUTER JOIN doctor d on d.code = ro.doctor  
                LEFT OUTER JOIN pttype pe on pe.pttype = o.pttype  
                LEFT OUTER JOIN icd101 ic on ic.code = ro.pdx 
                left outer join opitemrece ot ON ot.vn = ro.vn 
                left outer join s_drugitems s on s.icode=ot.icode  
                left outer join drugusage du on du.drugusage=ot.drugusage  
                left outer join sp_use u on u.sp_use = ot.sp_use  
                left outer join drugitems i on i.icode=ot.icode
                WHERE ro.refer_date BETWEEN "'.$startdate.'" AND "'.$enddate.'"
                AND ro.department = "IPD"
                GROUP BY ro.vn
        ');
         
        return view('dashboard.report_refer_hos',[
            'start'        => $startdate,
            'end'          => $enddate ,
            // 'total_refer'  => $total_refer ,
            'datashow_'    => $datashow_
        ]);
    }
    public function check_knee_ipd(Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate; 
        $datashow_ = DB::connection('mysql3')->select(' 
                SELECT ip.vn,e.hn,e.an,e.regdate,e.dchdate,group_concat(distinct it2.pttype) as pttype
                        ,concat(pt.pname,pt.fname," ",pt.lname) as fullname,oo.icode,e.pdx,e.dx0,e.dx1,e.dx2,e.dx3,e.dx4
                        ,sd.name as s_name 
                        ,e.inc08 as INCOMEKNEE
                        ,e.income as INCOME
                        ,e.paid_money as PAY
                        ,sum(distinct oo.sum_price) as Priceknee
                        ,group_concat(distinct n1.name) as Nameknee 
                        ,e.uc_money 
                        ,ip.pttype 
                        ,pt.cid 
                        from an_stat e
                        left outer join patient pt on pt.hn = e.hn
                        left outer join pttype p on p.pttype = e.pttype
                        left outer join iptdiag im on im.an=e.an
                        left join ipt ip on ip.an = e.an
                        left join ipt_pttype it2 on it2.an=e.an  
                        left join hos.ipdrent ir on ir.an =e.an
                        left outer join hos.opitemrece oo on oo.an = e.an 
                        LEFT JOIN hos.rent_reason r on r.id = ir.rent_reason_id  
                        left join hos.nondrugitems n1 on n1.icode = oo.icode
                        left join hos.s_drugitems sd on sd.icode = oo.icode
                        where e.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'"                
                        and oo.icode IN("3009737","3010372","3010569")
                        group by e.an; 
        ');
    
        return view('dashboard.check_knee_ipd',[
            'start'     => $startdate,
            'end'       => $enddate ,
            'datashow_' => $datashow_
        ]);
    }
    public function check_knee_opd(Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate; 
        $startdate = $request->startdate;
        $enddate = $request->enddate; 
        $datashow_ = DB::connection('mysql3')->select(' 
                SELECT ip.vn,e.hn,e.an,e.regdate,e.dchdate,group_concat(distinct it2.pttype) as pttype
                        ,concat(pt.pname,pt.fname," ",pt.lname) as fullname,oo.icode,e.pdx,e.dx0,e.dx1,e.dx2,e.dx3,e.dx4
                        ,sd.name as s_name 
                        ,e.inc08 as INCOMEKNEE
                        ,e.income as INCOME
                        ,e.paid_money as PAY
                        ,sum(distinct oo.sum_price) as Priceknee
                        ,group_concat(distinct n1.name) as Nameknee 
                        ,e.uc_money 
                        ,ip.pttype 
                        ,pt.cid 
                        from an_stat e
                        left outer join patient pt on pt.hn = e.hn
                        left outer join pttype p on p.pttype = e.pttype
                        left outer join iptdiag im on im.an=e.an
                        left join ipt ip on ip.an = e.an
                        left join ipt_pttype it2 on it2.an=e.an  
                        left join hos.ipdrent ir on ir.an =e.an
                        left outer join hos.opitemrece oo on oo.an = e.an 
                        LEFT JOIN hos.rent_reason r on r.id = ir.rent_reason_id  
                        left join hos.nondrugitems n1 on n1.icode = oo.icode
                        left join hos.s_drugitems sd on sd.icode = oo.icode
                        where e.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'"                
                        and oo.icode IN("3009737","3010372","3010569")
                        group by e.an; 
        ');
    
        return view('dashboard.check_knee_opd',[
            'start'     => $startdate,
            'end'       => $enddate ,
            'datashow_' => $datashow_
        ]);
    }
    public function check_kradook(Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate; 
        $datashow_ = DB::connection('mysql3')->select(' 
                SELECT ip.vn,a.hn,a.an,pt.cid ,a.regdate,a.dchdate,a.pttype 
                ,concat(pt.pname,pt.fname," ",pt.lname) as fullname
                ,oo.icode,sum(distinct oo.sum_price) as Price
                ,group_concat(distinct n1.name) as ListName 
                ,a.inc08,a.income,a.paid_money,a.uc_money 
                from an_stat a
                left outer join patient pt on pt.hn = a.hn
                left outer join pttype p on p.pttype = a.pttype               
                left join ipt ip on ip.an = a.an
                left join hos.ipdrent ir on ir.an =a.an
                left outer join hos.opitemrece oo on oo.an = a.an                 
                left join hos.nondrugitems n1 on n1.icode = oo.icode
                left join hos.s_drugitems sd on sd.icode = oo.icode
                where a.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'"                
                and oo.icode IN("3011002","3009749")
                group by a.an; 
        ');
    
        return view('dashboard.check_kradook',[
            'start'     => $startdate,
            'end'       => $enddate ,
            'datashow_' => $datashow_
        ]);
    }
    public function check_khosaphok(Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate; 
        $datashow_ = DB::connection('mysql3')->select(' 
            SELECT ip.vn,a.hn,a.an,pt.cid ,a.regdate,a.dchdate,group_concat(distinct it2.pttype) as pttype
                ,concat(pt.pname,pt.fname," ",pt.lname) as fullname
                ,oo.icode ,sum(distinct oo.sum_price) as Price
                ,group_concat(distinct n1.name) as ListName 
                ,a.inc08 ,a.income,a.paid_money,a.uc_money 
                from an_stat a
                left outer join patient pt on pt.hn = a.hn
                left outer join pttype p on p.pttype = a.pttype
                left outer join iptdiag im on im.an=a.an
                left join ipt ip on ip.an = a.an
                left join ipt_pttype it2 on it2.an=a.an  
                left join hos.ipdrent ir on ir.an =a.an
                left outer join hos.opitemrece oo on oo.an = a.an 
                LEFT JOIN hos.rent_reason r on r.id = ir.rent_reason_id  
                left join hos.nondrugitems n1 on n1.icode = oo.icode
                left join hos.s_drugitems sd on sd.icode = oo.icode
                where a.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'"                
                and oo.icode IN("3009738","3009739","3010896","3009740","3010228")
                group by a.an;  
        ');
    
        return view('dashboard.check_khosaphok',[
            'start'     => $startdate,
            'end'       => $enddate ,
            'datashow_' => $datashow_
        ]);
    }
    public function check_khosaphokdetail(Request $request,$newDate,$datenow)
    { 
        $datashow_ = DB::connection('mysql3')->select('   
                SELECT ip.vn,a.hn,a.an,pt.cid ,a.regdate,a.dchdate,group_concat(distinct it2.pttype) as pttype
                ,concat(pt.pname,pt.fname," ",pt.lname) as fullname
                ,oo.icode
                ,sum(distinct oo.sum_price) as Price
                ,group_concat(distinct n1.name) as ListName 
                ,a.inc08 
                ,a.income 
                ,a.paid_money 
                ,a.uc_money  
                
                from an_stat a
                left outer join patient pt on pt.hn = a.hn
                left outer join pttype p on p.pttype = a.pttype
                left outer join iptdiag im on im.an=a.an
                left join ipt ip on ip.an = a.an
                left join ipt_pttype it2 on it2.an=a.an  
                left join hos.ipdrent ir on ir.an =a.an
                left outer join hos.opitemrece oo on oo.an = a.an 
                LEFT JOIN hos.rent_reason r on r.id = ir.rent_reason_id  
                left join hos.nondrugitems n1 on n1.icode = oo.icode
                left join hos.s_drugitems sd on sd.icode = oo.icode
                where a.dchdate BETWEEN "'.$newDate.'" AND "'.$datenow.'"                
                and oo.icode IN("3009738","3009739","3010896","3009740","3010228")
                group by a.an; 
        ');
               
        return view('dashboard.check_khosaphokdetail', [ 
            'datashow_'      =>  $datashow_,
            'newDate'        =>  $newDate,
            'datenow'        =>  $datenow,
        ]);
    }
    public function check_kradookdetail(Request $request,$newDate,$datenow)
    { 
        $datashow_ = DB::connection('mysql3')->select('   
                SELECT ip.vn,a.hn,a.an,pt.cid ,a.regdate,a.dchdate,group_concat(distinct it2.pttype) as pttype
                ,concat(pt.pname,pt.fname," ",pt.lname) as fullname
                ,oo.icode
                ,sum(distinct oo.sum_price) as Price
                ,group_concat(distinct n1.name) as ListName 
                ,a.inc08 
                ,a.income 
                ,a.paid_money 
                ,a.uc_money  
                
                from an_stat a
                left outer join patient pt on pt.hn = a.hn
                left outer join pttype p on p.pttype = a.pttype
                left outer join iptdiag im on im.an=a.an
                left join ipt ip on ip.an = a.an
                left join ipt_pttype it2 on it2.an=a.an  
              
                left outer join hos.opitemrece oo on oo.an = a.an 
                
                left join hos.nondrugitems n1 on n1.icode = oo.icode 
                where a.dchdate BETWEEN "'.$newDate.'" AND "'.$datenow.'"                
                and oo.icode IN("3011002","3009749")
                and oo.an 
                group by a.an; 
        ');
        // left join hos.ipdrent ir on ir.an =a.an

        // LEFT JOIN hos.rent_reason r on r.id = ir.rent_reason_id 
               
        return view('dashboard.check_kradookdetail', [ 
            'datashow_'      =>  $datashow_,
            'newDate'        =>  $newDate,
            'datenow'        =>  $datenow,
        ]);
    }
    
}
