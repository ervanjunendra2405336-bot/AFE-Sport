<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SportCategory;

class SportCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nama' => 'Futsal',
                'slug' => 'futsal',
                'deskripsi' => 'Lapangan futsal indoor dan outdoor untuk permainan tim 5vs5',
                'icon' => '⚽',
                'aktif' => true,
                'urutan' => 1,
            ],
            [
                'nama' => 'Basket',
                'slug' => 'basket',
                'deskripsi' => 'Lapangan basket indoor dan outdoor untuk pertandingan profesional maupun casual',
                'icon' => '🏀',
                'aktif' => true,
                'urutan' => 2,
            ],
            [
                'nama' => 'Tenis',
                'slug' => 'tenis',
                'deskripsi' => 'Lapangan tenis outdoor dengan permukaan hard court, clay court, atau grass court',
                'icon' => '🎾',
                'aktif' => true,
                'urutan' => 3,
            ],
            [
                'nama' => 'Badminton',
                'slug' => 'badminton',
                'deskripsi' => 'Lapangan badminton indoor dengan fasilitas lengkap',
                'icon' => '🏸',
                'aktif' => true,
                'urutan' => 4,
            ],
            [
                'nama' => 'Voli',
                'slug' => 'voli',
                'deskripsi' => 'Lapangan voli indoor dan outdoor untuk permainan 6vs6',
                'icon' => '🏐',
                'aktif' => true,
                'urutan' => 5,
            ],
            [
                'nama' => 'Mini Soccer',
                'slug' => 'mini-soccer',
                'deskripsi' => 'Lapangan mini soccer 7vs7 dan 9vs9 untuk berbagai format permainan',
                'icon' => '⚽',
                'aktif' => true,
                'urutan' => 6,
            ],
            [
                'nama' => 'Padel',
                'slug' => 'padel',
                'deskripsi' => 'Lapangan padel tennis yang sedang populer di Indonesia',
                'icon' => '🎾',
                'aktif' => true,
                'urutan' => 7,
            ],
        ];

        foreach ($categories as $category) {
            SportCategory::create($category);
        }
    }
}
