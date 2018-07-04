<?php

namespace Hkp22\Tests\Laravel\Bannable\Unit;

use Hkp22\Tests\Laravel\Bannable\TestCase;
use Hkp22\Laravel\Bannable\Events\ModelWasBanned;
use Hkp22\Tests\Laravel\Bannable\Stubs\Models\User;

class ModelBannedEventTest extends TestCase
{
    /** @test */
    public function it_can_fire_event_on_helper_call()
    {
        $this->expectsEvents(ModelWasBanned::class);

        $entity = factory(User::class)->create();

        $entity->ban();
    }

    /** @test */
    public function it_can_fire_event_on_relation_create()
    {
        $this->expectsEvents(ModelWasBanned::class);

        $entity = factory(User::class)->create();

        $entity->bans()->create();
    }
}
