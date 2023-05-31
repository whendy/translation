<?php
/*
 * Created By : Ahmad Windi Wijayanto
 * Email : ahmadwindiwijayanto@gmail.com
 * Website : https://whendy.net
 * github : https://github.com/whendy
 * LinkedIn : https://www.linkedin.com/in/ahmad-windi-wijayanto/
 *
 */

/* Get config middleware */

use Whendy\Translation\Models\Language;

$middleware = app('config')->get("whendy/translation::middleware");

Route::filter('localize', function()
{
    $is_method      = Request::isMethod('get');
    $languageProvider= app(\Whendy\Translation\Providers\LanguageProvider::class);
    $cookie_locale  = \Cookie::get('locale');
    $defaultLanguage= Language::where(['status' => 1, 'default' => 1])->first();
    $defaultLocale  = $defaultLanguage->locale?:'en';

    
    if (!$cookie_locale && $is_method){
        //$cookie_locale = $defaultLocale;
        return Redirect::to(Request::url())->withCookie(\Cookie::forever('locale', $defaultLocale));
    }
    if ((Input::get('locale') && !empty(trim(Input::get('locale')))) && $is_method) {
        if ($available_locale = Language::where(['status' => 1, 'locale' => trim(Input::get('locale'))])->first()) {
            $cookie_locale = $available_locale->locale;
        } else {
            $cookie_locale = 'en';
        }
        return Redirect::to(Request::url())->withCookie(\Cookie::forever('locale', $cookie_locale));
    }

    if ($cookie_locale) {

        $currentLanguage = $languageProvider->findByLocale($cookie_locale);
        $selectableLanguages = $languageProvider->findAllActiveExcept($cookie_locale);
        $altLocalizedUrls = [];

        foreach ($selectableLanguages as $lang) {
            $altLocalizedUrls[] = [
                'locale' => $lang->locale,
                'name' => $lang->name,
                'url' => Request::fullUrl() . (Request::getQueryString()?'&':'?') .'locale='.$lang->locale
            ];
        }

        // Set app locale
        App::setLocale($cookie_locale);

        // Share language variable with views:
        \View::share('currentLanguage', $currentLanguage);
        \View::share('selectableLanguages', $selectableLanguages);
        \View::share('altLocalizedUrls', $altLocalizedUrls);

        if (!Session::has('whendy.translation.locale') or (Session::has('whendy.translation.locale') && Session::get('whendy.translation.locale') !== $cookie_locale)) {
            Session::put('whendy.translation.locale', $cookie_locale);
        }
    }else {

        // If no locale was set in the cookie, check the session locale
        if (Session::has('whendy.translation.locale') && $sessionLocale = Session::get('whendy.translation.locale') && $is_method) {
            if ($languageProvider->isValidLocale($sessionLocale)) {
                return Redirect::to(Request::url())->withCookie(\Cookie::forever('locale', $sessionLocale));
            }
        }
    }

    // If no locale was set in the url, check the browser's locale:
    /*$browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
    $browserLocale = (is_null($cookie_locale) && $browserLocale != $defaultLocale ? $defaultLocale : $browserLocale);
    if ($languageProvider->isValidLocale($browserLocale)) {
        return redirect()->to($request->url())->withCookie(cookie()->forever('locale', $browserLocale));
    }*/
});

Route::group(['prefix'=>'translation-language-manager', 'before'=>$middleware, 'namespace' => 'Whendy\Translation\Controllers'],function (){

    Route::get('/',['as'=>'whendy.translation.index','uses'=>'TranslationController@index']);

    Route::group(['prefix'=>'language'],function (){
        Route::get('/add-edit/{id?}',['as'=>'whendy.translation.language.add_edit','uses'=>'TranslationController@language_add_edit']);
        Route::post('/save',['as'=>'whendy.translation.language.save','uses'=>'TranslationController@language_save']);
    });
    Route::group(['prefix'=>'translation'],function (){
        Route::get('/add-edit/{code?}',['as'=>'whendy.translation.translation.add_edit','uses'=>'TranslationController@translation_add_edit']);
        Route::post('/save',['as'=>'whendy.translation.translation.save','uses'=>'TranslationController@translation_save']);
        Route::post('/delete',['as'=>'whendy.translation.translation.delete','uses'=>'TranslationController@translation_delete']);
    });
});
