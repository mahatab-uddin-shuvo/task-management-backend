<?php

namespace Database\Seeders;

use App\Models\TaskAssignment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product =  new TaskAssignment();
        $product->name = "Samasung s20";
        $product->stock_qty = 20;
        $product->price = 20000;
        $product->disc = 700;
        $product->save();

        $product =  new TaskAssignment();
        $product->name = "Sony Tv";
        $product->stock_qty = 15;
        $product->price = 10000;
        $product->disc = 200;
        $product->save();

        $product =  new TaskAssignment();
        $product->name = "Router";
        $product->stock_qty = 20;
        $product->price = 2000;
        $product->disc = 100;
        $product->save();

        $product =  new TaskAssignment();
        $product->name = "Washing Machine";
        $product->stock_qty = 20;
        $product->price = 20000;
        $product->disc = 500;
        $product->save();

        $product =  new TaskAssignment();
        $product->name = "Monitor";
        $product->stock_qty = 20;
        $product->price = 6000;
        $product->disc = 300;
        $product->save();
    }
}
