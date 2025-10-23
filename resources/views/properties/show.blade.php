@extends('layouts.app')

@section('title', $property->title)

@section('content')
@php
  $images = $property->images ?? [];
  $cover  = $images[0] ?? null;

  // Niz punih URL-ova (storage)
  $imageUrls = collect($images)
      ->map(fn($p) => asset('storage/'.$p))
      ->values()
      ->all();
@endphp

<div class="container mx-auto mt-6">
  {{-- Header --}}
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <div class="flex items-center gap-2">
        @if($property->category?->type)
          <span class="text-xs px-2 py-1 rounded-full bg-gray-100 border">
            {{ __($property->category->type === 'rent' ? 'Rent' : 'Sale') }}
          </span>
        @endif
        @if($property->city)
          <span class="text-xs px-2 py-1 rounded-full bg-gray-100 border">
            {{ $property->city }}
          </span>
        @endif
      </div>
      <h1 class="text-2xl md:text-3xl font-bold mt-2">
        {{ $property->title_localized }}
      </h1>
      <div class="text-gray-600">{{ $property->address }}</div>
    </div>

    <div class="text-right">
      @if(!is_null($property->price))
        <div class="text-3xl font-semibold">€{{ number_format($property->price,0,',','.') }}</div>
      @endif
      <div class="text-gray-600">
        {{ $property->area }} m²
        @if(!is_null($property->rooms)) · {{ $property->rooms }} {{ __('Rooms') }} @endif
        @if(!is_null($property->floor)) · {{ $property->floor }} {{ __('Floor') }} @endif
      </div>
    </div>
  </div>

 {{-- Gallery --}}
<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
  {{-- Velika slika – klik otvara modal na indexu 0 --}}
  <div class="md:col-span-2 rounded-2xl overflow-hidden border">
    <img
      src="{{ $cover ? asset('storage/'.$cover) : 'https://placehold.co/1200x800' }}"
      alt="{{ $property->title }}"
      class="w-full h-[360px] md:h-[520px] object-cover cursor-pointer"
      data-gallery-open="0"
    >
  </div>

  {{-- Thumbnails – svaki otvara modal na svom indexu --}}
  <div class="grid grid-cols-3 md:grid-cols-1 gap-4">
    @forelse($images as $i => $img)
      @continue($i === 0)
      <img
        src="{{ asset('storage/'.$img) }}"
        alt="Image {{ $i+1 }}"
        class="w-full h-28 md:h-40 object-cover rounded-xl border cursor-pointer"
        data-gallery-open="{{ $i }}"
      >
    @empty
      <div class="col-span-3 text-gray-500 border rounded-xl p-4 text-center">
        {{ __('No additional images') }}
      </div>
    @endforelse
  </div>
</div>

  {{-- Content + Map + Contact --}}
  <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
      <section class="rounded-2xl border p-5 bg-white">
        <h2 class="text-lg font-semibold mb-3">{{ __('Description') }}</h2>
        <div class="prose max-w-none">
          {!! nl2br(e($property->description_localized)) !!}
        </div>
      </section>

      {{-- Mapa (ako ima koordinate) --}}
      {{-- https://developers.google.com/maps/documentation/embed/get-started --}}
      {{-- https://www.google.com/maps?q=45.2671,19.8335&z=15&output=embed --}}

      @if($property->lat && $property->lng)
        <section class="rounded-2xl border overflow-hidden">
          <iframe
            src="https://maps.google.com/maps?q={{ $property->lat }},{{ $property->lng }}&z=15&output=embed"
            class="w-full h-80"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            aria-label="{{ __('Map') }}"></iframe>
        </section>
      @endif
    </div>

    <aside class="space-y-6">
      <div class="rounded-2xl border p-5 bg-white">
        <h3 class="text-lg font-semibold mb-3">{{ __('Key details') }}</h3>
        <dl class="grid grid-cols-2 gap-3 text-sm">
          <dt class="text-gray-600">{{ __('Type') }}</dt>
          <dd class="font-medium">{{ __($property->category?->type === 'rent' ? 'Rent' : 'Sale') }}</dd>

          <dt class="text-gray-600">{{ __('City') }}</dt>
          <dd class="font-medium">{{ $property->city }}</dd>

          <dt class="text-gray-600">{{ __('Area') }}</dt>
          <dd class="font-medium">{{ $property->area }} m²</dd>

          <dt class="text-gray-600">{{ __('Rooms') }}</dt>
          <dd class="font-medium">{{ $property->rooms ?? '—' }}</dd>

          <dt class="text-gray-600">{{ __('Floor') }}</dt>
          <dd class="font-medium">{{ $property->floor ?? '—' }}</dd>

          <dt class="text-gray-600">{{ __('Address') }}</dt>
          <dd class="font-medium">{{ $property->address ?? '—' }}</dd>

          <dt class="text-gray-600">{{ __('Price') }}</dt>
          <dd class="font-medium">€{{ number_format($property->price,0,',','.') }}</dd>
        </dl>
      </div>

      <div class="rounded-2xl border p-5 bg-white">
        <h3 class="text-lg font-semibold mb-3">{{ __('Share') }}</h3>
        <div class="flex gap-2">
          <a class="px-3 py-1.5 rounded-lg border hover:bg-gray-50"
             href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener">Facebook</a>
          <a class="px-3 py-1.5 rounded-lg border hover:bg-gray-50"
             href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener">X</a>
        </div>
      </div>
    </aside>
  </div>
</div>
{{-- Prosleđujemo slike u JSON za JS (ceo niz, redosled kao u $images) --}}
<script type="application/json" id="property-images-json">
  {!! json_encode($imageUrls, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

{{-- Modal galerija --}}
<div id="galleryModal" class="fixed inset-0 z-50 hidden items-center justify-center">
  <div class="absolute inset-0 bg-black/70" data-gallery-close></div>

  <div class="relative w-[min(95vw,1200px)] h-[82vh] md:h-[86vh] px-4">
    <img id="galleryImage" src="" alt="gallery"
         class="w-full h-full object-contain rounded-lg shadow-lg select-none" />

    {{-- Kontrole --}}
    <button id="galleryPrev"
            class="absolute left-2 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white/80 hover:bg-white shadow">
      ‹
    </button>
    <button id="galleryNext"
            class="absolute right-2 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white/80 hover:bg-white shadow">
      ›
    </button>
    <button id="galleryClose"
            class="absolute -right-1 -top-1 p-2 rounded-full bg-white/90 hover:bg-white shadow">
      ✕
    </button>
  </div>
</div>

<script>
(function () {
  const dataEl = document.getElementById('property-images-json');
  const IMAGES = dataEl ? JSON.parse(dataEl.textContent) : [];
  if (!IMAGES.length) return;

  const modal = document.getElementById('galleryModal');
  const imgEl = document.getElementById('galleryImage');
  const btnPrev = document.getElementById('galleryPrev');
  const btnNext = document.getElementById('galleryNext');
  const btnClose = document.getElementById('galleryClose');
  const backdropClose = modal.querySelector('[data-gallery-close]');

  let index = 0;
  let touchStartX = null;

  function show(i) {
    index = (i + IMAGES.length) % IMAGES.length;
    imgEl.src = IMAGES[index];
  }

  function open(i) {
    show(i);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
  }

  function close() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
  }

  function next() { show(index + 1); }
  function prev() { show(index - 1); }

  // Otvaranje sa svih elemenata koji imaju data-gallery-open
  document.querySelectorAll('[data-gallery-open]').forEach(el => {
    el.addEventListener('click', () => {
      const i = parseInt(el.getAttribute('data-gallery-open'), 10) || 0;
      open(i);
    });
  });

  // Kontrole
  btnNext.addEventListener('click', next);
  btnPrev.addEventListener('click', prev);
  btnClose.addEventListener('click', close);
  backdropClose.addEventListener('click', close);

  // Tastatura
  document.addEventListener('keydown', (e) => {
    if (modal.classList.contains('hidden')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowRight') next();
    if (e.key === 'ArrowLeft') prev();
  });

  // Swipe (mobile)
  imgEl.addEventListener('touchstart', (e) => {
    touchStartX = e.touches[0].clientX;
  }, {passive: true});
  imgEl.addEventListener('touchend', (e) => {
    if (touchStartX === null) return;
    const dx = e.changedTouches[0].clientX - touchStartX;
    if (Math.abs(dx) > 40) { dx < 0 ? next() : prev(); }
    touchStartX = null;
  });
})();
</script>
@endsection
