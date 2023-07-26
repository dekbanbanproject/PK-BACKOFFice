<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Validator;
use App\Models\User;
use App\Models\Ot_one;
use PDF;
use setasign\Fpdi\Fpdi;
use App\Models\Budget_year;
// use Illuminate\Support\Facades\File;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
// use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\OtExport;
// use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Department;
use App\Models\Departmentsub;
use App\Models\Departmentsubsub;
use App\Models\Position;
use App\Models\Product_spyprice;
use App\Models\Products;
use App\Models\Products_type;
use App\Models\Product_group;
use App\Models\Product_unit;
use App\Models\Products_category;
use App\Models\Article;
use App\Models\Product_prop;
use App\Models\Product_decline;
use App\Models\Department_sub_sub;
use App\Models\Aipn_stm;
use App\Models\Status;
use App\Models\Aipn_ipdx;
use App\Models\Aipn_ipop;
use App\Models\Aipn_session;
use App\Models\Aipn_billitems;
use App\Models\Aipn_ipadt;
use App\Models\Check_sit;
use App\Models\Stm;

use App\Models\D_export_ucep;
use App\Models\D_ins;
use App\Models\D_pat;
use App\Models\D_opd;
use App\Models\D_orf;
use App\Models\D_odx;
use App\Models\D_cht;
use App\Models\D_cha;

use App\Models\Ssop_billitems;
use App\Models\Claim_ssop;
use App\Models\Claim_sixteen_dru;
use App\Models\claim_sixteen_adp;
use App\Models\Claim_sixteen_cha;
use App\Models\Claim_sixteen_cht;
use App\Models\Claim_sixteen_oop;
use App\Models\Claim_sixteen_odx;
use App\Models\Claim_sixteen_orf;
use App\Models\Claim_sixteen_pat;
use App\Models\Claim_sixteen_ins;
use App\Models\Claim_temp_ssop;
use App\Models\Claim_sixteen_opd;
use Auth;
use ZipArchive;
use Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\If_;
use Stevebauman\Location\Facades\Location;
use SoapClient;
use SplFileObject;
use File;
use Illuminate\Filesystem\Filesystem;


class SixteenController extends Controller
{
    public function six(Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate;

        if ($startdate != '') {
            D_export_ucep::truncate();
            $data = DB::connection('mysql3')->select('
                SELECT o.vn,o.an,o.hn,p.cid,o.vstdate,o.pttype
                        ,concat(p.pname," ",p.fname," ", p.lname) as ptname
                        ,a.pdx ,g.er_screen,ee.er_emergency_level_name
                        from ovst o
                        left outer join an_stat a on a.an = o.an
                        left outer join spclty s on s.spclty=o.spclty
                        left outer join patient p on o.hn=p.hn
                        left outer join er_regist g on g.vn=o.vn
                        left outer join er_emergency_level ee on ee.er_emergency_level_id = g.er_emergency_level_id
                        left outer join pttype pt on pt.pttype = a.pttype
                        where a.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'"
                        AND g.er_emergency_level_id IN("1","2")
                        AND o.an <>"" and pt.hipdata_code ="UCS"
                        group by o.vn;
            ');
            foreach ($data as $va2) {
                $date = date('Y-m-d');
                D_export_ucep::insert([
                    'hn'                       => $va2->hn,
                    'an'                       => $va2->an,
                    'vn'                       => $va2->vn,
                    'cid'                      => $va2->cid,
                    'fullname'                 => $va2->ptname,
                    'send_date'                => $date,
                    'vstdate'                  => $va2->vstdate,
                    'pdx'                      => $va2->pdx,
                    'pttype'                   => $va2->pttype,
                    'er_screen'                => $va2->er_screen,
                    'er_emergency_level_name'  => $va2->er_emergency_level_name,
                ]);
            }
        } else {
            $data = DB::connection('mysql3')->select('
                SELECT o.vn,o.an,o.hn,p.cid,o.vstdate,o.pttype
                        ,concat(p.pname," ",p.fname," ", p.lname) as ptname
                        ,a.pdx ,g.er_screen,ee.er_emergency_level_name
                        from ovst o
                        left outer join an_stat a on a.an = o.an
                        left outer join spclty s on s.spclty=o.spclty
                        left outer join patient p on o.hn=p.hn
                        left outer join er_regist g on g.vn=o.vn
                        left outer join er_emergency_level ee on ee.er_emergency_level_id = g.er_emergency_level_id
                        left outer join pttype pt on pt.pttype = a.pttype
                        where a.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'"
                        AND g.er_emergency_level_id IN("1","2")
                        AND o.an <>"" and pt.hipdata_code ="UCS"
                        group by o.vn;
            ');
        }

        $data['data_show']     = D_export_ucep::get();
        $data['data_ins']      = D_ins::get();
        $data['data_pat']      = D_pat::get();
        $data['data_opd']      = D_opd::get();
        return view('claim.six',$data,[
            'startdate'        => $startdate,
            'enddate'          => $enddate,
        ]);
    }
    public function six_pull(Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $data = DB::connection('mysql3')->select('
                SELECT
                    "" d_ins_id
                    ,v.hn HN
                    ,if(i.an is null,p.hipdata_code,pp.hipdata_code) INSCL
                    ,if(i.an is null,p.pcode,pp.pcode) SUBTYPE
                    ,v.cid CID
                    ,DATE_FORMAT(if(i.an is null,v.pttype_begin,ap.begin_date), "%Y%m%d")  DATEIN
                    ,DATE_FORMAT(if(i.an is null,v.pttype_expire,ap.expire_date), "%Y%m%d")   DATEEXP
                    ,if(i.an is null,v.hospmain,ap.hospmain) HOSPMAIN
                    ,if(i.an is null,v.hospsub,ap.hospsub) HOSPSUB
                    ,"" GOVCODE
                    ,"" GOVNAME
                    ,ifnull(if(i.an is null,vp.claim_code or vp.auth_code,ap.claim_code),r.sss_approval_code) PERMITNO
                    ,"" DOCNO
                    ,"" OWNRPID
                    ,"" OWNRNAME
                    ,i.an AN
                    ,v.vn SEQ
                    ,"" SUBINSCL
                    ,"" RELINSCL
                    ,"" HTYPE
                    ,"" created_at
                    ,"" updated_at
                    from vn_stat v
                    LEFT JOIN pttype p on p.pttype = v.pttype
                    LEFT JOIN ipt i on i.vn = v.vn
                    LEFT JOIN pttype pp on pp.pttype = i.pttype
                    left join ipt_pttype ap on ap.an = i.an
                    left join visit_pttype vp on vp.vn = v.vn
                    LEFT JOIN rcpt_debt r on r.vn = v.vn
                    left join patient px on px.hn = v.hn
                    left join claim.d_export_ucep x on x.vn = v.vn
                where x.active="N";
        ');
        D_ins::truncate();
        foreach ($data as $va1) {
            $date = date('Y-m-d');
            D_ins::insert([
                'HN'                     => $va1->HN,
                'INSCL'                  => $va1->INSCL,
                'SUBTYPE'                => $va1->SUBTYPE,
                'CID'                    => $va1->CID,
                'DATEIN'                 => $va1->DATEIN,
                'DATEEXP'                => $va1->DATEEXP,
                'HOSPMAIN'               => $va1->HOSPMAIN,
                'HOSPSUB'                => $va1->HOSPSUB,
                'GOVCODE'                => $va1->GOVCODE,
                'GOVNAME'                => $va1->GOVNAME,
                'PERMITNO'               => $va1->PERMITNO,
                'DOCNO'                  => $va1->DOCNO,
                'OWNRPID'                => $va1->OWNRPID,
                'OWNRNAME'               => $va1->OWNRNAME,
                'AN'                     => $va1->AN,
                'SEQ'                    => $va1->SEQ,
                'SUBINSCL'               => $va1->SUBINSCL,
                'RELINSCL'               => $va1->RELINSCL,
                'HTYPE'                  => $va1->HTYPE
            ]);
        }

        $data_pat = DB::connection('mysql3')->select('
                SELECT "" d_pat_id
                ,v.hcode HCODE
                ,v.hn HN
                ,pt.chwpart CHANGWAT
                ,pt.amppart AMPHUR
                ,DATE_FORMAT(pt.birthday,"%Y%m%d") DOB
                ,pt.sex SEX
                ,pt.marrystatus MARRIAGE
                ,pt.occupation OCCUPA
                ,lpad(pt.nationality,3,0) NATION
                ,pt.cid PERSON_ID
                ,concat(pt.fname," ",pt.lname,",",pt.pname) NAMEPAT
                ,pt.pname TITLE
                ,pt.fname FNAME
                ,pt.lname LNAME
                ,"1" IDTYPE
                ,"" created_at
                ,"" updated_at
                from vn_stat v
                LEFT JOIN pttype p on p.pttype = v.pttype
                LEFT JOIN ipt i on i.vn = v.vn
                LEFT JOIN patient pt on pt.hn = v.hn
                left join claim.d_export_ucep x on x.vn = v.vn
                where x.active="N";
        ');
        D_pat::truncate();
        foreach ($data_pat as $va2) {
            D_pat::insert([
                'HCODE'               => $va2->HCODE,
                'HN'                  => $va2->HN,
                'CHANGWAT'            => $va2->CHANGWAT,
                'AMPHUR'              => $va2->AMPHUR,
                'DOB'                 => $va2->DOB,
                'SEX'                 => $va2->SEX,
                'MARRIAGE'            => $va2->MARRIAGE,
                'OCCUPA'              => $va2->OCCUPA,
                'NATION'              => $va2->NATION,
                'PERSON_ID'           => $va2->PERSON_ID,
                'NAMEPAT'             => $va2->NAMEPAT,
                'TITLE'               => $va2->TITLE,
                'FNAME'               => $va2->FNAME,
                'LNAME'               => $va2->LNAME,
                'IDTYPE'              => $va2->IDTYPE
            ]);
        }

        $data_opd = DB::connection('mysql3')->select('
                SELECT "" d_opd_id
                ,v.hn HN
                ,v.spclty CLINIC
                ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                ,concat(substr(o.vsttime,1,2),substr(o.vsttime,4,2)) TIMEOPD
                ,v.vn SEQ
                ,"1" UUC
                ,"" created_at
                ,"" updated_at
                from vn_stat v
                LEFT JOIN ovst o on o.vn = v.vn
                LEFT JOIN pttype p on p.pttype = v.pttype
                LEFT JOIN ipt i on i.vn = v.vn
                LEFT JOIN patient pt on pt.hn = v.hn
                left join claim.d_export_ucep x on x.vn = v.vn
                where x.active="N";
        ');
        D_opd::truncate();
        foreach ($data_opd as $va3) {
            D_opd::insert([
                'HN'                  => $va3->HN,
                'CLINIC'              => $va3->CLINIC,
                'DATEOPD'             => $va3->DATEOPD,
                'TIMEOPD'             => $va3->TIMEOPD,
                'SEQ'                 => $va3->SEQ,
                'UUC'                 => $va3->UUC
            ]);
        }

        $data_orf = DB::connection('mysql3')->select('
                SELECT
                "" d_orf_id
                ,v.hn HN
                ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                ,v.spclty CLINIC
                ,ifnull(r1.refer_hospcode,r2.refer_hospcode) REFER
                ,"0100" REFERTYPE
                ,v.vn SEQ
                ,"" created_at
                ,"" updated_at
                from vn_stat v
                LEFT JOIN ovst o on o.vn = v.vn
                LEFT JOIN referin r1 on r1.vn = v.vn
                LEFT JOIN referout r2 on r2.vn = v.vn
                left join claim.d_export_ucep x on x.vn = v.vn
                where x.active="N"
                and (r1.vn is not null or r2.vn is not null);
        ');
        D_orf::truncate();
        foreach ($data_orf as $va4) {
            D_orf::insert([
                'HN'                => $va4->HN,
                'DATEOPD'           => $va4->DATEOPD,
                'CLINIC'            => $va4->CLINIC,
                'REFER'             => $va4->REFER,
                'REFERTYPE'         => $va4->REFERTYPE,
                'SEQ'               => $va4->SEQ
            ]);
        }

        $data_odx = DB::connection('mysql3')->select('
            SELECT
                "" d_odx_id
                ,v.hn HN
                ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEDX
                ,v.spclty CLINIC
                ,o.icd10 DIAG
                ,o.diagtype DXTYPE
                ,if(d.licenseno="","-99999",d.licenseno) DRDX
                ,v.cid PERSON_ID
                ,v.vn SEQ
                ,"" created_at
                ,"" updated_at
                from vn_stat v
                LEFT JOIN ovstdiag o on o.vn = v.vn
                LEFT JOIN doctor d on d.`code` = o.doctor
                inner JOIN icd101 i on i.code = o.icd10
                left join claim.d_export_ucep x on x.vn = v.vn
                where x.active="N";
        ');
        D_odx::truncate();
        foreach ($data_odx as $va5) {
            D_odx::insert([
                'HN'                => $va5->HN,
                'DATEDX'            => $va5->DATEDX,
                'CLINIC'            => $va5->CLINIC,
                'DIAG'              => $va5->DIAG,
                'DXTYPE'            => $va5->DXTYPE,
                'DRDX'              => $va5->DRDX,
                'PERSON_ID'         => $va5->PERSON_ID,
                'SEQ'               => $va5->SEQ,
            ]);
        }

        $data_oop = DB::connection('mysql3')->select('
                SELECT "" d_oop_id
                ,v.hn HN
                ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                ,v.spclty CLINIC
                ,o.icd10 OPER
                ,if(d.licenseno="","-99999",d.licenseno) DROPID
                ,pt.cid PERSON_ID
                ,v.vn SEQ
                ,"" created_at
                ,"" updated_at
                from vn_stat v
                LEFT JOIN ovstdiag o on o.vn = v.vn
                LEFT JOIN patient pt on v.hn=pt.hn
                LEFT JOIN doctor d on d.`code` = o.doctor
                inner JOIN icd9cm1 i on i.code = o.icd10
                left join claim.d_export_ucep x on x.vn = v.vn
                where x.active="N";
        ');
        D_oop::truncate();
        foreach ($data_oop as $va6) {
            D_oop::insert([
                'HN'                => $va6->HN,
                'DATEOPD'           => $va6->DATEOPD,
                'CLINIC'            => $va6->CLINIC,
                'OPER'              => $va6->OPER,
                'DROPID'            => $va6->DROPID,
                'PERSON_ID'         => $va6->PERSON_ID,
                'SEQ'               => $va6->SEQ,
            ]);
        }

        $data_cht = DB::connection('mysql3')->select('
                SELECT "" d_cht_id
                ,v.hn HN
                ,v.an AN
                ,DATE_FORMAT(if(a.an is null,v.vstdate,a.dchdate),"%Y%m%d") DATE
                ,round(if(a.an is null,vv.income,a.income),2) TOTAL
                ,round(if(a.an is null,vv.paid_money,a.paid_money),2) PAID
                ,if(vv.paid_money >"0" or a.paid_money >"0","10",pt.pcode) PTTYPE
                ,pp.cid PERSON_ID
                ,v.vn SEQ
                ,"" created_at
                ,"" updated_at
                from ovst v
                LEFT JOIN vn_stat vv on vv.vn = v.vn
                LEFT JOIN an_stat a on a.an = v.an
                LEFT JOIN patient pp on pp.hn = v.hn
                LEFT JOIN pttype pt on pt.pttype = vv.pttype or pt.pttype=a.pttype
                LEFT JOIN pttype p on p.pttype = a.pttype
                left join claim.d_export_ucep x on x.vn = v.vn
                where x.active="N";
        ');
        D_cht::truncate();
        foreach ($data_cht as $va7) {
            D_cht::insert([
                'HN'                => $va7->HN,
                'AN'                => $va7->AN,
                'DATE'              => $va7->DATE,
                'TOTAL'             => $va7->TOTAL,
                'PAID'              => $va7->PAID,
                'PTTYPE'            => $va7->PTTYPE,
                'PERSON_ID'         => $va7->PERSON_ID,
                'SEQ'               => $va7->SEQ,
            ]);
        }

        $data_cha = DB::connection('mysql3')->select('
                SELECT "" d_cha_id,v.hn HN
                ,if(v1.an is null,"",v1.an) AN
                ,if(v1.an is null,DATE_FORMAT(v.vstdate,"%Y%m%d"),DATE_FORMAT(v1.dchdate,"%Y%m%d")) DATE
                ,if(v.paidst in("01","03"),dx.chrgitem_code2,dc.chrgitem_code1) CHRGITEM
                ,round(sum(v.sum_price),2) AMOUNT
                ,p.cid PERSON_ID
                ,ifnull(v.vn,v.an) SEQ
                from opitemrece v
                LEFT JOIN vn_stat vv on vv.vn = v.vn
                LEFT JOIN patient p on p.hn = v.hn
                LEFT JOIN ipt v1 on v1.an = v.an
                LEFT JOIN income i on v.income=i.income
                LEFT JOIN drg_chrgitem dc on i.drg_chrgitem_id=dc.drg_chrgitem_id
                LEFT JOIN drg_chrgitem dx on i.drg_chrgitem_id= dx.drg_chrgitem_id
                left join claim.d_export_ucep x on x.vn = v.vn
                where x.active="N"
                group by v.vn,CHRGITEM
                union all
                SELECT v.hn HN
                ,v1.an AN
                ,if(v1.an is null,DATE_FORMAT(v.vstdate,"%Y%m%d"),DATE_FORMAT(v1.dchdate,"%Y%m%d")) DATE
                ,if(v.paidst in("01","03"),dx.chrgitem_code2,dc.chrgitem_code1) CHRGITEM
                ,round(sum(v.sum_price),2) AMOUNT
                ,p.cid PERSON_ID
                ,ifnull(v.vn,v.an) SEQ
                from opitemrece v
                LEFT JOIN vn_stat vv on vv.vn = v.vn
                LEFT JOIN patient p on p.hn = v.hn
                LEFT JOIN ipt v1 on v1.an = v.an
                LEFT JOIN income i on v.income=i.income
                LEFT JOIN drg_chrgitem dc on i.drg_chrgitem_id=dc.drg_chrgitem_id
                LEFT JOIN drg_chrgitem dx on i.drg_chrgitem_id= dx.drg_chrgitem_id
                left join claim.d_export_ucep x on x.vn = v1.vn
                where x.active="N";
                group by v.an,CHRGITEM;
        ');
        D_cha::truncate();
        foreach ($data_cha as $va8) {
            D_cha::insert([
                'HN'                => $va8->HN,
                'AN'                => $va8->AN,
                'DATE'              => $va8->DATE,
                'CHRGITEM'          => $va8->CHRGITEM,
                'AMOUNT'            => $va8->AMOUNT,
                'PERSON_ID'         => $va8->PERSON_ID,
                'SEQ'               => $va8->SEQ,
            ]);
        }


        return redirect()->back();

    }

}






