jQuery(document).ready(function($){
	$('.char-count').each(function(){
		var t = $($(this).data('target'));
		if(0 === t.length) return;
		var s = $(this);
		var update = function(){
			s.text(t.val().length);
		};
		t.keyup(update);
		t.change(update);
		update();
	});
});
