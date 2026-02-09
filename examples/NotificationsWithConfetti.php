<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Tapp\FilamentConfetti\Actions\ConfettiAction;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Mark as complete with celebration
            Action::make('complete')
                ->label('Mark as Complete')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Complete Project')
                ->modalDescription('Are you sure you want to mark this project as complete?')
                ->action(function (Project $record) {
                    $record->markAsComplete();

                    // Fire confetti
                    $this->dispatch('confetti', [
                        'preset' => 'realistic',
                        'options' => [
                            'particleCount' => 200,
                            'colors' => ['#10b981', '#34d399', '#6ee7b7', '#a7f3d0'],
                        ],
                    ]);

                    // Show notification
                    Notification::make()
                        ->success()
                        ->title('Project Completed! 🎉')
                        ->body("Congratulations! '{$record->name}' has been marked as complete.")
                        ->duration(5000)
                        ->send();

                    // Redirect to list
                    return redirect()->route('filament.admin.resources.projects.index');
                })
                ->visible(fn (Project $record) => ! $record->is_completed),

            // Milestone celebration
            Action::make('milestone')
                ->label('Celebrate Milestone')
                ->icon('heroicon-o-trophy')
                ->color('warning')
                ->action(function (Project $record) {
                    // Fire multiple confetti effects
                    $this->dispatch('confetti', [
                        'preset' => 'fireworks',
                        'options' => [
                            'duration' => 5000,
                            'colors' => ['#FFE400', '#FFBD00', '#E89400', '#FFCA6C'],
                        ],
                    ]);

                    // Wait a bit and fire stars
                    $this->js("setTimeout(() => {
                        \$dispatch('confetti', {
                            preset: 'stars',
                            options: {
                                particleCount: 50,
                                colors: ['#FFE400', '#FFBD00', '#E89400']
                            }
                        });
                    }, 2000)");

                    Notification::make()
                        ->success()
                        ->title('Milestone Reached! 🏆')
                        ->body('Great work on achieving this milestone!')
                        ->duration(5000)
                        ->send();
                }),

            // Share success
            Action::make('share')
                ->label('Share Success')
                ->icon('heroicon-o-share')
                ->color('primary')
                ->form([
                    \Filament\Forms\Components\Textarea::make('message')
                        ->label('Success Message')
                        ->placeholder('Share your success with the team...')
                        ->rows(3)
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Share the message (implementation depends on your app)
                    // $this->shareSuccess($data['message']);

                    // Fire emoji confetti
                    $this->dispatch('confetti', [
                        'options' => [
                            'emoji' => '🎊',
                            'particleCount' => 40,
                            'spread' => 360,
                            'scalar' => 2,
                        ],
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Success Shared! 🎊')
                        ->body('Your success has been shared with the team.')
                        ->send();
                }),

            // Custom celebration button
            ConfettiAction::make('celebrate')
                ->label('Just Celebrate!')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->randomDirection()
                ->colors(['#3b82f6', '#8b5cf6', '#ec4899', '#f97316'])
                ->particleCount(150),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Project saved successfully!';
    }

    protected function afterSave(): void
    {
        // Fire confetti after successful save
        $this->dispatch('confetti', [
            'options' => [
                'particleCount' => 100,
                'spread' => 70,
                'origin' => ['y' => 0.6],
                'colors' => ['#10b981', '#3b82f6', '#8b5cf6'],
            ],
        ]);
    }

    /**
     * Example method showing confetti on validation error prevention
     */
    protected function onValidationError(\Illuminate\Validation\ValidationException $exception): void
    {
        parent::onValidationError($exception);

        // Optional: You could add a different visual effect on errors
        // For example, a subtle shake animation instead of confetti
    }

    /**
     * Custom method to celebrate team achievements
     */
    public function celebrateTeam(): void
    {
        // Fire snow effect for team celebration
        $this->dispatch('confetti', [
            'preset' => 'snow',
            'options' => [
                'duration' => 10000,
                'colors' => ['#ffffff', '#e0f2fe', '#bae6fd', '#7dd3fc'],
            ],
        ]);

        Notification::make()
            ->info()
            ->title('Team Celebration! ❄️')
            ->body('Celebrating the amazing teamwork!')
            ->duration(5000)
            ->send();
    }

    /**
     * Custom method for deadline achievement
     */
    public function celebrateDeadline(): void
    {
        // Fire side cannons
        $this->dispatch('confetti', [
            'preset' => 'sideCannons',
            'options' => [
                'duration' => 3000,
                'particleCount' => 5,
                'colors' => ['#ef4444', '#f97316', '#eab308', '#84cc16'],
            ],
        ]);

        Notification::make()
            ->success()
            ->title('Deadline Met! 🎯')
            ->body('Project completed before the deadline!')
            ->duration(5000)
            ->send();
    }
}
