<?php

namespace Qirolab\Tests\Laravel\Bannable;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;
use Qirolab\Laravel\Bannable\Models\Ban;
use Qirolab\Tests\Laravel\Bannable\Stubs\Models\User;

abstract class TestCase extends Orchestra
{
    /**
     * Actions to be performed on PHPUnit start.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->destroyPackageMigrations();

        $this->publishPackageMigrations();

        $this->setUpDatabase();
    }

    /**
     * Publish package migrations.
     *
     * @return void
     */
    protected function publishPackageMigrations()
    {
        $this->artisan('vendor:publish', [
            '--force' => '',
            '--tag' => 'migrations',
        ]);
    }

    /**
     * Delete all published package migrations.
     *
     * @return void
     */
    protected function destroyPackageMigrations()
    {
        File::cleanDirectory(__DIR__.'/../vendor/orchestra/testbench-core/laravel/database/migrations');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->setDefaultUserModel($app);
    }

    /**
     * Load package service provider.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Qirolab\Laravel\Bannable\BannableServiceProvider::class,
        ];
    }

    /**
     * Set default user model used by tests.
     *
     * @param  $app
     * @return void
     */
    private function setDefaultUserModel($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);
    }

    /**
     * Set up the database.
     *
     * @return void
     */
    protected function setUpDatabase()
    {
        include_once __DIR__.'/../migrations/2018_06_25_000000_create_bans_table.php';
        include_once __DIR__.'/database/migrations/2018_06_25_000000__create_user_table.php';

        (new \CreateBansTable())->up();
        (new \CreateUserTable())->up();
    }

    protected function faker($locale = null)
    {
        $locale = $locale ?? Factory::DEFAULT_LOCALE;

        if (isset($this->app) && $this->app->bound(Generator::class)) {
            return $this->app->make(Generator::class, ['locale' => $locale]);
        }

        return Factory::create($locale);
    }

    public function factory($class, $attributes = [], $amount = null)
    {
        if (isset($amount) && is_int($amount)) {
            $resource = [];

            for ($i = 0; $i < $amount; $i++) {
                $resource[] = (new $class())->forceCreate($attributes);
            }

            return new Collection($resource);
        }

        return (new $class())->forceCreate($attributes);
    }

    public function createUser($class = null, $attributes = [], $amount = null)
    {
        $class = $class ?? User::class;

        return $this->factory(
            $class,
            array_merge(
                ['name' => $this->faker()->name],
                $attributes
            ),
            $amount
        );
    }

    public function createBan($attributes = [], $amount = null)
    {
        $bannable = $this->createUser(User::class);

        // dd(array_merge(
        //     [
        //         'bannable_id' => $bannable->getKey(),
        //         'bannable_type' => $bannable->getMorphClass(),
        //     ],
        //     $attributes
        // ));
        return $this->factory(
            Ban::class,
            array_merge(
                [
                    'bannable_id' => $bannable->getKey(),
                    'bannable_type' => $bannable->getMorphClass(),
                ],
                $attributes
            ),
            $amount
        );
    }
}
