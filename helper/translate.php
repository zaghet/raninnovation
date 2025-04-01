<?php
namespace TST;
if( ! class_exists( '\TST\Translate' ) ) {
class Translate {
    public function __construct(){
        add_action('after_setup_theme', array($this, 'load_theme_textdomain'));
    }
    public function load_theme_textdomain(){
        load_theme_textdomain('tst', get_template_directory() . '/languages');
    }

    public static function traduction($string){
        if(function_exists('pll__')){
            return pll__($string);
        }else {
            return __($string, 'tst');
        }
    }

    public static function traduction_e($string){
        if(function_exists('pll_e')){
            return pll_e($string);
        }else {
            return _e($string, 'tst');
        }
    }
}
if( ! function_exists( 'TST_TRANSLATE_HELPER' ) ){
    function TST_TRANSLATE_HELPER(){
        return new Translate();
    }
}
if( ! function_exists( '\TST\trad___' ) ){
    function trad___($string){
        return Translate::traduction($string);
    }
}
if( ! function_exists( '\TST\trad___e' ) ){
    function trad___e($string){
        return \TST\Translate::traduction_e($string);
    }
}

TST_TRANSLATE_HELPER();
}