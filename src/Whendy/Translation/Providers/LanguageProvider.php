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

class LanguageProvider {

    /**
     *	The Eloquent language model.
     *	@var string
     */
    protected $model = 'Whendy\Translation\Models\Language';

    /**
     * Create a new Eloquent Language provider.
     *
     * @param  string  $model
     * @return void
     */
    public function __construct($model = null)
    {
        $this->setModel($model);
    }

    /**
     * Find the language by ID.
     *
     * @param  int  $id
     * @return Eloquent NULL in case no language entry was found.
     */
    public function findById($id)
    {
        return $this->createModel()->newQuery()->find($id);
    }

    /**
     * Find the language by ISO.
     *
     * @param  string  $locale
     * @return Eloquent NULL in case no language entry was found.
     */
    public function findByLocale($locale)
    {
        return $this->createModel()->newQuery()->where('locale', '=', $locale)->first();
    }

    /**
     * Find the language by name.
     *
     * @param  string  $name
     * @return Eloquent  $language
     */
    public function findByName($name)
    {
        return $this->createModel()->newQuery()->where('name', '=', $name)->first();
    }

    /**
     * Returns all languages excepted those that have been deleted.
     *
     * @return array  $languages
     */
    public function findAll()
    {
        return $this->createModel()->newQuery()->get()->all();
    }

    /**
     * Returns all languages that have active status.
     *
     * @return array  $languages
     */
    public function findAllActive()
    {
        return $this->createModel()->newQuery()->where('status', '=', 1)->get()->all();
    }

    /**
     * Returns all languages that have active status.
     * @param string $sort
     * @return array  $languages
     */
    public function findAllActiveOrderBy($sort = 'ASC')
    {
        return $this->createModel()->newQuery()->where('status', '=', 1)->orderBy('name', $sort)->get()->all();
    }

    /**
     * Returns all deleted languages.
     *
     * @return array  $languages
     */
    public function findTrashed()
    {
        return $this->createModel()->newQuery()->onlyTrashed()->get()->all();
    }

    /**
     * Returns the deleted language with id.
     *
     *	@param integer $id
     * 	@return array  $languages
     */
    public function findTrashedById($id)
    {
        return $this->createModel()->newQuery()->withTrashed()->find($id);
    }

    /**
     * Returns all deleted languages.
     *
     * @return array  $languages
     */
    public function findAllWithTrashed()
    {
        return $this->createModel()->newQuery()->withTrashed()->get()->all();
    }

    /**
     * Returns all languages except the one passed by parameter.
     *
     * @param  Whendy\Translation\Models\Language 	$language
     * @return array
     */
    public function findAllExcept($language)
    {
        return $this->createModel()->newQuery()->where('locale', '!=', $language->locale)->get();
    }

    /**
     * Returns all languages except the one passed by parameter.
     *
     * @param  string 	$locale
     * @return array
     */
    public function findAllActiveExcept($locale)
    {
        return $this->createModel()->newQuery()->where('locale', '!=', $locale)->where('status', '=', 1)->get();
    }

    /**
     *  Checks if a language with the given locale exists.
     *
     *  @return boolean
     */
    public function isValidLocale($locale)
    {
        return $this->createModel()->newQuery()->where('locale', '=', $locale)->where('status', '=', 1)->count() > 0;
    }

    /**
     * Restore a deleted language.
     *
     *	@param integer $id
     * 	@return array  $languages
     */
    public function restore($id)
    {
        return $this->findTrashedById($id)->restore();
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
