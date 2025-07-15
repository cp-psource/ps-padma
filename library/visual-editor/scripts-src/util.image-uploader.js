define(['jquery', 'underscore'], function($, _) {

	openImageUploader = function(callback) {

		// Store callback globally for the iframe to access
		window.imageUploaderCallback = callback;

		if ( !boxExists('input-image') ) {

			if ( isNaN(Padma.viewModels.layoutSelector.currentLayout()) )
				iframePostID = 0;

			var settings = {
				id: 'input-image',
				title: 'Select an image',
				description: 'Upload or select an image',
				src: Padma.homeURL + '/?padma-trigger=media-uploader',
				load: function() {
					// No need to initiate complex uploader, just wait for iframe to load
					console.log('Media uploader iframe loaded');
				},
				width: $(window).width() - 200,
				height: $(window).height() - 200,
				center: true,
				draggable: false,
				deleteWhenClosed: true,
				blackOverlay: true
			};

			var box = createBox(settings);

			$('#box-input-image').css({
				width: 'auto',
				height: 'auto',
				top: '70px',
				left: '70px',
				right: '70px',
				bottom: '70px',
				margin: 0
			});
		}

		openBox('input-image');

	}

	// Callback function is now handled by the iframe directly
	// No need for complex WordPress Media API integration

});