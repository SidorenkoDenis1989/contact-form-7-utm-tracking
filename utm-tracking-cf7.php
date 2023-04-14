<?php 


namespace UTMTrackingCF7;

/*
 * Plugin Name: UAC. UTM tracking fields for Contact Form 7 
 * Description: Added UTM tracking to CF 7 
 * Author:            Denys Sydorenko
 * Author URI:        https://github.com/SidorenkoDenis1989
 * Version: 1.0.0
 */

class Main {

    private static $instance;
    const UTM_TRACKING_PARAMS_KEY = "utm_tracking_params";

    private function __construct() {
        add_action( 'init', array($this, 'set_utm_params_2_session') );
        add_filter('wpcf7_form_elements',  array($this, 'add_utm_params_2_forms') , 10, 3);
    }

    public function set_utm_params_2_session() {
        if (!session_id()) {
            session_start();
        }

        if (isset($_GET) && count($_GET)) {
            $_SESSION[self::UTM_TRACKING_PARAMS_KEY] = [];
            $_SESSION[self::UTM_TRACKING_PARAMS_KEY]["utm_source"]      = $_GET['utm_source'] ? $_GET['utm_source'] : null; 
            $_SESSION[self::UTM_TRACKING_PARAMS_KEY]["utm_medium"]      = $_GET['utm_medium'] ? $_GET['utm_medium'] : null; 
            $_SESSION[self::UTM_TRACKING_PARAMS_KEY]["utm_campaign"]    = $_GET['utm_campaign'] ? $_GET['utm_campaign'] : null; 
            $_SESSION[self::UTM_TRACKING_PARAMS_KEY]["utm_content"]     = $_GET['utm_content'] ? $_GET['utm_content'] : null; 
            $_SESSION[self::UTM_TRACKING_PARAMS_KEY]["utm_term"]        = $_GET['utm_term'] ? $_GET['utm_term'] : null; 
            $_SESSION[self::UTM_TRACKING_PARAMS_KEY]["referral"]        = $_GET['referral'] ? $_GET['referral'] : null; 
            $_SESSION[self::UTM_TRACKING_PARAMS_KEY]["fbclid"]          = $_GET['fbclid'] ? $_GET['fbclid'] : null; 
            $_SESSION[self::UTM_TRACKING_PARAMS_KEY]["gclid"]           = $_GET['gclid'] ? $_GET['gclid'] : null;
        }
    }
 

    public function add_utm_params_2_forms( $elements ) {
        $form = wpcf7_get_current_contact_form();    
        if (!$_SESSION[self::UTM_TRACKING_PARAMS_KEY]) {
            return $elements;
        }
        $updatedElements = $elements;
        foreach ($_SESSION[self::UTM_TRACKING_PARAMS_KEY] as $utm_key => $utm_param) {
            echo "<input type='hidden' class='wpcf7-length' value='" . strlen($utm_param) . "'/>";
            if (!is_null($utm_param)) {
                $updatedElements .= "<input type='hidden' class='wpcf7-" . $utm_key . "' name='" . $utm_key .  "' value='" . $utm_param . "'/>";
            }
        }
        return $updatedElements;
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
Main::getInstance();