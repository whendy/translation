<?php
/*
 * Created By : Ahmad Windi Wijayanto
 * Email : ahmadwindiwijayanto@gmail.com
 * Website : https://whendy.net
 * github : https://github.com/whendy
 * LinkedIn : https://www.linkedin.com/in/ahmad-windi-wijayanto/
 *
 */

namespace Whendy\Translation\Models;


class LanguageEntry extends \Eloquent {

    /**
     *  Table name in the database.
     *  @var string
     */
    protected $table = 'translator_translations';

    /**
     *  List of variables that cannot be mass assigned
     *  @var array
     */
    protected $guarded = array('id');

    /**
     *	Each language entry belongs to a language.
     */
    public function language()
    {
        return $this->belongsTo('Whendy\Translation\Models\Language');
    }

    /**
     *  Returns the full translation code for an entry: namespace.group.item
     *  @return string
     */
    public function getCodeAttribute()
    {
        return $this->namespace === '*' ? "{$this->group}.{$this->item}" : "{$this->namespace}::{$this->group}.{$this->item}";
    }

    /**
     *  Return the language entry in the default language that corresponds to this entry.
     *  @param Whendy\Translation\Models\Language  $defaultLanguage
     *  @return Whendy\Translation\Models\LanguageEntry
     */
    public function original($defaultLanguage)
    {
        if ($this->exists && $defaultLanguage && $defaultLanguage->exists) {
            return $defaultLanguage->entries()->where('namespace', '=', $this->namespace)->where('group', '=', $this->group)->where('item', '=', $this->item)->first();
        } else {
            return NULL;
        }
    }

    /**
     *  Update the text. In case the second argument is true, then all translations for this entry will be flagged as unstable.
     *  @param  string   $text
     *  @param  boolean  $isDefault
     *  @return boolean
     */
    public function updateText($text, $isDefault = false, $lock = false, $force = false)
    {
        $saved            = false;

        // If the text is locked, do not allow editing:
        if (!$this->locked || $force) {
            $this->text   = $text;
            $this->locked = $lock;
            $saved        = $this->save();
            if ($saved && $isDefault) {
                $this->flagSiblingsUnstable();
            }
        }
        return $saved;
    }

    /**
     *  Flag all siblings as unstable.
     *
     */
    public function flagSiblingsUnstable()
    {
        if ($this->id) {
            LanguageEntry::where('namespace', '=', $this->namespace)
                ->where('group', '=', $this->group)
                ->where('item', '=', $this->item)
                ->where('locale', '!=', $this->locale)
                ->update(array('unstable' => '1'));
        }
    }

    /**
     *  Returns a list of entries that contain a translation for this item in the given language.
     *
     *  @param App\Translation\Models\Language
     *  @return App\Translation\Models\LanguageEntry
     */
    public function getSuggestedTranslations($language)
    {
        $self = $this;
        return $language->entries()
            ->select("{$this->table}.*")
            ->join("{$this->table} as e", function($join) use ($self) {
                $join
                    ->on('e.group', '=', "{$self->table}.group")
                    ->on('e.item', '=', "{$self->table}.item");
            })
            ->where('e.locale', '=', $this->locale)
            ->where('e.text', '=', "{$this->text}")
            ->groupBy("{$this->table}.text")
            ->get();
    }
}
