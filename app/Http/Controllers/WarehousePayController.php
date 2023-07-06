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
use App\Models\Position;
use App\Models\Product_spyprice;
use App\Models\Products;
use App\Models\Products_type;
use App\Models\Product_group;
use App\Models\Product_unit;
use App\Models\Products_category;
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
use App\Models\Book_type;
use App\Models\Book_import_fam;
use App\Models\Book_signature;
use App\Models\Bookrep;
use App\Models\Book_objective;
use App\Models\Book_senddep;
use App\Models\Book_senddep_sub;
use App\Models\Book_send_person;
use App\Models\Book_sendteam;
use App\Models\Bookrepdelete;
use App\Models\Car_status;
use App\Models\Car_index;
use App\Models\Article_status;
use App\Models\Car_type;
use App\Models\Product_brand;
use App\Models\Medical_borrow;
use App\Models\Land;
use App\Models\Building;
use App\Models\Product_budget;
use App\Models\Product_method;
use App\Models\Product_buy;
use App\Models\Building_level;
use App\Models\Building_level_room;
use App\Models\Building_room_type;
use App\Models\Building_type;
use App\Models\Car_location;
use App\Models\Carservice_signature;
use App\Models\Car_service_personjoin;
use App\Models\Car_drive;
use App\Models\Com_repaire;
use App\Models\Com_repaire_signature;
use App\Models\Warehouse_pay;
use App\Models\Medical_repaire;

use DataTables;
use PDF;
use Auth;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Intervention\Image\ImageManagerStatic as Image;

class WarehousePayController extends Controller
{
    public static function refnumber()
    {
        $year = date('Y');
        $maxnumber = DB::table('warehouse_pay')->max('warehouse_pay_id');
        if ($maxnumber != '' ||  $maxnumber != null) {
            $refmax = DB::table('warehouse_pay')->where('warehouse_pay_id', '=', $maxnumber)->first();
            if ($refmax->warehouse_pay_code != '' ||  $refmax->warehouse_pay_code != null) {
                $maxref = substr($refmax->warehouse_pay_code, -5) + 1;
            } else {
                $maxref = 1;
            }
            $ref = str_pad($maxref, 6, "0", STR_PAD_LEFT);
        } else {
            $ref = '000001';
        }
        $ye = date('Y') + 543;
        $y = substr($ye, -2);
        $refnumber = 'PA'.$ye . '-' . $ref;
        return $refnumber;       
    }
    public function warehouse_pay(Request $request)
    {
        $data['budget_year'] = DB::table('budget_year')->get();
        $data['users'] = User::get();
        $data['products_vendor'] = Products_vendor::get();
        $data['warehouse_inven'] = DB::table('warehouse_inven')->get();
        $data['article_data'] = Article::where('article_data.article_status_id', '=', '3')
            ->leftjoin('product_brand', 'product_brand.brand_id', '=', 'article_data.article_brand_id')
            ->leftjoin('department_sub_sub', 'department_sub_sub.DEPARTMENT_SUB_SUB_ID', '=', 'article_data.article_deb_subsub_id')
            ->leftjoin('article_status', 'article_status.article_status_id', '=', 'article_data.article_status_id')
            // ->where('article_id', '=', $id)
            ->get();
        $data['warehouse_pay'] = DB::table('warehouse_pay')->get();

        $data['department_sub_sub'] = Department_sub_sub::get();

        $data['warehouse_pay'] = DB::connection('mysql')->select('
        select  wi.warehouse_inven_name,w.warehouse_pay_id,w.warehouse_pay_code,w.warehouse_pay_no_bill,
            w.warehouse_pay_po,w.warehouse_pay_type,w.warehouse_pay_fromuser_id,w.warehouse_pay_date,
            w.warehouse_pay_repuser_id,w.warehouse_pay_inven_id,w.warehouse_pay_frominven_id,w.warehouse_pay_status,
            w.warehouse_pay_send,w.warehouse_pay_total,w.store_id,w.warehouse_pay_year,u.fname,u.lname,wp.warehouse_pay_status_name
            
            from warehouse_pay w
            LEFT JOIN warehouse_inven wi on wi.warehouse_inven_id = w.warehouse_pay_inven_id
            LEFT JOIN users u on u.id = w.warehouse_pay_repuser_id
            LEFT JOIN warehouse_pay_status wp on wp.warehouse_pay_status_code = w.warehouse_pay_status
                
        ');

        // select m.medical_borrow_id,m.medical_borrow_active,m.medical_borrow_date,m.medical_borrow_backdate
        // ,a.article_name,d.DEPARTMENT_SUB_SUB_NAME,m.medical_borrow_qty,m.medical_borrow_debsubsub_id,a.article_num
        // ,m.medical_borrow_users_id,m.medical_borrow_backusers_id,a.medical_typecat_id,a.article_status_id
        // ,m.medical_borrow_typecat_id,mt.medical_typecatname
        // from warehouse_pay m
        // LEFT JOIN article_data a on a.article_id = m.medical_borrow_article_id
        // LEFT JOIN department_sub_sub d on d.DEPARTMENT_SUB_SUB_ID = m.medical_borrow_debsubsub_id 
        // LEFT JOIN medical_typecat mt on mt.medical_typecat_id = m.medical_borrow_typecat_id   
        // where m.medical_borrow_date between "' . $newDate . '" AND "' . $date . '"  
 
        return view('warehouse.warehouse_pay', $data);
    }
    public function warehouse_pay_edit(Request $request,$id)
    {
        $data['warehouse_pay'] = DB::table('warehouse_pay')->where('warehouse_pay_id','=',$id)->first();
        $data['budget_year'] = DB::table('budget_year')->get();
        $data['users'] = User::get();
        $data['products_vendor'] = Products_vendor::get();
        $data['warehouse_inven'] = DB::table('warehouse_inven')->get();
        $data['department_sub_sub'] = Department_sub_sub::get();
        return view('warehouse.warehouse_pay_edit',$data);
    }

    public function warehouse_paysave(Request $request)
    {
        $date = date("Y-m-d H:i:s");
        $artcle = $request->medical_repaire_article_id;
        $artcleid = Article::where('article_id', '=', $artcle)->first();

        DB::table('warehouse_pay')->insert([
            'warehouse_pay_code'         => $request->warehouse_pay_code,
            'warehouse_pay_no_bill'      => $request->warehouse_pay_no_bill,
            'warehouse_pay_year'         => $request->warehouse_pay_year,
            'warehouse_pay_fromuser_id'  => $request->warehouse_pay_fromuser_id,
            'warehouse_pay_repuser_id'   => $request->warehouse_pay_repuser_id,
            'warehouse_pay_date'         => $request->warehouse_pay_date,
            'warehouse_pay_frominven_id' => $request->warehouse_pay_frominven_id,
            'warehouse_pay_inven_id'     => $request->warehouse_pay_inven_id,
            'store_id'                   => $request->store_id,
            'warehouse_pay_status'       => 'pay',
            'created_at' => $date,
            'updated_at' => $date
        ]);
        
        return response()->json([
            'status'     => '200',
        ]);
    }

    public function warehouse_payupdate(Request $request)
    {
        $date = date("Y-m-d H:i:s");
        $pay_id = $request->warehouse_pay_id;
         
        Warehouse_pay::where('warehouse_pay_id', $pay_id) 
        ->update([
            'warehouse_pay_code'         => $request->warehouse_pay_code,
            'warehouse_pay_no_bill'      => $request->warehouse_pay_no_bill,
            'warehouse_pay_year'         => $request->warehouse_pay_year,
            'warehouse_pay_fromuser_id'  => $request->warehouse_pay_fromuser_id,
            'warehouse_pay_repuser_id'   => $request->warehouse_pay_repuser_id,
            'warehouse_pay_date'         => $request->warehouse_pay_date,
            'warehouse_pay_frominven_id' => $request->warehouse_pay_frominven_id,
            'warehouse_pay_inven_id'     => $request->warehouse_pay_inven_id,
            'store_id'                   => $request->store_id,
            'warehouse_pay_status'       => 'pay',
            'created_at' => $date,
            'updated_at' => $date
        ]);
        // DB::table('bookrep')
        // ->where('bookrep_id','=', $file_id)
        // ->update(['bookrep_file2' => null]);
         
        return response()->json([
            'status'     => '200',
        ]);
    }
    public function warehouse_pay_sub(Request $request,$id)
    {
        $data['warehouse_pay'] = DB::table('warehouse_pay')->where('warehouse_pay_id','=',$id)->first();
        $data['product_data'] = Products::where('product_groupid', '=', 1)->orwhere('product_groupid', '=', 2)->orderBy('product_id', 'DESC')->get();
        $data['products_typefree'] = DB::table('products_typefree')->get();
        $data['product_unit'] = DB::table('product_unit')->get();
      
        return view('warehouse.warehouse_pay_sub',$data);
    }
    public function warehouse_payadd(Request $request,$id)
    {
        $data['warehouse_pay'] = DB::table('warehouse_pay')->where('warehouse_pay_id','=',$id)->first();
        $data['budget_year'] = DB::table('budget_year')->get();
        $data['users'] = User::get();
        $data['products_vendor'] = Products_vendor::get();
        $data['warehouse_inven'] = DB::table('warehouse_inven')->get();
        $data['department_sub_sub'] = Department_sub_sub::get();

        $data['inven'] = DB::table('warehouse_pay')
        ->leftjoin('warehouse_inven','warehouse_inven.warehouse_inven_id','=','warehouse_pay.warehouse_pay_frominven_id')
        ->where('warehouse_pay_id','=',$id)->first();

        $data['product_data'] = Products::where('store_id', '=', Auth::user()->store_id)->orderBy('product_id', 'DESC')->get();
        $data['products_typefree'] = DB::table('products_typefree')->get();
        $data['product_unit'] = DB::table('product_unit')->get();

        return view('warehouse.warehouse_payadd',$data);
    }
    public function warehouse_addsave(Request $request)
    {
        $warehouse_rep_id    = $request->warehouse_rep_id;
        $store_id            = $request->store_id;
        $warehouse_inven_id  = $request->warehouse_inven_id; 

            if ($request->product_id != '' || $request->product_id != null) {
                $product_id = $request->product_id;
                $product_type_id = $request->product_type_id;
                $product_qty = $request->product_qty;
                $product_price = $request->product_price;
                $product_unit_subid = $request->product_unit_subid;
                $product_lot = $request->product_lot;
                $warehouse_rep_sub_exedate = $request->warehouse_rep_sub_exedate;
                $warehouse_rep_sub_expdate = $request->warehouse_rep_sub_expdate;
                $warehouse_rep_sub_status = $request->warehouse_rep_sub_status;

                $number = count($product_id);
                $count = 0;
                for ($count = 0; $count < $number; $count++) {

                    $idpro = DB::table('product_data')->where('product_id', '=', $product_id[$count])->first(); 
                    $maxcode = DB::table('warehouse_rep')->max('warehouse_rep_code');
                    $date = date("Y-m-d H:i:s");
                    $idtype = DB::table('products_typefree')->where('products_typefree_id','=', $product_type_id[$count])->first();
                    $idunit = DB::table('product_unit')->where('unit_id','=', $product_unit_subid[$count])->first();

                    $add2 = new Warehouse_rep_sub();
                    $add2->warehouse_rep_id = $warehouse_rep_id; 
                    $add2->product_id = $idpro->product_id;
                    $add2->product_code = $idpro->product_code;
                    $add2->product_name = $idpro->product_name;
                    $add2->product_type_id = $idtype->products_typefree_id;
                    $add2->product_type_name = $idtype->products_typefree_name;
                    $add2->product_unit_subid = $idunit->unit_id;
                    $add2->product_unit_subname = $idunit->unit_name;
                    $add2->product_lot = $product_lot[$count];
                    $add2->product_qty = $product_qty[$count];
                    $add2->product_price = $product_price[$count];
                    $add2->warehouse_rep_sub_exedate = $warehouse_rep_sub_exedate[$count];
                    $add2->warehouse_rep_sub_expdate = $warehouse_rep_sub_expdate[$count];
                    $add2->warehouse_rep_sub_status = $warehouse_rep_sub_status[$count];
                    $total = $product_qty[$count] * $product_price[$count];
                    $add2->product_price_total = $total;
                    $add2->save();


                }
                $sumrecieve  =  Warehouse_rep_sub::where('warehouse_rep_id','=',$warehouse_rep_id)->sum('product_price_total');
                $countsttus = DB::table('warehouse_rep_sub')->where('warehouse_rep_id', '=',$warehouse_rep_id)->where('warehouse_rep_sub_status', '=','2')->count();
                $update = Warehouse_rep::find($warehouse_rep_id);
                $update->warehouse_rep_total = $sumrecieve;
                if ($countsttus == '0') {
                    $update->warehouse_rep_send = 'FINISH';
                } else {
                    $update->warehouse_rep_send = 'STALE';
                }

                $update->save();

            }
            return response()->json([
                'status'     => '200'
            ]); 
    }

}
