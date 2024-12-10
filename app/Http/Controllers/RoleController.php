<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);  // Default pagination limit
        $search = $request->search;

        // Sorting
        $sortBy = $request->get('sort_by', 'role_id');  // Default sorting column (ganti dengan kolom yang ada di tabel Role)
        $order = $request->get('order', 'asc');   // Default sorting order ('asc' atau 'desc')

        // Query dasar
        $data = Role::query();

        // Filter pencarian
        if ($search) {
            $data->where('role_name', 'like', "%{$search}%");
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
        return view('admin.role.index', compact('data', 'sortBy', 'order'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'role_name'    => 'required|string|max:255',
            'role_description'  => 'required'
        ]);

        Role::create([
            'role_name'   => $request->role_name,
            'role_description' => $request->role_description,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
        
        return redirect()->route('role.index')->withStatus('Role berhasil ditambahkan.');
    }

    public function update(Request $request, $id) {
        $request->validate([
            'edit_role_name'    => 'required|string|max:255',
            'edit_role_description' => 'required',
        ]);

        $role = Role::findOrFail($id);

        $role->update([
            'role_name'   => $request->edit_role_name,
            'role_description'   => $request->edit_role_description,
            'updated_at'     => now(),
        ]);
        return redirect()->route('role.index')->withStatus(__('Role berhasil diperbaharui.'));        
    }
    
    public function destroy(String $id)
    {
        $check = Role::findOrFail($id);
        if(!$check){
            return redirect()->route('role.index')->withError(__('Role tidak ditemukan.'));
        }
        try{
                Role::destroy($id);
                return redirect()->route('role.index')->withStatus(__('Role berhasil dihapus.'));
        }catch(\Illuminate\Database\QueryException $e){
            return redirect()->route('role.index')->withError(__('Data pengguna gagal dihapus karena ada tabel lain yang terkait dengan data ini.'));
        }
    }
}
