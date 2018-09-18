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
    $("#Sort").click(function () {
        var bulkstatus = $('#BulkActiondiv').css('display');
        var sortstatus = $('#Sortdiv').css('display');

    if ( sortstatus != "none") {
            $("#Sortdiv").css("display", "block");
            $("#BulkActiondiv").css("display", "block");
        $("#BulkAction").css("color", "white");
        $("#BulkAction").css("background-color", "#6B6B6B");
        }
        else if ( (sortstatus == "none") && (bulkstatus !="none") ) {
            $("#BulkActiondiv").css("display", "none");
        $("#BulkAction").css("color", "#6B6B6B");
        $("#BulkAction").css("background-color", "#d3d1d1");
		}
		else if ( bulkstatus == "none") {
            $("#BulkActiondiv").css("display", "block");
        $("#BulkAction").css("color", "white");
        $("#BulkAction").css("background-color", "#6B6B6B !important");
		}
});
})
$(document).ready(function() {
    $("#BulkAction").click(function () {
        var bulkstatus = $('#BulkActiondiv').css('display');
        var sortstatus = $('#Sortdiv').css('display');
        $("#BulkAction").addClass("is-active");

        if ( bulkstatus != "none") {
            $("#BulkActiondiv").css("display", "none");
            $("#Sortdiv").css("display", "block");
            $("#Sort").css("color", "white");
            $("#Sort").css("background-color", "#6B6B6B");
        }
        else if ( (bulkstatus == "none") && (sortstatus !="none") ) {
            $("#Sortdiv").css("display", "none");
            $("#Sort").css("color", "#6B6B6B");
            $("#Sort").css("background-color", "#d3d1d1");
        }
        else if ( sortstatus == "none") {
            $("#Sortdiv").css("display", "block");
            $("#Sort").css("color", "white");
            $("#Sort").css("background-color", "#6B6B6B !important");
        }
    });
})


$(document).ready(function() {
	'use strict';
	_init_superuser();

});



