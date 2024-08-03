<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            DivisionSeeder::class,
            ShiftsSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            DetailUserSeeder::class,
            LogSeeder::class,
            CategoriesSeeder::class,
            SubcategoriesSeeder::class,
        ]);
    }
}