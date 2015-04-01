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
// CVSystem
//----------------------------------------------------------------
class CVSystem extends CObject
{
	//------------------------------------------------------------
	// [BEGIN] Static Function
	//------------------------------------------------------------
	function &SetupSystem( $attri = null )
	{
		if ( $attri == null )
		{
			$attri = array(
				XA_CLASS=>'CVSystem'
			);
		}

		$null_ptr = null;
		$sys =& CObject::SetupObject( $null_ptr, 'sys', $attri );

		//-- [BEGIN] Set Lang Code
		global $LANG_CODE;
		$sys->SetLangCode( $LANG_CODE );
		//-- [END] Set Lang Code

		//-- [BEGIN] Set Default User Type
		$sys->SetUserType( UT_GUEST );
		//-- [END] Set Default User Type

		return $sys;
	}

	function &RunSystem( $attri )
	{
		$sys =& CObject::SetupSystem( $attri );
		$sys->Run();
		return $sys;
	}
	//-----------------------------------------------------------
	// [END] Static Function
	//-----------------------------------------------------------

	function Init( &$prt, $name = null, $attri = null )
	{
		parent::Init( $prt, $name, $attri );

		global $sys;
		$sys =& $this;
		$this->sys =& $this;

		global $LANG_CODE;
		$this->SetLangCode( $LANG_CODE );
	}
	
	function CreateChildren()
	{
		$this->OnCompoSpec( $compo );

		foreach ( $compo as $key => $val )
		{
			if ( $val != null )
			{
				$compo[$key] =& $this->CreateObject( $compo[$key] );
				eval( "\$this->" . $key . " =& \$compo['" . $key . "'];" );
				$compo[$key]->Init( $this );
			}
		}

		$this->OnLoadPageListSpec();
	}

	function OnCompoSpec( &$compo )
	{
		$compo = array(
			'Error'=>'CError',
			'Session'=>'CSession',
			'Authorization'=>'CAuthorization',
			'AuthSession'=>'CAuthSession',
			'PageSig'=>'CPageSig',
			'ZBuffer'=>'CZBuffer',
			'HtmlMacro'=>'CHtmlMacro',
			'Request'=>'CRequest',
			'State'=>'CState',
			'DB'=>'CDatabase',
			'SysInfo'=>'CSysInfo');
	}

	function OnLoadPageListSpec()
	{
		// Must be overwritten :
		//------------------------------------------
		//[e.g.] include( 'df_pageset.inc.php' );
		//[e.g.] $this->SetPageSetSpec( $spec );
		//------------------------------------------
	}

	function SetPageSetSpec( $spec )
	{
		$this->spec_pageset = $spec;
	}

	//-----------------------------------------------------------
	// [BEGIN] Request
	//-----------------------------------------------------------
	function GetIV( $key )
	{
		$v = $this->Request->Get( $key );
		if ( $v == null ) $v = ""; 
		return $v;
	}

	function SetIV( $key, $val )
	{
		$this->Request->Set( $key, $val );
	}
	//-----------------------------------------------------------
	// [END] Request
	//-----------------------------------------------------------

	//-----------------------------------------------------------
	// [BEGIN] Language Code
	//-----------------------------------------------------------
	function GetLangCode()
	{
		return $this->lang_code;
	}

	function SetLangCode( $lang_code )
	{
		$this->lang_code = $lang_code;
	}
	//-----------------------------------------------------------
	// [END] Language Code
	//-----------------------------------------------------------

	//-----------------------------------------------------------
	// [BEGIN] UserType
	//-----------------------------------------------------------
	function GetUserType()
	{
		return $this->user_type;
	}

	function GetUserTypeCaption()
	{
		return constant( 'RSTR_UT_CAP_' . $this->GetUserType() );
	}

	function SetUserType( $user_type )
	{
		$this->user_type = $user_type;
	}
	//-----------------------------------------------------------
	// [END] UserType
	//-----------------------------------------------------------

	//-----------------------------------------------------------
	// Run
	//-----------------------------------------------------------
	function Run()
	{
		$this->ZBuffer->SetCallBack( 'page:state', $this );

		//-- if ( SYS_DB_TRANSACTION )
		//-- {
		//-- 	$this->DB->BeginTrans();
		//-- }

		$sc =& new CSysCmd( $this );

		while ( $sc->GetNextPageSet( $ps ) )
		{
			if ( !isset( $this->spec_pageset[$ps] ) ) 
			{
				$sc->RaiseError( SC_ERR_PAGE_NOT_FOUND );
			}
			else
			{
				$this->PageSet =& $this->SetupObject( $this, $ps, $this->spec_pageset[$ps] );
				$this->PageSet->Run( $sc );
			}
		}

		$sc->ProcessPage();

		//-- if ( SYS_DB_TRANSACTION )
		//-- {
		//-- 	$this->DB->EndTrans();
		//-- }

		if ( DEBUG_WRITE_TO_CONSOLE )
		{
			CConsole::Write( get_class($this) . "/state", $this->State->PrintAll() );
		}
	}

	function GetPageSet()
	{
		return $this->PageSet;
	}

	//------------------------------------------------------------
	// CallBack
	//------------------------------------------------------------
	function state()
	{
		return $this->sys->State->GetStateTag();
	}
}

//-----------------------------------------------------------------------
// END OF FILE
//-----------------------------------------------------------------------
?>