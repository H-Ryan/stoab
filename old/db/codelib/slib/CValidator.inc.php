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
// CValidator
//----------------------------------------------------------------
class CValidator
{
	function IsInteger( $v )
	{
		if ( strlen( $v ) == 0 ) return false;
		if ( !is_numeric( $v ) ) return false;
		if ( doubleval( $v ) - intval( $v ) != 0 ) return false;
		return true;
	}

	function IsFloat( $v )
	{
		if ( strlen( $v ) == 0 ) return false;
		if ( !is_numeric( $v ) ) return false;
		return true;
	}

	function IsEmailAddress( $v )
	{
		$s = trim( $v );
		if ( $s  != "" )
		{
			$p1 = mb_strpos( $s, "@" );
			$p2 = mb_strrpos( $s, "." );
			if (( $p1  <= 0 )  ||  ( mb_strlen( $s )-1 <= $p2 )  ||  ( $p2  - $p1  <= 1 )  ||  ( mb_strpos($s ," " ) !== false ) )
				return false;
		}
		return true;
	}
	
	function DateExists( $year, $month, $day )
	{
		if (!( CValidator::IsInteger( $year ) &&
			CValidator::IsInteger( $month ) &&
			CValidator::IsInteger( $day ) )) return false;

		$year = intval($year);
		$month = intval($month);
		$day = intval($day);

		$s1 = "{$year}-{$month}-{$day}";
		if ( !($t = strtotime($s1)) ) return false;
		$s2 = date('Y-n-j', $t );
		
		return ( $s1 == $s2 );
	}

	function IsYYYYMMDD_HHMMSS( $s )
	{
		$pat = "^([ ]*)([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})([ ]*([0-9]{1,2})(:([0-9]{1,2})(:([0-9]{1,2}))?)?)?([ ]*)$";

		mb_regex_encoding( "utf-8" );
		if ( mb_ereg( $pat, $s, $regs ) === false ) return false;

		$year = $regs[2];
		$month = $regs[3];
		$day = $regs[4];

		if ( !CValidator::DateExists( $year, $month, $day ) ) return false;
		
		$hour = $regs[6];
		$min = $regs[8];
		$sec = $regs[10];

		//for( $i=0; $i < 11; $i++ ) echo $i . '=>' . $regs[$i] . '<br>';

		if ( $year != '' )
		{
			$year = (int)$year;
			if ( $year < 1000 ) return false;
			if ( $year > 3000 ) return false;
		}

		if ( $month != '' )
		{
			$month = (int)$month;
			if ( $month < 1 ) return false;
			if ( $month > 12 ) return false;
		}

		if ( $day != '' )
		{
			$day = (int)$day;
			if ( $day < 1 ) return false;
			if ( $day > 31 ) return false;
		}

		if ( $hour != '' )
		{
			$hour = (int)$hour;
			if ( $hour < 0 ) return false;
			if ( $hour >= 24 ) return false;
		}

		if ( $min != '' )
		{
			$min = (int)$min;
			if ( $min < 0 ) return false;
			if ( $min >= 60 ) return false;
		}

		if ( $sec != '' )
		{
			$sec = (int)$sec;
			if ( $sec < 0 ) return false;
			if ( $sec >= 60 ) return false;
		}

		return true;
	}
	
	function IsDateTime( $s )
	{
		return CValidator::IsYYYYMMDD_HHMMSS( $s );
	}
}

?>