<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamPricingModel extends Model
{
    use HasFactory;
    protected $table = "team_pricing";

    protected $fillable = [
        'team_id',
        'part_type',
        'manufacturer',
        'model_number',
        'list_price',
        'multiplier',
        'static_price',
        'team_price',
        'is_active'
    ];

    public function team(){
        $this->belongsTo(TeamModel::class, 'team_id');
    }
}
