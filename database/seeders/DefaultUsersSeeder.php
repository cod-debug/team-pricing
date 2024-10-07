<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $users = [
            [
                'name' => 'System Admin',
                'email' => 'system.admin@email.com',
                'password' =>  Hash::make('password'),
                'team_id' => null,
                'user_type' => 1
            ],
            [
                'name' => 'Team A Admin',
                'email' => 'teama.admin@email.com',
                'password' =>  Hash::make('password'),
                'team_id' => 1,
                'user_type' => 2
            ],
            [
                'name' => 'Team A Member',
                'email' => 'teama.member@email.com',
                'password' =>  Hash::make('password'),
                'team_id' => 1,
                'user_type' => 3
            ],
            [
                'name' => 'Team B Admin',
                'email' => 'teamb.admin@email.com',
                'password' =>  Hash::make('password'),
                'team_id' => 1,
                'user_type' => 2
            ],
            [
                'name' => 'Team B Member',
                'email' => 'teamb.member@email.com',
                'password' =>  Hash::make('password'),
                'team_id' => 1,
                'user_type' => 3
            ]
        ];

        foreach($users as $user){
            $exist = User::where('email', '=', $user['email'])->first();

            if(!$exist){
                User::create($user);
            }
        }
    }
}
