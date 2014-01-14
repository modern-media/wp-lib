jQuery(document).ready(function($) {
	$('#submit-post-type-archives').click(function(event) {
		event.preventDefault();

		/* Get checked boxes */
		var postTypes = [];
		$('#post-type-archive-checklist li :checked').each(function() {
			postTypes.push($(this).val());
		});

		/* Send checked post types with our action, and nonce */
		$.post( ajaxurl, {
				action: "MMMUNavMenuCustomPostTypesAjax",
				posttypearchive_nonce: MMMUNavMenuCustomPostTypes.nonce,
				post_types: postTypes
			},

			/* AJAX returns html to add to the menu */
			function( response ) {
				$('#menu-to-edit').append(response);
			}
		);
	})
});
