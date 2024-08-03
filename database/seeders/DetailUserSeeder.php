<?php

namespace Database\Seeders;

use App\Models\DetailUser;
use App\Models\Division;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DetailUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        $faker = Faker::create();

        $divisi = Division::pluck('id')->toArray();

        $shift = Shift::pluck('id')->toArray();

        foreach($users as $user) {
            $detailUser = new DetailUser();
            $detailUser->username = $user->username;
            $detailUser->nama = $faker->name();
            $detailUser->asal_sekolah = "UPN";
            $detailUser->tempat_lahir = $faker->city();
            $detailUser->tanggal_lahir = $faker->date('Y-m-d');
            $detailUser->nomor_hp = $faker->phoneNumber();
            $detailUser->nip = $faker->unique()->numerify('UPN/###');
            $tanggal_masuk = $faker->date('Y-m-d');
            $detailUser->tanggal_masuk = $tanggal_masuk;
            $detailUser->tanggal_keluar = $faker->dateTimeBetween($tanggal_masuk, '+1 month')->format('Y-m-d');
            $randomDiv = array_rand($divisi);
            $divValue = $divisi[$randomDiv];
            $detailUser->divisi_id = $divValue;
            $randomShift = array_rand($shift);
            $shiftValue = $shift[$randomShift];
            $detailUser->shift_id = $shiftValue;
            $detailUser->save();
        }
    }
}