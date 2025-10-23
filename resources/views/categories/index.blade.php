@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10">
  <h1 class="text-3xl font-bold mb-6">{{ __('Categories') }}</h1>

  <div class="grid md:grid-cols-2 gap-6">
    <a href="{{ route('properties.index',['type'=>'rent']) }}" class="p-8 border rounded-2xl flex items-center justify-between">
      <div>
        <div class="text-xl font-semibold">{{ __('Rent') }}</div>
        <div class="text-sm text-gray-600">{{ $rentCount }} {{ __('Available') }}</div>
      </div>
      <span>🏠</span>
    </a>

    <a href="{{ route('properties.index',['type'=>'sale']) }}" class="p-8 border rounded-2xl flex items-center justify-between">
      <div>
        <div class="text-xl font-semibold">{{ __('Sale') }}</div>
        <div class="text-sm text-gray-600">{{ $saleCount }} {{ __('Available') }}</div>
      </div>
      <span>🏢</span>
    </a>
  </div>

  <h2 class="text-xl font-semibold mt-10 mb-4">{{ __('All categories') }}</h2>
  <ul class="grid md:grid-cols-3 gap-4">
    @foreach($categories as $c)
      <li>
        <a class="block p-4 border rounded-lg hover:bg-gray-50" href="{{ route('categories.show',$c->slug) }}">
          {{ $c->name_localized ?? $c->name }}
        </a>
      </li>
    @endforeach
  </ul>
</div>
@endsection
