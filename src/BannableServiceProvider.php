<?php

namespace Qirolab\Laravel\Bannable;

use Illuminate\Support\ServiceProvider;
use Qirolab\Laravel\Bannable\Models\Ban;
use Qirolab\Laravel\Bannable\Observers\BanObserver;
use Qirolab\Laravel\Bannable\Middleware\ForbidBannedUser;

class BannableServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrations();

        $this->registerObservers();
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMiddleware();
    }

    /**
     * Setup the resource publishing groups for Ban.
     *
     * @return void
     */
    protected function loadMigrations()
    {
        if ($this->app->runningInConsole()) {
            $migrationsPath = __DIR__.'/../migrations';

            $this->publishes([
                $migrationsPath => database_path('migrations'),
            ], 'migrations');

            $this->loadMigrationsFrom($migrationsPath);
        }
    }

    /**
     * Register Ban's models observers.
     *
     * @return void
     */
    protected function registerObservers()
    {
        $this->app->make(Ban::class)->observe(new BanObserver);
    }

    /**
     * Register middleware.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        $this->app['router']->aliasMiddleware('forbidBannedUser', ForbidBannedUser::class);
    }
}
