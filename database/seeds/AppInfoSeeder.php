<?php

use Illuminate\Database\Seeder;

class AppInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\AppInfo::create(['app_version'=>100]);
    }
}
