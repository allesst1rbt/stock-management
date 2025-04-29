<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Category = Category::find(1);
        Product::factory(6, [ "category_id"=>$Category->id])->create();
        $Category = Category::find(2);

        Product::factory( 5, [ "category_id"=>$Category->id])->create();
    }
}
