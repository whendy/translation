<?php
/*
 * Created By : Ahmad Windi Wijayanto
 * Email : ahmadwindiwijayanto@gmail.com
 * Website : https://whendy.net
 * github : https://github.com/whendy
 * LinkedIn : https://www.linkedin.com/in/ahmad-windi-wijayanto/
 *
 */

namespace Whendy\Translation\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Whendy\Translation\Models\Language;
use Whendy\Translation\Models\LanguageEntry;
use Whendy\Translation\Providers\LanguageEntryProvider;
use Whendy\Translation\Providers\LanguageProvider;

class TranslationController extends \BaseController
{
    protected $languages;

    public function __construct()
    {
        $this->languages = new LanguageProvider();
    }

    public function index()
    {
        $search                 = trim(Input::get('search', ''));
        $params['languages']    = $this->languages->findAll();
        $translation            = LanguageEntry::whereNotIn('translator_translations.group', ['translatable'])->groupBy('code_order');
        $params['language_active'] = Language::where(['status' => 1, 'default' => 1])->count();
        $where_search           = [];
        $params['appends_link'] = [];
        if ($search){
            $where_search[] = "translator_translations.item LIKE '%{$search}%'";
            $where_search[] = "translator_translations.group LIKE '%{$search}%'";
            $params['appends_link'] = ['search' => $search];
        }
        $selects = [\DB::raw('CONCAT(translator_translations.namespace, translator_translations.group, translator_translations.item) AS code_order,
`translator_translations`.`namespace`,
`translator_translations`.`group`,
`translator_translations`.`item`')];
        foreach ($params['languages'] as $locale) {
            array_push($selects, "lang_{$locale->locale}.text AS language_{$locale->locale}");
            $translation->leftJoin("translator_translations AS lang_{$locale->locale}", function($q) use($locale){
                $q->on('translator_translations.namespace', '=', "lang_{$locale->locale}.namespace")
                    ->on('translator_translations.group', '=', 'lang_'.$locale->locale.'.group')
                    ->on('translator_translations.item', '=', 'lang_'.$locale->locale.'.item')
                    ->where("lang_{$locale->locale}.locale", '=', $locale->locale);
            });
            if ($search){
                $where_search[] = "lang_{$locale->locale}.text LIKE '%{$search}%'";
            }
        }
        if (count($where_search)){
            $translation = $translation->whereRaw('('.implode(' OR ', $where_search).')');
        }
        $params['translations'] = $translation->select($selects)->paginate(15);
        return View::make("whendy/translation::index", $params);
    }

    public function language_add_edit($id = '')
    {
        $params['language'] = new Language();
        if ($id && $language = Language::where('id', '=', $id)->first()){
            $params['language'] = $language;
        }
        return View::make("whendy/translation::lang_add_edit", $params);
    }

    public function language_save()
    {
        $id     = trim(Input::get('id'));
        if (Input::has('set-default')&&Input::get('set-default')){
            Language::where('id', '<>', $id)->update(['default'=>0]);
            Language::where('id', '=', $id)->update(['default'=>1]);
            return Response::json(['message' => 'Success'], 200);
        }
        $locale = trim(Input::get('locale'));
        $name   = trim(Input::get('name'));
        $status = trim(Input::get('status'));
        $rules = [
            'locale'    => "required|unique:translator_languages,locale,{$id}",
            'name'      => "required|unique:translator_languages,name,{$id}",
            'status'    => "required|in:1,0"
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return Response::json(['errors' => $errors, 'message' => 'Validation error'], 422);
        }
        Language::updateOrCreate(['id' => $id], [
            'locale'    => $locale,
            'name'      => $name,
            'status'    => $status
        ]);
        return Response::json(['message' => 'Success'], 200);
    }

    public function translation_add_edit($code = '')
    {
        $params['languageEntryProvider']    = app(LanguageEntryProvider::class);
        $params['locale']       = Language::where('default', '=', 1)->first()->locale;
        $params['code']         = trim($code);
        $params['translation']  = new LanguageEntry();
        if (!empty($code)) {
            $translation = $params['languageEntryProvider'];
            list($namespace, $group, $item) = $translation->parseCode($code);
            $has_translation  = $translation->findFirstByCode($code);
            $params['translation'] = $has_translation;
            if (!$has_translation){
                $params['translation']->locale      = $params['locale'];
                $params['translation']->namespace   = $namespace;
                $params['translation']->group       = $group;
                $params['translation']->item        = $item;
            }
        }

        $params['locales']      = Language::orderBy('default', 'DESC')->get();
        $params['namespaces']   = LanguageEntry::select('namespace')->groupBy('namespace')->get();
        $params['groups']       = LanguageEntry::select('group')->whereNotIn('group', ['translatable'])->groupBy('group')->get();
        return View::make("whendy/translation::trans_add_edit", $params);
    }

    public function translation_save()
    {
        $code       = trim(Input::get('code'));
        $namespace  = trim(Input::get('namespace'));
        $group      = trim(Input::get('group'));
        $item       = trim(Input::get('item'));
        $default_translate_text = '';
        $key = 0;
        foreach (Input::get('translations') as $translate) {
            $locale = trim($translate['locale']);
            if ($key == 0){
                $default_translate_text = trim($translate['text']);
            }
            $translate_text = trim($translate['text']);
            LanguageEntry::updateOrCreate(['id' => trim($translate['id'])],[
                'locale'    => $locale,
                'namespace' => $namespace,
                'group'     => $group,
                'item'      => $item,
                'text'      => (!empty($translate_text)?$translate_text:$default_translate_text)
            ]);
            Cache::forget("whendy|translation|$locale.$group.$namespace");
            $key++;
        }
        return Response::json(['message' => 'Success'], 200);
    }

    public function translation_delete()
    {
        $code           = trim(Input::get('code'));
        app(LanguageEntryProvider::class)->deleteByCode($code);
        return Response::json(['message' => 'Success'], 200);
    }
}
