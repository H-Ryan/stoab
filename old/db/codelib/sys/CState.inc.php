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


define( 'STR_SYSSTATE_KEY', '_ss' );

//----------------------------------------------------------------
// CState
//----------------------------------------------------------------
class CState extends CObject
{
	var $buff;

	function Setup()
	{
		$this->buff = array();
		CMBStr::unpack_kv( $this->sys->Request->Get( STR_SYSSTATE_KEY ), $this->buff );
	}

  /**
   * Set
   *
   * @param string $key
   * @param string $val
   */
	function Set( $key, $val, $b_force = false )
	{
		if ( $b_force )
			$this->buff[ $key ] = $val;
		else
		{
			if ( $val != '' ) $this->buff[ $key ] = $val;
		}
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
			return '';
	}

  /**
   * Clear
   *
   * @param string $key
   */
	function Clear( $key )
	{
		if ( substr( $key, strlen($key)-1, 1 ) == '*' )
		{
			$key = substr( $key, 0, strlen($key)-1 );
			foreach( $this->buff as $k => $v )
			{
				if ( substr( $k, 0, strlen($key) ) == $key )
					unset($this->buff[ $k ]);
			}
		}
		else
			unset($this->buff[ $key ]);
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

  /**
   * Get state hidden input tag
   *
   * @return string
   */
	function GetStateTag()
	{
		$s = "<input type='hidden' name='" . STR_SYSSTATE_KEY ."' value='" . CMBStr::pack_kv( $this->buff ) . "'/>\r\n";
		return $s;
	}
	
	function PrintAll()
	{
		return CUtil::PrintPairs( $this->buff );
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>