@extends('layouts.app')

@section('title', __('Home'))

@section('content')

{{-- HERO sa animiranim blobovima & glass formom --}}
<section class="relative container mx-auto mt-6 rounded-3xl overflow-hidden">
  {{-- background slika + gradijent overlay --}}
  <div class="relative h-[58vh] min-h-[440px] w-full rounded-3xl bg-cover bg-center"
       style="background-image:url('https://images.unsplash.com/photo-1501183638710-841dd1904471?q=80&w=1800&auto=format&fit=crop');">
    <div class="absolute inset-0 bg-gradient-to-tr from-black/70 via-black/40 to-transparent"></div>

    {{-- plivajući “blobovi” za premium feel --}}
    <div class="pointer-events-none">
      <div class="absolute -top-16 -left-10 w-72 h-72 rounded-full bg-emerald-400/30 blur-3xl animate-[blob_14s_ease-in-out_infinite]"></div>
      <div class="absolute -bottom-20 -right-10 w-80 h-80 rounded-full bg-cyan-400/30 blur-3xl animate-[blob_16s_ease-in-out_infinite]"></div>
      <div class="absolute top-1/3 -right-20 w-64 h-64 rounded-full bg-indigo-400/20 blur-3xl animate-[blob_18s_ease-in-out_infinite]"></div>
    </div>

    {{-- sadržaj hero-a --}}
    <div class="absolute inset-0 px-6 md:px-10 lg:px-16 flex items-center">
      <div class="max-w-4xl text-white">
        <span class="inline-flex items-center gap-2 text-xs tracking-widest uppercase bg-white/10 rounded-full px-3 py-1 border border-white/20 reveal">
          <span class="size-1.5 bg-emerald-400 rounded-full"></span>
          {{ __('Handpicked listings') }}
        </span>

        <h1 class="mt-4 text-4xl md:text-6xl font-bold leading-[1.1] reveal">
          {{ __('Find your next home with ease') }}
        </h1>
        <p class="mt-3 md:mt-4 text-white/90 md:text-lg max-w-2xl reveal reveal-delay-1">
          {{ __('Browse curated rentals and sales across top neighborhoods.') }}
        </p>

        {{-- Glass quick search --}}
        <form class="glass mt-6 grid grid-cols-1 md:grid-cols-[1.2fr_0.6fr_0.6fr_auto] gap-3 p-4 rounded-2xl reveal reveal-delay-2"
              action="{{ route('properties.index') }}" method="GET" role="search" aria-label="{{ __('Property quick search') }}">
          <input name="q" class="px-4 py-3 rounded-xl border border-white/30 bg-white/70 placeholder:text-gray-500 text-gray-900"
                 placeholder="{{ __('City, address or keyword') }}" value="{{ request('q') }}">
          <select name="type" class="px-4 py-3 rounded-xl border border-white/30 bg-white/70 text-gray-900">
            <option value="">{{ __('Any') }}</option>
            <option value="rent" @selected(request('type')==='rent')>{{ __('Rent') }}</option>
            <option value="sale" @selected(request('type')==='sale')>{{ __('Sale') }}</option>
          </select>
          <input name="min_price" type="number" min="0" step="500"
                 class="px-4 py-3 rounded-xl border border-white/30 bg-white/70 placeholder:text-gray-500 text-gray-900"
                 placeholder="{{ __('Min price (€)') }}" value="{{ request('min_price') }}">

          <button class="relative inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-medium text-black bg-white hover:bg-white/90 transition">
            <span class="absolute inset-0 overflow-hidden rounded-xl pointer-events-none">
              <span class="absolute inset-y-0 -left-1 w-1/3 bg-white/50 animate-[shine_1.2s_ease-in-out_infinite]"></span>
            </span>
            🔎 {{ __('Search') }}
          </button>
        </form>

        <div class="mt-3 text-sm text-white/80 reveal reveal-delay-3">
          {{ __('Tip: You can filter later by rooms, area and more.') }}
        </div>
      </div>
    </div>
  </div>
</section>

{{-- === LAYOUT: MAIN + RIGHT BANNERS === --}}
<section class="container mx-auto mt-10 grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_220px] gap-6">

  {{-- MAIN CONTENT (uvek prvi na mobilu) --}}
  <main class="order-1 min-w-0">

    {{-- PREČICE: Rent / Sale (futurističke kartice) --}}
    <section class="mt-0">
      <div class="grid md:grid-cols-2 gap-6">
        {{-- RENT kartica --}}
        <a href="{{ route('properties.index',['type'=>'rent']) }}"
           class="card group relative rounded-2xl overflow-hidden border bg-gradient-to-br from-white to-gray-50">
          <div class="absolute inset-0 bg-[radial-gradient(600px_200px_at_100%_-10%,theme(colors.emerald.100),transparent)]"></div>
          <img
            src="{{ $rentCover ?? 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=1600&auto=format&fit=crop' }}"
            alt="Rent"
            class="h-52 w-full object-cover opacity-95 group-hover:opacity-100 transition">
          <div class="p-6 flex items-center justify-between relative">
            <div>
              <div class="text-xl font-semibold">{{ __('Rent') }}</div>
              <div class="text-gray-600 text-sm">{{ ($rentCount ?? 0) }} {{ __('Available') }}</div>
            </div>
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 shadow-inner">
              🏠
            </span>
          </div>
        </a>

        {{-- SALE kartica --}}
        <a href="{{ route('properties.index',['type'=>'sale']) }}"
           class="card group relative rounded-2xl overflow-hidden border bg-gradient-to-br from-white to-gray-50">
          <div class="absolute inset-0 bg-[radial-gradient(600px_200px_at_0%_-10%,theme(colors.indigo.100),transparent)]"></div>
          <img
            src="{{ $saleCover ?? 'https://images.unsplash.com/photo-1560185008-b033106af2de?q=80&w=1600&auto=format&fit=crop' }}"
            alt="Sale"
            class="h-52 w-full object-cover opacity-95 group-hover:opacity-100 transition">
          <div class="p-6 flex items-center justify-between relative">
            <div>
              <div class="text-xl font-semibold">{{ __('Sale') }}</div>
              <div class="text-gray-600 text-sm">{{ ($saleCount ?? 0) }} {{ __('Available') }}</div>
            </div>
            <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-700 shadow-inner">
              🏢
            </span>
          </div>
        </a>
      </div>
    </section>

    {{-- ISTAKNUTO / NAJNOVIJE --}}
    <section class="mt-12">
      <div class="flex items-baseline justify-between mb-4">
        <h2 class="text-2xl font-semibold">{{ __('Latest properties') }}</h2>
        <a href="{{ route('properties.index') }}" class="text-sm underline">{{ __('View all') }}</a>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse(($latest ?? []) as $idx => $p)
          <a href="{{ route('properties.show',$p->slug) }}"
             class="group card relative border rounded-2xl overflow-hidden bg-white">
            @php $img = $p->images[0] ?? null; @endphp
            <div class="relative">
              <img
                src="{{ $img ? asset('storage/'.$img) : 'https://placehold.co/1200x800' }}"
                alt="{{ $p->title }}"
                class="h-56 w-full object-cover group-hover:scale-[1.03] transition duration-500">
              <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
              @if($p->category?->type)
                <span class="absolute top-3 left-3 text-xs px-2 py-1 rounded-full backdrop-blur bg-white/80 border">
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
        @empty
          <div class="col-span-3 text-gray-500">{{ __('No properties yet.') }}</div>
        @endforelse
      </div>
    </section>

    {{-- WHY US --}}
    <section class="mt-14">
      <div class="grid md:grid-cols-3 gap-6">
        <div class="glass p-6 rounded-2xl">
          <div class="text-2xl mb-2">✨</div>
          <h3 class="font-semibold">{{ __('Handpicked listings') }}</h3>
          <p class="text-sm text-gray-700 mt-1">{{ __('Every property is verified for quality, location and value.') }}</p>
        </div>
        <div class="glass p-6 rounded-2xl">
          <div class="text-2xl mb-2">⚡</div>
          <h3 class="font-semibold">{{ __('Fast & simple') }}</h3>
          <p class="text-sm text-gray-700 mt-1">{{ __('Clean filters, quick contact and instant previews.') }}</p>
        </div>
        <div class="glass p-6 rounded-2xl">
          <div class="text-2xl mb-2">🔒</div>
          <h3 class="font-semibold">{{ __('Secure by default') }}</h3>
          <p class="text-sm text-gray-700 mt-1">{{ __('HTTPS, spam protection and privacy-first approach.') }}</p>
        </div>
      </div>
    </section>

    {{-- CTA --}}
    <section class="my-16">
      <div class="rounded-3xl p-8 md:p-12 text-center text-white bg-[radial-gradient(1200px_400px_at_50%_-50%,theme(colors.gray.800),theme(colors.gray.500))]">
        <h2 class="text-2xl md:text-3xl font-semibold">{{ __('Ready to list your property?') }}</h2>
        <p class="mt-2 text-white/85">{{ __('Manage listings from a simple admin panel.') }}</p>
        <a href="/admin" class="inline-flex mt-5 px-6 py-3 rounded-xl bg-white text-black font-medium hover:bg-white/90 transition">
          {{ __('Open Admin') }}
        </a>
      </div>
    </section>

  </main>

  {{-- RIGHT BANNERS (na mobilu ide ispod maina) --}}
  <aside role="region" aria-label="Right banners"
         class="order-2 lg:sticky lg:top-24 space-y-6">

    {{-- Primeri bannera (zameniti svojim slotovima/komponentama ili dinamičkim izvorom) --}}
    <a href="#promo-right-1" class="block rounded-2xl border bg-white overflow-hidden">
      <img src="https://placehold.co/400x600?text=Banner+200x600"
           alt="Right Promo 1" class="w-full h-auto">
    </a>

    <a href="#promo-right-2" class="block rounded-2xl border bg-white overflow-hidden">
      <img src="https://placehold.co/400x300?text=Banner+200x300"
           alt="Right Promo 2" class="w-full h-auto">
    </a>
    <a href="#promo-right-2" class="block rounded-2xl border bg-white overflow-hidden">
      <img src="https://placehold.co/400x300?text=Banner+200x300"
           alt="Right Promo 2" class="w-full h-auto">
    </a>
    <a href="#promo-right-2" class="block rounded-2xl border bg-white overflow-hidden">
      <img src="https://placehold.co/400x300?text=Banner+200x300"
           alt="Right Promo 2" class="w-full h-auto">
    </a>
    <a href="#promo-right-2" class="block rounded-2xl border bg-white overflow-hidden">
      <img src="https://placehold.co/400x300?text=Banner+200x300"
           alt="Right Promo 2" class="w-full h-auto">
    </a>
    <a href="#promo-right-2" class="block rounded-2xl border bg-white overflow-hidden">
      <img src="https://placehold.co/400x230?text=Banner+200x300"
           alt="Right Promo 2" class="w-full h-auto">
    </a>
    <a href="#promo-right-1" class="block rounded-2xl border bg-white overflow-hidden">
      <img src="https://placehold.co/400x600?text=Banner+200x600"
           alt="Right Promo 1" class="w-full h-auto">
    </a>
    {{-- po želji još bannera... --}}

  </aside>
</section>

@endsection
