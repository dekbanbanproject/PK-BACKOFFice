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
use App\Models\Product_color;  
use App\Models\Land;
use App\Models\Building;
use App\Models\Product_budget;
use App\Models\Product_method;
use App\Models\Product_buy;
use App\Models\Acc_doc;
use App\Models\Acc_1102050102_106;
use App\Models\Acc_debtor;
use Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class Account106Controller extends Controller
{
     // ************ OPD 106******************
    public function acc_106_dashboard(Request $request)
    { 
        $dabudget_year = DB::table('budget_year')->where('active','=',true)->first();
        $leave_month_year = DB::table('leave_month')->orderBy('MONTH_ID', 'ASC')->get();
        $date = date('Y-m-d');
        $y = date('Y') + 543;
        $newweek = date('Y-m-d', strtotime($date . ' -1 week')); //ย้อนหลัง 1 สัปดาห์
        $newDate = date('Y-m-d', strtotime($date . ' -5 months')); //ย้อนหลัง 5 เดือน
        $newyear = date('Y-m-d', strtotime($date . ' -1 year')); //ย้อนหลัง 1 ปี
        $yearnew = date('Y')+1;
        $yearold = date('Y')-1;
        $start = (''.$yearold.'-10-01');
        $end = (''.$yearnew.'-09-30'); 

        $data['startdate'] = $request->startdate;
        $data['enddate'] = $request->enddate;
        if ($data['startdate'] == '') {
            $data['datashow'] = DB::connection('mysql')->select('
                SELECT month(v.vstdate) as months,year(v.vstdate) as year,l.MONTH_NAME
                    ,COUNT(DISTINCT r.vn) countvn,SUM(r.amount) sumamount
                    from hos.rcpt_arrear r  
                    LEFT OUTER JOIN hos.vn_stat v on v.vn=r.vn  
                    LEFT OUTER JOIN hos.patient p on p.hn=r.hn   
                    LEFT OUTER JOIN leave_month l on l.MONTH_ID = month(v.vstdate)
                    WHERE v.vstdate BETWEEN "'.$newDate.'" and "'.$date.'"
                    AND r.paid ="N" AND r.pt_type="OPD"
                    GROUP BY month(v.vstdate)
                    ORDER BY v.vstdate desc limit 6; 
            '); 
        } else {
            $data['datashow'] = DB::connection('mysql')->select('
                SELECT month(v.vstdate) as months,year(v.vstdate) as year,l.MONTH_NAME
                        ,COUNT(DISTINCT r.vn) countvn,SUM(r.amount) sumamount
                        from hos.rcpt_arrear r  
                        LEFT OUTER JOIN hos.vn_stat v on v.vn=r.vn   
                        LEFT OUTER JOIN hos.patient p on p.hn=r.hn  
                        LEFT OUTER JOIN leave_month l on l.MONTH_ID = month(v.vstdate)
                        WHERE v.vstdate BETWEEN "'.$data['startdate'].'" and "'.$data['enddate'].'" 
                        AND r.paid ="N"
                        ORDER BY v.vstdate desc limit 6;  
            '); 
        }
        
        
        return view('account_106.acc_106_dashboard', $data );
    }
    public function acc_106_pull(Request $request)
    {
        $datenow = date('Y-m-d');
        $months = date('m');
        $year = date('Y');
        // dd($year);
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        if ($startdate == '') {
            // $acc_debtor = Acc_debtor::where('stamp','=','N')->whereBetween('dchdate', [$datenow, $datenow])->get();
            $acc_debtor = DB::select('
                SELECT a.*,c.subinscl from acc_debtor a
                left join checksit_hos c on c.vn = a.vn  
                WHERE a.account_code="1102050102.106"
                AND a.stamp = "N"
                group by a.vn
                order by a.vstdate asc;

            ');
            // and month(a.dchdate) = "'.$months.'" and year(a.dchdate) = "'.$year.'"
        } else {
            // $acc_debtor = Acc_debtor::where('stamp','=','N')->whereBetween('dchdate', [$startdate, $enddate])->get();
        }

        return view('account_106.acc_106_pull',[
            'startdate'     =>     $startdate,
            'enddate'       =>     $enddate,
            'acc_debtor'    =>     $acc_debtor,
        ]);
    }
    public function acc_106_pulldata(Request $request)
    { 
        $startdate = $request->datepicker;
        $enddate = $request->datepicker2; 
        $acc_debtor = DB::connection('mysql2')->select(' 
            SELECT r.vn,r.hn,p.cid,concat(p.pname,p.fname," ",p.lname) as ptname
                ,v.pttype,v.vstdate,r.arrear_date,r.arrear_time,rp.book_number,rp.bill_number,r.amount,rp.total_amount,r.paid
                ,o.vsttime,t.name as pttype_name,"27" as acc_code,"1102050102.106" as account_code,"ชำระเงิน" as account_name,r.staff
                ,r.rcpno,r.finance_number,r.receive_money_date,r.receive_money_staff

                FROM hos.rcpt_arrear r  
                LEFT OUTER JOIN hos.rcpt_print rp on r.vn = rp.vn 
                LEFT OUTER JOIN hos.ovst o on o.vn= r.vn  
                LEFT OUTER JOIN hos.vn_stat v on v.vn= r.vn  
                LEFT OUTER JOIN hos.patient p on p.hn=r.hn  
                LEFT OUTER JOIN hos.pttype t on t.pttype = o.pttype
                LEFT OUTER JOIN hos.pttype_eclaim e on e.code = t.pttype_eclaim_id
                WHERE v.vstdate BETWEEN "' . $startdate . '" AND "' . $enddate . '"
                AND r.paid ="N" AND r.pt_type="OPD"
                GROUP BY r.vn
        ');
        // LEFT OUTER JOIN leave_month l on l.MONTH_ID = month(o.vstdate)
        foreach ($acc_debtor as $key => $value) {
                    $check = Acc_debtor::where('vn', $value->vn)->where('account_code','1102050102.106')->whereBetween('vstdate', [$startdate, $enddate])->count();
                    if ($check == 0) {
                        Acc_debtor::insert([
                            'hn'                 => $value->hn,
                            // 'an'                 => $value->an,
                            'vn'                 => $value->vn,
                            'cid'                => $value->cid,
                            'ptname'             => $value->ptname,
                            'pttype'             => $value->pttype,
                            'vstdate'            => $value->vstdate,
                            'acc_code'           => $value->acc_code,
                            'account_code'       => $value->account_code,
                            'account_name'       => $value->account_name,
                            // 'income_group'       => $value->income_group,
                            // 'income'             => $value->income,
                            // 'uc_money'           => $value->uc_money,
                            // 'discount_money'     => $value->discount_money,
                            // 'paid_money'         => $value->paid_money,
                            // 'rcpt_money'         => $value->rcpt_money,
                            'debit'              => $value->amount,
                            // 'debit_drug'         => $value->debit_drug,
                            // 'debit_instument'    => $value->debit_instument,
                            // 'debit_toa'          => $value->debit_toa,
                            // 'debit_refer'        => $value->debit_refer,
                            'debit_total'        => $value->amount,
                            'rcpno'              => $value->rcpno,
                            // 'max_debt_amount'    => $value->max_debt_money,
                            'acc_debtor_userid'  => Auth::user()->id
                        ]);
                    }
        }
            return response()->json([

                'status'    => '200'
            ]);
    }
    public function acc_106_stam(Request $request)
    {
        $id = $request->ids;
        $iduser = Auth::user()->id;
        $data = Acc_debtor::whereIn('acc_debtor_id',explode(",",$id))->get();
            Acc_debtor::whereIn('acc_debtor_id',explode(",",$id))
                    ->update([
                        'stamp' => 'Y'
                    ]);
        foreach ($data as $key => $value) {
                $date = date('Y-m-d H:m:s');
             $check = Acc_1102050102_106::where('vn', $value->vn)->count(); 
                if ($check > 0) {
                # code...
                } else {
                    Acc_1102050102_106::insert([
                            'vn'                => $value->vn,
                            'hn'                => $value->hn,
                            'an'                => $value->an,
                            'cid'               => $value->cid,
                            'ptname'            => $value->ptname,
                            'vstdate'           => $value->vstdate,
                            'regdate'           => $value->regdate,
                            'dchdate'           => $value->dchdate,
                            'pttype'            => $value->pttype,
                            'pttype_nhso'       => $value->pttype_spsch,
                            'acc_code'          => $value->acc_code,
                            'account_code'      => $value->account_code,
                            'income'            => $value->income,
                            'income_group'      => $value->income_group,
                            'uc_money'          => $value->uc_money,
                            'discount_money'    => $value->discount_money,
                            'rcpt_money'        => $value->rcpt_money,
                            'debit'             => $value->debit,
                            'debit_drug'        => $value->debit_drug,
                            'debit_instument'   => $value->debit_instument,
                            'debit_refer'       => $value->debit_refer,
                            'debit_toa'         => $value->debit_toa,
                            'debit_total'       => $value->debit_total,
                            'max_debt_amount'   => $value->max_debt_amount,
                            'acc_debtor_userid' => $iduser
                    ]);
                }

        }
        return response()->json([
            'status'    => '200'
        ]);
    }
    public function acc_106_detail(Request $request,$months,$year)
    {
        $datenow = date('Y-m-d');
        $startdate = $request->startdate;
        $enddate = $request->enddate; 
        $data['users'] = User::get();

        $data = DB::select('
        SELECT U1.vn,U1.an,U1.hn,U1.cid,U1.ptname,U1.vstdate,U1.pttype,U1.debit_total
            from acc_1102050102_106 U1
            WHERE month(U1.vstdate) = "'.$months.'" AND year(U1.vstdate) = "'.$year.'" 
            GROUP BY U1.vn
        ');
        // WHERE month(U1.vstdate) = "'.$months.'" and year(U1.vstdate) = "'.$year.'"
        return view('account_106.acc_106_detail', $data, [ 
            'data'          =>     $data,
            'startdate'     =>     $startdate,
            'enddate'       =>     $enddate
        ]);
    }
    public function acc_106_detail_date(Request $request,$startdate,$enddate)
    { 
        $data['users'] = User::get();

        $data = DB::select('
        SELECT U1.vn,U1.an,U1.hn,U1.cid,U1.ptname,U1.vstdate,U1.pttype,U1.debit_total
            from acc_1102050102_106 U1
            WHERE U1.vstdate  BETWEEN "'.$startdate.'" AND "'.$enddate.'" 
            GROUP BY U1.vn
        ');
        // WHERE month(U1.vstdate) = "'.$months.'" and year(U1.vstdate) = "'.$year.'"
        return view('account_106.acc_106_detail_date', $data, [ 
            'data'          =>     $data,
            'startdate'     =>     $startdate,
            'enddate'       =>     $enddate
        ]);
    }
    public function acc_106_file(Request $request)
    {
        $datenow = date('Y-m-d');
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        // $datashow = DB::connection('mysql')->select('SELECT * FROM acc_stm_repmoney ar LEFT JOIN acc_trimart_liss a ON a.acc_trimart_liss_id = ar.acc_stm_repmoney_tri ORDER BY acc_stm_repmoney_id DESC');
        $datashow = DB::connection('mysql')->select('
          
            SELECT U1.acc_1102050102_106_id,U1.vn,U1.an,U1.hn,U1.cid,U1.ptname,U1.account_code,U1.vstdate,U1.pttype,U1.debit_total,U2.file,U2.filename
            from acc_1102050102_106 U1
            LEFT OUTER JOIN acc_doc U2 ON U2.acc_doc_pangid = U1.acc_1102050102_106_id
            GROUP BY U1.vn
        ');
        // SELECT YEAR(a.acc_trimart_start_date) as year,ar.acc_stm_repmoney_id,a.acc_trimart_code,a.acc_trimart_name
        // ,ar.acc_stm_repmoney_book,ar.acc_stm_repmoney_no,ar.acc_stm_repmoney_price301,ar.acc_stm_repmoney_price302,ar.acc_stm_repmoney_price310,ar.acc_stm_repmoney_date,concat(u.fname," ",u.lname) as fullname
        // FROM acc_stm_repmoney ar 
        // LEFT JOIN acc_trimart a ON a.acc_trimart_id = ar.acc_trimart_id 
        // LEFT JOIN users u ON u.id = ar.user_id 
        // ORDER BY acc_stm_repmoney_id DESC
        $countc = DB::table('acc_stm_ucs_excel')->count(); 
        $data['trimart'] = DB::table('acc_trimart')->get();

        return view('account_106.acc_106_file',$data,[
            'startdate'     =>     $startdate,
            'enddate'       =>     $enddate,
            'datashow'      =>     $datashow,
            'countc'        =>     $countc
        ]);
    }
    public function acc_106_file_updatefile(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:xls,xlsx,pdf,png,jpeg'
        ]);
        // $the_file = $request->file('file'); 
        $file_name = $request->file('file')->getClientOriginalName(); //ชื่อไฟล์
        //    dd($file_name);
 
        $add = new Acc_doc();
        $add->acc_doc_pangid           = $request->input('acc_1102050102_106_id');
        $add->acc_doc_pang             = $request->input('account_code'); 
        $add->file                     = $request->file('file');
        if($request->hasFile('file')){               
            $request->file->storeAs('account_106',$file_name,'public');
            $add->filename             = $file_name;
        }
        $add->save();
        return redirect()->route('acc.acc_106_file');
        // return response()->json([
        //     'status'    => '200' 
        // ]); 
    }
    public function acc106destroy(Request $request,$id)
    {
        
        $file_ = Acc_doc::find($id);  
        $file_name = $file_->filename; 
        $filepath = public_path('storage/account_106/'.$file_name);
        $description = File::delete($filepath);

        $del = Acc_doc::find($id);  
        $del->delete(); 

        return redirect()->route('acc.acc_106_file');
        // return response()->json(['status' => '200']);
    }
    
 
}
