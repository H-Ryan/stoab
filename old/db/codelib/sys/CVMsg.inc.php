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
// CVMsg
//----------------------------------------------------------------
class CVMsg
{
	var $sender;
	var $ls;
	var $msg_arr;

  /**
   * Initialize Object
   *
   * @param object $prt
   * @param array $ls
   * @param array $msg_arr
   */
	function Init( $sender, &$ls, &$msg_arr )
	{
		$this->sender = $sender;
		$this->ls =& $ls;
		$this->msg_arr =& $msg_arr;
	}

  /**
   * Get
   *
   * @param string $key
   * @return string
   */
	function Get( $key )
	{
		if ( isset( $this->msg_arr[$key] ) )
			return $this->msg_arr[$key];
		else
			return '';
	}

  /**
   * Set
   *
   * @param string $key
   * @param string $val
   */
	function Set( $key, $val )
	{
		$this->msg_arr[$key] = $val;
		return $val;
	}
}

//-----------------------------------------------------------------------
// END OF FILE
//-----------------------------------------------------------------------
?>