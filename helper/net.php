<?php
namespace TST;
if( ! class_exists( '\TST\Network' ) ) {
class Network {

    public function __construct(){

    }

    public function get_client_ip(){
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = null;


        if($ipaddress != null){
            if(strpos($ipaddress, ",") !== false){
                $ipaddresses        = explode(",", $ipaddress);

                return trim($ipaddresses[0]);
            }else{
                return trim($ipaddress);
            }
        }

        

        return $ipaddress;
    }

    public static function get_request($name, $esc_attr = true){
        if(isset($_REQUEST[$name]) && trim($_REQUEST[$name]) != ''){

            if($esc_attr == true){
                if(is_array($_REQUEST[$name])){
                    return $_REQUEST[$name];
                }
                
                return esc_attr($_REQUEST[$name]);
            }

            return $_REQUEST[$name];
        }

        return null;
    }
    
    static public function current_url(){
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

}
if( ! function_exists( 'TST_NETWORK_HELPER' ) ){
    function TST_NETWORK_HELPER(){
        return new Network();
    }
}
TST_NETWORK_HELPER();
}