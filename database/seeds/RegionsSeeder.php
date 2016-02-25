<?php

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = [
            '', '---', 'sev', 'simf', 'ubk', 'evp', 'feo'
        ];

        foreach ($regions as $region) {
            Region::create(['id' => $region]);
        }
    }
}
