<?php

namespace App\Http\Controllers;

use App\Models\TeamPricingModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TeamPricingController extends Controller
{
    //
    public function index(){
        $user = Auth::user();

        $teamPricing = TeamPricingModel::where('is_active', true);
        if($user->user_type === 2){
            $teamPricing->where('team_id', $user->team_id);
        }
        $paginated = $teamPricing->get();
        $allowUpload = $user->user_type == 2 || $user->user_type == 1;

        return Inertia::render('TeamPricing/IndexPage', ['teamPricing' => $paginated, 'allowUpload' => $allowUpload]);
    }

    public function upload(){
        return Inertia::render('TeamPricing/UploadPage');
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
            $inputFileType = 'Xlsx';
            
            // Create a reader and load the file
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($file->getPathName());
    
            // Convert spreadsheet to array and skip the first row
            $dataArray = $spreadsheet->getActiveSheet()->toArray();
    
            // Remove the first row (headers) if it exists
            $dataWithoutHeader = array_slice($dataArray, 1);
            $added = [];
            $updated = [];
            $error_import = [];

            foreach($dataWithoutHeader as $pricing){
                $part_type = $pricing[0];
                $manufacturer = $pricing[1];
                $model_number = $pricing[2];
                $list_price = $pricing[3];
                $multiplier = $pricing[4] ? $pricing[4] : 0;
                $static_price = $pricing[5] ? $pricing[5] : 0;
                $team_price = $multiplier ? $multiplier * $list_price : $static_price;
                $team_id = Auth::user()->team_id;

                $data = [
                    'team_id' => $team_id,
                    'part_type' => $part_type,
                    'manufacturer' => $manufacturer, 
                    'model_number' => $model_number,
                    'list_price' => $list_price,
                    'multiplier' => $multiplier,
                    'static_price' => $static_price,
                    'team_price' => $team_price,
                ];

                $exist = TeamPricingModel::where('manufacturer', $manufacturer)
                ->where('model_number', $model_number)
                ->where('team_id', $team_id)
                ->first();

                if($exist){
                    // update
                    $exist->update($data);
                    array_push($updated, $data);
                } else {
                    // add
                    TeamPricingModel::create($data);
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
