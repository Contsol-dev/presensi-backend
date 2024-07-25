<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as Logger;

class LogsController extends Controller
{
    public function logBaru(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,username',
            'tanggal' => 'required|date',
        ]);

        $user = User::where('username', $request->username)->first();

        $log = new Log();
        $log->user_id = $user->id;
        $log->tanggal = $request->tanggal; // '2024-07-25'
        // $log->tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
        $log->save();

        return response()->json([
            'message' => 'Log entry created successfully',
            'log' => $log
        ], 201);
    }

    public function masuk(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:logs,user_id',
            'tanggal' => 'required|date|exists:logs,tanggal',
            'masuk' => 'required|date_format:H:i:s',
        ]);

        $log = Log::where('user_id', $request->user_id)
                    ->where('tanggal', $request->tanggal)
                    ->first();

        if ($log) {
            $log->masuk = $request->masuk;
            $log->save();

            return response()->json([
                'message' => 'Sukses update jam masuk',
                'masuk' => $log->masuk,
                'next' => 'istirahat'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Gagal'
            ], 404);
        }
    }

    public function istirahat(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:logs,user_id',
            'tanggal' => 'required|date|exists:logs,tanggal',
            'istirahat' => 'required|date_format:H:i:s',
        ]);

        $log = Log::where('user_id', $request->user_id)
                    ->where('tanggal', $request->tanggal)
                    ->first();

        if ($log) {
            $log->istirahat = $request->istirahat;
            $log->save();

            return response()->json([
                'message' => 'Sukses update jam masuk',
                'istirahat' => $log->istirahat,
                'next' => 'kembali'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Gagal'
            ], 404);
        }
    }
    
    public function kembali(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:logs,user_id',
            'tanggal' => 'required|date|exists:logs,tanggal',
            'kembali' => 'required|date_format:H:i:s',
        ]);

        $log = Log::where('user_id', $request->user_id)
                    ->where('tanggal', $request->tanggal)
                    ->first();

        if ($log) {
            $log->kembali = $request->kembali;
            $log->save();

            return response()->json([
                'message' => 'Sukses update jam kembali',
                'kembali' => $log->kembali,
                'next' => 'pulang'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Gagal'
            ], 404);
        }
    }

    public function pulang(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:logs,user_id',
            'tanggal' => 'required|date|exists:logs,tanggal',
            'pulang' => 'required|date_format:H:i:s',
        ]);

        $log = Log::where('user_id', $request->user_id)
                    ->where('tanggal', $request->tanggal)
                    ->first();

        if ($log) {
            $log->pulang = $request->pulang;
            $log->save();

            return response()->json([
                'message' => 'Sukses update jam kembali',
                'pulang' => $log->pulang,
                'next' => ''
            ], 200);
        } else {
            return response()->json([
                'message' => 'Gagal'
            ], 404);
        }
    }
    
    public function kebaikan(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:logs,user_id',
            'tanggal' => 'required|date|exists:logs,tanggal',
            'kebaikan' => 'required',
        ]);
    
        $log = Log::where('user_id', $request->user_id)
                    ->where('tanggal', $request->tanggal)
                    ->first();
    
        if ($log) {
            $log->kebaikan = $request->kebaikan;
            $log->save();
    
            return response()->json([
                'message' => 'Sukses update kebaikan',
                'kebaikan' => $log->kebaikan
            ], 200);
        } else {
            return response()->json([
                'message' => 'Gagal'
            ], 404);
        }
    }
    
    public function logActivity(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:logs,user_id',
            'tanggal' => 'required|date|exists:logs,tanggal',
            'log_activity' => 'string',
        ]);
    
        $log = Log::where('user_id', $request->user_id)
                    ->where('tanggal', $request->tanggal)
                    ->first();
    
        if ($log) {
            $log->log_activity = $request->log_activity;
            $log->save();
    
            return response()->json([
                'message' => 'Sukses update log_activity',
                'log_activity' => $log->log_activity
            ], 200);
        } else {
            return response()->json([
                'message' => 'Gagal'
            ], 404);
        }
    }
}

