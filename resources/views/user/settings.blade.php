@extends('user.layout')

@section('page-title', 'Pengaturan & API')
@section('page-subtitle', 'Kelola akses API dan preferensi akun')

@section('content')
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header"><h3 class="card-title">Token API Personal</h3></div>
    <div class="card-body">
        <p class="text-secondary" style="font-size: 0.9rem; margin-bottom: 1.5rem;">
            Token API memungkinkan layanan eksternal atau script Anda untuk membuat, membaca, dan menghapus link secara terprogram. Anda dapat menggunakan Endpoint <code style="background: var(--bg-hover); padding: 0.2rem 0.4rem; border-radius: var(--radius-sm);">/api/v1/links</code>.
        </p>

        @if(session('token_plain'))
            <div style="background: var(--success-subtle); border: 1px solid rgba(34,197,94,0.3); padding: 1.5rem; border-radius: var(--radius-sm); margin-bottom: 2rem;">
                <div style="font-size: 0.85rem; color: #15803d; font-weight: 600; margin-bottom: 0.5rem;">Token Berhasil Dibuat!</div>
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                    <code style="font-size: 1.15rem; font-weight: 700; color: var(--accent); user-select: all; word-break: break-all;">{{ session('token_plain') }}</code>
                </div>
                <div class="text-danger" style="font-size: 0.8rem; margin-top: 0.5rem;">* Pastikan Anda menyalin token ini sekarang. Token tidak akan ditampilkan lagi setelah Anda meninggalkan halaman ini.</div>
            </div>
        @endif

        <form action="{{ route('user.settings.token') }}" method="POST" style="display: flex; gap: 0.75rem; margin-bottom: 2rem; max-width: 500px;">
            @csrf
            <input type="text" name="name" placeholder="Nama Token (misal: Script Otomatis)" class="form-control" required>
            <button type="submit" class="btn btn-primary">Buat Token</button>
        </form>

        <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-primary);">Token Aktif</h4>
        
        @if($tokens->isEmpty())
            <div class="text-muted" style="font-size: 0.875rem;">Belum ada token yang dibuat.</div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Token</th>
                        <th>Terakhir Digunakan</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tokens as $token)
                <tr>
                    <td style="font-weight: 500;" class="text-primary">{{ $token->name }}</td>
                    <td class="text-secondary">{{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Belum pernah digunakan' }}</td>
                    <td style="text-align: right;">
                        <form action="{{ route('user.settings.token.revoke', $token->id) }}" method="POST" onsubmit="return confirm('Cabut akses untuk token ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Cabut Token</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
