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
	Default link (w manually entered url)
	Video: [manual url]https://youtu.be/xyz[/manual]
	*/
	$video_url = $arr->setup_array_validation( 'video_url', $vars );

	// pre, are you simply displaying the URL with anchor tags?
	// yes? this is what you'll use
	echo '<div class="item-url"><a href="'.$video_url.'" target="_blank">'.$video_url.'</a></div>';

	// no? maybe you want to use it for oEmbed? here's your code
	if( !empty( $video_url ) ) {
		
		$thumbnail = $arr->setup_array_validation( 'thumbnail', $vars );
		if( !empty( $thumbnail ) ) :
			
			// clickable thumbnail

			$use_this_thumb = wp_get_attachment_image( $thumbnail, $arr->setup_array_validation( 'def_thumb_size', $vars ), false, array( 'class' => 'item-thumbnail-img' ) );
			
			?>
			<div class="item-video" id="vthumbs__<?php echo $vars[ "counts" ]; ?>">
				<button class="item-video-button" aria-label="Play">
					<svg class="item-play-icon" height="100%" version="1.1" viewBox="0 0 68 48" width="100%"><path class="play-icon-tv" d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#f00"></path><path class="play-icon-triangle" d="M 45,24 27,14 27,34" fill="#fff"></path></svg>
					<div class="item-thumbnail"><?php echo $use_this_thumb; ?></div>
				</button>
			</div>
			<?php

		else :

			// show video right away
			//echo '<div class="item-oembed">'.$video_url.'</div>';
			echo '<div class="item-oembed">'.$arr->setup_embed_sc( $video_url ).'</div>';

		endif;

	}

	echo '<input type="'.$arr->setup_array_validation( 'input_type', $vars ).'" id="vtype__'.$vars[ 'counts' ].'" value="youtube" />';
	echo '<input type="'.$arr->setup_array_validation( 'input_type', $vars ).'" id="vidid__'.$vars[ 'counts' ].'" value="'.$arr->setup_array_validation( 'video_id', $vars ).'" />';

// WRAP | CLOSE
echo '</div>';