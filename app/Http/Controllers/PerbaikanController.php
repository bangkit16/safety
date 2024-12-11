<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Patrol;
use App\Models\Perbaikan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerbaikanController extends Controller
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
        $data = Perbaikan::query();

        // Filter pencarian
        if ($search) {
            $divisi = Divisi::where('nama', 'like', "%{$search}%")->pluck('divisi_id');
            $user = User::where('name', 'like', "%{$search}%")->pluck('user_id'); // Ambil ID User
            $patrol = Patrol::where('temuan', 'like', "%{$search}%")->pluck('patrol_id');

            $data->where(function ($q) use ($search, $bulan, $divisi, $user, $patrol) {
                if ($divisi->isNotEmpty()) {
                    $q->orWhereIn('divisi_id', $divisi);
                }
                if ($user->isNotEmpty()) {
                    $q->orWhere('user_id', $user); // Asumsikan patrol memiliki `user_id`
                }
                if ($patrol->isNotEmpty()) {
                    $q->orWhere('patrol_id', $user); // Asumsikan patrol memiliki `user_id`
                }
                if ($bulan) {
                    $q->orWhereMonth('tanggal', $bulan); // Cari berdasarkan bulan
                }
                $q->orWhere('status', 'like', "%{$search}%")
                ->orWhere('perbaikan', 'like', "%{$search}%")
                ->orWhere('target', 'like', "%{$search}%");
            });
        }        

        // Terapkan sorting
        $sortBy = $request->get('sort_by', 'perbaikan_id');
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
        $apar = Patrol::all();

        // Kirim data ke view
        return view('admin.perbaikan.index', compact('data', 'apar' ,'user', 'divisi', 'sortBy', 'order'));
    }


    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'perbaikan' => 'nullable',
            'patrol_id' => 'required|exists:patrols,patrol_id',
            'user_id' => 'required|exists:users,user_id',
            'divisi_id' => 'required|exists:divisis,divisi_id',
            'target' => 'nullable|date',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk file gambar
        ]);

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
        
        return redirect()->route('perbaikan.index')->withStatus('Perbaikan berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'edit_perbaikan' => 'nullable',
            'edit_patrol_id' => 'required|exists:patrols,patrol_id',
            'edit_user_id' => 'required|exists:users,user_id',
            'edit_divisi_id' => 'required|exists:divisis,divisi_id',
            'edit_target' => 'nulable|date',
            'edit_dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Temukan data berdasarkan ID
        $perbaikan = Perbaikan::findOrFail($id);

        if ($request->hasFile('edit_dokumentasi')) {
            // Hapus file lama jika ada
            if ($perbaikan->dokumentasi && Storage::disk('public')->exists($perbaikan->dokumentasi)) {
                Storage::disk('public')->delete($perbaikan->dokumentasi);
            }

            // Simpan file baru
            $filePath = $request->file('edit_dokumentasi')->store('perbaikan', 'public');
            $perbaikan->update([
                'perbaikan' => $request->edit_perbaikan,
                'dokumentasi' => $filePath,
                'patrol_id' => $request->edit_patrol_id,
                'user_id' => $request->edit_divisi_id,
                'divisi_id' => $request->edit_divisi_id,
                'target' => $request->edit_target,
                'updated_at' => now(),
            ]);
        } else {
            // Jika tidak ada file baru, tetap simpan data lama
            $perbaikan->update([
                'perbaikan' => $request->edit_perbaikan,
                'patrol_id' => $request->edit_patrol_id,
                'user_id' => $request->edit_divisi_id,
                'divisi_id' => $request->edit_divisi_id,
                'target' => $request->edit_target,
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('perbaikan.index')->withStatus(__('Perbaikan berhasil diperbaharui.'));
    }

    public function destroy($id)
    {
        // Temukan data berdasarkan ID
        $perbaikan = perbaikan::findOrFail($id);
    
        // Hapus file gambar jika ada
        if ($perbaikan->dokumentasi && Storage::disk('public')->exists($perbaikan->dokumentasi)) {
            Storage::disk('public')->delete($perbaikan->dokumentasi);
        }
    
        // Hapus data dari database
        $perbaikan->delete();
    
        return redirect()->route('perbaikan.index')->withStatus(__('Perbaikan berhasil dihapus.'));
    }
}

