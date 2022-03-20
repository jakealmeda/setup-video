<?php

global $timers;

$arr = new SetupVideoFunc();


echo '<div style="color:orange;">';

	$title = $arr->setup_array_validation( 'title', $timers );
	if( !empty( $title ) ) {
		echo '<div class="item-time-title"><b>TITLE:</b> '.$title.'</div>';
	}

	$summary = $arr->setup_array_validation( 'summary', $timers );
	if( !empty( $summary ) ) {
		echo '<div class="item-time-summary"><b>SUMMARY:</b> '.$summary.'</div>';
	}

	$start = $arr->setup_array_validation( 'start', $timers );
	if( !empty( $start ) ) {
		echo '<div class="item-time-start"><b>START:</b> '.$start.'</div>';
	}

	$end = $arr->setup_array_validation( 'end', $timers );
	if( !empty( $end ) ) {
		echo '<div class="item-time-end"><b>END:</b> '.$end.'</div>';
	}

echo '</div>';