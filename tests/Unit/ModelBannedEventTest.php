<?php

namespace Qirolab\Tests\Laravel\Bannable\Unit;

use Illuminate\Support\Facades\Event;
use Qirolab\Laravel\Bannable\Events\ModelWasBanned;
use Qirolab\Tests\Laravel\Bannable\Stubs\Models\User;
use Qirolab\Tests\Laravel\Bannable\TestCase;

class ModelBannedEventTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Event::fake([ModelWasBanned::class]);
    }

    /** @test */
    public function it_can_fire_event_on_helper_call()
    {
        $entity = $this->createUser(User::class);

        $entity->ban();

        Event::assertDispatched(ModelWasBanned::class);
    }

    /** @test */
    public function it_can_fire_event_on_relation_create()
    {
        $entity = $this->createUser(User::class);

        $entity->bans()->create();

        Event::assertDispatched(ModelWasBanned::class);
    }
}
