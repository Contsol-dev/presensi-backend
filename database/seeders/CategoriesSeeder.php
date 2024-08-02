<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category1 = [
            'Pengetahuan',
            'Kreativitas',
            'Lainnya',
        ];

        foreach ($category1 as $cat) {
            DB::table('nilai_categories')->insert([
                'nama_kategori' => $cat,
                'division_id' => 1
            ]);
        }
    }
}
