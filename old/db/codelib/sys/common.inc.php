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
// Language Code
//----------------------------------------------------------------
define( 'LC_ENG', 'eng' );
define( 'LC_JPN', 'jpn' );

//----------------------------------------------------------------
// _LANG_FILE_
//----------------------------------------------------------------
function _LANG_FILE_( $filename )
{
	global $LANG_CODE;
	if ( !in_array( $LANG_CODE, array(
		LC_ENG,
		LC_JPN
	) ) )
	{
 		echo 'Invalid Language Code';
 		exit;
	}
	return str_replace( "##LANG_CODE##", $LANG_CODE, $filename ); 
}

//----------------------------------------------------------------
// Include Global Cfg & Global SLib
//----------------------------------------------------------------
require(dirname(__FILE__) . "/../cfg/common.inc.php");
require(dirname(__FILE__) . "/../slib/common.inc.php");

//----------------------------------------------------------------
// Sys Files
//----------------------------------------------------------------
require("sysconst.inc.php");

require("CObject.inc.php");
require("CError.inc.php");
require("CSession.inc.php");
require("CHtmlMacro.inc.php");
require("COutObject.inc.php");
require("CVMsg.inc.php");
require("CSysCmd.inc.php");
require("CVField.inc.php");
require("CVFieldSet.inc.php");
require("CVFieldList.inc.php");
require("CVPageSet.inc.php");
require("CAuthorization.inc.php");
require("CAuthSession.inc.php");
require("CPageSig.inc.php");
require("CRequest.inc.php");
require("CState.inc.php");
require("CZBuffer.inc.php");
require("CSysInfo.inc.php");
require("CVSystem.inc.php");
require("CMySQL.inc.php");
require("CRecordList.inc.php");
require("CPageTab.inc.php");
require("CSysObject.inc.php");
require("CMenu.inc.php");

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>