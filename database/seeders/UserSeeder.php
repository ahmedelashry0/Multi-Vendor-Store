<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=> 'ahmed khaled',
            'email'=> 'ahmed@gmail.com',
            'password'=> Hash::make('123456'),
            'phone_number'=> '01111111111',
        ]);
        DB::table('users')->insert([
            'name'=> 'abdo khaled',
            'email'=> 'abdo@gmail.com',
            'password'=> Hash::make('123456'),
            'phone_number'=> '0111111111',
            ]);
    }
}
