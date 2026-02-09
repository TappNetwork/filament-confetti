<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Tapp\FilamentConfetti\Components\Confetti;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Order Details')
                    ->schema([
                        TextInput::make('order_number')
                            ->required()
                            ->disabled(),

                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state) {
                                // Fire confetti when order is completed
                                if ($state === 'completed') {
                                    $this->dispatch('order-completed');
                                }
                            }),

                        // Confetti component that listens for order completion
                        Confetti::make()
                            ->fireworks(duration: 3000)
                            ->colors(['#10b981', '#34d399', '#6ee7b7'])
                            ->trigger('order-completed'),
                    ]),

                Section::make('Celebration Zone')
                    ->description('This confetti fires automatically when the section is visible')
                    ->schema([
                        // Auto-firing confetti
                        Confetti::make()
                            ->stars()
                            ->autoFire()
                            ->delay(500),

                        TextInput::make('celebration_message')
                            ->placeholder('🎉 Order Completed! 🎉'),
                    ])
                    ->visible(fn ($record) => $record?->status === 'completed'),
            ]);
    }
}
