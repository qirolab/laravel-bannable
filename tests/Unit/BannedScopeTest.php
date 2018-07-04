<?php

namespace Hkp22\Tests\Laravel\Bannable\Unit;

use Carbon\Carbon;
use Hkp22\Tests\Laravel\Bannable\TestCase;
use Hkp22\Tests\Laravel\Bannable\Stubs\Models\User;
use Hkp22\Tests\Laravel\Bannable\Stubs\Models\UserModelWithDisabledBannedScope;

class BannedScopeTest extends TestCase
{
    /** @test */
    public function it_can_get_all_unbanned_models_by_default()
    {
        factory(User::class, 2)->create([
            'banned_at' => Carbon::now()->subDay(),
        ]);

        factory(User::class, 3)->create([
            'banned_at' => null,
        ]);

        $entities = User::all();

        $this->assertCount(3, $entities);
    }

    /** @test */
    public function it_can_get_models_without_banned()
    {
        factory(User::class, 2)->create([
            'banned_at' => Carbon::now()->subDay(),
        ]);

        factory(User::class, 3)->create([
            'banned_at' => null,
        ]);

        $entities = User::withoutBanned()->get();

        $this->assertCount(3, $entities);
    }

    /** @test */
    public function it_can_get_models_with_banned()
    {
        factory(User::class, 2)->create([
            'banned_at' => Carbon::now()->subDay(),
        ]);

        factory(User::class, 3)->create([
            'banned_at' => null,
        ]);

        $entities = User::withBanned()->get();

        $this->assertCount(5, $entities);
    }

    /** @test */
    public function it_can_get_only_banned_models()
    {
        factory(User::class, 2)->create([
             'banned_at' => Carbon::now()->subDay(),
         ]);

        factory(User::class, 3)->create([
             'banned_at' => null,
         ]);

        $entities = User::onlyBanned()->get();

        $this->assertCount(2, $entities);
    }

    /** @test */
    public function it_can_disable_banned_at_default_scope()
    {
        factory(User::class, 3)->create([
            'banned_at' => Carbon::now()->subDay(),
        ]);

        factory(User::class, 2)->create([
            'banned_at' => null,
        ]);

        $entities = UserModelWithDisabledBannedScope::all();

        $this->assertCount(5, $entities);
    }
}
