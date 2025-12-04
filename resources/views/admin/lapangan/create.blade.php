@extends('admin.layout')

@section('title', 'Tambah Lapangan')
@section('page-title', 'Tambah Lapangan Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>📝 Form Tambah Lapangan</h2>
        <a href="{{ route('admin.lapangan.index') }}" class="btn" style="background: #6c757d; color: white;">← Kembali</a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.lapangan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Nama Lapangan *</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                </div>

                <div class="form-group">
                    <label>Kategori Olahraga *</label>
                    <select name="sport_category_id" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('sport_category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Jumlah Lapangan *</label>
                    <input type="number" name="jumlah_lapangan" class="form-control" value="{{ old('jumlah_lapangan', 1) }}" min="1" required>
                </div>

                <div class="form-group">
                    <label>Kota *</label>
                    <input type="text" name="kota" class="form-control" value="{{ old('kota') }}" required>
                </div>

                <div class="form-group">
                    <label>Status *</label>
                    <select name="tersedia" class="form-control" required>
                        <option value="1" {{ old('tersedia', '1') == '1' ? 'selected' : '' }}>Tersedia</option>
                        <option value="0" {{ old('tersedia') == '0' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Jam Buka *</label>
                    <input type="time" name="jam_buka" class="form-control" value="{{ old('jam_buka', '06:00') }}" required>
                </div>

                <div class="form-group">
                    <label>Jam Tutup *</label>
                    <input type="time" name="jam_tutup" class="form-control" value="{{ old('jam_tutup', '23:00') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Alamat Lengkap *</label>
                <textarea name="alamat" class="form-control" required>{{ old('alamat') }}</textarea>
            </div>

            <div class="form-group">
                <label>Deskripsi *</label>
                <textarea name="deskripsi" class="form-control" required>{{ old('deskripsi') }}</textarea>
            </div>

            <div class="form-group">
                <label>Fasilitas (pisahkan dengan koma)</label>
                <input type="text" name="fasilitas" class="form-control" value="{{ old('fasilitas') }}"
                       placeholder="Contoh: Parkir Luas, Toilet, Kantin, Mushola">
                <small style="color: #666;">Contoh: Parkir Luas, Toilet, Kantin, Mushola</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Harga per Jam (Weekday) *</label>
                    <input type="number" name="harga_per_jam" class="form-control" value="{{ old('harga_per_jam') }}" min="0" required>
                </div>

                <div class="form-group">
                    <label>Harga per Jam (Weekend)</label>
                    <input type="number" name="harga_weekend" class="form-control" value="{{ old('harga_weekend') }}" min="0">
                    <small style="color: #666;">Kosongkan jika sama dengan harga weekday</small>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Jam Buka *</label>
                    <input type="time" name="jam_buka" class="form-control" value="{{ old('jam_buka', '07:00') }}" required>
                </div>

                <div class="form-group">
                    <label>Jam Tutup *</label>
                    <input type="time" name="jam_tutup" class="form-control" value="{{ old('jam_tutup', '22:00') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Gambar Lapangan</label>
                <input type="file" name="gambar" class="form-control" accept="image/jpeg,image/png,image/jpg" onchange="previewImage(event)">
                <small style="color: #666;">Format: JPG, PNG. Max: 2MB</small>
                <div id="imagePreview" style="margin-top: 10px;"></div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 30px;">
                <button type="submit" class="btn btn-primary">💾 Simpan Lapangan</button>
                <a href="{{ route('admin.lapangan.index') }}" class="btn" style="background: #6c757d; color: white;">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">`;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
