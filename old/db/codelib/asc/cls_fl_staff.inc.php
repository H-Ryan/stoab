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
// cls_fl_staff
//----------------------------------------------------------------
class cls_fl_staff extends cls_fl_aso
{
}

//----------------------------------------------------------------
// cls_group_id
//----------------------------------------------------------------
class cls_group_id extends CVSelection
{
	function GetText( &$msg )
	{
		$s =
			GROUP_ADMIN . "=" . RSTR_ADMINISTRATOR . "\r\n" .
			GROUP_STAFF . "=" . RSTR_GENERAL_STAFF;
		return $s;
	}
}

//----------------------------------------------------------------
// cls_password_encrypt
//----------------------------------------------------------------
class cls_password_encrypt extends CVPassword
{
	function XProc( &$msg )
	{
		switch ( $msg->Get(XM_CMD) )
		{
		case XC_BEFORE_INSERT_RECORDSET:
		case XC_BEFORE_UPDATE_RECORDSET:
			$this->keep_val = $this->GetVal();
			$this->SetVal( $this->sys->EncryptPassword( $this->keep_val ) );
			return nothing;

		case XC_AFTER_INSERT_RECORDSET:
		case XC_AFTER_UPDATE_RECORDSET:
			$this->SetVal( $this->keep_val );
			return nothing;
		}

		return parent::XProc( $msg );
	}
}

//----------------------------------------------------------------
// cls_active_login
//----------------------------------------------------------------
class cls_active_login extends CVField {}

//----------------------------------------------------------------
// cls_username_login
//----------------------------------------------------------------
class cls_username_login extends CVAsciiText
{
	function Validate_Value( &$msg )
	{
		$v = $this->val;
		if ( !parent::Validate_Value( $msg ) ) return false;
		return true;
	}
}

//----------------------------------------------------------------
// cls_password_login
//----------------------------------------------------------------
class cls_password_login extends CVPassword
{
	function Validate_Value( &$msg )
	{
		$v = $this->val;
		return true;
	}
}

//----------------------------------------------------------------
// cls_password_curr
//----------------------------------------------------------------
class cls_password_curr extends CVPassword
{
	function Validate_Value( &$msg )
	{
		$v = $this->val;
		if ( !parent::Validate_Value( $msg ) ) return false;
		return true;
	}
}

//----------------------------------------------------------------
// cls_password_new
//----------------------------------------------------------------
class cls_password_new extends CVPassword
{
	function Validate_Value( &$msg )
	{
		$v = $this->val;
		if ( !parent::Validate_Value( $msg ) ) return false;
		if ( !$this->Validate_Password( $v ) ) return false;
		return true;
	}
	
	function Validate_Password( $v )
	{
		$s = CStr::n2e( $v );
		return true;
	}
}

//----------------------------------------------------------------
// cls_password_conf
//----------------------------------------------------------------
class cls_password_conf extends CVPassword
{
	function Validate_Value( &$msg )
	{
		$v = $this->val;
		if ( !$this->Validate_Conf( $v ) ) return false;
		if ( !parent::Validate_Value( $msg ) ) return false;
		return true;
	}
	
	function Validate_Conf( $v )
	{
		$p = $this->prt->GetChild('password_new');
		if ( !( $v == $p->GetVal() ) )
		{
			$this->SetErrMsg( RSTR_ERR_CAN_NOT_CONFIRM, $v );
			return false;
		}

		return true;
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>