<?php

namespace Qirolab\Tests\Laravel\Bannable\Unit;

use Illuminate\Http\Request;
use Qirolab\Laravel\Bannable\Exceptions\BannableTraitNotUsed;
use Qirolab\Laravel\Bannable\Middleware\ForbidBannedUser;
use Qirolab\Tests\Laravel\Bannable\Stubs\Models\UserWithoutBannableTrait;
use Qirolab\Tests\Laravel\Bannable\TestCase;

class BanExceptionTest extends TestCase
{
    /** @test **/
    public function it_can_throw_BannableTraitNotUsed_exception()
    {
        $this->expectException(BannableTraitNotUsed::class);

        $user = $this->createUser(UserWithoutBannableTrait::class);

        $this->actingAs($user);

        $request = Request::create('/test', 'GET');

        $middleware = new ForbidBannedUser(auth());

        $response = $middleware->handle($request, function () {
        });
    }
}