<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Tapp\FilamentConfetti\Actions\ConfettiAction;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    protected static ?string $title = 'Dashboard';

    public function mount(): void
    {
        // Welcome confetti on page load
        $this->dispatch('confetti', [
            'preset' => 'realistic',
            'options' => [
                'particleCount' => 100,
                'spread' => 70,
            ],
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            // Basic confetti
            ConfettiAction::make('basic')
                ->label('Basic')
                ->icon('heroicon-o-sparkles')
                ->basic()
                ->colors(['#3b82f6', '#8b5cf6', '#ec4899']),

            // Fireworks
            ConfettiAction::make('fireworks')
                ->label('Fireworks')
                ->icon('heroicon-o-fire')
                ->fireworks(duration: 5000)
                ->colors(['#FFE400', '#FFBD00', '#E89400', '#FFCA6C']),

            // Snow
            ConfettiAction::make('snow')
                ->label('Snow')
                ->icon('heroicon-o-cloud')
                ->snow(duration: 10000)
                ->colors(['#ffffff', '#e0f2fe', '#bae6fd']),

            // Stars
            ConfettiAction::make('stars')
                ->label('Stars')
                ->icon('heroicon-o-star')
                ->stars()
                ->particleCount(40)
                ->colors(['#FFE400', '#FFBD00', '#E89400', '#FFCA6C', '#FDFFB8']),

            // Side Cannons
            ConfettiAction::make('cannons')
                ->label('Side Cannons')
                ->icon('heroicon-o-rocket-launch')
                ->sideCannons(duration: 3000)
                ->particleCount(5)
                ->colors(['#ef4444', '#f97316', '#eab308']),

            // School Pride
            ConfettiAction::make('school')
                ->label('School')
                ->icon('heroicon-o-academic-cap')
                ->school(duration: 3000),

            // Random Direction
            ConfettiAction::make('random')
                ->label('Random')
                ->icon('heroicon-o-arrow-path')
                ->randomDirection()
                ->colors(['#10b981', '#14b8a6', '#06b6d4']),

            // Emoji - Hearts
            ConfettiAction::make('hearts')
                ->label('Hearts')
                ->icon('heroicon-o-heart')
                ->emoji('❤️')
                ->particleCount(30),

            // Emoji - Unicorns
            ConfettiAction::make('unicorns')
                ->label('Unicorns')
                ->emoji('🦄')
                ->particleCount(25),

            // Emoji - Stars
            ConfettiAction::make('star-emoji')
                ->label('Star Emoji')
                ->emoji('⭐')
                ->particleCount(40),

            // Custom Shape - Triangle
            ConfettiAction::make('triangles')
                ->label('Triangles')
                ->customShape('M0 10 L5 0 L10 10z')
                ->particleCount(50)
                ->colors(['#ff0000', '#00ff00', '#0000ff']),

            // Custom advanced configuration
            ConfettiAction::make('advanced')
                ->label('Advanced')
                ->icon('heroicon-o-cog')
                ->confettiOptions([
                    'particleCount' => 150,
                    'spread' => 180,
                    'startVelocity' => 60,
                    'decay' => 0.95,
                    'gravity' => 1.2,
                    'drift' => 0.5,
                    'ticks' => 300,
                    'origin' => ['x' => 0.5, 'y' => 0.3],
                    'colors' => ['#ff6b6b', '#4ecdc4', '#45b7d1', '#f7b731'],
                    'shapes' => ['circle', 'square'],
                    'scalar' => 1.5,
                ])
                ->tooltip('Custom configured confetti'),
        ];
    }

    public function getViewData(): array
    {
        return [
            'stats' => [
                'users' => 1234,
                'revenue' => '$56,789',
                'orders' => 890,
            ],
        ];
    }
}
