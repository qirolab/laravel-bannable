<?php

namespace Hkp22\Tests\Laravel\Bannable\Unit;

use Carbon\Carbon;
use Hkp22\Laravel\Bannable\Models\Ban;
use Hkp22\Tests\Laravel\Bannable\TestCase;
use Hkp22\Tests\Laravel\Bannable\Stubs\Models\User;

class BanObserverTest extends TestCase
{
    /** @test */
    public function it_can_set_banned_flag_to_owner_model_on_create()
    {
        $user = factory(User::class)->create([
            'banned_at' => null,
        ]);

        $user->bans()->create([]);

        $user->refresh();

        $this->assertNotNull($user->banned_at);
    }

    /** @test */
    public function it_can_set_created_by_id_and_type_before_creating()
    {
        $bannedBy = factory(User::class)->create();

        $user = factory(User::class)->create([
            'banned_at' => null,
        ]);

        $this->actingAs($bannedBy);

        $ban = $user->ban();

        $this->assertInstanceOf(User::class, $ban->createdBy);

        $this->assertEquals($bannedBy->getKey(), $ban->createdBy->getKey());
    }

    /** @test */
    public function it_can_unset_banned_flag_to_owner_model_on_delete()
    {
        $user = factory(User::class)->create([
            'banned_at' => Carbon::now(),
        ]);

        factory(Ban::class)->create([
            'bannable_id' => $user->getKey(),
            'bannable_type' => $user->getMorphClass(),
        ]);

        $this->assertNotNull($user->banned_at);

        $user->unban();

        $user->refresh();

        $this->assertNull($user->banned_at);
    }
}
