<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileRequest;
use App\Support\ImageStore;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

/** Pengaturan profil admin yang sedang login: nama, email, dan password. */
class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.profile', ['user' => $request->user()]);
    }

    /** Ubah nama, email, dan foto profil. */
    public function update(ProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $attributes = ['name' => $data['name'], 'email' => $data['email']];
        $oldPhoto = $user->photo;

        if ($request->hasFile('image')) {
            // Foto dipotong persegi di tengah, lebar maksimal 400 px (cukup tajam untuk avatar)
            $attributes['photo'] = ImageStore::put($request->file('image'), 'uploads/avatars', 400, 1.0);
        } elseif ($request->boolean('remove_photo')) {
            $attributes['photo'] = null;
        }

        $user->update($attributes);

        if (array_key_exists('photo', $attributes) && $oldPhoto) {
            ImageStore::delete($oldPhoto); // buang file lama agar tidak menumpuk
        }

        return redirect()->route('admin.profile.edit')->with('status', 'Profil berhasil diperbarui.');
    }

    /** Ganti password: harus menyebut password lama agar orang lain tidak bisa mengganti seenaknya. */
    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'different:current_password', Password::min(8)],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'current_password.current_password' => 'Password lama salah.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password baru tidak sama.',
            'password.different' => 'Password baru tidak boleh sama dengan password lama.',
            'password.min' => 'Password baru minimal 8 karakter.',
        ]);

        $request->user()->update(['password' => $data['password']]); // otomatis di-hash oleh model

        $request->session()->regenerate();

        return redirect()->route('admin.profile.edit')->with('status', 'Password berhasil diganti.');
    }
}