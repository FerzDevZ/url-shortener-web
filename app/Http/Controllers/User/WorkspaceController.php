<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkspaceController extends Controller
{
    public function index()
    {
        $workspaces = auth()->user()->workspaces()->withCount('users', 'links')->get();
        return view('user.workspaces.index', compact('workspaces'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $workspace = Workspace::create($validated);
        $workspace->users()->attach(auth()->id(), ['role' => 'admin']);

        return back()->with('success', 'Workspace tim berhasil dibuat!');
    }

    public function show(Workspace $workspace)
    {
        // Pastikan user adalah anggota workspace ini
        if (!$workspace->users()->where('user_id', auth()->id())->exists()) {
            abort(403, 'Anda bukan anggota workspace ini.');
        }

        $workspace->load(['users', 'links']);
        
        // Ambil role current user
        $myRole = $workspace->users()->where('user_id', auth()->id())->first()->pivot->role;

        return view('user.workspaces.show', compact('workspace', 'myRole'));
    }

    public function addMember(Request $request, Workspace $workspace)
    {
        // Pastikan current user adalah admin di workspace ini
        $myPivot = $workspace->users()->where('user_id', auth()->id())->first();
        if (!$myPivot || $myPivot->pivot->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan Admin tim ini.');
        }

        $validated = $request->validate([
            'email' => ['required', 'email', Rule::exists('users', 'email')],
            'role'  => ['required', Rule::in(['admin', 'member'])],
        ], [
            'email.exists' => 'Pengguna dengan email ini tidak ditemukan terdaftar di sistem.'
        ]);

        $userToAdd = User::where('email', $validated['email'])->first();

        // Mencegah masukin diri sendiri ulang
        if ($userToAdd->id === auth()->id()) {
            return back()->withErrors(['email' => 'Anda sudah berada di dalam tim.']);
        }

        if ($workspace->users()->where('user_id', $userToAdd->id)->exists()) {
            return back()->withErrors(['email' => 'Pengguna tersebut sudah menjadi anggota tim.']);
        }

        $workspace->users()->attach($userToAdd->id, ['role' => $validated['role']]);

        return back()->with('success', "{$userToAdd->name} berhasil ditambahkan ke dalam tim!");
    }

    public function removeMember(Workspace $workspace, User $user)
    {
        // Pastikan current user adalah admin di workspace ini
        $myPivot = $workspace->users()->where('user_id', auth()->id())->first();
        if (!$myPivot || $myPivot->pivot->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan Admin tim ini.');
        }

        // Jangan izinkan admin menghapus diri sendiri melalui route ini (mencegah tim tanpa admin sama sekali)
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak bisa mengeluarkan diri sendiri. Gunakan fitur Keluar Tim.']);
        }

        $workspace->users()->detach($user->id);

        return back()->with('success', 'Anggota berhasil dikeluarkan dari tim.');
    }

    public function updateMemberRole(Request $request, Workspace $workspace, User $user)
    {
        $myPivot = $workspace->users()->where('user_id', auth()->id())->first();
        if (!$myPivot || $myPivot->pivot->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan Admin tim ini.');
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'member'])],
        ]);

        $workspace->users()->updateExistingPivot($user->id, ['role' => $validated['role']]);

        return back()->with('success', "Peran {$user->name} berhasil diperbarui.");
    }

    public function update(Request $request, Workspace $workspace)
    {
        $myPivot = $workspace->users()->where('user_id', auth()->id())->first();
        if (!$myPivot || $myPivot->pivot->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan Admin tim ini.');
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $workspace->update($validated);
        return back()->with('success', 'Detail workspace berhasil diperbarui.');
    }

    public function destroy(Workspace $workspace)
    {
        $myPivot = $workspace->users()->where('user_id', auth()->id())->first();
        if (!$myPivot || $myPivot->pivot->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan Admin tim ini.');
        }

        // Hapus paksa links terkait dari database (karena tidak pake soft delete, 
        // pastikan setelan foreign key DB aman, tapi lebih baik dihapus manual sini juga
        // atau memanfaatkan cascadeOnDelete() milik foreign id table)
        // Kita biarkan constraint DB bekerja jika ada.

        $workspace->delete();

        return redirect()->route('user.workspaces.index')->with('success', 'Lingkungan Kerja berhasil dibubarkan.');
    }
}
