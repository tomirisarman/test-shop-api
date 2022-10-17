<?php

use App\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kenia = DB::table('categories')->where('name', 'Kenia')->first();
        $earlGrey = DB::table('categories')->where('name', 'Earl Grey')->first();
        $fruity = DB::table('categories')->where('name', 'With fruits')->first();

        $blackTeas = DB::table('products')->insert([
            [
                'name' => 'Piala Kenia for Family Dinner',
                'category_id' => $kenia->id,
                'price' => 570,
                'slug' => Str::slug('Piala Kenia for Family Dinner', '-')
            ],
            [
                'name' => 'Lipton "English Breakfast" in teabags',
                'category_id' => $earlGrey->id,
                'price' => 600,
                'slug' => Str::slug('Lipton "English Breakfast" in teabags', '-')
            ],
            [
                'name' => 'Tess with berries and stuff',
                'category_id' => $fruity->id,
                'price' => 750,
                'slug' => Str::slug('Tess with berries and stuff', '-')
            ],
        ]);

        $blackTeas = DB::table('product_features')->insert([
            [
                'product_id' => Product::inRandomOrder()->first()->id,
                'name' => 'Country',
                'value' => 'Kazakhstan',
            ],
            [
                'product_id' => Product::inRandomOrder()->first()->id,
                'name' => 'Weight',
                'value' => '250 gr',

            ],
        ]);

    }
}
