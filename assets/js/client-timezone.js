jQuery(document).ready(function($){
	var d = new Date();
	/**
	 * Date.getTimezoneOffset() = int in minutes
	 * In javascript, locations west of UTC have positive
	 * offsets, but we reverse this to conform with PHP's
	 * convention that locations west of UTC have negative
	 * offsets.
	 * @type {number}
	 */
	var o = d.getTimezoneOffset() * -1;
	d.setTime(d.getTime() + ( 60 * 1000) );
	var expires = "expires=" + d.toGMTString();
	document.cookie = "mm_wp_lib_client_timezone=" + o + "; " + expires + ";path=/";
});