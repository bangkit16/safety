<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('admin.profile.edit');
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|max:225',
            'name' => 'required|string|max:255',
        ]);

        $user = User::find($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->withStatus(__('Profile berhasil diperbaharui.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(Request $request, $id)
    {
        $request->validate([
            'old_password' => 'required|min:6',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::find($id);

        if (!Hash::check($request->input('old_password'), $user->password)) {
            return back()->withErrors(['old_password' => 'The provided password does not match our records.']);
        } else {
            $user->update([
                'password' => Hash::make($request->get('password'))
            ]);
            return back()->withPasswordStatus(__('Password berhasil diperbaharui.'));
        }
    }
}
