<?php
namespace Nahid\Talk;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Nahid\Talk\Conversations\ConversationRepository;
use Nahid\Talk\Example\TalkController;
use Nahid\Talk\Messages\MessageRepository;
class TalkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
        $this->setupMigrations();
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerTalk();
        $this->registerView();
        include __DIR__.'/Example/routes.php';
    }
    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/../config/talk.php');
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
            realpath(__DIR__ . '/../database/migrations/') => database_path('migrations')
        ], 'migrations');
    }
    /**
     * Register Talk class.
     *
     * @return void
     */
    protected function registerTalk()
    {
        $this->app->singleton('Talk', function (Container $app) {
            return new Talk($app[ConversationRepository::class], $app[MessageRepository::class]);
        });
    }

    protected function registerView()
    {
        $this->loadViewsFrom(__DIR__.'/Example/views', 'talk');
    }
    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            Talk::class
        ];
    }
}
