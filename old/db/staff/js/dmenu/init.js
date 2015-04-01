$(document).ready( function() {
	$( ".dmenu_item" ).mouseover( function() {
		$( this ).parent().addClass( "dmenu_td_over" );
	});

	$( ".dmenu_item" ).mouseout( function() {
		$( this ).parent().removeClass( "dmenu_td_over" );
	});
});
