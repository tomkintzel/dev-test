jQuery(document).ready(function() {
	var progress = jQuery('.QMProgress-Progress');
	var progress_info = jQuery('.QMProgress-Info');
	var get_progress = function() {
		var data = {
			action: 'get-async-task-manager-progress'
		};
		jQuery.post(ajaxurl, data, function(data) {
			if(data.open + data.running + data.done > 0) {
				var progress_now = data.done;
				var progress_max = data.open + data.running + data.done;
				var progress_percentage = Math.ceil( 100.0 * progress_now / progress_max );
				progress.width( progress_percentage + '%' );
				progress_info.html( progress_percentage + ' % (' + progress_now + ' / ' + progress_max + ')' );
				if(progress_max > progress_now) {
					window.setTimeout(function() {
						get_progress();
					}, 1000);
				} else {
					window.setTimeout(function() {
						location.reload(true);
					}, 1000);
				}
			} else {
				progress.width( '0%' );
				progress_info.html( 'abgebrochen' );
				window.setTimeout(function() {
					location.reload(true);
				}, 1000);
			}
		});
	};
	if(progress.length > 0) {
		get_progress();
	}
});
