<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['name' => "Femi Fatokun"],
            ['name' => "Jay Soni"],
            ['name' => "Idris Badmus"],
            ['name' => "Daniel Adewale"],
            ['name' => "Tommy Shelby"]
        ]) ;  
    }
}
