<?php
namespace TST;
if( ! class_exists( '\TST\Template' ) ) {
class Template {

    public function __construct(){

        // Sostituzione placeholder da contenuto page/post
        add_filter( 'the_content', [$this, 'replace_placeholder'], 1 );
    }

    public static function theme_url( $path ) {
        return get_template_directory_uri() . '/' . $path;
    }

    public function get_glob_files( $extra_path = '' ){
        $path = substr(get_template_directory(), strpos(get_template_directory(),'wp-content'));
        
        if(is_admin()){
            return glob('../'.$path.$extra_path);
        }else{
            return glob($path.$extra_path);
        }
    }

    public function load_template_part($slug, $name = null, $args = array()){

        ob_start();
        get_template_part($slug, $name, $args);
        $var = ob_get_contents();
        ob_end_clean();

        return $var;
    }

    function get_template_file_uri($filepath){
        return get_template_directory_uri() . "/{$filepath}";
    }

    function format_date($format_date, $date = null){
        return date($format_date, strtotime($date));
    }

    public function replace_placeholder($content){
        $get = $_GET;

        if(isset($get['email'])){
            $email = $get['email'];
            $replace = "<a id='email_customer' class='email_customer' href='mailto:{$email}'>{$email}</a>";
            $content = str_replace('{email}', $replace, $content);
        }else{
            $content = str_replace('{email}', '', $content);
        }

        return $content;
    }

}
if( ! function_exists( 'TST_TEMPLATE_HELPER' ) ){
    function TST_TEMPLATE_HELPER(){
        return new Template();
    }
}
TST_TEMPLATE_HELPER();
}