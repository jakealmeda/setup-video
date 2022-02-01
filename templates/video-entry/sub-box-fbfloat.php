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
echo '<div class="item-videoentry box-fbfloat'.$cont_class.'"'.$inline_style.'>';
	
	$title = $arr->setup_array_validation( 'title', $vars );
	if( !empty( $title ) ) :
		echo '<div class="item-title textsize-xl">'.$title.'</div>';
	endif;

	$oembed = $arr->setup_array_validation( 'oembed', $vars );
	if( !empty( $oembed ) ) {
		
		$thumbnail = $arr->setup_array_validation( 'thumbnail', $vars );
		if( !empty( $thumbnail ) ) :
			// who clickable thumbnail
?>
			<div class="item-video">
				<button class="ytp-large-play-button ytp-button item-play" aria-label="Play">
					<svg class="item-play" height="100%" version="1.1" viewBox="0 0 68 48" width="100%"><path class="ytp-large-play-button-bg" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f00"></path><path class="ytp-large-play-button-symbol" d="M 45,24 27,14 27,34" fill="#fff"></path></svg>
					<div class="item-thumbnail" id="vthumbs__'.$vars[ 'counts' ].'">'.wp_get_attachment_image( $thumbnail, $arr->setup_array_validation( 'def_thumb_size', $vars ) ).'</div>
				</button>
			</div>
<?php
		else :
			// show video right away
			echo '<div class="item-oembed">'.$oembed.'</div>';
			//echo '<div class="item-oembed">'.$arr->setup_embed_sc( $oembed ).'</div>';
		endif;

	}
	/*
	$oembed = $arr->setup_array_validation( 'oembed', $vars );
	if( !empty( $oembed ) ) {
		echo '<div class="item-oembed">'.$oembed.'</div>';
	}
	
	$thumbnail = $arr->setup_array_validation( 'thumbnail', $vars );
	if( !empty( $thumbnail ) ) :
		echo '<div class="item-thumbnail">'.wp_get_attachment_image( $thumbnail, $arr->setup_array_validation( 'def_thumb_size', $vars ) ).'</div>';
	endif;*/

	$summary = $arr->setup_array_validation( 'summary', $vars );
	if( !empty( $summary ) ) :
		echo '<div class="item-summary textsize-sm">'.$summary.'</div>';
	endif;

	$credits = $arr->setup_array_validation( 'credits', $vars );
	$video_url = $arr->setup_array_validation( 'video_url', $vars );
	if( !empty( $credits ) && !empty( $video_url ) ) :

		echo '<div class="item-credit textsize-sm">Source: <a href="'.$video_url.'" target="_blank">'.$credits.'</a></div>';

	else:

		// show credits only - no video_url
		if( !empty( $credits ) && empty( $video_url ) ) :
			echo '<div class="item-credit textsize-sm">Source: '.$credits.'</div>';
		endif;

		// show video_url only - no credits
		if( empty( $credits ) && !empty( $video_url ) ) :
			echo '<div class="item-credit textsize-sm">Source: <a class="item-url" href="'.$video_url.'" target="_blank">Link</a></div>';
		endif;

	endif;

    echo '<input type="'.$arr->setup_array_validation( 'input_type', $vars ).'" id="vtype__'.$vars[ 'counts' ].'" value="youtube" />';
    echo '<input type="'.$arr->setup_array_validation( 'input_type', $vars ).'" id="vidid__'.$vars[ 'counts' ].'" value="'.$arr->setup_array_validation( 'video_id', $vars ).'" />';

// WRAP | CLOSE
echo '</div>';