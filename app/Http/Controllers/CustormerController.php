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
use App\Models\Acc_1102050101_302;

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


class CustormerController extends Controller
 { 
    
    public function contact_save(Request $request)
    {
        $datenow = date('Y-m-d');
        $startdate = $request->startdate;
        $enddate = $request->enddate;
      
        return response()->json([

            'status'    => '200'
        ]); 
    }
    

    public function account_304_pulldata(Request $request)
    {
        $datenow = date('Y-m-d');
        $startdate = $request->datepicker;
        $enddate = $request->datepicker2;
        // Acc_opitemrece::truncate();
            $acc_debtor = DB::connection('mysql3')->select('
                SELECT a.vn,a.an,a.hn,pt.cid,concat(pt.pname,pt.fname," ",pt.lname) fullname
                ,a.regdate as admdate,a.dchdate as dchdate,v.vstdate,op.income as income_group
                ,a.pttype,ptt.max_debt_money,ec.code,ec.ar_ipd as account_code
                ,ec.name as account_name,ifnull(ec.ar_ipd,"") pang_debit
                ,a.income as income ,a.uc_money,a.rcpt_money as cash_money,a.discount_money
                ,a.income-a.rcpt_money-a.discount_money as looknee_money
                
                ,sum(if(op.icode ="3010058",sum_price,0)) as fokliad
                ,sum(if(op.income="02",sum_price,0)) as debit_instument
                ,sum(if(op.icode IN("1560016","1540073","1530005","1540048","1620015","1600012","1600015"),sum_price,0)) as debit_drug
                ,sum(if(op.icode IN ("3001412","3001417"),sum_price,0)) as debit_toa
                ,sum(if(op.icode IN ("3010829","3010726 "),sum_price,0)) as debit_refer
                from ipt ip
                LEFT JOIN hos.an_stat a ON ip.an = a.an
                LEFT JOIN patient pt on pt.hn=a.hn
                LEFT JOIN pttype ptt on a.pttype=ptt.pttype
                LEFT JOIN pttype_eclaim ec on ec.code=ptt.pttype_eclaim_id
                LEFT JOIN hos.ipt_pttype ipt ON ipt.an = a.an
                LEFT JOIN hos.opitemrece op ON ip.an = op.an
                LEFT JOIN hos.vn_stat v on v.vn = a.vn
                WHERE a.dchdate BETWEEN "' . $startdate . '" AND "' . $enddate . '"
                AND a.pttype = "s7"
                GROUP BY a.an;
            ');

            foreach ($acc_debtor as $key => $value) {
                    $check = Acc_debtor::where('an', $value->an)->where('account_code','1102050101.304')->whereBetween('dchdate', [$startdate, $enddate])->count();
                    if ($check == 0) {
                        Acc_debtor::insert([
                            'hn'                 => $value->hn,
                            'an'                 => $value->an,
                            'vn'                 => $value->vn,
                            'cid'                => $value->cid,
                            'ptname'             => $value->fullname,
                            'pttype'             => $value->pttype,
                            'vstdate'            => $value->vstdate,
                            'regdate'            => $value->admdate,
                            'dchdate'            => $value->dchdate,
                            'acc_code'           => $value->code,
                            'account_code'       => $value->account_code,
                            'account_name'       => $value->account_name,
                            'income_group'       => $value->income_group,
                            'income'             => $value->income,
                            'uc_money'           => $value->uc_money,
                            'discount_money'     => $value->discount_money,
                            'paid_money'         => $value->cash_money,
                            'rcpt_money'         => $value->cash_money,
                            'debit'              => $value->looknee_money,
                            'debit_drug'         => $value->debit_drug,
                            'debit_instument'    => $value->debit_instument,
                            'debit_toa'          => $value->debit_toa,
                            'debit_refer'        => $value->debit_refer,
                            'fokliad'            => $value->fokliad,
                            'debit_total'        => $value->looknee_money,
                            'max_debt_amount'    => $value->max_debt_money,
                            'acc_debtor_userid'  => Auth::user()->id
                        ]);
                    }
 
            }
            return response()->json([

                'status'    => '200'
            ]);
    }
    
 


 }