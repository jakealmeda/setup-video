<?php
/**
 * Plugin Name: Setup Video 2.0
 * Description: Utilize ACF's custom fields to handle videos.
 * Version: 1.0
 * Author: Jake Almeda
 * Author URI: http://smarterwebpackages.com/
 * Network: true
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


// GLOBAL VIDEO HIERARCHY
class SetupVideoStructure {

    // default video dimensions
    public function setup_video_size() {

        return $sizes = array(
            'width'     =>  '560',
            'height'    =>  '315',
        );

    }

    // array of Youtube URLs
    public $domain_yt = array(
        'www.youtube.com',
        'youtu.be',
    );

    // array of Vimeo URLs
    public $domain_vimeo = array(
        'vimeo.com',
    );

    // default video provider
    /*public function videodefault() {

        return 'youtube';

    }*/

    // default video provider hierarchy
    /*public function videostructure() {

        return array(
            'youtube',
            'vimeo',
            'rumble',
        );

    }*/

    // simply return this plugin's main directory
    public function setup_plugin_dir_path() {

        return plugin_dir_path( __FILE__ );

    }

}


// JQUERY HANDLER
class SetupVideojQuery {

    /**
     * ENQUEUE SCRIPTS
     */
    public function setup_enqueue_scripts() {

        // last arg is true - will be placed before </body>
        wp_register_script( 'setup_video_2_0_scripts', plugins_url( 'js/asset.js', __FILE__ ), NULL, '1.0', TRUE );

        // enqueue styles
        //wp_enqueue_style( 'setup_video_block_style', plugins_url( 'assets/css/setup-video-block-style.css', __FILE__ ) );
         
        // Localize the script with new data
        /*$args = array(
            'wp_config'			=> $this->find_wp_config_path().'/wp-config.php',
        );
        wp_localize_script( 'setup_video_2_0_scripts', 'setup_video_2_0_args', $args );*/
        
        // Enqueued script with localized data.
        wp_enqueue_script( 'setup_video_2_0_scripts' );

    }


    /**
     * FIND MY WP-CONFIG.PHP
     */
    /*public function find_wp_config_path() {
        $dir = dirname(__FILE__);
        do {
            if( file_exists($dir."/wp-config.php") ) {
                return $dir;
            }
        } while( $dir = realpath("$dir/..") );
        return null;
    }*/


    /**
     * Handle the display
     */
    public function __construct() {

        add_action( 'wp_footer', array( $this, 'setup_enqueue_scripts' ), 5 );

    }

}


// INCLUDE FUNCTION FILE
include_once( 'lib/setup-video-acf.php' );
include_once( 'lib/setup-video-function.php' );


// INITIATE CLASS
$xox = new SetupVideoACF();
$sos = new SetupVideoFunc();
$oxo = new SetupVideojQuery();