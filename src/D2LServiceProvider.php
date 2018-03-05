<?php

namespace SmithAndAssociates\LaravelValence;

use Illuminate\Support\ServiceProvider;
use SmithAndAssociates\LaravelValence\Console\OrgStructureCommand;
use SmithAndAssociates\LaravelValence\Console\AwardCommand;
use SmithAndAssociates\LaravelValence\Helper\D2LHelper;
use SmithAndAssociates\LaravelValence\Console\ChildlessCommand;
use SmithAndAssociates\LaravelValence\Console\OuTypesCommand;
use SmithAndAssociates\LaravelValence\Console\TableOfContentCommand;
use SmithAndAssociates\LaravelValence\Console\UsersCommand;
use SmithAndAssociates\LaravelValence\Console\DataExportCommand;
use SmithAndAssociates\LaravelValence\Console\RolesCommand;
use SmithAndAssociates\LaravelValence\Console\EnrollCommand;
use SmithAndAssociates\LaravelValence\Console\SpecialCommand;
use SmithAndAssociates\LaravelValence\Console\AuthenticateCommand;

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

//		$this->loadRoutesFrom(__DIR__.'/routes/api.php');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    	$this->app->singleton('D2L', function () {
			return new D2L(
				config('d2l.host'),
				config('d2l.id'),
				config('d2l.key'),
				config('d2l.a'),
				config('d2l.b')
			);
		});

    	$this->app->singleton('D2LHelper', function ($app) {
    		return new D2LHelper(
    			resolve('D2L')
			);
		});

		if ($this->app->runningInConsole()) {
			$this->commands([
				OrgStructureCommand::class,
				AwardCommand::class,
				ChildlessCommand::class,
				OuTypesCommand::class,
				TableOfContentCommand::class,
				UsersCommand::class,
				DataExportCommand::class,
				RolesCommand::class,
				EnrollCommand::class,
				SpecialCommand::class,
                AuthenticateCommand::class
			]);
		}
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
