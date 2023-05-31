<?php
/*
 * Created By : Ahmad Windi Wijayanto
 * Email : ahmadwindiwijayanto@gmail.com
 * Website : https://whendy.net
 * github : https://github.com/whendy
 * LinkedIn : https://www.linkedin.com/in/ahmad-windi-wijayanto/
 *
 */

namespace Whendy\Translation\Providers;

use Illuminate\Support\NamespacedItemResolver;

class LanguageEntryProvider {

    /**
     *	The Eloquent language entry model.
     *	@var string
     */
    protected $model = 'Whendy\Translation\Models\LanguageEntry';

    /**
     * Create a new Eloquent LangEntry provider.
     *
     * @param  string  $model
     * @return void
     */
    public function __construct($model = null)
    {
        $this->setModel($model);
    }

    /**
     * Find the language entry by ID.
     *
     * @param  int  $id
     * @return Eloquent NULL in case no language entry was found.
     */
    public function findById($id)
    {
        return $this->createModel()->newQuery()->find($id);
    }

    /**
     * Find the entries with a key that starts with the provided key.
     *
     * @param  string  	$key
     * @return Eloquent List.
     */
    public function findByKey($language, $key)
    {
        return $this->createModel()->newQuery()->where('key', 'LIKE', "$key%")->get();
    }

    /**
     * Find all entries for a given language.
     *
     * @param  Eloquent  	$language
     * @return Eloquent
     */
    public function findByLanguage($name)
    {
        return $this->createModel()->newQuery()->where('name', '=', $name)->first();
    }

    /**
     * Returns all languages.
     *
     * @return array  $languages
     */
    public function findAll()
    {
        return $this->createModel()->newQuery()->get()->all();
    }

    /**
     *  Find a translation per namespace, group and item values
     *
     *  @param  string  $locale
     *  @param  string  $code
     *  @return Translation
     */
    public function findByLangCode($locale, $code)
    {
        list($namespace, $group, $item) = $this->parseCode($code);
        return $this->createModel()->newQuery()->where(['locale' => $locale, 'namespace' => $namespace, 'group' => $group, 'item' => $item])->first();
    }

    /**
     *  Find a translation per namespace, group and item values
     *
     *  @param  string  $locale
     *  @param  string  $namespace
     *  @param  string  $group
     *  @param  string  $item
     *  @return Translation
     */
    public function findByCode($locale, $namespace, $group, $item)
    {
        return $this->createModel()->newQuery()->where(['locale' => $locale, 'namespace' => $namespace, 'group' => $group, 'item' => $item])->first();
    }
    /**
     *  Find a translation per namespace, group and item values
     *
     *  @param  string  $code
     *  @return Translation
     */
    public function findFirstByCode($code)
    {
        list($namespace, $group, $item) = $this->parseCode($code);
        return $this->createModel()->newQuery()->where(['namespace' => $namespace, 'group' => $group, 'item' => $item])->first();
    }

    /**
     *  Delete all entries by code
     *
     *  @param  string  $code
     *  @return boolean
     */
    public function deleteByCode($code)
    {
        list($namespace, $group, $item) = $this->parseCode($code);
        $this->createModel()->newQuery()->where(['namespace' => $namespace, 'group' => $group, 'item' => $item])->delete();
    }

    /**
     *  Parse a translation code into its components
     *
     *  @param  string $code
     *  @return boolean
     */
    public function parseCode($code)
    {
        $segments = (new NamespacedItemResolver)->parseKey($code);

        if (is_null($segments[0])) {
            $segments[0] = '*';
        }

        return $segments;
    }

    /**
     *	Returns a language entry that is untranslated in the specified language.
     *	@param Whendy\Translation\Models\Language 				$reference
     *	@param Whendy\Translation\Models\Language 				$target
     *	@return Whendy\Translation\Models\LanguageEntry
     */
    public function findUntranslated($reference, $target)
    {
        $model = $this->createModel();
        return $model
            ->newQuery()
            ->where('locale', '=', $reference->locale)
            ->whereNotExists(function($query) use ($model, $reference, $target){
                $table = $model->getTable();
                $query
                    ->from("$table as e")
                    ->where('locale', '=', $target->locale)
                    ->whereRaw("(e.namespace = $table.namespace OR (e.namespace IS NULL AND $table.namespace IS NULL))")
                    ->whereRaw("e.group = $table.group")
                    ->whereRaw("e.item = $table.item")
                ;
            })
            ->first();
    }

    /**
     * Creates a language.
     *
     * @param  array  $attributes
     * @return Cartalyst\Sentry\languages\GroupInterface
     */
    public function create(array $attributes)
    {
        $language = $this->createModel();
        $language->fill($attributes)->save();
        return $language;
    }

    /**
     *	Loads messages into the database
     *	@param array 			$lines
     *	@param Language 	$language
     *	@param string 		$group
     *	@param string 		$namespace
     *	@param boolean 		$isDefault
     *	@return void
     */
    public function loadArray(array $lines, $language, $group, $namespace = null, $isDefault = false)
    {
        if (! $namespace) {
            $namespace = '*';
        }
        // Transform the lines into a flat dot array:
        $lines = array_dot($lines);
        foreach ($lines as $item => $text) {
            // Check if the entry exists in the database:
            $entry = $this
                ->createModel()
                ->newQuery()
                ->where('namespace', '=', $namespace)
                ->where('group', '=', $group)
                ->where('item', '=', $item)
                ->where('locale', '=', $language->locale)
                ->first();

            // If the entry already exists, we update the text:
            if ($entry) {
                $entry->updateText($text, $isDefault);
            }
            // The entry doesn't exist:
            else {
                $entry = $this->createModel();
                $entry->namespace = $namespace;
                $entry->group = $group;
                $entry->item = $item;
                $entry->text = $text;
                $language->entries()->save($entry);
            }
        }
    }

    /**
     * Create a new instance of the model.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function createModel()
    {
        $class = '\\'.ltrim($this->model, '\\');

        return new $class;
    }

    /**
     * Sets a new model class name to be used at
     * runtime.
     *
     * @param  string  $model
     */
    public function setModel($model = null)
    {
        $this->model = $model ?: $this->model;
    }
}
