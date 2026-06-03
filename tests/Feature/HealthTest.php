<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class HealthTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_update_synchronizes_to_both_tables(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/health/update', [
                'heart_rate'   => 80,
                'spo2'         => 98,
                'weight'       => 70.5,
                'height'       => 175,
                'water_intake' => 2.5,
                'sleep_hours'  => 8.0,
                'steps'        => 5000,
                'calories'     => 1800,
            ]);

        $response->assertSessionHasNoErrors();

        // Assert users table is updated
        $user->refresh();
        $this->assertEquals(80, $user->heart_rate);
        $this->assertEquals(98, $user->spo2);
        $this->assertEquals(70.5, $user->weight);
        $this->assertEquals(175, $user->height);
        $this->assertEquals(2.5, $user->water_intake);
        $this->assertEquals(8.0, $user->sleep_hours);
        $this->assertEquals(5000, $user->steps);
        $this->assertEquals(1800, $user->calories);

        // Assert health_metrics table has today's record updated
        $this->assertDatabaseHas('health_metrics', [
            'user_id'      => $user->id,
            'recorded_at'  => now()->toDateString(),
            'heart_rate'   => 80,
            'spo2'         => 98,
            'weight'       => 70.5,
            'water_intake' => 2.5,
            'sleep_hours'  => 8.0,
            'steps'        => 5000,
            'calories'     => 1800,
        ]);
    }

    public function test_pair_submit_from_qr_synchronizes_to_both_tables(): void
    {
        $user = User::factory()->create();
        $token = 'test-token-123';

        // Set token in Cache
        Cache::put("qr_pair_{$token}", [
            'user_id' => $user->id,
            'data'    => null,
        ], 360);

        $response = $this->post("/pair/{$token}", [
            'heart_rate'   => 85,
            'spo2'         => 99,
            'weight'       => 72.0,
            'height'       => 178,
            'water_intake' => 3.0,
            'sleep_hours'  => 7.5,
        ]);

        $response->assertOk();

        // Assert users table is updated
        $user->refresh();
        $this->assertEquals(85, $user->heart_rate);
        $this->assertEquals(99, $user->spo2);
        $this->assertEquals(72.0, $user->weight);
        $this->assertEquals(178, $user->height);
        $this->assertEquals(3.0, $user->water_intake);
        $this->assertEquals(7.5, $user->sleep_hours);

        // Assert health_metrics table has today's record updated (excluding height)
        $this->assertDatabaseHas('health_metrics', [
            'user_id'      => $user->id,
            'recorded_at'  => now()->toDateString(),
            'heart_rate'   => 85,
            'spo2'         => 99,
            'weight'       => 72.0,
            'water_intake' => 3.0,
            'sleep_hours'  => 7.5,
        ]);
    }
}
