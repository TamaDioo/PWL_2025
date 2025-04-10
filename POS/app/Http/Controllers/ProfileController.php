<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Profil User',
            'list' => ['Home', 'Profil']
        ];


        $activeMenu = 'profil'; // set menu yang sedang aktif

        $user = Auth::user();

        return view('profil.index', ['breadcrumb' => $breadcrumb, 'user' => $user, 'activeMenu' => $activeMenu]);
    }

    public function uploadFoto()
    {
        $user = auth()->user();
        return view('profil.upload_foto', compact('user'));
    }

    public function simpanFoto(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'foto_profile' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $user = Auth::user();

            if ($user->foto_profile && Storage::exists($user->foto_profile)) {
                Storage::delete($user->foto_profile);
            }

            $path = $request->file('foto_profile')->store('foto_profil', 'public');

            $user->foto_profile = $path;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Foto profil berhasil diubah!',
                'foto_url' => asset('storage/' . $path)
            ]);
        }

        return redirect('/');
    }
}
