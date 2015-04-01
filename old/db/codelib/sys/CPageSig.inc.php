<?php
//==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>
//
// Address Book Script v1.18 [G110]
// Copyright (c) phpkobo.com ( http://www.phpkobo.com/ )
// Email : admin@phpkobo.com
// ID : AB201-118 [G110]
// URL : http://www.phpkobo.com/address_book.php
//
// This software is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; version 2 of the
// License.
//
//==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<



define( 'STR_SYSPAGESIG_KEY', '_sys_pagesig_' );
define( 'STR_PSIG_DELIMITER', '/' );

//----------------------------------------------------------------
// CPageSig
//----------------------------------------------------------------
class CPageSig extends CObject
{
	function Mark( $psig_key )
	{
		$page_sig_key = $psig_key;
		$page_sig_val = 'x_x_x_page_sig_val_x_x_x_' . CUtil::CreateRandomString( 32 );
		$_SESSION[$page_sig_val] = $page_sig_val;
		$this->sys->State->Set( STR_SYSPAGESIG_KEY, 
			$page_sig_key . STR_PSIG_DELIMITER . $page_sig_val );
	}

	function Clear( $psig_key )
	{
		return $this->Check( $psig_key, true );
	}

	function Check( $psig_key, $b_clear = false )
	{
		if ( $psig_key == '' ) return ture;

		$ax = split( STR_PSIG_DELIMITER, $this->sys->State->Get( STR_SYSPAGESIG_KEY ));
		if ( count($ax) != 2 ) return false;

		$page_sig_key = $ax[0];
		$page_sig_val = $ax[1];

		if ( $page_sig_key != $psig_key ) return false;
		if ( !isset( $_SESSION[$page_sig_val] ) ) return false;
		if ( $_SESSION[$page_sig_val] != $page_sig_val ) return false;

		if ( $b_clear ) unset( $_SESSION[$page_sig_val] );

		return true;
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>