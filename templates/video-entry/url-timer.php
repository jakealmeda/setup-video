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
echo '<div class="item-videoentry'.$cont_class.'"'.$inline_style.'>';

	// oEmbed field with start time
	$o_timed = $arr->setup_array_validation( 'oembed_timed', $vars );
	if( !empty( $o_timed ) ) {
		echo '<div class="item-oembed-timed textsize-xl">'.$o_timed.'</div>';
	}
	
	// URL field with start time
	$url_timed = $arr->setup_array_validation( 'video_url_timed', $vars );
	if( !empty( $url_timed ) ) {
		echo '<div class="item-url-timed textsize-xl">'.$url_timed.'</div>';
	}

	// https://youtu.be/Cy2U6GtojDk?t=88
	// start & end time tutorial - youtube

	echo '<input type="'.$arr->setup_array_validation( 'input_type', $vars ).'" id="vtype__'.$vars[ 'counts' ].'" value="youtube" />';
	echo '<input type="'.$arr->setup_array_validation( 'input_type', $vars ).'" id="vidid__'.$vars[ 'counts' ].'" value="'.$arr->setup_array_validation( 'video_id', $vars ).'" />';

// WRAP | CLOSE
echo '</div>';