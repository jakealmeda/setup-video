<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class SetupVideoACF extends SetupVideoStructure {


    /**
     * Available Image Sizes
     *
     */
    /*public function acf_setup_image_sizes( $field ) {

        $field['choices'] = array();

        foreach( get_intermediate_image_sizes() as $value ) {
            $field['choices'][$value] = $value;
        }

        return $field;

    }*/


    /**
     * Auto fill Select options
     *
     */
    public function acf_setup_load_view_html_template_choices( $field, $dir_ext ) {
        
        $file_extn = 'php';

        // get all files found in VIEWS folder
        $view_dir = $this->setup_plugin_dir_path().'templates/'.$dir_ext.'/';

        $data_from_dir = $this->setup_pulls_view_files( $view_dir, $file_extn );

        $field['choices'] = array();

        //Loop through whatever data you are using, and assign a key/value
        if( is_array( $data_from_dir ) ) {

            foreach( $data_from_dir as $field_key => $field_value ) {
                $field['choices'][$field_key] = $field_value;
            }

            return $field;

        }
        
    }


    /**
     * Function to pass directory where templates can be found (VIDEO ENTRY)
     *
     */
    public function acf_setup_video_entry_template( $field ) {

        return $this->acf_setup_load_view_html_template_choices( $field, 'video-entry' );

    }


    /**
     * Function to pass directory where templates can be found (VIDEO HEADER)
     *
     */
    /*public function acf_setup_video_header_template( $field ) {

        return $this->acf_setup_load_view_html_template_choices( $field, 'video-header' );

    }*/


    /**
     * Pull all files found in $directory but get rid of the dots that scandir() picks up in Linux environments
     *
     */
    public function setup_pulls_view_files( $directory, $file_extn ) {

        $out = array();
        
        // get all files inside the directory but remove unnecessary directories
        $ss_plug_dir = array_diff( scandir( $directory ), array( '..', '.' ) );

        foreach( $ss_plug_dir as $filename ) {
            
            if( pathinfo( $filename, PATHINFO_EXTENSION ) == $file_extn ) {
                $out[ $filename ] = pathinfo( $filename, PATHINFO_FILENAME );
            }

        }

        // Return an array of files (without the directory)
        return $out;

    }


    /**
     * Add a block category for "Setup" if it doesn't exist already.
     *
     * @ param array $categories Array of block categories.
     *
     * @ return array
     */
    public function setup_block_vid_cats( $categories ) {

        $category_slugs = wp_list_pluck( $categories, 'slug' );

        return in_array( 'setup', $category_slugs, TRUE ) ? $categories : array_merge(
            array(
                array(
                    'slug'  => 'setup',
                    'title' => __( 'Setup', 'mydomain' ),
                    'icon'  => null,
                ),
            ),
            $categories
        );

    }


    /**
     * LOG (Custom Blocks)
     * Register Custom Blocks
     * 
     */
    public function setup_block_vid_init() {

        $blocks = array(

            'setup_videos_2' => array(
                'name'                  => 'setup_videos_2',
                'title'                 => __('Videos 2.0'),
                'render_template'       => $this->setup_plugin_dir_path().'templates/blocks/block-videos.php',
                'category'              => 'setup',
                'icon'                  => 'video-alt3',
                'mode'                  => 'edit',
                'keywords'              => array( 'video', 'videos' ),
                'supports'              => [
                    'align'             => false,
                    'anchor'            => true,
                    'customClassName'   => true,
                    'jsx'               => true,
                ],
            ),

            'setup_videos_2_inout' => array(
                'name'                  => 'setup_videos_2_inout',
                'title'                 => __('Videos 2.0 IO'),
                'render_template'       => $this->setup_plugin_dir_path().'templates/blocks/block-videos-io.php',
                'category'              => 'setup',
                'icon'                  => 'video-alt3',
                'mode'                  => 'edit',
                'keywords'              => array( 'video', 'videos' ),
                'supports'              => [
                    'align'             => false,
                    'anchor'            => true,
                    'customClassName'   => true,
                    'jsx'               => true,
                ],
            ),

        );

        // Bail out if function doesn’t exist or no blocks available to register.
        if ( !function_exists( 'acf_register_block_type' ) && !$blocks ) {
            return;
        }

        foreach( $blocks as $block ) {
            acf_register_block_type( $block );
        }

    }


    /**
     * Handle the display
     */
    public function __construct() {

        add_filter( 'acf/load_field/name=video-template-global', array( $this, 'acf_setup_video_entry_template' ) );
        add_filter( 'acf/load_field/name=video-template', array( $this, 'acf_setup_video_entry_template' ) );
        add_filter( 'acf/load_field/name=video-template-global-io', array( $this, 'acf_setup_video_entry_template' ) );
        add_filter( 'acf/load_field/name=video-template-io', array( $this, 'acf_setup_video_entry_template' ) );

        // thumbnail sizes
        //add_filter( 'acf/load_field/name=video-thumb-size', array( $this, 'acf_setup_image_sizes' ) );
        //add_filter( 'acf/load_field/name=vme-thumb-size', array( $this, 'acf_setup_image_sizes' ) );

        // blocks
        add_filter( 'block_categories_all', array( $this, 'setup_block_vid_cats' ) );
        add_action( 'acf/init', array( $this, 'setup_block_vid_init' ) );

    }

}