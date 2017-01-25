<?php

namespace Nahid\Talk;

use Illuminate\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Nahid\Talk\Conversations\ConversationRepository;
use Nahid\Talk\Messages\MessageRepository;

class TalkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->setupConfig();
        $this->setupMigrations();
        $this->loadViewsFrom(__DIR__.'/views', 'talk');
    }
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->registerBroadcast();
        $this->registerTalk();
    }
    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/talk.php');
        // Check if the application is a Laravel OR Lumen instance to properly merge the configuration file.
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('talk.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('talk');
        }
        $this->mergeConfigFrom($source, 'talk');
    }
    /**
     * Publish migrations files.
     */
    protected function setupMigrations()
    {
        $this->publishes([
            realpath(__DIR__.'/../database/migrations/') => database_path('migrations'),
        ], 'migrations');
    }
    /**
     * Register Talk class.
     */
    protected function registerTalk()
    {
        $this->app->singleton('talk', function (Container $app) {
            return new Talk($app['config'], $app['talk.broadcast'], $app[ConversationRepository::class], $app[MessageRepository::class]);
        });

        $this->app->alias('talk', Talk::class);
    }

    /**
     * Register Talk class.
     */
    protected function registerBroadcast()
    {
        $this->app->singleton('talk.broadcast', function (Container $app) {
            return new Live\Broadcast($app['config']);
        });

        $this->app->alias('talk.broadcast', Live\Broadcast::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'talk',
            'talk.broadcast',
        ];
    }
}
