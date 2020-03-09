<?php

namespace Qirolab\Tests\Laravel\Bannable\Unit;

use Carbon\Carbon;
use Qirolab\Laravel\Bannable\Models\Ban;
use Qirolab\Tests\Laravel\Bannable\Stubs\Models\User;
use Qirolab\Tests\Laravel\Bannable\TestCase;

class BanModelTest extends TestCase
{
    /** @test */
    public function comment_can_be_filled_and_no_MassAssignmentException_error()
    {
        $ban = new Ban([
            'comment' => 'You are ban!',
        ]);

        $this->assertEquals('You are ban!', $ban->comment);
    }

    /** @test */
    public function expired_at_can_be_filled_and_no_MassAssignmentException_error()
    {
        $expiredAt = '2019-01-01 00:00:00';

        $ban = new Ban([
            'expired_at' => $expiredAt,
        ]);

        $this->assertEquals($expiredAt, $ban->expired_at);

        // Cast expired_at
        $this->assertInstanceOf(Carbon::class, $ban->expired_at);
    }

    /** @test */
    public function it_can_has_ban_creator()
    {
        $bannedBy = factory(User::class)->create();

        $ban = factory(Ban::class)->create([
            'created_by_id' => $bannedBy->getKey(),
            'created_by_type' => $bannedBy->getMorphClass(),
        ]);

        $this->assertInstanceOf(User::class, $ban->createdBy);
    }

    /** @test */
    public function ban_model_can_be_created_with_expire_carbon_date()
    {
        $expiredAt = Carbon::now();

        $ban = new Ban([
            'expired_at' => $expiredAt,
        ]);

        $this->assertEquals($expiredAt, $ban->expired_at);
    }

    /** @test */
    public function it_can_make_model_with_expire_relative_date()
    {
        $ban = new Ban([
            'expired_at' => '+1 year',
        ]);

        $this->assertEquals(Carbon::parse('+1 year')->format('Y'), $ban->expired_at->format('Y'));
    }

    /** @test */
    public function it_can_has_bannable_model()
    {
        $user = factory(User::class)->create();

        $ban = factory(Ban::class)->create([
            'bannable_id' => $user->getKey(),
            'bannable_type' => $user->getMorphClass(),
        ]);

        $this->assertInstanceOf(User::class, $ban->bannable);
    }

    /** @test */
    public function it_can_delete_all_expired_bans()
    {
        factory(Ban::class, 3)->create([
            'expired_at' => Carbon::now()->subMonth(),
        ]);

        factory(Ban::class, 4)->create([
            'expired_at' => Carbon::now()->addMonth(),
        ]);

        Ban::deleteExpired();

        $this->assertCount(4, Ban::all());
    }

    /** @test */
    public function it_can_scope_bannable_models()
    {
        $user1 = factory(User::class)->create();

        factory(Ban::class, 4)->create([
            'bannable_id' => $user1->getKey(),
            'bannable_type' => $user1->getMorphClass(),
        ]);

        $user2 = factory(User::class)->create();

        factory(Ban::class, 2)->create([
            'bannable_id' => $user2->getKey(),
            'bannable_type' => $user2->getMorphClass(),
        ]);

        $bannableModels = Ban::whereBannable($user1)->get();
        $this->assertCount(4, $bannableModels);

        $bannableModels = Ban::whereBannable($user2)->get();
        $this->assertCount(2, $bannableModels);
    }
}
