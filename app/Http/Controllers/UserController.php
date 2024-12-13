<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10); // Mengambil nilai limit dari URL
        $search = $request->search;

        // Sorting
        $sortBy = $request->get('sort_by', 'user_id');  // Kolom default untuk sorting
        $order = $request->get('order', 'asc');   // Urutan default ('asc' atau 'desc')

        // Query dasar
        $data = User::query();

        // Filter berdasarkan pencarian
        if ($search) {
            $role = Role::where('role_name', 'like', "%{$search}%")->get();
            if ($role->isNotEmpty()) {
                $data->where(function ($q) use ($search, $role) {
                    $q->whereIn('role_id', $role->pluck('role_id'))
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            } else {
                $data->whereRaw('1 = 0'); // Tidak ada hasil
            }
        }

        // Sorting berdasarkan kolom yang dipilih
        $data->orderBy($sortBy, $order);

        // Handling limit untuk pagination
        if ($limit == 'all') {
            $data = $data->get(); // Ambil semua data tanpa pagination
        } else {
            $data = $data->paginate($limit)->appends($request->only('search', 'limit', 'sort_by', 'order')); // Tambahkan query params
        }

        $role = Role::all();

        return view('admin.users.index', compact('data', 'role', 'sortBy', 'order'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name', // Perbaikan validasi name
            'email' => 'required|email|unique:users,email', // Pastikan email juga unique
            'password' => 'required|string|confirmed|min:6', // Minimum password 6 karakter
            'role_id' => 'required|exists:roles,role_id',
        ]);

        // Create a new role (or user, jika ini untuk user)
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash password dengan Hash::make()
            'role_id' => $request->role_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('user.index')->withStatus('User berhasil ditambahkan.');
    }

    public function update(Request $request, $id) {
        $request->validate([
            'edit_name'    => 'required|string|max:255',
            'edit_role_id'    => 'required|exists:roles,role_id',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name' =>$request->edit_name,
            'role_id'   => $request->edit_role_id,
            'updated_at'     => now(),
        ]);
        return redirect()->route('user.index')->withStatus(__('User berhasil diperbaharui.'));        
    }
    
    public function destroy(String $id)
    {
        $check = User::findOrFail($id);
        if(!$check){
            return redirect()->route('user.index')->withError(__('User tidak ditemukan.'));
        }
        try{
                User::destroy($id);
                return redirect()->route('user.index')->withStatus(__('User berhasil dihapus.'));
        }catch(\Illuminate\Database\QueryException $e){
            return redirect()->route('user.index')->withError(__('Data pengguna gagal dihapus karena ada tabel lain yang terkait dengan data ini.'));
        }
    }
}
