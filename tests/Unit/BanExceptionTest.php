<?php

namespace Qirolab\Tests\Laravel\Bannable\Unit;

use Illuminate\Http\Request;
use Qirolab\Tests\Laravel\Bannable\TestCase;
use Qirolab\Laravel\Bannable\Middleware\ForbidBannedUser;
use Qirolab\Laravel\Bannable\Exceptions\BannableTraitNotUsed;
use Qirolab\Tests\Laravel\Bannable\Stubs\Models\UserWithoutBannableTrait;

class BanExceptionTest extends TestCase
{
    /** @test **/
    public function it_can_throw_BannableTraitNotUsed_exception()
    {
        $this->expectException(BannableTraitNotUsed::class);

        $user = factory(UserWithoutBannableTrait::class)->create();

        $this->actingAs($user);

        $request = Request::create('/test', 'GET');

        $middleware = new ForbidBannedUser(auth());

        $response = $middleware->handle($request, function () {
        });
    }
}
