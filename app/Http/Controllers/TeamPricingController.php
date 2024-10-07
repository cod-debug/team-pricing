<?php

namespace App\Http\Controllers;

use App\Models\TeamPricingModel;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TeamPricingController extends Controller
{
    //
    public function index(){
        $user = Auth::user();

        $teamPricing = TeamPricingModel::where('is_active', true);
        if($user->user_type === 2){
            $teamPricing->where('team_id', $user->team_id);
        }
        $paginated = $teamPricing->paginate(10);
        return Inertia::render('TeamPricing', ['teamPricing' => $paginated]);
    }
}
