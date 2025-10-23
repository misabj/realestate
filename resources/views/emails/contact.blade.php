<p><strong>{{ __('Name and surname') }}:</strong> {{ $name }}</p>
<p><strong>{{ __('Email') }}:</strong> {{ $emailFrom }}</p>
@if(!empty($phone))
<p><strong>{{ __('Phone') }}:</strong> {{ $phone }}</p>
@endif

<p><strong>{{ __('Message') }}:</strong></p>
<p>{!! nl2br(e($userMessage)) !!}</p>
