@extends('user.layout')

@section('page-title', 'Edit Link')
@section('page-subtitle', 'Mengubah pengaturan untuk /' . ($link->custom_alias ?? $link->short_code))

@section('content')
<div style="max-width: 540px; margin: 0 auto;">
    <form action="{{ route('user.links.update', $link) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="card">
            <div class="card-body" style="padding: 2rem;">
                <!-- Tampilkan URL Asli (Readonly) -->
                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label">URL Asli Target</label>
                    <div style="background: var(--bg-hover); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 0.75rem 1rem; font-size: 0.875rem; color: var(--text-secondary); word-break: break-all; cursor: not-allowed;">
                        {{ $link->original_url }}
                    </div>
                </div>

                <!-- Field Judul -->
                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label">Judul Referensi</label>
                    <input type="text" name="title" value="{{ old('title', $link->title) }}" class="form-control" placeholder="Nama link (misal: Kampanye Promo Promo 2)">
                </div>

                <!-- Field Kadaluarsa -->
                <div style="margin-bottom: 1.5rem;">
                    <label class="form-label">Ubah Tanggal Kadaluarsa</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $link->expires_at?->format('Y-m-d\TH:i')) }}" class="form-control">
                    <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.35rem;">Biarkan kosong jika tautan tidak pernah kadaluarsa.</div>
                </div>

                <!-- Toggle Aktif/Nonaktif -->
                <div style="margin-bottom: 2rem; border-top: 1px dashed var(--border); padding-top: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ $link->is_active ? 'checked' : '' }} style="accent-color: var(--accent); width: 1.25rem; height: 1.25rem; border-radius: var(--radius-sm);">
                        <div>
                            <span class="text-primary" style="font-size: 0.95rem; font-weight: 500; display: block;">Tautan Aktif</span>
                            <span class="text-muted" style="font-size: 0.8rem;">Jika dimatikan, orang yang mengakses link akan melihat pesan tidak aktif.</span>
                        </div>
                    </label>
                </div>

                <hr class="my-4" style="border-color: var(--border);">
                <h6 class="text-primary mb-3" style="font-weight: 600;">Analitik & Branding (Advanced)</h6>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="fb_pixel_id" class="form-label">Facebook Pixel ID</label>
                        <input type="text" name="fb_pixel_id" id="fb_pixel_id" class="form-control" placeholder="Contoh: 1234567890" value="{{ old('fb_pixel_id', $link->fb_pixel_id) }}">
                        @error('fb_pixel_id') <div class="text-danger" style="font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="gtm_id" class="form-label">Google Tag Manager ID</label>
                        <input type="text" name="gtm_id" id="gtm_id" class="form-control" placeholder="Contoh: GTM-XXXXXXX" value="{{ old('gtm_id', $link->gtm_id) }}">
                        @error('gtm_id') <div class="text-danger" style="font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 2rem;">
                    <div>
                        <label for="qr_color" class="form-label">Warna Utama QR Code</label>
                        <div style="display: flex; align-items: center;">
                            <input type="color" name="qr_color" id="qr_color" class="form-control form-control-color" value="{{ old('qr_color', $link->qr_color ?? '#000000') }}" style="width: 50px; padding: 0.25rem; margin-right: 0.5rem;" title="Pilih warna">
                            <span class="text-muted" style="font-size: 0.875rem;">Sesuaikan dengan brand Anda</span>
                        </div>
                        @error('qr_color') <div class="text-danger" style="font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="form-label">Ganti Logo QR Code (Opsional)</label>
                        <input type="file" name="qr_logo" id="qr_logo" class="form-control" accept="image/png, image/jpeg, image/svg+xml">
                        <div class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem;">
                            {{ $link->qr_logo_path ? 'Biarkan kosong jika tidak ingin mengubah logo saat ini.' : 'Ikon kecil di tengah QR. Maks 500KB.' }}
                        </div>
                        @error('qr_logo') <div class="text-danger" style="font-size: 0.8rem; margin-top: 0.35rem;">{{ $message }}</div> @enderror

                        @if($link->qr_logo_path)
                        <div style="margin-top: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <img src="{{ Storage::url($link->qr_logo_path) }}" alt="Current Logo" style="width: 32px; height: 32px; object-fit: contain; border-radius: 4px; border: 1px solid var(--border); padding: 2px;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.875rem;" class="text-danger">
                                <input type="checkbox" name="remove_qr_logo" value="1">
                                Hapus Logo Saat Ini
                            </label>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1rem; border-top: 1px solid var(--border);">
                    <a href="{{ route('user.links.show', $link) }}" class="btn btn-secondary btn-lg">Kembali</a>
                    <button type="submit" class="btn btn-primary btn-lg">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
