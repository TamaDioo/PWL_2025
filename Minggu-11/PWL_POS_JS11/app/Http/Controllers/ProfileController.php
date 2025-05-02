<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;

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

    public function editProfil()
    {
        $user = Auth::user();
        return view('profil.edit_profil', compact('user'));
    }

    // public function updateProfil(Request $request)
    // {
    //     // dd($request->all());
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $user = Auth::user();

    //         $rules = [
    //             'nama' => 'required|string|max:100',
    //             'username' => 'required|string|max:20|unique:users,username,' . $user->user_id,
    //             'password' => 'required|string|min:6|confirmed',
    //         ];

    //         $validator = Validator::make($request->all(), $rules);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Validasi gagal',
    //                 'msgField' => $validator->errors(),
    //             ], 200);
    //         }

    //         $user->nama = $request->nama;
    //         $user->username = $request->username;

    //         if ($request->filled('password')) {
    //             $user->password = Hash::make($request->password);
    //         }

    //         $user->save();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Profil berhasil diperbarui!',
    //         ], 200);
    //     }

    //     return redirect('/profil');
    // }
    public function updateProfil(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $user = Auth::user();

            $rules = [
                'nama' => 'string|max:50',
                'username' => 'string|max:50|unique:m_user,username,' . $user->user_id . ',user_id',
                'password' => 'nullable|min:6|confirmed',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ], 200); // **Sangat Penting: Kode Status 200**
            }

            $user->nama = $request->nama;
            $user->username = $request->username;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Profil berhasil diperbarui!',
            ], 200); // **Sangat Penting: Kode Status 200**
        }

        // **Hapus atau Modifikasi Baris Ini:**
        // return redirect('/profil'); // Hapus baris ini untuk permintaan AJAX
    }

    public function checkUsername(Request $request)
    {
        $userId = Auth::id(); // Ini akan mengambil user_id dari user yang sedang login.

        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:20',
                'unique:m_user,username,' . $userId . ',user_id', // Perhatikan perubahan ini
            ],
            'old_username' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(false); // Username sudah digunakan
        }

        return response()->json(true); // Username tersedia
    }
}
