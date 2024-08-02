<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as Logger;
use Illuminate\Support\Facades\Validator;

class LogsController extends Controller
{
    public function logBaru(Request $request) {
        $messages = [
            'username.exists' => 'Username tidak ada',
            'tanggal.required' => 'Tanggal wajib diisi'
        ];

        $validator = Validator::make([
            'username' => 'required|exists:users,username',
            'tanggal' => 'required|date',
        ], $messages);

        // if (!Auth::check()) {
        //     return response()->json(['message' => 'Uanuthorized'], 401);
        // }

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $now = Carbon::now();
        $tanggal = $now->format('Y-m-d');

        $todayLog = Log::where('tanggal', '=', $tanggal)
            ->first();

        if ($todayLog) {
            return response()->json([
                'message' => 'Log already exists'
            ]);
        } else {
            $log = new Log();
            $log->username = $request->username;
            $log->tanggal = $request->tanggal; // '2024-07-25'
            $log->save();

            return response()->json([
                'message' => 'Log entry created successfully',
                'log' => $log
            ], 201);
        }
    }

    public function getLog(Request $request) {
        $log = Log::where('username', '=', $request->username)
            ->where('tanggal', '=', $request->tanggal)
            ->first();
        return response()->json(['log' => $log]);
    }

    public function getLogs(Request $request) {
        $logs = Log::where('username', '=', $request->username)
            ->orderBy('tanggal', 'desc')
            ->select('id', 'tanggal', 'log_activity')
            ->get();
        return response()->json(['logs' => $logs]);
    }

    public function masuk(Request $request) {
        $messages = [
            'username.exists' => 'User tidak ada'
        ];

        $validator = Validator::make([
            'username' => 'required|exists:logs,username',
            'tanggal' => 'required|date|exists:logs,tanggal',
            'masuk' => 'required|date_format:H:i:s',
        ], $messages);

        // if (!Auth::check()) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $log = Log::where('username', $request->username)
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
        $messages = [
            'username.exists' => 'User tidak ada',
            'tanggal.exists' => 'Log hari ini belum ada',
            'istirahat.required' => 'istirahat jam berapa',
            'istirahat.date_format' => 'Format jam salah'
        ];

        $validator = Validator::make([
            'username' => 'required|exists:logs,username',
            'tanggal' => 'required|date|exists:logs,tanggal',
            'istirahat' => 'required|date_format:H:i:s',
        ], $messages);

        $log = Log::where('username', $request->username)
                    ->where('tanggal', $request->tanggal)
                    ->first();

        // if (!Auth::check()) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        if ($log) {
            $log->istirahat = $request->istirahat;
            $log->save();

            return response()->json([
                'message' => 'Sukses update jam istirahat',
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
        $messages = [
            'username.exists' => 'User tidak ada',
            'tanggal.exists' => 'Log hari ini belum ada',
            'kembali.required' => 'kembali jam berapa',
            'kembali.date_format' => 'Format jam salah'
        ];

        $validator = Validator::make([
            'username' => 'required|exists:logs,username',
            'tanggal' => 'required|date|exists:logs,tanggal',
            'kembali' => 'required|date_format:H:i:s',
        ], $messages);

        // if (!Auth::check()) {
        //     return response()->json(['message' => 'Uanuthorized'], 401);
        // }

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $log = Log::where('username', $request->username)
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
        $messages = [
            'username.exists' => 'User tidak ada',
            'tanggal.exists' => 'Log hari ini belum ada',
            'pulang.required' => 'pulang jam berapa',
            'pulang.date_format' => 'Format jam salah'
        ];

        $validator = Validator::make([
            'username' => 'required|exists:logs,username',
            'tanggal' => 'required|date|exists:logs,tanggal',
            'pulang' => 'required|date_format:H:i:s',
        ], $messages);

        // if (!Auth::check()) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $log = Log::where('username', $request->username)
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
        $messages = [
            'username.exists' => 'User tidak ada',
            'tanggal.exists' => 'Log hari ini belum ada',
            'kebaikan.required' => 'Belum ada kebaikan yang kamu ceritakan'
        ];

        $validator = Validator::make([
            'username' => 'required|exists:logs,username',
            'tanggal' => 'required|date|exists:logs,tanggal',
            'kebaikan' => 'required',
        ], $messages);

        // if (!Auth::check()) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $log = Log::where('username', $request->username)
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
        $messages = [
            'username.exists' => 'User tidak ada',
            'tanggal.exists' => 'Log hari ini belum ada',
            'log_activity.required' => 'Belum ada log activity'
        ];

        $validator = Validator::make([
            'username' => 'required|exists:logs,username',
            'tanggal' => 'required|date|exists:logs,tanggal',
            'log_activity' => 'required|string',
        ], $messages);

        // if (!Auth::check()) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $log = Log::where('username', $request->username)
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