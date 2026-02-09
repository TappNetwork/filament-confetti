<div
    x-data="{
        confettiModule: null,
        
        async init() {
            // Preload the confetti module on page load
            try {
                const module = await import('{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('confetti', 'tapp/filament-confetti') }}');
                this.confettiModule = module.default;
                console.log('Confetti module preloaded');
            } catch (error) {
                console.error('Failed to preload confetti module:', error);
            }
        },
        
        async fireConfetti(event) {
            // Livewire passes event data as an array, get first element
            const eventData = Array.isArray(event.detail) ? event.detail[0] : event.detail;
            
            // If module not loaded yet, load it now
            if (!this.confettiModule) {
                const module = await import('{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('confetti', 'tapp/filament-confetti') }}');
                this.confettiModule = module.default;
            }
            
            // Create component instance and fire
            const component = this.confettiModule({
                config: eventData,
                autoFire: false,
                delay: 0
            });
            
            component.fire(eventData);
        }
    }"
    x-on:confetti.window="fireConfetti($event)"
></div>
