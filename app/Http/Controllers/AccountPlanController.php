<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Validator;
use App\Models\User;
use App\Models\Account_main;
use App\Models\Account_percen;
use App\Models\Account_listpercen;
use App\Models\Account_monthlydebt;
use App\Models\Account_creditor;
use App\Models\Plan_type;
use App\Models\Plan_vision;
use App\Models\Plan_mission;
use App\Models\Plan_strategic;
use App\Models\Plan_taget;
use App\Models\Plan_kpi;
use App\Models\Department_sub_sub;
use App\Models\Departmentsub; 
use App\Models\Plan_control_type;
use App\Models\Plan_control;
use App\Models\Plan_control_money;
use App\Models\Plan_control_obj;
use App\Models\Plan_control_kpi;
use App\Models\Plan_control_activity;
use App\Models\Plan_control_budget;
use App\Models\Plan_list_budget;
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
date_default_timezone_set("Asia/Bangkok");


class AccountPlanController extends Controller
{
    public function account_plane(Request $request)
    {
        $startdate                  = $request->startdate;
        $enddate                    = $request->enddate;
        $departmentsub              = $request->departmentsub;
        $data['com_tec']            = DB::table('com_tec')->get();
        $data['users']              = User::get();
        $data['department_sub']     = Departmentsub::get();
        $data['department_sub_sub'] = Department_sub_sub::get();
        $data['plan_control_type']  = Plan_control_type::get();
        $data['plan_strategic']     = Plan_strategic::get();
        
        // $data['plan_control'] = Plan_control::get();
        $data['plan_control'] = DB::connection('mysql')->select('
            SELECT 
            plan_control_id,billno,plan_obj,plan_name,plan_reqtotal,pt.plan_control_typename,p.plan_price,p.plan_starttime,p.plan_endtime,p.`status`,s.DEPARTMENT_SUB_SUB_NAME
            ,p.plan_price_total,p.plan_req_no
            FROM
            plan_control p
            LEFT OUTER JOIN department_sub_sub s ON s.DEPARTMENT_SUB_SUB_ID = p.department
            LEFT OUTER JOIN plan_control_type pt ON pt.plan_control_type_id = p.plan_type
            WHERE p.department = "'.$departmentsub.'" AND p.plan_starttime BETWEEN "'.$startdate.'" AND "'.$enddate.'"
            ORDER BY p.plan_control_id ASC
        ');    
 
        return view('account.account_plane',$data, [ 
            'startdate'        =>  $startdate,
            'enddate'          =>  $enddate,
            'departmentsub'    =>  $departmentsub,
        ]);
    }

    public function account_plane_activity(Request $request,$id)
    {
        $data['startdate']             = $request->startdate;
        $data['enddate']               = $request->enddate;
     
        $data['plan_control']          = Plan_control::where('plan_control_id',$id)->first();
        $data['plan_control_activity'] = Plan_control_activity::where('plan_control_id',$id)->get();
        $data_activity                 = Plan_control_activity::where('plan_control_id',$id)->first();
        $data['plan_control_budget']   = Plan_control_budget::where('plan_control_id',$id)->get();

        $data['department_sub']        = Departmentsub::get();
        $data['department_sub_sub']    = Department_sub_sub::get();
        $data['plan_control_type']     = Plan_control_type::get();
        $data['plan_strategic']        = Plan_strategic::get();
        $data['plan_list_budget']      = Plan_list_budget::get();  
        $data['plan_unit']             = DB::table('plan_unit')->get();  
        $data['users']                 = User::get();    
        
        return view('account.account_plane_activity', $data,[ 
            'id'    =>  $id  // plan_control_id
        ]);
    }
    public function account_plane_payedit(Request $request,$id)
    { 
        $data_show = Plan_control_activity::where('plan_control_activity_id',$id)->first();
       
        return response()->json([
            'status'               => '200', 
            'data_show'            =>  $data_show,
        ]);
    }
     public function account_plane_pay(Request $request)
     { 
         $maxno_ = Plan_control_money::where('plan_control_id',$request->input('update_plan_control_id'))->max('plan_control_money_no');
         $maxno = $maxno_+1;
         $add = new Plan_control_money();
         $add->plan_control_id                = $request->input('update_plan_control_id'); 
         $add->plan_control_money_no          = $maxno;
         $add->plan_control_moneydate         = $request->input('plan_control_moneydate');
         $add->plan_control_moneyprice        = $request->input('plan_control_moneyprice');
         $add->plan_control_moneyuser_id      = $request->input('plan_control_moneyuser_id');
         $add->plan_control_moneycomment      = $request->input('plan_control_moneycomment'); 
         $add->save();
 
         $planid = $request->input('update_plan_control_id');
         $check_price = Plan_control::where('plan_control_id',$planid)->first();
         // $maxno_ = Plan_control::where('plan_control_id',$request->input('update_plan_control_id'))->max('plan_control_money_no');
 
         $check = Plan_control::where('plan_control_id',$planid)->count();
         // dd($request->plan_price);
         if ($check > 0) {
             Plan_control::where('plan_control_id',$planid)->update([
                 'plan_req_no'        =>  ($check_price->plan_req_no) + 1,
                 'plan_reqtotal'      =>  ($check_price->plan_reqtotal) + ($request->input('plan_control_moneyprice')),
                 'plan_price_total'   =>  ($check_price->plan_price) - (($check_price->plan_reqtotal) + ($request->input('plan_control_moneyprice')))
             ]);
         } else {              
         }         
         return response()->json([
             'status'     => '200',
         ]);
     }
    




}
