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
use App\Models\Com_repaire;  
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
                ,v.income,v.uc_money,v.discount_money,v.paid_money,v.rcpt_money

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
            // $check = Acc_debtor::where('vn', $value->vn)->where('account_code','1102050102.106')->whereBetween('vstdate', [$startdate, $enddate])->count();
                    // $check = Acc_debtor::where('vn', $value->vn)->where('account_code','1102050102.106')->count();
                    $check = Acc_debtor::where('vn', $value->vn)->where('account_code','1102050102.106')->count();
                    if ($check > 0) {
                        Acc_debtor::where('vn', $value->vn)->where('account_code','1102050102.106')->update([                           
                            'income'             => $value->income,
                            'uc_money'           => $value->uc_money,
                            'discount_money'     => $value->discount_money,
                            'paid_money'         => $value->paid_money,
                            'rcpt_money'         => $value->rcpt_money,
                            'debit'              => $value->amount, 
                            'debit_total'        => $value->amount,
                            'rcpno'              => $value->rcpno,  
                        ]);
                        // Acc_1102050102_106::where('vn', $value->vn)->update([ 
                        //     'income'             => $value->income,
                        //     'uc_money'           => $value->uc_money,
                        //     'discount_money'     => $value->discount_money,
                        //     'paid_money'         => $value->paid_money,
                        //     'rcpt_money'         => $value->rcpt_money,
                        //     'debit'              => $value->amount, 
                        //     'debit_total'        => $value->amount,
                        //     'rcpno'              => $value->rcpno,  
                        // ]);
                    }else{
                        Acc_debtor::insert([
                            // 'stamp'              => 'Y',
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
                            'income'             => $value->income,
                            'uc_money'           => $value->uc_money,
                            'discount_money'     => $value->discount_money,
                            'paid_money'         => $value->paid_money,
                            'rcpt_money'         => $value->rcpt_money,
                            'debit'              => $value->amount, 
                            'debit_total'        => $value->amount,
                            'rcpno'              => $value->rcpno, 
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

    public function acc_106_debt(Request $request)
    {
        $startdate = $request->startdate;
        $enddate = $request->enddate; 
        $data['users'] = User::get();
 
        $datashow = DB::connection('mysql')->select('        
                SELECT U1.acc_1102050102_106_id,U1.vn,U1.an,U1.hn,U1.cid,U1.ptname,U1.account_code,U1.vstdate,U1.pttype,U3.income,U3.paid_money,U3.rcpt_money,U1.debit_total,U2.file,U2.filename
                FROM acc_1102050102_106 U1
                LEFT OUTER JOIN acc_doc U2 ON U2.acc_doc_pangid = U1.acc_1102050102_106_id
                LEFT OUTER JOIN acc_debtor U3 ON U3.vn = U1.vn
                WHERE U1.debit_total > 0
                GROUP BY U1.vn
        ');
        return view('account_106.acc_106_debt',[
            'startdate'     =>  $startdate,
            'enddate'       =>  $enddate,
            'datashow'      =>  $datashow,
        ]);
    }
    public function acc_106_debt_print(Request $request, $id)
    { 
        $dataedit = Com_repaire::leftJoin('com_repaire_speed', 'com_repaire_speed.status_id', '=', 'com_repaire.com_repaire_speed')
            ->leftjoin('users', 'users.id', '=', 'com_repaire.com_repaire_user_id')
            ->where('com_repaire_id', '=', $id)->first();

        $org = DB::table('orginfo')->where('orginfo_id', '=', 1)
            ->leftjoin('users', 'users.id', '=', 'orginfo.orginfo_manage_id')
            ->leftjoin('users_prefix', 'users_prefix.prefix_code', '=', 'users.pname')
            ->first();
        $rong = $org->prefix_name . ' ' . $org->fname . '  ' . $org->lname;

        $orgpo = DB::table('orginfo')->where('orginfo_id', '=', 1)
            ->leftjoin('users', 'users.id', '=', 'orginfo.orginfo_po_id')
            ->leftjoin('users_prefix', 'users_prefix.prefix_code', '=', 'users.pname')
            ->first();
        $po = $orgpo->prefix_name . ' ' . $orgpo->fname . '  ' . $orgpo->lname;

        $count = DB::table('com_repaire_signature')
            ->where('com_repaire_id', '=', $id)
            // ->orwhere('com_repaire_no', '=', $dataedit->com_repaire_no)
            ->count();

        // $countper = DB::table('car_service_personjoin')
        //     ->where('com_repaire_id', '=', $id)
        //     // ->orwhere('car_service_no', '=', $dataedit->car_service_no)
        //     ->count();

        // $countpers = $countper + 1;
        // dd($countper);
        if ($count != 0) {
            $signature = DB::table('com_repaire_signature')->where('com_repaire_id', '=', $id)
                // ->orwhere('com_repaire_no','=',$dataedit->com_repaire_no)
                ->first();
            $siguser = $signature->signature_name_usertext; //ผู้รองขอ
            $sigstaff = $signature->signature_name_stafftext; //ผู้รองขอ
            $sigrep = $signature->signature_name_reptext; //ผู้รับงาน
            $sighn = $signature->signature_name_hntext; //หัวหน้า
            $sigrong = $signature->signature_name_rongtext; //หัวหน้าบริหาร
            $sigpo = $signature->signature_name_potext; //ผอ

        } else {
            $sigrong = '';
            $siguser = '';
            $sigstaff = '';
            $sighn = '';
            $sigpo = '';
        }


        define('FPDF_FONTPATH', 'font/');
        require(base_path('public') . "/fpdf/WriteHTML.php");

        $pdf = new Fpdi(); // Instantiation   start-up Fpdi

        function dayThai($strDate)
        {
            $strDay = date("j", strtotime($strDate));
            return $strDay;
        }
        function monthThai($strDate)
        {
            $strMonth = date("n", strtotime($strDate));
            $strMonthCut = array("", "มกราคม", "กุมภาพันธ์ ", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
            $strMonthThai = $strMonthCut[$strMonth];
            return $strMonthThai;
        }
        function yearThai($strDate)
        {
            $strYear = date("Y", strtotime($strDate)) + 543;
            return $strYear;
        }
        function time($strtime)
        {
            $H = substr($strtime, 0, 5);
            return $H;
        }

        function DateThai($strDate)
        {
            if ($strDate == '' || $strDate == null || $strDate == '0000-00-00') {
                $datethai = '';
            } else {
                $strYear = date("Y", strtotime($strDate)) + 543;
                $strMonth = date("n", strtotime($strDate));
                $strDay = date("j", strtotime($strDate));
                $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
                $strMonthThai = $strMonthCut[$strMonth];
                $datethai = $strDate ? ($strDay . ' ' . $strMonthThai . ' ' . $strYear) : '-';
            }
            return $datethai;
        }

        $date = date_create($dataedit->created_at);
        $datnow =  date_format($date, "Y-m-j");

        //  // Arial bold 15
        // $this->SetFont('Arial','B',15);
        // // Calculate width of title and position
        // $w = $this->GetStringWidth($title)+6;
        // $this->SetX((210-$w)/2);
        // // Colors of frame, background and text
        // $this->SetDrawColor(0,80,180);
        // $this->SetFillColor(230,230,0);
        // $this->SetTextColor(220,50,50);
        // // Thickness of frame (1 mm)
        // $this->SetLineWidth(1);
        // // Title
        // $this->Cell($w,9,$title,1,1,'C',true);
        // // Line break
        // $this->Ln(10);

        $pdf->SetLeftMargin(22);
        $pdf->SetRightMargin(5);
        $pdf->AddFont('THSarabunNew', '', 'THSarabunNew.php');
        $pdf->AddFont('THSarabunNew Bold', '', 'THSarabunNew Bold.php');
        $pdf->SetFont('THSarabunNew Bold', '', 19);
        // $pdf->AddPage("L", ['100', '100']);
        $pdf->AddPage("P");
        $pdf->Image('assets/images/crut.png', 22, 15, 16, 16);
        $pdf->SetFont('THSarabunNew Bold', '', 19);
        $pdf->Text(93, 25, iconv('UTF-8', 'TIS-620', 'ใบแจ้งซ่อม '));
        $pdf->SetFont('THSarabunNew', '', 17);
        $pdf->Text(75, 33, iconv('UTF-8', 'TIS-620', 'โรงพยาบาล ' . $org->orginfo_name));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(25, 41, iconv('UTF-8', 'TIS-620', 'หน่วยงานที่แจ้งซ่อม :   ' . $dataedit->com_repaire_debsubsub_name));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(110, 41, iconv('UTF-8', 'TIS-620', 'เบอร์โทร :   ' . $dataedit->tel));
        // x1,y1,x2,y2
        $pdf->Line(25, 45, 180, 45);   // 25 คือ ย่อหน้า  // 45 คือ margintop   // 180 คือความยาวเส้น 
        $pdf->SetFont('THSarabunNew Bold', '', 14);
        $pdf->Text(25, 52, iconv('UTF-8', 'TIS-620', 'ส่วนที่ 1 ผู้แจ้ง  :  '));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(50, 52, iconv('UTF-8', 'TIS-620', 'รหัสแจ้งซ่อม  :  ' . $dataedit->com_repaire_no));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(100, 52, iconv('UTF-8', 'TIS-620', 'วันที่  :  ' . DateThai($dataedit->com_repaire_date)));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(150, 52, iconv('UTF-8', 'TIS-620', 'เวลา  :  ' . time($dataedit->com_repaire_time). ' น.'));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(25, 60, iconv('UTF-8', 'TIS-620', 'หมายเลขครุภัณฑ์  :  ' . $dataedit->com_repaire_article_num));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(25, 68, iconv('UTF-8', 'TIS-620', 'ชื่อครุภัณฑ์  :  ' . $dataedit->com_repaire_article_name));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(25, 76, iconv('UTF-8', 'TIS-620', 'รายละเอียดแจ้งซ่อม  :  ' . $dataedit->com_repaire_detail));
        // $pdf->SetFont('THSarabunNew', '', 15);
        // $pdf->Text(130, 35, iconv('UTF-8', 'TIS-620', 'วันที่  ' . dayThai($datnow) . '  ' . monthThai($datnow) . '  พ.ศ. ' . yearThai($datnow)));

        //ผู้ขออนุญาต
        if ($siguser != null) { 
            // $pdf->SetTextColor(128);
            // ชิดซ้าย
            // $pdf->Image($siguser, 50, 82, 50, 17, "png");
            // $pdf->SetFont('THSarabunNew', '', 15);
            // $pdf->Text(41, 92, iconv('UTF-8', 'TIS-620', '(ลงชื่อ)                                        ผู้แจ้งซ่อม'));
            // $pdf->SetFont('THSarabunNew', '', 15);
            // $pdf->Text(55, 102, iconv('UTF-8', 'TIS-620', '(   ' . $dataedit->com_repaire_user_name . '   )'));

            //ตรงกลาง
            $pdf->Image($siguser, 80, 85, 50, 17, "png");
            $pdf->SetFont('THSarabunNew', '', 15);
            $pdf->Text(71, 95, iconv('UTF-8', 'TIS-620', '(ลงชื่อ)                                        ผู้แจ้งซ่อม'));
            $pdf->SetFont('THSarabunNew', '', 15);
            $pdf->Text(85, 105, iconv('UTF-8', 'TIS-620', '(   ' . $dataedit->com_repaire_user_name . '   )'));
        } else {
            // $pdf->Image($sig, 150,220, 40, 20,"png");
        }

        $pdf->Line(25, 110, 180, 110);   // 25 คือ ย่อหน้า  // 45 คือ margintop   // 180 คือความยาวเส้น 
        $pdf->SetFont('THSarabunNew Bold', '', 14);
        $pdf->Text(25, 117, iconv('UTF-8', 'TIS-620', 'ส่วนที่ 2 ช่าง  :  '));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(50, 117, iconv('UTF-8', 'TIS-620', 'รหัสแจ้งซ่อม  :  ' . $dataedit->com_repaire_no));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(100, 117, iconv('UTF-8', 'TIS-620', 'วันที่  :  ' . DateThai($dataedit->com_repaire_date)));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(150, 117, iconv('UTF-8', 'TIS-620', 'เวลา  :  ' . time($dataedit->com_repaire_time). ' น.'));

        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(50, 126, iconv('UTF-8', 'TIS-620', 'ความเร่งด่วน'));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(85, 126, iconv('UTF-8', 'TIS-620', 'ปกติ  '));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(110, 126, iconv('UTF-8', 'TIS-620', 'ด่วน  '));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(135, 126, iconv('UTF-8', 'TIS-620', 'ด่วนมาก  '));
        $pdf->SetFont('THSarabunNew', '', 14);
        $pdf->Text(165, 126, iconv('UTF-8', 'TIS-620', 'ด่วนที่สุด  ')); 
        if ($dataedit->com_repaire_speed == "1") { 
            $pdf->Image(base_path('public') . '/fpdf/img/checked.png', 78, 123, 4, 4);
            $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 103, 123, 4, 4);
            $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 128, 123, 4, 4);
            $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 157, 123, 4, 4);
        }else if ($dataedit->com_repaire_speed == "2") { 
            $pdf->Image(base_path('public') . '/fpdf/img/checked.png', 103, 123, 4, 4);
            $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 78, 123, 4, 4);
            $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 128, 123, 4, 4);
            $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 157, 123, 4, 4);
        }else if ($dataedit->com_repaire_speed == "3") { 
            $pdf->Image(base_path('public') . '/fpdf/img/checked.png', 128, 123, 4, 4);
            $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 103, 123, 4, 4);
            $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 78, 123, 4, 4);
            $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 157, 123, 4, 4);
        } else {
            $pdf->Image(base_path('public') . '/fpdf/img/checked.png', 157, 123, 4, 4);
            $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 103, 123, 4, 4);
            $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 128, 123, 4, 4);
            $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 78, 123, 4, 4);
         }
         $pdf->SetFont('THSarabunNew', '', 14);
         $pdf->Text(25, 136, iconv('UTF-8', 'TIS-620', 'รายละเอียดการตรวจซ่อมที่พบ/ความเห็นของช่าง  ')); 
         $pdf->SetFont('THSarabunNew', '', 14);
         $pdf->Text(90, 136, iconv('UTF-8', 'TIS-620',' :  ' .$dataedit->com_repaire_detail_tech));   
        //ผู้ดูแลอนุญาต
        if ($sigstaff != null) {
            $pdf->Image($sigstaff, 109, 173, 50, 17, "png");
            $pdf->SetFont('THSarabunNew', '', 15);
            $pdf->Text(100, 188, iconv('UTF-8', 'TIS-620', '(ลงชื่อ)                                            ผู้อนุญาต'));
            // $pdf->Text(112, 198, iconv('UTF-8', 'TIS-620', '(   ' . $dataedit->car_service_staff_name . '   )'));
        } else {
            // $pdf->Image($siguser, 105,173, 50, 17,"png"); 
            $pdf->SetFont('THSarabunNew', '', 15);
            $pdf->Text(100, 180, iconv('UTF-8', 'TIS-620', '(ลงชื่อ) ......................................................... ผู้อนุญาต'));
            $pdf->SetFont('THSarabunNew', '', 15);
            $pdf->Text(108, 189, iconv('UTF-8', 'TIS-620', '( .......................................................... )'));
        }

        $pdf->SetFont('THSarabunNew', '', 15);
        $pdf->Text(25, 220, iconv('UTF-8', 'TIS-620', 'ความเห็นของผู้มีอำนาจสั่งรถยนต์ '));
        $pdf->SetFont('THSarabunNew', '', 15);
        $pdf->Text(113, 220, iconv('UTF-8', 'TIS-620', 'อนุญาต '));
        $pdf->SetFont('THSarabunNew', '', 15);
        $pdf->Text(150, 220, iconv('UTF-8', 'TIS-620', 'ไม่อนุญาต '));

        if ($sigpo != null) {

            // dd($dataedit->car_service_status);
            if ($dataedit->car_service_status == "noallow") {
                $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 105, 217, 4, 4);
                $pdf->Image(base_path('public') . '/fpdf/img/checked.png', 140, 217, 4, 4);
            } else {
                $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 140, 217, 4.5, 4.5);
                $pdf->Image(base_path('public') . '/fpdf/img/checked.png', 105, 217, 4.5, 4.5);
                // $pdf->Image(base_path('public').'/fpdf/img/checkno.jpg',140,217, 4, 4); 
            }


            $pdf->Image($sigpo, 109, 225, 50, 17, "png");
            $pdf->Text(108, 249, iconv('UTF-8', 'TIS-620', $po));
            // $pdf->Text(150,288,iconv( 'UTF-8','TIS-620','ผู้อำนวยการ'.$orgpo->orginfo_name  ));
            $pdf->SetFont('THSarabunNew', '', 15);
            $pdf->Text(100, 240, iconv('UTF-8', 'TIS-620', '(ลงชื่อ)                                              ผู้อนุญาต'));
            // $pdf->SetFont('THSarabunNew','',15);
            // $pdf->Text(108,249,iconv( 'UTF-8','TIS-620','( .......................................................... )' )); 
            $pdf->SetFont('THSarabunNew', '', 15);
            $pdf->Text(108, 258, iconv('UTF-8', 'TIS-620', 'ผู้อำนวยการ' . $orgpo->orginfo_name));
        } else {
            // $pdf->Image($siguser, 105,225, 50, 17,"png");
            $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 105, 217, 4, 4);
            $pdf->Image(base_path('public') . '/fpdf/img/checkno.jpg', 140, 217, 4, 4);
            $pdf->SetFont('THSarabunNew', '', 15);
            $pdf->Text(100, 240, iconv('UTF-8', 'TIS-620', '(ลงชื่อ) ......................................................... ผู้อนุญาต'));
            $pdf->SetFont('THSarabunNew', '', 15);
            $pdf->Text(108, 249, iconv('UTF-8', 'TIS-620', '( .......................................................... )'));
            $pdf->SetFont('THSarabunNew', '', 15);
            $pdf->Text(108, 258, iconv('UTF-8', 'TIS-620', 'ผู้อำนวยการ' . $orgpo->orginfo_name));
        }


        $pdf->Output();

        exit;
    }
    
 
}
