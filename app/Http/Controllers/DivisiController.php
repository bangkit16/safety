<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DivisiController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);  // Default pagination limit
        $search = $request->search;

        // Sorting
        $sortBy = $request->get('sort_by', 'divisi_id');  // Default sorting column (ganti dengan kolom yang ada di tabel Divisi)
        $order = $request->get('order', 'asc');   // Default sorting order ('asc' atau 'desc')

        // Query dasar
        $data = Divisi::query();

        // Filter pencarian
        if ($search) {
            $data->where('nama', 'like', "%{$search}%")
            ->orWhere('tanda_tangan', 'like', "%{$search}%");
        }

        // Terapkan sorting
        $data->orderBy($sortBy, $order);

        // Pagination atau semua data
        if ($limit == 'all') {
            $data = $data->get();  // Ambil semua data
        } else {
            $data = $data->paginate($limit)->appends($request->only('search', 'limit', 'sort_by', 'order')); // Tambahkan query params
        }

        // Kirim data ke view
        return view('admin.divisi.index', compact('data', 'sortBy', 'order'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:225',
            'tanda_tangan' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk file gambar
        ]);

        // Unggah file dan simpan path ke dalam database
        if ($request->file('tanda_tangan')) {
            $filePath = $request->file('tanda_tangan')->store('divisi', 'public'); // Simpan ke storage/public/divisi
            Divisi::create([
                'nama'          => $request->nama,
                'tanda_tangan'  => $filePath, // Simpan path file
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        return redirect()->route('divisi.index')->withStatus('Divisi berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'edit_nama' => 'required|string|max:225',
            'edit_tanda_tangan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Temukan data berdasarkan ID
        $divisi = Divisi::findOrFail($id);

        if ($request->hasFile('edit_tanda_tangan')) {
            // Hapus file lama jika ada
            if ($divisi->tanda_tangan && Storage::disk('public')->exists($divisi->tanda_tangan)) {
                Storage::disk('public')->delete($divisi->tanda_tangan);
            }

            // Simpan file baru
            $filePath = $request->file('edit_tanda_tangan')->store('divisi', 'public');
            $divisi->update([
                'nama' => $request->edit_nama, // Perbaikan di sini
                'tanda_tangan' => $filePath,
                'updated_at' => now(),
            ]);
        } else {
            // Jika tidak ada file baru, tetap simpan data lama
            $divisi->update([
                'nama' => $request->edit_nama, // Perbaikan di sini
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('divisi.index')->withStatus(__('Divisi berhasil diperbaharui.'));
    }

    public function destroy($id)
    {
        // Temukan data berdasarkan ID
        $divisi = Divisi::findOrFail($id);
    
        // Hapus file gambar jika ada
        if ($divisi->tanda_tangan && Storage::disk('public')->exists($divisi->tanda_tangan)) {
            Storage::disk('public')->delete($divisi->tanda_tangan);
        }
    
        // Hapus data dari database
        $divisi->delete();
    
        return redirect()->route('divisi.index')->withStatus(__('Divisi berhasil dihapus.'));
    }
    
}
