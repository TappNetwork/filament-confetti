# Filament Confetti

A beautiful and performant confetti animation plugin for Filament PHP using [canvas-confetti](https://github.com/catdad/canvas-confetti). Features async Alpine.js loading, fluent Laravel-style API, and multiple built-in presets.

<img src="./art/logo.png" height="300" />

## Features

- **Async Loading** - Canvas-confetti library is loaded on-demand using Alpine.js
- **Multiple Presets** - Fireworks, Snow, Stars, Side Cannons, Realistic, and more
- **Fluent API** - Laravel-style chainable methods for easy configuration
- **Performant** - Leverages canvas-confetti's optimized rendering
- **Flexible** - Use as Actions, Components, or trigger via custom events
- **Customizable** - Full control over colors, shapes, emojis, and physics

## Requirements

- PHP 8.2+
- Filament 4.x/5.x
- Laravel 11.x/12.x

## Installation

Install the package via Composer:

```bash
composer require tapp/filament-confetti
```

The package will automatically register itself.

## Usage

### As an Action

Use confetti in your Filament actions:

```php
use Tapp\FilamentConfetti\Actions\ConfettiAction;

// Basic confetti
ConfettiAction::make('celebrate')
    ->label('Celebrate! 🎉')
    ->basic()

// Fireworks
ConfettiAction::make('fireworks')
    ->label('Launch Fireworks')
    ->fireworks()

// With auto-fire
ConfettiAction::make('success')
    ->fireworks()
    ->autoFire()
    ->delay(500)

// Custom configuration
ConfettiAction::make('custom')
    ->confettiOptions([
        'particleCount' => 150,
        'spread' => 90,
        'origin' => ['y' => 0.5],
    ])
    ->colors(['#ff0000', '#00ff00', '#0000ff'])
```

### As a Component

Add confetti to forms or infolists:

```php
use Tapp\FilamentConfetti\Components\Confetti;

// In a form schema
Confetti::make()
    ->stars()
    ->autoFire()
    ->delay(1000)

// With custom trigger
Confetti::make()
    ->fireworks()
    ->trigger('celebrate')

// Then trigger from JavaScript
// $dispatch('celebrate')
```

### Via JavaScript Event

Trigger confetti from anywhere in your Filament app:

```javascript
// Trigger with default config
$dispatch('confetti')

// Trigger with custom config
$dispatch('confetti', {
    preset: 'fireworks',
    options: {
        duration: 3000,
        colors: ['#ff0000', '#00ff00']
    }
})
```

### Dispatch event via Livewire component

Trigger confetti from any Livewire component:

```javascript
$this->dispatch('confetti', [
    'options' => [
        'particleCount' => 50,
        'spread' => 70,
        'origin' => ['y' => 0.6]
    ]
]);
```

## Presets

### Basic

Simple confetti burst:

```php
ConfettiAction::make('basic')
    ->basic()
    ->particleCount(100)
    ->colors(['#ff0000', '#00ff00', '#0000ff'])
```

### Fireworks

Continuous fireworks display:

```php
ConfettiAction::make('fireworks')
    ->fireworks(duration: 5000)
    ->colors(['#FFE400', '#FFBD00', '#E89400'])
```

### Snow

Gentle falling snowflakes:

```php
ConfettiAction::make('snow')
    ->snow(duration: 10000)
    ->colors(['#ffffff', '#99ccff'])
```

### Stars

Sparkling stars:

```php
ConfettiAction::make('stars')
    ->stars()
    ->colors(['#FFE400', '#FFBD00', '#E89400', '#FFCA6C', '#FDFFB8'])
```

### Side Cannons

Confetti shooting from the sides:

```php
ConfettiAction::make('cannons')
    ->sideCannons(duration: 3000)
    ->particleCount(5)
```

### Realistic

Multi-burst realistic confetti:

```php
ConfettiAction::make('realistic')
    ->realistic()
    ->particleCount(200)
```

### School Pride

Celebratory school-style confetti:

```php
ConfettiAction::make('school')
    ->school(duration: 3000)
```

### Random Direction

Confetti with randomized angles:

```php
ConfettiAction::make('random')
    ->randomDirection()
```

### Emoji

Use any emoji as confetti:

```php
ConfettiAction::make('hearts')
    ->emoji('❤️')

ConfettiAction::make('unicorns')
    ->emoji('🦄')
    ->particleCount(50)
```

### Custom Shapes

Create confetti from SVG paths:

```php
ConfettiAction::make('triangles')
    ->customShape('M0 10 L5 0 L10 10z')
    ->colors(['#ff0000', '#00ff00'])
```

## Advanced Configuration

### All Available Options

```php
ConfettiAction::make('advanced')
    ->confettiOptions([
        'particleCount' => 100,       // Number of confetti particles
        'angle' => 90,                // Launch angle in degrees
        'spread' => 45,               // Spread in degrees
        'startVelocity' => 45,        // Initial velocity in pixels
        'decay' => 0.9,               // Speed decay rate
        'gravity' => 1,               // Gravity strength
        'drift' => 0,                 // Horizontal drift
        'ticks' => 200,               // Animation duration
        'origin' => [                 // Launch origin point
            'x' => 0.5,               // 0 = left, 1 = right
            'y' => 0.5                // 0 = top, 1 = bottom
        ],
        'colors' => [                 // Array of color hex codes
            '#ff0000',
            '#00ff00',
            '#0000ff'
        ],
        'shapes' => [                 // Particle shapes
            'square',
            'circle',
            'star'
        ],
        'scalar' => 1,                // Size multiplier
        'zIndex' => 100,              // CSS z-index
    ])
```

### Combining Multiple Effects

Chain multiple confetti calls for complex effects:

```php
ConfettiAction::make('combo')
    ->action(function () {
        // First burst
        $this->dispatch('confetti', [
            'options' => [
                'particleCount' => 50,
                'spread' => 70,
                'origin' => ['y' => 0.6]
            ]
        ]);
        
        // Second burst after delay
        $this->dispatch('confetti', [
            'options' => [
                'particleCount' => 30,
                'spread' => 90,
                'origin' => ['x' => 0.2, 'y' => 0.7]
            ]
        ]);
    })
```

### Dynamic Configuration

Use closures for dynamic values:

```php
ConfettiAction::make('dynamic')
    ->confettiOptions(fn () => [
        'particleCount' => rand(50, 150),
        'angle' => rand(55, 125),
        'colors' => $this->getUserFavoriteColors(),
    ])
```

## Examples

### Success Notification

Celebrate when a form is successfully submitted:

```php
use Filament\Forms\Form;
use YourVendor\FilamentConfetti\Actions\ConfettiAction;

public function form(Form $form): Form
{
    return $form
        ->schema([
            // ... your form fields
        ])
        ->statePath('data');
}

protected function getFormActions(): array
{
    return [
        Action::make('save')
            ->action(function () {
                // Save logic
                $this->save();
                
                // Trigger confetti
                $this->dispatch('confetti', [
                    'preset' => 'fireworks',
                    'options' => ['duration' => 3000]
                ]);
                
                Notification::make()
                    ->success()
                    ->title('Saved successfully!')
                    ->send();
            }),
    ];
}
```

### Celebration Page

Create a custom page with auto-firing confetti:

```php
use Filament\Pages\Page;
use YourVendor\FilamentConfetti\Components\Confetti;

class CelebrationPage extends Page
{
    protected static string $view = 'filament.pages.celebration';
    
    public function mount(): void
    {
        $this->dispatch('confetti', [
            'preset' => 'school',
            'options' => ['duration' => 5000]
        ]);
    }
}
```

### Table Action

Add confetti to table row actions:

```php
use Filament\Tables\Actions\Action;

Action::make('celebrate')
    ->icon('heroicon-o-sparkles')
    ->action(function (Model $record) {
        $record->markAsCelebrated();
        
        $this->dispatch('confetti', [
            'preset' => 'stars',
            'options' => [
                'colors' => ['#FFE400', '#FFBD00', '#E89400']
            ]
        ]);
    })
```

### Modal with Confetti

Show confetti when opening a modal:

```php
Action::make('winner')
    ->modalHeading('Congratulations! 🎉')
    ->modalContent(view('filament.modals.winner'))
    ->modalActions([
        Action::make('claim')
            ->action(fn () => $this->claimPrize()),
    ])
    ->after(function () {
        $this->dispatch('confetti', [
            'preset' => 'realistic',
            'options' => ['particleCount' => 200]
        ]);
    })
```

## Browser Compatibility

This plugin uses the [canvas-confetti](https://github.com/catdad/canvas-confetti) library, which works in all modern browsers:

- Chrome/Edge 15+
- Firefox 44+
- Safari 11+
- Opera

## Credits

- Built on [canvas-confetti](https://github.com/catdad/canvas-confetti) by [catdad](https://github.com/catdad)
- Created for [Filament PHP](https://filamentphp.com)

## License

MIT License - see [LICENSE](LICENSE) file for details

## Support

For issues, questions, or contributions, please visit the [GitHub repository](https://github.com/TappNetwork/filament-confetti).
