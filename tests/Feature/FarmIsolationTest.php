<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Farm;
use App\Models\Crop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FarmIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_farmer_cannot_see_another_farms_crop()
    {
        $farm1 = Farm::factory()->create();
        $farm2 = Farm::factory()->create();
        $farmer = User::factory()->create(['farm_id' => $farm1->id, 'role' => 'farmer']);
        $crop = Crop::factory()->create(['farm_id' => $farm2->id]);

        $this->actingAs($farmer)
            ->get(route('crops.edit', $crop))
            ->assertStatus(403); // forbidden
    }
}
