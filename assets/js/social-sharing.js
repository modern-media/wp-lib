//Google async script...
(function() {
	var po = document.createElement('script');
	po.type = 'text/javascript';
	po.async = true;
	po.src = 'https://apis.google.com/js/platform.js';
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(po, s);
})();

//twitter async script...
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");

//stumbleupon...
(function() {
	var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
	li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
})();

//facebook...

(function(d, s, id) {
	if (! window.mm_wp_lib_social_sharing_facebook_app_id) return;
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=" + window.mm_wp_lib_social_sharing_facebook_app_id;
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

//stumbleupon...
(function() {
	var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
	li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
})();

//pinterest...
(function(d){
var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
p.type = 'text/javascript';
p.async = true;
p.src = '//assets.pinterest.com/js/pinit.js';
f.parentNode.insertBefore(p, f);
}(document));