<?php
/**
 * Plugin Name:  Categories Image
 * Plugin URI:   http://techiescircle.com/
 * Description: Categories Images Plugin allow users to add an image to category or  custom taxonomy.
 * Version: 1.0.1
 * Author: WP Mirage
 * Author URI: http://techiescircle.com/
 * Requires at least: 4.7
 * Tested up to: 4.9
 * Text Domain: tcl-categories-image
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'TCLCI_VERSION', '1.0.1' );

define( 'TCLCI_PLUGIN', __FILE__ );

define( 'TCLCI_PLUGIN_BASENAME', plugin_basename( TCLCI_PLUGIN ) );

define( 'TCLCI_PLUGIN_NAME', trim( dirname( TCLCI_PLUGIN_BASENAME ), '/' ) );

define( 'TCLCI_PLUGIN_DIR', untrailingslashit( dirname( TCLCI_PLUGIN ) ) );

define( 'TCLCI_ABSPATH', dirname( __FILE__ ) . '/' );

define('TCLCI_IMAGE_PLACEHOLDER', plugins_url('/assets/images/placeholder.jpg',__FILE__));



final class TCL_Categories_Image {


    private static $instance = null;


    // get the only one instance of this class
    public static function getInstance()
    {

        if(!isset(self::$instance))
        {

            self::$instance = new TCL_Categories_Image();
        }

        return self::$instance;
    }



    protected function __construct()
    {
        
        add_action( 'init',     array( $this, 'tclci_includes' ), 3  );
        add_action('admin_enqueue_scripts',array($this,'admin_enqueue_assets'));
    }


    //cloning is forbidden
    private function __clone(){ }


    //Unserializing instances of this class is forbidden.
    private function __wakeup(){ }

    
   // templates includes
    public function tclci_includes()
    {

        
        if ( self::get_the_request( 'admin' ) )
        {

            include_once( TCLCI_ABSPATH . 'includes/tcl-admin-setting.php' );
        }

        include_once( TCLCI_ABSPATH . 'includes/class-tcl-term-meta-box.php' );
        include_once( TCLCI_ABSPATH . 'includes/tcl-cifunctions.php' );
    }


    // handling request    
    public static function get_the_request($request_type)
    {
     
        switch ( $request_type ) 
        {
            case 'admin' :
                return is_admin();
            case 'ajax' :
                return defined( 'DOING_AJAX' );
            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }


 
    public function admin_enqueue_assets()
    {

        wp_enqueue_media();
        wp_enqueue_script('categories-image-js', plugins_url('/assets/js/tcl-media-uploader.js',__FILE__), array('jquery'), '1.0.0', true );
        wp_localize_script( 'categories-image-js', 'media_upload_object',
            array( 
                '_placeholder_image' => TCLCI_IMAGE_PLACEHOLDER,
                '_media_button_text' => 'Use this media',
                '_media_upload_title' => 'Select or Upload media',
                '_enable_multiple_select' => false
            )
        );
    }

}

//getting the instance with class name
TCL_Categories_Image::getInstance();
?>