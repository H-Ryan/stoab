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
// CSysInfo
//----------------------------------------------------------------
class CSysInfo extends CObject
{
	function Setup()
	{
		$this->sys->ZBuffer->Set('page:err_msg', "");
		$this->sys->ZBuffer->Set('page:info_msg', "");
		$this->list_err = array();
		$this->list_info = array();
	}

	function Commit()
	{
		$err_msg = implode( '<br>', $this->list_err );
		$this->sys->ZBuffer->Set('page:err_msg', $err_msg );

		$info_msg = implode( '<br>', $this->list_info );
		$this->sys->ZBuffer->Set('page:info_msg', $info_msg );
	}

	function SetErrMsg( $list_err )
	{
		if ( is_array( $list_err ) ) 
			$this->list_err = array_merge( $this->list_err, $list_err );
		else
			$this->list_err[] = $list_err;
		
		return ( count( $list_err ) > 0 );
	}
	
	function SetInfoMsg( $list_info )
	{
		if ( is_array( $list_info ) ) 
			$this->list_info = array_merge( $this->list_info, $list_info );
		else
			$this->list_info[] = $list_info;

		return ( count( $list_info ) > 0 );
	}

}

//-----------------------------------------------------------------------
// END OF FILE
//-----------------------------------------------------------------------
?>