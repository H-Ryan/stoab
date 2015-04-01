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


define( 'STR_MOCKUP_PREFIX', '' ); 
define( 'STR_MOCKUP_EXT', '.html' ); 

//----------------------------------------------------------------
// CToHtml
//----------------------------------------------------------------
class CToHtml
{
	function GetNormSCList()
	{
		return array(
			'frame.login'=>'index',
			'frame.logoff'=>'index',
			'frame.auth'=>'member.search'
		);
	}

	function Save( $pageset, $cmd, $txt )
	{
		//-- If cmd starts with "_pb" or "_ret", don't need to save the page.
		if ( CToHtml::RemovePostfix( $cmd, "_pb" ) ) return false;
		if ( CToHtml::RemovePostfix( $cmd, "_ret" ) ) return false;

		//-- Change the links' destination
		CToHtml::ReplaceLinkDest( $pageset, $txt );

		//-- Change the buttons' destination
		CToHtml::ReplaceButtonDest( $pageset, $txt );

		//-- Remove tags from $txt
		CToHtml::RemoveTag( $txt, "<form name='main' " );
		CToHtml::RemoveTag( $txt, "<input type='hidden' name='_ss' " );
		CToHtml::RemoveTag( $txt, "<input type='hidden' name='_sc' " );
		CToHtml::RemoveTag( $txt, "</form" );

		//-- Set up the folder path
		$path = CPath::ThisFolderPath();
		$path = str_replace( "/web/", "/web_html/", $path );

		//-- Setup Sc
		$sc = CToHtml::NormalizeSC( $pageset . "." . $cmd );

		//-- Set up the file name
		$fn = STR_MOCKUP_PREFIX . $sc . STR_MOCKUP_EXT;

		//-- Save the html
		$f = fopen( $path . $fn, "w");
		fwrite ( $f, $txt );
		fclose( $f );
	}

	function ReplaceLinkDest( $ps, &$txt )
	{
		$sepa = " href=\"" . CPath::ThisFileUrl() . "\?_sc=";
		$tx = split( $sepa, $txt );
		
		for ( $i = 1; $i < count( $tx ); $i++ )
		{
			$sx = $tx[$i];
			if (( $pos = strpos( $sx, "&" ) ) === false )
			{
				echo "Error : CToHtml::ReplaceLinkDest : Does not end with &.";
				die;
			}

			$ss = substr( $sx, 0, $pos );

			$ax = split( "/", $ss );
			if ( count( $ax ) != 2 ) return false;

			$pageset = $ax[0];
			$pageset = str_replace( "_this", $ps, $pageset );

			$cmd = $ax[1];
			CToHtml::RemovePostfix( $cmd, "_pb" );
			CToHtml::RemovePostfix( $cmd, "_ret" );
			if ( $cmd == '' ) $cmd = 'search';

			//-- Setup Sc
			$sc = CToHtml::NormalizeSC( $pageset . "." . $cmd );

			$fn = STR_MOCKUP_PREFIX . $sc . STR_MOCKUP_EXT;

			$tx[$i] = " href=\"" . $fn . substr( $sx, $pos+1 );
		}

		$txt = implode( "", $tx );
	}

	function ReplaceButtonDest( $ps, &$txt )
	{
		$sepa = " name=\"_sc=";
		$tx = split( $sepa, $txt );

		for ( $i = 1; $i < count( $tx ); $i++ )
		{
			$sx = $tx[$i];
			if (( $pos = strpos( $sx, "&" ) ) === false ) return false;

			$ss = substr( $sx, 0, $pos );
			$ax = split( "/", $ss );
			if ( count( $ax ) != 2 ) return false;

			$pageset = $ax[0];
			$pageset = str_replace( "_this", $ps, $pageset );

			$cmd = $ax[1];
			CToHtml::RemovePostfix( $cmd, "_pb" );
			CToHtml::RemovePostfix( $cmd, "_ret" );

			//-- Setup Sc
			$sc = CToHtml::NormalizeSC( $pageset . "." . $cmd );

			$fn = STR_MOCKUP_PREFIX . $sc . STR_MOCKUP_EXT;

	 		$onclick = " onclick=\"javascript:document.location='{$fn}';return false;\"";
			$tx[$i-1] .= $onclick;
			
			//"<a href=\"" . $path . "\"><img border='0'";
		}
		$txt = implode( $sepa, $tx );
	}

	function NormalizeSC( $sc )
	{
		$ls = CToHtml::GetNormSCList();
		foreach( $ls as $key => $val )
		{
			if ( $sc == $key ) return $val;
		}
		return $sc;
	}
			
	function RemovePostfix( &$s, $postfix )
	{
		if ( substr( $s, -strlen($postfix) ) == $postfix )
		{
			$s = substr( $s, 0, strlen($s)-strlen($postfix) );
			return true;
		}
		else
			return false;
	}

	function RemoveTag( &$s, $key )
	{
		if (( $pos = strpos( $s, $key )) !== false )
		{
			if (( $pos_e = strpos( $s, ">", $pos ) ) !== false )
			{
				$s1 = substr( $s, 0, $pos );
				$s2 = substr( $s, $pos_e+1 );
				$s = $s1 . $s2;
			}
		}
	}
}

?>