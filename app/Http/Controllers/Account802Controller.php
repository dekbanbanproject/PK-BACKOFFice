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


class Account802Controller extends Controller
 {
     // *************************** 802 ********************************************    
   
     public function account_802_dash(Request $request)
     {
         $datenow = date('Y-m-d');
         $startdate = $request->startdate;
         $enddate = $request->enddate;
         $dabudget_year = DB::table('budget_year')->where('active','=',true)->first();
 
         $leave_month_year = DB::table('leave_month')->orderBy('MONTH_ID', 'ASC')->get();
         $date = date('Y-m-d');
         $y = date('Y') + 543;
         $newweek = date('Y-m-d', strtotime($date . ' -1 week')); //ย้อนหลัง 1 สัปดาห์
         $newDate = date('Y-m-d', strtotime($date . ' -5 months')); //ย้อนหลัง 5 เดือน
         $newyear = date('Y-m-d', strtotime($date . ' -1 year')); //ย้อนหลัง 1 ปี
         $yearnew = date('Y');
         $yearold = date('Y')-1;
         $start = (''.$yearold.'-10-01');
         $end = (''.$yearnew.'-09-30'); 
 
         if ($startdate == '') {
             $datashow = DB::select('
                     SELECT month(a.dchdate) as months,year(a.dchdate) as year,l.MONTH_NAME
                     ,count(distinct a.hn) as hn
                     ,count(distinct a.vn) as vn
                     ,count(distinct a.an) as an
                     ,sum(a.income) as income
                     ,sum(a.paid_money) as paid_money
                     ,sum(a.income)-sum(a.discount_money)-sum(a.rcpt_money) as total
 
                     FROM acc_debtor a
                     left outer join leave_month l on l.MONTH_ID = month(a.dchdate)
                     WHERE a.dchdate between "'.$start.'" and "'.$end.'"
                     and account_code="1102050102.802"
 
                     group by month(a.dchdate) order by month(a.dchdate) desc limit 3;
             ');
             // and stamp = "N"
         } else {
             $datashow = DB::select('
                     SELECT month(a.dchdate) as months,year(a.dchdate) as year,l.MONTH_NAME
                     ,count(distinct a.hn) as hn
                     ,count(distinct a.vn) as vn
                     ,count(distinct a.an) as an
                     ,sum(a.income) as income
                     ,sum(a.paid_money) as paid_money
                     ,sum(a.income)-sum(a.discount_money)-sum(a.rcpt_money) as total
 
                     FROM acc_debtor a
                     left outer join leave_month l on l.MONTH_ID = month(a.dchdate)
                     WHERE a.dchdate between "'.$startdate.'" and "'.$enddate.'"
                     and account_code="1102050102.802"
 
                     
             ');
         }
        //  group by month(a.dchdate) order by month(a.dchdate) desc;
             return view('account_802.account_802_dash',[
                 'startdate'     =>     $startdate,
                 'enddate'       =>     $enddate,
                 'datashow'    =>     $datashow,
                 'leave_month_year' =>  $leave_month_year,
             ]);
     }
     public function account_802_pull(Request $request)
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
                 left join checksit_hos c on c.an = a.an
                 WHERE account_code="1102050102.802"
                 AND stamp = "N"
                 group by an
                 order by vstdate desc; 
             '); 
         } else {
             $acc_debtor = DB::select('
                 SELECT 
                 a.acc_debtor_id,a.an,a.vn,a.hn,a.cid,a.ptname,a.pttype,a.dchdate,a.income,a.debit_total,a.debit_instument,a.debit_drug,a.debit_toa,a.debit_refer
                 ,c.subinscl 
                 from acc_debtor a
               
                 left join checksit_hos c on c.an = a.an
                 WHERE a.account_code="1102050102.802" and a.dchdate BETWEEN "' . $startdate . '" AND "' . $enddate . '"
                 AND a.stamp = "N"
                 group by a.an
                 order by a.dchdate desc; 
             '); 
         }  
         return view('account_802.account_802_pull',[
             'startdate'     =>     $startdate,
             'enddate'       =>     $enddate,
             'acc_debtor'    =>     $acc_debtor,
         ]);
     }
       public function account_802_pulldata(Request $request)
     {
         $datenow = date('Y-m-d');
         $startdate = $request->datepicker;
         $enddate = $request->datepicker2;
         $acc_debtor = DB::connection('mysql2')->select('
            SELECT i.vn,a.an,a.hn,pt.cid,concat(pt.pname,pt.fname," ",pt.lname) ptname
                ,a.regdate,a.dchdate,v.vstdate,op.income as income_group
                ,ipt.pttype,ipt.pttype_number,ipt.max_debt_amount,i.rw,i.adjrw,i.adjrw*9000 as total_adjrw_income 
                ,pt.hcode,o.vsttime,e.code as acc_code,e.ar_ipd as account_code,e.name as account_name
                ,a.income,a.uc_money,a.discount_money,a.paid_money,a.rcpt_money
                ,a.rcpno_list as rcpno
                ,a.income-a.discount_money-a.rcpt_money as debit
                ,if(op.icode IN ("3010058"),sum_price,0) as fokliad
                ,sum(if(op.income="02",sum_price,0)) as debit_instument
                ,sum(if(op.icode IN("1560016","1540073","1530005","1540048","1620015","1600012","1600015"),sum_price,0)) as debit_drug
                ,sum(if(op.icode IN("3001412","3001417"),sum_price,0)) as debit_toa
                ,sum(if(op.icode IN("3010829","3011068","3010864","3010861","3010862","3010863","3011069","3011012","3011070"),sum_price,0)) as debit_refer
                ,ptt.max_debt_money
                from ipt i
                LEFT JOIN ovst o on o.an = i.an
                left join an_stat a on a.an=i.an
                left join patient pt on pt.hn=o.hn
                LEFT JOIN pttype ptt on o.pttype=ptt.pttype
                LEFT JOIN pttype_eclaim e on e.code=ptt.pttype_eclaim_id
                LEFT JOIN ipt_pttype ipt ON ipt.an = a.an
                LEFT JOIN opitemrece op ON op.an = i.an
                LEFT JOIN drugitems d on d.icode=op.icode
                LEFT JOIN hos.vn_stat v on v.vn = i.vn
                WHERE a.dchdate BETWEEN "' . $startdate . '" AND "' . $enddate . '" 
                AND ipt.pttype IN(SELECT pttype from pkbackoffice.acc_setpang_type WHERE pttype IN(SELECT pttype FROM pkbackoffice.acc_setpang_type WHERE pang ="1102050102.802"))
                GROUP BY i.an
             
        ');
// dd($acc_debtor);
        foreach ($acc_debtor as $key => $value) {
                    $check = Acc_debtor::where('an', $value->an)->where('account_code','1102050102.802')->whereBetween('dchdate', [$startdate, $enddate])->count();
                    if ($check == 0) {
                        Acc_debtor::insert([
                            'hn'                 => $value->hn,
                            'an'                 => $value->an,
                            'vn'                 => $value->vn,
                            'cid'                => $value->cid,
                            'ptname'             => $value->ptname,
                            'pttype'             => $value->pttype,
                            'vstdate'            => $value->vstdate,
                            'dchdate'            => $value->dchdate,
                            'acc_code'           => $value->acc_code,
                            'account_code'       => $value->account_code,
                            'account_name'       => $value->account_name,
                            'income_group'       => $value->income_group,
                            'income'             => $value->income,
                            'uc_money'           => $value->uc_money,
                            'discount_money'     => $value->discount_money,
                            'paid_money'         => $value->paid_money,
                            'rcpt_money'         => $value->rcpt_money,
                            'debit'              => $value->debit,
                            'debit_drug'         => $value->debit_drug,
                            'debit_instument'    => $value->debit_instument,
                            'debit_toa'          => $value->debit_toa,
                            'debit_refer'        => $value->debit_refer,
                            'debit_total'        => $value->debit,
                            'max_debt_amount'    => $value->max_debt_money,
                            'rw'                 => $value->rw,
                            'adjrw'              => $value->adjrw,
                            'total_adjrw_income' => $value->total_adjrw_income,
                            'acc_debtor_userid'  => Auth::user()->id
                        ]);
                    }
        }
 
             return response()->json([
 
                 'status'    => '200'
             ]);
     }
    //  public function account_pkucs202(Request $request,$months,$year)
    //  {
    //      $datenow = date('Y-m-d');
    //      $startdate = $request->startdate;
    //      $enddate = $request->enddate;
    //      // dd($id);
    //      $data['users'] = User::get();
 
    //      $acc_debtor = DB::select('
    //          SELECT a.*,c.subinscl from acc_debtor a
    //          left outer join check_sit_auto c on c.cid = a.cid and c.vstdate = a.vstdate
 
    //          WHERE a.account_code="1102050101.202"
    //          AND a.stamp = "N"
    //          and month(a.dchdate) = "'.$months.'" and year(a.dchdate) = "'.$year.'"
    //          order by a.dchdate asc;
 
    //      ');
 
    //      return view('account_202.account_pkucs202', $data, [
    //          'startdate'     =>     $startdate,
    //          'enddate'       =>     $enddate,
    //          'acc_debtor'    =>     $acc_debtor,
    //          'months'        =>     $months,
    //          'year'          =>     $year
    //      ]);
    //  }
    //  public function account_pkucs202_detail(Request $request,$months,$year)
    //  {
    //      $datenow = date('Y-m-d');
    //      $startdate = $request->startdate;
    //      $enddate = $request->enddate;
    //      // dd($id);
    //      $data['users'] = User::get();
 
    //      $data = DB::select(' 
    //          SELECT *  from acc_1102050101_202
    //          WHERE month(dchdate) = "'.$months.'" and year(dchdate) = "'.$year.'"
    //          AND status = "N"
    //      ');
    //      // SELECT *,au.subinscl  from acc_1102050101_202 a
    //      //     LEFT JOIN acc_debtor au ON au.an = a.an
    //      //     WHERE month(a.dchdate) = "'.$months.'" and year(a.dchdate) = "'.$year.'";
 
    //      return view('account_202.account_pkucs202_detail', $data, [
    //          'startdate'     =>     $startdate,
    //          'enddate'       =>     $enddate,
    //          'data'          =>     $data,
    //          'months'        =>     $months,
    //          'year'          =>     $year
    //      ]);
    //  }
    //  public function account_pkucs202_detail_date(Request $request,$startdate,$enddate)
    //  {
    //      $datenow = date('Y-m-d');
    //      $startdate = $request->startdate;
    //      $enddate = $request->enddate;
    //      // dd($id);
    //      $data['users'] = User::get();
 
    //      $data = DB::select(' 
    //          SELECT *  from acc_1102050101_202 
    //          WHERE dchdate BETWEEN "'.$startdate.'" AND  "'.$enddate.'" 
    //          AND status = "N"
    //      ');
    //      // SELECT *,au.subinscl  from acc_1102050101_202 a
    //      //     LEFT JOIN acc_debtor au ON au.an = a.an
    //      //     WHERE month(a.dchdate) = "'.$months.'" and year(a.dchdate) = "'.$year.'";
 
    //      return view('account_202.account_pkucs202_detail_date', $data, [
    //          'startdate'     =>     $startdate,
    //          'enddate'       =>     $enddate,
    //          'data'          =>     $data,
    //          'startdate'     =>     $startdate,
    //          'enddate'       =>     $enddate
    //      ]);
    //  }
    //  public function account_pkucs202_stam(Request $request)
    //  {
    //      $id = $request->ids;
    //      $iduser = Auth::user()->id;
    //      $data = Acc_debtor::whereIn('acc_debtor_id',explode(",",$id))->get();
 
    //          Acc_debtor::whereIn('acc_debtor_id',explode(",",$id))
    //                  ->update([
    //                      'stamp' => 'Y'
    //                  ]);
 
    //      foreach ($data as $key => $value) {
    //              $date = date('Y-m-d H:m:s');
    //                  Acc_1102050101_202::insert([
    //                      'vn'                => $value->vn,
    //                      'hn'                => $value->hn,
    //                      'an'                => $value->an,
    //                      'cid'               => $value->cid,
    //                      'ptname'            => $value->ptname,
    //                      'vstdate'           => $value->vstdate,
    //                      'regdate'           => $value->regdate,
    //                      'dchdate'           => $value->dchdate,
    //                      'pttype'            => $value->pttype,
    //                      'pttype_nhso'       => $value->pttype_spsch,
    //                      'acc_code'          => $value->acc_code,
    //                      'account_code'      => $value->account_code,
    //                      'income_group'      => $value->income_group,
    //                      'income'            => $value->income,
    //                      'uc_money'          => $value->uc_money,
    //                      'discount_money'    => $value->discount_money,
    //                      'rcpt_money'        => $value->rcpt_money,
    //                      'debit'             => $value->debit,
    //                      'debit_drug'        => $value->debit_drug,
    //                      'debit_instument'   => $value->debit_instument,
    //                      'debit_refer'       => $value->debit_refer,
    //                      'debit_toa'         => $value->debit_toa,
    //                     //  'debit_total'       => $value->debit - $value->debit_drug - $value->debit_instument - $value->debit_refer - $value->debit_toa,
    //                      'debit_total'       => $value->debit_total,
    //                      'max_debt_amount'   => $value->max_debt_amount,
    //                      'rw'                => $value->rw,
    //                      'adjrw'             => $value->adjrw,
    //                      'total_adjrw_income'=> $value->total_adjrw_income,
    //                      'acc_debtor_userid' => $value->acc_debtor_userid

                           
    //                  ]);
    //                 //  $acc_opitemrece_ = DB::connection('mysql')->select('
    //                 //          SELECT a.stamp,ao.an,ao.vn,ao.hn,ao.vstdate,ao.pttype,ao.paidst,ao.finance_number,ao.income,ao.icode,ao.name as dname,ao.qty,ao.unitprice,ao.cost,ao.discount,ao.sum_price
    //                 //          FROM acc_opitemrece ao
    //                 //          LEFT JOIN acc_debtor a ON ao.an = a.an
    //                 //          WHERE a.account_code ="1102050101.202" AND a.stamp ="Y"
    //                 //          AND ao.an ="'.$value->an.'"
    //                 //  ');
    //                 //  foreach ($acc_opitemrece_ as $va2) {
    //                 //      Acc_opitemrece_stm::insert([
    //                 //          'hn'                 => $va2->hn,
    //                 //          'an'                 => $va2->an,
    //                 //          'vn'                 => $va2->vn,
    //                 //          'vstdate'            => $va2->vstdate,
    //                 //          'pttype'             => $va2->pttype,
    //                 //          'paidst'             => $va2->paidst,
    //                 //          'finance_number'     => $va2->finance_number,
    //                 //          'income'             => $va2->income,
    //                 //          'icode'              => $va2->icode,
    //                 //          'name'               => $va2->dname,
    //                 //          'qty'                => $va2->qty,
    //                 //          'cost'               => $va2->cost,
    //                 //          'unitprice'          => $va2->unitprice,
    //                 //          'discount'           => $va2->discount,
    //                 //          'sum_price'          => $va2->sum_price
    //                 //      ]);
 
    //                 //  }
    //      }
 
 
    //      return response()->json([
    //          'status'    => '200'
    //      ]);
    //  }
    //  public function account_pkucs202_stm(Request $request,$months,$year)
    //  {
    //      $datenow = date('Y-m-d');
    //      $startdate = $request->startdate;
    //      $enddate = $request->enddate;
    //      // dd($id);
    //      $data['users'] = User::get();
 
    //      $datashow = DB::select('
    //             SELECT s.tranid,a.vn,a.an,a.hn,a.cid,a.ptname,a.vstdate,a.dchdate,a.debit_total,s.dmis_money2
    //             ,s.total_approve,a.income_group,s.inst,s.hc,s.hc_drug,s.ae,s.ae_drug,s.ip_paytrue,s.STMdoc,a.adjrw,a.total_adjrw_income
    //             from acc_1102050101_202 a
    //          LEFT JOIN acc_stm_ucs s ON s.an = a.an
    //          WHERE month(a.dchdate) = "'.$months.'" and year(a.dchdate) = "'.$year.'" AND s.rep IS NOT NULL
 
    //      ');
    //      $sum_money_ = DB::connection('mysql')->select('
    //         SELECT SUM(a.debit_total) as total
    //         from acc_1102050101_202 a
    //         LEFT JOIN acc_stm_ucs au ON au.an = a.an
    //         WHERE month(a.dchdate) = "'.$months.'" and year(a.dchdate) = "'.$year.'" AND au.rep IS NOT NULL;
    //     ');
    //     foreach ($sum_money_ as $key => $value) {
    //         $sum_debit_total = $value->total;
    //     }
    //      $sum_stm_ = DB::connection('mysql')->select('
    //         SELECT SUM(au.inst) as stmtotal
    //         from acc_1102050101_202 a
    //         LEFT JOIN acc_stm_ucs au ON au.an = a.an
    //         WHERE month(a.dchdate) = "'.$months.'" and year(a.dchdate) = "'.$year.'" AND au.rep IS NOT NULL;
    //     ');
    //     foreach ($sum_stm_ as $key => $value) {
    //         $sum_stm_total = $value->stmtotal;
    //     }
 
    //      return view('account_202.account_pkucs202_stm', $data, [
    //          'startdate'         =>     $startdate,
    //          'enddate'           =>     $enddate,
    //          'datashow'          =>     $datashow,
    //          'months'            =>     $months,
    //          'year'              =>     $year,
    //          'sum_debit_total'   =>     $sum_debit_total,
    //          'sum_stm_total'     =>     $sum_stm_total
    //      ]);
    //  }
    //  public function account_pkucs202_stm_date(Request $request,$startdate,$enddate)
    //  { 
    //      $data['users'] = User::get();
 
    //      $datashow = DB::select('
    //             SELECT s.tranid,a.vn,a.an,a.hn,a.cid,a.ptname,a.vstdate,a.dchdate,a.debit_total,s.dmis_money2
    //             ,s.total_approve,a.income_group,s.inst,s.hc,s.hc_drug,s.ae,s.ae_drug,s.ip_paytrue,s.STMdoc,a.adjrw,a.total_adjrw_income
    //             from acc_1102050101_202 a
    //          LEFT JOIN acc_stm_ucs s ON s.an = a.an
    //          WHERE a.dchdate BETWEEN "'.$startdate.'" AND  "'.$enddate.'" 
    //          AND s.rep IS NOT NULL
 
    //      ');
       
 
    //      return view('account_202.account_pkucs202_stm_date', $data, [
    //          'startdate'         =>     $startdate,
    //          'enddate'           =>     $enddate,
    //          'datashow'          =>     $datashow, 
    //      ]);
    //  }
    //  public function account_pkucs202_stmnull(Request $request,$months,$year)
    //  {
    //      $datenow = date('Y-m-d');
    //      $startdate = $request->startdate;
    //      $enddate = $request->enddate;
    //      // dd($id);
    //      $data['users'] = User::get();
 
    //         $data = DB::connection('mysql')->select('
    //         SELECT au.tranid,a.vn,a.an,a.hn,a.cid,a.ptname,a.vstdate,a.dchdate,a.debit_total,au.dmis_money2,au.total_approve,a.income_group,au.inst,au.ip_paytrue,a.adjrw,a.total_adjrw_income
    //         from acc_1102050101_202 a
    //         LEFT JOIN acc_stm_ucs au ON au.an = a.an
    //         WHERE status ="N"
    //         AND month(a.dchdate) = "'.$months.'" and year(a.dchdate) = "'.$year.'";
 
 
    //          ');
    //          // SELECT vn,an,hn,cid,ptname,dchdate,income_group,debit_total
    //          // ,inst
    //          // FROM acc_1102050101_202
    //          // WHERE status ="N"
    //         $sum_money_ = DB::connection('mysql')->select('
    //             SELECT SUM(a.debit_total) as total
    //             from acc_1102050101_202 a
    //             LEFT JOIN acc_stm_ucs au ON au.an = a.an
    //             WHERE month(a.dchdate) = "'.$months.'" and year(a.dchdate) = "'.$year.'" AND au.rep IS NULL;
    //         ');
    //         foreach ($sum_money_ as $key => $value) {
    //             $sum_debit_total = $value->total;
    //         }
    //         $sum_stm_ = DB::connection('mysql')->select('
    //             SELECT SUM(au.inst) as stmtotal
    //             from acc_1102050101_202 a
    //             LEFT JOIN acc_stm_ucs au ON au.an = a.an
    //             WHERE month(a.dchdate) = "'.$months.'" and year(a.dchdate) = "'.$year.'" AND au.rep IS NULL;
    //         ');
    //         foreach ($sum_stm_ as $key => $value) {
    //             $sum_stm_total = $value->stmtotal;
    //         }
 
    //      return view('account_202.account_pkucs202_stmnull', $data, [
    //          'startdate'         =>     $startdate,
    //          'enddate'           =>     $enddate,
    //          'data'              =>     $data,
    //          'months'            =>     $months,
    //          'year'              =>     $year,
    //          'sum_debit_total'   =>     $sum_debit_total,
    //          'sum_stm_total'     =>     $sum_stm_total
    //      ]);
    //  }
    //  public function account_pkucs202_stmnull_date(Request $request,$startdate,$enddate)
    //  { 
    //      $data['users'] = User::get();
 
    //         $data = DB::connection('mysql')->select('
    //         SELECT au.tranid,a.vn,a.an,a.hn,a.cid,a.ptname,a.vstdate,a.dchdate,a.debit_total,au.dmis_money2,au.total_approve,a.income_group,au.inst,au.ip_paytrue,a.adjrw,a.total_adjrw_income
    //         from acc_1102050101_202 a
    //         LEFT JOIN acc_stm_ucs au ON au.an = a.an
    //         WHERE status ="N" AND a.dchdate BETWEEN "'.$startdate.'" AND  "'.$enddate.'"   
 
    //          ');
             
 
    //      return view('account_202.account_pkucs202_stmnull_date', $data, [
    //          'startdate'         =>     $startdate,
    //          'enddate'           =>     $enddate,
    //          'data'              =>     $data, 
    //      ]);
    //  }
    //  public function account_pkucs202_stmnull_all(Request $request,$months,$year)
    //  {
    //      $datenow = date('Y-m-d');
    //      $startdate = $request->startdate;
    //      $enddate = $request->enddate;
    //      // dd($id);
    //      $data['users'] = User::get();
    //      $mototal = $months + 1;
    //      $datashow = DB::connection('mysql')->select('
 
 
    //              SELECT au.tranid,a.vn,a.an,a.hn,a.cid,a.ptname,a.vstdate,a.dchdate,a.debit_total,au.dmis_money2,au.total_approve,a.income_group,au.inst,au.ip_paytrue,au.STMdoc,a.adjrw,a.total_adjrw_income
    //                  from acc_1102050101_202 a
    //                  LEFT JOIN acc_stm_ucs au ON au.an = a.an
    //                  WHERE a.status ="N"
    //                  AND month(a.dchdate) < "'.$mototal.'"
    //                  and year(a.dchdate) = "'.$year.'"
    //                  AND au.ip_paytrue IS NULL
    //                  GROUP BY a.an
 
 
 
    //          ');
    //      return view('account_202.account_pkucs202_stmnull_all', $data, [
    //          'startdate'         =>     $startdate,
    //          'enddate'           =>     $enddate,
    //          'datashow'          =>     $datashow,
    //          'months'            =>     $months,
    //          'year'              =>     $year,
    //      ]);
    //  }
    
   
 

 }