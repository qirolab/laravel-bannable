<?php

namespace Qirolab\Tests\Laravel\Bannable\Unit;

use Illuminate\Support\Facades\Event;
use Qirolab\Laravel\Bannable\Events\ModelWasUnbanned;
use Qirolab\Tests\Laravel\Bannable\TestCase;

class ModelUnbannedEventTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Event::fake([ModelWasUnbanned::class]);
    }

    /** @test */
    public function it_can_fire_event_on_helper_call()
    {
        $ban = $this->createBan();

        $ban->bannable->unban();

        Event::assertDispatched(ModelWasUnbanned::class);
    }

    /** @test */
    public function it_can_fire_event_on_relation_delete()
    {
        $ban = $this->createBan();

        $ban->delete();

        Event::assertDispatched(ModelWasUnbanned::class);
    }
}
