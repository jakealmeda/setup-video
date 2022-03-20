
<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $gcounter;

class SetupVideoFuncExtension {

    public function setup_insert_timers( $vid_url, $time, $embed = FALSE ) {

        $pars = parse_url( $vid_url );
        
        $arr = new SetupVideoFunc();

        if( array_key_exists( 'query', $pars ) && !empty( $pars[ 'query' ] ) ) {

            $whole_url = $pars[ 'scheme' ].'://'.$pars[ 'host' ].$pars[ 'path' ].'?'.$pars[ 'query' ].'&t='.$this->setup_video_timer( $time );

        } else {

            // add params
            $whole_url = $pars[ 'scheme' ].'://'.$pars[ 'host' ].$pars[ 'path' ].'?t='.$this->setup_video_timer( $time );

        }

        if( !empty( $embed ) ) {
            return $arr->setup_embed_sc( $whole_url );
        } else {
            return '<a href="'.$whole_url.'" target="_blank">'.$time.'</a>';
        }


    }


    /**
     * Get the hours, minutes and seconds
     */
    private function setup_video_timer( $vid_timer ) {

        $vs = explode( ':', $vid_timer );
        if( count( $vs ) > 2 ) {
            // hours : minutes : seconds
            $vs_hours = ( $vs[ 0 ] * 60 ) * 60; // ( hours * 60 minutes ) * 60 seconds
            $vs_minutes = $vs[ 1 ] * 60;
            $vs_seconds = $vs[ 2 ];
        } else {
            // minutes : seconds
            $vs_hours = 0;
            $vs_minutes = $vs[ 0 ] * 60;
            $vs_seconds = $vs[ 1 ];
        }

        return $vs_hours + $vs_minutes + $vs_seconds;

    }

}