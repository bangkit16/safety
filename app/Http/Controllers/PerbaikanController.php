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
        if (auth()->user()->role_id == 3) {
            $patrol = Patrol::where('user_id', auth()->user()->user_id);
            // dd($patrol);
            $data = Perbaikan::where('patrol_id', $patrol->patrol_id);
        } else {
            $data = Perbaikan::query();
        }

        // Filter pencarian
        if ($search) {
            $data->where(function ($q) use ($search, $bulan) {
                if ($bulan) {
                    $q->orWhereMonth('tanggal', $bulan); // Cari berdasarkan bulan
                }
                $q->orWhere('status', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%")
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
            'temuan' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'required',
            'temuan_patrol_id' => 'required|exists:patrols,patrol_id',
        ]);

        // Inisialisasi data untuk penyimpanan
        $filePath = null;

        // Proses upload file jika ada
        if ($request->hasFile('temuan')) {
            $filePath = $request->file('temuan')->store('perbaikan', 'public');
        }

        // dd($request->keterangan);

        // Simpan data ke database
        Perbaikan::create([
            'patrol_id' => $request->temuan_patrol_id,
            'temuan' => $filePath,
            'keterangan' => $request->keterangan,
            'perbaikan' => '',
            'target' => null,
            'dokumentasi' => '',
            'status' => 'Belum Dicek',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Kirim Notifikasi Email
        $details = [
            'user' => auth()->user()->name,
            'tanggal' => now()->toDateTimeString(),
            'status' => 'Belum Dicek',
        ];

        $user = User::all();
        
        foreach ($user as $us) {
            if ($us->role_id == 1) {
                \Mail::to($us->email)->send(new \App\Mail\NotifyEmail1($details));
            }
        }

        return redirect()->route('patrol.index')->withStatus('Perbaikan berhasil ditambahkan.');
    }



    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'edit_patrol_id' => 'required|exists:patrols,patrol_id',
            'edit_temuan' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'edit_keterangan' => 'required',
            'edit_perbaikan' => 'required',
            'edit_target' => 'required|date',
            'edit_dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Temukan data berdasarkan ID
        $perbaikan = Perbaikan::findOrFail($id);

        $updateData = [
            'patrol_id' => $request->edit_patrol_id,
            'keterangan' => $request->edit_keterangan,
            'perbaikan' => $request->edit_perbaikan,
            'target' => $request->edit_target,
            'updated_at' => now(),
        ];

        // Cek dan proses file baru untuk "dokumentasi"
        if ($request->hasFile('edit_dokumentasi')) {
            // Hapus file lama jika ada
            if ($perbaikan->dokumentasi && Storage::exists('public/' . $perbaikan->dokumentasi)) {
                Storage::delete('public/' . $perbaikan->dokumentasi);
            }
            // Simpan file baru
            $updateData['dokumentasi'] = $request->file('edit_dokumentasi')->store('perbaikan', 'public');
        }

        // Cek dan proses file baru untuk "temuan"
        if ($request->hasFile('edit_temuan')) {
            // Hapus file lama jika ada
            if ($perbaikan->temuan && Storage::exists('public/' . $perbaikan->temuan)) {
                Storage::delete('public/' . $perbaikan->temuan);
            }
            // Simpan file baru
            $updateData['temuan'] = $request->file('edit_temuan')->store('perbaikan', 'public');
        }

        // Update data
        $perbaikan->update($updateData);

        return redirect()->route('perbaikan.index')->withStatus(__('Perbaikan berhasil diperbaharui.'));
    }

    public function FormPerbaikan(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'patrol_id' => 'required|exists:patrols,patrol_id',
            'keterangan' => 'required',
            'perbaikan' => 'required',
            'target' => 'required|date',
        ]);

        $perbaikan = Perbaikan::findOrFail($id);
        $perbaikan->update([
            'perbaikan' => $request->perbaikan,
            'target' => $request->target,
            'status' => 'Proses',
            'updated_at' => now()
        ]);

        return redirect()->route('perbaikan.index')->withStatus(__('Perbaikan berhasil ditambahkan.'));
    }


    public function dokumentasi(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'dokum_dokumentasi' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $perbaikan = Perbaikan::findOrFail($id);
            // Simpan file baru
            $filePath = $request->file('dokum_dokumentasi')->store('perbaikan', 'public');
            $perbaikan->update([
                'dokumentasi' => $filePath,
                'status' => 'Selesai',
                'updated_at' => now(),
            ]);

            $details = [
                'tanggal' => now()->toDateTimeString(),
                'status' => 'Selesai',
            ];
    
            $user = User::all(); 
            
            foreach ($user as $us) {
                if ($us->role_id == 1 ) {
                    \Mail::to($us->email)->send(new \App\Mail\NotifyEmail4($details));
                }
            }

        return redirect()->route('perbaikan.index')->withStatus(__('Dokumentasi berhasil ditambahkan.'));
    }

    public function approveAdmin($id)
    {
        $perbaikan = Perbaikan::find($id);

        $perbaikan->update([
            'status' => 'Setuju Admin',
            'updated_at' => now(),
        ]);

        $details = [
            'tanggal' => now()->toDateTimeString(),
            'status' => 'Setuju Admin',
        ];

        $user = User::all(); 
        
        foreach ($user as $us) {
            if ($us->role_id == 2) {
                \Mail::to($us->email)->send(new \App\Mail\NotifyEmail2($details));
            }
        }

        return redirect()->route('perbaikan.index')->withStatus('Status berhasil diubah.');
    }

    public function approveManager($id)
    {
        $perbaikan = Perbaikan::find($id);
        $patrol = Patrol::find($perbaikan->patrol_id);

        $perbaikan->update([
            'status' => 'Setuju Semua',
            'updated_at' => now(),
        ]);

        // Kirim Notifikasi Email
        $details = [
            'tanggal' => now()->toDateTimeString(),
            'status' => 'Setuju Semua',
        ];
        $user = User::where('user_id', $patrol->user_id)->first();

        \Mail::to($user->email,)->send(new \App\Mail\NotifyEmail3($details));
        return redirect()->route('perbaikan.index')->withStatus('Status berhasil diubah.');
    }

    public function setujuAdmin($id)
    {
        $perbaikan = Perbaikan::find($id);

        $perbaikan->update([
            'status' => 'Lolos Admin',
            'updated_at' => now(),
        ]);

        $details = [
            'tanggal' => now()->toDateTimeString(),
            'status' => 'Lolos Admin',
        ];

        $user = User::all(); 
        
        foreach ($user as $us) {
            if ($us->role_id == 2) {
                \Mail::to($us->email)->send(new \App\Mail\NotifyEmail5($details));
            }
        }

        return redirect()->route('perbaikan.index')->withStatus('Status berhasil diubah.');
    }

    public function setujuManager($id)
    {
        $perbaikan = Perbaikan::find($id);
        $patrol = Patrol::find($perbaikan->patrol_id);

        $perbaikan->update([
            'status' => 'Lolos Semua',
            'updated_at' => now(),
        ]);

        $details = [
            'tanggal' => now()->toDateTimeString(),
            'status' => 'Lolos Semua',
        ];
        
        $user = User::where('user_id', $patrol->user_id)->first();
                \Mail::to($user->email)->send(new \App\Mail\NotifyEmail6($details));

        return redirect()->route('perbaikan.index')->withStatus('Status berhasil diubah.');
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

