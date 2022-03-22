<?php

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
         $this->call(SystemSeeder::class);
         $this->call(TestSeeder::class);
         $this->call(RegionsSeeder::class);
         $this->call(TransactionTypesSeeder::class);
         $this->call(repayment_plansTableSeeder::class);
    }
}
