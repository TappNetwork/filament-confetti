<?php

namespace Tapp\FilamentConfetti\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Illuminate\Contracts\Support\Htmlable;

class ConfettiAction extends Action
{
    use CanCustomizeProcess;

    protected string|Htmlable|Closure|null $confettiPreset = null;

    protected array|Closure|null $confettiOptions = null;

    protected bool|Closure $autoFire = false;

    protected int|Closure|null $delay = null;

    protected function setUp(): void
    {
        parent::setUp();

        // Store config in data attribute for Alpine to access
        $this->extraAttributes(function (ConfettiAction $action): array {
            $attributes = [];

            $config = $action->getConfettiConfig();

            // Store config as a data attribute (Laravel will handle escaping)
            $attributes['data-confetti-config'] = htmlspecialchars(json_encode($config), ENT_QUOTES, 'UTF-8');

            // Use Alpine to read from data attribute and dispatch
            // Use single quotes to avoid escaping issues
            $attributes['@click'] = "window.dispatchEvent(new CustomEvent('confetti', { detail: JSON.parse(\$el.getAttribute('data-confetti-config')) }))";

            if ($action->shouldAutoFire()) {
                $attributes['x-init'] = 'setTimeout(() => { 
                    window.dispatchEvent(new CustomEvent(\'confetti\', { 
                        detail: JSON.parse($el.getAttribute(\'data-confetti-config\')) 
                    })) 
                }, '.($action->getDelay() ?? 0).')';
            }

            return $attributes;
        });

        // Use alpineClickHandler to prevent Livewire action
        $this->alpineClickHandler('');
    }

    public function confetti(string|Closure|null $preset = null): static
    {
        $this->confettiPreset = $preset;

        return $this;
    }

    public function confettiOptions(array|Closure|null $options): static
    {
        $this->confettiOptions = $options;

        return $this;
    }

    public function autoFire(bool|Closure $condition = true): static
    {
        $this->autoFire = $condition;

        return $this;
    }

    public function delay(int|Closure $milliseconds): static
    {
        $this->delay = $milliseconds;

        return $this;
    }

    // Preset helper methods
    public function basic(array $options = []): static
    {
        return $this->confettiOptions(array_merge([
            'particleCount' => 100,
            'spread' => 70,
            'origin' => ['y' => 0.6],
        ], $options));
    }

    public function randomDirection(array $options = []): static
    {
        return $this->confettiOptions(array_merge([
            'angle' => fn () => rand(55, 125),
            'spread' => fn () => rand(50, 70),
            'particleCount' => fn () => rand(50, 100),
            'origin' => ['y' => 0.6],
        ], $options));
    }

    public function fireworks(int $duration = 5000, array $options = []): static
    {
        $this->confettiPreset = 'fireworks';

        return $this->confettiOptions(array_merge([
            'duration' => $duration,
            'startVelocity' => 30,
            'spread' => 360,
            'ticks' => 60,
            'particleCount' => 50,
        ], $options));
    }

    public function snow(int $duration = 5000, array $options = []): static
    {
        $this->confettiPreset = 'snow';

        return $this->confettiOptions(array_merge([
            'duration' => $duration,
            'particleCount' => 1,
            'startVelocity' => 0,
            'ticks' => 200,
            'gravity' => 0.3,
            'spread' => 90,
            'drift' => fn () => (rand(0, 1) ? 1 : -1) * 0.4,
            'scalar' => 1.2,
        ], $options));
    }

    public function stars(array $options = []): static
    {
        return $this->confettiOptions(array_merge([
            'spread' => 360,
            'ticks' => 50,
            'gravity' => 0,
            'decay' => 0.94,
            'startVelocity' => 30,
            'shapes' => ['star'],
            'colors' => ['FFE400', 'FFBD00', 'E89400', 'FFCA6C', 'FDFFB8'],
        ], $options));
    }

    public function sideCannons(int $duration = 5000, array $options = []): static
    {
        $this->confettiPreset = 'sideCannons';

        return $this->confettiOptions(array_merge([
            'duration' => $duration,
            'particleCount' => 3,
            'angle' => 60,
            'spread' => 55,
            'startVelocity' => 60,
        ], $options));
    }

    public function realistic(array $options = []): static
    {
        $this->confettiPreset = 'realistic';

        return $this->confettiOptions($options);
    }

    public function school(int $duration = 3000, array $options = []): static
    {
        $this->confettiPreset = 'school';

        return $this->confettiOptions(array_merge([
            'duration' => $duration,
        ], $options));
    }

    public function emoji(string $emoji, array $options = []): static
    {
        return $this->confettiOptions(array_merge([
            'emoji' => $emoji,
            'scalar' => 2,
            'spread' => 360,
            'ticks' => 60,
            'gravity' => 0,
            'decay' => 0.96,
            'startVelocity' => 20,
        ], $options));
    }

    public function customShape(string $svgPath, array $options = []): static
    {
        return $this->confettiOptions(array_merge([
            'customShape' => $svgPath,
            'scalar' => 2,
        ], $options));
    }

    public function colors(array $colors): static
    {
        $options = $this->getConfettiOptions() ?? [];
        $options['colors'] = $colors;

        return $this->confettiOptions($options);
    }

    public function particleCount(int $count): static
    {
        $options = $this->getConfettiOptions() ?? [];
        $options['particleCount'] = $count;

        return $this->confettiOptions($options);
    }

    public function origin(array $origin): static
    {
        $options = $this->getConfettiOptions() ?? [];
        $options['origin'] = $origin;

        return $this->confettiOptions($options);
    }

    public function getConfettiPreset(): ?string
    {
        return $this->evaluate($this->confettiPreset);
    }

    public function getConfettiOptions(): ?array
    {
        $options = $this->evaluate($this->confettiOptions);

        if (! $options) {
            return null;
        }

        // Evaluate closures in options
        foreach ($options as $key => $value) {
            if ($value instanceof Closure) {
                $options[$key] = $value();
            }
        }

        return $options;
    }

    public function getConfettiConfig(): array
    {
        return [
            'preset' => $this->getConfettiPreset(),
            'options' => $this->getConfettiOptions(),
        ];
    }

    public function shouldAutoFire(): bool
    {
        return $this->evaluate($this->autoFire);
    }

    public function getDelay(): ?int
    {
        return $this->evaluate($this->delay);
    }

    protected function registerConfettiScripts(): void
    {
        // This method is no longer needed since we're using pure Alpine
        // The confetti is dispatched directly via Alpine click handler
        // Keep it for backwards compatibility if someone calls parent actions
    }
}
