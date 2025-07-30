<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Validation\Rule;
    use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
     public function index()
        {
            // Ambil semua user kecuali admin yang sedang login
            $users = User::where('id', '!=', Auth::id())->latest()->paginate(10);
            return view('pages.admin.users.index', compact('users'));
        }

        public function create()
        {
            return view('pages.admin.users.create');
        }

        public function store(Request $request)
        {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'role' => ['required', 'string', Rule::in(['admin', 'customer'])],
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
        }

        public function edit(User $user)
        {
            return view('pages.admin.users.edit', compact('user'));
        }

        public function update(Request $request, User $user)
        {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
                'role' => ['required', 'string', Rule::in(['admin', 'customer'])],
            ]);

            $data = $request->only('name', 'email', 'role');
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
        }

        public function destroy(User $user)
        {
            // Tambahan keamanan: pastikan admin tidak menghapus akunnya sendiri
            if ($user->id === Auth::id()) {
                return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            }

            $user->delete();

            return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
        }
}
