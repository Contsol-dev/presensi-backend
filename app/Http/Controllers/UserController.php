<?php

namespace App\Http\Controllers;

use App\Models\AlumniFiles;
use App\Models\DetailUser;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function getFiles($username) {
        $files = AlumniFiles::where('username', $username)->first();
        $detailUser = DetailUser::where('username', $username)->first();
        return response()->json(['success' => true, 'files' => $files, 'nama' => $detailUser->nama, 'nip' => $detailUser->nip]);
    }
}