define(['jquery', 'underscore'], function($, _) {

	openAudioUploader = function(callback) {

		// Check if WordPress Media Library is available
		if (typeof wp !== 'undefined' && wp.media) {
			
			// Initialize WordPress Media Library directly
			var mediaUploader = wp.media({
				title: 'Select or Upload Audio',
				button: {
					text: 'Select Audio'
				},
				multiple: false,
				library: {
					type: 'audio'
				}
			});
			
			// Handle media selection
			mediaUploader.on('select', function() {
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				callback(attachment.url, attachment.filename);
			});
			
			// Open the media library
			mediaUploader.open();
			
		} else {
			// Fallback to iframe method if wp.media is not available
			openAudioUploaderIframe(callback);
		}

	}

	// Fallback iframe method
	openAudioUploaderIframe = function(callback) {

		if ( !boxExists('input-audio') ) {

			if ( isNaN(Padma.viewModels.layoutSelector.currentLayout()) )
				iframePostID = 0;

			var settings = {
				id: 'input-audio',
				title: 'Select an audio',
				description: 'Upload or select an audio',
				src: Padma.homeURL + '/?padma-trigger=media-uploader',
				load: function() {
					console.log('Audio uploader iframe loaded');
				},
				width: $(window).width() - 200,
				height: $(window).height() - 200,
				center: true,
				draggable: false,
				deleteWhenClosed: true,
				blackOverlay: true
			};

			var box = createBox(settings);

			$('#box-input-audio').css({
				width: 'auto',
				height: 'auto',
				top: '70px',
				left: '70px',
				right: '70px',
				bottom: '70px',
				margin: 0
			});

		}

		openBox('input-audio');

	}

});
