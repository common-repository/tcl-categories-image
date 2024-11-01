<?php

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
} 

/**
 * Class for registering a new settings page under Settings.
 */
class TCLCI_Setting_Page {

    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'tclci_setting_admin_page' ) );
        add_action( 'admin_init', array( $this, 'tclci_register_settings_init' ) );
    }


    /**
     * Registers a new settings page under Settings.
     */
    function tclci_setting_admin_page() {
        add_options_page(
            __( 'Categories Image Setting', 'tcl-categories-image' ),
            __( 'TCL Categories Image', 'tcl-categories-image' ),
            'manage_options',
            'tclci-options',
            array(
                $this,
                'tclci_settings_callback'
            )
        );
    }
    

    /**
     * Settings page display callback.
     */
    public function tclci_settings_callback()
    {
        // Set class property
        $this->options = get_option( 'tclci_options' );
        ?>
        <div class="wrap">
           <?php screen_icon(); ?>
        <h2><?php _e('Categories Image', 'tcl-categories-image'); ?></h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'tclci_option_group' );
                do_settings_sections( 'tclci-options' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function tclci_register_settings_init()
    {        
        register_setting(
            'tclci_option_group', // Option group
            'tclci_options', // Option name
            array( $this, 'sanitize_settings' ) // Sanitize
        );

        add_settings_section(
            'tclci_setting_id', // ID
            'Categories Image Settings', // Title
            array( $this, 'display_section_info' ), // Callback
            'tclci-options' // Page
        );  

        add_settings_field(
            'tclci_exclude_taxonomies', // ID
            'Exclude Taxonomies', // Title 
            array( $this, '_exclude_taxonomies_callback' ), // Callback
            'tclci-options', // Page
            'tclci_setting_id' // Section           
        );      
   
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize_settings( $input )
    {
        return $input;
    }

    /** 
     * Print the Section text
     */
    public function display_section_info()
    {
        echo '<p>'.__('Please select the taxonomies which you want to exclude', 'tcl-categories-image').'</p>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function _exclude_taxonomies_callback()
    {
        
        $taxonomies = get_taxonomies(); 
        $disabled_taxonomies = array('nav_menu', 'link_category', 'post_format');
        foreach ($taxonomies as $tax)
        {
            if (in_array($tax, $disabled_taxonomies)) continue; ?>
            <input type="checkbox" name="tclci_options[tclci_exclude_taxonomies][<?php echo $tax ?>]" value="<?php echo $tax ?>" <?php checked(isset($this->options['tclci_exclude_taxonomies'][$tax])); ?> /> <?php echo $tax; ?><br />
            <?php 
        }
    }
}

 $my_settings_page = new TCLCI_Setting_Page();