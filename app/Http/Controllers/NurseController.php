<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Validator;
use App\Models\User;
use App\Models\Acc_debtor;
use App\Models\Pttype_eclaim;
use App\Models\Account_listpercen;
use App\Models\Leave_month;
use App\Models\Acc_debtor_stamp;
use App\Models\Acc_debtor_sendmoney;
use App\Models\Pttype;
use App\Models\Pttype_acc;
use App\Models\Acc_stm_ti;
use App\Models\Acc_stm_ti_total;
use App\Models\Acc_opitemrece;
use App\Models\Acc_1102050101_202;
use App\Models\Acc_1102050101_217;
use App\Models\Acc_1102050101_2166;
use App\Models\Acc_stm_ucs;
use App\Models\Acc_1102050101_301;
use App\Models\Acc_1102050101_304;
use App\Models\Acc_1102050101_308;
use App\Models\Acc_1102050101_4011;
use App\Models\Acc_1102050101_3099;
use App\Models\Acc_1102050101_401;
use App\Models\Acc_1102050101_402;
use App\Models\Acc_1102050102_801;
use App\Models\Acc_1102050102_802;
use App\Models\Acc_1102050102_803;
use App\Models\Acc_1102050102_804;
use App\Models\Acc_1102050101_4022;
use App\Models\Acc_1102050102_602;
use App\Models\Acc_1102050102_603;
use App\Models\Acc_stm_prb;
use App\Models\Acc_stm_ti_totalhead;
use App\Models\Acc_stm_ti_excel;
use App\Models\Acc_stm_ofc;
use App\Models\acc_stm_ofcexcel;
use App\Models\Acc_stm_lgo;
use App\Models\Acc_stm_lgoexcel;
use App\Models\Check_sit_auto;
use App\Models\Acc_stm_ucs_excel;
use App\Models\Document;
use App\Models\Nurse;
use PDF;
use setasign\Fpdi\Fpdi;
use App\Models\Budget_year;
use Illuminate\Support\Facades\File;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;
use App\Mail\DissendeMail;
use Mail;
use Illuminate\Support\Facades\Storage;
use Auth;
use Http;
use SoapClient;
// use File;
// use SplFileObject;
use Arr;
// use Storage;
use GuzzleHttp\Client;

use App\Imports\ImportAcc_stm_ti;
use App\Imports\ImportAcc_stm_tiexcel_import;
use App\Imports\ImportAcc_stm_ofcexcel_import;
use App\Imports\ImportAcc_stm_lgoexcel_import;
use App\Models\Acc_1102050101_217_stam;
use App\Models\Acc_opitemrece_stm;

use SplFileObject;
use PHPExcel;
use PHPExcel_IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

date_default_timezone_set("Asia/Bangkok");


class NurseController extends Controller
 {
    // ***************** NurseController********************************

    public function nurse_dashboard (Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $dabudget_year = DB::table('budget_year')->where('active','=',true)->first();
        $leave_month_year = DB::table('leave_month')->orderBy('MONTH_ID', 'ASC')->get();
        $date = date('Y-m-d');
        $y = date('Y') + 543;
        $newweek = date('Y-m-d', strtotime($date . ' -1 week')); //ย้อนหลัง 1 สัปดาห์
        $newDate = date('Y-m-d', strtotime($date . ' -5 months')); //ย้อนหลัง 5 เดือน
        $newyear = date('Y-m-d', strtotime($date . ' -1 year')); //ย้อนหลัง 1 ปี

        $data['datashow'] = DB::connection('mysql')->select('SELECT * FROM document WHERE active = "Y" AND (user_id <> "581" OR user_id ="" OR user_id IS NULL)');
        
        return view('nurse.nurse_dashboard',$data,[
            'startdate'        => $startdate,
            'enddate'          => $enddate,
            // 'data_doctor'      => $data_doctor,
           
        ]);
    }
    public function nurse_index (Request $request)
    {
        $startdate     = $request->startdate;
        $enddate       = $request->enddate;
        $data_insert   = $request->data_insert;
        $dabudget_year = DB::table('budget_year')->where('active','=',true)->first();
        $leave_month_year = DB::table('leave_month')->orderBy('MONTH_ID', 'ASC')->get();
        $date = date('Y-m-d');
        $y = date('Y') + 543;
        $newweek = date('Y-m-d', strtotime($date . ' -1 week')); //ย้อนหลัง 1 สัปดาห์
        $newDate = date('Y-m-d', strtotime($date . ' -5 months')); //ย้อนหลัง 5 เดือน
        $newyear = date('Y-m-d', strtotime($date . ' -1 year')); //ย้อนหลัง 1 ปี

        if ($data_insert == '') {
            # code...
        } else {
            Nurse::truncate();
            $datashow_ = DB::connection('mysql2')->select(
                'SELECT w.ward,w.name as ward_name,
                COUNT(DISTINCT a.an) as total_an,
                ROUND(COUNT(DISTINCT a.an) * 1.6 / 8, 2) as soot_a_total,
                ROUND(COUNT(DISTINCT a.an) * 1.44 / 8, 2) as soot_b_total,
                ROUND(COUNT(DISTINCT a.an) * 0.96 / 8, 2) as soot_c_total
                FROM an_stat a
                LEFT OUTER JOIN ward w on w.ward = a.ward
                WHERE a.dchdate is null AND w.ward is not null
                GROUP BY a.ward ORDER BY w.name
            ');
            foreach ($datashow_ as $key => $value) {
                    $add = new Nurse(); 
                    $add->datesave         = $date;
                    $add->ward             = $value->ward;
                    $add->ward_name        = $value->ward_name;
                    $add->count_an         = $value->total_an; 
                    $add->soot_a     = $value->soot_a_total;
                    $add->soot_b    = $value->soot_b_total;
                    $add->soot_c     = $value->soot_c_total;
                    $add->save();
            }
        }
        
       


        $data['datashow'] = DB::connection('mysql')->select('SELECT * FROM nurse');

        return view('nurse.nurse_index',$data,[
            'startdate'        => $startdate,
            'enddate'          => $enddate,
            // 'data_doctor'      => $data_doctor,
           
        ]);
    }

    public function nurse_index_process (Request $request)
    { 
        $data_insert   = $request->data_insert; 
        $date = date('Y-m-d');
         
        if ($data_insert == '1') {
            
            // Nurse::truncate();
            $datashow_ = DB::connection('mysql2')->select(
                'SELECT w.ward,w.name as ward_name,
                COUNT(DISTINCT a.an) as total_an,
                ROUND(COUNT(DISTINCT a.an) * 1.6 / 8, 2) as soot_a_total,
                ROUND(COUNT(DISTINCT a.an) * 1.44 / 8, 2) as soot_b_total,
                ROUND(COUNT(DISTINCT a.an) * 0.96 / 8, 2) as soot_c_total
                FROM an_stat a
                LEFT OUTER JOIN ward w on w.ward = a.ward
                WHERE a.dchdate is null AND w.ward is not null
                GROUP BY a.ward ORDER BY w.name
            ');
            foreach ($datashow_ as $key => $value) {
               $check = Nurse::where('datesave',$date)->where('ward',$value->ward)->count();
               if ($check > 0) {
                

                
                
               } else {
                    $add = new Nurse(); 
                    $add->datesave         = $date;
                    $add->ward             = $value->ward;
                    $add->ward_name        = $value->ward_name;
                    $add->count_an         = $value->total_an; 
                    $add->soot_a     = $value->soot_a_total;
                    $add->soot_b    = $value->soot_b_total;
                    $add->soot_c     = $value->soot_c_total;
                    $add->save();
               }
                
            }
        }
        return response()->json([
            'status'     => '200'
        ]);
        
    }
    // public function documentsub (Request $request,$id)
    // {
    //     $startdate = $request->startdate;
    //     $enddate = $request->enddate;
    //     $dabudget_year = DB::table('budget_year')->where('active','=',true)->first();
    //     $leave_month_year = DB::table('leave_month')->orderBy('MONTH_ID', 'ASC')->get();
    //     $date = date('Y-m-d');
    //     $y = date('Y') + 543;
    //     $newweek = date('Y-m-d', strtotime($date . ' -1 week')); //ย้อนหลัง 1 สัปดาห์
    //     $newDate = date('Y-m-d', strtotime($date . ' -5 months')); //ย้อนหลัง 5 เดือน
    //     $newyear = date('Y-m-d', strtotime($date . ' -1 year')); //ย้อนหลัง 1 ปี

    //     $data['datashow'] = DB::connection('mysql')->select('SELECT * FROM document WHERE active = "Y" AND user_id <> "581"');
    //     $dataedit = Document::where('document_id','=',$id)->first();
    //     $data_file =  $dataedit->img_name;
    //     // storage/air/'.$item->air_imgname
    //     return view('document.documentsub',$data,[
    //         'startdate'     => $startdate,
    //         'enddate'       => $enddate,
    //         'data_file'     => $data_file,
           
    //     ]);
    // }

    // public function document_save(Request $request)
    // {
    //     $date_now = date('Y-m-d'); 
    //     $iduser = Auth::user()->id;
    //     $add = new Document(); 
    //     $add->document_name     = $request->document_name;
    //     $add->hip_code          = $request->hip_code; 

    //     $maxid = Document::max('document_id');
    //     $nameid = $maxid + 1;

    //     if ($request->hasfile('img')) {
    //         $image_64 = $request->file('img');   
    //         $extention = $image_64->getClientOriginalExtension(); 
    //         $filename = 'document_' .$nameid. '.' . $extention;
    //         $request->img->storeAs('document', $filename, 'public');    
 
    //         $add->img        = $filename;
    //         $add->img_name   = $filename; 
    //         $add->img_file   = $extention; 
    //         $add->user_id    = $iduser;

    //         if ($extention =='.jpg') {
    //             $file64 = "data:image/jpg;base64,".base64_encode(file_get_contents($request->file('img'))); 
    //         } else {
    //             $file64 = "data:image/png;base64,".base64_encode(file_get_contents($request->file('img'))); 
    //         } 
    //         $add->img_base       = $file64; 
    //     }
         
    //     $add->save();
    //     return response()->json([
    //         'status'     => '200'
    //     ]);
     

       
        
    // }

    // public function document_update(Request $request)
    // {
    //     $date_now = date('Y-m-d'); 
    //     $id       = $request->document_id;
    //     $iduser   = Auth::user()->id;
    //     $update = Document::find($id); 
    //     $update->document_name     = $request->document_name;
    //     $update->hip_code          = $request->hip_code;  
    //     $nameid                    = $id;
 
    //     if ($request->hasfile('img')) {

    //         $description = 'storage/document/'.$update->img;
    //         if (File::exists($description)) {
    //             File::delete($description);
    //         }

    //         $image_64 = $request->file('img');   
    //         $extention = $image_64->getClientOriginalExtension(); 
    //         $filename = 'document_' .$nameid. '.' . $extention;
    //         $request->img->storeAs('document', $filename, 'public');    
 
    //         $update->img        = $filename;
    //         $update->img_name   = $filename; 
    //         $update->img_file   = $extention; 
    //         $update->user_id    = $iduser;

    //         if ($extention =='.jpg') {
    //             $file64 = "data:image/jpg;base64,".base64_encode(file_get_contents($request->file('img'))); 
    //         } else {
    //             $file64 = "data:image/png;base64,".base64_encode(file_get_contents($request->file('img'))); 
    //         } 
    //         $update->img_base       = $file64; 
    //     }
         
    //     $update->save();
    //     return response()->json([
    //         'status'     => '200'
    //     ]);
     

       
        
    // }

    // public function document_destroy(Request $request,$id)
    // {
    //     $del = Document::find($id);
    //     $description = 'storage/document/'.$del->img;
    //     if (File::exists($description)) {
    //         File::delete($description);
    //     }
    //     $del->delete();
    //     return response()->json(['status' => '200','success' => 'Delete Success']);
    // }

   

 }
