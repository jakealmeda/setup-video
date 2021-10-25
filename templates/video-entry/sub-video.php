<?php

global $vars;
$arr = new SetupVideoFunc();

// CONTAINER (WRAP) | CSS
$cont_class = $arr->setup_array_validation( 'video_wrap_sel', $vars, array( 'attr' => 'selectors' ) );
// CONTAINER (WRAP) | INLINE STYLE
$cont_style = $arr->setup_array_validation( 'video_wrap_sty', $vars, array( 'attr' => 'inline' ) );

/**
 * CONTENT | START
 */

$styles = ''; // add your styles here without the HTML tag STYLE

if( !empty( $cont_style ) || !empty( $styles ) ) {
	$inline_style = ' style="'.$cont_style.$styles.'"';	
} else {
	$inline_style = '';
}

// WRAP | OPEN
echo '<div class="item-video'.$cont_class.'"'.$inline_style.'>';

	echo '<h1 style="color:brown;">SUB-VIDEO TEMPLATE '.$vars[ 'counts' ].'</h1>';

	$thumbnail = $arr->setup_array_validation( 'thumbnail', $vars );
	if( !empty( $thumbnail ) ) :
		echo '<div class="item-thumbnail">'.$thumbnail.'</div>';
	endif;

	$title = $arr->setup_array_validation( 'title', $vars );
	if( !empty( $title ) ) :
		echo '<div class="item-title">'.$title.'</div>';
	endif;

	$video_url = $arr->setup_array_validation( 'video_url', $vars );
	if( !empty( $video_url ) ) :
		echo '<div class="item-video-url">'.$video_url.'</div>';
	endif;

	$summary = $arr->setup_array_validation( 'summary', $vars );
	if( !empty( $summary ) ) :
		echo '<div class="item-summary">'.$summary.'</div>';
	endif;

	$credits = $arr->setup_array_validation( 'credits', $vars );
	if( !empty( $credits ) ) :
		echo '<div class="item-credits">'.$credits.'</div>';
	endif;

// WRAP | CLOSE
echo '</div>';