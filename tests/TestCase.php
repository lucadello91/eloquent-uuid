<?php

namespace Lucadello91\EloquentUuid\Tests;

use Dotenv\Dotenv;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        $this->loadEnvironmentVariables();

        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
    }

    public function tearDown(): void
    {
        $app = $this->app;

        $app['db']->connection()->getSchemaBuilder()->drop('users');
        $app['db']->connection()->getSchemaBuilder()->drop('posts');
    }

    protected function loadEnvironmentVariables(): void
    {
        if (!file_exists(__DIR__ . '/../.env')) {
            return;
        }

        $dotenv = Dotenv::create(__DIR__ . '/..');

        $dotenv->load();
    }

    /**
     * @param Application $app
     */
    protected function setUpDatabase($app): void
    {
        $app['db']->connection()->getSchemaBuilder()->create('users', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username');
            $table->string('password');
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('posts', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('user_id');
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('users_binary', function(Blueprint $table) {
            $table->binary('id')->primary();
            $table->string('username');
            $table->string('password');
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('posts_binary', function(Blueprint $table) {
            $table->binary('id')->primary();
            $table->string('name');
            $table->uuid('user_id');
            $table->timestamps();
        });
    }
}
