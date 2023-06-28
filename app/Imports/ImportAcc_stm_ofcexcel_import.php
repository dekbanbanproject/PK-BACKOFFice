<?php

namespace App\Imports;

use App\Models\Acc_stm_ofc; 
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportAcc_stm_ofcexcel_import implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // return new Acc_stm_ofc([
        //     //
        // ]);
         // Acc_stm_ti_excel::truncate();
       
         #ตัดขีด, ตัด : ออก แล้วเรียงใหม่
        //  $dchdate_ = $row[7];
        //  $starttime = substr($dchdate_, 0, 5);
        //  $regday = substr($dchdate_, 0, 2);
        //  $regmo = substr($dchdate_, 3, 2);
        //  $regyear = substr($dchdate_, 6, 10);            
        //  $dchdate = $regyear.'-'.$regmo.'-'.$regday;

         $vstdate_ = $row[6];
         $starttime = substr($vstdate_, 0,5);
         $day = substr($vstdate_, 0, 2);
         $mo = substr($vstdate_, 3, 2);
         $year = substr($vstdate_, 6, 10);
         $vstdate = $year.'-'.$mo.'-'.$day; 
                    // dd(day);
             return new Acc_stm_ofc([ 
                 'repno' => $row[0],
                 'no' => $row[1],
                 'hn' => $row[2],
                 'an' => $row[3],
                 'cid' => $row[4],
                 'fullname' => $row[5],           
                 'vstdate' =>$vstdate,
                //  'dchdate' => $dchdate, 
                 'PROJCODE' => $row[8],
                 'AdjRW' => $row[9],
                 'price_req' => $row[10],
                 'prb' => $row[11], 
                 'room' => $row[12], 
                 'inst' => $row[13], 
                 'drug' => $row[14], 
                 'income' => $row[15], 
                 'refer' => $row[16], 
                 'waitdch' => $row[17], 
                 'service' => $row[18],  
                 'pricereq_all' => $row[19],  
                 'STMdoc' => $row[20]
             ]); 
    }
}
