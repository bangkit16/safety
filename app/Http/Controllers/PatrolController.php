<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Patrol;
use App\Models\Perbaikan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatrolController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);  // Default pagination limit
        $search = $request->search;

        // Pemetaan nama bulan ke angka
        $bulanMap = [
            'januari' => '01', 'februari' => '02', 'maret' => '03',
            'april' => '04', 'mei' => '05', 'juni' => '06',
            'juli' => '07', 'agustus' => '08', 'september' => '09',
            'oktober' => '10', 'november' => '11', 'desember' => '12',
        ];

        // Cek apakah pencarian mengandung nama bulan
        $bulan = null;
        if ($search) {
            $searchLower = strtolower($search);
            if (isset($bulanMap[$searchLower])) {
                $bulan = $bulanMap[$searchLower];
            }
        }

        // Query dasar
        $data = Patrol::query();

        // Filter pencarian
        if ($search) {
            $divisi = Divisi::where('nama', 'like', "%{$search}%")->pluck('divisi_id');
            $user = User::where('name', 'like', "%{$search}%")->pluck('user_id'); // Ambil ID User

            $data->where(function ($q) use ($search, $bulan, $divisi, $user) {
                if ($divisi->isNotEmpty()) {
                    $q->orWhereIn('divisi_id', $divisi);
                }
                if ($user->isNotEmpty()) {
                    $q->orWhere('user_id', $user); // Asumsikan patrol memiliki `user_id`
                }
                if ($bulan) {
                    $q->orWhereMonth('tanggal', $bulan); // Cari berdasarkan bulan
                }
                $q->orWhere('status', 'like', "%{$search}%")
                ->orWhere('temuan', 'like', "%{$search}%")
                ->orWhere('tanggal', 'like', "%{$search}%");
            });
        }        

        // Terapkan sorting
        $sortBy = $request->get('sort_by', 'patrol_id');
        $order = $request->get('order', 'asc');
        $data->orderBy($sortBy, $order);

        // Pagination atau semua data
        if ($limit == 'all') {
            $data = $data->get();  // Ambil semua data
        } else {
            $data = $data->paginate($limit)->appends($request->only('search', 'limit', 'sort_by', 'order')); // Tambahkan query params
        }

        $divisi = Divisi::all();
        $user = User::all();

        // Kirim data ke view
        return view('admin.patrol.index', compact('data', 'user', 'divisi', 'sortBy', 'order'));
    }


    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'temuan' => 'nullable',
            'divisi_id' => 'required|exists:divisis,divisi_id',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk file gambar
        ]);

        // Unggah file dan simpan path ke dalam database
        if ($request->file('dokumentasi')) {
            $filePath = $request->file('dokumentasi')->store('patrol', 'public'); // Simpan ke storage/public/divisi
            Patrol::create([
                'temuan' => $request->temuan,
                'divisi_id' => $request->divisi_id,
                'dokumentasi'  => $filePath, // Simpan path file
                'tanggal' => now(),
                'user_id' => auth()->user()->user_id,
                'status' => 'Belum Dicek',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            Patrol::create([
                'temuan' => $request->temuan,
                'divisi_id' => $request->divisi_id,
                'dokumentasi'  => $request->dokumentasi, // Simpan path file
                'tanggal' => now(),
                'user_id' => auth()->user()->user_id,
                'status' => 'Belum Dicek',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        return redirect()->route('patrol.index')->withStatus('Patrol berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'edit_temuan' => 'nullable',
            'edit_divisi_id' => 'required|exists:divisis,divisi_id',
            'edit_dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Temukan data berdasarkan ID
        $patrol = Patrol::findOrFail($id);

        if ($request->hasFile('edit_dokumentasi')) {
            // Hapus file lama jika ada
            if ($patrol->dokumentasi && Storage::disk('public')->exists($patrol->dokumentasi)) {
                Storage::disk('public')->delete($patrol->dokumentasi);
            }

            // Simpan file baru
            $filePath = $request->file('edit_dokumentasi')->store('patrol', 'public');
            $patrol->update([
                'temuan' => $request->edit_temuan,
                'dokumentasi' => $filePath,
                'divisi_id' => $request->edit_divisi_id,
                'updated_at' => now(),
            ]);
        } else {
            // Jika tidak ada file baru, tetap simpan data lama
            $patrol->update([
                'temuan' => $request->edit_temuan,
                'divisi_id' => $request->edit_divisi_id,
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('patrol.index')->withStatus(__('Patrol berhasil diperbaharui.'));
    }

    public function destroy($id)
    {
        // Temukan data berdasarkan ID
        $patrol = Patrol::findOrFail($id);
    
        // Hapus file gambar jika ada
        if ($patrol->dokumentasi && Storage::disk('public')->exists($patrol->dokumentasi)) {
            Storage::disk('public')->delete($patrol->dokumentasi);
        }
    
        // Hapus data dari database
        $patrol->delete();
    
        return redirect()->route('patrol.index')->withStatus(__('Patrol berhasil dihapus.'));
    }

    public function approveAdmin(Request $request)
    {
        
        dd($request->approve_patrol_id);    
        // Validasi input
        $request->validate([
            'approve_perbaikan' => 'nullable',
            'approve_patrol_id' => 'required|exists:patrols,patrol_id',
            'approve_user_id' => 'required|exists:users,user_id',
            'approve_divisi_id' => 'required|exists:divisis,divisi_id',
            'approve_target' => 'nullable|date',
            'approve_dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk file gambar
        ]);


        $patrol = Patrol::find($request->patrol_id);
        
        // Unggah file dan simpan path ke dalam database
        if ($request->file('dokumentasi')) {
            $filePath = $request->file('dokumentasi')->store('perbaikan', 'public'); // Simpan ke storage/public/divisi
            Perbaikan::create([
                'perbaikan' => $request->approve_perbaikan,
                'divisi_id' => $request->approve_divisi_id,
                'patrol_id' => $request->approve_patrol_id,
                'user_id' => $request->approve_user_id,
                'dokumentasi'  => $filePath, // Simpan path file
                'target' => $request->approve_target,
                'status' => 'Proses',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            Perbaikan::create([
                'perbaikan' => $request->approve_perbaikan,
                'divisi_id' => $request->approve_divisi_id,
                'patrol_id' => $request->approve_patrol_id,
                'user_id' => $request->approve_user_id,
                'dokumentasi'  => $request->approve_dokumentasi, // Simpan path file
                'target' => $request->approve_target,
                'status' => 'Proses',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $patrol->update([
            'status' => 'Setuju Admin',
            'updated_at' => now(),
        ]);

        return redirect()->route('patrol.index')->withStatus('Status berhasil diubah.');
    }

    public function approveManager(Request $request)
    {
        $request->validate([
            'perbaikan' => 'nullable',
            'patrol_id' => 'required|exists:patrols,patrol_id',
            'user_id' => 'required|exists:users,user_id',
            'divisi_id' => 'required|exists:divisis,divisi_id',
            'target' => 'nullable|date',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk file gambar
        ]);
        $patrol = Patrol::find($request->patrol_id);
        
        // Unggah file dan simpan path ke dalam database
        if ($request->file('dokumentasi')) {
            $filePath = $request->file('dokumentasi')->store('perbaikan', 'public'); // Simpan ke storage/public/divisi
            Perbaikan::create([
                'perbaikan' => $request->perbaikan,
                'divisi_id' => $request->divisi_id,
                'patrol_id' => $request->patrol_id,
                'user_id' => auth()->user()->user_id,
                'dokumentasi'  => $filePath, // Simpan path file
                'target' => $request->target,
                'status' => 'Proses',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            Perbaikan::create([
                'perbaikan' => $request->perbaikan,
                'divisi_id' => $request->divisi_id,
                'patrol_id' => $request->patrol_id,
                'user_id' => auth()->user()->user_id,
                'dokumentasi'  => $request->dokumentasi, // Simpan path file
                'target' => $request->target,
                'status' => 'Proses',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $patrol->update([
            'status' => 'Setuju Semua',
            'updated_at' => now(),
        ]);

        return redirect()->route('patrol.index')->withStatus('Status berhasil diubah.');
    }


}
