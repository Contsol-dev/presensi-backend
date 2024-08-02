<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\User;
use App\Models\Shift;
use App\Models\Division;
use App\Models\DetailUser;
use Illuminate\Http\Request;
use App\Models\NilaiCategory;
use App\Models\NilaiSubcategory;
use Illuminate\Support\Facades\Route;
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