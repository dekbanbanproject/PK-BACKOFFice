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
 
 

class ChecksitController extends Controller
{ 
    public function check_sit_day(Request $request)
    {
        $datestart = $request->startdate;
        $dateend = $request->enddate;

        // if ($datestart == '') {      
        //     $data_sit = DB::connection('mysql')->select(' 
        //         SELECT vn,cid,vstdate,fullname,pttype,hospmain,hospsub,subinscl,hmain,hsub,staff,subinscl_name
        //         FROM check_sit_auto  
        //         WHERE vstdate = CURDATE()  
        //         GROUP BY cid              
        //     '); 
           
        // } else {
            // $data_sit = DB::connection('mysql7')->select(' 
            $data_sit = DB::connection('mysql')->select(' 
                SELECT vn,cid,vstdate,fullname,pttype,hospmain,hospsub,subinscl,hmain,hsub,staff,subinscl_name
                FROM check_sit_auto  
                WHERE vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'" 
                GROUP BY cid
            ');             
        // }    
        return view('authen.check_sit_day ',[            
            'data_sit'    => $data_sit, 
            'start'     => $datestart, 
            'end'        => $dateend,           
        ]);
    }
    public function check_sit_daysearch(Request $request)
    {
        $datestart = $request->datestart;
        $dateend = $request->dateend; 
        // dd($dateend);

        if ($datestart == '') {
            $data_sits = DB::connection('mysql3')->select(' 
                SELECT o.vn,p.hn,p.cid,o.vstdate,o.vsttime,o.pttype,concat(p.pname,p.fname," ",p.lname) as fullname,o.staff,pt.nhso_code,o.hospmain,o.hospsub
                FROM ovst o 
                join patient p on p.hn=o.hn 
                JOIN pttype pt on pt.pttype=o.pttype  
                JOIN opduser op on op.loginname = o.staff 
                WHERE o.vstdate = CURDATE()  
                group by p.cid
            ');  
            // AND pt.pttype_eclaim_id not in("06","27","28","36")
            foreach ($data_sits as $key => $value) {
                // Check_sit_auto::truncate();
                $check = Check_sit_auto::where('vn', $value->vn)->count();
                if ($check > 0) {
                    Check_sit_auto::where('vn', $value->vn) 
                        ->update([   
                            'hn' => $value->hn,
                            'cid' => $value->cid,
                            'vstdate' => $value->vstdate,
                            'vsttime' => $value->vsttime,
                            'fullname' => $value->fullname,
                            'hospmain' => $value->hospmain,
                            'hospsub' => $value->hospsub,
                            'pttype' => $value->pttype,
                            'staff' => $value->staff 
                        ]);     
                } else {
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

            $data_sit = DB::connection('mysql7')->select(' 
                SELECT *
                FROM check_sit_auto  
                WHERE vstdate = CURDATE()  
            '); 

        } else {
            $data_sits = DB::connection('mysql3')->select(' 
                SELECT o.vn,p.hn,p.cid,o.vstdate,o.vsttime,o.pttype,concat(p.pname,p.fname," ",p.lname) as fullname,o.staff,pt.nhso_code,o.hospmain,o.hospsub
                FROM ovst o 
                join patient p on p.hn=o.hn 
                JOIN pttype pt on pt.pttype=o.pttype  
                JOIN opduser op on op.loginname = o.staff 
                WHERE o.vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'" 
                group by p.cid
            ');  
                foreach ($data_sits as $key => $value) {
                    // Check_sit_auto::truncate();
                    $check = Check_sit_auto::where('vn', $value->vn)->count();
                    if ($check > 0) {
                        Check_sit_auto::where('vn', $value->vn) 
                            ->update([   
                                'hn' => $value->hn,
                                'cid' => $value->cid,
                                'vstdate' => $value->vstdate,
                                'vsttime' => $value->vsttime,
                                'fullname' => $value->fullname,
                                'hospmain' => $value->hospmain,
                                'hospsub' => $value->hospsub,
                                'pttype' => $value->pttype,
                                'staff' => $value->staff 
                            ]);     
                    } else {
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
            $data_sit = DB::connection('mysql7')->select(' 
                SELECT *
                FROM check_sit_auto  
                WHERE vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'" 
            '); 

        }     
        // AND pt.pttype_eclaim_id not in("06","27","28","36")           

        
        // if ($datestart == '') {      
        //     $data_sit = DB::connection('mysql7')->select(' 
        //         SELECT *
        //         FROM check_sit_auto  
        //         WHERE vstdate = CURDATE()  
        //     '); 
        // } else {
        //     $data_sit = DB::connection('mysql7')->select(' 
        //         SELECT *
        //         FROM check_sit_auto  
        //         WHERE vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'" 
        //     ');             
        // }   
        return response()->json([
            'status'     => '200',
             'data_sit'    => $data_sit, 
            'start'     => $datestart, 
            'end'        => $dateend, 
        ]); 
        // return view('authen.check_sit_day ',[            
        //     'data_sit'    => $data_sit, 
        //     'start'     => $datestart, 
        //     'end'        => $dateend,           
        // ]);
    }

    public function check_sit_pull(Request $request)
    {
        $datestart = $request->datestart;
        $dateend = $request->dateend; 
        // dd($datestart);

        // if ($datestart == '') {
        //     $data_sits = DB::connection('mysql3')->select(' 
        //         SELECT o.vn,p.hn,p.cid,o.vstdate,o.vsttime,o.pttype,concat(p.pname,p.fname," ",p.lname) as fullname,o.staff,pt.nhso_code,o.hospmain,o.hospsub
        //         FROM ovst o 
        //         join patient p on p.hn=o.hn 
        //         JOIN pttype pt on pt.pttype=o.pttype  
        //         JOIN opduser op on op.loginname = o.staff 
        //         WHERE o.vstdate = CURDATE()  
        //         group by p.cid
        //     ');  
           
        //     foreach ($data_sits as $key => $value) { 
        //         $check = Check_sit_auto::where('vn', $value->vn)->count();
        //         if ($check > 0) {
        //             Check_sit_auto::where('vn', $value->vn) 
        //                 ->update([   
        //                     'hn' => $value->hn,
        //                     'cid' => $value->cid,
        //                     'vstdate' => $value->vstdate,
        //                     'vsttime' => $value->vsttime,
        //                     'fullname' => $value->fullname,
        //                     'hospmain' => $value->hospmain,
        //                     'hospsub' => $value->hospsub,
        //                     'pttype' => $value->pttype,
        //                     'staff' => $value->staff 
        //                 ]);     
        //         } else {
        //             Check_sit_auto::insert([
        //                 'vn' => $value->vn,
        //                 'hn' => $value->hn,
        //                 'cid' => $value->cid,
        //                 'vstdate' => $value->vstdate,
        //                 'vsttime' => $value->vsttime,
        //                 'fullname' => $value->fullname,
        //                 'pttype' => $value->pttype,
        //                 'hospmain' => $value->hospmain,
        //                 'hospsub' => $value->hospsub,
        //                 'staff' => $value->staff 
        //             ]);
        //         }                        
        //     }
  
        // } else {
            $data_sits = DB::connection('mysql3')->select(' 
                SELECT o.vn,p.hn,p.cid,o.vstdate,o.vsttime,o.pttype,concat(p.pname,p.fname," ",p.lname) as fullname,o.staff,pt.nhso_code,o.hospmain,o.hospsub
                FROM ovst o 
                join patient p on p.hn=o.hn 
                JOIN pttype pt on pt.pttype=o.pttype  
                JOIN opduser op on op.loginname = o.staff 
                WHERE o.vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'" 
                group by p.cid
            ');  
                foreach ($data_sits as $key => $value) {
                    // Check_sit_auto::truncate();
                    $check = Check_sit_auto::where('vn', $value->vn)->count();
                    if ($check > 0) {
                        Check_sit_auto::where('vn', $value->vn) 
                            ->update([   
                                'hn' => $value->hn,
                                'cid' => $value->cid,
                                'vstdate' => $value->vstdate,
                                'vsttime' => $value->vsttime,
                                'fullname' => $value->fullname,
                                'hospmain' => $value->hospmain,
                                'hospsub' => $value->hospsub,
                                'pttype' => $value->pttype,
                                'staff' => $value->staff 
                            ]);     
                    } else {
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
            // $data_sit = DB::connection('mysql7')->select(' 
            //     SELECT *
            //     FROM check_sit_auto  
            //     WHERE vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'" 
            // '); 

        // }     
      
        return response()->json([
            'status'     => '200',
            //  'data_sit'    => $data_sit, 
            'start'     => $datestart, 
            'end'        => $dateend, 
        ]); 
        // return view('authen.check_sit_day ',[            
        //     'data_sit'    => $data_sit, 
        //     'start'     => $datestart, 
        //     'end'        => $dateend,           
        // ]);
    }
    public function check_sit_font(Request $request)
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
        // $data_sitss = DB::connection('mysql7')->select('
        $data_sitss = DB::connection('mysql')->select(' 
            SELECT cid,vn
            FROM check_sit_auto  
            WHERE vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'"               
            AND subinscl IS NULL  
            AND upsit_date IS NULL
            LIMIT 30
        ');  
        // AND person_id_nhso IS NULL 

        // AND upsit_date IS NULL
        // AND status <> "จำหน่าย/เสียชีวิต"
        // dd($data_sitss);
        // WHERE vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'" 

        // WHERE vstdate = CURDATE() 
        // WHERE vstdate = "2023-01-25"  
        // WHERE vstdate = CURDATE()
        // BETWEEN "'.$datestart.'" AND "'.$dateend.'"  
        // set_time_limit(1000);
        // $i = 0;
        foreach ($data_sitss as $key => $item) {
            $pids = $item->cid;
            $vn = $item->vn;
             
            // sleep(1000);
            // $i++;
            // dd($pids); 
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
       
            // dd($contents);
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
        $data_sit = DB::connection('mysql')->select(' 
                SELECT vn,cid,vstdate,fullname,pttype,hospmain,hospsub,subinscl,hmain,hsub,staff,subinscl_name
                FROM check_sit_auto  
                WHERE vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'" 
            '); 

        // return view('authen.check_sit_auto ',[            
        //     'data_sit'    => $data_sit, 
        //     'start'     => $datestart, 
        //     'end'        => $dateend,           
        // ]);
        // return redirect()->back();
        //  return view('authen.check_sit_day ',[   
        //     'status'     => '200',         
        //     'data_sit'    => $data_sit, 
        //     'start'     => $datestart, 
        //     'end'        => $dateend,           
        // ]);
        return response()->json([
            'status'     => '200',
            // 'data_sit'    => $data_sit, 
            'start'     => $datestart, 
            'end'        => $dateend, 
        ]); 
    }

 
    public function acc_checksit_spsch_pangstamp(Request $request)
    {
        $datestart = $request->startdate;
        $dateend = $request->enddate;
        // dd($datestart); 
        $token_data = DB::connection('mysql7')->select('
            SELECT cid,token FROM ssop_token 
        '); 
        foreach ($token_data as $key => $valuetoken) {
            $cid_ = $valuetoken->cid;
            $token_ = $valuetoken->token;
        }
 
        $data_sitss = DB::connection('mysql8')->select('
            SELECT * FROM pang_stamp_temp
            WHERE vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'"  
            AND check_sit_subinscl IS NULL
        '); 

        // dd($data_sitss); 
        foreach ($data_sitss as $key => $item) {
            $pids = $item->cid;
            $vn = $item->vn;
            $vstdate = $item->vstdate; 
            // dd($pids); 
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
       
            // dd($contents);
            foreach ($contents as $key => $v) {  
                @$status = $v->status ;  
                @$maininscl = $v->maininscl;
                @$startdate = $v->startdate;
                @$hmain = $v->hmain ; 
                @$subinscl = $v->subinscl ;
                @$person_id_nhso = $v->person_id;
                // dd(@$status);
                IF(@$maininscl =="" || @$maininscl==null || @$status =="003" ){ #ถ้าเป็นค่าว่างไม่ต้อง insert
                   
                        $date_now = date('Y-m-d');
                        Pang_stamp_temp::where('vn', $vn) 
                                ->update([  
                                    'check_sit_subinscl'       => @$subinscl, 
                                    'pttype_stamp'             => 'จำหน่าย/เสียชีวิต'
                                ]);      
                  }elseif(@$maininscl !="" || @$subinscl !=""){ 
                    $date_now2 = date('Y-m-d');
                    Pang_stamp_temp::where('vn', $vn) 
                                ->update([  
                                    'check_sit_subinscl'          => @$subinscl, 
                                    'pttype_stamp'             => @$subinscl.'('.@$hmain.')'.$date_now2
                                ]);
                  
                // }elseif($maininscl=="" && $status=="" ){ 
                //     Pang_stamp_temp::where('vn', $vn) 
                //                 ->update([  
                //                     'check_sit_subinscl'          => @$subinscl, 
                //                     'pttype_stamp'             => @$subinscl.'('.@$hmain.')'.$date_now
                //                 ]);
                  }

            }           
        }
        $data_sit = DB::connection('mysql8')->select('
            SELECT * FROM pang_stamp_temp
            WHERE vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'"                 
        '); 
        return view('claim.acc_checksit ',[    
            'subinscl'     => @$subinscl, 
            'start'        => $datestart,
            'end'          => $dateend, 
            'data_sit'     => $data_sit          
        ]);
    }

    public function check_sit_token(Request $request)
    {
        $datestart = $request->datestart;
        $dateend = $request->dateend;
        $cid = $request->cid;
        $token = $request->token;

        Ssop_token::truncate();

        $data_add = Ssop_token::create([
            'cid'               => $cid,
            'token'             => $token            
        ]);
        $data_add->save();
        
        return response()->json([
            'status'     => '200', 
            'start'     => $datestart, 
            'end'        => $dateend, 
        ]); 
    }
    public function check_sit_money(Request $request)
    {
        $datestart = $request->startdate;
        $dateend = $request->enddate;
 
            $data_sit = DB::connection('mysql5')->select(' 
                SELECT *
                FROM pang_stamp_temp  
                WHERE vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'" 
            ');   
        return view('authen.check_sit_money ',[            
            'data_sit'    => $data_sit, 
            'start'     => $datestart, 
            'end'        => $dateend,           
        ]);
    }
    public function check_sit_money_pk(Request $request)
    {
        $datestart = $request->datepicker;
        $dateend = $request->datepicker2;
        $date = date('Y-m-d');

        $token_data = DB::connection('mysql7')->select('
            SELECT cid,token FROM ssop_token 
        '); 
        foreach ($token_data as $key => $valuetoken) {
            $cid_ = $valuetoken->cid;
            $token_ = $valuetoken->token;
        }
 
        $data_sitss = DB::connection('mysql5')->select(' 
            SELECT *
            FROM pang_stamp_temp  
            WHERE vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'"   
            AND pang_stamp IN("1102050101.401","1102050101.402","1102050102.801","1102050102.802","1102050102.803","1102050102.804")
            AND check_sit_subinscl IS NULL;
        ');  
        
        foreach ($data_sitss as $item) {
            $pids = $item->cid;
            $vn = $item->vn;
            $hn = $item->hn; 
            $vstdate = $item->vstdate;
          
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
       
            // dd($contents);
            foreach ($contents as $key => $v) {  
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
                // dd(@$maininscl);
                IF(@$maininscl =="" || @$maininscl==null || @$status =="003" ){ #ถ้าเป็นค่าว่างไม่ต้อง insert
                    
                  }elseif(@$maininscl !="" || @$subinscl !=""){ 
                    $date_now2 = date('Y-m-d');
                    Pang_stamp_temp::where('vn', $vn) 
                    ->update([    
                        // 'status' => @$status,
                        // 'maininscl' => @$maininscl,
                        // 'startdate' => @$startdate,
                        // 'hmain' => @$hmain,
                        'check_sit_subinscl' => @$subinscl,
                        'pttype_stamp' => @$subinscl.'() 0000-00-00'
                        // 'pttype_stamp' => @$subinscl.' '.@$startdate

                        // 'hmain_op' => @$hmain_op,
                        // 'hmain_op_name' => @$hmain_op_name,
                        // 'hsub' => @$hsub,
                        // 'hsub_name' => @$hsub_name,
                        // 'subinscl_name' => @$subinscl_name 
                    ]); 
 
                  }

            }           
        }
        $data_sit = DB::connection('mysql5')->select(' 
            SELECT *
            FROM pang_stamp_temp  
            WHERE vstdate BETWEEN "'.$datestart.'" AND "'.$dateend.'"   
            AND pang_stamp IN("1102050101.401","1102050101.402","1102050102.801","1102050102.802","1102050102.803","1102050102.804");
        ');  
 
        return response()->json([
            'status'     => '200',
            'data_sit'    => $data_sit, 
            'start'     => $datestart, 
            'end'        => $dateend, 
        ]); 
    }
}
