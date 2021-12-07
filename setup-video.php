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

    public $usehook = 'genesis_entry_content';

    public $show_num_vids = 100; // number of videos to show

    // control what videos to show
    public $type_of_vids = 'default'; // show videos based on each entry's arrangement
//    public $type_of_vids = array( 'youtube', 'header' );
//    public $type_of_vids = array( 'vimeo' );
//    public $type_of_vids = array( 'vimeo', 'youtube' );
//    public $type_of_vids = array( 'youtube', 'vimeo', 'rumble' );

    // default video dimensions | Youtube & Vimeo
    public function setup_video_size() {

        return $sizes = array(
            'width'     =>  '560',
            'height'    =>  '315',
        );

    }

    // default video dimensions | Rumble
    public function setup_rumble_video_size() {

        return $sizes = array(
            'width'     =>  '640',
            'height'    =>  '360',
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

    // array of Rumble URLs
    public $domain_rumble = array(
        'rumble.com',
    );

    // input type text | change to show or hide for each video
    public $input_type = 'hidden'; // either TEXT or HIDDEN

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