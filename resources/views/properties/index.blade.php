@extends('layouts.app')

@section('title', __('Properties'))

@section('content')
<div class="container mx-auto mt-6 grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-6">

  {{-- SIDEBAR FILTERI (sticky) --}}
  <aside class="lg:sticky lg:top-6 h-fit rounded-2xl border bg-white/70 backdrop-blur">
    <form method="GET" action="{{ route('properties.index') }}" class="p-5 space-y-4">
      <h2 class="text-lg font-semibold">{{ __('Filters') }}</h2>

      {{-- Tabs Rent/Sale --}}
      <div class="grid grid-cols-2 rounded-xl overflow-hidden border">
        <label class="cursor-pointer">
          <input type="radio" class="hidden" name="type" value="rent" @checked(request('type')==='rent')>
          <div class="px-3 py-2 text-center {{ request('type')==='rent' ? 'bg-black text-white' : 'hover:bg-gray-50' }}">
            {{ __('Rent') }}
          </div>
        </label>
        <label class="cursor-pointer">
          <input type="radio" class="hidden" name="type" value="sale" @checked(request('type')==='sale')>
          <div class="px-3 py-2 text-center {{ request('type')==='sale' ? 'bg-black text-white' : 'hover:bg-gray-50' }}">
            {{ __('Sale') }}
          </div>
        </label>
      </div>

      {{-- Lokacija --}}
      <div>
        <label class="block text-sm text-gray-600 mb-1">{{ __('Location (city or address)') }}</label>
        <input name="q" value="{{ request('q') }}" placeholder="{{ __('Belgrade, Dorćol...') }}"
               class="w-full px-3 py-2 rounded-lg border">
      </div>

      {{-- Cena --}}
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm text-gray-600 mb-1">{{ __('Min price') }}</label>
          <input type="number" name="min_price" value="{{ request('min_price') }}" class="w-full px-3 py-2 rounded-lg border" min="0" step="100">
        </div>
        <div>
          <label class="block text-sm text-gray-600 mb-1">{{ __('Max price') }}</label>
          <input type="number" name="max_price" value="{{ request('max_price') }}" class="w-full px-3 py-2 rounded-lg border" min="0" step="100">
        </div>
      </div>

      {{-- Rooms --}}
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm text-gray-600 mb-1">{{ __('Min rooms') }}</label>
          <input type="number" name="rooms_min" value="{{ request('rooms_min') }}" class="w-full px-3 py-2 rounded-lg border" min="0">
        </div>
        <div>
          <label class="block text-sm text-gray-600 mb-1">{{ __('Max rooms') }}</label>
          <input type="number" name="rooms_max" value="{{ request('rooms_max') }}" class="w-full px-3 py-2 rounded-lg border" min="0">
        </div>
      </div>

      {{-- Area --}}
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm text-gray-600 mb-1">{{ __('Min area (m²)') }}</label>
          <input type="number" name="area_min" value="{{ request('area_min') }}" class="w-full px-3 py-2 rounded-lg border" min="0" step="5">
        </div>
        <div>
          <label class="block text-sm text-gray-600 mb-1">{{ __('Max area (m²)') }}</label>
          <input type="number" name="area_max" value="{{ request('area_max') }}" class="w-full px-3 py-2 rounded-lg border" min="0" step="5">
        </div>
      </div>

      {{-- Sort --}}
      <div>
        <label class="block text-sm text-gray-600 mb-1">{{ __('Sort by') }}</label>
        <select name="sort" class="w-full px-3 py-2 rounded-lg border">
          <option value="newest" @selected(request('sort')==='newest' || !request()->has('sort'))>{{ __('Newest') }}</option>
          <option value="price_asc" @selected(request('sort')==='price_asc')>{{ __('Price ↑') }}</option>
          <option value="price_desc" @selected(request('sort')==='price_desc')>{{ __('Price ↓') }}</option>
          <option value="area_desc" @selected(request('sort')==='area_desc')>{{ __('Area ↓') }}</option>
        </select>
      </div>

      {{-- Actions --}}
      <div class="flex gap-2 pt-2">
        <button class="flex-1 bg-black text-white rounded-lg py-2 hover:bg-black/90">{{ __('Apply') }}</button>
        <a href="{{ route('properties.index') }}" class="px-4 py-2 rounded-lg border">{{ __('Clear') }}</a>
      </div>
    </form>
  </aside>

  {{-- CONTENT --}}
  <section>
    {{-- Active filters pills --}}
    @php
      $map = [
        'type' => ['label' => __('Type')],
        'q' => ['label' => __('Location')],
        'min_price' => ['label' => __('Min price')],
        'max_price' => ['label' => __('Max price')],
        'rooms_min' => ['label' => __('Min rooms')],
        'rooms_max' => ['label' => __('Max rooms')],
        'area_min' => ['label' => __('Min area (m²)')],
        'area_max' => ['label' => __('Max area (m²)')],
        'sort' => ['label' => __('Sort')],
      ];
      $active = collect(request()->only(array_keys($map)))->filter(fn($v) => filled($v));
    @endphp

    @if($active->isNotEmpty())
      <div class="mb-4 flex flex-wrap items-center gap-2">
        @foreach($active as $key => $val)
          <a href="{{ request()->fullUrlWithQuery([$key => null]) }}"
             class="inline-flex items-center gap-2 text-sm px-3 py-1.5 rounded-full border bg-white hover:bg-gray-50">
            <span class="text-gray-600">{{ $map[$key]['label'] }}:</span>
            <span class="font-medium">{{ e($val) }}</span>
            <span class="text-gray-400">✕</span>
          </a>
        @endforeach
        <a href="{{ route('properties.index') }}" class="text-sm underline ml-2">{{ __('Clear all') }}</a>
      </div>
    @endif

    {{-- Grid --}}
    @if($properties->count())
      <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($properties as $p)
          <a href="{{ route('properties.show',$p->slug) }}" class="group relative border rounded-2xl overflow-hidden bg-white card">
            @php $img = $p->images[0] ?? null; @endphp
            <div class="relative">
              <img src="{{ $img ? asset('storage/'.$img) : 'https://placehold.co/1200x800' }}"
                   alt="{{ $p->title }}" class="h-56 w-full object-cover group-hover:scale-[1.02] transition duration-500">
              @if($p->category?->type)
                <span class="absolute top-3 left-3 text-xs px-2 py-1 rounded-full backdrop-blur bg-white/85 border">
                  {{ __($p->category->type === 'rent' ? 'Rent' : 'Sale') }}
                </span>
              @endif
            </div>
            <div class="p-4">
              <div class="font-semibold line-clamp-1">{{ $p->title_localized ?? $p->title }}</div>
              <div class="text-sm text-gray-600 line-clamp-1">
                {{ $p->city_localized ?? $p->city }} · {{ $p->area }} m² · €{{ number_format($p->price,0,',','.') }}
              </div>
              <div class="mt-3 flex gap-2 text-xs">
                @if(!is_null($p->rooms))
                  <span class="px-2 py-1 rounded-full bg-gray-100">{{ $p->rooms }} {{ __('Rooms') }}</span>
                @endif
                @if(!is_null($p->floor))
                  <span class="px-2 py-1 rounded-full bg-gray-100">{{ $p->floor }} {{ __('Floor') }}</span>
                @endif
              </div>
            </div>
          </a>
        @endforeach
      </div>

      <div class="mt-8">
        {{ $properties->onEachSide(1)->links() }}
      </div>
    @else
      <div class="rounded-2xl border p-8 bg-white text-center">
        <div class="text-3xl">🔎</div>
        <h3 class="mt-2 text-lg font-semibold">{{ __('No results match your filters') }}</h3>
        <p class="text-gray-600">{{ __('Try widening your search or clearing filters.') }}</p>
        <a href="{{ route('properties.index') }}" class="inline-block mt-4 px-4 py-2 rounded-lg border hover:bg-gray-50">
          {{ __('Clear all filters') }}
        </a>
      </div>
    @endif
  </section>
</div>
@endsection
