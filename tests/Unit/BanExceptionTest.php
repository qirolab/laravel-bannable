<?php

namespace Hkp22\Tests\Laravel\Bannable\Unit;

use Illuminate\Http\Request;
use Hkp22\Tests\Laravel\Bannable\TestCase;
use Hkp22\Laravel\Bannable\Middleware\ForbidBannedUser;
use Hkp22\Laravel\Bannable\Exceptions\BannableTraitNotUsed;
use Hkp22\Tests\Laravel\Bannable\Stubs\Models\UserWithoutBannableTrait;

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
