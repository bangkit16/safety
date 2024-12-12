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
                $q->orWhere('tanggal', 'like', "%{$search}%");
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
            'user_id' => 'required|exists:users,user_id',
            'divisi_id' => 'required|exists:divisis,divisi_id',
        ]);
            
        Patrol::create([
                'divisi_id' => $request->divisi_id,
                'tanggal' => now(),
                'user_id' => $request->user_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        
        return redirect()->route('patrol.index')->withStatus('Patrol berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'edit_user_id' => 'required|exists:users,user_id',
            'edit_divisi_id' => 'required|exists:divisis,divisi_id',
        ]);

        // Temukan data berdasarkan ID
        $patrol = Patrol::findOrFail($id);
        
            $patrol->update([
                'user_id' => $request->edit_user_id,
                'divisi_id' => $request->edit_divisi_id,
                'updated_at' => now(),
            ]);

        return redirect()->route('patrol.index')->withStatus(__('Patrol berhasil diperbaharui.'));
    }

    public function destroy($id)
    {
        // Temukan data berdasarkan ID
        $patrol = Patrol::findOrFail($id);
    
        // Hapus data dari database
        $patrol->delete();
    
        return redirect()->route('patrol.index')->withStatus(__('Patrol berhasil dihapus.'));
    }


}
