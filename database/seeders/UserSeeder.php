<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            ['name' => "Femi Fatokun"],
            ['name' => "Jay Soni"],
            ['name' => "Idris Badmus"],
            ['name' => "Daniel Adewale"],
            ['name' => "Tommy Shelby"]
        ]) ;  
    }
}
