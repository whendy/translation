<?php
/*
 * Created By : Ahmad Windi Wijayanto
 * Email : ahmadwindiwijayanto@gmail.com
 * Website : https://whendy.net
 * github : https://github.com/whendy
 * LinkedIn : https://www.linkedin.com/in/ahmad-windi-wijayanto/
 *
 */

namespace Whendy\Translation\Loaders;

use Illuminate\Translation\LoaderInterface;
use Illuminate\Translation\FileLoader as LaravelFileLoader;
use Whendy\Translation\Loaders\Loader;
use Whendy\Translation\Providers\LanguageProvider as LanguageProvider;
use Whendy\Translation\Providers\LanguageEntryProvider as LanguageEntryProvider;

class FileLoader extends Loader implements LoaderInterface {

	/**
	 * The laravel file loader instance.
	 *
	 * @var \Illuminate\Translation\FileLoader
	 */
	protected $laravelFileLoader;

	/**
	 * 	Create a new mixed loader instance.
	 *
	 * 	@param  \Whendy\Translation\Providers\LanguageProvider  		$languageProvider
	 * 	@param 	\Whendy\Translation\Providers\LanguageEntryProvider		$languageEntryProvider
	 *	@param 	\Illuminate\Foundation\Application  					$app
	 */
	public function __construct($languageProvider, $languageEntryProvider, $app)
	{
		parent::__construct($languageProvider, $languageEntryProvider, $app);
		$this->laravelFileLoader = new LaravelFileLoader($app['files'], $app['path'].'/lang');
	}

	/**
	 * Load the messages strictly for the given locale without checking the cache or in case of a cache miss.
	 *
	 * @param  string  $locale
	 * @param  string  $group
	 * @param  string  $namespace
	 * @return array
	 */
	public function loadRawLocale($locale, $group, $namespace = null)
	{
		$namespace = $namespace ?: '*';
		return $this->laravelFileLoader->load($locale, $group, $namespace);
	}

}
