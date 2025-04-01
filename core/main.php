<?php
/**
 * Main class Twow Starter theme
 * 
 */

namespace TST;

include( 'core.php' );

class Main extends Core {

	// Settings
	public $settings = [
		'logging' => 'everything',
		'enable_logger' => true,
		'enable_debug' => true,
	];

	// Loading framework
	public function __construct( $args = array()){ 

		$default_args = array(
			'start'      => __DIR__,
		);
		$args = wp_parse_args( $args, $default_args );

		// self::componentLoader('vendor');
		self::componentLoader('library', 0, $args['start']);
		self::componentLoader('helper', 0, $args['start']);
		self::componentLoader('configure', 0, $args['start']);
		self::componentLoader('extensions', 1, $args['start']);// di extensions e subdir di 1° livello
		self::componentLoader('cpt', 0, $args['start']);// caricamento custom post type
	
		// OTHER check ---------s
		if ( ! defined('GTM4WP_VERSION') ) {
			add_action( 'admin_notices', function(){
				?>
				<div class="notice notice-warning is-dismissible">
				<p><?php _e( 'Installare Plugin GTM4WP' ); ?></p>
				</div>
				<?php
			} );
		}
	}

	// funzione di loader ricorsivo
	public static function componentLoader( $path=NULL, $dir_recurse_depth=0, $start ){
        $directory  = $start . DIRECTORY_SEPARATOR . $path;

        $iterations = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),\RecursiveIteratorIterator::LEAVES_ONLY);
		$iterations->setMaxDepth($dir_recurse_depth);
		if(empty($iterations)) return FALSE;
		$components = [];
		
		foreach($iterations as $iteration) {
			$fileName   = $iteration->getFilename();
			if($iteration->isReadable() && !preg_match("/(\A[.].*)/", $fileName)) {
				$components[]   = $iteration->getPathname();
			}
		}
		foreach($components as $component) {
			if(strpos('admin', basename($component)) === false) {
				require_once $component;
			}else{
				if(is_admin()) {
					require_once $component;
				}
			}
		}
		
	}

	// Option page
	public function get_acf_option($lang=''){
		return empty($lang) ? 'options' : 'options-'.$lang;
	}

	// Product cat
	public static function has_cat( $term, $product_id ){
		return has_term( $term, 'product_cat', $product_id ) ? true : false;
	}

	/**
	 * Log by wc_get_logger with fallback WP error_log
	 */
	public function log( $message, $level = 'info', $e = null ) {
		// define log levels
		$log_detail = isset( $this->settings['logging'] ) ? $this->settings['logging'] : '';
		// legacy fallback
		if ( empty( $log_detail ) && isset( $this->settings['enable_logger'] ) ) {
			$log_detail = 'events+errors';
		}
		$log_levels = array(
			'debug'     => array( 'everything' ),
			'info'      => array( 'everything', 'events+errors' ),
			'notice'    => array( 'everything', 'events+errors' ),
			'warning'   => array( 'everything', 'events+errors', 'errors' ),
			'error'     => array( 'everything', 'events+errors', 'errors' ),
			'critical'  => array( 'everything', 'events+errors', 'errors' ),
			'alert'     => array( 'everything', 'events+errors', 'errors' ),
			'emergency' => array( 'everything', 'events+errors', 'errors' ),
		);
		// select relevant levels
		$allowed_levels = array();
		foreach ( $log_levels as $log_level => $detail_levels ) {
			if ( in_array( $log_detail, $detail_levels ) ) {
				$allowed_levels[] = $log_level;
			}
		}
		// allowed log levels
		$allowed_levels = apply_filters( 'tst_log_levels_allowed', $allowed_levels, $log_detail );

		// input level not allowed
		if ( empty( $allowed_levels ) || ! in_array( $level, $allowed_levels ) ) {
			return;
		}

		$context   = array( 'source' => 'tst-theme' );
		// wc logger
		if ( function_exists( 'wc_get_logger' ) ) {
			$wc_logger = wc_get_logger();

			if ( is_callable( array( $e, 'getFile' ) ) && is_callable( array( $e, 'getLine' ) ) ) {
				$message = sprintf( '%s (%s:%d)', $message, $e->getFile(), $e->getLine() );
			}

			// The `log` method accepts any valid level as its first argument.
			// debug     - 'Detailed debug information'
			// info      - 'Interesting events'
			// notice    - 'Normal but significant events'
			// warning   - 'Exceptional occurrences that are not errors'
			// error     - 'Runtime errors that do not require immediate'
			// critical  - 'Critical conditions'
			// alert     - 'Action must be taken immediately'
			// emergency - 'System is unusable'.
			$wc_logger->log( $level, $message, $context );
		} else {
			$this->custom_log( $message, $level, $context );
		}
	}

	public function custom_log($log, $level, $context) {
        if (true === WP_DEBUG || $this->settings['enable_logger']) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}