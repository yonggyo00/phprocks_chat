$(document).ready(function() {
	// date picker
	$( ".datepicker" ).datepicker({
		dateFormat : "yy-mm-dd",
		numberOfMonths: 1,
		showButtonPanel: true
	});
	
	$("#chat-result-form #select-all").click(function() {
		$checkbox = $("#chat-result-form #chat-log-table input[type='checkbox']");
		
		if ( $checkbox.prop('checked') == false ) {
			$checkbox.prop('checked', true);
			$(this).text('전체해제');
		}
		else {
			$checkbox.prop('checked', false);
			$(this).text('전체선택');
		}
	});
});