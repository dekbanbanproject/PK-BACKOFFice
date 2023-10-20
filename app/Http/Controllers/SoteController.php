<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Validator;
use App\Models\User;

use App\Models\Plan_mission;
use App\Models\Plan_strategic;
use App\Models\Plan_taget;
use App\Models\Plan_kpi;
use App\Models\Department_sub_sub;
use PDF;
use setasign\Fpdi\Fpdi;
use App\Models\Budget_year;
use Illuminate\Support\Facades\File;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;

use App\Models\Audiovisual;
use App\Models\Audiovisual_type;

class SoteController extends Controller
{
    public function audiovisual_work(Request $request)
    {
        $data['startdate'] = $request->startdate;
        $data['enddate'] = $request->enddate;  
        $data['users'] = User::get();
        $data['department_sub_sub'] = DB::table('department_sub_sub')->get();
        $data['audiovisual_type'] = DB::table('audiovisual_type')->get();
        $data['audiovisual'] = DB::connection('mysql')->select('
            SELECT * 
            from audiovisual a
            LEFT JOIN users i on i.id = a.ptname
            LEFT JOIN audiovisual_type b on b.audiovisual_type_id = a.audiovisual_type
            left JOIN department_sub_sub d on d.DEPARTMENT_SUB_SUB_ID = a.department 
        ');

        return view('sote.audiovisual_work', $data);
    }
    public static function refnumber()
    {
        $year = date('Y');
        $maxnumber = DB::table('audiovisual')->max('audiovisual_id');  
        if($maxnumber != '' ||  $maxnumber != null){
            $refmax = DB::table('audiovisual')->where('audiovisual_id','=',$maxnumber)->first();  
            if($refmax->billno != '' ||  $refmax->billno != null){
            $maxref = substr($refmax->billno, -4)+1;
            }else{
            $maxref = 1;
            }
            $ref = str_pad($maxref, 5, "0", STR_PAD_LEFT);
        }else{
            $ref = '00001';
        }
        $ye = date('Y')+543;
        $y = substr($ye, -2);
        $refnumber = 'SOTE'.'-'.$ref;
        return $refnumber;
    }
    public function audiovisual_work_save(Request $request)
    {
        $add = new Audiovisual();
        $add->ptname                    = $request->input('ptname');
        $add->tel                       = $request->input('tel');
        $add->work_order_date           = $request->input('work_order_date'); 
        $add->job_request_date          = $request->input('job_request_date'); 
        $add->department                = $request->input('department'); 
        $add->audiovisual_type          = $request->input('audiovisual_type'); 
        $add->audiovisual_name          = $request->input('audiovisual_name'); 
        $add->audiovisual_qty           = $request->input('audiovisual_qty'); 
        $add->audiovisual_detail        = $request->input('audiovisual_detail'); 
        $add->save();

        return response()->json([
            'status'     => '200',
        ]);
    }

    // public function plan_project(Request $request)
    // {
    //     $data['com_tec'] = DB::table('com_tec')->get();
    //     $data['users'] = User::get();

    //     return view('plan.plan_project', $data);
    // }
    // public function plan_project_add(Request $request)
    // {
    //     $data['budget_year'] = DB::table('budget_year')->get();
    //     $data['users'] = User::get();

    //     return view('plan.plan_project_add', $data);
    // }



 

    // ********************************************

    // public function plan_taget(Request $request,$id)
    // {
    //     $data_plan_strategic = Plan_strategic::where('plan_strategic_id','=',$id)->first();
    //     $data['plan_strategic'] = Plan_strategic::leftjoin('plan_mission','plan_mission.plan_mission_id','=','plan_strategic.plan_mission_id')->get();
    //     // plan_taget
    //     $data['plan_taget'] = Plan_taget::where('plan_strategic_id','=',$id)->get();

    //     return view('plan.plan_taget', $data,[
    //         'data_plan_strategic'       =>       $data_plan_strategic
    //     ]);
    // }
   
    // public function plan_taget_update(Request $request)
    // { 
    //     $id = $request->input('editplan_taget_id');

    //     $update = Plan_taget::find($id);
    //     $update->plan_strategic_id = $request->input('editplan_strategic_id');
    //     $update->plan_taget_code = $request->input('editplan_taget_code');
    //     $update->plan_taget_name = $request->input('editplan_taget_name'); 
    //     $update->save();

    //     return response()->json([
    //         'status'     => '200',
    //     ]);
    // }
 
     
}
