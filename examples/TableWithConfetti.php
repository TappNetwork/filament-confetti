<?php

namespace App\Filament\Resources;

use App\Models\Achievement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Tapp\FilamentConfetti\Actions\ConfettiAction;

class AchievementResource extends Resource
{
    protected static ?string $model = Achievement::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('description')
                    ->limit(50),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'locked',
                        'success' => 'unlocked',
                    ]),
                
                Tables\Columns\TextColumn::make('unlocked_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'locked' => 'Locked',
                        'unlocked' => 'Unlocked',
                    ]),
            ])
            ->actions([
                // Unlock achievement with celebration
                Tables\Actions\Action::make('unlock')
                    ->icon('heroicon-o-lock-open')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Unlock Achievement')
                    ->modalDescription(fn (Achievement $record) => 
                        "Are you sure you want to unlock '{$record->title}'?"
                    )
                    ->action(function (Achievement $record) {
                        $record->unlock();
                        
                        // Fire confetti celebration
                        $this->dispatch('confetti', [
                            'preset' => 'realistic',
                            'options' => [
                                'particleCount' => 200,
                                'colors' => ['#10b981', '#34d399', '#6ee7b7', '#a7f3d0'],
                            ]
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Achievement Unlocked! 🎉')
                            ->body("Congratulations! You unlocked '{$record->title}'")
                            ->send();
                    })
                    ->visible(fn (Achievement $record) => $record->status === 'locked'),
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    // Bulk unlock with confetti
                    Tables\Actions\BulkAction::make('unlock')
                        ->icon('heroicon-o-trophy')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $count = 0;
                            foreach ($records as $record) {
                                if ($record->status === 'locked') {
                                    $record->unlock();
                                    $count++;
                                }
                            }
                            
                            // Fire massive confetti celebration
                            if ($count > 0) {
                                $this->dispatch('confetti', [
                                    'preset' => 'fireworks',
                                    'options' => [
                                        'duration' => 5000,
                                        'particleCount' => 100,
                                        'colors' => ['#FFE400', '#FFBD00', '#E89400', '#10b981'],
                                    ]
                                ]);
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Achievements Unlocked! 🎊')
                                ->body("Successfully unlocked {$count} achievement(s)")
                                ->send();
                        }),
                ]),
            ])
            ->headerActions([
                // Celebrate all achievements button
                ConfettiAction::make('celebrate_all')
                    ->label('Celebrate All!')
                    ->icon('heroicon-o-sparkles')
                    ->color('primary')
                    ->fireworks(duration: 10000)
                    ->colors(['#FFE400', '#FFBD00', '#E89400', '#FFCA6C', '#10b981']),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAchievements::route('/'),
            'create' => Pages\CreateAchievement::route('/create'),
            'edit' => Pages\EditAchievement::route('/{record}/edit'),
        ];
    }
}
