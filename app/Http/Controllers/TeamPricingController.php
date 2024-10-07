<?php

namespace App\Http\Controllers;

use App\Models\TeamPricingModel;
use Inertia\Inertia;

class TeamPricingController extends Controller
{
    //
    public function index(){
        $teamPricing = TeamPricingModel::paginate(10);
        return Inertia::render('TeamPricing', ['teamPricing' => $teamPricing]);
    }
}
