<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [

            ['id' => 1,'parent_id' => 0, 'name' => 'Kuş Sesleri',],
            ['id' => 2,'parent_id' => 0, 'name' => 'Kedi Sesleri',],
            ['id' => 3,'parent_id' => 0, 'name' => 'Köpek Sesleri',],

        ];

        foreach ($items as $item) {
            \App\Models\Category::create($item);
        }
    }
}
