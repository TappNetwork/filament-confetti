<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Tapp\FilamentConfetti\Actions\ConfettiAction;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),

            // Basic confetti celebration
            ConfettiAction::make('celebrate')
                ->label('Celebrate! 🎉')
                ->basic()
                ->particleCount(100)
                ->colors(['#ff0000', '#00ff00', '#0000ff']),

            // Fireworks display
            ConfettiAction::make('fireworks')
                ->label('Launch Fireworks')
                ->icon('heroicon-o-sparkles')
                ->fireworks(duration: 5000)
                ->colors(['#FFE400', '#FFBD00', '#E89400']),

            // Stars effect
            ConfettiAction::make('stars')
                ->label('Stars')
                ->icon('heroicon-o-star')
                ->stars()
                ->particleCount(40),

            // Emoji confetti
            ConfettiAction::make('hearts')
                ->label('Send Hearts')
                ->emoji('❤️')
                ->particleCount(50),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'User updated successfully!';
    }

    protected function afterSave(): void
    {
        // Auto-fire confetti after successful save
        $this->dispatch('confetti', [
            'preset' => 'realistic',
            'options' => [
                'particleCount' => 150,
                'colors' => ['#10b981', '#3b82f6', '#8b5cf6'],
            ],
        ]);
    }
}
