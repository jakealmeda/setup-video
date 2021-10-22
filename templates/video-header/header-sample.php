<?php

global $vars;
$arr = new URCVideoFunc();

// CONTAINER (WRAP) | CSS
$cont_class = $arr->urc_array_validation( 'video_wrap_sel', $vars, array( 'attr' => 'selectors' ) );
// CONTAINER (WRAP) | INLINE STYLE
$cont_style = $arr->urc_array_validation( 'video_wrap_sty', $vars, array( 'attr' => 'inline' ) );

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

	echo '<h1 style="color:gold;">HEADER SAMPLE</h1>';

	$title = $arr->urc_array_validation( 'title', $vars );
	if( !empty( $title ) ) :
		echo '<div class="item-title">'.$title.'</div>';
	endif;

	$content = $arr->urc_array_validation( 'content', $vars );
	if( !empty( $content ) ) :
		echo '<div class="item-content">'.$content.'</div>';
	endif;

// WRAP | CLOSE
echo '</div>';