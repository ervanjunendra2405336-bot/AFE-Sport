<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\SportCategory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = SportCategory::getAktif();
        $featuredLapangan = Lapangan::with('sportCategory')
            ->where('tersedia', true)
            ->orderBy('rating', 'desc')
            ->orderBy('jumlah_review', 'desc')
            ->take(6)
            ->get();

        return view('home', compact('categories', 'featuredLapangan'));
    }
}
