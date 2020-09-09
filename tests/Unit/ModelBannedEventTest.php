<?php

namespace Qirolab\Tests\Laravel\Bannable\Unit;

use Qirolab\Laravel\Bannable\Events\ModelWasBanned;
use Qirolab\Tests\Laravel\Bannable\Stubs\Models\User;
use Qirolab\Tests\Laravel\Bannable\TestCase;

class ModelBannedEventTest extends TestCase
{
    /** @test */
    public function it_can_fire_event_on_helper_call()
    {
        $this->expectsEvents(ModelWasBanned::class);

        $entity = $this->createUser(User::class);;

        $entity->ban();
    }

    /** @test */
    public function it_can_fire_event_on_relation_create()
    {
        $this->expectsEvents(ModelWasBanned::class);

        $entity = $this->createUser(User::class);;

        $entity->bans()->create();
    }
}