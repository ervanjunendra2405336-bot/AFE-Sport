<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Mapping kategori ke nama file gambar
$categoryMapping = [
    'Futsal' => 'futsal',
    'Basket' => 'basket',
    'Tenis' => 'tenis',
    'Badminton' => 'badminton',
    'Voli' => 'voli',
    'Mini Soccer' => 'minisoccer',
    'Padel' => 'padel'
];

$lapangan = DB::table('lapangan')
    ->join('sport_categories', 'lapangan.sport_category_id', '=', 'sport_categories.id')
    ->select('lapangan.*', 'sport_categories.nama as category_name')
    ->orderBy('sport_categories.nama')
    ->orderBy('lapangan.id')
    ->get();

// Counter per kategori
$counters = [];

foreach($lapangan as $lap) {
    $categoryKey = $lap->category_name;

    // Increment counter untuk kategori ini
    if (!isset($counters[$categoryKey])) {
        $counters[$categoryKey] = 1;
    } else {
        $counters[$categoryKey]++;
    }

    // Get prefix nama file
    $prefix = $categoryMapping[$categoryKey] ?? 'lapangan';
    $newImage = "images/{$prefix}{$counters[$categoryKey]}.jpg";

    // Update database
    DB::table('lapangan')->where('id', $lap->id)->update(['foto' => $newImage]);

    echo sprintf("%-30s -> %s\n", $lap->nama, $newImage);
}

echo "\n✅ Total: Updated " . count($lapangan) . " lapangan\n";
echo "\n📊 Breakdown per kategori:\n";
foreach($counters as $cat => $count) {
    $prefix = $categoryMapping[$cat] ?? 'lapangan';
    echo "  {$cat}: {$count} files ({$prefix}1.jpg - {$prefix}{$count}.jpg)\n";
}
