<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Validator;
use App\Models\User;
use App\Models\Department;
use App\Models\Departmentsub;
use App\Models\Departmentsubsub;
use App\Models\Products_vendor;
use App\Models\Status;
use App\Models\Position;
use App\Models\Products_request;
use App\Models\Products_request_sub;
use App\Models\Products;
use App\Models\Products_type;
use App\Models\Product_group;
use App\Models\Product_unit;
use App\Models\Products_category;
use App\Models\Leave_leader;
use App\Models\Leave_leader_sub;
use App\Models\Line_token;
use DataTables;

class PermissController extends Controller
{  
 
public function permiss(Request $request)
{       
    $data['users'] = User::get();
    return view('setting.permiss',$data);
}
public function permiss_liss(Request $request,$id)
{    
    $data['users'] = User::get();
    $dataedit = User::join('users_prefix','users_prefix.prefix_code','=','users.pname')
    ->where('users.id','=',$id)->first();

    return view('setting.permiss_liss',$data,[
       'dataedits'    =>   $dataedit 
    ]);
}
public function permiss_save(Request $request)
{         
      
        $id = $request->input('id'); 
        $update = User::find($id);
        $update->permiss_person = $request->input('permiss_person'); 
        $update->permiss_gleave = $request->input('permiss_gleave'); 
        $update->permiss_book = $request->input('permiss_book'); 
        $update->permiss_car = $request->input('permiss_car'); 
        $update->permiss_meetting = $request->input('permiss_meetting'); 
        $update->permiss_repair = $request->input('permiss_repair'); 
        $update->permiss_com = $request->input('permiss_com'); 
        $update->permiss_medical = $request->input('permiss_medical'); 
        $update->permiss_plan = $request->input('permiss_plan'); 
        $update->permiss_hosing = $request->input('permiss_hosing'); 
        $update->permiss_asset = $request->input('permiss_asset'); 
        $update->permiss_supplies = $request->input('permiss_supplies'); 
        $update->permiss_store = $request->input('permiss_store'); 
        $update->permiss_store_dug = $request->input('permiss_store_dug'); 
        $update->permiss_pay = $request->input('permiss_pay');  
        $update->permiss_money = $request->input('permiss_money'); 
        $update->permiss_claim = $request->input('permiss_claim'); 
        $update->permiss_medicine = $request->input('permiss_medicine'); 
        $update->permiss_ot = $request->input('permiss_ot'); 
        $update->permiss_p4p = $request->input('permiss_p4p'); 
        $update->permiss_timeer = $request->input('permiss_timeer'); 

        $update->permiss_env = $request->input('permiss_env'); 
        $update->permiss_account = $request->input('permiss_account'); 
        $update->save();    
        return response()->json([
            'status'     => '200'
    ]);
}
public function permiss_edit(Request $request,$id)
{    
    // $data['department'] = Department::all();
    $data['department'] = Department::leftJoin('users','department.LEADER_ID','=','users.id')->orderBy('DEPARTMENT_ID','DESC')
    // ->select('users.*', 'department.DEPARTMENT_ID', 'department.DEPARTMENT_NAME', 'department.LINE_TOKEN')
    ->get();
    $data['users'] = User::get();
    $dataedit = Department::where('DEPARTMENT_ID','=',$id)->first(); 

    return view('setting.permiss_edit',$data,[
        'dataedits'=>$dataedit
    ]);
}
public function permiss_update(Request $request)
{      
    $iddep = $request->DEPARTMENT_ID; 

    $update = Department::find($iddep);
    $update->DEPARTMENT_NAME = $request->input('DEPARTMENT_NAME'); 
    $update->LINE_TOKEN = $request->input('LINE_TOKEN'); 

    $iduser = $request->input('LEADER_ID'); 
    if ($iduser != '') {
        $usersave = DB::table('users')->where('id','=',$iduser)->first();
        $update->LEADER_ID = $usersave->id; 
        $update->LEADER_NAME = $usersave->fname. '  ' .$usersave->lname ; 
    } else {
        $update->LEADER_ID = ''; 
        $update->LEADER_NAME =''; 
    }
    
    $update->save(); 
        
    return response()->json([
        'status'     => '200'
        ]);
 
}

public function permiss_destroy(Request $request,$id)
{
   $del = Department::find($id); 

   $del->delete(); 
    return response()->json(['status' => '200','success' => 'Delete Success']);
}
function switchpermiss_person(Request $request)
{ 
    $id = $request->person;
    $active = User::find($id);
    $active->permiss_person = $request->onoff;
    $active->save();
}
function switchpermiss_book(Request $request)
{ 
    $id = $request->book;
    $active = User::find($id);
    $active->permiss_book = $request->onoff;
    $active->save();
}
function switchpermiss_car(Request $request)
{ 
    $id = $request->car;
    $active = User::find($id);
    $active->permiss_car = $request->onoff;
    $active->save();
}
function switchpermiss_meetting(Request $request)
{ 
    $id = $request->meetting;
    $active = User::find($id);
    $active->permiss_meetting = $request->onoff;
    $active->save();
}
function switchpermiss_repair(Request $request)
{ 
    $id = $request->repaire;
    $active = User::find($id);
    $active->permiss_repair = $request->onoff;
    $active->save();
}



}