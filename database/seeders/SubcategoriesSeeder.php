<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubcategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategory1 = [
            'Desain Thinking',
            'Pemahaman Penerapan Desain'
        ];
        
        $subcategory2 = [
            'Desain Yang Menarik',
            'Pemecahan Masalah Pengguna',
            'Hasil Kerja'
        ];
        
        $subcategory3 = [
            'Aktif Presentasi',
            'Kejujuran',
            'Kedisiplinan',
            'Tanggung Jawab',
        ];

        foreach ($subcategory1 as $sc) {
            DB::table('nilai_subcategories')->insert([
                'nama_subkategori' => $sc,
                'category_id' => 1
            ]);
        }

        foreach ($subcategory2 as $sc) {
            DB::table('nilai_subcategories')->insert([
                'nama_subkategori' => $sc,
                'category_id' => 2
            ]);
        }

        foreach ($subcategory3 as $sc) {
            DB::table('nilai_subcategories')->insert([
                'nama_subkategori' => $sc,
                'category_id' => 3
            ]);
        }
    }
}
