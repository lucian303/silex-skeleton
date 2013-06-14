// app.js
(function($) {
	$(document).ready(function() {
		$('#button-ajax-test').click(function(event) {
			console.log(event);
			$.ajax('/api/search/lucian', {
				success: function(data) {
					console.log(data);
				}
			});
		});
	});
})(jQuery);
