<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;

class TodayLogSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $dates = collect();
        
        // Generate random dates
        // for ($i = 0; $i < 10; $i++) {
        //     $dates->push(Carbon::today()->subDays(30));
        // }
        
        foreach ($users as $user) {
            $masuk = $this->randomTime();
            $istirahat = $this->randomTime($masuk);
            $kembali = $this->randomTime($istirahat);
            $pulang = $this->randomTime($kembali);

            $log_activity = $this->randomLogActivity();
            $kebaikan = $this->randomKebaikan();
            
            $log = Log::create([
                'username' => $user->username,
                'tanggal' => Carbon::today(),
                'masuk' => $masuk,
                'istirahat' => $istirahat,
                'kembali' => $kembali,
                'pulang' => $pulang,
                'log_activity' => $log_activity,
                'kebaikan' => $kebaikan,
                'terlambat_masuk' => $this->randomBoolean(),
                'istirahat_awal' => $this->randomBoolean(),
                'terlambat_kembali' => $this->randomBoolean(),
                'pulang_awal' => $this->randomBoolean(),
                'kehadiran' => $this->checkKehadiran($masuk, $istirahat, $kembali, $pulang)
            ]);
    }}

    private function randomTime($after = null)
    {
        $time = Carbon::today()->addHours(rand(7, 18))->addMinutes(rand(0, 59))->addSeconds(rand(0, 59));
        if ($after) {
            $time = $after->copy()->addHours(rand(1, 3))->addMinutes(rand(0, 59))->addSeconds(rand(0, 59));
        }
        return $time->format('H:i:s');
    }

    private function randomLogActivity()
    {
        $activities = ['Meeting', 'Coding', 'Reviewing', 'Testing'];
        return $activities[array_rand($activities)];
    }

    private function randomKebaikan()
    {
        $kebaikan = ['Helping colleague', 'Completing task early', 'Volunteering for project', 'Organizing team event'];
        return $kebaikan[array_rand($kebaikan)];
    }

    private function randomBoolean()
    {
        return (bool)rand(0, 1);
    }

    private function checkKehadiran($masuk, $istirahat, $kembali, $pulang)
    {
        return ($masuk && $istirahat && $kembali && $pulang) ? true : false;
    }
}
