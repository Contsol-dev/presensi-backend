<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\User;
use App\Models\Admin;
use App\Models\DetailUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminPageController extends Controller
{
    public function dashboard()
    {
      $pemagang = DetailUser::whereNull('tanggal_keluar')->count();
      $alumni = DetailUser::whereNotNull('tanggal_keluar')->count();
      $hadir = Log::where('kehadiran', 'hadir')->whereDate('tanggal', Carbon::today())->count();
      $izin = Log::where('kehadiran', 'izin')->whereDate('tanggal', Carbon::today())->count();
      $tidak_hadir = Log::where('kehadiran', 'tidak hadir')->whereDate('tanggal', Carbon::today())->count();

      return response()->json([
        'pemagang' => $pemagang,
        'alumni' => $alumni,
        'hadir' => $hadir,
        'izin' => $izin,
        'tidak_hadir' => $tidak_hadir,
      ]);
    }

    public function profile($admin_id) {
        $profile = Admin::where('id', '=', $admin_id)
            ->first();

        if ($profile) {
            return response()->json(['success' => true, 'profile' => $profile], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan'], 404);
        }
    }

    public function postProfile(Request $request) {
        $messages = [
            'admin_id.required' => 'Id admin tidak ada',
            'photo.image' => 'File harus berupa gambar',
            'photo.max' => 'Ukuran maksimal 2MB'
        ];

        $validator = Validator::make($request->all(), [
            'admin_id' => 'required',
            'photo' => 'nullable|image|max:2048'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $profile = Admin::where('id', '=', $request->admin_id)
            ->first();

        if ($profile) {
            if ($request->nama) {
                $profile->nama = $request->nama;
            }
            if ($request->email) {
                $profile->email = $request->email;
            }
            if ($request->no_hp) {
                $profile->no_hp = $request->no_hp;
            }
            if ($request->alamat) {
                $profile->alamat = $request->alamat;
            }
            if ($request->about) {
                $profile->about = $request->about;
            }
            if ($request->hasFile('photo')) {
                $filename = time(). '.'. $request->photo->extension();
                $request->photo->move(public_path('admin_photos'), $filename);
                $profile->photo = 'admin_photos/' . $filename;
            }

            $profile->save();

            return response()->json(['success' => true, 'message' => 'Profil Admin tersimpan']);
        } else {
            return response()->json(['success' => false, 'message' => "Admin tidak ditemukan"]);
        }
    }

    public function getPresensi(Request $request) {
        $messages = [
            'tanggal.date' => 'Format tanggal tidak valid',
            'tanggal.exists' => 'Tanggal tidak ada',
            'filter.string' => 'Filter harus berupa string',
            'nama.string' => 'Nama harus berupa string'
        ];

        $validator = Validator::make($request->all(), [
            'tanggal' => 'nullable|date|date_format:Y-m-d|exists:logs,tanggal',
            'filter' => 'nullable|string',
            'nama' => 'nullable|string'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $tanggal = Carbon::today();

        if ($request->tanggal) {
            $tanggal = $request->tanggal;
        }

        $presensi = Log::join('detail_users', 'logs.username', '=', 'detail_users.username')
                ->select('detail_users.nama', 'logs.masuk', 'logs.pulang', 'logs.istirahat', 'logs.kembali', 'logs.log_activity', 'logs.kebaikan', 'logs.kehadiran')
                ->where('logs.tanggal', '=', $tanggal)
                ->get();

        if ($request->filter) {
            $filter = $request->filter;
            $presensi = Log::join('detail_users', 'logs.username', '=', 'detail_users.username')
                ->select('detail_users.nama', 'logs.masuk', 'logs.pulang', 'logs.istirahat', 'logs.kembali', 'logs.log_activity', 'logs.kebaikan', 'logs.kehadiran')
                ->where('logs.tanggal', '=', $tanggal)
                ->where('logs.kehadiran', '=', $filter)
                ->get();
        }

        if ($request->nama) {
            $presensi = Log::join('detail_users', 'logs.username', '=', 'detail_users.username')
                ->select('detail_users.nama', 'logs.masuk', 'logs.pulang', 'logs.istirahat', 'logs.kembali', 'logs.log_activity', 'logs.kebaikan', 'logs.kehadiran')
                ->where('logs.tanggal', '=', $tanggal)
                ->where('detail_users.nama', 'LIKE', '%' . $request->nama . '%')
                ->get();
            if ($request->filter) {
                $filter = $request->filter;
                $presensi = Log::join('detail_users', 'logs.username', '=', 'detail_users.username')
                    ->select('detail_users.nama', 'logs.masuk', 'logs.pulang', 'logs.istirahat', 'logs.kembali', 'logs.log_activity', 'logs.kebaikan', 'logs.kehadiran')
                    ->where('logs.tanggal', '=', $tanggal)
                    ->where('logs.kehadiran', '=', $filter)
                    ->where('detail_users.nama', 'LIKE', '%' . $request->nama . '%')
                    ->get();
            }
        }

        $hadir = Log::where('kehadiran', '=', 'hadir')->count();
        $izin = Log::where('kehadiran', '=', 'izin')->count();
        $tidakHadir = Log::where('kehadiran', '=', 'tidak hadir')->count();

        return response()->json([
            'success' => true,
            'presensi' => $presensi,
            'hadir' => $hadir,
            'izin' => $izin,
            'tidakHadir' => $tidakHadir
        ]);
    }

    public function getDetailPresensi(Request $request) {
        $messages = [
            'filter.string' => 'Filter harus berupa string',
            'username.required' => 'Username harus ada',
            'username.string' => 'Username harus berupa string',
            'username.exists' => 'Username tidak ada/belum terdaftar'
        ];

        $validator = Validator::make($request->all(), [
            'filter' => 'nullable|string',
            'username' => 'required|string|exists:users,username'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $magang = DetailUser::where('username', '=', $request->username)
            ->select('tanggal_masuk', 'tanggal_keluar')
            ->first();

        $presensi = Log::where('username', '=', $request->username)
            ->get();

        return response()->json(['success' => true, 'magang' => $magang, 'presensi' => $presensi]);

        // TODO : nambahin data shift sama hitung"an jam kerja dll
    }
}
