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

        return view('sote.audiovisual_work', $data);
    }
    public function plan_project(Request $request)
    {
        $data['com_tec'] = DB::table('com_tec')->get();
        $data['users'] = User::get();

        return view('plan.plan_project', $data);
    }
    public function plan_project_add(Request $request)
    {
        $data['budget_year'] = DB::table('budget_year')->get();
        $data['users'] = User::get();

        return view('plan.plan_project_add', $data);
    }



 

    // ********************************************

    public function plan_taget(Request $request,$id)
    {
        $data_plan_strategic = Plan_strategic::where('plan_strategic_id','=',$id)->first();
        $data['plan_strategic'] = Plan_strategic::leftjoin('plan_mission','plan_mission.plan_mission_id','=','plan_strategic.plan_mission_id')->get();
        // plan_taget
        $data['plan_taget'] = Plan_taget::where('plan_strategic_id','=',$id)->get();

        return view('plan.plan_taget', $data,[
            'data_plan_strategic'       =>       $data_plan_strategic
        ]);
    }
    public function plan_taget_save(Request $request)
    {
        $add = new Plan_taget();
        $add->plan_strategic_id = $request->input('plan_strategic_id');
        $add->plan_taget_code = $request->input('plan_taget_code');
        $add->plan_taget_name = $request->input('plan_taget_name'); 
        $add->save();

        return response()->json([
            'status'     => '200',
        ]);
    }
    public function plan_taget_update(Request $request)
    { 
        $id = $request->input('editplan_taget_id');

        $update = Plan_taget::find($id);
        $update->plan_strategic_id = $request->input('editplan_strategic_id');
        $update->plan_taget_code = $request->input('editplan_taget_code');
        $update->plan_taget_name = $request->input('editplan_taget_name'); 
        $update->save();

        return response()->json([
            'status'     => '200',
        ]);
    }

    // ********************************************
    public function plan_kpi(Request $request,$strategic_id,$taget_id)
    {
        $data_plan_strategic = Plan_strategic::where('plan_strategic_id','=',$strategic_id)->first();
        // $data['plan_strategic'] = Plan_strategic::leftjoin('plan_mission','plan_mission.plan_mission_id','=','plan_strategic.plan_mission_id')->get();
        // plan_taget
        $data_plan_taget = Plan_taget::where('plan_taget_id','=',$taget_id)->first();

        $data['plan_kpi'] = Plan_kpi::get();
        $data['budget_year'] = Budget_year::get();
        $data['dep_subsub'] = Department_sub_sub::get();
        $data['user'] = User::get();
        $yearnow = date('Y')+543;

        return view('plan.plan_kpi', $data,[
            'data_plan_strategic'       =>       $data_plan_strategic,
            'data_plan_taget'           =>       $data_plan_taget,
            'yearnow'                   =>       $yearnow
        ]);
    }
    public function plan_kpi_save(Request $request)
    {
        $add = new Plan_kpi();
        $add->plan_strategic_id = $request->input('plan_strategic_id');
        $add->plan_taget_id = $request->input('plan_taget_id');
        $add->plan_kpi_code = $request->input('plan_kpi_code');
        $add->plan_kpi_name = $request->input('plan_kpi_name'); 
        $add->plan_kpi_year = $request->input('leave_year_id'); 
        $add->save();
        
        return response()->json([
            'status'     => '200',
        ]);
    }
    public function plan_kpi_update(Request $request)
    { 
        $id = $request->input('editplan_kpi_id');

        $update = Plan_kpi::find($id);
        $update->plan_strategic_id = $request->input('editplan_strategic_id');
        $update->plan_taget_id = $request->input('editplan_taget_id');
        $update->plan_kpi_code = $request->input('editplan_kpi_code');
        $update->plan_kpi_name = $request->input('editplan_kpi_name'); 
        $update->plan_kpi_year = $request->input('editleave_year_id'); 
        $update->save();

        return response()->json([
            'status'     => '200',
        ]);
    }
    public function plan_kpi_destroy(Request $request, $id)
    {
        $del = Plan_kpi::find($id);
        $del->delete();
        return response()->json(['status' => '200']);
    }
     
}
