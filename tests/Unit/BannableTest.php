<?php

namespace Qirolab\Tests\Laravel\Bannable\Unit;

use Carbon\Carbon;
use Qirolab\Laravel\Bannable\Models\Ban;
use Qirolab\Tests\Laravel\Bannable\Stubs\Models\User;
use Qirolab\Tests\Laravel\Bannable\TestCase;

class BannableTest extends TestCase
{
    /** @test */
    public function model_can_has_related_ban()
    {
        $user = $this->createUser(User::class);

        $ban = $this->createBan([
            'bannable_id' => $user->getKey(),
            'bannable_type' => $user->getMorphClass(),
        ]);

        $assertBan = $user->bans->first();

        $this->assertInstanceOf(Ban::class, $assertBan);

        $this->assertEquals($ban->getKey(), $assertBan->getKey());
    }

    /** @test */
    public function model_can_has_many_related_bans()
    {
        $user = $this->createUser(User::class);

        $this->createBan([
            'bannable_id' => $user->getKey(),
            'bannable_type' => $user->getMorphClass(),
        ], 2);

        $this->assertCount(2, $user->bans);
    }

    /** @test */
    public function model_can_ban()
    {
        $user = $this->createUser(User::class, [
            'banned_at' => null,
        ]);

        $user->ban();

        $this->assertNotNull($user->fresh()->banned_at);
    }

    /** @test */
    public function model_can_unban()
    {
        $user = $this->createUser(User::class, [
            'banned_at' => Carbon::now(),
        ]);

        $this->createBan([
            'bannable_id' => $user->getKey(),
            'bannable_type' => $user->getMorphClass(),
        ]);

        $this->assertNotNull($user->banned_at);
        $this->assertCount(1, $user->bans);

        $user->unban();

        $user->refresh();

        $this->assertCount(0, $user->bans);
        $this->assertNull($user->banned_at);
    }

    /** @test */
    public function model_can_soft_delete_ban_on_unban()
    {
        $user = $this->createUser(User::class, [
            'banned_at' => Carbon::now(),
        ]);

        $this->createBan([
            'bannable_id' => $user->getKey(),
            'bannable_type' => $user->getMorphClass(),
        ]);

        $this->assertCount(1, $user->bans);

        $user->unban();

        // without Trash
        $this->assertCount(0, $user->fresh()->bans);

        // with Trash
        $this->assertCount(1, $user->bans()->withTrashed()->get());
    }

    /** @test */
    public function model_can_return_ban_model()
    {
        $user = $this->createUser(User::class, [
            'banned_at' => null,
        ]);

        $ban = $user->ban();

        $this->assertInstanceOf(Ban::class, $ban);
    }

    /** @test */
    public function model_can_has_empty_banned_by()
    {
        $user = $this->createUser(User::class, [
            'banned_at' => null,
        ]);

        $ban = $user->ban();

        $this->assertNull($ban->banned_by);
    }

    /** @test */
    public function model_can_has_current_user_as_banned_by()
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
    public function model_can_ban_via_ban_relation_create()
    {
        $user = $this->createUser(User::class, [
            'banned_at' => null,
        ]);

        $ban = $user->bans()->create();

        $this->assertInstanceOf(Ban::class, $ban);

        $this->assertTrue($user->fresh()->isBanned());
    }

    /** @test */
    public function model_can_ban_with_comment()
    {
        $user = $this->createUser(User::class, [
            'banned_at' => null,
        ]);

        $ban = $user->ban([
            'comment' => 'You are banned!',
        ]);

        $this->assertEquals('You are banned!', $ban->comment);
    }

    /** @test */
    public function model_can_ban_with_expiration_date()
    {
        $user = $this->createUser(User::class, [
            'banned_at' => null,
        ]);

        $ban = $user->ban([
            'expired_at' => '2086-03-28 00:00:00',
        ]);

        $this->assertEquals('2086-03-28 00:00:00', $ban->expired_at);
    }
}