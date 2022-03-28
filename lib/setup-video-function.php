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
    public function setup_video_acf( $block, $acf_group = FALSE )  {

        global $vars, $gcounter, $timers;

        $vid_struc = new SetupVideoStructure();
        $vid_extn = new SetupVideoFuncExtension();

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
                
                // get start time
                $vid_start = get_field( 'video-start'.$acf_group );

                // oembed
                $oembeds_io = get_field( 'video-oembeds'.$acf_group );
                if( !empty( $oembeds_io ) ) {
                    $vars[ 'oembed' ] = $oembeds_io;
                    $vars[ 'video_id' ] = $this->setup_youtube_id_regex( $oembeds_io );
                } else {
                    $vars[ 'oembed' ] = '';
                    $vars[ 'video_id' ] = '';
                }

                // video (manual) url
                $vid_url = get_field( 'video-url'.$acf_group );
                if( !empty( $vid_url ) ) {
                    $vars[ 'video_url' ] = $vid_url;
                } else {
                    $vars[ 'video_url' ] = '';
                }

                $vars[ 'thumbnail' ] = get_field( 'video-thumbnail'.$acf_group );
                $vars[ 'title' ] = get_field( 'video-title'.$acf_group );
                $vars[ 'credits' ] = get_field( 'video-credit'.$acf_group );
                $vars[ 'summary' ] = get_field( 'video-summary'.$acf_group );
                $vars[ 'video_wrap_sel' ] = get_field( 'video-section-class'.$acf_group );
                $vars[ 'video_wrap_sty' ] = get_field( 'video-section-style'.$acf_group );

                // video counter
                $gcounter++;
                $vars[ 'counts' ] = $gcounter;

                $echo_this .= $this->setup_pull_view_template( get_field( 'video-template'.$acf_group ), 'video-entry' );

                // TIMESTAMP
                // -------------------
                if( have_rows( 'video-timestamps'.$acf_group ) ):

                    $x = '';

                    $oembeds_url = get_field( 'video-oembeds'.$acf_group, FALSE, FALSE );
                    if( empty( $oembeds_url ) ) {
                        $oembeds_url = $vid_url;
                    }

                    // global template - timestamp
                    $time_template_g = get_field( 'video-time-templates'.$acf_group );

                    while( have_rows( 'video-timestamps'.$acf_group ) ) : the_row();

                        $timers[ 'title' ] = get_sub_field( 'video-time-title'.$acf_group );
                        $timers[ 'summary' ] = get_sub_field( 'video-time-summary'.$acf_group );
                        $timers[ 'start' ] = $vid_extn->setup_insert_timers( $oembeds_url, get_sub_field( 'video-time-start'.$acf_group ) );
                        $timers[ 'end' ] = $vid_extn->setup_insert_timers( $oembeds_url, get_sub_field( 'video-time-end'.$acf_group ) );
                        
                        // template
                        if( get_sub_field( 'video-time-otemplate'.$acf_group ) === TRUE ) {
                            // override
                            $x .= $this->setup_pull_view_template( get_sub_field( 'video-time-template'.$acf_group ), 'video-timestamp' );
                        } else {
                            $x .= $this->setup_pull_view_template( $time_template_g, 'video-timestamp' );
                        }

                    endwhile;

                    $echo_this .= $x;

                endif; // ACF Flexible Content Field - END

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
                                
                                // video counter
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

            // ACF Flexible Content Field
            if( have_rows( 'video-flex'.$acf_group ) ):
                while( have_rows( 'video-flex'.$acf_group ) ) : the_row();

                    // SINGLE LAYOUT
                    if( get_row_layout() == 'vbm-single' ):

                        // capture oEmbed URLs                    
                        $oembeds_io = get_sub_field( 'video-oembeds'.$acf_group );
                        if( !empty( $oembeds_io ) ) {
                            $vars[ 'oembed' ] = $oembeds_io;
                            $vars[ 'video_id' ] = $this->setup_youtube_id_regex( $oembeds_io );

                            $oembed_raw = get_sub_field( 'video-oembeds'.$acf_group, FALSE, FALSE );
                        } else {
                            $vars[ 'oembed' ] = '';
                            $vars[ 'video_id' ] = '';

                            $oembed_raw = '';
                        }

                        // capture Manual URL
                        $vars[ 'video_url' ] = get_sub_field( 'video-url'.$acf_group );
                        $video_url_raw = get_sub_field( 'video-url'.$acf_group, FALSE, FALSE );

                        // check if video is included
                        if( get_sub_field( 'video-exclude'.$acf_group ) === FALSE ) {

                            $vars[ 'thumbnail' ] = get_sub_field( 'video-thumbnail'.$acf_group );
                            
                            $vars[ 'title' ] = get_sub_field( 'video-title'.$acf_group );
                            $vars[ 'credits' ] = get_sub_field( 'video-credit'.$acf_group );
                            $vars[ 'summary' ] = get_sub_field( 'video-summary'.$acf_group );
                            //$vars[ 'input_type' ] = $vid_struc->input_type;
                            $vars[ 'video_wrap_sel' ] = get_sub_field( 'video-section-class'.$acf_group );
                            $vars[ 'video_wrap_sty' ] = get_sub_field( 'video-section-style'.$acf_group );

                            // video counter
                            $gcounter++;
                            $vars[ 'counts' ] = $gcounter;

                            // template
                            if( get_sub_field( 'video-template-override'.$acf_group ) === TRUE ) {
                                $temp_actual = get_sub_field( 'video-template'.$acf_group );
                            } else {
                                $temp_actual = $gtempate;
                            }
                        
                            $echo_this .= $this->setup_pull_view_template( $temp_actual, 'video-entry' );
                        }

                        // ACF Repeater Field
                        // check if timestamp is included
                        if( get_sub_field( 'video-exclude-time'.$acf_group ) === FALSE ) {

                            // global template - TimeStamp
                            $time_template_g = get_sub_field( 'video-time-templates'.$acf_group );
                            
                            // ACF Repeater Field | TimeStamp
                            if( have_rows( 'video-timestamps'.$acf_group ) ):
                                
                                $x = '';

                                // check URLs
                                if( !empty( $oembed_raw ) ) {
                                    $oembeds_url_raw = $oembed_raw;
                                } else {
                                    $oembeds_url_raw = $video_url_raw;
                                }
                                
                                // loop
                                while( have_rows( 'video-timestamps'.$acf_group ) ) : the_row();
                                    
                                    $timers[ 'title' ] = get_sub_field( 'video-time-title'.$acf_group );
                                    $timers[ 'summary' ] = get_sub_field( 'video-time-summary'.$acf_group );
                                    $timers[ 'start' ] = $vid_extn->setup_insert_timers( $oembeds_url_raw, get_sub_field( 'video-time-start'.$acf_group ) );
                                    $timers[ 'end' ] = $vid_extn->setup_insert_timers( $oembeds_url_raw, get_sub_field( 'video-time-end'.$acf_group ) );
                                    
                                    // template
                                    if( get_sub_field( 'video-time-otemplate'.$acf_group ) === TRUE ) {
                                        // override
                                        $x .= $this->setup_pull_view_template( get_sub_field( 'video-time-template'.$acf_group ), 'video-timestamp' );
                                    } else {
                                        $x .= $this->setup_pull_view_template( $time_template_g, 'video-timestamp' );
                                    }

                                endwhile;

                            endif;

                            $echo_this .= $x;
                            
                        }

                    endif; // SINGLE LAYOUT - END

                    // PULL LAYOUT
                    if( get_row_layout() == 'vbm-pull' ):

                        $ventry = get_sub_field( 'video-entries'.$acf_group );
                        if( is_array( $ventry ) ) {

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
                                $vars[ 'video_wrap_sel' ] = get_sub_field( 'video-section-class'.$acf_group );
                                $vars[ 'video_wrap_sty' ] = get_sub_field( 'video-section-style'.$acf_group );
                                
                                // video counter
                                $gcounter++;
                                $vars[ 'counts' ] = $gcounter;

                                // template
                                if( get_sub_field( 'video-template-override'.$acf_group ) === TRUE ) {
                                    $temp_actual = get_sub_field( 'video-template'.$acf_group );
                                } else {
                                    $temp_actual = $gtempate;
                                }
                                
                                $out .= $this->setup_pull_view_template( $temp_actual, 'video-entry' );

                            }

                            $echo_this .= $out;
                        }

                    endif; // // PULL LAYOUT - END
                    
                endwhile;
            endif; // ACF Flexible Content Field - END
           
        }
        
        // ***********************************
        // * CONTAINER
        // ***********************************
        // block class
        $bclass = $this->setup_array_validation( 'className', $block );
        if( !empty( $echo_this ) ) {

            // WRAPS OR NOT
            if( get_field( 'video-wrap-enable'.$acf_group ) === TRUE ) {

                //use wraps

                // style
                $ztyle = get_field( 'video-wrap-style'.$acf_group );
                if( empty( $ztyle ) ) {
                    $styl = '';
                } else {
                    $styl = ' style="'.$ztyle.'"';
                }

                // classes
                $clazz = get_field( 'video-wrap-class'.$acf_group );
                if( !empty( $bclass ) && !empty( $clazz ) ) {
                    $cla = ' class="'.$bclass.' '.$clazz.'"';
                } else {

                    if( !empty( $bclass ) && empty( $clazz ) ) {
                        $cla = ' class="'.$bclass.'"';
                    } elseif( empty( $bclass ) && !empty( $clazz ) ) {
                        $cla = ' class="'.$clazz.'"';
                    } else {
                        $cla = '';
                    }
                }

                echo '<'.$vid_struc->contet_wrapper.$cla.$styl.'>'.$echo_this.'</'.$vid_struc->contet_wrapper.'>';

            } else {

                // no wraps & block class
                if( empty( $bclass ) ) {
                    echo $echo_this;
                } else {
                    echo '<'.$vid_struc->contet_wrapper.' class="'.$bclass.'">'.$echo_this.'</div>';
                }

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
    public function setup_embed_sc( $vid ) {

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