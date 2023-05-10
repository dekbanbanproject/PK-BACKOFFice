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
use Illuminate\Support\Facades\File;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
// use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\OtExport;
// use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel; 
use App\Models\Article;
use App\Models\Product_prop;
use App\Models\Product_decline;
use App\Models\Department_sub_sub;
use App\Models\Products_vendor;
use App\Models\Status; 
use App\Models\Products_request;
use App\Models\Products_request_sub;   
use App\Models\Leave_leader;
use App\Models\Leave_leader_sub;
use App\Models\Check_sit_auto;
use App\Models\Check_sit;
use App\Models\Ssop_stm;
use App\Models\Ssop_session;
use App\Models\Ssop_opdx;
use App\Models\Pang_stamp_temp;
use App\Models\Ssop_token;
use App\Models\Ssop_opservices;
use App\Models\Ssop_dispenseditems;
use App\Models\Ssop_dispensing;
use App\Models\Ssop_billtran;
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
use App\Models\Dashboard_authen_day;
use App\Models\Dashboard_department_authen;
use App\Models\Visit_pttype_authen_report;
use App\Models\Dashboard_authenstaff_day;
use Auth;
use ZipArchive;
use Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Stevebauman\Location\Facades\Location; 
use SoapClient; 
use SplFileObject;
// use File;
 
 

class AutoController extends Controller
{  
    public function sit(Request $request)
    {             
        return view('authen.sit');
    }
    public function repage(Request $request)
    {             
        $date_now = date('Y-m-d');
        // $date_start = "2023-05-04";
        // $date_end = "2023-05-07"; 
        // $url = "https://authenservice.nhso.go.th/authencode/api/authencode-report?hcode=10978&provinceCode=3600&zoneCode=09&claimDateFrom=$date_now&claimDateTo=$date_now&page=0&size=100000";
        $url = "https://authenservice.nhso.go.th/authencode/#/report/eclaim";

        // dd($url);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            // CURLOPT_URL => 'https://authenservice.nhso.go.th/authencode/api/authencode-report?hcode=10978&provinceCode=3600&zoneCode=09&claimDateFrom=2023-01-05&claimDateTo=2023-01-05&page=0&size=1000',
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json, text/plain, */*',
                'Accept-Language: th-TH,th;q=0.9,en-US;q=0.8,en;q=0.7',
                'Connection: keep-alive',
                'Cookie: SESSION=Zjg0MGQ4YjQtYzc0OS00OGEyLWEzYjAtZTQxMDU5MGExMTIz; TS01bfdc7f=013bd252cb2f635ea275a9e2adb4f56d3ff24dc90de5421d2173da01a971bc0b2d397ab2bfbe08ef0e379c3946b8487cf4049afe9f2b340d8ce29a35f07f94b37287acd9c2; _ga_B75N90LD24=GS1.1.1665019756.2.0.1665019757.0.0.0; _ga=GA1.3.1794349612.1664942850; TS01e88bc2=013bd252cb8ac81a003458f85ce451e7bd5f66e6a3930b33701914767e3e8af7b92898dd63a6258beec555bbfe4b8681911d19bf0c; SESSION=YmI4MjUyNjYtODY5YS00NWFmLTlmZGItYTU5OWYzZmJmZWNh; TS01bfdc7f=013bd252cbc4ce3230a1e9bdc06904807c8155bd7d0a8060898777cf88368faf4a94f2098f920d5bbd729fbf29d55a388f507d977a65a3dbb3b950b754491e7a240f8f72eb; TS01e88bc2=013bd252cbe2073feef8c43b65869a02b9b370d9108007ac6a34a07f6ae0a96b2967486387a6a0575c46811259afa688d09b5dfd21',
                'Referer: https://authenservice.nhso.go.th/authencode/',
                'Sec-Fetch-Dest: empty',
                'Sec-Fetch-Mode: cors',
                'Sec-Fetch-Site: same-origin',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
                'sec-ch-ua: "Not?A_Brand";v="8", "Chromium";v="108", "Google Chrome";v="108"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Windows"'
            ),
        ));
 
        $response = curl_exec($curl);
        curl_close($curl);
        // dd($curl);
        $contents = $response;
        // dd($contents);
        $result = json_decode($contents, true);

        @$content = $result['content']; 
        return view('authen.repage');
    }
    public function sit_pull_auto(Request $request)
    { 
            $data_sits = DB::connection('mysql3')->select(' 
                SELECT o.vn,p.hn,p.cid,o.vstdate,o.vsttime,o.pttype,concat(p.pname,p.fname," ",p.lname) as fullname,o.staff,pt.nhso_code,o.hospmain,o.hospsub
                FROM ovst o 
                join patient p on p.hn=o.hn 
                JOIN pttype pt on pt.pttype=o.pttype  
                JOIN opduser op on op.loginname = o.staff 
                WHERE o.vstdate = CURDATE()
                group by p.cid
                limit 1500
            ');  
            foreach ($data_sits as $key => $value) { 
                $check = Check_sit_auto::where('vn', $value->vn)->count();
                if ($check == 0) {
                    Check_sit_auto::insert([
                        'vn' => $value->vn,
                        'hn' => $value->hn,
                        'cid' => $value->cid,
                        'vstdate' => $value->vstdate,
                        'vsttime' => $value->vsttime,
                        'fullname' => $value->fullname,
                        'pttype' => $value->pttype,
                        'hospmain' => $value->hospmain,
                        'hospsub' => $value->hospsub,
                        'staff' => $value->staff 
                    ]);
                }        
            }
            return view('authen.sit_pull_auto');
    }

    public function sit_auto(Request $request)
    {
        $datestart = $request->datestart;
        $dateend = $request->dateend;
        $date = date('Y-m-d');

        $token_data = DB::connection('mysql')->select('
            SELECT cid,token FROM ssop_token 
        '); 
        foreach ($token_data as $key => $valuetoken) {
            $cid_ = $valuetoken->cid;
            $token_ = $valuetoken->token;
        } 
        $data_sitss = DB::connection('mysql')->select(' 
            SELECT cid,vn
            FROM check_sit_auto  
            WHERE vstdate = CURDATE()              
            AND subinscl IS NULL  
            AND upsit_date IS NULL
            LIMIT 5
        ');  
         
        foreach ($data_sitss as $key => $item) {
            $pids = $item->cid;
            $vn = $item->vn;
   
            $client = new SoapClient("http://ucws.nhso.go.th/ucwstokenp1/UCWSTokenP1?wsdl",
                array(
                    "uri" => 'http://ucws.nhso.go.th/ucwstokenp1/UCWSTokenP1?xsd=1',
                                    "trace"      => 1,    
                                    "exceptions" => 0,    
                                    "cache_wsdl" => 0 
                    )
                );
                $params = array(
                    'sequence' => array(
                        "user_person_id" => "$cid_",
                        "smctoken" => "$token_",
                        "person_id" => "$pids"
                )
            ); 
            $contents = $client->__soapCall('searchCurrentByPID',$params);  
            foreach ($contents as $v) {  
                @$status = $v->status ;  
                @$maininscl = $v->maininscl;
                @$startdate = $v->startdate;
                @$hmain = $v->hmain ; 
                @$subinscl = $v->subinscl ;
                @$person_id_nhso = $v->person_id;

                @$hmain_op = $v->hmain_op;  //"10978"
                @$hmain_op_name = $v->hmain_op_name;  //"รพ.ภูเขียวเฉลิมพระเกียรติ"
                @$hsub = $v->hsub;    //"04047"
                @$hsub_name = $v->hsub_name;   //"รพ.สต.แดงสว่าง"
                @$subinscl_name = $v->subinscl_name ; //"ช่วงอายุ 12-59 ปี" 
                IF(@$maininscl == "" || @$maininscl == null || @$status == "003" ){ #ถ้าเป็นค่าว่างไม่ต้อง insert
                    $date = date("Y-m-d");
                    Check_sit_auto::where('vn', $vn) 
                                ->update([    
                                    'status' => 'จำหน่าย/เสียชีวิต',
                                    'maininscl' => @$maininscl,
                                    'startdate' => @$startdate,
                                    'hmain' => @$hmain,
                                    'subinscl' => @$subinscl,
                                    'person_id_nhso' => @$person_id_nhso,

                                    'hmain_op' => @$hmain_op,
                                    'hmain_op_name' => @$hmain_op_name,
                                    'hsub' => @$hsub,
                                    'hsub_name' => @$hsub_name,
                                    'subinscl_name' => @$subinscl_name,
                                    'upsit_date'    => $date
                                ]);      
                }elseif(@$maininscl !="" || @$subinscl !=""){  
                        $date2 = date("Y-m-d");
                            Check_sit_auto::where('vn', $vn) 
                            ->update([    
                                'status' => @$status,
                                'maininscl' => @$maininscl,
                                'startdate' => @$startdate,
                                'hmain' => @$hmain,
                                'subinscl' => @$subinscl,
                                'person_id_nhso' => @$person_id_nhso,

                                'hmain_op' => @$hmain_op,
                                'hmain_op_name' => @$hmain_op_name,
                                'hsub' => @$hsub,
                                'hsub_name' => @$hsub_name,
                                'subinscl_name' => @$subinscl_name,
                                'upsit_date'    => $date2
                            ]); 
 
                }

            }           
        }
       
        return view('authen.sit_auto');
       
    }

    public function dbday_auto(Request $request)
    { 
            $data_sits = DB::connection('mysql3')->select(' 
                    SELECT v.vstdate 
                    ,count(distinct v.hn) as hn
                    ,count(distinct v.vn) as vn 
                    ,count(distinct v.cid) as cid
                    ,"" as Kios
                    ,"" as Staff
                    ,"" as Success
                    ,"" as Unsuccess                     
                    FROM vn_stat v 
                    left outer join hos.ovst o on o.vn = v.vn 
                    left outer join hos.patient p on p.hn = v.hn   
                    left outer join hos.pttype pt on pt.pttype = v.pttype 
                    left outer join hos.leave_month l on l.MONTH_ID = month(v.vstdate)                    
                    WHERE o.vstdate = CURDATE()
                    group by DAY(v.vstdate)
            ');  
            foreach ($data_sits as $key => $value) { 
                $check = Dashboard_authen_day::where('vstdate', $value->vstdate)->count(); 
                if ($check == 0) {
                    Dashboard_authen_day::insert([
                        'vn' => $value->vn,
                        'hn' => $value->hn,
                        'cid' => $value->cid,
                        'vstdate' => $value->vstdate                        
                    ]);
                } else { 
                    Dashboard_authen_day::where('vstdate', $value->vstdate) 
                        ->update([    
                            'vn' => $value->vn,
                            'hn' => $value->hn,
                            'cid' => $value->cid,
                            'vstdate' => $value->vstdate
                            
                        ]);     
                }
                      
            }
            $data_kios_all = DB::connection('mysql3')->select(' 
                    SELECT v.vstdate 
                    ,COUNT(DISTINCT o.vn) as vn
                    ,count(DISTINCT p.cid) as cid                    
                    FROM ovst o
                    LEFT OUTER JOIN hos.vn_stat v on v.vn = o.vn 
                    LEFT OUTER JOIN visit_pttype vp on vp.vn = o.vn
                    LEFT OUTER JOIN ovst_queue_server os on os.vn = o.vn
                    LEFT OUTER JOIN ovst_queue_server_authen oq on oq.vn = os.vn 
                    LEFT OUTER JOIN patient p on p.hn=o.hn
     
                    WHERE o.vstdate = CURDATE()
                    AND os.staff LIKE "kiosk%"
                    GROUP BY o.vstdate
            ');  
            // = CURDATE()
            // BETWEEN "2023-05-01" AND "2023-05-09"
            foreach ($data_kios_all as $key => $value2) { 
                $check2 = Dashboard_authen_day::where('vstdate', $value2->vstdate)->count(); 
                if ($check2 == 0) {
                    Dashboard_authen_day::insert([
                        'Kios' => $value2->vn                       
                    ]);
                } else { 
                    Dashboard_authen_day::where('vstdate', $value2->vstdate) 
                        ->update([    
                            'Kios' => $value2->vn                             
                        ]);     
                }                      
            }
            $data_user_all = DB::connection('mysql3')->select(' 
                    SELECT v.vstdate,COUNT(o.vn) as VN                    
                    FROM ovst o
                    LEFT OUTER JOIN hos.vn_stat v on v.vn = o.vn   
                    LEFT OUTER JOIN visit_pttype_authen_report wr ON wr.personalId = v.cid AND v.vstdate = wr.claimDate
                    WHERE o.vstdate = CURDATE()
                    AND o.staff not LIKE "kiosk%" 
                    GROUP BY o.vstdate
            '); 
            $data_total_all = DB::connection('mysql3')->select(' 
                    SELECT v.vstdate,count(DISTINCT wr.claimCode) as claimCode                    
                    FROM ovst o
                    LEFT OUTER JOIN hos.vn_stat v on v.vn = o.vn   
                    LEFT OUTER JOIN visit_pttype_authen_report wr ON wr.personalId = v.cid AND v.vstdate = wr.claimDate
                    WHERE o.vstdate = CURDATE()
                    GROUP BY o.vstdate
            ');  
            foreach ($data_total_all as $key => $value3) { 
                $check3 = Dashboard_authen_day::where('vstdate', $value3->vstdate)->count(); 
                if ($check3 == 0) {
                    Dashboard_authen_day::insert([
                        'Total_Success' => $value3->claimCode                       
                    ]);
                } else { 
                    Dashboard_authen_day::where('vstdate', $value3->vstdate) 
                        ->update([    
                            'Total_Success' => $value3->claimCode                             
                        ]);     
                }
                      
            }
            
            return view('auto.dbday_auto');
    }

    public function depauthen_auto(Request $request)
    { 
            $data_authen = DB::connection('mysql3')->select(' 
                SELECT v.vstdate,o.main_dep,sk.department,COUNT(DISTINCT o.vn) as vn,count(DISTINCT wr.claimCode) as claimCode  
                        ,count(DISTINCT wr.tel) as Success ,COUNT(DISTINCT o.vn)-count(DISTINCT wr.tel) as Unsuccess
                        FROM ovst o
                        LEFT JOIN vn_stat v on v.vn = o.vn	
                        LEFT JOIN visit_pttype vp on vp.vn = o.vn
                        LEFT OUTER JOIN kskdepartment sk on sk.depcode = o.main_dep
                        LEFT OUTER JOIN patient p on p.hn=o.hn
                        LEFT OUTER JOIN visit_pttype_authen_report wr ON wr.personalId = p.cid and wr.claimDate = o.vstdate
                        WHERE o.vstdate = CURDATE() 
                        GROUP BY o.main_dep
            ');  
            foreach ($data_authen as $key => $value) { 
                $check = Dashboard_department_authen::where('vstdate', $value->vstdate)->where('main_dep', $value->main_dep)->count();                 
                if ($check == 0) {                     
                    Dashboard_department_authen::insert([
                        'vstdate'     => $value->vstdate,
                        'main_dep'    => $value->main_dep,
                        'department'  => $value->department,
                        'vn'          => $value->vn, 
                        'claimCode'   => $value->claimCode,
                        'Success'     => $value->Success,
                        'Unsuccess'   => $value->Unsuccess 
                    ]);
                } else {                     
                    Dashboard_department_authen::where('vstdate', $value->vstdate)->where('main_dep', $value->main_dep)
                    ->update([    
                        'vstdate'     => $value->vstdate,
                        // 'main_dep'    => $maindep,
                        // 'department'  => $department_,
                        'vn'          => $value->vn, 
                        'claimCode'   => $value->claimCode,
                        'Success'     => $value->Success,
                        'Unsuccess'   => $value->Unsuccess                            
                    ]); 
                }                       
            }

            $data_authen_person = DB::connection('mysql3')->select(' 
                SELECT v.vstdate,op.loginname,o.staff as Staffmini,op.name as Stafffull,s.name as Spclty 
                        ,COUNT(DISTINCT o.vn) as vn,count(DISTINCT vp.claimCode) as claimCode  
                        ,count(DISTINCT vp.tel) as Success ,COUNT(DISTINCT o.vn)-count(DISTINCT vp.tel) as Unsuccess			
                        FROM ovst o
                        LEFT JOIN vn_stat v on v.vn = o.vn	
                        LEFT JOIN visit_pttype vt on vt.vn = o.vn
                        LEFT OUTER JOIN kskdepartment sk on sk.depcode = o.main_dep
                        LEFT OUTER JOIN spclty s ON s.spclty = sk.spclty
                        LEFT OUTER JOIN patient p on p.hn=o.hn
                        LEFT OUTER JOIN visit_pttype_authen_report vp ON vp.personalId = p.cid and vp.claimDate = o.vstdate
                        LEFT OUTER JOIN opduser op on op.loginname = o.staff
                        WHERE o.vstdate = CURDATE() 
                        GROUP BY op.loginname
            '); 
            foreach ($data_authen_person as $key => $value2) { 
                // $check2 = Dashboard_authenstaff_day::where('vstdate', $value2->vstdate)->count(); 
                $check2 = Dashboard_authenstaff_day::where('vstdate', $value2->vstdate)->where('loginname','=',$value2->loginname)->count();                 
                if ($check2 == 0) {                     
                    Dashboard_authenstaff_day::insert([
                        'vstdate'     => $value2->vstdate,
                        'loginname'   => $value2->loginname,
                        'Staff'       => $value2->Stafffull,
                        'Spclty'      => $value2->Spclty,
                        'vn'          => $value2->vn, 
                        'claimCode'   => $value2->claimCode,
                        'Success'     => $value2->Success,
                        'Unsuccess'   => $value2->Unsuccess 
                    ]);
                } else {                     
                    Dashboard_authenstaff_day::where('vstdate', $value2->vstdate)->where('loginname','=',$value2->loginname)
                    ->update([    
                        'vstdate'     => $value2->vstdate,
                        'loginname'   => $value2->loginname,
                        'Staff'       => $value2->Stafffull,
                        'Spclty'      => $value2->Spclty,
                        'vn'          => $value2->vn, 
                        'claimCode'   => $value2->claimCode,
                        'Success'     => $value2->Success,
                        'Unsuccess'   => $value2->Unsuccess                           
                    ]); 
                }                       
            }
            return view('auto.depauthen_auto');
    }

    public function checkauthen_autospsch(Request $request)
    { 
        $date_now = date('Y-m-d');
        $date_start = "2023-05-07";
        $date_end = "2023-05-09";
    
        $url = "https://authenservice.nhso.go.th/authencode/api/authencode-report?hcode=10978&provinceCode=3600&zoneCode=09&claimDateFrom=$date_now&claimDateTo=$date_now&page=0&size=1000&sort=transId,desc";
      
        $curl = curl_init();
        curl_setopt_array($curl, array(
            // CURLOPT_URL => 'https://authenservice.nhso.go.th/authencode/api/authencode-report?hcode=10978&provinceCode=3600&zoneCode=09&claimDateFrom=2023-05-09&claimDateTo=2023-01-05&page=0&size=1000',
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json, text/plain, */*',
                'Accept-Language: th-TH,th;q=0.9,en-US;q=0.8,en;q=0.7',
                'Connection: keep-alive',
                'Cookie: SESSION=Zjg0MGQ4YjQtYzc0OS00OGEyLWEzYjAtZTQxMDU5MGExMTIz; TS01bfdc7f=013bd252cb2f635ea275a9e2adb4f56d3ff24dc90de5421d2173da01a971bc0b2d397ab2bfbe08ef0e379c3946b8487cf4049afe9f2b340d8ce29a35f07f94b37287acd9c2; _ga_B75N90LD24=GS1.1.1665019756.2.0.1665019757.0.0.0; _ga=GA1.3.1794349612.1664942850; TS01e88bc2=013bd252cb8ac81a003458f85ce451e7bd5f66e6a3930b33701914767e3e8af7b92898dd63a6258beec555bbfe4b8681911d19bf0c; SESSION=YmI4MjUyNjYtODY5YS00NWFmLTlmZGItYTU5OWYzZmJmZWNh; TS01bfdc7f=013bd252cbc4ce3230a1e9bdc06904807c8155bd7d0a8060898777cf88368faf4a94f2098f920d5bbd729fbf29d55a388f507d977a65a3dbb3b950b754491e7a240f8f72eb; TS01e88bc2=013bd252cbe2073feef8c43b65869a02b9b370d9108007ac6a34a07f6ae0a96b2967486387a6a0575c46811259afa688d09b5dfd21',
                'Referer: https://authenservice.nhso.go.th/authencode/',
                'Sec-Fetch-Dest: empty',
                'Sec-Fetch-Mode: cors',
                'Sec-Fetch-Site: same-origin',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
                'sec-ch-ua: "Not?A_Brand";v="8", "Chromium";v="108", "Google Chrome";v="108"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Windows"'
            ),
        ));
 
        $response = curl_exec($curl);
        curl_close($curl);
        // dd($curl);
        $contents = $response; 
        $result = json_decode($contents, true); 
        @$content = $result['content']; 
        // dd($content);
    
        foreach ($content as $key => $value) {
            $transId = $value['transId'];  
            $hmain = $value['hmain']; 
            // $personalId = $value['personalId']; 
            // $patientName = $value['patientName']; 
            // $mainInscl = $value['mainInscl']; 
            // $mainInsclName = $value['mainInsclName']; 
            // $subInscl = $value['subInscl']; 
            // $subInsclName = $value['subInsclName'];
            // $hnCode = $value['hnCode'];
            // $createDate = $value['createDate']; 


            // isset( $value['hmain'] ) ? $hmain = $value['hmain'] : $hmain = "";
            isset( $value['personalId'] ) ? $personalId = $value['personalId'] : $personalId = "";
            isset( $value['patientName'] ) ? $patientName = $value['patientName'] : $patientName = "";
            isset( $value['addrNo'] ) ? $addrNo = $value['addrNo'] : $addrNo = "";
            isset( $value['moo'] ) ? $moo = $value['moo'] : $moo = "";
            isset( $value['moonanName'] ) ? $moonanName = $value['moonanName'] : $moonanName = "";
            isset( $value['tumbonName'] ) ? $tumbonName = $value['tumbonName'] : $tumbonName = "";
            isset( $value['amphurName'] ) ? $amphurName = $value['amphurName'] : $amphurName = "";
            isset( $value['changwatName'] ) ? $changwatName = $value['changwatName'] : $changwatName = "";
            isset( $value['birthdate'] ) ? $birthdate = $value['birthdate'] : $birthdate = "";
            isset( $value['tel'] ) ? $tel = $value['tel'] : $tel = "";
            isset( $value['mainInscl'] ) ? $mainInscl = $value['mainInscl'] : $mainInscl = "";
            isset( $value['mainInsclName'] ) ? $mainInsclName = $value['mainInsclName'] : $mainInsclName = "";
            isset( $value['subInscl'] ) ? $subInscl = $value['subInscl'] : $subInscl = "";
            isset( $value['subInsclName'] ) ? $subInsclName = $value['subInsclName'] : $subInsclName = "";
            isset( $value['claimStatus'] ) ? $claimStatus = $value['claimStatus'] : $claimStatus = "";
            isset( $value['patientType'] ) ? $patientType = $value['patientType'] : $patientType = "";
            isset( $value['claimCode'] ) ? $claimCode = $value['claimCode'] : $claimCode = "";
            isset( $value['claimType'] ) ? $claimType = $value['claimType'] : $claimType = "";
            isset( $value['claimTypeName'] ) ? $claimTypeName = $value['claimTypeName'] : $claimTypeName = "";
            isset( $value['hnCode'] ) ? $hnCode = $value['hnCode'] : $hnCode = ""; 
            isset( $value['createDate'] ) ? $createDate = $value['createDate'] : $createDate = "";

            isset( $value['claimStatus'] ) ? $claimStatus = $value['claimStatus'] : $claimStatus = "";
            isset( $value['patientType'] ) ? $patientType = $value['patientType'] : $patientType = "";
            isset( $value['sourceChannel'] ) ? $sourceChannel = $value['sourceChannel'] : $sourceChannel = "";
            isset( $value['claimAuthen'] ) ? $claimAuthen = $value['claimAuthen'] : $claimAuthen = "";
            isset( $value['createBy'] ) ? $createBy = $value['createBy'] : $createBy = "";
            isset( $value['mainInsclWithName'] ) ? $mainInsclWithName = $value['mainInsclWithName'] : $mainInsclWithName = "";
          
            $claimDate = explode("T",$value['claimDate']);
            $checkdate = $claimDate[0];
            $checktime = $claimDate[1];
            // dd($transId); 
                $datenow = date("Y-m-d");               
                    $checktransId = Visit_pttype_authen_report::where('transId','=',$transId)->count(); 
                    // dd($checktransId);
                    if ($checktransId > 0) {
                       
                            Visit_pttype_authen_report::where('transId', $transId)
                                ->update([
                                            // 'transId'                           => $transId, 
                                            'hmain'                             => $hmain,
                                            'personalId'                        => $personalId,
                                            'patientName'                       => $patientName, 
                                            'addrNo'                            => $addrNo,                            
                                            'moo'                               => $moo,
                                            'moonanName'                        => $moonanName,
                                            'tumbonName'                        => $tumbonName,
                                            'amphurName'                        => $amphurName,
                                            'changwatName'                      => $changwatName,
                                            'birthdate'                         => $birthdate,
                                            'tel'                               => $tel,
                                            'mainInscl'                         => $mainInscl,
                                            'mainInsclName'                     => $mainInsclName,
                                            'subInscl'                          => $subInscl,
                                            'subInsclName'                      => $subInsclName,
                                            'claimDate'                         => $checkdate,
                                            'claimTime'                         => $checktime,
                                            'claimCode'                         => $claimCode,
                                            'claimType'                         => $claimType,
                                            'claimTypeName'                     => $claimTypeName,
                                            'hnCode'                            => $hnCode, 
                                            'claimStatus'                       => $claimStatus, 
                                            'patientType'                       => $patientType, 
                                            'createBy'                          => $createBy, 
                                            'sourceChannel'                     => $sourceChannel, 
                                            'mainInsclWithName'                 => $mainInsclWithName,
                                            'claimAuthen'                       => $claimAuthen,  
                                            'date_data'                         => $datenow
                                ]);                         
                    } else {    
                         
                            $data_add = Visit_pttype_authen_report::create([  
                                    'transId'                           => $transId, 
                                    'hmain'                             => $hmain,
                                    'personalId'                        => $personalId,
                                    'patientName'                       => $patientName, 
                                    'addrNo'                            => $addrNo,                            
                                    'moo'                               => $moo,
                                    'moonanName'                        => $moonanName,
                                    'tumbonName'                        => $tumbonName,
                                    'amphurName'                        => $amphurName,
                                    'changwatName'                      => $changwatName,
                                    'birthdate'                         => $birthdate,
                                    'tel'                               => $tel,
                                    'mainInscl'                         => $mainInscl,
                                    'mainInsclName'                     => $mainInsclName,
                                    'subInscl'                          => $subInscl,
                                    'subInsclName'                      => $subInsclName,
                                    'claimDate'                         => $checkdate,
                                    'claimTime'                         => $checktime,
                                    'claimCode'                         => $claimCode,
                                    'claimType'                         => $claimType,
                                    'claimTypeName'                     => $claimTypeName,
                                    'hnCode'                            => $hnCode, 
                                    'claimStatus'                       => $claimStatus, 
                                    'patientType'                       => $patientType,
                                    'createBy'                          => $createBy, 
                                    'sourceChannel'                     => $sourceChannel,
                                    'mainInsclWithName'                 => $mainInsclWithName,
                                    'claimAuthen'                       => $claimAuthen,  
                                    'date_data'                         => $datenow
                        ]);
                        $data_add->save();
                    }   
        }
        return view('auto.checkauthen_autospsch',[
            'response'  => $response,
            'result'  => $result, 
        ]);     
        
    }

 
    
}
