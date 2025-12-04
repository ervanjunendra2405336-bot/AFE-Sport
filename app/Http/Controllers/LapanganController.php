<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\SportCategory;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Lapangan::with('sportCategory')->where('tersedia', true);

        // Filter berdasarkan kategori olahraga
        if ($request->has('category') && $request->category != '') {
            $query->where('sport_category_id', $request->category);
        }

        // Filter berdasarkan kota
        if ($request->has('kota') && $request->kota != '') {
            $query->where('kota', 'like', '%' . $request->kota . '%');
        }

        // Filter berdasarkan tipe (indoor/outdoor)
        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe', $request->tipe);
        }

        // Filter berdasarkan harga
        if ($request->has('harga_min')) {
            $query->where('harga_per_jam', '>=', $request->harga_min);
        }
        if ($request->has('harga_max')) {
            $query->where('harga_per_jam', '<=', $request->harga_max);
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');

        if ($sortBy === 'harga') {
            $query->orderBy('harga_per_jam', $sortOrder);
        } elseif ($sortBy === 'rating') {
            $query->orderBy('rating', 'desc')->orderBy('jumlah_review', 'desc');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $lapangan = $query->paginate(12);
        $categories = SportCategory::getAktif();
        $kotas = Lapangan::select('kota')->distinct()->whereNotNull('kota')->pluck('kota');

        return view('lapangan.index', compact('lapangan', 'categories', 'kotas'));
    }

    public function show($id)
    {
        $lapangan = Lapangan::with('sportCategory')->findOrFail($id);
        $relatedLapangan = Lapangan::where('sport_category_id', $lapangan->sport_category_id)
            ->where('id', '!=', $id)
            ->where('tersedia', true)
            ->limit(4)
            ->get();

        return view('lapangan.show', compact('lapangan', 'relatedLapangan'));
    }

    public function byCategory($slug)
    {
        $category = SportCategory::where('slug', $slug)->firstOrFail();
        $lapangan = Lapangan::with('sportCategory')
            ->where('sport_category_id', $category->id)
            ->where('tersedia', true)
            ->orderBy('rating', 'desc')
            ->paginate(12);

        $categories = SportCategory::getAktif();
        $kotas = Lapangan::where('sport_category_id', $category->id)
            ->select('kota')
            ->distinct()
            ->whereNotNull('kota')
            ->pluck('kota');

        return view('lapangan.category', compact('lapangan', 'category', 'categories', 'kotas'));
    }
}
