<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Property;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        // baza: samo objavljene
        $base = Property::query()->where('is_published', true);

        // broj dostupnih po tipu
        $rentCount = (clone $base)
            ->whereHas('category', fn($q) => $q->where('type', 'rent'))
            ->count();

        $saleCount = (clone $base)
            ->whereHas('category', fn($q) => $q->where('type', 'sale'))
            ->count();

        // najnovije za listu
        $latest = (clone $base)
            ->with('category')
            ->latest()
            ->take(6)
            ->get();

        // po jedan cover za kartice
        $rent = (clone $base)
            ->whereHas('category', fn($q) => $q->where('type', 'rent'))
            ->whereNotNull('images')
            ->latest()
            ->first();

        $sale = (clone $base)
            ->whereHas('category', fn($q) => $q->where('type', 'sale'))
            ->whereNotNull('images')
            ->latest()
            ->first();

        $rentCover = $rent && is_array($rent->images) && !empty($rent->images) ? $rent->images[0] : null;
        $saleCover = $sale && is_array($sale->images) && !empty($sale->images) ? $sale->images[0] : null;

        $toUrl = function ($path) {
            if (!$path) return null;
            if (Str::startsWith($path, ['http://', 'https://'])) return $path;
            if (Str::startsWith($path, ['/storage/', 'storage/'])) return url(ltrim($path, '/'));
            return asset('storage/' . ltrim($path, '/'));
        };

        return view('home', [
            'latest'    => $latest,
            'rentCount' => $rentCount,
            'saleCount' => $saleCount,
            'rentCover' => $toUrl($rentCover) ?: 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=1600&auto=format&fit=crop',
            'saleCover' => $toUrl($saleCover) ?: 'https://images.unsplash.com/photo-1560185008-b033106af2de?q=80&w=1600&auto=format&fit=crop',
        ]);
    }
}
