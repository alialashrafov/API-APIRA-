<?php

use Illuminate\Database\Seeder;
use App\Models\Category;
use Faker\Generator;
use Illuminate\Support\Facades\DB;
class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker\Generator $faker)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Category::truncate();

        for($i = 0; $i < 30; $i++) {
            $category_name = rtrim($faker->sentence(1), '.');

            Category::create([
                'name' => $category_name,
                'slug' => \Illuminate\Support\Str::slug($category_name)
            ]);
        }
        DB::table('category_product')->insert(['product_id' => 1, 'category_id' => 1]);
        DB::table('category_product')->insert(['product_id' => 1, 'category_id' => 2]);
        DB::table('category_product')->insert(['product_id' => 2, 'category_id' => 1]);
        DB::table('category_product')->insert(['product_id' => 2, 'category_id' => 2]);
        DB::table('category_product')->insert(['product_id' => 2, 'category_id' => 3]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

    }

}
