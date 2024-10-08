<?php

namespace App\Http\Controllers;

use App\Models\TeamModel;
use App\Models\TeamPricingModel;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemWidePartModel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TeamPricingController extends Controller
{
    //
    public function index(){
        $user = Auth::user();

        $teamPricing = TeamPricingModel::where('is_active', true)
        ->where('team_id', $user->team_id)
        ->with('part')->get();

        $allowUpload = $user->user_type == 2;

        $data = [
            'teamPricing' => $teamPricing, 
            'allowUpload' => $allowUpload,
            'isAdmin' => $user->user_type == 1
        ];

        if($user->user_type == 1){
            $data['teams'] = TeamModel::all();
        }

        return Inertia::render('TeamPricing/IndexPage', $data);
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
                $part_type = $part[0];
                $manufacturer = $part[1];
                $model_number = $part[2];
                $list_price = $part[3] ? $part[3] : 0;
                $multiplier = $part[4];
                $static_price = $part[5];
                $team_price = $multiplier ? $multiplier * $list_price : $static_price;
                $team_id = Auth::user()->team_id;

                $system_wide_parts_data = [
                    'part_type' => $part_type,
                    'manufacturer' => $manufacturer, 
                    'model_number' => $model_number,
                    'list_price' => $list_price
                ];

                $system_wide_part = SystemWidePartModel::where('manufacturer', $manufacturer)
                ->where('model_number', $model_number)
                ->first();

                if($system_wide_part){
                    // update
                    $system_wide_part->update($system_wide_parts_data);
                } else {
                    // add
                    $system_wide_part = SystemWidePartModel::create($system_wide_parts_data);
                }

                $system_part_id = $system_wide_part->id;
                $team_pricing = TeamPricingModel::where('team_id', $team_id)->where('system_part_id', $system_part_id)->first();

                $team_pricing_data = [
                    'team_id' => $team_id,
                    'system_part_id' => $system_part_id,
                    'multiplier' => $multiplier,
                    'static_price' => $static_price,
                    'team_price' => $team_price,
                ];

                if($team_pricing){
                    // if team price already exists
                    $team_pricing->update($team_pricing_data);
                    array_push($updated, $system_wide_parts_data);
                } else {
                    TeamPricingModel::create($team_pricing_data);
                    array_push($added, $system_wide_parts_data);
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
