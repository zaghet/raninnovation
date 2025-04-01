<?php
namespace TST;
if( ! class_exists( '\TST\Tracking' ) ) {
class Tracking {

    public function __construct(){

        add_action('after_setup_theme', [$this, 'cookie_gclid']);
    }

    private function check_param($param){
        return Network::get_request($param);
    }
    
    public function cookie_gclid() {
        if ( $this->check_param('gclid') || $this->check_param('wbraid') || $this->check_param('gbraid') ) {
            
            if( $this->check_param('gbraid') )
                $gclid = $this->check_param('gbraid');

            if( $this->check_param('wbraid') )
                $gclid = $this->check_param('wbraid');

            if( $this->check_param('gclid') )
                $gclid = $this->check_param('gclid');    
                        
            setcookie('tw-gclid', $gclid, time() + 86400, '/', $_SERVER['SERVER_NAME'], 1);
            $this->_gclid = $gclid;
        }
        if (isset($_COOKIE['tw-gclid'])) {
            $this->_gclid = $_COOKIE['tw-gclid'];
        }
    }
}
if( ! function_exists( 'TST_TRACKING_HELPER' ) ){
    function TST_TRACKING_HELPER(){
        return new Tracking();
    }
}	
TST_TRACKING_HELPER();
}