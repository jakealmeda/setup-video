<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class SetupVideoFunc {

    // intialize counter for sections (div)
    public $div_counter = 0;

    // initialize counter for max video count to be shown
    //public $video_count = 0;

    /**
     * Main function
     */
    public function setup_video_acf( $acf_group = FALSE ) {

        global $vars;

        $vid_struc = new SetupVideoStructure();

        $echo_this = '';

        $global_template = get_field( 'video-template-global' );

        // pull entries
        $ve = get_field( 'video-entry' );

        // VALIDATE IF VIDEOS ARE TO BE DISPLAYED
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
                            $vars[ 'video_wrap_sel' ] = ' class="'.$template_class.'"';
                        }

                        // style
                        $template_style = $entry[ 'video-section-style' ];
                        if( empty( $template_style ) ) {
                            $vars[ 'video_wrap_sty' ] = '';
                        } else {
                            $vars[ 'video_wrap_sty' ] = ' style="'.$template_style.'"';
                        }

                        // entries
                        foreach( $entry[ 'video-entries' ] as $vid ) {
                            //var_dump( get_post_meta( $vid ) );
                            $vars[ 'eid' ] = $vid;
                            $vars[ 'oembed' ] = get_field( 'video-oembeds', $vid );
                            $vars[ 'thumbnail' ] = get_field( 'video-thumbnail', $vid );
                            $vars[ 'video_url' ] = get_field( 'video-url', $vid );
                            $vars[ 'title' ] = get_field( 'video-title', $vid );
                            $vars[ 'credits' ] = get_field( 'video-credit', $vid );
                            $vars[ 'summary' ] = get_field( 'video-summary', $vid );

                            // video counter
                            $this->div_counter++;
                            $vars[ 'counts' ] = $this->div_counter; // templates use this variable

                            $out .= $this->setup_pull_view_template( $use_temp, 'video-entry' );

                        }

                        $echo_this .= $out;

                    }

                } // if( count( $entry ) >= 1 && is_array( $entry ) ) {

            } // for( $x=0; $x<=( count( $ve ) - 1 ); $x++ ) {
            
        } // if( is_array( $ve ) ) {


        if( !empty( $echo_this ) ) {

            // WRAPS OR NOT
            if( get_field( 'video-wrap-enable' ) === TRUE ) {

                //use wraps

                // class
                $clazz = get_field( 'video-wrap-class' );
                if( empty( $clazz ) ) {
                    $cla = '';
                } else {
                    $cla = ' class="'.$clazz.'"';
                }

                // style
                $ztyle = get_field( 'video-wrap-style' );
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


    /*public function setup_process_video_entry( $args ) {

        global $vars;

        $outz = '';

        $vid_dimensions = new SetupVideoStructure();
        $vid_details = $vid_dimensions->setup_video_size();
        $vid_details_rumble = $vid_dimensions->setup_rumble_video_size();

        // VIDEO COUNTER
        $this->div_counter++;
        $vars[ 'counts' ] = $this->div_counter; // templates use this variable

        // VIDEO TITLE
        $vtitle = $this->setup_array_validation( 'title', $args );
        if( !empty( $vtitle ) ) {
            $vars[ 'title' ] = $vtitle;

            $display = 1; // variable to check if something's for display
        } else {
            $vars[ 'title' ] = '';
        }

        // VIDEO CONTENT
        $vcontent = $this->setup_array_validation( 'content', $args );
        if( !empty( $vcontent ) ) {
            $vars[ 'content' ] = $vcontent;

            $display = 1; // variable to check if something's for display
        } else {
            $vars[ 'content' ] = '';
        }

        // VIDEO URL
        $vurl = $this->setup_array_validation( 'url', $args );
        if( !empty( $vurl ) ) {
            $vars[ 'video_url' ] = $this->setup_embed_sc( '[embed width="'.$vid_details[ "width" ].'" height="'.$vid_details[ "height" ].'"]'.$vurl.'[/embed]' );
            //$vars[ 'video_url_raw' ] = $vurl;

            $p_url = parse_url( $vurl );
            if( array_key_exists( 'host', $p_url ) && !empty( $p_url[ 'host' ] ) ) {

                // YOUTUBE
                if( in_array( $p_url[ 'host' ], $vid_dimensions->domain_yt ) ) :

                    $vtyp = 'youtube';
                    $v_id = $this->setup_youtube_id_regex( $vurl );

                endif;

                if( in_array( $p_url[ 'host' ], $vid_dimensions->domain_vimeo ) ) :

                    $vtyp = 'vimeo';
                    $v_id = (int) substr( parse_url( $vurl, PHP_URL_PATH ), 1 );

                endif;

                if( in_array( $p_url[ 'host' ], $vid_dimensions->domain_rumble ) ) :

                    $par_url = explode( '/', $p_url[ 'path' ] );

                    $vtyp = 'rumble';
                    $v_id = $par_url[ 2 ];

                    $vars[ 'video_url' ] = '<iframe class="rumble" width="'.$vid_details_rumble[ "width" ].'" height="'.$vid_details_rumble[ "height" ].'" src="'.$vurl.'" frameborder="0" allowfullscreen></iframe>';
                    / *
                        sample
                        <iframe class="rumble" width="640" height="360" src="https://rumble.com/embed/vnckqr/?pub=4" frameborder="0" allowfullscreen></iframe>
                        https://rumble.com/embed/vnckqr/?pub=4
                        
                        -------
                        
                        - IFRAME -
                        <iframe class="rumble" width="640" height="360" src="https://rumble.com/embed/vn7ykt/?pub=4" frameborder="0" allowfullscreen></iframe>
                        - VIDEO URL -
                        https://rumble.com/embed/vn7ykt/?pub=4

                        - JS EMBED -
                        <script>!function(r,u,m,b,l,e){r._Rumble=b,r[b]||(r[b]=function(){(r[b]._=r[b]._||[]).push(arguments);if(r[b]._.length==1){l=u.createElement(m),e=u.getElementsByTagName(m)[0],l.async=1,l.src="https://rumble.com/embedJS/u4"+(arguments[1].video?'.'+arguments[1].video:'')+"/?url="+encodeURIComponent(location.href)+"&args="+encodeURIComponent(JSON.stringify([].slice.apply(arguments))),e.parentNode.insertBefore(l,e)}})}(window, document, "script", "Rumble");</script>
                        <div id="rumble_vn7ykt"></div>
                        <script>
                        Rumble("play", {"video":"vn7ykt","div":"rumble_vn7ykt"});</script>
                    * /
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
        $vsummary = $this->setup_array_validation( 'summary', $args );
        if( !empty( $vsummary ) ) {
            $vars[ 'summary' ] = $vsummary;

            $display = 1; // variable to check if something's for display
        } else {
            $vars[ 'summary' ] = '';
        }

        // VIDEO CREDITS
        $vcreds = $this->setup_array_validation( 'credits', $args );
        if( !empty( $vcreds ) ) {
            $vars[ 'credits' ] = $vcreds;

            $display = 1; // variable to check if something's for display
        } else {
            $vars[ 'credits' ] = '';
        }

        // VIDEO THUMBNAIL
        $vthumb = $this->setup_array_validation( 'thumb', $args );
        if( !empty( $vthumb ) ) {
            $vars[ 'thumbnail' ] = wp_get_attachment_image( $vthumb, $this->setup_array_validation( 'thumb_size', $args ) );

            $display = 1; // variable to check if something's for display
        } else {

            // GET YOUTUBE/VIMEO/RUMBLE THUMBNAIL
            if( !empty( $vurl ) ) {

                // YOUTUBE
                if( $vtyp == 'youtube' ) :

                    $vars[ 'thumbnail' ] = '<img src="https://img.youtube.com/vi/'.$this->setup_youtube_id_regex( $vurl ).'/hqdefault.jpg" border="0" />';

                    $display = 1; // variable to check if something's for display

                endif;

                // VIMEO
                if( $vtyp == 'vimeo' ) :

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

        // CSS / STYLES - MAIN VIDEO | CONTAINER - CSS SELECTORS
        $vds_wrap_sel = $this->setup_array_validation( 'sec_class', $args );
        if( !empty( $vds_wrap_sel ) ) {
            $vars[ 'video_wrap_sel' ] = $vds_wrap_sel;
        } else {
            $vars[ 'video_wrap_sel' ] = '';
        }

        // CSS / STYLES - MAIN VIDEO | CONTAINER - INLINE STYLES
        $vds_wrap_sty = $this->setup_array_validation( 'sec_inline', $args );
        if( !empty( $vds_wrap_sty ) ) {
            $vars[ 'video_wrap_sty' ] = $vds_wrap_sty;
        } else {
            $vars[ 'video_wrap_sty' ] = '';
        }
        
        // VIDEO TEMPLATE
        if( $this->setup_array_validation( 'layout_override', $args ) === TRUE ) {
            // OVERRIDE
            $layout = $this->setup_array_validation( 'layout', $args );
        } else {
            // GLOBAL
            $layout = $this->setup_array_validation( 'layout_global', $args );
        }
        
        // Display only if there's any content
        if( !empty( $display ) ) {
            
            // DISPLAY USING TEMPLATE
            $outz .= $this->setup_pull_view_template( $layout, $this->setup_array_validation( 'layout_location', $args ) );

            // include the raw video url for jQuery
            if( !empty( $vurl ) ) {
                //$outz .= '<input type="'.$this->input_type.'" id="vlink__'.$this->div_counter.'" value="'.$vurl.'" />';
                $outz .= '<input type="'.$vid_dimensions->input_type.'" id="vtype__'.$this->div_counter.'" value="'.$vtyp.'" />';
                $outz .= '<input type="'.$vid_dimensions->input_type.'" id="vidid__'.$this->div_counter.'" value="'.$v_id.'" />';
            }
        }
        
        // return type of video and output
        return array(
            'type'      => $vtyp,
            'output'    => $outz,
        );

    }
    */

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