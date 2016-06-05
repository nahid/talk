<?php namespace Nahid\Talk;

use Illuminate\Support\ServiceProvider;
use App;

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
        App::bind('Talk', function ($app) {
      return new Talk($app['Nahid\Talk\Conversations\ConversationRepository'], $app['Nahid\Talk\Messages\MessageRepository']);
});
	}

	public function provides()
    {
        return ['Nahid\Talk\TalkServiceProvider'];
    }

}
