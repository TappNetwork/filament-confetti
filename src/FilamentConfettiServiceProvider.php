<?php

namespace Tapp\FilamentConfetti;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentConfettiServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-confetti';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews('filament-confetti');
    }

    public function packageBooted(): void
    {
        // Register the confetti JavaScript component
        FilamentAsset::register([
            AlpineComponent::make('confetti', __DIR__.'/../dist/confetti.js'),
        ], 'tapp/filament-confetti');

        // Register the global confetti event listener
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn (): string => view('filament-confetti::confetti-listener')->render(),
        );
    }
}
