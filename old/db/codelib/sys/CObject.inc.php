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


class CObject
{
	var $sys; // System
	var $prt; // Parent
	var $name; // Name
	var $clist; // Children List

	//----------------------------------------------------------------
	// Init
	//----------------------------------------------------------------
  /**
   * Initialize Object
   *
   * @param object $prt
   */
	function Init( &$prt, $name = null, $attri = null )
	{
		if ( is_null( $prt ) )
		{
			$this->prt = null;
			$this->sys = null;
		}
		else
		{
			$this->prt =& $prt;
			if ( !is_null( $prt->sys ) ) $this->sys =& $prt->sys;

			if ( $name == null )
				$this->prt->clist[] =& $this;
			else
				$this->prt->clist[$name] =& $this;
		}
		
		$this->name = $name;
		$this->attri = $attri;
		$this->clist = array();
	}

	function Get( $key )
	{
		if ( isset( $this->attri[$key] ) )
			return $this->attri[$key];
		else
			return '';
	}

	function Set( $key, $val )
	{
		$this->attri[$key] = $val;
		return $val;
	}

	//------------------------------------------------------------
	//
	// Create
	//
	// - This is a non-message-type function
	// - Creates children and call Create function of each child
	// - "Create" and "Setup" must be performed separately
	//   since Setup assumes all child objects have alreayd been
	//   created.
	//
	//------------------------------------------------------------
	function Create()
	{
		$this->CreateChildren();
		foreach( $this->clist as $name => $obj )
			$this->clist[$name]->Create();
	}

	//----------------------------------------------------------------
	// CreateChildren
	//----------------------------------------------------------------
	function CreateChildren()
	{
	}

	//----------------------------------------------------------------
	// ConstructObjects
	//----------------------------------------------------------------
	function ConstructObjects( $spec )
	{
		foreach( $spec as $name => $attri )
			$this->ConstructObject( $name, $attri );
	}
	
	//----------------------------------------------------------------
	// ConstructObject
	//----------------------------------------------------------------
	function &ConstructObject( $name, $attri )
	{
 		$obj =& $this->CreateObject( $attri[XA_CLASS] );
		$obj->Init( $this, $name, $attri );
		return $obj;
	}

	//------------------------------------------------------------
	//
	// Setup
	//
	// - This is a non-message-type function
	// - "Create" and "Setup" must be performed separately
	//   since Setup assumes all child objects have alreayd been
	//   created.
	//
	//------------------------------------------------------------
	function Setup()
	{
		foreach( $this->clist as $key => $obj ) $this->clist[$key]->Setup();
	}

	//----------------------------------------------------------------
	// GetChild
	//----------------------------------------------------------------
	function &GetChild( $name )
	{
		return $this->clist[$name];
	}

	//----------------------------------------------------------------
	// GetChildList
	//----------------------------------------------------------------
	function GetChildList()
	{
		return $this->clist;
	}

	//----------------------------------------------------------------
	// GetName
	//----------------------------------------------------------------
	function GetName()
	{
		return $this->name;
	}
	
	//----------------------------------------------------------------
	// Tools
	//----------------------------------------------------------------
	function &CreateObject( $class_name )
	{
		if ( !class_exists( $class_name ) )
		{
			CObject::SystemError( get_class($this) . '/' . __METHOD__, "Class ({$class_name}) does not exist." );
		}
		
		$p = null;
		eval( "\$p =& new " . $class_name . ";" );
		return $p;
	}

	function &InitObject( &$prt, $name, $attri )
	{
		$obj =& CObject::CreateObject( $attri[XA_CLASS] );
		$obj->Init( $prt, $name, $attri );
		return $obj;
	}

	function &SetupObject( &$prt, $name, $attri )
	{
		$obj =& CObject::InitObject( $prt, $name, $attri );
		$obj->Create();
		$obj->Setup();
		return $obj;
	}

	function &RunObject( &$prt, $name, $attri )
	{
		$obj =& CObject::SetupObject( $prt, $name, $attri );
		$obj->Run();
		return $obj;
	}

	//----------------------------------------------------------------
	// SystemError
	//----------------------------------------------------------------
	function SystemError( $loc, $msg )
	{
		$s = "";
		$s .= "<hr>";
		$s .= "<font color='#40404'>Location : </font><font color='#FF0000'><b>" . $loc . "</b></font><br>";
		$s .= "<font color='#40404'>Message  : </font><font color='#FF0000'><b>" . $msg . "</b></font><br>";
		$s .= "<hr>";
		if ( DEBUG_WRITE_TO_CONSOLE )
		{
			CConsole::Write( get_class($this) . '/system_error', $s );
		}
		echo $s;
		exit;
	}

	//----------------------------------------------------------------
	// PrintTree
	//----------------------------------------------------------------
	function PrintTree()
	{
		echo "<div align='left'>";
		echo "<table border='1' cellspacing='0' cellpadding='10' bgcolor='#FFFFF0'>";
		echo "<tr><td align='left'><pre>\r\n" . $this->PrintTreeX() . "</pre></td></tr></table>";
		echo "</div>";
	}

	function PrintTreeX( $indent = '' )
	{
		$s = $indent . get_class($this) . "\r\n";
		foreach ( $this->clist as $key => $val )
			$s .= $val->PrintTreeX( $indent . "\t" );
		return $s;
	}
}	

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>