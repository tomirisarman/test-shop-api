<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Қара шай, көк шай

        $tea = DB::table('categories')->insertGetId([
            'name' => 'Tea',
        ]);

        $blackTea = DB::table('categories')->insertGetId([
            'name' => 'Black Tea',
            'parent_id'=>$tea,
        ]);

        $blackTeas = DB::table('categories')->insert([
            ['name' => 'Kenia', 'parent_id' => $blackTea],
            ['name' => 'Earl Grey', 'parent_id' => $blackTea],
            ['name' => 'With fruits', 'parent_id' => $blackTea],
        ]);

        $greenTea = DB::table('categories')->insertGetId([
            'name' => 'Green Tea',
            'parent_id'=>$tea,
        ]);

        $greenTeas = DB::table('categories')->insert([
            ['name' => 'Herbal', 'parent_id' => $greenTea],
            ['name' => 'Calming', 'parent_id' => $greenTea],
        ]);

    }
}
