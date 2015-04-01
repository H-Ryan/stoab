$(document).ready( function() {

	//-- [BEGIN] Row Mouse Over
	$( "table.data_table tr" ).mouseover( function() {
		$( this ).addClass( "over" );
	}).mouseout( function() {
		$( this ).removeClass( "over" );
	});
	//-- [END] Row Mouse Over

	//-- [BEGIN] Row Alternate Coloring
	$( "table.data_table tr:even" ).addClass( "alt" );
	//-- [END] Row Alternate Coloring

	//-- [BEGIN] Page Tab Mouse Over
	$( "table.page_tabs td.page_cell" ).mouseover( function() {
		$( this ).addClass( "page_cell_over" );
	}).mouseout( function() {
		$( this ).removeClass( "page_cell_over" );
	});
	//-- [END] Page Tab Mouse Over

	//-- [BEGIN] Select All / Deselect All
	$( '#selrec_header' ).click( function() {
		$( ".selrec_checkbox" ).attr( 'checked', $(this).is( ':checked' ) );
	});
	//-- [END] Select All / Deselect All

	//-- [BEGIN] Confirm Deletion
	$( '#btn_del_multi' ).click( function( event ) {
		var cnt = 0;
		$( ".selrec_checkbox" ).each( function() {
			if ( $(this).is( ':checked' ) )
			{
				cnt++;
			}
		} );

		if ( cnt == 0 )
		{
			alert( RSTR_SELECT_AT_LEAST_ONE );
		}
		else
		{
			var msg = RSTR_DELETE_CONFIRM;
			msg = msg.replace( '##cnt##', cnt );
			msg = msg.replace( '##s##', cnt > 1 ? 's' : '' );

			if ( confirm ( msg ) )
			{
				// Proceed!
				return;
			}
		}
		event.preventDefault();
	});
	//-- [END] Confirm Deletion

});
