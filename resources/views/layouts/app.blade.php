<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Real Estate')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen text-gray-900 bg-[linear-gradient(180deg,#f8fafc,white)]">
    <header id="siteHeader"
        >

  {{-- MOBILE NAV --}}
  <nav class="md:hidden container mx-auto px-5 flex items-center justify-between header-inner py-3 transition-all duration-300">
      <a id="logoMobile" href="{{ route('home') }}" class="font-bold text-xl transition-all duration-300">RealEstate</a>

      <button id="menuToggle"
              class="inline-flex items-center justify-center p-2 rounded-lg border hover:bg-gray-50"
              aria-label="Open menu" aria-controls="mobileMenu" aria-expanded="false">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path d="M3 6h18M3 12h18M3 18h18" stroke-width="2" stroke-linecap="round" />
          </svg>
      </button>
  </nav>

  {{-- DESKTOP NAV --}}
  <nav class="hidden md:grid container mx-auto px-5 header-inner py-4 transition-all duration-300 md:grid-cols-3 md:items-center">
      <div class="flex items-center">
          <a id="logoDesktop" href="{{ route('home') }}" class="font-bold text-xl transition-all duration-300">RealEstate</a>
      </div>

      <div class="flex items-center justify-center gap-6">
          <a href="{{ route('properties.index', ['type' => 'rent']) }}">{{ __('Rent') }}</a>
          <a href="{{ route('properties.index', ['type' => 'sale']) }}">{{ __('Sale') }}</a>
          <a href="/admin" class="px-3 py-1 rounded bg-gray-900 text-white">{{ __('Admin') }}</a>
      </div>

      <div class="flex items-center justify-end gap-3">
          <a href="{{ route('locale.switch', 'en') }}" class="text-sm underline">EN</a>
          <a href="{{ route('locale.switch', 'sr') }}" class="text-sm underline">MNE</a>
          <a href="{{ route('locale.switch', 'ru') }}" class="text-sm underline">RU</a>
      </div>
  </nav>

  {{-- MOBILE MENU PANEL (tvoj postojeći) --}}
  <div id="mobileMenu"
       class="md:hidden hidden border-t bg-white/95 backdrop-blur supports-[backdrop-filter]:bg-white/70">
      <div class="container mx-auto px-5 py-3 flex flex-col gap-2">
          <a class="py-2" href="{{ route('properties.index', ['type' => 'rent']) }}">{{ __('Rent') }}</a>
          <a class="py-2" href="{{ route('properties.index', ['type' => 'sale']) }}">{{ __('Sale') }}</a>
          <a class="py-2" href="/admin">{{ __('Admin') }}</a>

          <div class="h-px bg-gray-200 my-1"></div>

          <div class="flex items-center gap-3">
              <span class="text-sm text-gray-500">{{ __('Language') }}:</span>
              <a href="{{ route('locale.switch', 'en') }}" class="text-sm underline">EN</a>
              <a href="{{ route('locale.switch', 'sr') }}" class="text-sm underline">MNE</a>
              <a href="{{ route('locale.switch', 'ru') }}" class="text-sm underline">RU</a>
          </div>
      </div>
  </div>
</header>

    {{-- bitno: bez globalnog paddinga ovde, da hero može full-bleed --}}
    <main class="relative overflow-x-hidden">@yield('content')</main>

    <footer class="mt-16 border-t">
        <div class="container mx-auto px-5 py-6 text-sm text-gray-600">
            © {{ date('Y') }}
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('menuToggle');
            const panel = document.getElementById('mobileMenu');
            if (!btn || !panel) return;

            btn.addEventListener('click', () => {
                const isHidden = panel.classList.toggle('hidden');
                btn.setAttribute('aria-expanded', String(!isHidden));
                btn.innerHTML = isHidden
                  ? `<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 6h18M3 12h18M3 18h18" stroke-width="2" stroke-linecap="round"/></svg>`
                  : `<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 6l12 12M18 6l-12 12" stroke-width="2" stroke-linecap="round"/></svg>`;
            });

            panel.querySelectorAll('a').forEach(a => {
                a.addEventListener('click', () => {
                    if (!panel.classList.contains('hidden')) {
                        panel.classList.add('hidden');
                        btn.setAttribute('aria-expanded', 'false');
                        btn.innerHTML =
                          `<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 6h18M3 12h18M3 18h18" stroke-width="2" stroke-linecap="round"/></svg>`;
                    }
                });
            });
        });
    </script>
   
</body>
</html>
