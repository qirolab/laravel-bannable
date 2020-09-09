<?php

namespace Qirolab\Tests\Laravel\Bannable\Unit;

use Carbon\Carbon;
use Qirolab\Tests\Laravel\Bannable\Stubs\Models\User;
use Qirolab\Tests\Laravel\Bannable\Stubs\Models\UserModelWithDisabledBannedScope;
use Qirolab\Tests\Laravel\Bannable\TestCase;

class BannedScopeTest extends TestCase
{
    /** @test */
    public function it_can_get_all_unbanned_models_by_default()
    {
        $this->createUser(User::class, [
            'banned_at' => Carbon::now()->subDay(),
        ], 2);

        $this->createUser(User::class, [
            'banned_at' => null,
        ], 3);

        $entities = User::all();

        $this->assertCount(3, $entities);
    }

    /** @test */
    public function it_can_get_models_without_banned()
    {
        $this->createUser(User::class, [
            'banned_at' => Carbon::now()->subDay(),
        ], 2);

        $this->createUser(User::class, [
            'banned_at' => null,
        ], 3);

        $entities = User::withoutBanned()->get();

        $this->assertCount(3, $entities);
    }

    /** @test */
    public function it_can_get_models_with_banned()
    {
        $this->createUser(User::class, [
            'banned_at' => Carbon::now()->subDay(),
        ], 2);

        $this->createUser(User::class, [
            'banned_at' => null,
        ], 3);

        $entities = User::withBanned()->get();

        $this->assertCount(5, $entities);
    }

    /** @test */
    public function it_can_get_only_banned_models()
    {
        $this->createUser(User::class, [
            'banned_at' => Carbon::now()->subDay(),
        ], 2);

        $this->createUser(User::class, [
            'banned_at' => null,
        ], 3);

        $entities = User::onlyBanned()->get();

        $this->assertCount(2, $entities);
    }

    /** @test */
    public function it_can_disable_banned_at_default_scope()
    {
        $this->createUser(User::class, [
            'banned_at' => Carbon::now()->subDay(),
        ], 2);

        $this->createUser(User::class, [
            'banned_at' => null,
        ], 3);

        $entities = UserModelWithDisabledBannedScope::all();

        $this->assertCount(5, $entities);
    }
}