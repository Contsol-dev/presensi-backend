<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function generateToken(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = Str::random(8);
        PasswordResetToken::updateOrCreate(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        Mail::to($request->email)->send(new ResetPassword($token));

        return response()->json([
            'message' => 'Request token reset password berhasil dibuat.'
        ], 200);
    }

    public function useToken($token) {
        $passwordReset = PasswordResetToken::where('token', $token)->first();
        $user = User::where('email', $passwordReset->email)->first();

        if (!$passwordReset) {
            return response()->json(['message' => 'Token salah.'], 404);
        }

        $passwordReset->token = Hash::make($passwordReset->email);
        $passwordReset->save();
        $user->password = Hash::make(Str::random(8));
        $user->save();

        return response()->json([
            'message' => "Reset password berhasil",
            'email' => $passwordReset->email
        ], 200);
    }

    public function newPassword(Request $request) {
        $messages = [
            'email.exists' => 'Email salah'
        ];

        $validator = Validator::make([
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'email' => 'required|exists:users,email',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $user = User::where('email', $request->email)
                ->first();
        if ($request->password === $request->confirm_password) {
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['success' => true, 'message' => 'penggantian password berhasil']);
        } else {
            return response()->json(['success' => false, 'message' => 'password tidak sama']);
        }
        
        
    }

}
