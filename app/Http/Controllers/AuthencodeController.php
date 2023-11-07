<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Authencode;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Validator;
// use Illuminate\support\Facades\Http;
use Stevebauman\Location\Facades\Location;
use Http;
use SoapClient;
use File;
use SplFileObject;
use Arr;
use Storage;

class AuthencodeController extends Controller
{     
    public function authen_index(Request $request)
    { 
        // $ip = $request()->ip();
        $ip = $request->ip();
        $collection = Http::get('http://'.$ip.':8189/api/smartcard/read')->collect();
        $patient =  DB::connection('mysql10')->select('select cid,hometel from patient limit 10');
        
        // $terminals = Http::get('http://'.$ip.':8189/api/smartcard/terminals')->collect();
        // $cardcid = Http::get('http://'.$ip.':8189/api/smartcard/read')->collect();
        // $cardcidonly = Http::get('http://'.$ip.':8189/api/smartcard/read-card-only')->collect();
        return view('authen.authen_index',[
            'collection1'  => $collection['pid'],
            'collection2'  => $collection['fname'],
            'collection3'  => $collection['lname'],
            'collection4'  => $collection['birthDate'],
            'collection5'  => $collection['transDate'],
            'collection6'  => $collection['mainInscl'],
            'collection7'  => $collection['subInscl'],
            'collection8'  => $collection['age'],
            'collection9'  => $collection['checkDate'],
            'collection10' => $collection['correlationId'],
            'collection11' => $collection['checkDate'],
            'collection'   => $collection,
            'patient'      => $patient
        ]);
   
    }
    public function authencode(Request $req)
    {
        // $authen = Http::post("http://localhost:8189/api/nhso-service/save-as-draft");
        // $cid = $req->pid;
        // $tel = $req->mobile;
        // $cid = '3451000002897';
        // $claimType = 'PG0060001';
        // $mobile = '0832411548';
        // $correlationId = '2341dc4e-9f38-4b93-ad61-5284e68ac5e4';
        // $hcode = '10978';  
        // 124242
        // $cid = '1360400223487';
        // $authen = Http::post("http://localhost:8189/api/nhso-service/confirm-save/",
        // [
        //     'pid'              =>  $cid,
        //     'claimType'        =>  $claimType,
        //     'mobile'           =>  $mobile,
        //     'correlationId'    =>  $correlationId,
        //     'hcode'            =>  $hcode
        // ]);

         $authen = Http::post("http://localhost:8189/api/nhso-service/confirm-save/",
        [
            'pid'              =>  $req->pid,
            'claimType'        =>  $req->claimType,
            'mobile'           =>  $req->mobile,
            'correlationId'    =>  $req->correlationId,
            // 'hcode'            =>  $req->hcode
        ]);

        // $authen = Http::post("http://localhost:8189/api/nhso-service/confirm-save",
        // [
        //     'pid'              =>  '1360400223487',
        //     'claimType'        =>  'PG0060001',
        //     'mobile'           =>  '0832411548',
        //     'correlationId'    =>  '2c3bd8ed-b78c-4452-b897-7a754e00926a',
        //     'hcode'            =>  '10978'
        // ]);

        
        Patient::where('cid', $req->pid)
            ->update([
                'hometel'    => $req->mobile
            ]);
 
        // return $authen->json();
        return response()->json([
            'status'     => '200'
        ]);
        
       
        // $authen = Http::post("http://localhost:8189/api/nhso-service/save-as-draft/",[
        //     'pid'              =>  "pid",
        //     'claimType'        =>  "claimType",
        //     'mobile'           =>  "mobile",
        //     'correlationId'    =>  "correlationId",
        //     'hcode'            =>  "hcode"
        // ]);
        // $authen = new Authencode;
        // $authen->pid = $req->pid;
        // $authen->claimType = $req->claimType;
        // $authen->mobile = $req->mobile;
        // $authen->correlationId = $req->correlationId;
        // $authen->hcode = $req->hcode;

        // $result = $authen->save();

        // if ($result) {
        //     return ["result" => "Data Save success"];
        // } else {
        //     return ["result" => "Data Save Fail"];
        // }
        
    }

    public function read(Request $request)
    { 
        // $collection = Http::get("http://localhost:8189/api/smartcard/read");
       $collection = Http::get('http://localhost:8189/api/smartcard/read')->collect();

       return view('welcome',[
        'collection1' => $collection['pid'],
        'collection2' => $collection['fname'],
        'collection3' => $collection['lname'],
        'collection4' => $collection['birthDate'],
        'collection5' => $collection['transDate'],
        'collection6' => $collection['mainInscl'],
        'collection7' => $collection['subInscl'],
        'collection8' => $collection['age'],
        'collection9' => $collection['checkDate'],
        'collection10' => $collection['correlationId'],
        'collection11' => $collection['startDateTime'],
        'collection' => $collection
    ]); // return Http::get('http://localhost:8189/api/smartcard/read');
    }

    public function authen_cid(Request $req)
    { 
       $collection = Http::get('http://localhost:8189/api/smartcard/read')->collect();
       $status            = '';
       $birthday          = '';
       $fname             = '';
       $lname             = '';
       $hmain             = '';
       $hmain_name        = '';
       $hsub              = '';
       $hsub_name         = '';
       $maininscl         = '';
       $maininscl_main    = '';
       $maininscl_name    = '';
       $expdate           = '';

        // str_pad(string , length , pad_string , pad_type)
        // string คือ สตริงที่ต้องการเติมคำ
        // length คือ ความยาวของสตริงที่ต้องการ
        // pad_string คือ ตัวอักษรหรือคำที่ต้องการเติม
        // pad_type คือ รูปแบบการเติม ค่าที่เป็นไปได้คือ
        // STR_PAD_BOTH - เติมทั้งสองข้าง ถ้าไม่ลงตัวข้างขวาจะถูกเติมมากกว่า
        // STR_PAD_LEFT - เติมด้านซ้าย
        // STR_PAD_RIGHT - เติมด้านขวา (default)
     
        //    $contents = File::get('D:\Authen\nhso_token.txt');
        $ip = $req->ip();
        // $path = ($ip.'/PKAuthen'.'/public/'.'Authen/nhso_token.txt');
        $contents = file('D:\UCAuthenticationMX\nhso_token.txt', FILE_SKIP_EMPTY_LINES|FILE_IGNORE_NEW_LINES);  
        // $contents = file($path, FILE_SKIP_EMPTY_LINES|FILE_IGNORE_NEW_LINES);  
        
        foreach($contents as $line) { 
            // echo str_pad($count, 2, 0, STR_PAD_LEFT).". ".$line;
            // echo $line;
        }

        // $mani = str_pad($line, 5); 
        // echo $mani . "#" . "\n";
       
        // $a="3451000002897#";
        // $count_a = strlen($a);
        // echo $count_a;
        // echo str_pad($count_a, 15, 0, STR_PAD_LEFT);
        // echo "<br>";
        $chars = preg_split('//', $line, -1, PREG_SPLIT_NO_EMPTY);
        // print_r($chars);
        // echo "<br>";
        // $data['output'] = Arr::sort($chars,2);
        $output = Arr::sort($chars,2);
        // dd($output,$chars['17']);
        // dd($data['output']); 
        $data['data1'] = $chars['1']; $data['data2'] = $chars['2']; $data['data3'] = $chars['3']; $data['data4'] = $chars['4'];
        $data['data5'] = $chars['5']; $data['data6'] = $chars['6']; $data['data7'] = $chars['7']; $data['data8'] = $chars['8'];
        $data['data9'] = $chars['9']; $data['data10'] = $chars['10']; $data['data11'] = $chars['11']; $data['data12'] = $chars['12'];
        $data['data13'] = $chars['13']; $data['data14'] = $chars['14']; $data['data15'] = $chars['15']; 
        // $data['data16'] = $chars['16'];

        $data['datacid'] = $data['data3'].''.$data['data4'].''.$data['data5'].''.$data['data6'].''.$data['data7'].''.$data['data8'].''.$data['data9'].''.$data['data10'].''.$data['data11']
        .''.$data['data12'].''.$data['data13'].''.$data['data14'].''.$data['data15'];

        // dd($data['datacid']);

        $data['data17'] = $chars['17'];
        $data['data18'] = $chars['18'];
        $data['data19'] = $chars['19'];
        $data['data20'] = $chars['20'];
        $data['data21'] = $chars['21'];
        $data['data22'] = $chars['22'];
        $data['data23'] = $chars['23'];
        $data['data24'] = $chars['24'];
        $data['data25'] = $chars['25'];
        $data['data26'] = $chars['26'];
        $data['data27'] = $chars['27'];
        $data['data28'] = $chars['28'];
        $data['data29'] = $chars['29'];
        $data['data30'] = $chars['30'];
        $data['data31']= $chars['31'];
        $data['data32'] = $chars['32'];

        $data['datatotal'] = $chars['17'].''.$data['data18'].''.$data['data19'].''.$data['data20'].''.$data['data21'].''.$data['data22'].''.$data['data23'].''.$data['data24'].''.$data['data25'].''.$data['data26'].''.$data['data27']
        .''.$data['data28'].''.$data['data29'].''.$data['data30'].''.$data['data31'].''.$data['data32'];

        // dd($data['datatotal']);
        // echo "function:".str_pad($line,14,".",STR_PAD_RIGHT);  
        // echo "<br>";

        // if(strlen($line) > 15){
        //     $line = mb_substr($line, 0, 15).'...';
        //     }
        //     echo $line;
        //     echo "<br>";

      
        // for($i = 1;$i <$count_a;$i++)
        // {
        //     echo $line[$i];
        //     echo "<br>";
        // }
        // $ar = array();
        // for($i = 0;$i < strlen($line); $i++)
        // {
        //     echo array_push($ar, substr($line,$i,1));
        // }
        // echo str_pad($mani,15,".");  

        // echo str_pad("line", 11, "pp", STR_PAD_BOTH )."\n";
        // echo str_pad($line, 20, "-=", STR_PAD_LEFT)."\n";
        // echo str_pad($line,  15, "*"). "\n"; 
        // echo str_pad($line,5,"$",STR_PAD_LEFT);
        // $myFile = new SplFileObject('D:\Authen\nhso_token.txt');
        // while (!$myFile->eof()) {
        //     echo $myFile->fgets() . PHP_EOL;
        // }

        // $file_handle = fopen('D:\Authen\nhso_token.txt', 'r'); 
        // function get_all_lines($file_handle) { 
        //     while (!feof($file_handle)) {
        //         yield fgets($file_handle);
        //     }
        // }        
        // $count = 0;        
        // foreach (get_all_lines($file_handle) as $line) {
        //     $count += 1;
        //     echo $count.". ".$line;
        // }        
        // fclose($file_handle);
        
        // dd($line);
       return view('authen_cid',$data,
        [
            $status            = $status,
            $birthday          =  $birthday ,
            $fname             = $fname,
            $lname             = $lname,
            $hmain             = $hmain,
            $hmain_name        =  $hmain_name,
            $hsub              = $hsub,
            $hsub_name         = $hsub_name,
            $maininscl         = $maininscl,
            $maininscl_main    = $maininscl_main,
            $maininscl_name    =  $maininscl_name,
            $expdate           = $expdate,
        ]
    );  
    }

    public function check_sit(Request $req )
    {  
        $cid_authen = $req->check_cid_authen;
        $cid = $req->check_cid;
        $token_ = $req->token;
        // dd($token_);
        // $client = new \SoapClient($wsdl, $options);
        $client = new SoapClient("http://ucws.nhso.go.th/ucwstokenp1/UCWSTokenP1?wsdl",
            array(
                "uri" => 'http://ucws.nhso.go.th/ucwstokenp1/UCWSTokenP1?xsd=1',
                                "trace"      => 1,    
                                "exceptions" => 0,    
                                "cache_wsdl" => 0 
                )
            );
            $params = array(
                'sequence' => array(
                    "user_person_id" => "$cid",
                    "smctoken" => "$token_",
                    "person_id" => "$cid_authen",
            )
        );         
        $result = $client->__soapCall('searchCurrentByPID',$params);
        // dd($result);
        foreach ($result as $key => $value) {
            // $status            = $value->status;
            // $birthday          = $value->birthdate;
            $fname             = $value->fname;
            $lname             = $value->lname;
            $hmain             = $value->hmain;
            $hmain_name        = $value->hmain_name;
            // $hsub              = $value->hsub;
            // $hsub_name         = $value->hsub_name;
            $maininscl         = $value->maininscl;
            $maininscl_main    = $value->maininscl_main;
            $maininscl_name    = $value->maininscl_name;
            // $expdate           = $value->expdate; 

            // $hmain_op           = $value->hmain_op; 
            // $hmain_op_name      = $value->hmain_op_name; 
            // $mastercup_id       = $value->mastercup_id; 
            $person_id          = $value->person_id; 
            $subinscl           = $value->subinscl; 
            $subinscl_name      = $value->subinscl_name; 

        }
        // dd($result);
        $ip = $req->ip();
        $collection = Http::get('http://'.$ip.':8189/api/smartcard/read')->collect();
        $data['patient'] =  DB::connection('mysql')->select('select cid,hometel from patient limit 10');

       return view('check_sit',$data,[
        'result'          =>  $result,
        // 'birthday'        =>  $birthday,
        'fname'           =>  $fname,
        'lname'           =>  $lname,
        'hmain'           =>  $hmain,
        'hmain_name'      =>  $hmain_name,
        // 'hsub'            =>  $hsub,
        // 'hsub_name'       =>  $hsub_name,
        'maininscl'       =>  $maininscl,
        'maininscl_main'  =>  $maininscl_main,
        'maininscl_name'  =>  $maininscl_name,
        // 'hmain_op'        =>  $hmain_op,
        // 'hmain_op_name'   =>  $hmain_op_name,
        // 'mastercup_id'    =>  $mastercup_id,
        'person_id'       =>  $person_id,
        'subinscl'        =>  $subinscl,
        'subinscl_name'   =>  $subinscl_name,

        'collection1' => $cid_authen,
        'collection2' => $collection['fname'],
        'collection3' => $collection['lname'],
        // 'collection4' => $collection['birthDate'],
        'collection5' => $collection['transDate'],
        'collection6' => $collection['mainInscl'],
        'collection7' => $collection['subInscl'],
        'collection8' => $collection['age'],
        'collection9' => $collection['checkDate'],
        // 'collection10' => '1e6b66ba-d51c-492e-baa7-304a96cc91ad',
        'collection10' => $collection['correlationId'],
        'collection11' => $collection['checkDate'],
        'collection' => $collection
       ]);  
    }

}

