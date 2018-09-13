<?php

namespace Hkp22\Tests\Laravel\Bannable\Unit;

use Illuminate\Http\Request;
use Hkp22\Tests\Laravel\Bannable\TestCase;
use Hkp22\Tests\Laravel\Bannable\Stubs\Models\User;
use Hkp22\Laravel\Bannable\Middleware\ForbidBannedUser;

class BanMiddlewareTest extends TestCase
{
    /** @test */
    public function it_redirected_back_to_banned_user()
    {
        $user = factory(User::class)->create();

        $user->ban();

        $this->actingAs($user->refresh());

        $request = Request::create('/test', 'GET');

        $middleware = new ForbidBannedUser(auth());

        $response = $middleware->handle($request, function () {
        });

        $this->assertEquals($response->getStatusCode(), 302);
    }

    /** @test */
    public function it_cannot_redirect_to_non_banned_user()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $request = Request::create('/test', 'GET');

        $middleware = new ForbidBannedUser(auth());

        $response = $middleware->handle($request, function () {
        });

        $this->assertEquals($response, null);
    }
}
