// eslint-disable-next-line no-undef
jQuery(document).ready(function ($) {
	// Instantiates the variable that holds the media library frame.
	let meta_image_frame;
	// Runs when the image button is clicked.
	$('.banner-image-upload').click(function (e) {
		// Get preview pane
		// eslint-disable-next-line @wordpress/no-unused-vars-before-return
		const meta_image_preview = $(this).parent().children('.banner-image-preview');
		// Prevents the default action from occuring.
		e.preventDefault();
		// eslint-disable-next-line @wordpress/no-unused-vars-before-return
		const meta_image = $(this).parent().children('.banner-image');

		// If the frame already exists, re-open it.
		if (meta_image_frame) {
			meta_image_frame.open();
			return;
		}
		// Sets up the media library frame
		// eslint-disable-next-line no-multi-assign
		meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
			title: meta_image.title,
			button: {
				text: meta_image.button,
			},
		});
		// Runs when an image is selected.
		meta_image_frame.on('select', function () {
			// Grabs the attachment selection and creates a JSON representation of the model.
			const media_attachment = meta_image_frame.state().get('selection').first().toJSON();
			// Sends the attachment URL to our custom image input field.
			meta_image.val(media_attachment.url);
			meta_image_preview.children('img').attr('src', media_attachment.url);
		});
		// Opens the media library frame.
		meta_image_frame.open();
	});

	$('.banner-image-preview').click(function () {
		$('.banner-image-upload').trigger('click');
	});
});
