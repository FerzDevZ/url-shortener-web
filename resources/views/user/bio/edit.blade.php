@extends('user.layout')

@section('page-title', 'Pengaturan Link in Bio')
@section('page-subtitle', 'Buat halaman profile custom untuk menampilkan semua link aktif Anda')

@section('topbar-actions')
    <a href="{{ route('bio.show', $bio->slug) }}" target="_blank" class="btn btn-secondary">Lihat Halaman Bio</a>
@endsection

@section('content')
<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header"><h3 class="card-title">Profil Link in Bio</h3></div>
    <div class="card-body">
        <form action="{{ route('user.bio.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label class="form-label">Nama Pengguna (Slug/URL)</label>
                    <div style="display: flex; align-items: center; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-hover); overflow: hidden;">
                        <span class="text-muted" style="padding: 0 0.75rem; font-size: 0.875rem;">{{ url('/u') }}/</span>
                        <input type="text" name="slug" value="{{ old('slug', $bio->slug) }}" class="form-control" style="border: none; border-radius: 0; box-shadow: none; padding-left: 0;" required>
                    </div>
                </div>
                <div>
                    <label class="form-label">Tema Warna</label>
                    <select name="theme_color" class="form-control">
                        <option value="dark" {{ $bio->theme_color === 'dark' ? 'selected' : '' }}>Mode Gelap (Dark)</option>
                        <option value="light" {{ $bio->theme_color === 'light' ? 'selected' : '' }}>Mode Terang (Light)</option>
                        <option value="blue" {{ $bio->theme_color === 'blue' ? 'selected' : '' }}>Aksen Biru</option>
                        <option value="green" {{ $bio->theme_color === 'green' ? 'selected' : '' }}>Aksen Hijau</option>
                        <option value="purple" {{ $bio->theme_color === 'purple' ? 'selected' : '' }}>Aksen Ungu</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label class="form-label">Judul Halaman / Nama Brand</label>
                <input type="text" name="title" value="{{ old('title', $bio->title) }}" class="form-control">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label class="form-label">Bio Singkat</label>
                <textarea name="bio" rows="3" class="form-control" placeholder="Tuliskan deksripsi singkat tentang Anda atau brand Anda...">{{ old('bio', $bio->bio) }}</textarea>
            </div>

            <div style="margin-bottom: 2rem;">
                <label class="form-label">Foto Profil Maks 2MB (Gantungkan saja jika tak ingin diubah)</label>
                @if($bio->photo_path)
                    <div style="margin-bottom: 1rem;">
                        <img src="{{ Storage::url($bio->photo_path) }}" alt="Current Photo" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                    </div>
                @endif
                <input type="file" name="photo" accept="image/*" class="form-control">
            </div>

            <div style="display: flex; justify-content: flex-end; padding-top: 1rem; border-top: 1px solid var(--border);">
                <button type="submit" class="btn btn-primary btn-lg">Simpan Pengaturan Bio</button>
            </div>
        </form>
    </div>
</div>
@endsection
