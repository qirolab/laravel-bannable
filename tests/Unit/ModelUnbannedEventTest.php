<?php

namespace Qirolab\Tests\Laravel\Bannable\Unit;

use Qirolab\Laravel\Bannable\Models\Ban;
use Qirolab\Tests\Laravel\Bannable\TestCase;
use Qirolab\Laravel\Bannable\Events\ModelWasUnbanned;

class ModelUnbannedEventTest extends TestCase
{
    /** @test */
    public function it_can_fire_event_on_helper_call()
    {
        $this->expectsEvents(ModelWasUnbanned::class);

        $ban = factory(Ban::class)->create();

        $ban->bannable->unban();
    }

    /** @test */
    public function it_can_fire_event_on_relation_delete()
    {
        $this->expectsEvents(ModelWasUnbanned::class);

        $ban = factory(Ban::class)->create();

        $ban->delete();
    }
}
