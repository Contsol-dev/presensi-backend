<?php

namespace Database\Seeders;

use App\Models\Log;
use App\Models\User;
use App\Models\DetailUser;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LogDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('username')->toArray();
        $faker = Faker::create();
        $bool = [true, false];
        $kehadiran = ['hadir', 'izin', 'tidak hadir'];

        for ($i=0; $i < 10; $i++) {
            $choosen = $users[array_rand($users)];
            $user = DetailUser::where('username', $choosen)->first();

            if ($user) {
                $log = new Log();
                $status = $kehadiran[array_rand($kehadiran)];
                if ($status == 'hadir') {
                    $log->username = $choosen;
                    $log->tanggal = Carbon::today()->format('Y-m-d');
                    $log->masuk = $faker->time('H:i:s');
                    $log->istirahat = $faker->time('H:i:s');
                    $log->kembali = $faker->time('H:i:s');
                    $log->pulang = $faker->time('H:i:s');
                    $log->log_activity = $faker->sentence();
                    $log->kebaikan = $faker->sentence();
                    $log->catatan = $faker->sentence();
                    $log->terlambat_masuk = $bool[array_rand($bool)];
                    $log->istirahat_awal = $bool[array_rand($bool)];
                    $log->terlambat_kembali = $bool[array_rand($bool)];
                    $log->pulang_awal = $bool[array_rand($bool)];
                    $log->kehadiran = 'hadir';
                    $log->save();
                } else if ($status == 'tidak hadir') {
                    $log->username = $choosen;
                    $log->tanggal = Carbon::today()->format('Y-m-d');
                    $log->kehadiran = 'tidak hadir';
                    $log->save();
                } else if ($status == 'izin') {
                    $log->username = $choosen;
                    $log->tanggal = Carbon::today()->format('Y-m-d');
                    $log->kehadiran = 'izin';
                    $log->save();
                }
            }
        }
    }
}
