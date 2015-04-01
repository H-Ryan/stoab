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


//----------------------------------------------------------------
// CError
//----------------------------------------------------------------
class CError extends CObject
{
	function GetErrStyle( &$ht )
	{
		if ( strcasecmp( get_class( $ht ), 'COutHtml' ) == 0 )
			$this->UseErrStyle( $ht );
		else
			{}
	}

	function UseErrStyle( &$ht )
	{
		$ht->SetStyle( 'background-color', 'yellow' );
	}

	function FormatErrMsg( $err_msg, $caption = null, $val = null, $b_hide_value = false )
	{
		//--- Print value ( Special Handling for Password )
		if ( $b_hide_value || ( is_null( $val ) ) || ( $val == '' ) )
			$val = '';
		else
		{
			if ( CMBStr::strlen( $val ) > 20 )
			{
				$val = CMBStr::substr( $val, 0, 20 ) . '...';
			}

			$val = CStr::html( $val );

			if ( $val != '' )
			{
				$val = "(" . $val . ")";
			}
		}
		$err_msg = CMBStr::replace( '##v##', $val, $err_msg );

		//-- Print caption
		if ( is_null( $caption ) ) $caption = '';
		$err_msg = CMBStr::replace( '##c##', $caption, $err_msg );

		return $err_msg;
	}

	//-- Stops immediately and gives an err message to users
	function ShowError( $msg )
	{
		$encoding = SYS_INTERNAL_ENCODING;

		//--- [BEGIN] Template ---
		$s =<<<_EOM_
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$encoding}" />
<title>Error</title>
</head>
<body>
	<div style='
		margin:20px 20px 20px 20px;
		padding:10px 10px 10px 10px;
		font-weight:bold;
		text-align:left;
		color:#ff0000;
		border:1px #ff0000 solid;
		background-color:#ffe0e0
	'>{$msg}
	</div>
</body>
</html>
_EOM_;
		//--- [END] Template ---

		echo CMBStr::replace( '##msg##', $msg, $s );
		exit;
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>