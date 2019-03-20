<?php

namespace Qirolab\Tests\Laravel\Bannable\Unit;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Auth\AuthenticationException;
use Qirolab\Tests\Laravel\Bannable\TestCase;
use Qirolab\Tests\Laravel\Bannable\Stubs\Models\User;
use Qirolab\Laravel\Bannable\Middleware\ForbidBannedUser;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MiddlewareTest extends TestCase
{
    /** @test **/
    public function it_can_throw_BannableTraitNotUsed_exception()
    {
        $user = factory(User::class)->create();
        $user->ban();

        $this->actingAs($user->fresh());

        $middleWare = new ForbidBannedUser(auth());

        $this->assertEquals(
            $this->runMiddleware(
                $middleWare
            ),
            302
        );
    }

    protected function runMiddleware($middleware, $parameter = null)
    {
        try {
            return $middleware->handle(new Request(), function () {
                return (new Response())->setContent('<html></html>');
            }, $parameter)->status();
        } catch (AuthenticationException $e) {
            return 401;
        } catch (HttpException $e) {
            return $e->getStatusCode();
        }
    }
}
