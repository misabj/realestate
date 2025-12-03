@extends('layouts.app')

@section('title', __('Properties'))

@section('content')

{{-- ====================== HERO BANNER ====================== --}}
<section class="relative w-full">
  <div class="min-h-[380px] h-[54vh] w-full bg-cover bg-center"
       style="background-image:url('{{ asset('storage/properties/baner.jpg') }}');">
    <div class="h-full w-full bg-black/30 flex items-center justify-center px-6">
      <h1 class="text-white text-4xl md:text-5xl font-semibold text-center"
          style="text-shadow:0 4px 12px rgba(0,0,0,.7), 0 0 2px rgba(0,0,0,.7)">
        {{ __('Discover Luxury Apartments in Montenegro') }}
      </h1>
    </div>
  </div>
</section>
{{-- ========================================================= --}}

{{-- ================== PANEL: FILTERI + LISTA ================= --}}
<section class="container mx-auto px-4">
  {{-- SENKA još blaža --}}
  <div class="mt-8 mb-10 md:mt-12 rounded-xl bg-white/90 backdrop-blur
              ring-1 ring-gray-200 p-4 md:p-6
              shadow-[0_8px_20px_rgba(0,0,0,0.07)]">
    <div class="grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-6">

      {{-- =============== SIDEBAR FILTERI (sticky) =============== --}}
      <aside class="lg:sticky lg:top-24 h-fit rounded-lg ring-1 ring-gray-200 bg-white
                     shadow-[0_6px_14px_rgba(0,0,0,0.06)]">
        <form method="GET" action="{{ route('properties.index') }}" class="p-5 space-y-4" role="search">
          <h2 class="text-lg font-semibold">{{ __('Filters') }}</h2>

          {{-- Tabs Rent/Sale (auto-submit) --}}
          <div class="grid grid-cols-2 rounded-lg overflow-hidden ring-1 ring-gray-200 bg-white">
            <label class="cursor-pointer">
              <input type="radio" class="hidden" name="type" value="rent"
                     @checked(request('type')==='rent') onchange="this.form.submit()">
              <div class="px-3 py-2 text-center {{ request('type')==='rent' ? 'bg-[#264864] text-white' : 'bg-white hover:bg-gray-50 text-gray-700' }}">
                {{ __('Rent') }}
              </div>
            </label>
            <label class="cursor-pointer">
              <input type="radio" class="hidden" name="type" value="sale"
                     @checked(request('type')==='sale') onchange="this.form.submit()">
              <div class="px-3 py-2 text-center {{ request('type')==='sale' ? 'bg-[#264864] text-white' : 'bg-white hover:bg-gray-50 text-gray-700' }}">
                {{ __('Sale') }}
              </div>
            </label>
          </div>

          {{-- Lokacija / Q --}}
          <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('Location (city or address)') }}</label>
            <input name="q" value="{{ request('q') }}" placeholder="{{ __('Budva, Tivat...') }}"
                   class="w-full px-3 py-2 rounded-lg border border-gray-300"/>
          </div>

          {{-- Cena --}}
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm text-gray-600 mb-1">{{ __('Min price (€)') }}</label>
              <input type="number" name="min_price" value="{{ request('min_price') }}"
                     class="w-full px-3 py-2 rounded-lg border border-gray-300" min="0" step="100"/>
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1">{{ __('Max price (€)') }}</label>
              <input type="number" name="max_price" value="{{ request('max_price') }}"
                     class="w-full px-3 py-2 rounded-lg border border-gray-300" min="0" step="100"/>
            </div>
          </div>

          {{-- Sobe --}}
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm text-gray-600 mb-1">{{ __('Min rooms') }}</label>
              <input type="number" name="rooms_min" value="{{ request('rooms_min') }}"
                     class="w-full px-3 py-2 rounded-lg border border-gray-300" min="0"/>
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1">{{ __('Max rooms') }}</label>
              <input type="number" name="rooms_max" value="{{ request('rooms_max') }}"
                     class="w-full px-3 py-2 rounded-lg border border-gray-300" min="0"/>
            </div>
          </div>

          {{-- Kvadratura --}}
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm text-gray-600 mb-1">{{ __('Min area (m²)') }}</label>
              <input type="number" name="area_min" value="{{ request('area_min') }}"
                     class="w-full px-3 py-2 rounded-lg border border-gray-300" min="0" step="5"/>
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1">{{ __('Max area (m²)') }}</label>
              <input type="number" name="area_max" value="{{ request('area_max') }}"
                     class="w-full px-3 py-2 rounded-lg border border-gray-300" min="0" step="5"/>
            </div>
          </div>

          {{-- Sort --}}
          <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('Sort by') }}</label>
            <select name="sort" class="w-full px-3 py-2 rounded-lg border border-gray-300 bg-white" onchange="this.form.submit()">
              <option value="newest" @selected(request('sort')==='newest' || !request()->has('sort'))>{{ __('Newest') }}</option>
              <option value="price_asc" @selected(request('sort')==='price_asc')>{{ __('Price ↑') }}</option>
              <option value="price_desc" @selected(request('sort')==='price_desc')>{{ __('Price ↓') }}</option>
              {{-- <option value="area_desc" @selected(request('sort')==='area_desc')>{{ __('Area ↓') }}</option> --}}
            </select>
          </div>

          {{-- Akcije (Apply & Clear) --}}
          <div class="flex gap-2 pt-2">
            <button class="flex-1 rounded-lg py-2 font-semibold text-white bg-[#264864] hover:brightness-105">{{ __('Apply') }}</button>
            <a href="{{ route('properties.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">{{ __('Clear') }}</a>
          </div>
        </form>
      </aside>
      {{-- ========================================================== --}}

      {{-- ====================== LISTA / GRID ====================== --}}
      <section>
        {{-- Aktivni filteri (pilule) --}}
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
                 class="inline-flex items-center gap-2 text-sm px-3 py-1.5 rounded-full border border-gray-200 bg-white hover:bg-gray-50">
                <span class="text-gray-600">{{ $map[$key]['label'] }}:</span>
                <span class="font-medium">{{ e($val) }}</span>
                <span class="text-gray-400">✕</span>
              </a>
            @endforeach
            <a href="{{ route('properties.index') }}" class="text-sm underline ml-2">{{ __('Clear all') }}</a>
          </div>
        @endif

        {{-- Grid 2 kolone --}}
        @if($properties->count())
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($properties as $p)
              <a href="{{ route('properties.show', $p->slug) }}"
                 class="group relative rounded-lg overflow-hidden bg-white ring-1 ring-gray-200 transition
                        shadow-[0_6px_14px_rgba(0,0,0,0.06)]
                        hover:shadow-[0_8px_18px_rgba(0,0,0,0.08)]">
                @php $img = $p->images[0] ?? null; @endphp
                <div class="relative">
                  <img src="{{ $img ? asset('storage/'.$img) : 'https://placehold.co/1200x800' }}"
                       alt="{{ $p->title }}"
                       class="h-56 w-full object-cover group-hover:scale-[1.02] transition duration-500">
                  @if($p->category?->type)
                    <span class="absolute top-3 left-3 text-xs px-2 py-1 rounded-full backdrop-blur bg-white/85 border border-gray-200">
                      {{ __($p->category->type === 'rent' ? 'Rent' : 'Sale') }}
                    </span>
                  @endif
                </div>
                <div class="p-4">
                  <div class="font-semibold line-clamp-1">{{ $p->title_localized ?? $p->title }}</div>
                  <div class="text-sm text-gray-600 line-clamp-1">
                    {{ $p->city_localized ?? $p->city }} · {{ $p->area }} m² · €{{ number_format($p->price, 0, ',', '.') }}
                  </div>
                  <div class="mt-3 flex gap-2 text-xs">
                    @if(!is_null($p->rooms))
                      <span class="px-2 py-1 rounded-full bg-gray-100 ring-1 ring-gray-200">{{ $p->rooms }} {{ __('Rooms') }}</span>
                    @endif
                    @if(!is_null($p->floor))
                      <span class="px-2 py-1 rounded-full bg-gray-100 ring-1 ring-gray-200">{{ $p->floor }} {{ __('Floor') }}</span>
                    @endif
                  </div>
                </div>
              </a>
            @endforeach
          </div>

         {{-- ==================== BANNERS BOTTOM ==================== --}}
          @php
              /** @var \Illuminate\Support\Collection|\App\Models\Banner[]|null $bottomBanners */

              // ako iz nekog razloga nije prosleđeno iz kontrolera, da ne puca
              $bottomBanners = $bottomBanners ?? collect();

              $banner1 = $bottomBanners->get('bottom1');
              $banner2 = $bottomBanners->get('bottom2');
              $banner3 = $bottomBanners->get('bottom3');

              // IMAGE URL-ovi (iz model accessor-a image_url) + fallback na Unsplash
              $b1 = $banner1?->image
                  ? asset('storage/' . $banner1->image)
                  : 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?q=80&w=1600&auto=format&fit=crop';

              $b2 = $banner2?->image
                  ? asset('storage/' . $banner2->image)
                  : 'https://images.unsplash.com/photo-1494526585095-c41746248156?q=80&w=1600&auto=format&fit=crop';

              $b3 = $banner3?->image
                  ? asset('storage/' . $banner3->image)
                  : 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?q=80&w=1600&auto=format&fit=crop';

              // LINKOVI – ako u bazi imaš "link", koristi njega, inače default kao do sad
              $b1Link = $banner1?->link ?: route('properties.index', ['type' => 'sale']);
              $b2Link = $banner2?->link ?: route('properties.index', ['type' => 'rent']);
              $b3Link = $banner3?->link ?: route('properties.index');

              // TITLOVI – ako si u Filamentu postavio title, koristi njega, inače default tekst
              $b1Title = $banner1?->title ?? __('Featured: Porto Montenegro Exclusive Offers');
              $b2Title = $banner2?->title ?? __('New Listings: Seafront Apartments');
              $b3Title = $banner3?->title ?? __('Subscribe for Luxury Offers & Updates');
          @endphp

          <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Banner 1 --}}
            <a href="{{ $b1Link }}"
              class="block relative rounded-lg overflow-hidden group ring-1 ring-gray-200 bg-center bg-cover
                      aspect-[16/9] md:aspect-[3/1]
                      shadow-[0_8px_20px_rgba(0,0,0,0.07)]
                      hover:shadow-[0_10px_24px_rgba(0,0,0,0.09)] transition">
              <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition"></div>
              <div class="absolute bottom-4 left-4 right-4 p-2 text-white text-xl font-semibold drop-shadow-md"
                  style="text-shadow:0 2px 8px rgba(0,0,0,.7), 0 0 2px rgba(0,0,0,.5)">
                {{ $b1Title }}
              </div>
              <span aria-hidden="true"
                    class="absolute inset-0 bg-center bg-cover"
                    style="background-image:url('{{ $b1 }}')"></span>
            </a>

            {{-- Banner 2 --}}
            <a href="{{ $b2Link }}"
              class="block relative rounded-lg overflow-hidden group ring-1 ring-gray-200 bg-center bg-cover
                      aspect-[16/9] md:aspect-[3/1]
                      shadow-[0_8px_20px_rgba(0,0,0,0.07)]
                      hover:shadow-[0_10px_24px_rgba(0,0,0,0.09)] transition">
              <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition"></div>
              <div class="absolute bottom-4 left-4 right-4 p-2 text-white text-xl font-semibold drop-shadow-md"
                  style="text-shadow:0 2px 8px rgba(0,0,0,.7), 0 0 2px rgba(0,0,0,.5)">
                {{ $b2Title }}
              </div>
              <span aria-hidden="true"
                    class="absolute inset-0 bg-center bg-cover"
                    style="background-image:url('{{ $b2 }}')"></span>
            </a>

            {{-- Banner 3 --}}
            <a href="{{ $b3Link }}"
              class="block relative rounded-lg overflow-hidden group ring-1 ring-gray-200 bg-center bg-cover
                      aspect-[16/9] md:aspect-[3/1]
                      shadow-[0_8px_20px_rgba(0,0,0,0.07)]
                      hover:shadow-[0_10px_24px_rgba(0,0,0,0.09)] transition">
              <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition"></div>
              <div class="absolute bottom-4 left-4 right-4 p-2 text-white text-xl font-semibold drop-shadow-md"
                  style="text-shadow:0 2px 8px rgba(0,0,0,.7), 0 0 2px rgba(0,0,0,.5)">
                {{ $b3Title }}
              </div>
              <span aria-hidden="true"
                    class="absolute inset-0 bg-center bg-cover"
                    style="background-image:url('{{ $b3 }}')"></span>
            </a>
          </div>

          {{-- Paginacija --}}
          <div class="mt-8">
            {{ $properties->appends(request()->query())->onEachSide(1)->links() }}
          </div>
        @else
          <div class="rounded-lg ring-1 ring-gray-200 p-8 bg-white text-center
                      shadow-[0_6px_14px_rgba(0,0,0,0.06)]">
            <div class="text-3xl">🔎</div>
            <h3 class="mt-2 text-lg font-semibold">{{ __('No results match your filters') }}</h3>
            <p class="text-gray-600">{{ __('Try widening your search or clearing filters.') }}</p>
            <a href="{{ route('properties.index') }}"
               class="inline-block mt-4 px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
              {{ __('Clear all filters') }}
            </a>
          </div>
        @endif
        {{-- ========================================================= --}}
      </section>

      {{-- =================== /LISTA / GRID ======================= --}}
    </div>
  </div>
</section>
{{-- ================= /PANEL: FILTERI + LISTA ================== --}}

@endsection
