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

    
    
}
