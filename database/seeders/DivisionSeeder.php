<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DivisionSeeder extends Seeder
{
    public function run()
    {
        $divisions = [
            'UI/UX Designer',
            'Programmer',
            'Desain Grafis',
            'Fotografer',
            'Videografer',
            'Digital Marketing',
            'Human Resource',
            'Marketing & Sales',
            'Marcom/Public Relation',
            'Content Writer',
            'Content Planner',
            'Administrasi',
            'Project Manager',
            'Research & Development',
            'Social Media Specialist',
            'Tiktok Creator',
            'Host/Presenter',
            'Voice Over Talent',
            'Las',
        ];

        foreach ($divisions as $division) {
            DB::table('divisions')->insert([
                'nama_divisi' => $division,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
