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
// CSession
//----------------------------------------------------------------
class CSession extends CObject
{
	function Setup()
	{
		//-------------------------------------
		//-- session_cache_limiter('none') 
		//-- prevents "page expired" error from
		//-- IE when pressing back-button
		//-------------------------------------
		@session_cache_limiter('none');
		//-----------------------------------

		@session_start();
		//header('Cache-control: private'); 
		//header('Cache-Control: max-age=3600, must-revalidate'); 
		//header('Cache-Control: max-age=1, must-revalidate'); 
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>