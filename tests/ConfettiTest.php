<?php

namespace YourVendor\FilamentConfetti\Tests;

use Filament\Actions\Testing\TestsActions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;
use YourVendor\FilamentConfetti\Actions\ConfettiAction;
use YourVendor\FilamentConfetti\Components\Confetti;
use YourVendor\FilamentConfetti\FilamentConfettiServiceProvider;

class ConfettiTest extends Orchestra
{
    use RefreshDatabase;
    use TestsActions;

    protected function getPackageProviders($app): array
    {
        return [
            FilamentConfettiServiceProvider::class,
        ];
    }

    /** @test */
    public function it_can_create_a_basic_confetti_action(): void
    {
        $action = ConfettiAction::make('test')
            ->basic();

        $this->assertInstanceOf(ConfettiAction::class, $action);
        $config = $action->getConfettiConfig();
        $this->assertArrayHasKey('options', $config);
        $this->assertEquals(100, $config['options']['particleCount']);
    }

    /** @test */
    public function it_can_create_fireworks_preset(): void
    {
        $action = ConfettiAction::make('fireworks')
            ->fireworks(duration: 5000);

        $config = $action->getConfettiConfig();
        $this->assertEquals('fireworks', $config['preset']);
        $this->assertEquals(5000, $config['options']['duration']);
    }

    /** @test */
    public function it_can_create_snow_preset(): void
    {
        $action = ConfettiAction::make('snow')
            ->snow(duration: 10000);

        $config = $action->getConfettiConfig();
        $this->assertEquals('snow', $config['preset']);
        $this->assertEquals(10000, $config['options']['duration']);
    }

    /** @test */
    public function it_can_create_stars_preset(): void
    {
        $action = ConfettiAction::make('stars')
            ->stars();

        $config = $action->getConfettiConfig();
        $this->assertArrayHasKey('shapes', $config['options']);
        $this->assertEquals(['star'], $config['options']['shapes']);
    }

    /** @test */
    public function it_can_set_custom_colors(): void
    {
        $colors = ['#ff0000', '#00ff00', '#0000ff'];
        $action = ConfettiAction::make('custom')
            ->basic()
            ->colors($colors);

        $config = $action->getConfettiConfig();
        $this->assertEquals($colors, $config['options']['colors']);
    }

    /** @test */
    public function it_can_set_particle_count(): void
    {
        $action = ConfettiAction::make('custom')
            ->basic()
            ->particleCount(200);

        $config = $action->getConfettiConfig();
        $this->assertEquals(200, $config['options']['particleCount']);
    }

    /** @test */
    public function it_can_set_origin(): void
    {
        $origin = ['x' => 0.3, 'y' => 0.7];
        $action = ConfettiAction::make('custom')
            ->basic()
            ->origin($origin);

        $config = $action->getConfettiConfig();
        $this->assertEquals($origin, $config['options']['origin']);
    }

    /** @test */
    public function it_can_use_emoji(): void
    {
        $action = ConfettiAction::make('hearts')
            ->emoji('❤️');

        $config = $action->getConfettiConfig();
        $this->assertEquals('❤️', $config['options']['emoji']);
    }

    /** @test */
    public function it_can_use_custom_shape(): void
    {
        $path = 'M0 10 L5 0 L10 10z';
        $action = ConfettiAction::make('triangles')
            ->customShape($path);

        $config = $action->getConfettiConfig();
        $this->assertEquals($path, $config['options']['customShape']);
    }

    /** @test */
    public function it_can_set_auto_fire(): void
    {
        $action = ConfettiAction::make('auto')
            ->basic()
            ->autoFire();

        $this->assertTrue($action->shouldAutoFire());
    }

    /** @test */
    public function it_can_set_delay(): void
    {
        $action = ConfettiAction::make('delayed')
            ->basic()
            ->autoFire()
            ->delay(1000);

        $this->assertEquals(1000, $action->getDelay());
    }

    /** @test */
    public function it_can_create_confetti_component(): void
    {
        $component = Confetti::make()
            ->fireworks();

        $this->assertInstanceOf(Confetti::class, $component);
        $config = $component->getConfettiConfig();
        $this->assertEquals('fireworks', $config['preset']);
    }

    /** @test */
    public function it_can_set_trigger_event(): void
    {
        $component = Confetti::make()
            ->basic()
            ->trigger('celebrate');

        $this->assertEquals('celebrate', $component->getTrigger());
    }

    /** @test */
    public function it_evaluates_closures_in_options(): void
    {
        $action = ConfettiAction::make('dynamic')
            ->randomDirection();

        $config = $action->getConfettiConfig();

        // Angle and spread should be evaluated from closures
        $this->assertIsInt($config['options']['angle']);
        $this->assertIsInt($config['options']['spread']);
        $this->assertIsInt($config['options']['particleCount']);
    }

    /** @test */
    public function it_can_chain_multiple_configuration_methods(): void
    {
        $action = ConfettiAction::make('chained')
            ->basic()
            ->particleCount(150)
            ->colors(['#ff0000', '#00ff00'])
            ->origin(['x' => 0.5, 'y' => 0.6])
            ->autoFire()
            ->delay(500);

        $config = $action->getConfettiConfig();

        $this->assertEquals(150, $config['options']['particleCount']);
        $this->assertEquals(['#ff0000', '#00ff00'], $config['options']['colors']);
        $this->assertEquals(['x' => 0.5, 'y' => 0.6], $config['options']['origin']);
        $this->assertTrue($action->shouldAutoFire());
        $this->assertEquals(500, $action->getDelay());
    }

    /** @test */
    public function it_can_override_preset_defaults(): void
    {
        $action = ConfettiAction::make('custom-fireworks')
            ->fireworks(duration: 3000, options: [
                'particleCount' => 75,
                'colors' => ['#ff0000'],
            ]);

        $config = $action->getConfettiConfig();

        $this->assertEquals('fireworks', $config['preset']);
        $this->assertEquals(3000, $config['options']['duration']);
        $this->assertEquals(75, $config['options']['particleCount']);
        $this->assertEquals(['#ff0000'], $config['options']['colors']);
    }

    /** @test */
    public function it_can_create_side_cannons_preset(): void
    {
        $action = ConfettiAction::make('cannons')
            ->sideCannons(duration: 3000);

        $config = $action->getConfettiConfig();
        $this->assertEquals('sideCannons', $config['preset']);
    }

    /** @test */
    public function it_can_create_realistic_preset(): void
    {
        $action = ConfettiAction::make('realistic')
            ->realistic();

        $config = $action->getConfettiConfig();
        $this->assertEquals('realistic', $config['preset']);
    }

    /** @test */
    public function it_can_create_school_preset(): void
    {
        $action = ConfettiAction::make('school')
            ->school(duration: 3000);

        $config = $action->getConfettiConfig();
        $this->assertEquals('school', $config['preset']);
    }

    /** @test */
    public function it_can_use_custom_confetti_options(): void
    {
        $customOptions = [
            'particleCount' => 250,
            'spread' => 180,
            'startVelocity' => 60,
            'decay' => 0.95,
            'gravity' => 1.2,
            'drift' => 0.5,
            'ticks' => 300,
            'origin' => ['x' => 0.5, 'y' => 0.3],
            'colors' => ['#ff6b6b', '#4ecdc4'],
            'shapes' => ['circle', 'square'],
            'scalar' => 1.5,
        ];

        $action = ConfettiAction::make('custom')
            ->confettiOptions($customOptions);

        $config = $action->getConfettiConfig();

        foreach ($customOptions as $key => $value) {
            $this->assertEquals($value, $config['options'][$key]);
        }
    }
}
