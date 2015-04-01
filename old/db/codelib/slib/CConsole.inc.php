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
// CConsole
//----------------------------------------------------------------
$CConsole_path_log = "";

class CConsole
{
	//-- private static $path_log = ""; //-- PHP5 only

	function Write( $path, $msg )
	{
		global $CConsole_path_log;

		$s = CConsole::PrintKV( $path, $msg );

		if ( $CConsole_path_log == "" )
		{
			$dir = DEBUG_PATH_CONSOLE_OUT;
			$CConsole_path_log = $dir . date( "Y-m-d_H-i-s" ) . ".html";
		}

		if( file_exists( $CConsole_path_log) )
			$f = fopen( $CConsole_path_log, 'a');
		else
			$f = fopen( $CConsole_path_log, 'w');

		fwrite( $f, $s );
		fclose( $f );
	}

	function PrintKV( $key, $val )
	{
		if ( strpos( $val, "<" ) == -1 )
			$val = CStr::html($val);

		$s = '';
		$s .= "<table border='0' cellpadding='4', cellspacing='3'>";
		$s .= '<tr>';
		$s .= "<td bgcolor='#008000'><font color='#ffffff'>" . CStr::html($key) . "</font></td>";
		$s .= "<td bgcolor='#c0ffc0'>" . $val .'</td>';
		$s .= '</tr>';
		$s .= '</table>';
		$s .= '<br/>';

		return $s;
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>