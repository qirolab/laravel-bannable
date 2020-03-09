<?php

namespace Qirolab\Tests\Laravel\Bannable;

use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;
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

        // $this->migratePackageTables();

        // $this->migrateUnitTestTables();

        $this->setUpDatabase();

        $this->registerPackageFactories();
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
            \Qirolab\Laravel\Bannable\BannableServiceProvider::class,
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

    /**
     * Perform package database migrations.
     *
     * @return void
     */
    // protected function migratePackageTables()
    // {
    //     $this->loadMigrationsFrom([
    //         '--realpath' => database_path('migrations'),
    //     ]);
    // }

    // /**
    //  * Perform unit test database migrations.
    //  *
    //  * @return void
    //  */
    // protected function migrateUnitTestTables()
    // {
    //     $this->loadMigrationsFrom([
    //         '--realpath' => realpath(__DIR__.'/database/migrations'),
    //     ]);
    // }

    /**
     * Register package related model factories.
     *
     * @return void
     */
    private function registerPackageFactories()
    {
        $pathToFactories = realpath(__DIR__.'/database/factories');

        $this->withFactories($pathToFactories);
    }
}
