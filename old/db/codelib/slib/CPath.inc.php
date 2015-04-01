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
// CPath
//----------------------------------------------------------------
class CPath
{
	function ThisFileUrl()
	{
		return $_SERVER['PHP_SELF'];
	}

	function ThisFileName()
	{
		$path = CPath::ThisFileUrl();
		$pos = strrpos( $path, '/' );
		return substr( $path, $pos+1 );
	}

	function ThisFolderUrl()
	{
		$path = CPath::ThisFileUrl();
		$pos = strrpos( $path, '/' );
		return substr( $path, 0, $pos+1 );
	}

	function ThisFilePath()
	{
		if ( isset( $_SERVER['SCRIPT_FILENAME'] ) )
			$path = $_SERVER['SCRIPT_FILENAME'];
		else
			$path = $_SERVER["PATH_TRANSLATED"];

		return str_replace( "\\", '/', $path );
	}

	function ThisFolderPath()
	{
		$path = CPath::ThisFilePath();
		if (( $pos = strrpos( $path, '/' ) ) !== false )
			return substr( $path, 0, $pos+1 );
		else if (( $pos = strrpos( $path, "\\" ) ) !== false )
			return substr( $path, 0, $pos+1 );
		else
			return $path;
	}
}

?>