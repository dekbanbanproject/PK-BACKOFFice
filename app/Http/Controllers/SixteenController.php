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
use App\Models\Dtemp_hosucep;
use App\Models\D_ucep;
use App\Models\D_ins;
use App\Models\D_pat;
use App\Models\D_opd;
use App\Models\D_orf;
use App\Models\D_odx;
use App\Models\D_cht;
use App\Models\D_cha;
use App\Models\D_oop;
use App\Models\Tempexport;
use App\Models\D_adp;
use App\Models\D_dru;
use App\Models\D_idx;
use App\Models\D_iop;
use App\Models\D_ipd;
use App\Models\D_aer;
use App\Models\D_irf;

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
            Dtemp_hosucep::truncate();
            D_ins::truncate();
            Tempexport::truncate();
            D_adp::truncate();
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
            // inner join claim.dtemp_hosucep zz on zz.an = o.an
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
                Tempexport::insert([
                    'hn'                       => $va2->hn,
                    'an'                       => $va2->an,
                    'vn'                       => $va2->vn,
                    'cid'                      => $va2->cid,
                    'sent_date'                => $date,
                ]);
            }
            // UCEP
            $data_opitemrece = DB::connection('mysql3')->select('
                SELECT "" dtemp_hosucep_id,o.an,o.hn,o.icode,o.rxdate,o.rxtime,a.vstdate,a.vsttime,DATEDIFF(o.rxdate,a.vstdate)<="1" as date_x,TIMEDIFF(o.rxtime,a.vsttime)<="24" time_x
                ,"" created_at,"" updated_at
                from opitemrece o
                LEFT JOIN ipt i on i.an = o.an
                LEFT JOIN ovst a on a.an = o.an
                left JOIN er_regist e on e.vn = i.vn
                LEFT JOIN ipt_pttype ii on ii.an = i.an
                LEFT JOIN pttype p on p.pttype = ii.pttype
                where i.dchdate BETWEEN "'.$startdate.'" AND "'.$enddate.'"
                and o.an is not null
                and p.hipdata_code ="ucs"
                and DATEDIFF(o.rxdate,a.vstdate)<="1"
                and TIMEDIFF(o.rxtime,a.vsttime)<="24"
                AND e.er_emergency_level_id IN("1","2")

                ORDER BY icode;
            ');
            foreach ($data_opitemrece as $va3) {
                Dtemp_hosucep::insert([
                    'an'                => $va3->an,
                    'hn'                => $va3->hn,
                    'icode'             => $va3->icode,
                    'rxdate'            => $va3->rxdate,
                    'vstdate'           => $va3->vstdate,
                    'rxtime'            => $va3->rxtime,
                    'vsttime'           => $va3->vsttime,
                    'date_x'            => $va3->date_x,
                    'time_x'            => $va3->time_x,
                ]);
            }
            //INS
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
            //ADP
            $data_adp = DB::connection('mysql3')->select('
                    SELECT HN,AN,DATEOPD,TYPE,CODE,sum(QTY) QTY,RATE,SEQ,"" "" a1,"" a2,"" a3,"" a4,"0" a5,"" a6,"0" a7 ,"" a8,"" TMLTCODE
                    ,"" STATUS1,"" BI,"" CLINIC,"" ITEMSRC,"" PROVIDER,"" GLAVIDA,"" GA_WEEK,"" DCIP,"0000-00-00" LMP,SP_ITEM
                    from (SELECT v.hn HN
                    ,if(v.an is null,"",v.an) AN
                    ,DATE_FORMAT(v.rxdate,"%Y%m%d") DATEOPD
                    ,n.nhso_adp_type_id TYPE
                    ,n.nhso_adp_code CODE
                    ,sum(v.QTY) QTY
                    ,round(v.unitprice,2) RATE
                    ,if(v.an is null,v.vn,"") SEQ
                    ,"" a1,"" a2,"" a3,"" a4,"0" a5,"" a6,"0" a7 ,"" a8
                    ,"" TMLTCODE,"" STATUS1,"" BI,"" CLINIC,"" ITEMSRC
                    ,"" PROVIDER,"" GLAVIDA,"" GA_WEEK,"" DCIP,"0000-00-00" LMP
                    ,(SELECT "01" from claim.dtemp_hosucep where an = v.an and icode = v.icode and rxdate = v.rxdate and rxtime = v.rxtime  limit 1) SP_ITEM
                    from opitemrece v
                    inner JOIN nondrugitems n on n.icode = v.icode and n.nhso_adp_code is not null
                    left join ipt i on i.an = v.an
                    AND i.an is not NULL
                    left join claim.tempexport x on x.vn = i.vn
                    where x.active="N"
                    AND n.icode <> "XXXXXX"
                    GROUP BY i.vn,n.nhso_adp_code,rate) a
                    GROUP BY an,CODE,rate
                    UNION
                    SELECT HN,AN,DATEOPD,TYPE,CODE,sum(QTY) QTY,RATE,SEQ,"" a1,"" a2,"" a3,"" a4,"0" a5,"" a6,"0" a7 ,"" a8,"" TMLTCODE
                    ,"" STATUS1,"" BI,"" CLINIC,"" ITEMSRC,"" PROVIDER,"" GLAVIDA,"" GA_WEEK,"" DCIP,"0000-00-00" LMP,"" SP_ITEM
                    from
                    (SELECT v.hn HN
                    ,if(v.an is null,"",v.an) AN
                    ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
                    ,n.nhso_adp_type_id TYPE
                    ,n.nhso_adp_code CODE
                    ,sum(v.QTY) QTY
                    ,round(v.unitprice,2) RATE
                    ,if(v.an is null,v.vn,"") SEQ,"" a1,"" a2,"" a3,"" a4,"0" a5,"" a6,"0" a7 ,"" a8
                    ,"" TMLTCODE,"" STATUS1,"" BI,"" CLINIC,"" ITEMSRC,"" PROVIDER,"" GLAVIDA,"" GA_WEEK,"" DCIP,"0000-00-00" LMP,"" SP_ITEM
                    from opitemrece v
                    inner JOIN nondrugitems n on n.icode = v.icode and n.nhso_adp_code is not null
                    left join ipt i on i.an = v.an
                    left join claim.tempexport x on x.vn = v.vn
                    where x.active="N"
                    AND n.icode <> "XXXXXX"
                    AND i.an is NULL
                    GROUP BY v.vn,n.nhso_adp_code,rate) b
                    GROUP BY seq,CODE,rate;
            ');
            foreach ($data_adp as $va4) {
                d_adp::insert([
                    'HN'                   => $va4->HN,
                    'AN'                   => $va4->AN,
                    'DATEOPD'              => $va4->DATEOPD,
                    'TYPE'                 => $va4->TYPE,
                    'CODE'                 => $va4->CODE,
                    'QTY'                  => $va4->QTY,
                    'RATE'                 => $va4->RATE,
                    'SEQ'                  => $va4->SEQ,
                    'CAGCODE'              => $va4->a1,
                    'DOSE'                 => $va4->a2,
                    'CA_TYPE'              => $va4->a3,
                    'SERIALNO'             => $va4->a4,
                    'TOTCOPAY'             => $va4->a5,
                    'USE_STATUS'           => $va4->a6,
                    'TOTAL'                => $va4->a7,
                    'QTYDAY'               => $va4->a8,
                    'TMLTCODE'             => $va4->TMLTCODE,
                    'STATUS1'              => $va4->STATUS1,
                    'BI'                   => $va4->BI,
                    'CLINIC'               => $va4->CLINIC,
                    'ITEMSRC'              => $va4->ITEMSRC,
                    'PROVIDER'             => $va4->PROVIDER,
                    'GLAVIDA'              => $va4->GLAVIDA,
                    'GA_WEEK'              => $va4->GA_WEEK,
                    'DCIP'                 => $va4->DCIP,
                    'LMP'                  => $va4->LMP,
                    'SP_ITEM'              => $va4->SP_ITEM,
                ]);
            }
            d_adp::where('CODE','=','XXXXXX')->delete();

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
        // DB::table('acc_stm_ucs_excel')->insert($data);
        $data['data_show']     = D_export_ucep::get();
        $data['data_ins']      = D_ins::get();
        $data['data_pat']      = D_pat::get();
        $data['data_opd']      = D_opd::get();
        return view('claim.six',$data,[
            'startdate'        => $startdate,
            'enddate'          => $enddate,
        ]);
    }
    public function six_pull_a(Request $request)
    {

            D_opd::truncate();
            D_oop::truncate();
            D_orf::truncate();

            //D_opd
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
                    INNER JOIN claim.d_export_ucep x on x.vn = v.vn
                    where x.active="N";
            ');
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
            //D_orf
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
                    INNER JOIN claim.d_export_ucep x on x.vn = v.vn
                    where x.active="N"
                    and (r1.vn is not null or r2.vn is not null);
            ');
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
            //D_oop
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
                    LEFT JOIN icd9cm1 i on i.code = o.icd10
                    INNER JOIN claim.d_export_ucep x on x.vn = v.vn
                    where x.active="N";
            ');
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

        return redirect()->back();
    }
    public function six_pull_b(Request $request)
    {

        D_odx::truncate();
        D_dru::truncate();
        D_idx::truncate();
        D_ipd::truncate();
        D_irf::truncate();

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
                LEFT JOIN icd101 i on i.code = o.icd10
                LEFT JOIN claim.d_export_ucep x on x.vn = v.vn
                where x.active="N";
        ');

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

        $data_dru = DB::connection('mysql3')->select('
                SELECT "" d_dru_id,vv.hcode HCODE
                ,v.hn HN
                ,v.an AN
                ,vv.spclty CLINIC
                ,vv.cid PERSON_ID
                ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATE_SERV
                ,d.icode DID
                ,concat(d.`name`," ",d.strength," ",d.units) DIDNAME
                ,sum(v.qty) AMOUNT
                ,round(v.unitprice,2) DRUGPRIC
                ,"0.00" DRUGCOST
                ,d.did DIDSTD
                ,d.units UNIT
                ,concat(d.packqty,"x",d.units) UNIT_PACK
                ,v.vn SEQ
                ,oo.presc_reason DRUGREMARK
                ,"" PA_NO
                ,"" TOTCOPAY
                ,if(v.item_type="H","2","1") USE_STATUS
                ,"" TOTAL,"" a1,""  a2,"" created_at,"" updated_at
                from opitemrece v
                LEFT JOIN drugitems d on d.icode = v.icode
                LEFT JOIN vn_stat vv on vv.vn = v.vn
                LEFT JOIN ovst_presc_ned oo on oo.vn = v.vn and oo.icode=v.icode
                LEFT JOIN claim.tempexport x on x.vn = v.vn
                where x.active="N"
                and d.did is not null
                GROUP BY v.vn,did

                UNION all

                SELECT "" d_dru_id,pt.hcode HCODE
                ,v.hn HN
                ,v.an AN
                ,v1.spclty CLINIC
                ,pt.cid PERSON_ID
                ,DATE_FORMAT((v.vstdate),"%Y%m%d") DATE_SERV
                ,d.icode DID
                ,concat(d.`name`," ",d.strength," ",d.units) DIDNAME
                ,sum(v.qty) AMOUNT
                ,round(v.unitprice,2) DRUGPRIC
                ,"0.00" DRUGCOST
                ,d.did DIDSTD
                ,d.units UNIT
                ,concat(d.packqty,"x",d.units) UNIT_PACK
                ,ifnull(v.vn,v.an) SEQ
                ,oo.presc_reason DRUGREMARK
                ,"" PA_NO
                ,"" TOTCOPAY
                ,if(v.item_type="H","2","1") USE_STATUS
                ,"" TOTAL,"" a1,""  a2,"" created_at,"" updated_at
                from opitemrece v
                LEFT JOIN drugitems d on d.icode = v.icode
                LEFT JOIN patient pt  on v.hn = pt.hn
                inner JOIN ipt v1 on v1.an = v.an
                LEFT JOIN ovst_presc_ned oo on oo.vn = v.vn and oo.icode=v.icode
                LEFT JOIN claim.tempexport x on x.vn = v1.vn
                where x.active="N"
                and d.did is not null AND v.qty<>"0"
                GROUP BY v.an,d.icode,USE_STATUS;
        ');
        foreach ($data_dru as $va9) {
            D_dru::insert([
                'HCODE'               => $va9->HCODE,
                'HN'                  => $va9->HN,
                'AN'                  => $va9->AN,
                'CLINIC'              => $va9->CLINIC,
                'PERSON_ID'           => $va9->PERSON_ID,
                'DATE_SERV'           => $va9->DATE_SERV,
                'DID'                 => $va9->DID,
                'DIDNAME'             => $va9->DIDNAME,
                'AMOUNT'              => $va9->AMOUNT,
                'DRUGPRIC'            => $va9->DRUGPRIC,
                'DRUGCOST'            => $va9->DRUGCOST,
                'DIDSTD'              => $va9->DIDSTD,
                'UNIT'                => $va9->UNIT,
                'UNIT_PACK'           => $va9->UNIT_PACK,
                'SEQ'                 => $va9->SEQ,
                'DRUGREMARK'          => $va9->DRUGREMARK,
                'PA_NO'               => $va9->PA_NO,
                'TOTCOPAY'            => $va9->TOTCOPAY,
                'USE_STATUS'          => $va9->USE_STATUS,
                'TOTAL'               => $va9->TOTAL,
                'SIGCODE'             => $va9->a1,
                'SIGTEXT'             => $va9->a2,
                'created_at'          => $va9->created_at,
                'updated_at'          => $va9->updated_at
            ]);
        }

        $data_idx = DB::connection('mysql3')->select('
             SELECT "" d_idx_id,v.an AN,o.icd10 DIAG
                    ,o.diagtype DXTYPE
                    ,if(d.licenseno="","-99999",d.licenseno) DRDX,"" created_at ,"" updated_at
                    FROM an_stat v
                    LEFT JOIN iptdiag o on o.an = v.an
                    LEFT JOIN doctor d on d.`code` = o.doctor
                    LEFT JOIN ipt ip on ip.an = v.an
                    INNER JOIN icd101 i on i.code = o.icd10
                    LEFT JOIN claim.d_export_ucep x on x.vn = v.vn
                    WHERE x.active="N";
        ');
        foreach ($data_idx as $va6) {
            D_idx::insert([
                'AN'                => $va6->AN,
                'DIAG'              => $va6->DIAG,
                'DXTYPE'            => $va6->DXTYPE,
                'DRDX'              => $va6->DRDX
            ]);
        }

        $data_ipd = DB::connection('mysql3')->select('
            SELECT "" d_ipd_id,v.hn HN,v.an AN
                ,DATE_FORMAT(o.regdate,"%Y%m%d") DATEADM
                ,Time_format(o.regtime,"%H%i") TIMEADM
                ,DATE_FORMAT(o.dchdate,"%Y%m%d") DATEDSC
                ,Time_format(o.dchtime,"%H%i")  TIMEDSC
                ,right(o.dchstts,1) DISCHS
                ,right(o.dchtype,1) DISCHT
                ,o.ward WARDDSC,o.spclty DEPT
                ,format(o.bw/1000,3) ADM_W
                ,"1" UUC ,"I" SVCTYPE,"" created_at,"" updated_at
                FROM an_stat v
                LEFT JOIN ipt o on o.an = v.an
                LEFT JOIN pttype p on p.pttype = v.pttype
                LEFT JOIN patient pt on pt.hn = v.hn
                LEFT JOIN claim.d_export_ucep x on x.vn = v.vn
                WHERE x.active="N";
        ');
        foreach ($data_ipd as $va10) {
            D_ipd::insert([
                'HN'                 => $va10->HN,
                'AN'                 => $va10->AN,
                'DATEADM'            => $va10->DATEADM,
                'TIMEADM'            => $va10->TIMEADM,
                'DATEDSC'            => $va10->DATEDSC,
                'TIMEDSC'            => $va10->TIMEDSC,
                'DISCHS'             => $va10->DISCHS,
                'DISCHT'             => $va10->DISCHT,
                'DEPT'               => $va10->DEPT,
                'ADM_W'              => $va10->ADM_W,
                'UUC'                => $va10->UUC,
                'SVCTYPE'            => $va10->SVCTYPE
            ]);
        }

        $data_irf = DB::connection('mysql3')->select('
                SELECT ""d_irf_id,v.an AN
                ,ifnull(o.refer_hospcode,oo.refer_hospcode) REFER
                ,"0100" REFERTYPE,"" created_at,"" updated_at
                FROM an_stat v
                LEFT JOIN referout o on o.vn =v.an
                LEFT JOIN referin oo on oo.vn =v.an
                LEFT JOIN ipt ip on ip.an = v.an
                LEFT JOIN claim.d_export_ucep x on x.vn = v.vn
                WHERE x.active="N"
                and (v.an in(select vn from referin where vn = oo.vn) or v.an in(select vn from referout where vn = o.vn));
        ');
        foreach ($data_irf as $va11) {
            D_irf::insert([
                'AN'                 => $va11->AN,
                'REFER'              => $va11->REFER,
                'REFERTYPE'          => $va11->REFERTYPE
            ]);
        }



        return redirect()->back();
    }
    public function six_pull_c(Request $request)
    {
        D_aer::truncate();
        D_iop::truncate();
        D_pat::truncate();
        D_cht::truncate();

        $data_aer = DB::connection('mysql3')->select('
                SELECT ""d_aer_id,v.hn HN,i.an AN
                ,v.vstdate DATEOPD,vv.claim_code AUTHAE
                ,"" AEDATE,"" AETIME,"" AETYPE,"" REFER_NO,"" REFMAINI
                ,"" IREFTYPE,"" REFMAINO,"" OREFTYPE,"" UCAE,"" EMTYPE,v.vn SEQ
                ,"" AESTATUS,"" DALERT,"" TALERT,"" created_at,"" updated_at
                from vn_stat v
                LEFT JOIN ipt i on i.vn = v.vn
                LEFT JOIN visit_pttype vv on vv.vn = v.vn
                LEFT OUTER JOIN pttype pt on pt.pttype =v.pttype
                LEFT JOIN claim.d_export_ucep x on x.vn = v.vn
                WHERE x.active="N"
                and i.an is null
                GROUP BY v.vn
                union all
                SELECT ""d_aer_id,v.hn HN
                ,v.an AN,v.dchdate DATEOPD,vv.claim_code AUTHAE
                ,"" AEDATE,"" AETIME,"" AETYPE,"" REFER_NO,"" REFMAINI
                ,"" IREFTYPE,"" REFMAINO,"" OREFTYPE,"" UCAE,"" EMTYPE
                ,"" SEQ,"" AESTATUS,"" DALERT,"" TALERT,"" created_at,"" updated_at
                from an_stat v
                LEFT JOIN ipt_pttype vv on vv.an = v.an
                LEFT OUTER JOIN pttype pt on pt.pttype =v.pttype
                LEFT JOIN claim.d_export_ucep x on x.vn = v.vn
                WHERE x.active="N"
                group by v.an;
        ');

        foreach ($data_aer as $va12) {
            D_aer::insert([
                'HN'                => $va12->HN,
                'AN'                => $va12->AN,
                'DATEOPD'           => $va12->DATEOPD,
                'AUTHAE'            => $va12->AUTHAE,
                'AEDATE'            => $va12->AEDATE,
                'AETIME'            => $va12->AETIME,
                'AETYPE'            => $va12->AETYPE,
                'REFER_NO'          => $va12->REFER_NO,
                'REFMAINI'          => $va12->REFMAINI,
                'IREFTYPE'          => $va12->IREFTYPE,
                'REFMAINO'          => $va12->REFMAINO,
                'OREFTYPE'          => $va12->OREFTYPE,
                'UCAE'              => $va12->UCAE,
                'SEQ'               => $va12->SEQ,
                'AESTATUS'          => $va12->AESTATUS,
                'DALERT'            => $va12->DALERT,
                'TALERT'            => $va12->TALERT,
            ]);
        }
         //D_iop
         $data_iop = DB::connection('mysql3')->select('
                SELECT "" d_iop_id,v.an AN
                ,o.icd9 OPER
                ,o.oper_type as OPTYPE
                ,if(d.licenseno="","-99999",d.licenseno) DROPID
                ,DATE_FORMAT(o.opdate,"%Y%m%d") DATEIN
                ,Time_format(o.optime,"%H%i") TIMEIN
                ,DATE_FORMAT(o.enddate,"%Y%m%d") DATEOUT
                ,Time_format(o.endtime,"%H%i") TIMEOUT,"" created_at,"" updated_at
                FROM an_stat v
                LEFT JOIN iptoprt o on o.an = v.an
                LEFT JOIN doctor d on d.`code` = o.doctor
                INNER JOIN icd9cm1 i on i.code = o.icd9
                LEFT JOIN ipt ip on ip.an = v.an
                LEFT JOIN claim.d_export_ucep x on x.vn = v.vn
                WHERE x.active="N";
        ');
        foreach ($data_iop as $va7) {
            D_iop::insert([
                'AN'                => $va7->AN,
                'OPER'              => $va7->OPER,
                'OPTYPE'            => $va7->OPTYPE,
                'DROPID'            => $va7->DROPID,
                'DATEIN'            => $va7->DATEIN,
                'TIMEIN'            => $va7->TIMEIN,
                'DATEOUT'           => $va7->DATEOUT,
                'TIMEOUT'           => $va7->TIMEOUT
            ]);
        }

        // D_pat
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
                INNER JOIN claim.d_export_ucep x on x.vn = v.vn
                where x.active="N";
        ');
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
                LEFT JOIN claim.d_export_ucep x on x.vn = v.vn
                where x.active="N";
        ');
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



        return redirect()->back();
    }
    public function six_pull_d(Request $request)
    {

        D_cha::truncate();

        $data_cha = DB::connection('mysql3')->select('
                SELECT "" d_cha_id,v.hn HN
                ,if(v1.an is null,"",v1.an) AN
                ,if(v1.an is null,DATE_FORMAT(v.vstdate,"%Y%m%d"),DATE_FORMAT(v1.dchdate,"%Y%m%d")) DATE
                ,if(v.paidst in("01","03"),dx.chrgitem_code2,dc.chrgitem_code1) CHRGITEM
                ,round(sum(v.sum_price),2) AMOUNT
                ,p.cid PERSON_ID
                ,ifnull(v.vn,v.an) SEQ,"" created_at,"" updated_at
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
                SELECT "" d_cha_id,v.hn HN
                ,v1.an AN
                ,if(v1.an is null,DATE_FORMAT(v.vstdate,"%Y%m%d"),DATE_FORMAT(v1.dchdate,"%Y%m%d")) DATE
                ,if(v.paidst in("01","03"),dx.chrgitem_code2,dc.chrgitem_code1) CHRGITEM
                ,round(sum(v.sum_price),2) AMOUNT
                ,p.cid PERSON_ID
                ,ifnull(v.vn,v.an) SEQ,"" created_at,"" updated_at
                from opitemrece v
                LEFT JOIN vn_stat vv on vv.vn = v.vn
                LEFT JOIN patient p on p.hn = v.hn
                LEFT JOIN ipt v1 on v1.an = v.an
                LEFT JOIN income i on v.income=i.income
                LEFT JOIN drg_chrgitem dc on i.drg_chrgitem_id=dc.drg_chrgitem_id
                LEFT JOIN drg_chrgitem dx on i.drg_chrgitem_id= dx.drg_chrgitem_id
                left join claim.d_export_ucep x on x.vn = v1.vn
                where x.active="N"
                group by v.an,CHRGITEM;
        ');
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

    public function six_send(Request $request)
    {
        $sss_date_now = date("Y-m-d");
        $sss_time_now = date("H:i:s");
        $sesid_status = 'new'; #ส่งค่าสำหรับเงื่อนไขการบันทึกsession

        #ตัดขีด, ตัด : ออก
        $pattern_date = '/-/i';
        $sss_date_now_preg = preg_replace($pattern_date, '', $sss_date_now);
        $pattern_time = '/:/i';
        $sss_time_now_preg = preg_replace($pattern_time, '', $sss_time_now);
        #ตัดขีด, ตัด : ออก


         //Move Uploaded File to public folder
         $url = "http://192.168.0.217/pkbackoffice/public";
         $part_ = pathinfo($url);

         #delete file in folder ทั้งหมด
        $file = new Filesystem;
        $file->cleanDirectory('Export_Claim');
        $folder='10978_UCEP_'.$sss_date_now_preg.'-'.$sss_time_now_preg;

         $desPath = 'Export_Claim';
         $testfolder = $desPath.'/'.$folder;



        // mkdir ('C:/export/'.$folder, 0777, true);

        header("Content-type: text/txt");
        header("Cache-Control: no-store, no-cache");
        header('Content-Disposition: attachment; filename="content.txt"');

        $file_name = "$testfolder/UCEP_24".$sss_time_now_preg.".txt";
        // SELECT COUNT(*) from claim_ssop
        // $ssop_count = DB::connection('mysql7')->select('
        //     SELECT COUNT(*) as Invno
        //     FROM ssop_billtran
        // ');
        // foreach ($ssop_count as $key => $valuecount) {
        //     $count = $valuecount->Invno;
        // }

        $file_pat = "C:/export/".$folder."/ins".".txt";
        $objFopen_opd = fopen($file_pat, 'w');

        // $file_pat2 = "C:/export/".$folder."/BillDisp".$sss_date_now_preg.".txt";
        // $objFopen_opd2 = fopen($file_pat2, 'w');

        // $file_pat3 = "C:/export/".$folder."/OPServices".$sss_date_now_preg.".txt";
        // $objFopen_opd3 = fopen($file_pat3, 'w');


        // $opd_head = 'gg';
        // fwrite($objFopen_opd, $opd_head);

        // $opd_head = "\n".'<ClaimRec System="OP" PayPlan="SS" Version="0.93" Prgs="HS">';
        // fwrite($objFopen_opd, $opd_head);

        // $opd_head = "\n".'<Header>';
        // fwrite($objFopen_opd, $opd_head);

        // $opd_head = "\n".'<HCODE>10978</HCODE>';
        // fwrite($objFopen_opd, $opd_head);

        // $opd_head = "\n".'<HNAME>โรงพยาบาลภูเขียวเฉลิมพระเกีรติ</HNAME>';
        // $opd_head_ansi = iconv('UTF-8', 'TIS-620', $opd_head);
        // fwrite($objFopen_opd, $opd_head_ansi);

        // $opd_head = "\n".'<DATETIME>'.$sss_date_now.'T'.$sss_time_now.'</DATETIME>';
        // fwrite($objFopen_opd, $opd_head);

        // $opd_head = "\n".'<SESSNO>'.$ssop_session_no.'</SESSNO>';
        // fwrite($objFopen_opd, $opd_head);

        // $opd_head = "\n".'<RECCOUNT>'.$count.'</RECCOUNT>';
        // fwrite($objFopen_opd, $opd_head);

        // $opd_head = "\n".'</Header>';
        // fwrite($objFopen_opd, $opd_head);

        // $opd_head = "\n".'<BILLTRAN>';
        // fwrite($objFopen_opd, $opd_head);

        $ins = DB::connection('mysql7')->select('
            SELECT * from d_ins
        ');

        foreach ($ins as $key => $value1) {
            $a1 = $value1->HN;
            $a2 = $value1->INSCL;
            $a3 = $value1->SUBTYPE;
            $a4 = $value1->CID;
            $a5 = $value1->DATEIN;
            $a6 = $value1->DATEEXP;
            $a7 = $value1->HOSPMAIN;
            $a8 = $value1->HOSPSUB;
            $a9 = $value1->GOVCODE;
            $a10 = $value1->GOVNAME;
            $a11 = $value1->PERMITNO;
            $a12 = $value1->DOCNO;
            $a13 = $value1->OWNRPID;
            $a14= $value1->OWNRNAME;
            $a15 = $value1->AN;
            $a16= $value1->SEQ;
            $a17= $value1->SUBINSCL;
            $a18 = $value1->RELINSCL;
            $a19 = $value1->HTYPE;
            $strText1="\n".$a1."|".$a2."|".$a3."|".$a4."|".$a5."|".$a6."|".$a7."|".$a8."|".$a9."|".$a10."|".$a11."|".$a12."|".$a13."|".$a14."|".$a15."|".$a16."|".$a17."|".$a18."|".$a19;
            $ansitxt_pat1 = iconv('UTF-8', 'TIS-620', $strText1);
            fwrite($objFopen_opd, $ansitxt_pat1);
        }

        // $opd_head = "\n".'</BILLTRAN>';
        // fwrite($objFopen_opd, $opd_head);

        // $opd_head = "\n".'<BillItems>';
        // fwrite($objFopen_opd, $opd_head);

        // $ssop_items = DB::connection('mysql7')->select('
        //     SELECT * FROM ssop_billitems
        //     ');
        // foreach ($ssop_items as $key => $value) {
        //     $s1 = $value->Invno;
        //     $s2 = $value->SvDate;
        //     $s3 = $value->BillMuad;
        //     $s4 = $value->LCCode;
        //     $s5 = $value->STDCode;
        //     $s6 = $value->Desc;
        //     $s7 = $value->QTY;
        //     $s8 = $value->UnitPrice;
        //     $s9 = $value->ChargeAmt;
        //     $s10 = $value->ClaimUP;
        //     $s11 = $value->ClaimAmount;
        //     $s12 = $value->SvRefID;
        //     $s13 = $value->ClaimCat;

        //     $strText="\n".$s1."|".$s2."|".$s3."|".$s4."|".$s5."|".$s6."|".$s7."|".$s8."|".$s9."|".$s10."|".$s11."|".$s12."|".$s13;
        //     $ansitxt_pat = iconv('UTF-8', 'TIS-620', $strText);
        //     fwrite($objFopen_opd, $ansitxt_pat);
        // }
        // $opd_head = "\n".'</BillItems>';
        // fwrite($objFopen_opd, $opd_head);
        // $opd_head = "\n".'</ClaimRec>';
        // fwrite($objFopen_opd, $opd_head);
        // $opd_head = "\n";
        // fwrite($objFopen_opd, $opd_head);
        // if($objFopen_opd){
        //     echo "File BillTran writed."."<BR>";
        // }else{
        //     echo "File BillTran can not write";
        // }
        fclose($objFopen_opd);



        // fwrite($objFopen_opd, $opd_head);
        // $opd_head = "\n";
        // fwrite($objFopen_opd, $opd_head);
        // if($objFopen_opd){
        //     echo "File BillDisp MD5 writed."."<BR>";
        // }else{
        //     echo "File BillDisp MD5 can not write";
        // }
        // fclose($objFopen_opd);





            return redirect()->route('data.six');

    }

}






