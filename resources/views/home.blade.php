{{-- resources/views/home.blade.php --}}
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('Home') }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    /* ---------- HERO LAYOUT ---------- */
    html,body { height: 100%; margin: 0; }
    body { overflow: hidden; font-family: system-ui, -apple-system, Segoe UI, Roboto, Inter, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif; }

    .hero {
      position: relative;
      height: 100svh;           /* daje malo prostora iznad i ispod kao na slici */
      min-height: 540px;
      background-size: cover;
      background-position: center;
      
    }
    .overlay { position: absolute; inset: 0; background: rgba(0,0,0,.32); }

    .center {
      position: absolute; inset: 0;
      display: flex; align-items: center; justify-content: center;
      padding-inline: 24px;
      text-align: center;
    }

    /* ---------- TITLE (dve linije kao na slici) ---------- */
    .title {
      color: #fff; font-weight: 500; line-height: 1.13;
      text-shadow: 0 10px 30px rgba(0,0,0,.45), 0 2px 6px rgba(0,0,0,.35);
      letter-spacing: .2px;
      margin: 0;
    }
    .title .line { display:block; }
    .title .line-1 { font-size: 36px; }
    .title .line-2 { font-size: 36px; margin-top: 6px; }

    @media (min-width: 640px) {
      .title .line-1 { font-size: 48px; }
      .title .line-2 { font-size: 48px; }
    }
    @media (min-width: 1024px) {
      .title .line-1 { font-size: 56px; }
      .title .line-2 { font-size: 56px; }
    }

    /* ---------- BUTTONS ---------- */
    .btns { margin-top: 28px; display:flex; gap:16px; justify-content:center; }
    .btn {
      border-radius: 9999px; padding: 12px 28px; font-weight: 700;
      text-decoration: none; display:inline-flex; align-items:center;
      box-shadow: 0 10px 20px rgba(0,0,0,.25);
      transition: transform .15s ease, filter .15s ease, background-color .15s ease;
    }
    .btn:active { transform: translateY(1px); }

    .btn-sale  { background: rgba(30,41,59,.92); color: #fff; }     /* tamno-plava */
    .btn-sale:hover  { filter: brightness(1.04); }

    .btn-rent { background: #f2c14e; color: #111; }                /* zlatna */
    .btn-rent:hover { background: #f5cf71; }

    /* ---------- LANGUAGE DROPDOWN (gore desno) ---------- */
    .lang { position:absolute; right:20px; top:20px; z-index:20; }
    .lang details { position: relative; }
    .lang summary {
      list-style: none; cursor: pointer;
      display:inline-flex; align-items:center; gap:8px;
      background: rgba(0,0,0,.55); color:#fff;
      border: 1px solid rgba(255,255,255,.2);
      padding: 10px 14px; border-radius: 8px; backdrop-filter: blur(4px);
      user-select:none;
    }
    .lang summary::-webkit-details-marker { display:none; }
    .lang .chev { transition: transform .2s ease; }
    .lang details[open] .chev { transform: rotate(180deg); }

    .lang .menu {
      position:absolute; right:0; margin-top:8px; min-width:110px;
      border-radius: 8px; overflow: hidden;
      background: rgba(0,0,0,.72); color:#fff;
      border: 1px solid rgba(255,255,255,.2);
    }
    .lang .menu a {
      display:block; padding:8px 14px; color:#fff; text-decoration:none;
    }
    .lang .menu a:hover { background: rgba(255,255,255,.10); }
  </style>
</head>
<body>

  <!-- HERO FULL BLEED -->
  <section class="hero" style="background-image:url('{{ asset('storage/properties/baner.jpg') }}')">
    <div class="overlay"></div>

    <!-- Language dropdown -->
    <div class="lang" aria-label="Language selector">
      <details>
        <summary>
          <strong>{{ strtoupper(app()->getLocale()==='sr'?'MNE':(app()->getLocale()==='ru'?'RU':'EN')) }}</strong>
          <svg class="chev" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.17l3.71-2.94a.75.75 0 111.04 1.08l-4.23 3.35a.75.75 0 01-.94 0L5.21 8.31a.75.75 0 01.02-1.1z" clip-rule="evenodd"/>
          </svg>
        </summary>
        <div class="menu">
          <a href="{{ route('locale.switch','en') }}">ENG</a>
          <a href="{{ route('locale.switch','sr') }}">MNE</a>
          <a href="{{ route('locale.switch','ru') }}">RUS</a>
        </div>
      </details>
    </div>

    <!-- Centered title + buttons -->
    <div class="center">
      <div>
        <h1 class="title" role="heading" aria-level="1">
          <span class="line line-1">{{ __('Luxury Apartments for Sale & Rent in') }}</span>
          <span class="line line-2">{{ __('Montenegro') }}</span>
        </h1>

        <div class="btns">
          <a class="btn btn-sale"  href="{{ route('properties.index',['type'=>'sale']) }}">{{ __('Sale') }}</a>
          <a class="btn btn-rent" href="{{ route('properties.index',['type'=>'rent']) }}">{{ __('Rent') }}</a>
        </div>
      </div>
    </div>
  </section>

</body>
</html>
