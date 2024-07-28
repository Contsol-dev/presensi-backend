<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Mail;

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

        if (!$passwordReset) {
            return response()->json(['message' => 'Token salah.'], 404);
        }

        return response()->json([
            'message' => "Reset password berhasil",
            'email' => $passwordReset->email]
        );
    }

}
