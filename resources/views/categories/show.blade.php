@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10">
  <h1 class="text-3xl font-bold mb-6">{{ $category->name_localized ?? $category->name }}</h1>

  <div class="grid md:grid-cols-3 gap-6">
    @foreach($properties as $p)
      <a href="{{ route('properties.show',$p->slug) }}" class="border rounded-xl overflow-hidden">
        @php $img = $p->images[0] ?? null; @endphp
        <img src="{{ $img ? asset('storage/'.$img) : 'https://placehold.co/600x400' }}" class="w-full h-48 object-cover" />
        <div class="p-4">
          <div class="font-semibold">{{ $p->title_localized ?? $p->title }}</div>
          <div class="text-sm text-gray-600">
            {{ $p->city_localized ?? $p->city }}
            · {{ $p->area }} m²
            · €{{ number_format($p->price,0,',','.') }}
          </div>
        </div>
      </a>
    @endforeach
  </div>

  <div class="mt-6">{{ $properties->links() }}</div>
</div>
@endsection
