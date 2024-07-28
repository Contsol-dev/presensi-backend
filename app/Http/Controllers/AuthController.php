<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        $messages = [
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'password.required' => 'Password wajib diisi',
            'konfirm_password.required' => 'Konfirmasi password wajib diisi',
            'konfirm_password.min' => 'Konfirmasi password minimal 8 karakter',
        ];

        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'nama' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'konfirm_password' => 'required|min:8|same:password',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $data = $request->all();
        if ($data['password'] === $data['konfirm_password']) {
            $user = new User();
            $user->nama = $data['nama'];
            $user->email = $data['email'];
            $user->username = $data['username'];
            $user->password = Hash::make($data['password']);
            $user->konfirmasi_email = false;
            $user->status_akun = 'aktif';
            $user->save();
            return response()->json(['success' => true, 'message' => 'register berhasil']);
        } else {
            return response()->json(['success' => false, 'message' => 'password tidak sama']);
        }
    }

    public function login(Request $request) {
        $messages = [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($data)) {
            return response()->json(['success' => true, 'message' => 'login sukses']);
        } else {
            Session::flash('error', 'Email atau password salah!');
            return response()->json(['success' => false, 'message' => 'login gagal']);
        }
    }

    public function logout() {
        Session::flush();
        Auth::logout();
        return response()->json(['success' => true, 'message' => 'logout berhasil']);
    }
}