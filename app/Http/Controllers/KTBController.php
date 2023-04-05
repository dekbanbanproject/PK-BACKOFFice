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
use Stevebauman\Location\Facades\Location;
use Http;
use SoapClient; 
use SplFileObject;
use Arr;
use Storage;
use GuzzleHttp\Client;
use App\Models\Tempexport;
use App\Models\D_aer;
use App\Models\D_adp;
use App\Models\D_cha;  
use App\Models\D_cht;
use App\Models\D_oop;
use App\Models\D_odx;
use App\Models\D_orf;
use App\Models\D_pat;
use App\Models\D_ins;
use App\Models\D_dru;
use App\Models\D_opd;
use App\Models\D_ktb_b17;
use App\Models\Stm;
use App\Models\D_export;

class KTBController extends Controller
{
    public function ktb_getcard(Request $request)
    { 
        $data['users'] = User::get();
        $budget = DB::table('budget_year')->where('active','=','True')->first();
        $datestart = $budget->date_begin;
        $dateend = $budget->date_end;
         
        $data_ogclgo = DB::connection('mysql3')->select('
            select month(i.dchdate) as months,count(distinct i.an) as cAN,count(distinct po.an) as poan,count(distinct pi.an) as pian

            from hos.ipt i 
            left outer join hos.doctor d on d.code = i.admdoctor
                left outer join hos.an_stat aa on aa.an = i.an
                left outer join hos.pttype p on p.pttype = aa.pttype
                left outer join hos.ipt_pttype pi on pi.an = i.an and pi.claim_code is null
                left outer join hos.ipt_pttype po on po.an = i.an and po.claim_code is not null                
                where aa.dchdate between "'.$datestart.'" and "'.$dateend.'"
                and aa.pttype in("o1","o2","o3","o4","o5","20","l1","l2","l3","l4","l5","l6","l7","21")
                group by month(i.dchdate)
                order by year(i.dchdate),month(i.dchdate)
                ');

                $url = "http://localhost:3000/moi/getCardData";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "$url",
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
         
            $response = curl_exec($curl);
            curl_close($curl);
            $content = $response;
            $result = json_decode($content, true);

            // dd($result);

            
            @$responseCode = $result['responseCode'];
            // dd($responseCode);
            if (@$responseCode < 0) {
                $smartcard = 'NO_CONNECT';
            } else { 
                $smartcard = 'CONNECT';
            }
            // dd($smartcard);
                @$readerName = $result['readerName'];
                @$responseDesc = $result['responseDesc'];
                @$pid = $result['pid'];
                @$cid = $result['cid'];
                @$chipId = $result['chipId'];
                @$fullNameTH = $result['fullNameTH'];
                @$fullNameEN = $result['fullNameEN'];
                @$birthTH = $result['birthTH'];
                @$birthEN = $result['birthEN'];
                @$sex = $result['sex'];
                @$cardId = $result['cardId'];
                @$sourceData = $result['sourceData'];
                @$issueCode = $result['issueCode'];
                @$dateIssueTH = $result['dateIssueTH'];
                @$dateIssueEN = $result['dateIssueEN'];
                @$dateExpTH = $result['dateExpTH'];
                @$dateExpEN = $result['dateExpEN'];
                @$address = $result['address'];
                @$image = $result['image'];
                @$imageNo = $result['imageNo'];
                @$cardVersion = $result['cardVersion'];
                @$customerPid = $result['customerPid'];
                @$customerCid = $result['customerCid'];
                @$ktbKeyY = $result['ktbKeyY'];
                @$customerKeyY = $result['customerKeyY'];

                $pid         = @$pid;
                $cid         = @$cid;
                $image_      = @$image;
                $chipId      = @$chipId;
                $fullNameTH  = @$fullNameTH;
                $fullNameEN  = @$fullNameEN;
                $birthTH     = @$birthTH;
                $birthEN     = @$birthEN;
                $address     = @$address;
                $dateIssueTH = @$dateIssueTH;
                $dateExpTH   = @$dateExpTH;
                 
                // dd( @$fullNameTH); 
        return view('ktb.ktb_getcard', $data,[ 
            'data_ogclgo'   =>  $data_ogclgo,
            'smartcard'     =>  $smartcard,
            'image_'        => $image_,
            'pid'           => $pid,
            'cid'           => $cid,
            'chipId'        => $chipId,
            'fullNameTH'    => $fullNameTH,
            'fullNameEN'    => $fullNameEN,
            'birthTH'       => $birthTH,
            'birthEN'       => $birthEN,
            'address'       => $address,
            'dateIssueTH'   => $dateIssueTH,
            'dateExpTH'     => $dateExpTH,
        ]);
    }
    public function ktb(Request $request)
    { 
        $datestart = $request->startdate;
        $dateend = $request->enddate;

        return view('claim.ktb',[
            'start'            => $datestart,
            'end'              => $dateend,
        ]);
    }
    
    public function anc_Pregnancy_test(Request $request)
    { 
        $datestart = $request->startdate;
        $dateend = $request->enddate;
        $ins_ = DB::connection('mysql7')->select('   
            SELECT * FROM d_ins   
        ');
        $pat_ = DB::connection('mysql7')->select('   
            SELECT * FROM d_pat   
        ');
        $opd_ = DB::connection('mysql7')->select('   
            SELECT * FROM d_opd   
        ');
        $odx_ = DB::connection('mysql7')->select('   
            SELECT * FROM d_odx   
        ');
        $adp_ = DB::connection('mysql7')->select('   
            SELECT * FROM d_adp   
        ');
        $dru_ = DB::connection('mysql7')->select('   
            SELECT * FROM d_dru   
        ');

        return view('claim.anc_Pregnancy_test',[
            'start'            => $datestart,
            'end'              => $dateend,
            'ins_'              => $ins_,
            'pat_'              => $pat_,
            'opd_'              => $opd_,
            'odx_'              => $odx_,
            'adp_'              => $adp_,
            'dru_'              => $dru_
        ]);
    }
    public function anc_Pregnancy_testsearch(Request $request)
    { 
        $datestart = $request->startdate;
        $dateend = $request->enddate;
        $data_opd = DB::connection('mysql3')->select('   
                SELECT p.hn,v.vn,p.cid,i.an from hos.vn_stat v 
                left join opitemrece o on o.vn = v.vn  
                left join ipt i on i.vn=v.vn 
                left outer join patient p on p.hn = v.hn
                WHERE v.vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'"  
                and o.icode = "3000149"
                and o.pttype NOT IN ("98","99","50","49","O1","O2","O3","O4","O5","L1","L2","L3","L4","L5","L6") 
                and o.an is null
                GROUP BY v.hn,v.vstdate;
        ');
        Tempexport::truncate();
        foreach ($data_opd as $key => $value) {           
            $add= new Tempexport();
            $add->vn = $value->vn ;
            $add->hn = $value->hn; 
            $add->an = $value->an; 
            $add->cid = $value->cid; 
            $add->ACTIVE = 'N';
            $add->save();
        }

        $data_ktb_b17 = DB::connection('mysql3')->select('   
                SELECT p.hn,v.vn,p.cid,i.an,v.uc_money,v.uc_money AS ClaimAmt
                ,v.vstdate,CONCAT(p.pname,p.fname," ",p.lname) AS FULLNAME
                 from hos.vn_stat v 
                left join opitemrece o on o.vn = v.vn  
                left join ipt i on i.vn=v.vn 
                left outer join patient p on p.hn = v.hn 
                WHERE v.vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'"   
                and o.icode = "3000149"
                and o.pttype NOT IN ("98","99","50","49","O1","O2","O3","O4","O5","L1","L2","L3","L4","L5","L6") 
                and o.an is null
                GROUP BY v.hn;
        '); 
       
        // D_ktb_b17::truncate();
        foreach ($data_ktb_b17 as $key => $item2) { 
            $checkvn = D_ktb_b17::where('vn','=',$item2->vn)->count();
            $datenow = date('Y-m-d H:m:s');
            if ($checkvn > 0) { 
            } else { 
                D_ktb_b17::insert([                        
                    'vn'         => $item2->vn,
                    'hn'         => $item2->hn,
                    'an'         => $item2->an,
                    'cid'        => $item2->cid,
                    'vstdate'    => $item2->vstdate,
                    'created_at' => $datenow, 
                ]);
            }          

            $check_vn = Stm::where('VN','=',$item2->vn)->count(); 
            
            if ($check_vn > 0) { 
            } else {
                Stm::insert([                        
                        'AN'                => $item2->an, 
                        'VN'                => $item2->vn,
                        'HN'                => $item2->hn,
                        'PID'               => $item2->cid,
                        'VSTDATE'           => $item2->vstdate,
                        'FULLNAME'          => $item2->FULLNAME,  
                        'MAININSCL'         => "",
                        'created_at'        => $datenow, 
                        'ClaimAmt'          =>$item2->ClaimAmt
                    ]);
            }
            
        }

        D_ins::truncate();
        $ins_ = DB::connection('mysql3')->select('   
                SELECT v.hn HN
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
                ,v.vstdate

                from vn_stat v
                LEFT JOIN opitemrece o on o.vn = v.vn
                LEFT JOIN pttype p on p.pttype = v.pttype
                LEFT JOIN ipt i on i.vn = v.vn 
                LEFT JOIN pttype pp on pp.pttype = i.pttype
                left join ipt_pttype ap on ap.an = i.an
                left join visit_pttype vp on vp.vn = v.vn
                LEFT JOIN rcpt_debt r on r.vn = v.vn
                left join patient px on px.hn = v.hn
                left join claim.tempexport x on x.vn = v.vn
                where x.ACTIVE="N" 
                GROUP BY v.hn,v.vstdate; 
        ');
        
        foreach ($ins_ as $key => $ins) {
            D_ins::insert([                        
                'HN'                => $ins->HN, 
                'INSCL'             => $ins->INSCL,
                'SUBTYPE'            => $ins->SUBTYPE,
                'CID'               => $ins->CID,
                'DATEIN'            => $ins->DATEIN,
                'DATEEXP'           => $ins->DATEEXP,  
                'HOSPMAIN'          => $ins->HOSPMAIN,
                'HOSPSUB'           => $ins->HOSPSUB, 
                'GOVCODE'           =>$ins->GOVCODE,
                'GOVNAME'           =>$ins->GOVNAME,
                'PERMITNO'          =>$ins->PERMITNO,
                'DOCNO'             =>$ins->DOCNO,
                'OWNRPID'           =>$ins->OWNRPID,
                'OWNRNAME'          =>$ins->OWNRNAME,
                'AN'                =>$ins->AN,
                'SEQ'               =>$ins->SEQ,
                'SUBINSCL'          =>$ins->SUBINSCL,
                'RELINSCL'          =>$ins->RELINSCL,
                'HTYPE'             =>$ins->HTYPE
            ]);
        }

        D_pat::truncate();
        $pat_ = DB::connection('mysql3')->select('   
                SELECT v.hcode HCODE
                ,v.hn HN
                ,pt.chwpart CHANGWAT
                ,pt.amppart AMPHUR
                ,DATE_FORMAT(pt.birthday, "%Y%m%d") DOB
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
                ,v.vstdate

                from vn_stat v
                LEFT JOIN opitemrece o on o.vn = v.vn
                LEFT JOIN pttype p on p.pttype = v.pttype
                LEFT JOIN ipt i on i.vn = v.vn  
                left join patient pt on pt.hn = v.hn
                left join claim.tempexport x on x.vn = v.vn
                where x.ACTIVE="N" 
                GROUP BY v.hn,v.vstdate; 
        ');
        
        foreach ($pat_ as $key => $pat) {
            D_pat::insert([                        
                'HN'                => $pat->HN, 
                'HCODE'             => $pat->HCODE,
                'CHANGWAT'          => $pat->CHANGWAT,
                'AMPHUR'            => $pat->AMPHUR,
                'DOB'               => $pat->DOB,
                'SEX'               => $pat->SEX,  
                'MARRIAGE'          => $pat->MARRIAGE,
                'OCCUPA'            => $pat->OCCUPA, 
                'NATION'            => $pat->NATION,
                'PERSON_ID'         => $pat->PERSON_ID,
                'NAMEPAT'           => $pat->NAMEPAT,
                'TITLE'             => $pat->TITLE,
                'FNAME'             => $pat->FNAME,
                'LNAME'             => $pat->LNAME,
                'IDTYPE'            => $pat->IDTYPE  
            ]);
        }

        D_opd::truncate();
        $opd_ = DB::connection('mysql3')->select('   
                SELECT v.hn as HN
                ,v.spclty as CLINIC
                ,DATE_FORMAT(v.vstdate, "%Y%m%d") as DATEOPD
                ,concat(substr(o.vsttime,1,2),substr(o.vsttime,4,2)) as TIMEOPD
                ,v.vn as SEQ 
                ,"1" UUC
                ,v.vstdate 

                from vn_stat v
                LEFT JOIN ovst o on o.vn = v.vn
                LEFT JOIN pttype p on p.pttype = v.pttype
                LEFT JOIN ipt i on i.vn = v.vn 
                LEFT JOIN patient pt on pt.hn = v.hn
                left join claim.tempexport x on x.vn = v.vn
                where x.ACTIVE="N"                
                GROUP BY SEQ; 
        ');
        
        foreach ($opd_ as $key => $opd) {
            D_opd::insert([                        
                'HN'                => $opd->HN, 
                'CLINIC'            => $opd->CLINIC,
                'DATEOPD'           => $opd->DATEOPD,
                'TIMEOPD'           => $opd->TIMEOPD,
                'SEQ'               => $opd->SEQ,
                'UUC'               => $opd->UUC 
            ]);
        }

        D_odx::truncate();
        $odx_ = DB::connection('mysql3')->select('   
                SELECT v.hn HN
                ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEDX
                ,v.spclty CLINIC
                ,o.icd10 DIAG
                ,o.diagtype DXTYPE
                ,if(d.licenseno="","-99999",d.licenseno) DRDX
                ,v.cid PERSON_ID 
                ,v.vn SEQ
                ,v.vstdate 
                ,v.pttype

                from vn_stat v
                LEFT JOIN ovstdiag o on o.vn = v.vn
                LEFT JOIN doctor d on d.`code` = o.doctor
                inner JOIN icd101 i on i.code = o.icd10 
                left join claim.tempexport x on x.vn = v.vn
                where x.ACTIVE="N"  
                GROUP BY SEQ; 
        ');
         
        foreach ($odx_ as $key => $odx) {
            D_odx::insert([                        
                'HN'                => $odx->HN, 
                'CLINIC'            => $odx->CLINIC,
                'DATEDX'            => $odx->DATEDX,
                'DIAG'              => $odx->DIAG,
                'DXTYPE'            => $odx->DXTYPE,
                'DRDX'              => $odx->DRDX,
                'PERSON_ID'         => $odx->PERSON_ID,
                'SEQ'               => $odx->SEQ 
            ]);
        }

        D_adp::truncate();
        $adp_ = DB::connection('mysql3')->select('   
            SELECT HN,AN,DATEOPD,TYPE,CODE,sum(QTY) QTY
            ,RATE,SEQ
            ,"" a1,"" a2,"" a3,"" a4,"0" a5,"" a6,"0" a7 ,"" a8
            ,"" TMLTCODE
            ,"" STATUS1
            ,"" BI
            ,"" CLINIC
            ,"" ITEMSRC
            ,"" PROVIDER
            ,"" GLAVIDA
            ,"" GA_WEEK
            ,"" DCIP
            ,"0000-00-00" LMP
            from (SELECT v.hn HN
            ,if(v.an is null,"",v.an) AN
            ,DATE_FORMAT(v.rxdate,"%Y%m%d") DATEOPD
            ,n.nhso_adp_type_id TYPE
            ,n.nhso_adp_code CODE 
            ,sum(v.QTY) QTY
            ,round(v.unitprice,2) RATE
            ,if(v.an is null,v.vn,"") SEQ
            ,"" a1,"" a2,"" a3,"" a4,"" a5,"" a6,"" a7 ,"" a8
            ,"" TMLTCODE
            ,"" STATUS1
            ,"" BI
            ,"" CLINIC
            ,"" ITEMSRC
            ,"" PROVIDER
            ,"" GLAVIDA
            ,"" GA_WEEK
            ,"" DCIP
            ,"0000-00-00" LMP
            from opitemrece v
            inner JOIN drugitems n on n.icode = v.icode and n.nhso_adp_code is not null
            left join ipt i on i.an = v.an
            AND i.an is not NULL
            left join claim.tempexport x on x.vn = i.vn
            where x.ACTIVE="N"
            GROUP BY i.vn,n.nhso_adp_code,rate) a 
            GROUP BY an,CODE,rate
            UNION
            SELECT HN,AN,DATEOPD,TYPE,CODE,sum(QTY) QTY,RATE,SEQ,"" "" a1,"" a2,"" a3,"" a4,"0" a5,"" a6,"0" a7 ,"" a8
            ,""TMLTCODE
            ,"" STATUS1
            ,"" BI
            ,"" CLINIC
            ,"" ITEMSRC
            ,"" PROVIDER
            ,"" GLAVIDA
            ,"" GA_WEEK
            ,"" DCIP
            ,"0000-00-00" LMP
            from
            (SELECT v.hn HN
            ,if(v.an is null,"",v.an) AN
            ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATEOPD
            ,n.nhso_adp_type_id TYPE
            ,n.nhso_adp_code CODE 
            ,sum(v.QTY) QTY
            ,round(v.unitprice,2) RATE
            ,if(v.an is null,v.vn,"") SEQ
            ,"" a1,"" a2,"" a3,"" a4,"0" a5,"" a6,"0" a7 ,"" a8
            ,"" TMLTCODE
            ,"" STATUS1
            ,"" BI
            ,"" CLINIC
            ,"" ITEMSRC
            ,"" PROVIDER
            ,"" GLAVIDA
            ,"" GA_WEEK
            ,"" DCIP
            ,"0000-00-00" LMP
            from opitemrece v
            inner JOIN drugitems n on n.icode = v.icode and n.nhso_adp_code is not null
            left join ipt i on i.an = v.an
            left join claim.tempexport x on x.vn = v.vn
            where x.ACTIVE="N"
            AND i.an is NULL
            GROUP BY v.vn,n.nhso_adp_code,rate) b 
            GROUP BY seq,CODE,rate ;              
        ');
         
        foreach ($adp_ as $key => $adp) {
            D_adp::insert([                        
                'HN'                => $adp->HN, 
                'AN'                => $adp->AN,
                'DATEOPD'           => $adp->DATEOPD,
                'TYPE'              => $adp->TYPE,
                'CODE'              => $adp->CODE,
                'QTY'               => $adp->QTY,
                'RATE'              => $adp->RATE,
                'SEQ'               => $adp->SEQ ,
                'a1'                => $adp->a1 ,
                'a2'                => $adp->a2, 
                'a3'                => $adp->a3 ,
                'a4'                => $adp->a4 ,
                'a5'                => $adp->a5 ,
                'a6'                => $adp->a6 ,
                'a7'                => $adp->a7, 
                'TMLTCODE'          => $adp->TMLTCODE,
                'STATUS1'           => $adp->STATUS1,
                'BI'                => $adp->BI,
                'CLINIC'            => $adp->CLINIC,
                'ITEMSRC'           => $adp->ITEMSRC,
                'PROVIDER'          => $adp->PROVIDER,
                'GLAVIDA'           => $adp->GLAVIDA,
                'GA_WEEK'           => $adp->GA_WEEK,
                'DCIP'              => $adp->DCIP,
                'LMP'               => $adp->LMP
            ]);
        }

        D_dru::truncate();
        $dru_ = DB::connection('mysql3')->select('   
            SELECT vv.hcode HCODE
            ,v.hn HN
            ,v.an AN
            ,vv.spclty CLINIC
            ,vv.cid PERSON_ID
            ,DATE_FORMAT(v.vstdate,"%Y%m%d") DATE_SERV
            ,d.icode DID
            ,concat(d.`name`," ",d.strength," ",d.units) DIDNAME
            ,v.qty AMOUNT
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
            , " " TOTAL,"" SIGCODE,""  SIGTEXT
            from opitemrece v
            LEFT JOIN drugitems d on d.icode = v.icode
            LEFT JOIN vn_stat vv on vv.vn = v.vn
            LEFT JOIN ovst_presc_ned oo on oo.vn = v.vn and oo.icode=v.icode
            left join claim.tempexport x on x.vn = v.vn
            where x.ACTIVE="N"
            and d.did is not null 
            GROUP BY v.vn,did
            UNION all
            SELECT pt.hcode HCODE
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
            ," " TOTAL,"" SIGCODE,""  SIGTEXT
            from opitemrece v
            LEFT JOIN drugitems d on d.icode = v.icode
            LEFT JOIN patient pt  on v.hn = pt.hn
            inner JOIN ipt v1 on v1.an = v.an
            LEFT JOIN ovst_presc_ned oo on oo.vn = v.vn and oo.icode=v.icode 
            left join claim.tempexport x on x.vn = v1.vn
            where x.ACTIVE="N"
            and d.did is not null AND v.qty<>"0"
            GROUP BY v.an,d.icode,USE_STATUS; 
        ');
         
        foreach ($dru_ as $key => $dru) {
            D_dru::insert([                        
                'HN'           => $dru->HN, 
                'HCODE'         => $dru->HCODE,
                'AN'            => $dru->AN,
                'CLINIC'        => $dru->CLINIC,
                'PERSON_ID'     => $dru->PERSON_ID,
                'DATE_SERV'     => $dru->DATE_SERV,
                'DID'           => $dru->DID,
                'DIDNAME'       => $dru->DIDNAME,
                'AMOUNT'        => $dru->AMOUNT,
                'DRUGPRIC'      => $dru->DRUGPRIC,
                'DRUGCOST'      => $dru->DRUGCOST,
                'DIDSTD'        => $dru->DIDSTD,
                'UNIT'          => $dru->UNIT,
                'UNIT_PACK'     => $dru->UNIT_PACK,
                'SEQ'           => $dru->SEQ,
                'DRUGREMARK'    => $dru->DRUGREMARK,
                'PA_NO'         => $dru->PA_NO,
                'TOTCOPAY'      => $dru->TOTCOPAY,
                'USE_STATUS'    => $dru->USE_STATUS,
                // 'STATUS1'       => $dru->STATUS1,
                'TOTAL'         => $dru->TOTAL,
                'SIGCODE'       => $dru->SIGCODE,
                'SIGTEXT'       => $dru->SIGTEXT 


            ]);
        }
 
        // return response()->json(['status' => '200']); 
        return view('claim.anc_Pregnancy_test',[
            'start'            => $datestart,
            'end'              => $dateend,
        ]);
    }

    public function anc_Pregnancy_test_export(Request $request)
    { 
        $datestart = $request->startdate;
        $dateend = $request->enddate;

        $date_now = date("Y-m-d");
        $y = substr(date("Y"),2);
        $m = date('m');
        $t = date("His");
        $time_now = date("H:i:s");
      
         #ตัดขีด, ตัด : ออก
    // $year = substr(date("Y"),2) +43;
    // $mounts = date('m');
    // $day = date('d');
    // $time = date("His");  
    // $vn = $year.''.$mounts.''.$day.''.$time;
        // dd($y);
        #sessionid เป็นค่าว่าง แสดงว่ายังไม่เคยส่งออก ต้องสร้างไอดีใหม่ จาก max+1
        $maxid = D_export::max('session_no');
        $session_no = $maxid+1;        

        #ตัดขีด, ตัด : ออก
        $pattern_date = '/-/i';
        $date_now_preg = preg_replace($pattern_date, '', $date_now);
        $pattern_time = '/:/i';
        $time_now_preg = preg_replace($pattern_time, '', $time_now);
        #ตัดขีด, ตัด : ออก

        $folder='10978_KTBBIL_'.$session_no.'_01_'.$date_now_preg.'-'.$time_now_preg;

        $add = new D_export();
        $add->session_no = $session_no;
        $add->session_date = $date_now;
        $add->session_time = $time_now;
        $add->session_filename = $folder;
        $add->session_ststus = "Send";
        $add->ACTIVE = "Y";
        $add->save();

        mkdir ('C:/export/'.$folder, 0777, true);
   
        // ดาวโหลด
        // header("Content-type: text/txt");
        // header("Cache-Control: no-store, no-cache");
        // header('Content-Disposition: attachment; filename="content.txt"');

        $file_pat = "C:/export/".$folder."/ADP".$y."".$m."".$t.".txt";     
        $objFopen_opd = fopen($file_pat, 'w');

        $file_pat2 = "C:/export/".$folder."/DRU".$y."".$m."".$t.".txt";     
        $objFopen_opd2 = fopen($file_pat2, 'w');

        $file_pat3 = "C:/export/".$folder."/INS".$y."".$m."".$t.".txt";     
        $objFopen_opd3 = fopen($file_pat3, 'w');

        $file_pat4 = "C:/export/".$folder."/ODX".$y."".$m."".$t.".txt";     
        $objFopen_opd4 = fopen($file_pat4, 'w');

        $file_pat5 = "C:/export/".$folder."/OPD".$y."".$m."".$t.".txt";     
        $objFopen_opd5 = fopen($file_pat5, 'w');

        $file_pat6 = "C:/export/".$folder."/PAT".$y."".$m."".$t.".txt";     
        $objFopen_opd6 = fopen($file_pat6, 'w');

        $opd_head = 'HN|AN|DATEOPD|TYPE|CODE|QTY|RATE|SEQ|CAGCODE|DOSE|CA_TYPE|SERIALNO|TOTCOPAY|USE_STATUS|TOTAL|QTYDAY|TMLTCODE|STATUS1|BI|CLINIC|ITEMSRC|PROVIDER|GLAVIDA|GA_WEEK|DCIP|LMP';
        fwrite($objFopen_opd, $opd_head);

        $opd_head2 = 'HCODE|HN|AN|CLINIC|PERSON_ID|DATE_SERV|DID|DIDNAME|AMOUNT|DRUGPRIC|DRUGCOST|DIDSTD|UNIT|UNIT_PACK|SEQ|DRUGREMARK|PA_NO|TOTCOPAY|USE_STATUS|TOTAL|SIGCODE|SIGTEXT';
        fwrite($objFopen_opd2, $opd_head2);

        $opd_head3 = 'HN|INSCL|SUBTYPE|CID|DATEIN|DATEEXP|HOSPMAIN|HOSPSUB|GOVCODE|GOVNAME|PERMITNO|DOCNO|OWNRPID|OWNNAME|AN|SEQ|SUBINSCL|RELINSCL|HTYPE';
        fwrite($objFopen_opd3, $opd_head3);

        $opd_head4 = 'HN|DATEDX|CLINIC|DIAG|DXTYPE|DRDX|PERSON_ID|SEQ';
        fwrite($objFopen_opd4, $opd_head4);

        $opd_head5 = 'HN|CLINIC|DATEOPD|TIMEOPD|SEQ|UUC';
        fwrite($objFopen_opd5, $opd_head5);

        $opd_head6 = 'HCODE|HN|CHANGWAT|AMPHUR|DOB|SEX|MARRIAGE|OCCUPA|NATION|PERSON_ID|NAMEPAT|TITLE|FNAME|LNAME|IDTYPE';
        fwrite($objFopen_opd6, $opd_head6);

        










        $ins_ = DB::connection('mysql7')->select('   
            SELECT * FROM d_ins   
        ');
        $pat_ = DB::connection('mysql7')->select('   
            SELECT * FROM d_pat   
        ');
        $opd_ = DB::connection('mysql7')->select('   
            SELECT * FROM d_opd   
        ');
        $odx_ = DB::connection('mysql7')->select('   
            SELECT * FROM d_odx   
        ');
        $adp_ = DB::connection('mysql7')->select('   
            SELECT * FROM d_adp   
        ');
        $dru_ = DB::connection('mysql7')->select('   
            SELECT * FROM d_dru   
        ');

        return view('claim.anc_Pregnancy_test',[
            'start'            => $datestart,
            'end'              => $dateend,
            'ins_'              => $ins_,
            'pat_'              => $pat_,
            'opd_'              => $opd_,
            'odx_'              => $odx_,
            'adp_'              => $adp_,
            'dru_'              => $dru_
        ]);
    }


    
    
}
