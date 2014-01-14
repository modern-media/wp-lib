jQuery(document).ready(function($) {
	$('#submit-network-sites').click(function(event) {
		event.preventDefault();

		/* Get checked boxes */
		var network_sites = [];
		$('#network-sites-checklist li :checked').each(function() {
			network_sites.push($(this).val());
		});

		/* Send checked post types with our action, and nonce */
		$.post( ajaxurl, {
				action: "NavMenuNetworkSitesAjax",
				posttypearchive_nonce: NavMenuNetworkSites.nonce,
				network_sites: network_sites
			},

			/* AJAX returns html to add to the menu */
			function( response ) {
				$('#menu-to-edit').append(response);
			}
		);
	})
});