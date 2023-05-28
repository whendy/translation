<?php
/*
 * Created By : Ahmad Windi Wijayanto
 * Email : ahmadwindiwijayanto@gmail.com
 * Website : https://whendy.net
 * github : https://github.com/whendy
 * LinkedIn : https://www.linkedin.com/in/ahmad-windi-wijayanto/
 *
 */

namespace Whendy\Translation\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Whendy\Translation\Providers\LanguageProvider as LanguageProvider;
use Whendy\Translation\Providers\LanguageEntryProvider as LanguageEntryProvider;

class FileLoaderCommand extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'translator:load';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = "Load language files into the database.";

  /**
   *  Create a new mixed loader instance.
   *
   *  @param  \Whendy\Translation\Providers\LanguageProvider        $languageProvider
   *  @param  \Whendy\Translation\Providers\LanguageEntryProvider   $languageEntryProvider
   *  @param  \Illuminate\Foundation\Application            $app
   */
  public function __construct($languageProvider, $languageEntryProvider, $fileLoader)
  {
    parent::__construct();
    $this->languageProvider       = $languageProvider;
    $this->languageEntryProvider  = $languageEntryProvider;
    $this->fileLoader             = $fileLoader;
    $this->finder                 = new Filesystem();
    $this->path                   = app_path().'/lang';
  }

  /**
   * Execute the console command.
   *
   * @return void
   */
  public function fire()
  {
    $localeDirs = $this->finder->directories($this->path);
    foreach($localeDirs as $localeDir) {
      $locale = str_replace($this->path.'/', '', $localeDir);
      $language = $this->languageProvider->findByLocale($locale);
      if ($language) {
        $langFiles = $this->finder->files($localeDir);
        foreach($langFiles as $langFile) {
          $group = str_replace(array($localeDir.'/', '.php'), '', $langFile);
          $lines = $this->fileLoader->loadRawLocale($locale, $group);
          $this->languageEntryProvider->loadArray($lines, $language, $group, null, $locale == $this->fileLoader->getDefaultLocale());
        }
      }
    }
  }
}
