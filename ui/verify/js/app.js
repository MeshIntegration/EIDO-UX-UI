$(document).foundation();


/**
 * Shared actions and methods by the superuser panel
 * @private
 */
function _init_superuser() {
	'use strict';

	//add class based on location
	if(location.pathname.indexOf('superuser') !== -1) {
		$("body").addClass("superuser");
	}

	$("#BulkActions").click( function() {
		var $this = $(this);

		if(!$this.hasClass("is-active")) {
			$this.addClass('is-active');
			$('.patient-list .column-first, .grid-header .column-first').css("display","block");
			$('.grid-header strong').removeClass("no-check");
		} else {
			$('.patient-list .column-first, .grid-header .column-first').css("display","none");
			$('.grid-header strong').addClass("no-check");
			$this.removeClass('is-active');
		}
	}).click(function() {
		$(this).trigger('eido-click', [ true ]);
	}).trigger('eido-click');

	//to handle the on load
	$('.patient-list .column-first, .grid-header .column-first').css("display","none");
	$('.grid-header strong').addClass("no-check");
	$("#BulkActions").removeClass('is-active');

	//when bulk action confirm buttons are clicked
	$(".bulk-action").on('submit', function(e) {
		e.preventDefault();
		var $form = $(this);
		//get the checkboxes
		$.post($form.attr("action"), { users: $('[name=performAction\\[\\]]:checked').serializeArray() }).done(function() {
		//	location.reload();
           window.location.search = '?m=';
		});
	});

}

$(document).ready(function() {
	'use strict';
	_init_superuser();

});



