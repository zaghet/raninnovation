<?php
namespace TST;
if( ! class_exists( '\TST\Media' ) ) {
class Media {
    public function __construct() {
        /**
         * Image Media Sizes
         * 
         * 
         */
        add_action('after_setup_theme', [$this, 'setup']);
        
        /**
         * SVG Support
         * 
         *  
         */ 
        add_action('admin_head', [$this, 'fix_svg']);
        add_filter('wp_check_filetype_and_ext', [$this, 'check_filetype'], 10, 4);
        add_filter('upload_mimes', [$this, 'mime_types'], 1, 1);
        add_action('wp_AJAX_svg_get_attachment_url', [$this, 'display_svg_files_backend']);
        // add_filter('wp_prepare_attachment_for_js', [$this, 'display_svg_media'], 10, 3);
        add_action('admin_head', [$this, 'svg_styles']);
    }

    public function setup() {

        // Custom image sizes
        add_image_size( 'hero', 1920, 500 );
        add_image_size( 'card-square', 424, 424, true );
        add_image_size( 'card', 424 );
        add_image_size( 'big', 1920 );
        add_image_size( 'mid', 1024 );
        add_image_size( 'fit', 550 );
    }

    public function check_filetype($checked, $file, $filename, $mimes){

        if(!$checked['type']){
       
            $upload_check = wp_check_filetype( $filename, $mimes );
            $ext              = $upload_check['ext'];
            $type             = $upload_check['type'];
            $proper_filename  = $filename;
       
            if($type && 0 === strpos($type, 'image/') && $ext !== 'svg'){
               $ext = $type = false;
            }
       
            // Check the filename
            $checked = compact('ext','type','proper_filename');
        }
       
        return $checked;
    }

    public function mime_types($mimes){
        $mimes['svg'] = 'image/svg+xml';

        return $mimes;
    }
    
    public function fix_svg() {
        echo '';
    }

    public function display_svg_files_backend(){

        $url = '';
        $attachmentID = isset($_REQUEST['attachmentID']) ? $_REQUEST['attachmentID'] : '';

        if($attachmentID){
            $url = wp_get_attachment_url($attachmentID);
        }
        echo $url;
        
        die();
    }
    
    public function display_svg_media($response, $attachment, $meta){
        if($response['type'] === 'image' && $response['subtype'] === 'svg+xml' && class_exists('SimpleXMLElement')){
            try {
                
                $path = get_attached_file($attachment->ID);

                if(@file_exists($path)){
                    $svg                = new SimpleXMLElement(@file_get_contents($path));
                    $src                = $response['url'];
                    $width              = (int) $svg['width'];
                    $height             = (int) $svg['height'];
                    $response['image']  = compact( 'src', 'width', 'height' );
                    $response['thumb']  = compact( 'src', 'width', 'height' );

                    $response['sizes']['full'] = array(
                        'height'        => $height,
                        'width'         => $width,
                        'url'           => $src,
                        'orientation'   => $height > $width ? 'portrait' : 'landscape',
                    );
                }
            }
            catch(Exception $e){}
        }

        return $response;
    }
    
    public function svg_styles() {
        echo "<style>
                /* Media LIB */
                table.media .column-title .media-icon img[src*='.svg']{
                    width: 100%;
                    height: auto;
                }
    
                /* Gutenberg Support */
                .components-responsive-wrapper__content[src*='.svg'] {
                    position: relative;
                }
    
            </style>";
    }
    
}
if( ! function_exists( 'TST_MEDIA_HELPER' ) ){
    function TST_MEDIA_HELPER(){
        return new Media();
    }
}
TST_MEDIA_HELPER();
}