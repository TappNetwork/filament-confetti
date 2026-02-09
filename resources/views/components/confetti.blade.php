@php
    $config = $getConfettiConfig();
    $autoFire = $shouldAutoFire();
    $delay = $getDelay() ?? 0;
    $trigger = $getTrigger();
@endphp

<div
    ax-load
    ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('confetti', 'tapp/confetti') }}"
    x-data="confettiComponent({
        config: @js($config),
        autoFire: @js($autoFire),
        delay: @js($delay)
    })"
    
    @if($trigger)
        @{{ $trigger }}.window="fire($event.detail)"
    @endif
    
    x-on:confetti.window="fire($event.detail)"
    
    {{ $attributes->merge(['class' => 'filament-confetti']) }}
>
    {{ $getChildComponentContainer() }}
</div>
