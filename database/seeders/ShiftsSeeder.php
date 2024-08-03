<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShiftsSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('shifts')->insert([
            'nama_shift' => 'Pagi',
            'masuk' => '08:00:00',
            'istirahat' => '11:30:00',
            'kembali' => '13:00:00',
            'pulang' => '16:30:00',
        ]);

        DB::table('shifts')->insert([
            'nama_shift' => 'Siang',
            'masuk' => '13:30:00',
            'istirahat' => '17:00:00',
            'kembali' => '18:30:00',
            'pulang' => '22:00:00',
        ]);

        DB::table('shifts')->insert([
            'nama_shift' => 'Malam',
            'masuk' => '19:00:00',
            'istirahat' => '22:30:00',
            'kembali' => '00:00:00',
            'pulang' => '03:30:00',
        ]);
    }
}