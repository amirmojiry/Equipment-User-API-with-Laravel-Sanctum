<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\User;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PhpParser\Node\Expr\BinaryOp\Equal;
use Tests\TestCase;

class EquipmentTest extends TestCase
{
    use DatabaseMigrations;

    private $base_url = '/api/equipments/';
    
    public function test_unauthenticated_user_can_see_all_equipment_without_internal_notes()
    {
        $equipment = Equipment::factory()->create();

        $response = $this->get($this->base_url);

        $response->assertSuccessful()
            ->assertSee($equipment->id)
            ->assertSee($equipment->name)
            ->assertSee($equipment->quantity)
            ->assertDontSee($equipment->internal_notes);
    }

    public function test_unauthenticated_user_can_see_an_equipment_without_internal_notes()
    {
        $equipment = Equipment::factory()->create();

        $response = $this->get($this->base_url.$equipment->id);

        $response->assertSuccessful()
            ->assertSee($equipment->id)
            ->assertSee($equipment->name)
            ->assertSee($equipment->quantity)
            ->assertDontSee($equipment->internal_notes);
    }

    public function test_authenticated_user_can_see_all_equipment_with_internal_notes()
    {
        $this->actingAs(User::factory()->create());

        $equipment = Equipment::factory()->create();

        $response = $this->get($this->base_url);

        $response->assertSee($equipment->internal_notes);
    }

    public function test_authenticated_user_can_see_equipment_with_internal_notes()
    {
        $this->actingAs(User::factory()->create());

        $equipment = Equipment::factory()->create();

        $response = $this->get($this->base_url.$equipment->id);

        $response->assertSee($equipment->internal_notes);
    }

    public function test_authenticated_user_can_create_an_equipment()
    {
        $this->actingAs(User::factory()->create());

        $equipment = Equipment::factory()->make();

        $this->post($this->base_url, $equipment->toArray());

        $this->assertAuthenticated()
            ->assertEquals(1, Equipment::all()->count());
    }

    public function test_unauthenticated_user_cannot_create_an_equipment()
    {
        $equipment = Equipment::factory()->make();

        $response = $this->post($this->base_url, $equipment->toArray());
        
        $this->assertGuest()
            ->assertEquals(0, Equipment::all()->count());
    }

    public function test_name_is_required_in_creating_an_equipment()
    {
        $this->actingAs(User::factory()->create());

        $equipment = Equipment::factory()->make(['name' => null]);

        $this->post($this->base_url, $equipment->toArray());

        $this->assertEquals(0, Equipment::all()->count());
    }

    public function test_quantity_must_be_number_in_creating_an_equipment()
    {
        $this->actingAs(User::factory()->create());

        $equipment = Equipment::factory()->make(['quantity' => "string"]);

        $this->post($this->base_url, $equipment->toArray());

        $this->assertEquals(0, Equipment::all()->count());
    }

    public function test_authenticated_user_can_update_an_equipment()
    {
        $this->actingAs(User::factory()->create());

        $equipment = Equipment::factory()->create();

        $equipment->name = "new name";

        $this->put($this->base_url.$equipment->id, $equipment->toArray());

        $this->assertDatabaseHas('equipment', [
            'id' => $equipment->id,
            'name' => 'new name',
        ]);
    }

    public function test_unauthenticated_user_cannot_update_an_equipment()
    {
        $equipment = Equipment::factory()->create();

        $equipment->name = "new name";

        $this->put($this->base_url.$equipment->id, $equipment->toArray());

        $this->assertDatabaseMissing('equipment', [
            'id' => $equipment->id,
            'name' => "new name",
        ]);
    }

    public function test_authenticated_user_can_delete_an_equipment()
    {
        $this->actingAs(User::factory()->create());

        $equipment = Equipment::factory()->create();

        $this->delete($this->base_url.$equipment->id);

        $this->assertDatabaseMissing('equipment', [
            'id' => $equipment->id,
        ]);
    }
    
    public function test_unauthenticated_user_cannot_delete_an_equipment()
    {
        $equipment = Equipment::factory()->create();

        $this->delete($this->base_url.$equipment->id);

        $this->assertDatabaseHas('equipment', [
            'id' => $equipment->id,
        ]);
    }
}
