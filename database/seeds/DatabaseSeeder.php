<?php

use App\Category;
use App\Product;
use App\Transaction;
use App\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Temporarily disable the Foreign key checks so it can run
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // Be sure to restart the db to the original status
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();

        // Also truncate the pivot table, access with db facade as it doesnt have a model
        DB::table('category_product')->truncate();

        // Define seed creation variables for data
        $userQuantity = 200;
        $categoriesQuantity = 30;
        $productsQuantity = 1000;
        $transactionsQuantity = 1000;

        // Use the factories to create the data
        factory(User::class, $userQuantity)->create();
        factory(Category::class, $categoriesQuantity)->create();

        // Assign a random category to a new product
        factory(Product::class, $productsQuantity)->create()->each(
          function ($product) {
            $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');

            // Associate the new category with the product
            $product->categories()->attach($categories);
          }
        );

        factory(Transaction::class, $transactionsQuantity)->create();

    }
}
