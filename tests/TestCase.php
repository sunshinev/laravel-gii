<?php
namespace Sunshinev\Gii\Test;

use Orchestra\Testbench\TestCase as Orchestra;
use Sunshinev\Gii\Providers\GiiServiceProvider;


abstract class TestCase extends Orchestra
{
    public function setUp() :void
    {
        parent::setUp();
    }
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            GiiServiceProvider::class,
        ];
    }
    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'mysql');
        $app['config']->set('database.connections.mysql', [
            'driver'   => 'mysql',
            'host'     => '127.0.0.1',
            'port'     => '3306',
            'database' => 'test',
            'username' => 'root',
            'password' => '',
            'prefix'   => '',
        ]);
    }
    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
    }
}