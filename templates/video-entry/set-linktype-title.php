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
echo '<div class="item-videoentry set-linktype'.$cont_class.'"'.$inline_style.'>';

	/*
	Default link (w title)
	Video: [manual url]Title[/manual]
	Video: [oembed url]Title[/oembed]
	*/

	$video_url = $arr->setup_array_validation( 'video_url', $vars );
	$oembed = $arr->setup_array_validation( 'oembed', $vars );
	if( empty( $video_url ) ) {
		// get raw URL from oEmbed
		$vurl = get_field( 'video-oembeds'.$arr->setup_array_validation( 'acf_group', $vars ), FALSE, FALSE );
	} else {
		// prioritize manual (video) URL
		if( !empty( $video_url ) ) {
			$vurl = $video_url;
		}
	}

	$title = $arr->setup_array_validation( 'title', $vars );
	if( !empty( $title ) ) {
		echo '<div class="item-title textsize-xl"><a href="'.$vurl.'" target="_blank">'.$title.'</a></div>';
	} else {
		echo '<div class="item-title textsize-xl"><a href="'.$vurl.'" target="_blank">'.$vurl.'</a></div>';
	}


	echo '<input type="'.$arr->setup_array_validation( 'input_type', $vars ).'" id="vtype__'.$vars[ 'counts' ].'" value="youtube" />';
	echo '<input type="'.$arr->setup_array_validation( 'input_type', $vars ).'" id="vidid__'.$vars[ 'counts' ].'" value="'.$arr->setup_array_validation( 'video_id', $vars ).'" />';

// WRAP | CLOSE
echo '</div>';