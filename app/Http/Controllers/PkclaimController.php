<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\support\Facades\Hash;
use Illuminate\support\Facades\Validator;
use App\Models\User;
use PDF;
use setasign\Fpdi\Fpdi;
use App\Models\Budget_year;
use Illuminate\Support\Facades\File;
use DataTables;
use Intervention\Image\ImageManagerStatic as Image;

class PkclaimController extends Controller
{
    public function pkclaim_info(Request $request)
    {
        $data['com_tec'] = DB::table('com_tec')->get();
        $data['users'] = User::get();

        return view('pkclaim.pkclaim_info', $data);
    }
    public function bk_getbar(Request $request)
    {
        $date = date("Y-m-d");
        $newDate = date('Y-m-d', strtotime($date . ' -1 months')); //ย้อนหลัง 1 เดือน  
        $newweek = date('Y-m-d', strtotime($date . ' -1 week')); //ย้อนหลัง 1 สัปดาห์  

        $type_chart5 = DB::connection('mysql3')->table('pttype')->select('pttype', 'name', 'pcode')->get(); 
        foreach ($type_chart5 as $item) {

            $data_count = DB::connection('mysql3')->table('ovst')->where('pttype', '=', $item->pttype)->WhereBetween('vstdate', [$newDate, $date])->count(); //ย้อนหลัง 1 เดือน  
            $data_count_week = DB::connection('mysql3')->table('ovst')->where('pttype', '=', $item->pttype)->WhereBetween('vstdate', [$newweek, $date])->count();  //ย้อนหลัง 1 สัปดาห์

            if ($data_count > 0) {
                $dataset[] = [
                    'label' => $item->name,
                    'count' => $data_count
                ];
            }

            if ($data_count_week > 0) {
                $dataset_2[] = [
                    'label_week' => $item->name,
                    'count_week' => $data_count_week
                ];
            }
        }
 
        $chartData_dataset = $dataset;
        $chartData_dataset_week = $dataset_2; 
        return response()->json([
            'status'             => '200', 
            'chartData_dataset_week'    => $chartData_dataset_week,
            'chartData_dataset'  => $chartData_dataset
        ]);
    }
}
