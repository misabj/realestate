<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Banner; // 👈 dodato
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $q = Property::query()
            ->with(['category'])
            ->where('is_published', true);

        if ($type = $request->string('type')->trim()->value()) {
            $q->whereHas('category', fn ($c) => $c->where('type', $type));
        }

        if ($kw = $request->string('q')->trim()->lower()->value()) {
            $normalize = [
                'belgrade' => 'beograd',
                'beograd'  => 'beograd',
                'novi sad' => 'novi sad',
                'nis'      => 'niš',
                'niš'      => 'niš',
                'niš'     => 'niš',
            ];
            $needle = $normalize[$kw] ?? $kw;

            $q->where(function ($x) use ($needle) {
                $x->whereRaw('LOWER(city) LIKE ?', ["%{$needle}%"])
                    ->orWhereRaw('LOWER(address) LIKE ?', ["%{$needle}%"]);
            });
        }

        if ($min = $request->integer('min_price')) {
            $q->where('price', '>=', $min);
        }

        if ($max = $request->integer('max_price')) {
            $q->where('price', '<=', $max);
        }

        if ($roomsMin = $request->integer('rooms_min')) {
            $q->where('rooms', '>=', $roomsMin);
        }

        if ($roomsMax = $request->integer('rooms_max')) {
            $q->where('rooms', '<=', $roomsMax);
        }

        if ($areaMin = $request->integer('area_min')) {
            $q->where('area', '>=', $areaMin);
        }

        if ($areaMax = $request->integer('area_max')) {
            $q->where('area', '<=', $areaMax);
        }

        match ($request->string('sort')->value()) {
            'price_asc'  => $q->orderBy('price', 'asc'),
            'price_desc' => $q->orderBy('price', 'desc'),
            'area_desc'  => $q->orderBy('area', 'desc'),
            default      => $q->latest(),
        };

        $properties = $q->paginate(12)->withQueryString();

        // 🔽 BANERI: bottom1, bottom2, bottom3
        $bottomBanners = Banner::query()
            ->whereIn('key', ['bottom1', 'bottom2', 'bottom3'])
            ->where('active', true)
            ->get()
            ->keyBy('key');

        return view('properties.index', compact('properties', 'bottomBanners'));
    }

    public function show(string $slug)
    {
        $property = Property::query()
            ->with('category')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('properties.show', compact('property'));
    }
}
