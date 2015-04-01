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
// CZBuffer
//----------------------------------------------------------------
define( 'ZB_NULL', '&nbsp;' );
define( 'ZB_CBF', 1 );
define( 'ZB_VAL', 2 );
define( 'ZB_ATTR', 3 );

class CZBuffer extends CObject
{
	var $buff;
	var $cbf_arr = array();
	var $unknown_cbf_arr = array();
	var $on_set_func = null;
	
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
   * Set call back function
   *
   * @param string $name
   * @param object $obj
   */
	function SetCallBack( $name, &$obj, $method = null )
	{
		$this->cbf_arr[$name] =& $obj;
	}
	
  /**
   * Get
   *
   * @param string $key
   * @param string
   */
	function Get( $key, $filter_type = null, $filter_param = null )
	{
		//---------------------------
		// Boolean
		//---------------------------
		$last_char = substr( $key, strlen($key)-1, 1 );
		$b_bool = false;
		$b_direct = false;
		$format_encoding = 'html';

		if ( $last_char == '?' )
			$b_bool = true;
		else if ( $last_char == '=' )
		{
			$format_encoding = 'text';
			$b_direct = true;
		}

		if ( $b_bool || $b_direct )
			$key = substr( $key, 0, strlen($key)-1 );

		//---------------------------
		// Callback Function
		//---------------------------
		if ( substr($key,0,1) == '@' )
		{
			$key = substr($key,1);
			if ( array_key_exists( $key, $this->cbf_arr ) )
			{
				$ax = split( ":", $key );
				$method_name = $ax[ count($ax)-1 ];
				$obj =& $this->cbf_arr[ $key ];
				return call_user_func( array( &$obj, $method_name ) );
			}
			else 
			{
				if ( array_key_exists( $key, $this->unknown_cbf_arr ) )
				{
					return false;
				}
				else
				{
					$this->unknown_cbf_arr[$key] = 1;
					return true;
				}
			}
		}
		//---------------------------
		// Value
		//---------------------------
		else if ( isset( $this->buff[ $key ] ) )
		{
			$v = $this->buff[ $key ];

			if ( !is_null( $filter_type ) )
			{
				switch( $filter_type )
				{
				case ZB_CBF:
					$callback_function = ( is_null($filter_param) ?
						'zb_callback' : $filter_param );
					$v = call_user_func( $callback_function, $key, $v );
					break;

				case ZB_ATTR:
					$this->ZbAttr( $v, $filter_param );
					break;
				}
			}

			if ( $b_bool )
			{
				return $v;
			}
			else if ( is_object( $v ) )
				$v = $v->Render( $format_encoding );

			if (( $v == null ) || ( $v == '' ))
			{
				if ( $format_encoding == 'text' )
					return '';
				else
					return ZB_NULL;
			}
			else
				return $v;
		}
		//---------------------------
		// Unknown
		//---------------------------
		else
		{
			if ( $b_bool )
				return false;
			else
				return "<font color='#ff0000'>" . $key . "</font>";
		}
	}

  /**
   * Clear
   *
   * @param string $key
   */
	function Clear( $key )
	{
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

	function PrintAll()
	{
		return CUtil::PrintPairs( $this->buff );
	}

	function ZbAttr( &$v, $ax )
	{
		foreach( $ax as $key => $val )
		{
			$v->SetKV( $key, $val );
		}
	}
}

//-----------------------------------------------------------------------
// END OF FILE
//-----------------------------------------------------------------------
?>