(function($) {

	var Source, SourceCounter, SectionNumber, VideoSource, VideoID,
		ActualVideoURL;

	$( "[id^=vthumbs__]" ).each( function () {

		$( '#'+this.id ).on( 'click', function(){

			Source = this.id;
			SourceCounter = Source.split( '__' );
			SectionNumber = SourceCounter[ 1 ];
			//VideoURL = $( '#vlink__' + SectionNumber ).val();
			VideoSource = $( '#vtype__' + SectionNumber ).val();
			VideoID = $( '#vidid__' + SectionNumber ).val();

			//getInfo( WPConfig, VideoURL );

		    // hide play button and thumbnail div
		    //$( this ).hide();

		    // append YOUTUBE video
		    if( VideoSource == 'yt' ) {

		    	// cancatenate here - doing so in the append command below creates an error on the console
		    	var ActualVideoURL = 'https://www.youtube.com/embed/' + VideoID + '?autoplay=1';
		    	
				$( '#vthumbs__' + SectionNumber )
			        .html( '<div class="video-iframe" style="position:relative;padding-bottom: 56.25%;height:0;background-color:#333;">' +
			                    '<iframe width="420" height="315" id="video_iframe" src="' + ActualVideoURL + '" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allowfullscreen></iframe>' +
			                 '</div>' );

			    //alert( VideoSource + ' | ' + VideoID );
		    }

		    // append VIMEO video
		    if( VideoSource == 'vi' ) {

		    	// cancatenate here - doing so in the append command below creates an error on the console
		    	var ActualVideoURL = 'https://player.vimeo.com/video/' + VideoID + '?autoplay=1&portrait=0';

		    	$( '#vthumbs__' + SectionNumber )
			        .html( '<div class="video-iframe" style="position:relative;padding-bottom: 56.25%;height:0;background-color:#333;">' +
			        			'<iframe src="' + ActualVideoURL + '" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>' +
			                 '</div><script src="https://player.vimeo.com/api/player.js"><\/script>' );
			        
			    //alert( VideoSource + ' | ' + VideoID );
		    }
		    

		});

	});

	/*function getInfo( WPConfigDir, VideoURL ) {
		//alert( WPConfigDir + ' ===== ' + VideoURL );
		
		$.ajax({
			type: "POST",
			url:  ThisPlugInDir + "ajax.php",
			data: 'wpconfig='+WPConfig+'&video_url'+VideoURL,
			success: function( data ){
				//$("#info").html(data);
				alert( data );
			}
		});
		
	};*/

})( jQuery );