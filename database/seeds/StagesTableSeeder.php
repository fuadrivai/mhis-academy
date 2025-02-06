<?php

use App\Level;
use Illuminate\Database\Seeder;

class StagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Level::create([
            "level" => 0,
            "stage" => "Induction",
        ]);
        Level::create([
            "level" => 1,
            "stage" => "Stage 1",
        ]);
        Level::create([
            "level" => 2,
            "stage" => "Stage 2",
        ]);
        Level::create([
            "level" => 3,
            "stage" => "Stage 3",
        ]);
        Level::create([
            "level" => 4,
            "stage" => "Stage 4",
        ]);
    }
}
