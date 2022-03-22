<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Response;
use App\Models\General\LevelOne;
use App\Models\General\LevelTwo;
use App\Models\General\LevelThree;
use App\Models\General\LevelFour;

class CsvImportsController extends Controller
{
    public function RegionsImport(Request $request){
    	    $regioncsv = $request->file('importfile');
            $regionfilename=$regioncsv->getClientOriginalName();
            $regioncsv->move('importfile',$regionfilename);
            $regionsfile = $request->file($regioncsv);

            // dd($regioncsv);
            // ''get the number of rows in the csv file
            $fp = file(public_path () . '/importfile/'.$regionfilename);
            $fp = count($fp);
            if($fp <= 1){
            	// dd("passed");

                return redirect()->back()->with('errors','The file uploaded does not contain any data. Please check the file and try again.');
            }
          $ext = substr($regionfilename, strrpos($regionfilename, '.') + 1);
            if ($ext === "xls" OR $ext === "csv" OR $ext === "xlsx"){

            }else{
                return redirect()->back()->with('errors', 'You can only upload CSV (.csv) or Excel (.xls or .xlsx) files. Kindly download our Upload to CSV/EXCEL Guide for directions on how the file should be populated.');

            }
                if (($data = Excel::load(public_path() . '/importfile/' . $regionfilename)->get()) !== FALSE) {
                    // $flag = true;
                    //  while ( ($data = fgetcsv ( $handle, 1000, ',' )) !== FALSE ) {
                	// dd("success");
                     $count = 1;

                    foreach ($data as $key => $value) {
                        $data = $value->toArray();
                        if (isset($data['levelone_id']) AND isset($data['levelone_name']) AND isset($data['leveltwo_id']) AND isset($data['leveltwo_name']) AND isset($data['levelthree_id']) AND isset($data['levelthree_name']) AND isset($data['levelfour_id']) AND isset($data['levelfour_name']) AND isset($data['levelfive_id']) AND isset($data['levelfour_name'])) {

                        	// $leveloneitems = 1;
                        	// if($leveloneitem = $data['levelone_id'] = $leveloneitem){

                        			$levelone = new LevelOne();
                        			$levelone->name = $data['levelone_name'];
                        			$levelone->country_id =1;
                        			$levelone->save();

                        			$leveltwo = new LevelTwo();
                        			$leveltwo->name = $data['leveltwo_name'];
                        			$leveltwo->level_one_id = $levelone->id;
                        			$leveltwo->save();

                        	// }




                        } else {
                            return redirect()->back()->with('errors', 'You can only upload CSV (.csv) or Excel (.xls or .xlsx) files. Kindly download our Upload to CSV/EXCEL Guide for directions on how the file should be populated.');

                        }
   		 }

		}
	}
}
