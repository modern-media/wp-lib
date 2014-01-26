jQuery(document).ready(function($){
	var d = new Date();
	/**
	 * Date.getTimezoneOffset() = int in minutes
	 * @type {number}
	 */
	var o = d.getTimezoneOffset();
	d.setTime(d.getTime() + ( 60 * 1000) );
	var expires = "expires=" + d.toGMTString();
	document.cookie = "mm_wp_lib_client_timezone=" + o + "; " + expires + ";path=/";
});