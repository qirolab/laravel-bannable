<?php

namespace Hkp22\Tests\Laravel\Bannable;

use Orchestra\Testbench\TestCase as Orchestra;
use Hkp22\Tests\Laravel\Bannable\Stubs\Models\User;

abstract class TestCase extends Orchestra
{
    /**
     * Actions to be performed on PHPUnit start.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->migratePackageTables();

        $this->migrateUnitTestTables();

        $this->registerPackageFactories();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->setDefaultUserModel($app);
    }

    /**
     * Load package service provider.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Hkp22\Laravel\Bannable\BannableServiceProvider::class,
        ];
    }

    /**
     * Set default user model used by tests.
     *
     * @param $app
     * @return void
     */
    private function setDefaultUserModel($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);
    }

    /**
     * Perform package database migrations.
     *
     * @return void
     */
    protected function migratePackageTables()
    {
        $this->loadMigrationsFrom([
            '--realpath' => database_path('migrations'),
        ]);
    }

    /**
     * Perform unit test database migrations.
     *
     * @return void
     */
    protected function migrateUnitTestTables()
    {
        $this->loadMigrationsFrom([
            '--realpath' => realpath(__DIR__ . '/database/migrations'),
        ]);
    }

    /**
     * Register package related model factories.
     *
     * @return void
     */
    private function registerPackageFactories()
    {
        $pathToFactories = realpath(__DIR__ . '/database/factories');

        $this->withFactories($pathToFactories);
    }
}
