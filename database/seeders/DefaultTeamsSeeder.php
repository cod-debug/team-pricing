<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeamModel;

class DefaultTeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $teams =[
            'Team A',
            'Team B'
        ];

        foreach($teams as $team){
            TeamModel::firstOrCreate([
                'name' => $team
            ]);
        }
        
    }
}
