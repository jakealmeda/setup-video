<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class SetupVideoFunc {

    // intialize counter for sections
    public $vid_counter = 0;

    // input type text | change to show or hide
    public $input_type = 'hidden'; // either TEXT or HIDDEN

    /**
     * Main function
     */
    public function setup_video_acf( $acf_group = FALSE ) {
        
        // declare empty variables
        $outz = '';
        $section_style = '';

        // 1st group
        $vid_det = get_field( 'video-main'.$acf_group );
        // 2nd group
        $vid_multi = get_field( 'video-multi'.$acf_group );
        
        if( $vid_det[ 'video-status' ] == 'enabled' ) :
            
            /**
             * MAIN VIDEO
             */ 
            if( $vid_det[ 'video-showhide' ] === TRUE ) {

                global $vars;

                $vid_dimensions = new SetupVideoStructure();
                $vid_details = $vid_dimensions->setup_video_size();

                // VIDEO COUNTER
                $this->vid_counter++;
                $vars[ 'counts' ] = $this->vid_counter;

                // VIDEO TITLE
                $vtitle = $vid_det[ 'video-title' ];
                if( !empty( $vtitle ) ) {
                    $vars[ 'title' ] = $vtitle;

                    $display = 1; // variable to check if something's for display
                } else {
                    $vars[ 'title' ] = '';
                }

                // VIDEO URL
                $vurl = $vid_det[ 'video-url' ];
                if( !empty( $vurl ) ) {
                    $vars[ 'video_url' ] = $this->setup_embed_sc( '[embed width="'.$vid_details[ "width" ].'" height="'.$vid_details[ "height" ].'"]'.$vurl.'[/embed]' );
                    //$vars[ 'video_url_raw' ] = $vurl;

                    $p_url = parse_url( $vurl );
                    if( array_key_exists( 'host', $p_url ) && !empty( $p_url[ 'host' ] ) ) {

                        // YOUTUBE
                        if( in_array( $p_url[ 'host' ], $vid_dimensions->domain_yt ) ) :

                            $vtyp = 'yt';
                            $v_id = $this->setup_youtube_id_regex( $vurl );

                        endif;

                        if( in_array( $p_url[ 'host' ], $vid_dimensions->domain_vimeo ) ) :

                            $vtyp = 'vi';
                            $v_id = (int) substr( parse_url( $vurl, PHP_URL_PATH ), 1 );

                        endif;

                    }

                    $display = 1; // variable to check if something's for display
                } else {
                    // declare empty variables
                    $vars[ 'video_url' ] = '';
                    $vtyp = '';
                    $v_id = '';
                }
                
                // VIDEO SUMMARY
                $vsummary = $vid_det[ 'video-summary' ];
                if( !empty( $vsummary ) ) {
                    $vars[ 'summary' ] = $vsummary;

                    $display = 1; // variable to check if something's for display
                } else {
                    $vars[ 'summary' ] = '';
                }

                // VIDEO CREDITS
                $vcreds = $vid_det[ 'video-credits' ];
                if( !empty( $vcreds ) ) {
                    $vars[ 'credits' ] = $vcreds;

                    $display = 1; // variable to check if something's for display
                } else {
                    $vars[ 'credits' ] = '';
                }

                // VIDEO THUMBNAIL
                $vthumb = $vid_det[ 'video-thumb-image' ];
                if( !empty( $vthumb ) ) {
                    $vars[ 'thumbnail' ] = wp_get_attachment_image( $vthumb, $vid_det[ 'video-thumb-size' ] );

                    $display = 1; // variable to check if something's for display
                } else {

                    // GET ACTUAL YOUTUBE/VIMEO THUMBNAIL
                    if( !empty( $vurl ) ) {

                        // YOUTUBE
                        if( $vtyp == 'yt' ) :

                            /*
                            https://img.youtube.com/vi/<insert-youtube-video-id-here>/0.jpg
                            https://img.youtube.com/vi/<insert-youtube-video-id-here>/1.jpg
                            https://img.youtube.com/vi/<insert-youtube-video-id-here>/2.jpg
                            https://img.youtube.com/vi/<insert-youtube-video-id-here>/3.jpg
                            */

                            $vars[ 'thumbnail' ] = '<img src="https://img.youtube.com/vi/'.$this->setup_youtube_id_regex( $vurl ).'/hqdefault.jpg" border="0" />';

                            $display = 1; // variable to check if something's for display

                        endif;

                        // VIMEO
                        if( $vtyp == 'vi' ) :

                            $vim_id = (int) substr( parse_url( $vurl, PHP_URL_PATH ), 1 );
                            $dataz = file_get_contents( "https://vimeo.com/api/v2/video/".$vim_id.".json" );
                            $dataz = json_decode($dataz);

                            $vars[ 'thumbnail' ] = '<img src="'.$dataz[0]->thumbnail_large.'" border="0" />';

                            $display = 1; // variable to check if something's for display

                        endif;

                    } else{
                        $vars[ 'thumbnail' ] = '';
                    }

                }

                // CSS / STYLES
                $vid_det_style = $vid_det[ 'video-section-wrap' ];

                // CSS / STYLES - MAIN VIDEO | CONTAINER - CSS SELECTORS
                $vds_wrap_sel = $vid_det_style[ 'video-section-class' ];
                if( !empty( $vds_wrap_sel ) ) {
                    $vars[ 'video_wrap_sel' ] = $vds_wrap_sel;
                } else {
                    $vars[ 'video_wrap_sel' ] = '';
                }

                // CSS / STYLES - MAIN VIDEO | CONTAINER - INLINE STYLES
                $vds_wrap_sty = $vid_det_style[ 'video-section-style' ];
                if( !empty( $vds_wrap_sty ) ) {
                    $vars[ 'video_wrap_sty' ] = $vds_wrap_sty;
                } else {
                    $vars[ 'video_wrap_sty' ] = '';
                }
                
                // VIDEO TEMPLATE
                if( $vid_det[ 'video-template-override' ] === TRUE ) {
                    // OVERRIDE
                    $layout = $vid_det[ 'video-template' ];
                } else {
                    // GLOBAL
                    $layout = $vid_det[ 'video-template-global' ];
                }
                
                // Display only if there's any content
                if( !empty( $display ) ) {
                    // DISPLAY USING TEMPLATE
                    $outz .= $this->setup_pull_view_template( $layout, 'video-entry' );

                    // include the raw video url for jQuery
                    if( !empty( $vurl ) ) {
                        //$outz .= '<input type="'.$this->input_type.'" id="vlink__'.$this->vid_counter.'" value="'.$vurl.'" />';
                        $outz .= '<input type="'.$this->input_type.'" id="vtype__'.$this->vid_counter.'" value="'.$vtyp.'" />';
                        $outz .= '<input type="'.$this->input_type.'" id="vidid__'.$this->vid_counter.'" value="'.$v_id.'" />';
                    }
                }

                // OUTPUT CONTAINER
                if( $vid_det[ 'video-container-enable' ] === TRUE ) {
                    $section_style = array(
                        'css'       => $vid_det[ 'video-container-class' ],
                        'inline'    => $vid_det[ 'video-container-style' ],
                    );
                }

            }

            /**
             * MULTI VIDEO
             */ 
            $outz .= $this->setup_additional_video( $vid_multi, $vid_det[ 'video-template-global' ] );

        endif;

        if( is_array( $section_style ) ) {

            // CONTAINER (WRAP) | CSS
            $sec_css = $this->setup_array_validation( 'css', $section_style, array( 'attr' => 'selectors' ) );
            // CONTAINER (WRAP) | INLINE STYLE
            $sec_style = $this->setup_array_validation( 'inline', $section_style, array( 'attr' => 'inline' ) );
            if( !empty( $sec_style ) ) {
                $sec_style = ' style="'.$sec_style.'"';
            } else {
                $sec_style = '';
            }

            echo '<section class="section-video'.$sec_css.'"'.$sec_style.'>'.$outz.'</section>';
        } else {
            echo $outz;
        }
        
    }


    /**
     * MULTI VIDEO FUNCTION
     */
    public function setup_additional_video( $vid_flex, $global_template ) {

        $out = ''; // declare empty variable

        if( is_array( $vid_flex ) ) {

            //global $title, $video_url, $summary, $credits, $thumbnail;
            global $vars;

            for( $x=0; $x<=( count( $vid_flex ) - 1); $x++ ) {

                $display = ''; // declare empty variable

                if( $vid_flex[ $x ][ 'acf_fc_layout' ] == 'video-multi-entry' ) :

                    if( $vid_flex[ $x ][ 'vme-showhide' ] === TRUE ) {

                        $vid_dimensions = new SetupVideoStructure();
                        $vid_details = $vid_dimensions->setup_video_size();

                        // VIDEO COUNTER
                        $this->vid_counter++;
                        $vars[ 'counts' ] = $this->vid_counter;

                        // VIDEO TITLE
                        $vtitle = $vid_flex[ $x ][ 'vme-title' ];
                        if( !empty( $vtitle ) ) {
                            $vars[ 'title' ] = $vtitle;

                            $display = 1; // variable to check if something's for display
                        } else {
                            $vars[ 'title' ] = '';
                        }

                        // VIDEO URL
                        $vurl = $vid_flex[ $x ][ 'vme-url' ];
                        if( !empty( $vurl ) ) {
                            $vars[ 'video_url' ] = $this->setup_embed_sc( '[embed width="'.$vid_details[ "width" ].'" height="'.$vid_details[ "height" ].'"]'.$vurl.'[/embed]' );
                            //$vars[ 'video_url_raw' ] = $vurl;

                            $p_url = parse_url( $vurl );
                            if( array_key_exists( 'host', $p_url ) && !empty( $p_url[ 'host' ] ) ) {

                                // YOUTUBE
                                if( in_array( $p_url[ 'host' ], $vid_dimensions->domain_yt ) ) :

                                    $vtyp = 'yt';
                                    $v_id = $this->setup_youtube_id_regex( $vurl );

                                endif;

                                if( in_array( $p_url[ 'host' ], $vid_dimensions->domain_vimeo) ) :

                                    $vtyp = 'vi';
                                    $v_id = (int) substr( parse_url( $vurl, PHP_URL_PATH ), 1 );

                                endif;

                            }

                            $display = 1; // variable to check if something's for display
                        } else {
                            // declare empty variables
                            $vars[ 'video_url' ] = '';
                            $vtyp = '';
                            $v_id = '';
                        }

                        // VIDEO SUMMARY
                        $vsummary = $vid_flex[ $x ][ 'vme-summary' ];
                        if( !empty( $vsummary ) ) {
                            $vars[ 'summary' ] = $vsummary;

                            $display = 1; // variable to check if something's for display
                        } else {
                            $vars[ 'summary' ] = '';
                        }

                        // VIDEO CREDITS
                        $vcreds = $vid_flex[ $x ][ 'vme-credits' ];
                        if( !empty( $vcreds ) ) {
                            $vars[ 'credits' ] = $vcreds;

                            $display = 1; // variable to check if something's for display
                        } else {
                            $vars[ 'credits' ] = '';
                        }

                        // VIDEO THUMBNAIL
                        $vthumb = $vid_flex[ $x ][ 'vme-thumb-image' ];
                        if( !empty( $vthumb ) ) {
                            $vars[ 'thumbnail' ] = wp_get_attachment_image( $vthumb, $vid_flex[ $x ][ 'vme-thumb-size' ] );

                            $display = 1; // variable to check if something's for display
                        } else {

                            // GET ACTUAL YOUTUBE/VIMEO THUMBNAIL
                            if( !empty( $vurl ) ) {

                                // YOUTUBE
                                if( $vtyp == 'yt' ) :

                                    $vars[ 'thumbnail' ] = '<img src="https://img.youtube.com/vi/'.$this->setup_youtube_id_regex( $vurl ).'/hqdefault.jpg" border="0" />';

                                    $display = 1; // variable to check if something's for display

                                endif;

                                // VIMEO
                                if( $vtyp == 'vi' ) :

                                    $vim_id = (int) substr( parse_url( $vurl, PHP_URL_PATH ), 1 );
                                    $dataz = file_get_contents( "https://vimeo.com/api/v2/video/".$vim_id.".json" );
                                    $dataz = json_decode($dataz);

                                    $vars[ 'thumbnail' ] = '<img src="'.$dataz[0]->thumbnail_large.'" border="0" />';
                                    
                                    $display = 1; // variable to check if something's for display

                                endif;

                            } else{
                                $vars[ 'thumbnail' ] = '';
                            }

                        }

                        // CSS / STYLES
                        $vid_det_style = $vid_flex[ $x ][ 'vme-section-wrap' ];

                        // CSS / STYLES - FLEX VIDEO | CONTAINER - CSS SELECTORS
                        $vds_wrap_sel = $vid_det_style[ 'vme-section-class' ];
                        if( !empty( $vds_wrap_sel ) ) {
                            $vars[ 'video_wrap_sel' ] = $vds_wrap_sel;
                        } else {
                            $vars[ 'video_wrap_sel' ] = '';
                        }

                        // CSS / STYLES - FLEX VIDEO | CONTAINER - INLINE STYLES
                        $vds_wrap_sty = $vid_det_style[ 'vme-section-style' ];
                        if( !empty( $vds_wrap_sty ) ) {
                            $vars[ 'video_wrap_sty' ] = $vds_wrap_sty;
                        } else {
                            $vars[ 'video_wrap_sty' ] = '';
                        }
                        
                        // VIDEO TEMPLATE
                        if( $vid_flex[ $x ][ 'vme-template-override' ] === TRUE ) {
                            $layout = $vid_flex[ $x ][ 'vme-template' ];
                        } else {
                            $layout = $global_template;
                        }

                        // Display only if there's any content
                        if( !empty( $display ) ) {
                            $out .= $this->setup_pull_view_template( $layout, 'video-entry' );

                            // include the raw video url for jQuery
                            if( !empty( $vurl ) ) {
                                //$out .= '<input type="'.$this->input_type.'" id="vlink__'.$this->vid_counter.'" value="'.$vurl.'" />';
                                $out .= '<input type="'.$this->input_type.'" id="vtype__'.$this->vid_counter.'" value="'.$vtyp.'" />';
                                $out .= '<input type="'.$this->input_type.'" id="vidid__'.$this->vid_counter.'" value="'.$v_id.'" />';
                            }
                        }

                    }

                endif;
                
                if( $vid_flex[ $x ][ 'acf_fc_layout' ] == 'video-multi-heading' ) :

                    $outs = ''; // declare empty variable

                    if( $vid_flex[ $x ][ 'vmh-showhide' ] === TRUE ) {

                        // DISPLAY USING TEMPLATE
                        global $title, $content;

                        // HEADER TITLE
                        $vtitle = $vid_flex[ $x ][ 'vmh-title' ];
                        if( !empty( $vtitle ) ) {
                            $vars[ 'title' ] = $vtitle;

                            $display = 1; // variable to check if something's for display
                        } else {
                            $vars[ 'title' ] = '';
                        }

                        // VIDEO TITLE
                        $vcontent = $vid_flex[ $x ][ 'vmh-content' ];
                        if( !empty( $vcontent ) ) {
                            $vars[ 'content' ] = $vcontent;

                            $display = 1; // variable to check if something's for display
                        } else {
                            $vars[ 'content' ] = '';
                        }

                        // CSS / STYLES
                        $vid_head_style = $vid_flex[ $x ][ 'vmh-section-wrap' ];

                        // CSS / STYLES - HEADER | CONTAINER - CSS SELECTORS
                        $vdh_wrap_sel = $vid_head_style[ 'vmh-section-class' ];
                        if( !empty( $vdh_wrap_sel ) ) {
                            $vars[ 'video_wrap_sel' ] = $vdh_wrap_sel;
                        } else {
                            $vars[ 'video_wrap_sel' ] = '';
                        }

                        // CSS / STYLES - HEADER | CONTAINER - INLINE STYLES
                        $vdh_wrap_sty = $vid_head_style[ 'vmh-section-style' ];

                        if( !empty( $vdh_wrap_sty ) ) {
                            $vars[ 'video_wrap_sty' ] = $vdh_wrap_sty;
                        } else {
                            $vars[ 'video_wrap_sty' ] = '';
                        }

                        // Display only if there's any content
                        if( !empty( $display ) ) {
                            $out .= $this->setup_pull_view_template( $vid_flex[ $x ][ 'vmh-template' ], 'video-header' );
                        }

                    } // if( $vid_flex[ $x ][ 'vmh-showhide' ] === TRUE ) {
                    
                endif;
                
            }

        }

        return $out;

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

    	return $GLOBALS[ 'wp_embed' ]->run_shortcode( $vid );

    }


    /**
     * Array validation
     */
    public function setup_array_validation( $needles, $haystacks, $args = FALSE ) {

        if( is_array( $haystacks ) && array_key_exists( $needles, $haystacks ) && !empty( $haystacks[ $needles ] ) ) {

            $attribute = $haystacks[ $needles ];

            if( !empty( $attribute ) && $args[ 'attr' ] == 'selectors' ) {

                return ' '.$attribute;

            } elseif( !empty( $attribute ) && $args[ 'attr' ] == 'inline' ) {

                return ' '.$attribute;

            } else {

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
    public function __construct() {

        if ( !is_admin() ) {

            add_action( 'genesis_entry_content', array( $this, 'setup_video_acf' ) );

        }

    }

}