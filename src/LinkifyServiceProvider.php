<?php 

namespace TaylorNetwork\Linkify;

use Illuminate\Support\ServiceProvider;

class LinkifyServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->publishes([
            __DIR__.'/config/linkify.php' => config_path('linkify.php'),
        ]);
	}

	public function register()
	{
		$this->mergeConfigFrom(
            __DIR__.'/config/linkify.php', 'linkify'
        );
	}
}