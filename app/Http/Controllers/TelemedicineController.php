<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Validator;
use App\Models\User;
use App\Models\m_registerdata;
use PDF;
use setasign\Fpdi\Fpdi;
use App\Models\Budget_year;
use Illuminate\Support\Facades\File;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
use Rector\Config\RectorConfig;
use Rector\PHPOffice\Set\PHPOfficeSetList;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPExcel_IOFactory;
use App\Models\Stm_head;
use App\Models\Stm_import;
/** PHPExcel */
// require_once '../upstm/Classes/PHPExcel.php';

/** PHPExcel_IOFactory - Reader */
// include '../upstm/Classes/PHPExcel/IOFactory.php';

class TelemedicineController extends Controller
{
    public function telemedicine(Request $request)
    {
        $data['com_tec'] = DB::table('com_tec')->get();
        $data['users'] = User::get();

        $claimcode = $request->claimcode;
        $hn = $request->hn;
        // $data = DB::select('select ci_code,ci_hn,ci_pid,ci_fullname from karn_ci where created_at between "'.$newDate.'" and "'.$date.'"');
       $data['claim'] =  DB::connection('mysql4')->select('SELECT * FROM m_registerdata 
                    WHERE HN ="'.$hn.'" 
                    ');

        return view('telemed.telemedicine', $data,[
            'claimcode' =>   $claimcode,
            'hn'        =>   $hn
            ]);
    }
    // public function eclaim_check_update(Request $request)
    // { 
    //         $hn = $request->HN; 
    //         DB::connection('mysql4')->table('m_registerdata')
    //         ->where('HN','=', $hn)
    //         ->update([
    //             'STATUS' => $request->ECLAIM_STATUS 
    //         ]);   

    //         return response()->json([
    //             'status'             => '200'
    //         ]);
    // }
    public function import_stm(Request $request)
    { 
        $data['users'] = User::get();
        $inputFileName = 'sample.xlsx';
        
                // $tar_file = "/Export_Claim/10978_SOCDSTM_20221202/10978_SOCDSTM_20221202.xml";

                // $xmlString = file_get_contents(public_path($tar_file));
                // $xmlObject = simplexml_load_string($xmlString);
                           
                // $json = json_encode($xmlObject);
                // $result = json_decode($json, true); 
           
                // // dd($result);
                // @$stmAccountID = $result['stmAccountID'];
                // @$hcode = $result['hcode'];
                // @$hname = $result['hname'];
                // @$AccPeriod = $result['AccPeriod'];
                // @$STMdoc = $result['STMdoc']; //ชื่อไฟล์
                // @$dateStart = $result['dateStart']; //"5  ธันวาคม  2565"
                // @$dateEnd = $result['dateEnd'];  //"18  ธันวาคม  2565"
                // @$dateData = $result['dateData'];  //"3  มกราคม  2566"
                // @$dateIssue = $result['dateIssue'];  //"4  มกราคม  2566"
                // @$acount = $result['acount'];  //"102"
                // @$amount = $result['amount'];  //"192100.0000"
                // @$thamount = $result['thamount'];  // "หนึ่งแสนเก้าหมื่นสองพันหนึ่งร้อยบาทถ้วน"
               
                // @$STMdat = $result['STMdat'];  // array
                // @$HDBills = $result['HDBills'];  // array
                // @$Remarks = $result['Remarks'];  // array
                // @$config = $result['config'];  // array
               
                // $hd_bills        = @$HDBills;
                // // dd($config);
                // $billaray = [];
                // foreach ($hd_bills as $key => $value2) {
                //     $hreg = $value2[0]['hreg']; 
                //     $hn = $value2[0]['hn']; 
                //     $name = $value2[0]['name']; 
                //     $pid = $value2[0]['pid']; 
                //     $wkno = $value2[0]['wkno']; 
                //     $hds = $value2[0]['hds']; 
                //     $quota = $value2[0]['quota']; 
                //     $hdcharge = $value2[0]['hdcharge']; 
                //     $payable = $value2[0]['payable']; 
                //     $outstanding = $value2[0]['outstanding']; 

                //     $effHDs = $value2[0]['EPO']['effHDs'];     //EPO   
                //     $effHCT = $value2[0]['EPO']['effHCT'];     //EPO
                //     $epoPay = $value2[0]['EPO']['epoPay'];     //EPO
                //     $epoAdm = $value2[0]['EPO']['epoAdm'];     //EPO             
                //     // dd($epoPay);

                //     $TBill = $value2[0]['TBill']; 
                //     foreach ($TBill as $key => $val) {
                //         $hcode = $val['hcode'];
                //         $station = $val['station'];
                //         $wkno = $val['wkno'];
                //         $hreg = $val['hreg'];
                //         $hn = $val['hn'];
                //         $invno = $val['invno'];
                //         $dttran = $val['dttran'];
                //         $hdrate = $val['hdrate'];
                //         $hdcharge = $val['hdcharge'];
                //         $amount = $val['amount'];
                //         $paid = $val['paid'];
                //         $rid = $val['rid'];
                //         $accp = $val['accp'];
                //     }
                //     // dd($invno);
 
                // }
                // dd($invno);
                // dd($TBill);
                // dd($EPO);
                // dd($invno);
        return view('telemed.import_stm', $data);
    }
    public function import_stm_save(Request $request)
    { 

                $tar_file = $request->file_;
                // $file = Input::file('file_')->getClientOriginalName();
                $file = $request->file('file_')->getClientOriginalName();

                $filename = pathinfo($file, PATHINFO_FILENAME);
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                // if ($request->hasfile('file_')) {
                    // $file = $request->file('file_');
                    // $extention = $file->getClientOriginalExtension();
                    // $name = $file->originalName();
                $tar_file = "/Export_Claim/stm/".$file;
                // foreach ($file as $key => $i) {
                //     $name = $i->originalName;
                // }
                    
                    // dd($extension);

                $xmlString = file_get_contents(public_path($tar_file));
                $xmlObject = simplexml_load_string($xmlString);
                $json = json_encode($xmlObject);
                // $json = json_encode($file);
                $result = json_decode($json, true); 
           
                // dd($result);
                @$stmAccountID = $result['stmAccountID'];
                @$hcode = $result['hcode'];
                @$hname = $result['hname'];
                @$AccPeriod = $result['AccPeriod'];
                @$STMdoc = $result['STMdoc']; //ชื่อไฟล์
                @$dateStart = $result['dateStart']; //"5  ธันวาคม  2565"
                @$dateEnd = $result['dateEnd'];  //"18  ธันวาคม  2565"
                @$dateData = $result['dateData'];  //"3  มกราคม  2566"
                @$dateIssue = $result['dateIssue'];  //"4  มกราคม  2566"
                @$acount = $result['acount'];  //"102"
                @$amount = $result['amount'];  //"192100.0000"
                @$thamount = $result['thamount'];  // "หนึ่งแสนเก้าหมื่นสองพันหนึ่งร้อยบาทถ้วน"
               
                @$STMdat = $result['STMdat'];  // array
                @$HDBills = $result['HDBills'];  // array
                @$Remarks = $result['Remarks'];  // array
                @$config = $result['config'];  // array
                
                $dates = date('Y-m-d H:m:s');
                Stm_head::insert([                        
                    'stmAccountID'       => $stmAccountID, 
                    'hcode'              => $hcode,
                    'hname'              => $hname,
                    'AccPeriod'          => $AccPeriod,
                    'STMdoc'             => $STMdoc.'.'.$extension,
                    'dateStart'          => $dateStart,
                    'dateEnd'            => $dateEnd,
                    'dateData'           => $dateData,
                    'dateIssue'          => $dateIssue,
                    'acount'             => $acount,
                    'amount'             => $amount,
                    'thamount'           => $thamount,
                    'created_at'         => $dates
                ]);                
                    // dd($hcode);                
                    $hd_bills        = @$HDBills;
                    // dd($hd_bills);                   
                    foreach ($hd_bills as $key => $value2) {
                        foreach ($value2 as $v) {
                            $hreg = $v["hreg"];
                            $hn = $v["hn"];
                            $name = $v["name"];
                            $pid = $v["pid"];
                            $wkno = $v["wkno"];
                            $hds = $v["hds"];
                            $quota = $v["quota"];
                            $hdcharge = $v["hdcharge"];
                            $payable = $v["payable"];
                            $outstanding = $v["outstanding"];
                            $effHDs = $v['EPO']['effHDs'];     //EPO  
                            $effHCT = $v['EPO']['effHCT'];     //EPO   
                            $epoPay = $v['EPO']['epoPay'];     //EPO  
                            $epoAdm = $v['EPO']['effHDs'];     //EPO 
                            $tbil = $v['TBill'];     //TBill 
                            // dd($tbil );
                            foreach ($tbil as $key => $item) { 
                                    $hcode = $item["hcode"];
                                    $station = $item["station"];
                                    $wkno = $item['wkno'];
                                    $hreg = $item['hreg'];
                                    // $hn = $item['hn'];
                                    $invno = $item['invno'];
                                    $dttran = $item['dttran'];                                  
                                    $ex_dttran = explode("T",$item['dttran']);
                                    $dttran1 = $ex_dttran[0];
                                    $dttran2 = $ex_dttran[1];
                                    // $y = date("Y")+543;
                                    $ye = date('Y',strtotime($dttran1))+543;
                                    $y = substr($ye, -2);
                                    $m = date('m',strtotime($dttran1));
                                    $d = date('d',strtotime($dttran1));
                                    $dttran_1 =  $y.''.$m.''.$d;
                                    // dd($d);
                                    // $sss_date_now = date("Y-m-d");
                                    $time_now = date("H:i:s");
                                     #ตัดขีด, ตัด : ออก
                                    $pattern_date = '/-/i';
                                    $aipn_date_now_preg = preg_replace($pattern_date, '', $dttran1);
                                    $pattern_time = '/:/i';
                                    $aipn_time_now_preg = preg_replace($pattern_time, '', $dttran2);
                                    #ตัดขีด, ตัด : ออก
                                    // $year = substr(date("Y"),2) +43;
                                    // $mounts = date('m');
                                    // $day = date('d');
                                    // $time = date("His");  
                                    // $vn = $year.''.$mounts.''.$day.''.$time;
                                    $hdrate = $item['hdrate'];
                                    $hdcharge2 = $item['hdcharge'];
                                    $amount = $item['amount'];
                                    $paid = $item['paid'];
                                    $rid = $item['rid'];
                                    $accp = $item['accp'];
                                // dd($invno);
                            }
                            $maxid = Stm_head::max('stm_head_id');
                            Stm_import::insert([                        
                                'stm_head_id'        => $maxid, 
                                'hreg'               => $hreg,
                                'hn'                 => $hn,
                                'name'               => $name,
                                'pid'                => $pid,
                                'wkno'               => $wkno,
                                'hds'                => $hds,
                                'quota'              => $quota,
                                'hdcharge'           => $hdcharge,
                                'payable'            => $payable,
                                'outstanding'        => $outstanding ,                                
                                'effHDs'             => $effHDs,
                                'effHCT'             => $effHCT,
                                'epoPay'             => $epoPay,
                                'epoAdm'             => $epoAdm,
                                'invno'              => $invno,

                                'vn'             => $dttran_1,
                                // 'dttran'             => $dttran1.''.$dttran2,

                                'dttran'             => $dttran1,

                                'hcode'              => $hcode,
                                'station'            => $station,
                                'hdrate'             => $hdrate,
                                'tbill_hdcharge'     => $hdcharge2,
                                'amount'               => $amount,
                                'paid'               => $paid,
                                'rid'                => $rid,
                                'accp'               => $accp,
                                'created_at'         => $dates
                            ]);                            
                            // dd($effHDs );
                        }
                    }

              
                    // $tbill_data = $value2[0]['TBill']; 
                    // dd($tbill_data );
                    //     foreach ($tbill_data as $key => $val) {
                            // foreach ($val as $bt) {
                                // $hcode = $bt["hcode"];
                                // $station = $bt["station"];
                                // $wkno = $bt['wkno'];
                                // $hreg = $bt['hreg'];
                                // $hn = $bt['hn'];
                                // $invno = $bt['invno'];
                                // $dttran = $bt['dttran'];
                                // $hdrate = $bt['hdrate'];
                                // $hdcharge = $bt['hdcharge'];
                                // $amount = $bt['amount'];
                                // $paid = $bt['paid'];
                                // $rid = $bt['rid'];
                                // $accp = $bt['accp'];
    
                                // $EPOiu = $v['EPOs']['EPOiu'];//EPOs  
                                // $LN = $v['EPOs']['LN'];//EPOs 
                                // $iu = $v['EPOs']['iu'];//EPOs 
                                // $item = $v['EPOs']['item'];//EPOs 
                                // $epoPay = $v['EPOs']['epoPay'];//EPOs 
                                // $epoclaim = $v['EPOs']['epoclaim'];//EPOs 
                                // Stm_import::insert([                        
                                //     'stm_head_id'        => $maxid, 
                                //     'hreg'               => $hreg,
                                //     'hn'                 => $hn,
                                //     'name'               => $name,
                                //     'pid'                => $pid,
                                //     'wkno'               => $wkno,
                                //     'hds'                => $hds,
                                //     'quota'              => $quota,
                                //     'hdcharge'           => $hdcharge,
                                //     'payable'            => $payable,
                                //     'outstanding'        => $outstanding ,                                
                                //     'effHDs'             => $effHDs,
                                //     'effHCT'             => $effHCT,
                                //     'epoPay'             => $epoPay,
                                //     'epoAdm'             => $epoAdm
                                // ]);      
                            // }
                            // $hcode = $val['hcode'];
                            // $station = $val['station'];
                            
                        // }





                        // $array_hdbills["hreg"] = $value2[$i++]["hreg"];
                        // $array_hdbills["hn"] = $value2[$i]["hn"];
                        // $array_hdbills["name"] = $value2[$i]["name"];
                        // $array_hdbills["pid"] = $value2[$i]["pid"];
                        // $array_hdbills["wkno"] = $value2[$i]["wkno"];
                        // $array_hdbills["hds"] = $value2[$i]["hds"];
                        // $array_hdbills["quota"] = $value2[$i]["quota"];
                        // $array_hdbills["hdcharge"] = $value2[$i]["hdcharge"];
                        // $array_hdbills["payable"] = $value2[$i]["payable"];
                        // $array_hdbills["outstanding"] = $value2[$i]["outstanding"]; 
                        // $i++;

                        // dd( $array_hdbills["pid"]);
                        // $hreg =  $value2[$i]["hreg"]; 
                        // $hn = $value2[$i]["hn"]; 
                        // $name = $value2[$i]["name"]; 
                        // $pid = $value2[$i]["pid"]; 
                        // $wkno = $value2[$i]["wkno"]; 
                        // $hds = $value2[$i]["hds"]; 
                        // $quota = $value2[$i]["quota"]; 
                        // $hdcharge =  $value2[$i]["hdcharge"] ; 
                        // $payable = $value2[$i]["payable"]; 
                        // $outstanding = $value2[$i]["outstanding"]; 
 
                        // $hreg = $value2[0]['hreg']; 
                        // $hn = $value2[0]['hn']; 
                        // $name = $value2[0]['name']; 
                        // $pid = $value2[0]['pid']; 
                        // $wkno = $value2[0]['wkno']; 
                        // $hds = $value2[0]['hds']; 
                        // $quota = $value2[0]['quota']; 
                        // $hdcharge = $value2[0]['hdcharge']; 
                        // $payable = $value2[0]['payable']; 
                        // $outstanding = $value2[0]['outstanding']; 

                        // $effHDs = $value2[0]['EPO']['effHDs'];     //EPO   
                        // $effHCT = $value2[0]['EPO']['effHCT'];     //EPO
                        // $epoPay = $value2[0]['EPO']['epoPay'];     //EPO
                        // $epoAdm = $value2[0]['EPO']['epoAdm'];     //EPO    
                        // dd($epoPay);

                        

                        // Stm_import::insert([                        
                        //     'stm_head_id'        => '', 
                        //     'hreg'               =>   $hreg,
                            // 'hn'                 => $hn,
                            // 'name'               => $name,
                            // 'pid'                => $pid,
                            // 'wkno'               => $wkno,
                            // 'hds'                => $hds,
                            // 'quota'              => $quota,
                            // 'hdcharge'           => $hdcharge,
                            // 'payable'            => $payable,
                            // 'outstanding'        => $outstanding ,
                            
                            // 'effHDs'             => $effHDs,
                            // 'effHCT'             => $effHCT,
                            // 'epoPay'             => $epoPay,
                            // 'epoAdm'             => $epoAdm
                        // ]);





                    // }
               
                     
                  
                // dd($hd_bills);
                // dd($TBill);
                // dd($EPO);
                // dd($invno);
    //    }
        // return view('telemed.import_stm');
        return redirect()->back();
    }
}

