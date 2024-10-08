<?php

namespace App\Http\Controllers;

use App\Models\DetailUser;
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
            ->where('username', '=', $request->username)
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

        $shift = DetailUser::where('username', $request->username)
                ->with('shift')
                ->first();
        return response()->json([
            'log' => $log,
            'detail' => $shift->shift->nama_shift,
            'nip' => $shift->nip
        ]);
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

        $shift = DetailUser::where('username', $request->username)
                            ->with('shift')
                            ->first();

        if ($log) {
            $jam = $shift->shift->masuk;
            $terlambat = $request->masuk > $jam;

            $log->masuk = $request->masuk;
            $log->kehadiran = "hadir";
            $log->terlambat_masuk = $terlambat;
            $log->save();

            return response()->json([
                'message' => 'Sukses update jam masuk',
                'masuk' => $log->masuk,
                'terlambat_masuk' => $terlambat,
                'next' => 'istirahat',
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

        $shift = DetailUser::where('username', $request->username)
                    ->with('shift')
                    ->first();

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        if ($log) {
            $jam = $shift->shift->istirahat;
            $istirahat_awal = $request->istirahat < $jam;

            $log->istirahat = $request->istirahat;
            $log->istirahat_awal = $istirahat_awal;
            $log->save();

            return response()->json([
                'message' => 'Sukses update jam istirahat',
                'istirahat' => $log->istirahat,
                'istirahat_awal' => $istirahat_awal,
                'next' => 'kembali',
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

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $log = Log::where('username', $request->username)
                    ->where('tanggal', $request->tanggal)
                    ->first();

        $shift = DetailUser::where('username', $request->username)
                    ->with('shift')
                    ->first();

        if ($log) {
            $jam = $shift->shift->kembali;
            $terlambat_kembali = $request->kembali > $jam;

            $log->kembali = $request->kembali;
            $log->terlambat_kembali = $terlambat_kembali;
            $log->save();

            return response()->json([
                'message' => 'Sukses update jam kembali',
                'kembali' => $log->kembali,
                'terlambat_kembali' => $terlambat_kembali,
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

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $log = Log::where('username', $request->username)
                    ->where('tanggal', $request->tanggal)
                    ->first();

        $shift = DetailUser::where('username', $request->username)
                    ->with('shift')
                    ->first();

        if ($log) {
            $jam = $shift->shift->pulang;
            $pulang_awal = $request->pulang < $jam;
            
            $log->pulang = $request->pulang;
            $log->pulang_awal = $pulang_awal;
            $log->save();

            return response()->json([
                'message' => 'Sukses update jam kembali',
                'pulang' => $log->pulang,
                'pulang_awal' => $pulang_awal,
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

    public function editKehadiran(Request $request) {
        $messages = [
            'username.exists' => 'User tidak ada',
            'tanggal.exists' => 'Log hari ini belum ada',
        ];

        $validator = Validator::make([
            'username' => 'required|exists:logs,username',
            'tanggal' => 'required|date|exists:logs,tanggal',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $log = Log::where('username', $request->username)
                    ->where('tanggal', $request->tanggal)
                    ->first();

                    $log->kehadiran = $request->kehadiran;
                    $log->save();

                    return response()->json([
                        'message' => 'Sukses update kehadiran'
                    ], 200);
    }

    public function editLog(Request $request) {
        $log = Log::where('id', $request->id)->first();
        $log->log_activity = $request->log_activity;
        $log->save();
        return response()->json(['success' => true, 'message' => 'Log berhasil diubah']);
    }
}