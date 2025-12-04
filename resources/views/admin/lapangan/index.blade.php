@extends('admin.layout')

@section('title', 'Manajemen Lapangan')
@section('page-title', 'Manajemen Lapangan')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>📋 Daftar Lapangan</h2>
        <a href="{{ route('admin.lapangan.create') }}" class="btn btn-primary">+ Tambah Lapangan</a>
    </div>

    <div class="card-body">
        <!-- Filter -->
        <form method="GET" style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
            <input type="text" name="search" class="form-control" placeholder="Cari nama lapangan..."
                   value="{{ request('search') }}" style="max-width: 300px;">

            <select name="category" class="form-control" style="max-width: 200px;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nama }}
                    </option>
                @endforeach
            </select>

            <select name="tersedia" class="form-control" style="max-width: 200px;">
                <option value="">Semua Status</option>
                <option value="1" {{ request('tersedia') == '1' ? 'selected' : '' }}>Tersedia</option>
                <option value="0" {{ request('tersedia') == '0' ? 'selected' : '' }}>Tidak Tersedia</option>
            </select>

            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.lapangan.index') }}" class="btn" style="background: #6c757d; color: white;">Reset</a>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Lapangan</th>
                        <th>Kategori</th>
                        <th>Kota</th>
                        <th>Jumlah</th>
                        <th>Harga/Jam</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lapangan as $item)
                    <tr>
                        <td>
                            <img src="{{ asset($item->foto) }}"
                                 alt="{{ $item->nama }}"
                                 style="width: 80px; height: 60px; object-fit: cover; border-radius: 6px;">
                        </td>
                        <td><strong>{{ $item->nama }}</strong></td>
                        <td>
                            <span class="badge" style="background: {{ $item->sportCategory->warna }}; color: white;">
                                {{ $item->sportCategory->nama }}
                            </span>
                        </td>
                        <td>{{ $item->kota }}</td>
                        <td>{{ $item->jumlah_lapangan }} lapangan</td>
                        <td><strong>Rp {{ number_format($item->harga_per_jam, 0, ',', '.') }}</strong></td>
                        <td>
                            @if($item->tersedia)
                                <span class="badge badge-success">Tersedia</span>
                            @else
                                <span class="badge badge-danger">Tidak Tersedia</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.lapangan.edit', $item) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('admin.lapangan.destroy', $item) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus lapangan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                            Tidak ada data lapangan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($lapangan->hasPages())
        <div style="margin-top: 20px; display: flex; justify-content: center;">
            {{ $lapangan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
