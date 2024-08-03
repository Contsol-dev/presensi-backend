<?php

namespace Database\Seeders;

use App\Models\DetailUser;
use App\Models\Log;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('username')->toArray();
        $faker = Faker::create();
        $bool = [true, false];

        // Fungsi untuk menghasilkan tanggal acak dalam rentang tertentu
        function getRandomDateWithinRange($faker, $startDate, $endDate) {
            if (!$startDate || !$endDate) {
                return $faker->date('Y-m-d');
            }

            return $faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d');
        }

        foreach ($users as $username) {
            $detailUser = DetailUser::where('username', $username)->first();

            if ($detailUser) {
                $tanggalMasuk = $detailUser->tanggal_masuk;
                $tanggalKeluar = $detailUser->tanggal_keluar;

                for ($i = 0; $i < 10; $i++) {
                    $log = new Log();
                    $log->username = $username;
                    $log->tanggal = getRandomDateWithinRange($faker, $tanggalMasuk, $tanggalKeluar);
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
                }

                for ($i = 0; $i < 10; $i++) {
                    $log = new Log();
                    $log->username = $username;
                    $log->tanggal = getRandomDateWithinRange($faker, $tanggalMasuk, $tanggalKeluar);
                    $log->kehadiran = 'tidak hadir';
                    $log->save();
                }

                for ($i = 0; $i < 10; $i++) {
                    $log = new Log();
                    $log->username = $username;
                    $log->tanggal = getRandomDateWithinRange($faker, $tanggalMasuk, $tanggalKeluar);
                    $log->kehadiran = 'izin';
                    $log->save();
                }
            }
        }
    }
}