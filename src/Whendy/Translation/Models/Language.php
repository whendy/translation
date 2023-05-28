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


class Language extends Eloquent {

    /**
     *  Table name in the database.
     *  @var string
     */
    protected $table = 'translator_languages';

    /**
     *  Allow for languages soft delete.
     *  @var boolean
     */
    protected $softDelete = true;

    /**
     *  List of variables that cannot be mass assigned
     *  @var array
     */
    protected $guarded = array('id');


    /**
     *	Each language may have several entries.
     */
    public function entries()
    {
        return $this->hasMany('Whendy\Translation\Models\LanguageEntry', 'locale', 'locale');
    }

    /**
     *  Transforms a uri into one containing the current locale slug.
     *  Examples: login/ => /es/login . / => /es
     *
     *  @param string $uri Current uri.
     *  @return string Target uri.
     */
    public function uri($uri)
    {
        // Delete the forward slash if any at the beginning of the uri:
        $uri = substr($uri, 0, 1) == '/' ? substr($uri, 1) : $uri;
        $segments = explode('/', $uri);
        $newUri = "/{$this->locale}/{$uri}";
        if (sizeof($segments) && strlen($segments[0]) == 2) {
            $newUri = "/{$this->locale}";
            for($i = 1; $i < sizeof($segments); $i++) {
                $newUri .= "/{$segments[$i]}";
            }
        }
        return $newUri;
    }

}
