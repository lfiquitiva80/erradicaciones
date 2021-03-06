<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TypeController
 */
class TypeControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view()
    {
        $types = Type::factory()->count(3)->create();

        $response = $this->get(route('type.index'));

        $response->assertOk();
        $response->assertViewIs('type.index');
        $response->assertViewHas('types');
    }


    /**
     * @test
     */
    public function create_displays_view()
    {
        $response = $this->get(route('type.create'));

        $response->assertOk();
        $response->assertViewIs('type.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TypeController::class,
            'store',
            \App\Http\Requests\TypeStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects()
    {
        $tipo = $this->faker->word;
        $observaciones = $this->faker->word;

        $response = $this->post(route('type.store'), [
            'tipo' => $tipo,
            'observaciones' => $observaciones,
        ]);

        $types = Type::query()
            ->where('tipo', $tipo)
            ->where('observaciones', $observaciones)
            ->get();
        $this->assertCount(1, $types);
        $type = $types->first();

        $response->assertRedirect(route('type.index'));
        $response->assertSessionHas('type.id', $type->id);
    }


    /**
     * @test
     */
    public function show_displays_view()
    {
        $type = Type::factory()->create();

        $response = $this->get(route('type.show', $type));

        $response->assertOk();
        $response->assertViewIs('type.show');
        $response->assertViewHas('type');
    }


    /**
     * @test
     */
    public function edit_displays_view()
    {
        $type = Type::factory()->create();

        $response = $this->get(route('type.edit', $type));

        $response->assertOk();
        $response->assertViewIs('type.edit');
        $response->assertViewHas('type');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TypeController::class,
            'update',
            \App\Http\Requests\TypeUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects()
    {
        $type = Type::factory()->create();
        $tipo = $this->faker->word;
        $observaciones = $this->faker->word;

        $response = $this->put(route('type.update', $type), [
            'tipo' => $tipo,
            'observaciones' => $observaciones,
        ]);

        $type->refresh();

        $response->assertRedirect(route('type.index'));
        $response->assertSessionHas('type.id', $type->id);

        $this->assertEquals($tipo, $type->tipo);
        $this->assertEquals($observaciones, $type->observaciones);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects()
    {
        $type = Type::factory()->create();

        $response = $this->delete(route('type.destroy', $type));

        $response->assertRedirect(route('type.index'));

        $this->assertDeleted($type);
    }
}
