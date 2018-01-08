<?php

namespace SmithAndAssociates\LaravelValence;

use Illuminate\Support\ServiceProvider;

class D2LServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
		$this->publishes([
			__DIR__.'/config/d2l.php' => config_path('d2l.php'),
		]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
//    	$this->app->bind(D2L::class, function () {
//			return new D2L(
//				config('d2l.host'),
//				config('d2l.appId'),
//				config('d2l.appKey'),
//				config('d2l.x_a'),
//				config('d2l.x_b')
//			);
//		});
    }

    protected function bindD2L()
	{
//		$this->app->singleton('D2L', function() {
//			return new D2L(
//				config('d2l.host'),
//				config('d2l.appId'),
//				config('d2l.appKey'),
//				config('d2l.x_a'),
//				config('d2l.x_b')
//			);
//		});
	}
}
