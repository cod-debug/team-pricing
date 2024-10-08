<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemWidePartModel extends Model
{
    use HasFactory;
    protected $table = "system_wide_parts";

    protected $fillable = [
        'part_type',
        'manufacturer',
        'model_number',
        'list_price',
        'is_active'
    ];

    public function pricings(){
        return $this->hasMany(TeamPricingModel::class, 'system_part_id');
    }
}
