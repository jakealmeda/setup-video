<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class SetupVideoFunc {

    // intialize counter for sections (div)
    public $div_counter = 0;

    // initialize counter for max video count to be shown
    public $video_count = 0;

    /**
     * Main function
     */
    public function setup_video_acf( $acf_group = FALSE ) {

        $vid_struc = new SetupVideoStructure();

        // declare empty variables
        if( is_array( $vid_struc->type_of_vids ) ) {
            $outz = array();
        } else {
            $outz = '';    
        }
        $video_count = 0;
        $out_this = '';
        $section_style = '';

        // 1st group
        $vid_det = get_field( 'video-main'.$acf_group );
        // 2nd group
        $vid_multi = get_field( 'video-multi'.$acf_group );

        // VALIDATE IF VIDEOS ARE TO BE DISPLAYED
        if( $vid_det[ 'video-status' ] == 'enabled' && $vid_struc->show_num_vids >= 1 ) {

            // GET GLOBAL LAYOUT (TEMPLATE)
            $layout_global = $this->setup_array_validation( 'video-template-global', $vid_det );

            /**
             * MAIN VIDEO
             */
            if( $vid_det[ 'video-showhide' ] === TRUE ) {

                $args = array(

                    'title'                 => $this->setup_array_validation( 'video-title', $vid_det ),
                    'url'                   => $this->setup_array_validation( 'video-url', $vid_det ),
                    'thumb'                 => $this->setup_array_validation( 'video-thumb-image', $vid_det ),
                    'thumb_size'            => $this->setup_array_validation( 'video-thumb-size', $vid_det ),
                    'summary'               => $this->setup_array_validation( 'video-summary', $vid_det ),
                    'credits'               => $this->setup_array_validation( 'video-credits', $vid_det ),

                    // section style
                    'sec_class'             => $this->setup_array_validation( 'video-section-class', $this->setup_array_validation( 'video-section-wrap', $vid_det ) ),
                    'sec_inline'            => $this->setup_array_validation( 'video-section-style', $this->setup_array_validation( 'video-section-wrap', $vid_det ) ),

                    // layout (template)
                    'layout_override'       => $this->setup_array_validation( 'video-template-override', $vid_det ),
                    'layout'                => $this->setup_array_validation( 'video-template', $vid_det ),
                    'layout_global'         => $layout_global,
                    'layout_location'       => 'video-entry', // directory inside the templates folder

                );

                $thiz = $this->setup_process_video_entry( $args );
                if( is_array( $vid_struc->type_of_vids ) ) {
                    
                    // check if video type is in the array of what to be shown
                    if( in_array( $thiz[ 'type' ], $vid_struc->type_of_vids ) ) {
                        $outz[] = array( $thiz[ 'type' ] => $thiz[ 'output' ] );
                    }

                } else {
                    
                    // DEFAULT
                    $outz .= $this->setup_array_validation( 'output', $thiz );

                }

                if( !empty( $outz ) ) {
                    $video_count++;
                }

            } // MAIN VIDEO - END


            // PIT STOP - check the number of videos to be shown
            if( $vid_struc->show_num_vids == $video_count ) {
                
                // $outz has the main video | stop the video multi loop
                $end_loop = 1;

            } else {
                
                // continue with the video multi loop
                $end_loop = 2;

            }


            /**
             * MULTI VIDEO
             */
            if( is_array( $vid_multi ) && $end_loop == 2 ) {

                for( $x=0; $x<=( count( $vid_multi ) - 1); $x++ ) {

                    // assign value to another variable for ease of use
                    $vid_multies = $vid_multi[ $x ];

                    // get the type of Flexible Content
                    $vm_layout = $vid_multies[ 'acf_fc_layout' ];

                    // VIDEO HEADER
                    if( $vm_layout == 'video-multi-heading' ) :

                        if( $vid_multies[ 'vmh-showhide' ] === TRUE ) {

                            $args = array(

                                'title'                 => $this->setup_array_validation( 'vmh-title', $vid_multies ),
                                'content'               => $this->setup_array_validation( 'vmh-content', $vid_multies ),

                                // section style
                                'sec_class'             => $this->setup_array_validation( 'vmh-section-class', $this->setup_array_validation( 'vmh-section-wrap', $vid_multies ) ),
                                'sec_inline'            => $this->setup_array_validation( 'vmh-section-style', $this->setup_array_validation( 'vmh-section-wrap', $vid_multies ) ),

                                // layout (template)
                                'layout_override'       => TRUE,
                                'layout'                => $this->setup_array_validation( 'vmh-template', $vid_multies ),
                                'layout_location'       => 'video-header', // directory inside the templates folder

                            );

                            $thiz = $this->setup_process_video_entry( $args );
                            if( is_array( $vid_struc->type_of_vids ) ) {
                                
                                if( in_array( 'header', $vid_struc->type_of_vids ) ) {
                                    $outz[] = array( 'header' => $this->setup_array_validation( 'output', $this->setup_process_video_entry( $args ) ) );
                                }

                            } else {
                                
                                // DEFAULT
                                $outz .= $this->setup_array_validation( 'output', $this->setup_process_video_entry( $args ) );

                            }

                            if( !empty( $outz ) ) {
                                $video_count++;
                            }

                        }

                    endif;

                    // VIDEO ENTRY
                    if( $vm_layout == 'video-multi-entry' ) :

                        if( $vid_multies[ 'vme-showhide' ] === TRUE ) {

                            $args = array(

                                'title'                 => $this->setup_array_validation( 'vme-title', $vid_multies ),
                                'url'                   => $this->setup_array_validation( 'vme-url', $vid_multies ),
                                'thumb'                 => $this->setup_array_validation( 'vme-thumb-image', $vid_multies ),
                                'thumb_size'            => $this->setup_array_validation( 'vme-thumb-size', $vid_multies ),
                                'summary'               => $this->setup_array_validation( 'vme-summary', $vid_multies ),
                                'credits'               => $this->setup_array_validation( 'vme-credits', $vid_multies ),

                                // section style
                                'sec_class'             => $this->setup_array_validation( 'vme-section-class', $this->setup_array_validation( 'vme-section-wrap', $vid_multies ) ),
                                'sec_inline'            => $this->setup_array_validation( 'vme-section-style', $this->setup_array_validation( 'vme-section-wrap', $vid_multies ) ),

                                // layout (template)
                                'layout_override'       => $this->setup_array_validation( 'vme-template-override', $vid_multies ),
                                'layout'                => $this->setup_array_validation( 'vme-template', $vid_multies ),
                                'layout_global'         => $layout_global,
                                'layout_location'       => 'video-entry', // directory inside the templates folder

                            );

                            $thiz = $this->setup_process_video_entry( $args );
                            if( is_array( $vid_struc->type_of_vids ) ) {
                                
                                if( in_array( $thiz[ 'type' ], $vid_struc->type_of_vids ) ) {
                                    $outz[] = array( $thiz[ 'type' ] => $thiz[ 'output' ] );
                                }

                            } else {
                                
                                // DEFAULT
                                $outz .= $this->setup_array_validation( 'output', $thiz );

                            }

                            if( !empty( $outz ) ) {
                                $video_count++;
                            }

                        }

                    endif;

                    // PIT STOP - check the number of videos to be shown
                    if( $vid_struc->show_num_vids == $video_count ) {
                        break; // exit loop - we got what we need
                    }

                } // for( $x=0; $x<=( count( $vid_multi ) - 1); $x++ ) {

            } // MULTI VIDEO - END
            

            // SORT ARRAY BASED ON $vid_struc->type_of_vids
            if( is_array( $vid_struc->type_of_vids ) && !empty( $outz ) ) :

                foreach( $vid_struc->type_of_vids as $vtvids ) {
                    
                    for( $h=0; $h<=( count( $outz ) - 1); $h++ ) {

                        foreach( $outz[ $h ] as $ke => $va ) {
                            
                            if( $vtvids == $ke ) {
                                $out_this .= $va;
                            }

                        }

                    }

                }

            else :

                $out_this = $outz;

            endif;

            
            /**
             * DISPLAY
             */
            if( $this->setup_array_validation( 'video-container-enable', $vid_det ) === TRUE ) :

                // CONTAINER (WRAP) | CSS
                //$sec_css = $this->setup_array_validation( 'css', $section_style, array( 'attr' => 'selectors' ) );
                $sec_css = ' '.$this->setup_array_validation( 'video-container-class', $vid_det );

                // CONTAINER (WRAP) | INLINE STYLE
                //$sec_style = $this->setup_array_validation( 'inline', $section_style, array( 'attr' => 'inline' ) );
                $sec_style = $this->setup_array_validation( 'video-container-style', $vid_det );

                if( !empty( $sec_style ) ) {
                    $sec_style = ' style="'.$sec_style.'"';
                } else {
                    $sec_style = '';
                }

                echo '<section class="section-video'.$sec_css.'"'.$sec_style.'>'.$out_this.'</section>';

            else:

                // NO CONTAINER
                //var_dump( $out_this );
                if( !empty( $outz ) )
                    echo $out_this;

            endif;

        } // if( $vid_det[ 'video-showhide' ] === TRUE ) {

    }


    public function setup_process_video_entry( $args ) {

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
                    /*
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
                    */
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
    public function __construct() {

        if ( !is_admin() ) {

            $mha = new SetupVideoStructure();

            add_action( $mha->usehook, array( $this, 'setup_video_acf' ) );

        }

    }

}