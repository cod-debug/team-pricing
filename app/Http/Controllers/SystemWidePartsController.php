<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamModel;
use App\Models\TeamPricingModel;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemWidePartModel;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SystemWidePartsController extends Controller
{
    //
    public function index(){
        $user = Auth::user();

        $teamPricing = SystemWidePartModel::where('is_active', true)->get();

        $allowUpload = $user->user_type == 1;

        $data = [
            'teamPricing' => $teamPricing, 
            'allowUpload' => $allowUpload,
            'isSystemAdmin' => $user->user_type == 1,
            'isTeamAdmin' => $user->user_type == 2,
        ];

        if($user->user_type == 1){
            $data['teams'] = TeamModel::all();
        }

        return Inertia::render('SystemWideParts/IndexPage', $data);
    }

    public function upload(){
        return Inertia::render('SystemWideParts/UploadPage');
    }

    public function uploadPost(Request $request){
        // Check if the file is in the request
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    
        // Get the uploaded file
        $file = $request->file('file');
    
        // Check if file was uploaded successfully
        if (!$file->isValid()) {
            return response()->json(['error' => 'File upload error'], 400);
        }
    
        try {
            // Determine the file type based on the file extension
            $extension = $file->getClientOriginalExtension();
    
            if ($extension == 'xlsx') {
                $inputFileType = 'Xlsx';
            } elseif ($extension == 'csv') {
                $inputFileType = 'Csv';
            } else {
                return response()->json(['error' => 'Unsupported file type'], 400);
            }
            
            // Create a reader and load the file
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($file->getPathName());
    
            // Convert spreadsheet to array and skip the first row
            $dataArray = $spreadsheet->getActiveSheet()->toArray();
    
            // Remove the first row (headers) if it exists
            $parts = array_slice($dataArray, 1);
            $added = [];
            $updated = [];
            $error_import = [];

            set_time_limit(800);

            foreach($parts as $part){
                $is_active = $part[0] == 'Y';
                $part_type = $part[1];
                $manufacturer = $part[2];
                $model_number = $part[3];
                $list_price = $part[4] ? $part[4] : 0;

                $data = [
                    'part_type' => $part_type,
                    'manufacturer' => $manufacturer, 
                    'model_number' => $model_number,
                    'list_price' => $list_price,
                    'is_active' => $is_active
                ];

                $exist = SystemWidePartModel::where('manufacturer', $manufacturer)
                ->where('model_number', $model_number)
                ->first();

                if($exist){
                    // update
                    $exist->update($data);
                    array_push($updated, $data);
                } else {
                    // add
                    SystemWidePartModel::create($data);
                    array_push($added, $data);
                }

            }

            $res = [
                'message' => "Import done.",
                'added' => $added,
                'error_import' => $error_import,
                'updated' => $updated,
                
            ];

            // Return JSON response with the data (excluding the first row)
            return response()->json($res);
    
        } catch (\Exception $e) {
            // Handle any exceptions that occur during file reading or processing
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
