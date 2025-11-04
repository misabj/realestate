@extends('layouts.app')

@section('title', $property->title)

@section('content')
@php
  $images = $property->images ?? [];
  $cover  = $images[0] ?? null;

  // puni URL-ovi (storage)
  $imageUrls = collect($images)->map(fn($p) => asset('storage/'.$p))->values()->all();

  // pomoćne vrednosti
  $beds   = $property->rooms;          // ako imaš zasebno, zameni
  $baths  = $property->bathrooms ?? null;
  $view   = $property->sea_view  ? __('Sea View')
         : ($property->mountain_view ? __('Mountain View') : null);
@endphp

{{-- ===== BACK dugme ===== --}}
<div class="container mx-auto px-4">
  <a href="{{ url()->previous() ?: route('properties.index') }}"
     class="inline-flex items-center gap-2 mt-4 mb-2 px-3 py-1.5 rounded-md ring-1 ring-gray-300 text-gray-700 bg-white hover:bg-gray-50">
    ← {{ __('Back to results') }}
  </a>
</div>

{{-- ============ TOP: FULL-WIDTH SLIDER ============ --}}
<section class="relative w-full overflow-hidden bg-gray-100">
  <div id="heroSlider"
       class="relative h-[48vh] md:h-[56vh] lg:h-[62vh] select-none">
    {{-- Slides --}}
    <div id="slidesTrack" class="absolute inset-0 flex transition-transform duration-500 ease-out">
      @forelse($imageUrls as $url)
        <div class="shrink-0 w-full h-full relative">
          <img src="{{ $url }}"
               alt="{{ $property->title }}"
               class="w-full h-full object-cover"
               data-gallery-open="0">
          <div class="absolute inset-0 pointer-events-none bg-gradient-to-b from-black/10 via-transparent to-black/10"></div>
        </div>
      @empty
        <div class="shrink-0 w-full h-full grid place-items-center text-gray-400">
          {{ __('No images') }}
        </div>
      @endforelse
    </div>

    {{-- Prev / Next --}}
    @if(count($imageUrls) > 1)
      <button id="btnPrev"
              class="absolute left-3 top-1/2 -translate-y-1/2 grid place-items-center w-10 h-10 rounded-full bg-white/80 hover:bg-white shadow">
        ‹
      </button>
      <button id="btnNext"
              class="absolute right-3 top-1/2 -translate-y-1/2 grid place-items-center w-10 h-10 rounded-full bg-white/80 hover:bg-white shadow">
        ›
      </button>
      {{-- dots --}}
      <div id="dots" class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-2">
        @foreach($imageUrls as $i => $u)
          <button class="size-2.5 rounded-full bg-white/70 ring-1 ring-black/10 data-[active=true]:bg-white"
                  data-idx="{{ $i }}"></button>
        @endforeach
      </div>
    @endif
  </div>
</section>

{{-- ============ SUMMARY CARD ============ --}}
<section class="container mx-auto px-4">
  <div class="mx-auto mb-10 max-w-6xl mt-6 md:mt-10 relative">
    <article class="rounded-xl bg-white shadow-xl ring-1 ring-gray-200 p-6 md:p-8">
      {{-- Title + location --}}
      <header>
        <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
          {{ $property->title_localized ?? $property->title }}
        </h1>
        <p class="mt-1 text-gray-600">
          {{ $property->address ?: $property->city }}
          @if($property->city) · {{ $property->city }} @endif
          @if($property->country) · {{ $property->country }} @endif
        </p>
      </header>

      {{-- Pills row --}}
      @php
        $beds  = $property->rooms;
        $baths = $property->bathrooms ?? null;
        $view  = $property->sea_view ? __('Sea View') : ($property->mountain_view ? __('Mountain View') : null);
      @endphp

      <div class="mt-4 flex flex-wrap gap-2">
        @if(!is_null($beds))
          <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-200 bg-gray-50 text-sm">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 7h18M6 7v10M18 7v10M3 17h18" stroke-width="1.5"/></svg>
            {{ $beds }} {{ __('Bedrooms') }}
          </span>
        @endif
        @if(!is_null($baths))
          <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-200 bg-gray-50 text-sm">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 10h16M7 10v8m10-8v8M5 18h14" stroke-width="1.5"/></svg>
            {{ $baths }} {{ __('Bathrooms') }}
          </span>
        @endif
        @if($view)
          <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-200 bg-gray-50 text-sm">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7Z" stroke-width="1.5"/><circle cx="12" cy="12" r="3" /></svg>
            {{ $view }}
          </span>
        @endif
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-200 bg-gray-50 text-sm">
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 1v22M7 5h6.5a3.5 3.5 0 1 1 0 7H9a3.5 3.5 0 0 0 0 7H17" stroke-width="1.5"/></svg>
          €{{ number_format($property->price, 0, ',', '.') }}
        </span>
      </div>

      {{-- 2-kolonski raspored: opis + kontakt | ključne stavke --}}
      <div class="mt-6 grid gap-6 md:grid-cols-3">
        {{-- Left: description + CTA --}}
        <div class="md:col-span-2">
          @if(filled($property->description_localized ?? $property->description))
            <p class="text-gray-800 leading-relaxed">
              {!! nl2br(e($property->description_localized ?? $property->description)) !!}
            </p>
          @endif

          <div class="mt-5">
            <a href="mailto:{{ config('mail.from.address') ?? 'info@example.com' }}?subject={{ rawurlencode($property->title) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-white bg-[#264864] hover:brightness-105 transition">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 4h16v16H4z"/><path d="m22 6-10 7L2 6" /></svg>
              {{ __('Contact') }}
            </a>
          </div>
        </div>

        {{-- Right: key facts --}}
        <aside class="rounded-lg ring-1 ring-gray-200 bg-gray-50 p-4">
          <h3 class="font-semibold mb-3">{{ __('Key details') }}</h3>
          <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
            <dt class="text-gray-600">{{ __('Type') }}</dt>
            <dd class="font-medium">{{ __($property->category?->type === 'rent' ? 'Rent' : 'Sale') }}</dd>

            <dt class="text-gray-600">{{ __('Area') }}</dt>
            <dd class="font-medium">{{ $property->area }} m²</dd>

            <dt class="text-gray-600">{{ __('Rooms') }}</dt>
            <dd class="font-medium">{{ $property->rooms ?? '—' }}</dd>

            <dt class="text-gray-600">{{ __('Floor') }}</dt>
            <dd class="font-medium">{{ $property->floor ?? '—' }}</dd>

            <dt class="text-gray-600">{{ __('City') }}</dt>
            <dd class="font-medium">{{ $property->city ?? '—' }}</dd>

            <dt class="text-gray-600">{{ __('Address') }}</dt>
            <dd class="font-medium truncate" title="{{ $property->address }}">{{ $property->address ?? '—' }}</dd>
          </dl>
        </aside>
      </div>
    </article>
  </div>
</section>

{{-- ============ MAP CARD ============ --}}
@if($property->lat && $property->lng)
  <section class="container mx-auto px-4 mt-6">
    <div class="mx-auto mb-10 max-w-6xl rounded-xl overflow-hidden bg-white shadow-xl ring-1 ring-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="font-semibold">{{ __('Location') }}</h3>
        <p class="text-sm text-gray-600">
          {{ $property->address ?: $property->city }}
          @if($property->city) · {{ $property->city }} @endif
        </p>
      </div>
      <iframe
        src="https://maps.google.com/maps?q={{ $property->lat }},{{ $property->lng }}&z=15&output=embed"
        class="w-full h-[380px] md:h-[460px]"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        aria-label="{{ __('Map') }}"></iframe>
    </div>
  </section>
@endif


{{-- ============ DATA za lightbox ============ --}}
<script type="application/json" id="property-images-json">
  {!! json_encode($imageUrls, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

{{-- ============ LIGHTBOX ============ --}}
<div id="galleryModal" class="fixed inset-0 z-50 hidden items-center justify-center">
  <div class="absolute inset-0 bg-black/70" data-gallery-close></div>
  <div class="relative w-[min(95vw,1200px)] h-[82vh] md:h-[86vh] px-4">
    <img id="galleryImage" src="" alt="gallery"
         class="w-full h-full object-contain rounded-lg shadow-lg select-none" />
    <button id="galleryPrev"
            class="absolute left-2 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white/80 hover:bg-white shadow">‹</button>
    <button id="galleryNext"
            class="absolute right-2 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white/80 hover:bg-white shadow">›</button>
    <button id="galleryClose"
            class="absolute -right-1 -top-1 p-2 rounded-full bg-white/90 hover:bg-white shadow">✕</button>
  </div>
</div>

{{-- ============ JS: slider + lightbox ============ --}}
<script>
(function () {
  const dataEl = document.getElementById('property-images-json');
  const IMAGES = dataEl ? JSON.parse(dataEl.textContent) : [];
  if (!IMAGES.length) return;

  // ---------- HERO SLIDER ----------
  const track   = document.getElementById('slidesTrack');
  const dotsBox = document.getElementById('dots');
  const btnPrev = document.getElementById('btnPrev');
  const btnNext = document.getElementById('btnNext');

  Array.from(track.children).forEach((slide, i) => {
    const img = slide.querySelector('img');
    if (img) img.setAttribute('data-gallery-open', i);
  });

  let idx = 0;
  const slideCount = track.children.length;

  function renderSlider() {
    track.style.transform = `translateX(${-idx * 100}%)`;
    if (!dotsBox) return;
    dotsBox.querySelectorAll('button').forEach((b, i) => {
      b.dataset.active = (i === idx);
    });
  }
  function next() { idx = (idx + 1) % slideCount; renderSlider(); }
  function prev() { idx = (idx - 1 + slideCount) % slideCount; renderSlider(); }

  if (btnNext) btnNext.addEventListener('click', next);
  if (btnPrev) btnPrev.addEventListener('click', prev);

  if (dotsBox) {
    dotsBox.querySelectorAll('button').forEach((b) => {
      b.addEventListener('click', () => { idx = +b.dataset.idx; renderSlider(); });
    });
  }

  // swipe support
  let startX = null;
  track.addEventListener('touchstart', (e) => { startX = e.touches[0].clientX; }, {passive:true});
  track.addEventListener('touchend', (e) => {
    if (startX == null) return;
    const dx = e.changedTouches[0].clientX - startX;
    if (Math.abs(dx) > 40) (dx < 0 ? next() : prev());
    startX = null;
  });

  renderSlider();

  // ---------- LIGHTBOX ----------
  const modal = document.getElementById('galleryModal');
  const imgEl = document.getElementById('galleryImage');
  const lbPrev = document.getElementById('galleryPrev');
  const lbNext = document.getElementById('galleryNext');
  const lbClose= document.getElementById('galleryClose');
  const lbBackdrop = modal.querySelector('[data-gallery-close]');

  let lbIndex = 0;
  function lbShow(i){ lbIndex=(i+IMAGES.length)%IMAGES.length; imgEl.src=IMAGES[lbIndex]; }
  function lbOpen(i){ lbShow(i); modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow='hidden'; }
  function lbCloseFn(){ modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow=''; }
  function lbNextFn(){ lbShow(lbIndex+1); }
  function lbPrevFn(){ lbShow(lbIndex-1); }

  document.querySelectorAll('[data-gallery-open]').forEach(el=>{
    el.addEventListener('click', () => {
      const i = parseInt(el.getAttribute('data-gallery-open'), 10) || 0;
      lbOpen(i);
    });
  });

  lbNext.addEventListener('click', lbNextFn);
  lbPrev.addEventListener('click', lbPrevFn);
  lbClose.addEventListener('click', lbCloseFn);
  lbBackdrop.addEventListener('click', lbCloseFn);

  document.addEventListener('keydown', (e) => {
    if (modal.classList.contains('hidden')) return;
    if (e.key === 'Escape') lbCloseFn();
    if (e.key === 'ArrowRight') lbNextFn();
    if (e.key === 'ArrowLeft') lbPrevFn();
  });

  imgEl.addEventListener('touchstart', (e)=>{ startX=e.touches[0].clientX; }, {passive:true});
  imgEl.addEventListener('touchend', (e)=>{
    if (startX==null) return;
    const dx=e.changedTouches[0].clientX-startX;
    if (Math.abs(dx)>40) (dx<0?lbNextFn():lbPrevFn());
    startX=null;
  });
})();
</script>
@endsection
