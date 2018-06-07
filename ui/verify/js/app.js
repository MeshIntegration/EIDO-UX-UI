$(document).foundation();


/**
 * Shared actions and methods by the superuser panel
 * @private
 */
function _init_superuser() {
	'use strict';

	//when bulk actions are click
	$(".bulk-action").on('submit', function(e) {
		e.preventDefault();
		var $form = $(this);
		//get the checkboxes

		location.href = $form.attr("action")+'&'+$('[name=id\\[\\]]:checked').serialize();
	});

}

$(document).ready(function() {
	'use strict';
	_init_superuser();

});



