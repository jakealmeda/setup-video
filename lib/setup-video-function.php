<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $gcounter;

class SetupVideoFunc {

    // intialize counter for sections (div)
    //public $vid_counter = 0;

    // initialize counter for max video count to be shown
    //public $video_count = 0;

    /**
     * Main function
     */
    public function setup_video_acf( $acf_group = FALSE ) {

        global $vars, $gcounter;

        $vid_struc = new SetupVideoStructure();

        // set input type for individual entries
        $vars[ 'input_type' ] = $vid_struc->input_type;
        // pass which group
        $vars[ 'acf_group' ] = $acf_group;

        $echo_this = '';

        $global_template = get_field( 'video-template-global'.$acf_group );

        // ***********************************
        // * SINGLE
        // ***********************************
        if( !empty( $acf_group ) && $acf_group == '-vbs' ) {
            
            if( get_field( 'video-exclude'.$acf_group ) === FALSE ) {
                
                $oembeds_io = get_field( 'video-oembeds'.$acf_group );
                if( !empty( $oembeds_io ) ) {
                    $vars[ 'oembed' ] = $oembeds_io;
                    $vars[ 'video_id' ] = $this->setup_youtube_id_regex( $oembeds_io );
                } else {
                    $vars[ 'oembed' ] = '';
                    $vars[ 'video_id' ] = '';
                }

                $vars[ 'thumbnail' ] = get_field( 'video-thumbnail'.$acf_group );
                $vars[ 'video_url' ] = get_field( 'video-url'.$acf_group );
                $vars[ 'title' ] = get_field( 'video-title'.$acf_group );
                $vars[ 'credits' ] = get_field( 'video-credit'.$acf_group );
                $vars[ 'summary' ] = get_field( 'video-summary'.$acf_group );
                $vars[ 'video_wrap_sel' ] = get_field( 'video-section-class'.$acf_group );
                $vars[ 'video_wrap_sty' ] = get_field( 'video-section-style'.$acf_group );

                // video counter
                //$this->vid_counter++;
                //$vars[ 'counts' ] = $this->vid_counter; // templates use this variable
                $gcounter++;
                $vars[ 'counts' ] = $gcounter;

                $echo_this .= $this->setup_pull_view_template( get_field( 'video-template'.$acf_group ), 'video-entry' );

            }

        }

        // ***********************************
        // * PULL
        // ***********************************
        $ve = get_field( 'video-entry' );
        if( is_array( $ve ) ) {

            for( $x=0; $x<=( count( $ve ) - 1 ); $x++ ) {

                $entry = $ve[ $x ]; // assign to a variable to avoid too much brackets

                if( count( $entry ) >= 1 && is_array( $entry ) ) {

                    // check if included
                    if( $entry[ 'video-exclude' ] === FALSE ) {

                        $out = ''; // declare empty variable for the next loop

                        // template
                        $template_override = $entry[ 'video-template-override' ];
                        if( $template_override === TRUE ) {
                            $use_temp = $entry[ 'video-template' ];
                        } else {
                            $use_temp = $global_template;
                        }

                        // class
                        $template_class = $entry[ 'video-section-class' ];
                        if( empty( $template_class ) ) {
                            $vars[ 'video_wrap_sel' ] = '';
                        } else {
                            $vars[ 'video_wrap_sel' ] = ' '.$template_class;
                        }

                        // style
                        $template_style = $entry[ 'video-section-style' ];
                        if( empty( $template_style ) ) {
                            $vars[ 'video_wrap_sty' ] = '';
                        } else {
                            $vars[ 'video_wrap_sty' ] = $template_style;
                        }

                        // get default thumbnail size
                        $vars[ 'def_thumb_size' ] = $vid_struc->def_thumb_size;

                        // entries
                        $video_entries_array = $entry[ 'video-entries' ];
                        if( is_array( $video_entries_array ) ) {

                            foreach( $video_entries_array as $vid ) {
                                
                                $oembeds = get_field( 'video-oembeds', $vid );
                                if( !empty( $oembeds ) ) {
                                    $vars[ 'oembed' ] = $oembeds;
                                    $vars[ 'video_id' ] = $this->setup_youtube_id_regex( $oembeds );
                                } else {
                                    $vars[ 'oembed' ] = '';
                                    $vars[ 'video_id' ] = '';
                                }

                                $vars[ 'thumbnail' ] = get_field( 'video-thumbnail', $vid );
                                $vars[ 'video_url' ] = get_field( 'video-url', $vid );
                                $vars[ 'title' ] = get_field( 'video-title', $vid );
                                $vars[ 'credits' ] = get_field( 'video-credit', $vid );
                                $vars[ 'summary' ] = get_field( 'video-summary', $vid );
                                //$vars[ 'input_type' ] = $vid_struc->input_type;
                                
                                // video counter
                                //$this->vid_counter++;
                                //$vars[ 'counts' ] = $this->vid_counter; // templates use this variable
                                $gcounter++;
                                $vars[ 'counts' ] = $gcounter;
                                
                                $out .= $this->setup_pull_view_template( $use_temp, 'video-entry' );

                            }

                            $echo_this .= $out;
                        }

                    }

                } // if( count( $entry ) >= 1 && is_array( $entry ) ) {

            } // for( $x=0; $x<=( count( $ve ) - 1 ); $x++ ) {
            
        } // if( is_array( $ve ) ) {
        
        // ***********************************
        // * MULTI
        // ***********************************
        if( !empty( $acf_group ) && $acf_group == '-vbm' ) {

            // get global template
            $gtempate = get_field( 'video-template-global'.$acf_group );

            $vflex = get_field( 'video-flex'.$acf_group );
            foreach( $vflex as $ventries ) {
                //var_dump( $ventries );
                
                $vormat = $ventries[ 'acf_fc_layout' ];

                // Single
                if( $vormat == 'vbm-single' && empty( $ventries[ 'video-exclude'.$acf_group ] ) ) {

                    $oembeds_io = $ventries[ 'video-oembeds'.$acf_group ];
                    if( !empty( $oembeds_io ) ) {
                        $vars[ 'oembed' ] = $oembeds_io;
                        $vars[ 'video_id' ] = $this->setup_youtube_id_regex( $oembeds_io );
                    } else {
                        $vars[ 'oembed' ] = '';
                        $vars[ 'video_id' ] = '';
                    }

                    $vars[ 'thumbnail' ] = $ventries[ 'video-thumbnail'.$acf_group ];
                    $vars[ 'video_url' ] = $ventries[ 'video-url'.$acf_group ];
                    $vars[ 'title' ] = $ventries[ 'video-title'.$acf_group ];
                    $vars[ 'credits' ] = $ventries[ 'video-credit'.$acf_group ];
                    $vars[ 'summary' ] = $ventries[ 'video-summary'.$acf_group ];
                    //$vars[ 'input_type' ] = $vid_struc->input_type;
                    $vars[ 'video_wrap_sel' ] = $ventries[ 'video-section-class'.$acf_group ];
                    $vars[ 'video_wrap_sty' ] = $ventries[ 'video-section-style'.$acf_group ];

                    // video counter
                    //$this->vid_counter++;
                    //$vars[ 'counts' ] = $this->vid_counter; // templates use this variable
                    $gcounter++;
                    $vars[ 'counts' ] = $gcounter;

                    // template
                    if( $ventries[ 'video-template-override'.$acf_group ] === TRUE ) {
                        $temp_actual = $ventries[ 'video-template'.$acf_group ];
                    } else {
                        $temp_actual = $gtempate;
                    }
                
                    $echo_this .= $this->setup_pull_view_template( $temp_actual, 'video-entry' );

                } // end of Single

                // Pull
                if( $vormat == 'vbm-pull' && empty( $ventries[ 'video-exclude'.$acf_group ] ) ) {

                    $ventry = $ventries[ 'video-entries'.$acf_group ];
                    if( is_array( $ventry ) ) {

                        //$echo_this .= $this->setup_process_pull_entries( $ventry, $acf_group );
                        $out = '';
                        foreach( $ventry as $vid ) {

                            $oembeds = get_field( 'video-oembeds', $vid );
                            if( !empty( $oembeds ) ) {
                                $vars[ 'oembed' ] = $oembeds;
                                $vars[ 'video_id' ] = $this->setup_youtube_id_regex( $oembeds );
                            } else {
                                $vars[ 'oembed' ] = '';
                                $vars[ 'video_id' ] = '';
                            }

                            $vars[ 'thumbnail' ] = get_field( 'video-thumbnail', $vid );
                            $vars[ 'video_url' ] = get_field( 'video-url', $vid );
                            $vars[ 'title' ] = get_field( 'video-title', $vid );
                            $vars[ 'credits' ] = get_field( 'video-credit', $vid );
                            $vars[ 'summary' ] = get_field( 'video-summary', $vid );
                            //$vars[ 'input_type' ] = $vid_struc->input_type;
                            $vars[ 'video_wrap_sel' ] = $ventries[ 'video-section-class'.$acf_group ];
                            $vars[ 'video_wrap_sty' ] = $ventries[ 'video-section-style'.$acf_group ];
                            
                            // video counter
                            //$this->vid_counter++;
                            //$vars[ 'counts' ] = $this->vid_counter; // templates use this variable
                            $gcounter++;
                            $vars[ 'counts' ] = $gcounter;

                            // template
                            if( $ventries[ 'video-template-override'.$acf_group ] === TRUE ) {
                                $temp_actual = $ventries[ 'video-template'.$acf_group ];
                            } else {
                                $temp_actual = $gtempate;
                            }
                            
                            $out .= $this->setup_pull_view_template( $temp_actual, 'video-entry' );

                        }

                        $echo_this .= $out;
                    }

                } // end of Pull

            } // end of foreach( $vflex as $ventries ) {

        }

        // ***********************************
        // * CONTAINER
        // ***********************************
        if( !empty( $echo_this ) ) {

            // WRAPS OR NOT
            if( get_field( 'video-wrap-enable'.$acf_group ) === TRUE ) {

                //use wraps

                // class
                $clazz = get_field( 'video-wrap-class'.$acf_group );
                if( empty( $clazz ) ) {
                    $cla = '';
                } else {
                    $cla = ' class="'.$clazz.'"';
                }

                // style
                $ztyle = get_field( 'video-wrap-style'.$acf_group );
                if( empty( $ztyle ) ) {
                    $styl = '';
                } else {
                    $styl = ' style="'.$ztyle.'"';
                }

                echo '<'.$vid_struc->contet_wrapper.$cla.$styl.'>'.$echo_this.'</'.$vid_struc->contet_wrapper.'>';

            } else {

                // no wraps
                echo $echo_this;

            }

        } // if( !empty( $echo_this ) ) {

    }
    

    /**
     * Get VIEW template | this function is called by SETUP-LOG-FLEX.PHP found in PARTIALS/BLOCKS folder
     */
    public function setup_pull_view_template( $layout, $dir_ext ) {

        $o = new SetupVideoStructure();

        $layout_file = $o->setup_plugin_dir_path().'templates/'.$dir_ext.'/'.$layout;

        if( is_file( $layout_file ) ) {

            ob_start();

            include $layout_file;

            $new_output = ob_get_clean();

            if( !empty( $new_output ) )
                $output = $new_output;

        } else {

            $output = FALSE;

        }

        return $output;

    }


    /**
     * WP Native Global Embed code
     */
    private function setup_embed_sc( $vid ) {

        $main_class = new SetupVideoStructure();
        $mc = $main_class->setup_video_size();
        
        return $GLOBALS[ 'wp_embed' ]->run_shortcode( '[embed width="'.$mc[ "width" ].'" height="'.$mc[ "height" ].'"]'.$vid.'[/embed]' );

    }


    /**
     * Array validation
     */
    public function setup_array_validation( $needles, $haystacks, $args = FALSE ) {

        if( is_array( $haystacks ) && array_key_exists( $needles, $haystacks ) && !empty( $haystacks[ $needles ] ) ) {

            $attribute = $haystacks[ $needles ];

            if( !empty( $attribute ) && $args[ 'attr' ] == 'selectors' ) {

                return ' '.$attribute;

            }/* elseif( !empty( $attribute ) && $args[ 'attr' ] == 'inline' ) {

                return $attribute;

            } */else {

                return $attribute;

            }

        } else {

            return FALSE;

        }

    }


    /**
     * Filter YouTube ID
     */
    public function setup_youtube_id_regex( $url ) {

        $parts = parse_url($url);

        if( isset( $parts[ 'query' ] ) ){

            parse_str( $parts[ 'query' ], $qs );

            if( isset( $qs[ 'v' ] ) ){
                return $qs[ 'v' ];
            } elseif( isset( $qs[ 'vi' ] ) ){
                return $qs[ 'vi' ];
            }

        }

        if( isset( $parts[ 'path' ] ) ){

            $path = explode( '/', trim( $parts[ 'path' ], '/' ) );
            return $path[ count( $path ) - 1 ];

        }

        return false;
        
    }

    
    /**
     * Handle the display
     */
    /*public function __construct() {

        if ( !is_admin() ) {

            $mha = new SetupVideoStructure();

            add_action( $mha->usehook, array( $this, 'setup_video_acf' ) );

        }

    }*/

}