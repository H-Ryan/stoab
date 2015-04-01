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
// CUtil
//----------------------------------------------------------------
class CUtil
{
	function &CreateObject( $class_name )
	{
		$p = null;
		eval( "\$p =& new " . $class_name . ";" );
		return $p;
	}

	function CurrentTimeStamp()
	{
		return date("Y-m-d H:i:s");
	}

	function DateAdd( $t, $d_year, $d_month, $d_day, $d_hour, $d_min, $d_sec )
	{
		return mktime(
			date( "H", $t ) + $d_hour,
			date( "i", $t ) + $d_min,
			date( "s", $t ) + $d_sec,
			date( "m", $t ) + $d_month,
			date( "d", $t ) + $d_day,
			date( "Y", $t ) + $d_year
		);
	}

	function IsIE( $def = true )
	{
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) )
			return ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) !== false );
		else
			return $def;
	}
	
	function IsWebFetch()
	{
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) )
			return ( strpos( $_SERVER['HTTP_USER_AGENT'], 'WebFetch' ) !== false );
		else
			return false;
	}
	
	function CreateRandomString( $n )
	{
		$s = '';
		for ( $i = 0; $i < $n; $i++ )
		if ( rand( 1, 2 ) == 1 )
			$s .= chr(rand(97, 122));
		else
			$s .= chr(rand(65, 90));
		return $s;
	}
	
	function CheckBeginning( $s, $needle )
	{
		return ( substr( $s, 0, strlen( $needle ) ) == $needle );
	}

	function PrintPairs( $kv )
	{
		$s = '';
		$s .= "<table border='0' cellpadding='4', cellspacing='3'>";

		$s .= '<tr>';
		$s .= "<td bgcolor='#000080' colspan='2'><font color='#ffffff'>State</font></td>";
		$s .= '</tr>';
		
		foreach ( $kv as $key => $val )
		{
			$s .= '<tr>';
			$s .= "<td bgcolor='#000080'><font color='#ffffff'>" . CStr::html($key) . "</font> : </td>";
			$s .= "<td bgcolor='#c0c0ff'>" . CStr::html($val) .'</td>';
			$s .= '</tr>';
		}
		$s .= '</table>';

		return $s;
	}
	
	function Text2Dict( $txt )
	{
		$txt = CMBStr::replace( "\r", '', $txt );
		$ax = CMBStr::split( "\n", $txt );
		$bx = array();

		for ( $i = 0; $i < count( $ax ); $i++ )
		{
			$L = $ax[ $i ];

			if ( CMBStr::substr($L,0,2) == "//" )
			{
				// do nothing
			}
			else if ( $L != "" )
			{
				CMBStr::splite( $L, $key, $val );
				$bx[$key] = $val;
			}
		}
		return $bx;
	}

	function Redirect( $url )
	{
		header( 'Location: ' . $url );
	}

	function RelRedirect( $relpath, $b_https = false )
	{
		$path = $_SERVER['PHP_SELF'];
		$path_parts = pathinfo( $path );
	    $dirname = $path_parts['dirname'];
		$_bp_ = $dirname . '/' . $relpath;
		$_host_ =  $_SERVER["HTTP_HOST"];

		if ( $b_https )
		{
			$url = "https://" . $_host_ . $_bp_;
			//-- For local versions
			//$url = str_replace( "/httpdocs/", "/httpsdocs/", $url );
		}
		else
		{
			$url = "http://" . $_host_ . $_bp_;
			//-- For local versions
			//$url = str_replace( "/httpsdocs/", "/httpdocs/", $url );
		}

		session_write_close();
		CUtil::Redirect( $url );
	}

	function EncryptPassword( $s )
	{
		return md5($s);
	}

	function JSDocumentWrite( $txt )
	{
		if ( isset( $_GET['_direct_'] ) )
		{
			return $txt;
		}
		$txt = str_replace( "\r", "", $txt );
		$ax = split( "\n", $txt );
		$bx = array();
		foreach( $ax as $line )
		{
			if ( $line != "" )
			{
				$s = str_replace( '"', '\"', $line );
				$s = str_replace( '</script>', '</scr"+"ipt>', $line );
				$s = "document.write( \"" . $s . "\" );\r\n";
				$bx[] = $s;
			}
		}
		return implode( "\r\n", $bx );
	}

	function GetCurrentPageURL()
	{
		$pageURL = 'http';

		if (( isset($_SERVER["HTTPS"]) ) && ( $_SERVER["HTTPS"] == "on" ))
		{
			$pageURL .= "s";
		}
		$pageURL .= "://" . $_SERVER["SERVER_NAME"];

		if ($_SERVER["SERVER_PORT"] != "80")
		{
			$pageURL .= $_SERVER["SERVER_PORT"];
		}

		$pageURL .= $_SERVER['PHP_SELF'];

		return $pageURL;
	}
}

?>