<?php
class translate {

    public static $language = '';
    protected static $loaded;
    private static function load($language) {
        self::$language = $language;

        if(file_exists($_SERVER['DOCUMENT_ROOT']."/../core/languages/".$language.".php")) {
            include($_SERVER['DOCUMENT_ROOT']."/../core/languages/".$language.".php");
            self::$loaded = $language;
        }
    }

    static function fetch($page, $string) {
        if(!self::$loaded) {
            self::load("en_gb");
        }

        $c = self::$loaded;
        return $c[$page][$string];
    }
}

function _tr ($page, $string) {
    $lang = new translate();
    return $lang::fetch($page, $string);
}
?>