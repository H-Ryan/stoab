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
// cls_fl_aso
//----------------------------------------------------------------
class cls_fl_aso extends CVFieldList
{
	function SetSpec( &$spec )
	{
		include( $this->Get(XA_SPEC_FILE) );
	}
}

//----------------------------------------------------------------
// cls_rlog_date_time
//----------------------------------------------------------------
class cls_rlog_date_time extends CVDateTime
{
	function XProc( &$msg )
	{
		switch ( $msg->Get(XM_CMD) )
		{
		case XC_BEFORE_UPDATE_RECORDSET:
		case XC_BEFORE_INSERT_RECORDSET:
			$this->val = CUtil::CurrentTimeStamp();
			return nothing;
		}

		return parent::XProc( $msg );
	}
}

//----------------------------------------------------------------
// cls_rlog_user_type
//----------------------------------------------------------------
class cls_rlog_user_type extends CVSelection
{
	function XProc( &$msg )
	{
		switch ( $msg->Get(XM_CMD) )
		{
		case XC_BEFORE_UPDATE_RECORDSET:
		case XC_BEFORE_INSERT_RECORDSET:
			$this->SetVal( $this->sys->GetUserType() );
			return nothing;
		}

		return parent::XProc( $msg );
	}

	function GetText( &$msg )
	{
		$s =<<<_EOM_
S=Staff
M=Member
G=Guest
_EOM_;
		return $s;
	}
}

//----------------------------------------------------------------
// cls_rlog_user_id
//----------------------------------------------------------------
class cls_rlog_user_id extends CVInteger
{
	function XProc( &$msg )
	{
		switch ( $msg->Get(XM_CMD) )
		{
		case XC_BEFORE_UPDATE_RECORDSET:
		case XC_BEFORE_INSERT_RECORDSET:
			if ( $this->sys->GetUserType() == UT_GUEST )
				$this->val = null;
			else
			{
				$this->val = $this->sys->AuthSession->GetV(
					$this->sys->Get( XA_FRAME_FIELDSET_ID ) );
			}
			return nothing;
		}

		return parent::XProc( $msg );
	}

	function OutputDefault( &$msg )
	{
		$v = $this->GetVal();
		if ( $v ==  null ) return '';

		$spec = array();

		if ( defined( 'TBL_STAFF' ) )
		{
			$spec[UT_STAFF] = array(
					'table_name'=>TBL_STAFF,
					'id_field'=>'staff_id',
					'name_field'=>'username'
				);
		}

		if ( defined( 'TBL_MEMBER' ) )
		{
			$spec[UT_MEMBER] = array(
					'table_name'=>TBL_MEMBER,
					'id_field'=>'member_id',
					'name_field'=>'username'
				);
		}

		$name = $this->GetName();
		$name = str_replace( "_user_id", "_user_type", $name );
		$p = $this->prt->GetChild( $name );
		$ut = $p->GetVal(); 
		if ( $ut == UT_GUEST )
			$v = RSTR_UT_CAP_G;
		else
		{
			$ax = $spec[$ut];
			$table_name = $ax['table_name'];
			$name_field = $ax['name_field'];
			$id_field  =  $ax['id_field'];
			
			$db =& $this->sys->DB;
			$flist = array( $name_field );
			$qx = array( "{$id_field}={$v}" );
			$sql = $db->GetSQLSelect( $table_name, $flist, $qx );
			$result = $db->Query( $sql );
			if ( $rs = $db->GetRowA( $result ) )
			{
				switch( $ut )
				{
				case UT_MEMBER:
					$v = "[Member]";
					break;
				case UT_STAFF:
					$v = "[Staff]";
					break;
				}
				$v .= " ";
				$v .= CStr::html( $rs[ $name_field ] );
			}
			else
				$v = "";
			$db->FreeResult($result);
		}
		
		return $v;
	}
}

//----------------------------------------------------------------
// cls_rlog_user_name
//----------------------------------------------------------------
class cls_rlog_user_name extends CVText
{
	function XProc( &$msg )
	{
		switch ( $msg->Get(XM_CMD) )
		{
		case XC_BEFORE_UPDATE_RECORDSET:
		case XC_BEFORE_INSERT_RECORDSET:
			if ( $this->sys->GetUserType() == UT_GUEST )
				$this->val = null;
			else
			{
				$this->val = $this->sys->AuthSession->GetV( "username" );
			}
			return nothing;
		}

		return parent::XProc( $msg );
	}

	function OutputDefault( &$msg )
	{
		$name = $this->GetName();
		$name = str_replace( "_user_name", "_user_type", $name );
		$p = $this->prt->GetChild( $name );
		$ut = $p->GetVal(); 
		if ( $ut == UT_GUEST )
			return '(' . RSTR_UT_CAP_G . ')';
		else
		{
			$v = $this->GetVal();
			if ( $v ==  null ) $v = '';
			return $v;
		}
	}
}

//----------------------------------------------------------------
// cls_active
//----------------------------------------------------------------
class cls_active extends CVSelection
{
	function GetText( &$msg )
	{
		$s =<<<_EOM_
Y=Yes
N=No
_EOM_;
		return $s;
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>