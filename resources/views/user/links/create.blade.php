@extends('user.layout')

@section('page-title', 'Buat Link Baru')
@section('page-subtitle', 'Persingkat URL panjang Anda dengan opsi lanjutan')

@section('content')
<div style="max-width: 640px; margin: 0 auto;">
    <form action="{{ route('user.links.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body" style="padding: 2rem;">
                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label">URL Asli (Wajib)</label>
                    <input type="url" name="original_url" value="{{ old('original_url') }}" class="form-control" placeholder="https://contoh.com/artikel/sangat-panjang-sekali..." required>
                    @error('original_url') <div class="text-danger" style="font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</div> @enderror
                </div>

                <!-- Kepemilikan Workspace (Tim) -->
                <div style="margin-bottom: 1.5rem; background: var(--bg-hover); padding: 1rem; border-radius: var(--radius-sm); border: 1px dashed var(--border);">
                    <label class="form-label" for="workspace_id">Tambahkan ke dalam Tim (Opsional)</label>
                    <select name="workspace_id" id="workspace_id" class="form-control">
                        <option value="">-- Personal (Hanya saya yang bisa mengelola) --</option>
                        @foreach($workspaces as $workspace)
                            <option value="{{ $workspace->id }}" {{ (old('workspace_id') == $workspace->id || (isset($selectedWorkspaceId) && $selectedWorkspaceId == $workspace->id)) ? 'selected' : '' }}>
                                {{ $workspace->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.35rem;">
                        Tautan yang dimasukkan ke Workspace dapat dilihat, diedit metriknya, dan dihapus oleh rekan anggota tim lain.
                    </div>
                    @error('workspace_id') <div class="text-danger mt-1 text-sm">{{ $message }}</div> @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div>
                        <label class="form-label">Judul Referensi (Opsional)</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="Nama link (hanya Anda yang melihat)">
                        @error('title') <div class="text-danger" style="font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">Custom Alias (Opsional)</label>
                        <div style="display: flex; align-items: center; border: 1px solid var(--border); border-radius: var(--radius-sm); background: var(--bg-card); overflow: hidden; transition: border-color 0.15s, box-shadow 0.15s;" class="custom-alias-wrapper">
                            <span class="text-muted" style="font-size: 0.875rem; padding-left: 0.75rem; white-space: nowrap;">{{ url('/') }}/</span>
                            <input type="text" name="custom_alias" value="{{ old('custom_alias') }}" class="form-control" style="border: none; background: transparent; padding-left: 0.25rem; box-shadow: none;" placeholder="nama-kustom" pattern="[a-zA-Z0-9_\-]+">
                        </div>
                        <style>
                            .custom-alias-wrapper:focus-within {
                                border-color: var(--border-focus);
                                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
                            }
                        </style>
                        @error('custom_alias') <div class="text-danger" style="font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 2rem;">
                    <div>
                        <label class="form-label">Lindungi dengan Password (Opsional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password">
                        <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem;">Kosongkan jika ingin publik</div>
                        @error('password') <div class="text-danger" style="font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">Tanggal Kadaluarsa (Opsional)</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="form-control">
                        @error('expires_at') <div class="text-danger" style="font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border);">
                <h6 class="text-primary mb-3" style="font-weight: 600;">Analitik & Branding (Advanced)</h6>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="fb_pixel_id" class="form-label">Facebook Pixel ID</label>
                        <input type="text" name="fb_pixel_id" id="fb_pixel_id" class="form-control" placeholder="Contoh: 1234567890" value="{{ old('fb_pixel_id') }}">
                        @error('fb_pixel_id') <div class="text-danger" style="font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="gtm_id" class="form-label">Google Tag Manager ID</label>
                        <input type="text" name="gtm_id" id="gtm_id" class="form-control" placeholder="Contoh: GTM-XXXXXXX" value="{{ old('gtm_id') }}">
                        @error('gtm_id') <div class="text-danger" style="font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 2rem;">
                    <div>
                        <label for="qr_color" class="form-label">Warna Utama QR Code</label>
                        <div style="display: flex; align-items: center;">
                            <input type="color" name="qr_color" id="qr_color" class="form-control form-control-color" value="{{ old('qr_color', '#000000') }}" style="width: 50px; padding: 0.25rem; margin-right: 0.5rem;" title="Pilih warna">
                            <span class="text-muted" style="font-size: 0.875rem;">Sesuaikan dengan brand Anda</span>
                        </div>
                        @error('qr_color') <div class="text-danger" style="font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">Logo QR Code (Opsional)</label>
                        <input type="file" name="qr_logo" id="qr_logo" class="form-control" accept="image/png, image/jpeg, image/svg+xml">
                        <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem;">Ikon kecil di tengah QR. Maks 500KB.</div>
                        @error('qr_logo') <div class="text-danger" style="font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1rem; border-top: 1px solid var(--border);">
                    <a href="{{ route('user.links.index') }}" class="btn btn-secondary btn-lg">Batal</a>
                    <button type="submit" class="btn btn-primary btn-lg">Buat Link Pendek</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
