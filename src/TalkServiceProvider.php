<?php namespace Nahid\Talk;

use Illuminate\Support\ServiceProvider;

class TalkServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			 __DIR__.'/config' => base_path('config'),
          __DIR__.'/Model'  => app_path(),
          __DIR__.'/migrations' => base_path('database/migrations')
			]);
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		include __DIR__."/routes.php";

		$this->app->make('Nahid\Talk\Talk');
	}

	public function provides()
    {
        return ['Nahid\Talk\TalkServiceProvider'];
    }

}
