<?php

namespace Qirolab\Tests\Laravel\Bannable\Unit;

use Illuminate\Http\Request;
use Qirolab\Laravel\Bannable\Middleware\ForbidBannedUser;
use Qirolab\Tests\Laravel\Bannable\Stubs\Models\User;
use Qirolab\Tests\Laravel\Bannable\TestCase;

class BanMiddlewareTest extends TestCase
{
    /** @test */
    public function it_redirected_back_to_banned_user()
    {
        $user = factory(User::class)->create();

        $user->ban();

        $this->actingAs($user->fresh());

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
