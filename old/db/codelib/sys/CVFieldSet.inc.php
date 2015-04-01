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
// FieldSet
//----------------------------------------------------------------
class CVFieldSet extends CVField
{
	//----------------------------------------------------------------
	// SendMsg
	//----------------------------------------------------------------
	function SendMsg( $ls, $msg_arr )
	{
		$msg =& new CVMsg();
		$msg->Init( $this, $ls, $msg_arr );
		$obj_list = array();

		foreach( $ls as $name )
			$obj_list[$name] =& $this->GetChild( $name );

		return $this->SendMsgToChildren( $obj_list, $msg );
	}

	//----------------------------------------------------------------
	// SendMsgToChildren
	//----------------------------------------------------------------
	function SendMsgToChildren( &$obj_list, &$msg )
	{
		$ret = array();
		foreach( $obj_list as $name => $obj )
		{
			if ( !isset( $obj_list[$name] ) )
			{
				echo "object does not exists : <br>";
				echo $this->Get(XA_CLASS) . " => " . $name . "<br>";
				exit;
			}

			if ( DEBUG_TRACE_MSG_LOOP )
			{
				echo $name . ":" . $msg->Get(XM_CMD) . "<br/>";
			}

			$r = $obj_list[$name]->XProc( $msg );
			if ( $r != nothing ) $ret[$name] = $r;
		}
		return $ret;
	}

  /**
   * Event Message Procedure
   *
   * @param array $msg_arr
   * @return string
   */
	function XProc( &$msg )
	{
		switch ( $msg->Get(XM_CMD) )
		{
		case XC_IS_INPUT:
		case XC_IS_STATE:
		case XC_OF_STATE:
		case XC_CLEAR_STATE:
			return $this->SendMsgToChildren( $this->clist, $msg );
		}

		return parent::XProc( $msg );
	}

  /**
   * IsEmpty
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function IsEmpty( &$msg )
	{
		$b = true;
		foreach( $this->clist as $name => $obj )
			$b = $b && $obj->IsEmpty( $msg );
		return $b;
	}

  /**
   * Validate Empty
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function Validate_Empty( &$msg )
	{
		$b = parent::Validate_Empty( $msg );
		if ( !$b )
		{
			$err_msg = $this->GetErrMsg();
			$ret = array();
			foreach( $this->clist as $name => $obj )
				$obj->SetErrMsg( $err_msg );
		}
		return $b;
	}

  /**
   * Validate Value
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function Validate_Value( &$msg )
	{
		$b = true;
		$ret = array();
		foreach ( $this->clist as $name => $obj )
		{
			$ret[$name] = $obj->Validate( $msg );
			$b = $b && $ret[$name];
		}
		if ( !$b ) return $ret;
		return $this->Validate_Relation( $msg );
	}

  /**
   * Validate Relation
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function Validate_Relation( &$msg )
	{
		return true;
	}
	
  /**
   * Get Value
   *
   * @param object $msg
   * @return string
   */
	function GetValue( &$msg )
	{
		return "*** FieldSet ***";
	}

  /**
   * Get Err List
   *
   * @param array $ret
   * @return array
   */
	function GetErrList( $ret )
	{
		$err_list = array();
		foreach ( $ret as $name=>$r )
		{
			$child =& $this->GetChild( $name );
			if ( is_array( $r ) )
			{
				if ( count( $r ) > 0 )
				{
					$err_list = array_merge( $err_list, $child->GetErrList( $r ) );
				}
			}
			else if ( $r == false )
				$err_list[] = $child->GetErrMsg();
		}
		return $err_list;
	}

	function FrameChildCaption( $s )
	{
		if ( substr($s,0,1) != '(' )
			$s = ' (' . $s . ')';
		else
			$s = ' ' . $s;
			
		return $s;
	}
}

//-----------------------------------------------------------------------
// END OF FILE
//-----------------------------------------------------------------------
?>