<?php namespace FullFame\Folio;

use Illuminate\Support\ServiceProvider;

class FolioServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

    public function boot()
    {
        $this->package('fullfame/folio');
        include __DIR__.'/routes.php';
    }

}
