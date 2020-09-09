<?php

namespace Qirolab\Tests\Laravel\Bannable\Unit;

use Carbon\Carbon;
use Qirolab\Tests\Laravel\Bannable\Stubs\Models\User;
use Qirolab\Tests\Laravel\Bannable\TestCase;

class BanObserverTest extends TestCase
{
    /** @test */
    public function it_can_set_banned_flag_to_owner_model_on_create()
    {
        $user = $this->createUser(User::class, [
            'banned_at' => null,
        ]);

        $user->bans()->create([]);

        $this->assertNotNull($user->fresh()->banned_at);
    }

    /** @test */
    public function it_can_set_created_by_id_and_type_before_creating()
    {
        $bannedBy = $this->createUser(User::class);

        $user = $this->createUser(User::class, [
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
        $user = $this->createUser(User::class, [
            'banned_at' => Carbon::now(),
        ]);

        $this->createBan([
            'bannable_id' => $user->getKey(),
            'bannable_type' => $user->getMorphClass(),
        ]);

        $this->assertNotNull($user->banned_at);

        $user->unban();

        $this->assertNull($user->fresh()->banned_at);
    }
}
