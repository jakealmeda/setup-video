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
	Video: [oembed url]Title[/oembed]
	Video: [manual url]Title[/manual]
	*/

	$acf_group = $arr->setup_array_validation( 'acf_group', $vars );

	// FOR OEMBED
	$title = $arr->setup_array_validation( 'title', $vars );
	if( !empty( $title ) ) :

		// Video: [oembed url]Title[/oembed]
		$oembed = $arr->setup_array_validation( 'oembed', $vars );
		if( !empty( $oembed ) ) {

			// get raw URL from oEmbed
			$oembed_url = get_field( 'video-oembeds'.$acf_group, false, false);
			echo '<div class="item-title textsize-xl"><a href="'.$oembed_url.'" target="_blank">'.$title.'</a></div>';
		}

		// Video: [manual url]Title[/manual]
		$video_url = $arr->setup_array_validation( 'video_url', $vars );
		if( !empty( $video_url ) ) {
			echo '<div class="item-title textsize-xl"><a href="'.$video_url.'" target="_blank">'.$title.'</a></div>';
		}
		
	endif;	


	echo '<input type="'.$arr->setup_array_validation( 'input_type', $vars ).'" id="vtype__'.$vars[ 'counts' ].'" value="youtube" />';
	echo '<input type="'.$arr->setup_array_validation( 'input_type', $vars ).'" id="vidid__'.$vars[ 'counts' ].'" value="'.$arr->setup_array_validation( 'video_id', $vars ).'" />';

// WRAP | CLOSE
echo '</div>';