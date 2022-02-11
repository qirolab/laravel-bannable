<?php

namespace Qirolab\Tests\Laravel\Bannable\Unit;

use Illuminate\Foundation\Testing\Concerns\MocksApplicationServices;
use Qirolab\Laravel\Bannable\Events\ModelWasUnbanned;
use Qirolab\Tests\Laravel\Bannable\TestCase;

class ModelUnbannedEventTest extends TestCase
{
    use MocksApplicationServices;

    /** @test */
    public function it_can_fire_event_on_helper_call()
    {
        $this->expectsEvents(ModelWasUnbanned::class);

        $ban = $this->createBan();

        $ban->bannable->unban();
    }

    /** @test */
    public function it_can_fire_event_on_relation_delete()
    {
        $this->expectsEvents(ModelWasUnbanned::class);

        $ban = $this->createBan();

        $ban->delete();
    }
}
