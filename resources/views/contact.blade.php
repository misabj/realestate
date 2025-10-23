@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 max-w-xl">
  <h1 class="text-3xl font-bold mb-6">{{ __('Send inquiry') }}</h1>

  <form method="POST" action="{{ route('contact.send') }}" class="space-y-3">
    @csrf

    <input class="w-full border rounded-lg p-2" name="name" placeholder="{{ __('Name and surname') }}" required>
    <input class="w-full border rounded-lg p-2" name="email" type="email" placeholder="{{ __('Email') }}" required>
    <input class="w-full border rounded-lg p-2" name="phone" placeholder="{{ __('Phone') }}">
    <textarea class="w-full border rounded-lg p-2" name="message" rows="5" placeholder="{{ __('Message') }}" required></textarea>

    <button class="w-full bg-black text-white rounded-lg py-2">
      {{ __('Send') }}
    </button>
  </form>
</div>
@endsection
