<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\SportCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Lapangan::with('sportCategory');

        if ($request->has('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('sport_category_id', $request->category);
        }

        if ($request->has('tersedia') && $request->tersedia != '') {
            $query->where('tersedia', $request->tersedia);
        }

        $lapangan = $query->paginate(15);
        $categories = SportCategory::all();

        return view('admin.lapangan.index', compact('lapangan', 'categories'));
    }

    public function create()
    {
        $categories = SportCategory::all();
        return view('admin.lapangan.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'sport_category_id' => 'required|exists:sport_categories,id',
            'jumlah_lapangan' => 'required|integer|min:1',
            'kota' => 'required|string|max:100',
            'alamat' => 'required|string',
            'deskripsi' => 'required|string',
            'fasilitas' => 'nullable|string',
            'harga_per_jam' => 'required|numeric|min:0',
            'harga_weekend' => 'nullable|numeric|min:0',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tersedia' => 'required|boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/lapangan'), $filename);
            $validated['foto'] = 'uploads/lapangan/' . $filename;
        }

        // Convert fasilitas to JSON
        if ($request->fasilitas) {
            $fasilitasArray = array_map('trim', explode(',', $request->fasilitas));
            $validated['fasilitas'] = json_encode($fasilitasArray);
        }

        Lapangan::create($validated);

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Lapangan $lapangan)
    {
        $categories = SportCategory::all();
        return view('admin.lapangan.edit', compact('lapangan', 'categories'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'sport_category_id' => 'required|exists:sport_categories,id',
            'jumlah_lapangan' => 'required|integer|min:1',
            'kota' => 'required|string|max:100',
            'alamat' => 'required|string',
            'deskripsi' => 'required|string',
            'fasilitas' => 'nullable|string',
            'harga_per_jam' => 'required|numeric|min:0',
            'harga_weekend' => 'nullable|numeric|min:0',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'tersedia' => 'required|boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($lapangan->foto && !str_contains($lapangan->foto, 'placehold.co')) {
                $oldPath = public_path($lapangan->foto);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/lapangan'), $filename);
            $validated['foto'] = 'uploads/lapangan/' . $filename;
        }

        // Convert fasilitas to JSON
        if ($request->fasilitas) {
            $fasilitasArray = array_map('trim', explode(',', $request->fasilitas));
            $validated['fasilitas'] = json_encode($fasilitasArray);
        }

        $lapangan->update($validated);

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Lapangan $lapangan)
    {
        // Delete image if exists
        if ($lapangan->foto && !str_contains($lapangan->foto, 'placehold.co')) {
            $oldPath = public_path($lapangan->foto);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil dihapus.');
    }

    public function updateStatus(Request $request, Lapangan $lapangan)
    {
        $validated = $request->validate([
            'tersedia' => 'required|boolean',
        ]);

        $lapangan->update($validated);

        return back()->with('success', 'Status lapangan berhasil diperbarui.');
    }
}
