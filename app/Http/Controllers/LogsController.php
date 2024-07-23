<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;

class LogsController extends Controller
{
    public function masuk(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:logs,user_id',
            'tanggal' => 'required|date|exists:logs,tanggal',
            'masuk' => 'required|date_format:H:i:s',
        ]);

        $log = Log::where('user_id', $request->user_id)
                    ->where('tanggal', $request->masuk)
                    ->first();

        if ($log) {
            $log->masuk = $request->masuk;
            $log->save();

            return response()->json([
                'message' => 'Sukses update jam masuk',
                'masuk' => $log->masuk,
                'next' => 'Istirahat'
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
                'message' => 'Sukses update jam istirahat',
                'istirahat' => $log->istirahat,
                'next' => 'Kembali'
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
                'next' => 'Pulang'
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
}

