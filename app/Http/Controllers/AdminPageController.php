<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\User;
use App\Models\Admin;
use App\Models\Shift;
use App\Models\DetailUser;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\NilaiCategory;
use App\Models\NilaiSubcategory;
use App\Models\NilaiUser;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password;

class AdminPageController extends Controller
{
    public function dashboard()
    {
      $pemagang = DetailUser::where('status_pegawai', '=', 'magang')->count();
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
            if ($profile->photo) {
                $profile->photo = url($profile->photo);
            }
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
                ->select('detail_users.username', 'detail_users.nama', 'logs.masuk', 'logs.pulang', 'logs.istirahat', 'logs.kembali', 'logs.log_activity', 'logs.kebaikan', 'logs.kehadiran')
                ->where('logs.tanggal', '=', $tanggal)
                ->get();

        if ($request->filter) {
            $filter = $request->filter;
            $presensi = Log::join('detail_users', 'logs.username', '=', 'detail_users.username')
                ->select('detail_users.username', 'detail_users.nama', 'logs.masuk', 'logs.pulang', 'logs.istirahat', 'logs.kembali', 'logs.log_activity', 'logs.kebaikan', 'logs.kehadiran')
                ->where('logs.tanggal', '=', $tanggal)
                ->where('logs.kehadiran', '=', $filter)
                ->get();
        }

        if ($request->nama) {
            $presensi = Log::join('detail_users', 'logs.username', '=', 'detail_users.username')
                ->select('detail_users.username', 'detail_users.nama', 'logs.masuk', 'logs.pulang', 'logs.istirahat', 'logs.kembali', 'logs.log_activity', 'logs.kebaikan', 'logs.kehadiran')
                ->where('logs.tanggal', '=', $tanggal)
                ->where('detail_users.nama', 'LIKE', '%' . $request->nama . '%')
                ->get();
            if ($request->filter) {
                $filter = $request->filter;
                $presensi = Log::join('detail_users', 'logs.username', '=', 'detail_users.username')
                    ->select('detail_users.username', 'detail_users.nama', 'logs.masuk', 'logs.pulang', 'logs.istirahat', 'logs.kembali', 'logs.log_activity', 'logs.kebaikan', 'logs.kehadiran')
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

        $magang = DetailUser::join('shifts', 'detail_users.shift_id', '=', 'shifts.id')
            ->where('detail_users.username', '=', $request->username)
            ->select('detail_users.nama', 'detail_users.nip', 'detail_users.tanggal_masuk', 'detail_users.tanggal_keluar', 'shifts.masuk', 'shifts.pulang')
            ->first();

        $masuk = Log::where('username', '=', $request->username)
            ->where('kehadiran', '=', 'hadir')
            ->count();
        $terlambatMasuk = Log::where('username', '=', $request->username)
            ->where('terlambat_masuk', '=', true)
            ->count();
        $istirahatAwal = Log::where('username', '=', $request->username)
            ->where('istirahat_awal', '=', true)
            ->count();
        $terlambatKembali = Log::where('username', '=', $request->username)
            ->where('terlambat_kembali', '=', true)
            ->count();
        $pulangAwal = Log::where('username', '=', $request->username)
            ->where('istirahat_awal', '=', true)
            ->count();

        $presensi = Log::where('username', '=', $request->username)
            ->select('tanggal', 'masuk', 'istirahat', 'kembali', 'pulang', 'log_activity', 'kebaikan', 'catatan', 'kehadiran')
            ->get();

        if ($request->filter) {
            $presensi = Log::where('username', '=', $request->username)
            ->where('kehadiran', 'LIKE', '%' . $request->filter . '%')
            ->select('tanggal', 'masuk', 'istirahat', 'kembali', 'pulang', 'log_activity', 'kebaikan', 'catatan', 'kehadiran')
            ->get();
        }

        return response()->json([
            'success' => true,
            'magang' => $magang,
            'presensi' => $presensi,
            'masuk' => $masuk,
            'terlambatMasuk' => $terlambatMasuk,
            'istirahatAwal' => $istirahatAwal,
            'terlambatKembali' => $terlambatKembali,
            'pulangAwal' => $pulangAwal
        ]);
    }

    public function postCatatan(Request $request) {
        $messages = [
            'tanggal.date' => 'Format tanggal tidak valid',
            'tanggal.exists' => 'Tanggal tidak ada',
            'username.required' => 'Username harus ada',
            'username.string' => 'Username harus berupa string',
            'username.exists' => 'Username tidak ada/belum terdaftar',
            'catatan.required' => 'Catatan wajib terisi'
        ];

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date|date_format:Y-m-d|exists:logs,tanggal',
            'username' => 'required|string|exists:users,username',
            'catatan' => 'required|string'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $log = Log::where('username', '=', $request->username)
            ->where('tanggal', '=', $request->tanggal)
            ->first();

        $log->catatan = $request->catatan;
        $log->save();

        return response()->json(['success' => true, 'message' => 'Catatan berhasil tersimpan']);
    }

    public function getLaporan(Request $request) {
        $messages = [
            'tanggal_mulai.date' => 'Format tanggal tidak valid',
            'tanggal_mulai.before' => 'Tanggal mulai harus sebelum selesai',
            'tanggal_selesai.date' => 'Format tanggal tidak valid',
            'tanggal_selesai.before' => 'Tanggal selesai lebih dari hari ini',
        ];

        $validator = Validator::make($request->all(), [
            'tanggal_mulai' => 'nullable|date|date_format:Y-m-d|before:tanggal_selesai',
            'tanggal_selesai' => 'nullable|date|date_format:Y-m-d|before:tomorrow'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $tanggalMulai = Carbon::today()->format('Y-m-d');
        if ($request->tanggal_mulai) {
            $tanggalMulai = $request->tanggal_mulai;
        }
        $tanggalSelesai = Carbon::today()->subMonth()->format('Y-m-d');
        if ($request->tanggal_selesai) {
            $tanggalSelesai = $request->tanggal_selesai;
        }

        $users = DetailUser::all();

        if ($request->shift_id) {
            $users = DetailUser::where('shift_id', $request->shift_id)->get();
            if ($request->filter) {
                $users = DetailUser::where('nama', 'LIKE', '%' . $request->filter . '%')->where('shift_id', $request->shift_id)->get();
            }
        }

        if ($request->filter) {
            $users = DetailUser::where('nama', 'LIKE', '%' . $request->filter . '%')->get();
        }

        $result = $users->map(function ($user) use ($tanggalMulai, $tanggalSelesai) {
            $hadirCount = Log::where('username', '=', $user->username)
                ->where('tanggal', '>=', $tanggalMulai)
                ->where('tanggal', '<=', $tanggalSelesai)
                ->where('kehadiran', '=', 'hadir')
                ->count();
            $tidakHadirCount = Log::where('username', '=', $user->username)
                ->where('tanggal', '>=', $tanggalMulai)
                ->where('tanggal', '<=', $tanggalSelesai)
                ->where('kehadiran', '=', 'tidak hadir')
                ->count();
            $izinCount = Log::where('username', '=', $user->username)
                ->where('tanggal', '>=', $tanggalMulai)
                ->where('tanggal', '<=', $tanggalSelesai)
                ->where('kehadiran', '=', 'izin')
                ->count();

            return [
                'username' => $user->nip,
                'nama' => $user->nama,
                'jumlah_hadir' => $hadirCount,
                'jumlah_izin' => $izinCount,
                'jumlah_tidak_hadir' => $tidakHadirCount
            ];
        });

        return response()->json(['success' => true, 'data' => $result]);
    }

    public function getDivisions(Request $request) {
        $messages = [
            'nama_divisi.string' => 'Nama Divisi berupa string'
        ];

        $validator = Validator::make($request->all(), [
            'nama_divisi' => 'nullable|string'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $divisi =  Division::all();

        if ($request->nama_divisi) {
            $divisi = Division::where('nama_divisi', 'LIKE', '%' . $request->nama_divisi . '%')
                ->get();
        }

        $data = $divisi->map(function ($div) {
            $divCount = DetailUser::where('divisi_id', $div->id)->count();
            return [
                'id' => $div->id,
                'nama_divisi' => $div->nama_divisi,
                'jumlah_anggota' => $divCount
            ];
        });

        return response()->json(['success' => true, 'divisions' => $data]);
    }

    public function getBelumAktif() {
        $users = DetailUser::where('divisi_id', '=', null)->get();

        return response()->json(['success' => true, 'unactive' => $users]);
    }

    public function getAnggotaDivisi(Request $request) {
        $messages = [
            'nama_divisi.string' => 'Nama Divisi berupa string'
        ];

        $validator = Validator::make($request->all(), [
            'nama_divisi' => 'required|string'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $divisi = Division::where('nama_divisi', '=', $request->nama_divisi)
            ->select('id')
            ->first()->id;

        $member = DetailUser::where('divisi_id', '=', $divisi)
            ->select('username', 'nama', 'nip', 'nilai_id', 'divisi_id')
            ->get();

        return response()->json([
            'success' => true,
            'member' => $member,
            'divisi' => $divisi
        ]);
    }

    public function postStatus(Request $request) {
        $messages = [
            'username.required' => 'Tidak ada username',
            'status_magang.required' => 'Status diperlukan'
        ];

        $validator = Validator::make($request->all(), [
            'username' => 'required|array',
            'status_magang' => 'required|string'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $users = $request->username;
        $status = $request->status_magang;
        $updatedUsers = []; // Array untuk menyimpan username yang berhasil diupdate

        foreach ($users as $user) {
            $detailUser = DetailUser::where('username', '=', $user)->first();

            if ($detailUser) {
                $detailUser->status_pegawai = $status;
                $detailUser->save();
                $updatedUsers[] = $user; // Tambahkan username ke daftar yang berhasil diupdate
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diganti',
            'updated_users' => $updatedUsers,
            'status' => $status
        ]);
    }

    public function getAllTeam() {
        $all = DetailUser::select('username', 'nama', 'nip', 'nilai_id', 'divisi_id')->get();
        return response()->json(['success' => true, 'member' => $all]);
    }

    public function getPemagang($username) {
        $user = User::join('detail_users', 'users.username', '=', 'detail_users.username')
            ->select(
                'detail_users.username',
                'detail_users.nama',
                'detail_users.asal_sekolah',
                'detail_users.tempat_lahir',
                'detail_users.tanggal_lahir',
                'detail_users.nomor_hp',
                'detail_users.tanggal_masuk',
                'detail_users.tanggal_keluar',
                'detail_users.nip',
                'detail_users.shift_id',
                'detail_users.divisi_id',
                'detail_users.os',
                'detail_users.browser',
                'users.status_akun',
                'users.konfirmasi_admin',
                'users.email'
            )->where('detail_users.username', '=', $username)
            ->first();

        return response()->json(['success' => true, 'user' => $user]);
    }

    public function postPemagang(Request $request) {
        $messages = [
            'username.required' => 'Username wajib diisi',
            'username.exists' => 'Username tidak ada',
            'password.min' => 'Password harus memiliki minimal 8 karakter.',
            'confPassword.same' => 'Konfirmasi Password harus sama dengan Password.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'tanggal_masuk.date' => 'Tanggal masuk harus berupa tanggal yang valid.',
            'tanggal_masuk.date_format' => 'Format tanggal masuk harus Y-m-d.',
            'tanggal_keluar.date' => 'Tanggal keluar harus berupa tanggal yang valid.',
            'tanggal_keluar.date_format' => 'Format tanggal keluar harus Y-m-d.',
            'tanggal_keluar.after' => 'Tanggal keluar harus setelah tanggal mulai.',
            'nip.string' => 'NIP harus berupa string.',
            'divisi_id.exists' => 'Divisi yang dipilih tidak valid.',
            'shift_id.exists' => 'Shift yang dipilih tidak valid.',
            'os.string' => 'OS harus berupa string.',
            'browser.string' => 'Browser harus berupa string.',
            'status_akun.string' => 'Status akun harus berupa string.',
            'konfirmasi_admin.string' => 'Konfirmasi admin harus berupa string.',
        ];

        $validator = Validator::make($request->all(), [
            'username' => 'required|exists:users,username',
            'password' => 'nullable|string|min:8',
            'confPassword' => 'nullable|string|same:password',
            'tanggal_masuk' => 'required|date|date_format:Y-m-d',
            'tanggal_keluar' => 'nullable|date|date_format:Y-m-d|after:tanggal_mulai',
            'nip' => 'nullable|string',
            'divisi_id' => 'nullable|exists:divisions,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'os' => 'nullable|string',
            'browser' => 'nullable|string',
            'status_akun' => 'nullable|string',
            'konfirmasi_admin' => 'nullable|boolean',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $user = User::where('username', $request->username)->first();
        $detailUser = DetailUser::where('username', $request->username)->first();

        $detailUser->tanggal_masuk = $request->tanggal_masuk;

        if ($request->password && $request->password != "") {
            $user->password = $request->password;
        }
        if ($request->tanggal_keluar) {
            $detailUser->tanggal_keluar = $request->tanggal_keluar;
        }
        if ($request->nip) {
            $detailUser->nip = $request->nip;
        }
        if ($request->divisi_id) {
            $detailUser->divisi_id = $request->divisi_id;
        }
        if ($request->shift_id) {
            $detailUser->shift_id = $request->shift_id;
        }
        if ($request->os) {
            $detailUser->os = $request->os;
        }
        if ($request->browser) {
            $detailUser->browser = $request->browser;
        }
        if ($request->status_akun) {
            $user->status_akun = $request->status_akun;
        }
        if ($request->konfirmasi_admin) {
            $user->konfirmasi_admin = $request->konfirmasi_admin;
        }

        $user->save();
        $detailUser->save();

        return response()->json(['success' => true, 'message' => 'Sukses']);
    }

    public function postPenilaian(Request $request) {
        $messages = [
            'username.required' => 'Username harus diisi.',
            'username.string' => 'Username harus berupa string.',
            'penilaian.required' => 'Data penilaian harus diisi.',
            'penilaian.array' => 'Data penilaian harus berupa array.',
            'penilaian.*.kategori.id.required' => 'ID kategori harus diisi.',
            'penilaian.*.kategori.id.integer' => 'ID kategori harus berupa integer.',
            'penilaian.*.kategori.sub_kategori.required' => 'Sub kategori harus diisi.',
            'penilaian.*.kategori.sub_kategori.array' => 'Sub kategori harus berupa array.',
            'penilaian.*.kategori.sub_kategori.*.id.required' => 'ID sub kategori harus diisi.',
            'penilaian.*.kategori.sub_kategori.*.id.integer' => 'ID sub kategori harus berupa integer.',
            'penilaian.*.kategori.sub_kategori.*.nilai.required' => 'Nilai harus diisi.',
            'penilaian.*.kategori.sub_kategori.*.nilai.integer' => 'Nilai harus berupa integer.',
        ];

        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'penilaian' => 'required|array',
            'penilaian.*.kategori.id' => 'required|integer',
            'penilaian.*.kategori.sub_kategori' => 'required|array',
            'penilaian.*.kategori.sub_kategori.*.id' => 'required|integer',
            'penilaian.*.kategori.sub_kategori.*.nilai' => 'required|integer',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $data = $request->all();

        foreach ($data['penilaian'] as $kategori) {
            $kategoriModel = NilaiCategory::where('id', $kategori['kategori']['id'])->first();
            if ($kategoriModel) {
                foreach ($kategori['kategori']['sub_kategori'] as $sub) {
                    $subKategoriModel = NilaiSubcategory::where('id', $sub['id'])->first();
                    if ($subKategoriModel) {
                        $penilaian = new NilaiUser();
                        $penilaian->username = $data['username'];
                        $penilaian->category_id = $kategoriModel->id;
                        $penilaian->subcategory_id = $subKategoriModel->id;
                        $penilaian->nilai = $sub['nilai'];
                        $penilaian->save();
                    }
                }
            }
        }

        return response()->json(['message' => 'Penilaian saved successfully!'], 201);
    }

    public function groupBySekolah()
    {
        $data = DetailUser::selectRaw('asal_sekolah, COUNT(*) as jumlah_partisipan')
                                  ->groupBy('asal_sekolah')
                                  ->get();

        return response()->json($data);
    }

    public function searchBySekolah(Request $request)
    {
        $keyword = $request->keyword;
        $data = DetailUser::selectRaw('asal_sekolah, COUNT(*) as jumlah_partisipan')
                                  ->where('asal_sekolah', 'like', "%{$keyword}%" )
                                  ->groupBy('asal_sekolah')
                                  ->get();

        return response()->json($data);
    }

    public function getShifts()
    {
      $data = Shift::all();

      return response()->json([
          'success' => true,
          'data' => $data
      ], 200);
    }

    public function editShift(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:shifts,id',
            'nama_baru' => 'required|string|max:255',
            'masuk' => 'required|date_format:H:i:s',
            'istirahat' => 'required|date_format:H:i:s',
            'kembali' => 'required|date_format:H:i:s',
            'pulang' => 'required|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil data dari request
        $id = $request->id;
        $nama_baru = $request->nama_baru;
        $jam_masuk = $request->masuk;
        $jam_istirahat = $request->istirahat;
        $jam_kembali = $request->kembali;
        $jam_pulang = $request->pulang;

        // Cari shift berdasarkan ID
        $shift = Shift::find($id);

        if ($shift) {
            // Update data shift
            $shift->update([
                'nama_shift' => $nama_baru,
                'jam_masuk' => $jam_masuk,
                'jam_istirahat' => $jam_istirahat,
                'jam_kembali' => $jam_kembali,
                'jam_pulang' => $jam_pulang,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal shift berhasil diupdate',
                'data' => $shift
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'id shift tidak ditemukan'
        ], 404);
    }

    public function addShift(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'nama_baru' => 'required|string|max:255',
          'masuk' => 'required|date_format:H:i:s',
          'istirahat' => 'required|date_format:H:i:s',
          'kembali' => 'required|date_format:H:i:s',
          'pulang' => 'required|date_format:H:i:s',
      ]);

      if ($validator->fails()) {
          return response()->json([
              'success' => false,
              'errors' => $validator->errors()
          ], 422);
      }

      $nama_baru = $request->nama_baru;
      $masuk = $request->masuk;
      $istirahat = $request->istirahat;
      $kembali = $request->kembali;
      $pulang = $request->pulang;

      $shift = Shift::create([
        'nama_shift' => $nama_baru,
        'masuk' => $masuk,
        'istirahat' => $istirahat,
        'kembali' => $kembali,
        'pulang' => $pulang,
      ]);

      return response()->json([
          'success' => true,
          'message' => 'Berhasil menambahkan shift',
          'data' => $shift
      ], 201);
    }

    public function deleteShift($id)
    {
        $shift = Shift::find($id);
        $allShifts = Shift::all();
        if ($shift) {
            $shift->delete();
            return response()->json([
                'success' => true,
                'message' => 'Shift deleted successfully',
                'data' => $allShifts
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Shift not found'
        ], 404);
    }

    public function getDivisi()
    {
      $data = Division::all();

      return response()->json([
          'success' => true,
          'data' => $data
      ], 200);
    }

    public function editDivisi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:divisions,id',
            'nama_baru' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $id = $request->id;
        $nama_baru = $request->nama_baru;

        $division = Division::find($id);

        if ($division) {
            $division->update([
                'nama_divisi' => $nama_baru
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Nama divisi berhasil diupdate',
                'data' => $division
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'id divisi tidak ditemukan'
        ], 404);
    }

    public function addDivisi(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'nama_divisi' => 'required|string|max:255'
      ]);

      if ($validator->fails()) {
          return response()->json([
              'success' => false,
              'errors' => $validator->errors()
          ], 422);
      }

      $nama_divisi = $request->nama_divisi;

      $divisions = Division::create([
        'nama_divisi' => $nama_divisi
      ]);

      return response()->json([
          'success' => true,
          'message' => 'Berhasil menambahkan divisi',
          'data' => $divisions
      ], 201);
    }

    public function deleteDivisi($id)
    {
        $division = Division::find($id);
        $allDivisions = Division::all();
        if ($division) {
            $division->delete();
            return response()->json([
                'success' => true,
                'message' => 'Divisi berhasil dihapus',
                'data' => $allDivisions
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Divisi tidak ditemukan'
        ], 404);
    }

    public function deleteSubkategori($id)
    {
        $subc = NilaiSubcategory::find($id);
        if ($subc) {
            $division_id = $subc->category->division_id;
            $subc->delete();
            $response = Route::dispatch(Request::create("/admin/manage-penilaian/{$division_id}", 'GET'));
            $responseData = json_decode($response->getContent(), true);

            return response()->json([
                'success' => true,
                'message' => 'Subcategory deleted successfully',
                'penilaian' => $responseData,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Subcategory not found'
        ], 404);
    }

    public function addSubkategori(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'nama_subkategori' => 'required|string|max:255',
          'category_id' => 'required|exists:nilai_categories,id'
      ]);

      if ($validator->fails()) {
          return response()->json([
              'success' => false,
              'errors' => $validator->errors()
          ], 422);
      }

      $nama_subkategori = $request->nama_subkategori;
      $category_id = $request->category_id;

      $subkategori = NilaiSubcategory::create([
        'nama_subkategori' => $nama_subkategori,
        'category_id' => $category_id
      ]);


      $c = NilaiSubcategory::where('category_id', $category_id)->first();
      $division_id = $c->category->division_id;


      $response = Route::dispatch(Request::create("/admin/manage-penilaian/{$division_id}", 'GET'));
      $responseData = json_decode($response->getContent(), true);

      return response()->json([
          'success' => true,
          'message' => 'Berhasil menambahkan subkategori nilai',
          'data' => $subkategori,
          'penilaian' => $responseData
      ], 201);
    }

    public function getPenilaian($division_id)
    {
        $categories = NilaiCategory::with('subcategories')
            ->where('division_id', $division_id)
            ->get();

        $data = $categories->map(function ($category) {
            return [
                'kategori' => [
                    'id' => $category->id,
                    'nama_kategori' => $category->nama_kategori,
                    'sub_kategori' => $category->subcategories->map(function ($subcategory) {
                        return [
                            'id' => $subcategory->id,
                            'nama' => $subcategory->nama_subkategori,
                        ];
                    })->toArray(),
                ],
            ];
        });

        return response()->json([
            'penilaian' => $data,
        ], 200);
    }

    public function getPenilaianUser($username)
    {
        // Ambil detail user
        $user = DetailUser::where('username', $username)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Ambil data penilaian berdasarkan username
        $penilaian = NilaiUser::where('username', $username)
            ->with('category', 'subcategory') // Pastikan relasi ini didefinisikan di model
            ->get()
            ->groupBy('category_id');

        // Struktur data yang diinginkan
        $result = [
            'username' => $user->nama,
            'nip' => $user->nip,
            'penilaian' => []
        ];

        foreach ($penilaian as $kategori_id => $nilaiItems) {
            $kategori = NilaiCategory::find($kategori_id);
            if ($kategori) {
                $result['penilaian'][] = [
                    'kategori' => [
                        'id' => $kategori->id,
                        'nama_kategori' => $kategori->nama_kategori,
                        'sub_kategori' => $nilaiItems->map(function ($item) {
                            $subKategori = NilaiSubcategory::find($item->subcategory_id);
                            return [
                                'id' => $subKategori->id,
                                'nama' => $subKategori->nama_subkategori,
                                'nilai' => $item->nilai
                            ];
                        })->toArray()
                    ]
                ];
            }
        }

        return response()->json($result);
    }

    public function getKategori($id)
    {
      $data = NilaiCategory::where('division_id', $id)
              ->get();

      return response()->json([
          'success' => true,
          'data' => $data
      ], 200);
    }

    public function addKategori(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'nama_kategori' => 'required|string|max:255',
          'division_id' => 'required|exists:divisions,id'
      ]);

      if ($validator->fails()) {
          return response()->json([
              'success' => false,
              'errors' => $validator->errors()
          ], 422);
      }

      $nama_kategori = $request->nama_kategori;
      $division_id = $request->division_id;

      $kategori_penilaian = NilaiCategory::create([
        'nama_kategori' => $nama_kategori,
        'division_id' => $division_id
      ]);

      $data = NilaiCategory::where('division_id', $division_id)
              ->get();

      return response()->json([
          'success' => true,
          'message' => 'Berhasil menambahkan kategori penilaian',
          'kategori_baru' => $kategori_penilaian,
          'data' => $data
      ], 201);
    }

    public function deleteKategori($division_id, $category_id)
    {
        $cat = NilaiCategory::find($category_id);
        if ($cat) {
          $cat->delete();
          $data = NilaiCategory::where('division_id', $division_id)
              ->get();
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully',
                'cat' => $data
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Subcategory not found'
        ], 404);
    }

}