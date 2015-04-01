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
// CRequest
//----------------------------------------------------------------
class CRequest extends CObject
{
	var $buff;

  /**
   * Initialize Object
   *
   * @param object $prt
   */
	function Setup()
	{
		//-- Reverse magic_quote effect
		if ( get_magic_quotes_gpc() )
		{
			function stripslashes_deep($value)
			{
				$value = is_array($value) ?
					array_map('stripslashes_deep', $value) :
					stripslashes($value);
				return $value;
			}

			$_POST = array_map('stripslashes_deep', $_POST);
			$_GET = array_map('stripslashes_deep', $_GET);
			$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
			$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
		}

		$this->buff = array();

		//--- Get new states from GET and POST
		foreach ( $_POST as $key => $val )
			$this->buff[$key] = $val;

		foreach ( $_POST as $key => $val )
		{
			$ax = explode( '&' , $key );
			for ( $i = 0; $i < count( $ax ); $i++ )
			{
				$bx = explode( '=' , $ax[ $i ] );
				if ( count( $bx ) == 2 )
					$this->buff[ $bx[ 0 ] ]   = $bx[ 1 ];
			}
		}

		foreach ( $_GET as $key => $val )
			$this->buff[$key] = $val;

		foreach ( $_GET as $key => $val )
		{
			$ax = explode( '&' , $key );
			for ( $i = 0; $i < count( $ax ); $i++ )
			{
				$bx = explode( '=', $ax[ $i ] );
				if ( count( $bx ) == 2 )
					$this->buff[ $bx[ 0 ] ] = $bx[ 1 ];
			}
		}
	}

  /**
   * Set
   *
   * @param string $key
   * @param string $val
   */
	function Set( $key, $val )
	{
		$this->buff[ $key ] = $val;
	}

  /**
   * Get
   *
   * @param string $key
   * @return string
   */
	function Get( $key )
	{
		if ( isset( $this->buff[ $key ] ) )
			return $this->buff[ $key ];
		else
			return null;
	}

  /**
   * Count
   *
   * @return integer
   */
	function Count()
	{
		return count($this->buff);
	}

	function SetToState( $key )
	{
		if ( substr( $key, strlen($key)-1, 1 ) == '*' )
			$key = substr( $key, 0, strlen($key)-1 );

		foreach ( $this->buff as $k => $v )
		{
			if ( substr( $k, 0, strlen( $key ) ) == $key )
			{
				$this->sys->State->Set( $k, $v );
			}
		}
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>