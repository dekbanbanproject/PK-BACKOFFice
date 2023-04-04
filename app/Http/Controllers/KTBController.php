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
use App\Models\D_export;
use App\Models\D_aer;
use App\Models\d_adp;
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

        return view('claim.anc_Pregnancy_test',[
            'start'            => $datestart,
            'end'              => $dateend,
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
        D_export::truncate();
        foreach ($data_opd as $key => $value) {           
            $add= new D_export();
            $add->vn = $value->vn ;
            $add->hn = $value->hn; 
            $add->an = $value->an; 
            $add->cid = $value->cid; 
            $add->save();
        }

        $data_ktb_b17 = DB::connection('mysql3')->select('   
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
        D_ktb_b17::truncate();
        foreach ($data_ktb_b17 as $key => $item2) {   
            $data_add = D_ktb_b17::create([
                'vn' => $item2->vn,
                'hn' => $item2->hn,
                'an' => $item2->an,
                'cid' => $item2->cid
            ]);
            $data_add->save();       
        }

         //     $add2= new D_ktb_b17();
        //     $add2->vn = $item2->vn ;
        //     $add2->hn = $item2->hn; 
        //     $add2->an = $item2->an; 
        //     $add2->cid = $item2->cid; 
        //     $add2->save();
    // return response()->json(['status' => '200']);



        return view('claim.anc_Pregnancy_test',[
            'start'            => $datestart,
            'end'              => $dateend,
        ]);
    }


    
    
}
