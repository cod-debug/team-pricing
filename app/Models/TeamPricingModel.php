<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamPricingModel extends Model
{
    use HasFactory;
    protected $table = "team_pricings";

    protected $fillable = [
        'team_id',
        'system_part_id',
        'multiplier',
        'static_price',
        'team_price',
        'is_active'
    ];

    public function team(){
        return $this->belongsTo(TeamModel::class, 'team_id');
    }

    public function part(){
        return $this->belongsTo(SystemWidePartModel::class, 'team_id');
    }
}
