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


define( 'STR_SYSCMD_KEY', '_sc');

define( 'STR_SC_INTERNAL_SEPA', '/' );
define( 'STR_THIS', '_this' );
define( 'STR_DEFAULT', '_def' );
define( 'STR_THIS_PAGESET', '_this_ps' );
define( 'STR_THIS_COMMAND', '_this_cmd' );
define( 'STR_PAGE_ID', '_pid' );
define( 'STR_SKIP_TPL', '_!_SKIP_TPL_!_' );

define( 'SC_NONE', 0 );
define( 'SC_OK', 1 );
define( 'SC_EXIT', 2 );
define( 'SC_ERR_CRITICAL_ERROR', -1 );
define( 'SC_ERR_PAGE_NOT_FOUND', -2 );
define( 'SC_ERR_UNAUTHORIZED', -3 );
define( 'SC_ERR_CIRCULATION_SEQ', -4 );
define( 'SC_ERR_WRONG_PAGE_SEQ', -5 );
//define( 'SC_ERR_DOUBLE_SUBMIT', -6 );

//----------------------------------------------------------------
//
//  An exmple of system command.
//
//  (e.g.) "member/search"
//
//  "member" is the pageset (ps) of the system command
//  "search" is the command (cmd) of the system command
//
//----------------------------------------------------------------

//----------------------------------------------------------------
// CSysCmd
//----------------------------------------------------------------
class CSysCmd
{
	function CSysCmd( &$sys )
	{
		$this->sys =& $sys;
		$this->ps_obj = null;
		$this->status = SC_NONE;
		$this->counter = 0;
		$this->cmd = '';
		$this->ps_name = '';
		$this->SetNextSc( $sys->GetIV( STR_SYSCMD_KEY ) );
		$this->history = array();
	}

	function Cmd()
	{
		return $this->cmd;
	}

	function Ps()
	{
		return $this->ps_name;
	}

	//----------------------------------------------------------------
	// GetDefID
	//----------------------------------------------------------------
	function GetDefID()
	{
		return $this->def_id;
	}

	//----------------------------------------------------------------
	// SetDefID
	//----------------------------------------------------------------
	function SetDefID( $def_id )
	{
		$this->def_id = $def_id;
	}

	function SetNextSc( $syscmd )
	{
		if ( strpos( $syscmd, STR_SC_INTERNAL_SEPA ) !== false )
		{
			$ax = split( STR_SC_INTERNAL_SEPA, $syscmd );
			if ( count($ax) == 2 )
			{
				$this->SetNextPageSet( $ax[0] );
				$this->next_command = $ax[1];
			}
			else
			{
				if ( DEBUG_WRITE_TO_CONSOLE )
				{
					$s = "Invalid Format in SC : " . $syscmd;
					CConsole::Write( get_class($this) . '/system_error', $s );
				}
			}
		}
		else
		{
			if ( $this->ps_obj != null )
				$this->SetNextPageSet( $this->ps_obj->name );
			else
				$this->SetNextPageSet( '' );
			$this->next_command = $syscmd;
		}
	}

	function SetNextPageSet( $ps_name )
	{
		if ( $ps_name == STR_THIS )
			$this->next_ps_name = $this->sys->State->Get( STR_THIS_PAGESET );
		else if (( $ps_name == '' ) || ( $ps_name == STR_DEFAULT ))
			$this->next_ps_name = $this->sys->Get( XA_DEFAULT_PAGESET );
		else
			$this->next_ps_name = $ps_name;
	}

	function GetCurrScRec()
	{
		$cnt = count( $this->history );
		if ( $cnt == 0 )
			return null;
		else
			return $this->history[ $cnt-1 ];
	}
	
	function FindScInHistory( $ps_name, $cmd )
	{
		foreach( $this->history as $v )
		{
			if (( $v['ps_name'] == $ps_name ) && ( $v['cmd'] == $cmd ))
				return true;
		}
		return false;
	}

	function CheckCounter()
	{
		$this->counter++;
		if ( $this->counter > 100 )
		{
			$this->sys->SystemError( get_class($this) . '/CheckCounter', "Looping too many times." );
		}
	}
	
	function GetNextPageSet( &$ps_name )
	{
		$this->CheckCounter();

		//--- If status is already set, then exit the pageset
		if ( $this->status != SC_NONE ) return false;

		//--- Set the next pageset name
		$ps_name = $this->next_ps_name;

		return true;
	}

	function GetNextCommand( &$ps_obj )
	{
		$this->CheckCounter();

		//--- If status is already set, then exit the pageset
		if ( $this->status != SC_NONE )
			return false;

		//--- If pageset changes, then exits the current pageset
		$sc_rec = $this->GetCurrScRec();

		if (( $sc_rec != null ) && ( $ps_obj->name != $this->next_ps_name ))
			return false;

		//--- Find the next command
		$this->ps_obj =& $ps_obj; 
		$this->ps_name = $ps_obj->name;

		$cmd = $this->next_command;
		if (( $cmd == '' ) || ( $cmd == STR_DEFAULT ))
			$this->cmd = $ps_obj->Get(XA_DEFAULT_COMMAND);
		else
			$this->cmd = $cmd;

		//--- Find same Sc in history
		if ( $this->FindScInHistory( $this->ps_name, $this->cmd ) )
		{
			$this->RaiseError( SC_ERR_CIRCULATION_SEQ );
			return false;
		}

		//--- Set it to history
		$this->history[] = array(
			'ps_name'=>$this->ps_name,
			'ps_obj'=>&$this->ps_obj,
			'cmd'=>$this->cmd );

		return true;
	}

	//------------------------------------------------------------
	// OutputPage
	//------------------------------------------------------------
	function SetPage( $path_template )
	{
		$sc_rec = $this->GetCurrScRec();
		if ( $sc_rec == null )
			$this->ps_obj = null;
		else
			$this->ps_obj =& $sc_rec['ps_obj'];
		$this->path_template = $path_template;
		$this->status = SC_OK;
	}

	//------------------------------------------------------------
	// SetPageID
	//------------------------------------------------------------
	function SetPageID( $page_id )
	{
		$this->sys->State->Set( STR_PAGE_ID, $page_id );
	}

	//------------------------------------------------------------
	// GetPrevPageID
	//------------------------------------------------------------
	function GetPrevPageID()
	{
		return $this->sys->State->Get( STR_PAGE_ID );
	}

	//------------------------------------------------------------
	// CheckPrevPageID
	//------------------------------------------------------------
	function CheckPrevPageID( $prev_page_id_array )
	{
		if ( !in_array( $this->GetPrevPageID(), $prev_page_id_array ) )
		{
			$this->RaiseError( SC_ERR_WRONG_PAGE_SEQ );
			return false;
		}
		else
			return true;
	}

	//------------------------------------------------------------
	// OutputPage
	//------------------------------------------------------------
	function OutputPage( $path_template )
	{
		$this->sys->SysInfo->Commit();

		//-- [BEGIN] Character-Encoding
		$ienc = SYS_INTERNAL_ENCODING;
		$oenc = SYS_OUTPUT_ENCODING;
		$b_change_char_encoding = ( $ienc != $oenc );
		//-- [END] Character-Encoding

		//-- [BEGIN] Mockup
		$b_print_to_html = SAVE_AS_HTML_PAGE;
		//-- [END] Mockup

		if ( $b_change_char_encoding || $b_print_to_html )
		{
			ob_start();
		}

		global $hm;
		$hm =& $this->sys->HtmlMacro;

		if ( isset( $this->def_id ) ) $hm->SetDefID( $this->GetDefID() );

		$sys =& $this->sys;

		$b_skip_template = ( $path_template == STR_SKIP_TPL );

		if ( !$b_skip_template )
		{
			if ( !file_exists( $path_template ) )
			{
				$this->sys->SystemError( get_class($this) . '/Output', "Tempalte ( {$path_template} ) does not exist." );
			}
		}

		if ( $this->ps_obj == null )
		{
			$this->sys->CurrentPageSet = null;
			$this->sys->State->Set( STR_THIS_PAGESET, 'null' );
		}
		else
		{
			$this->sys->CurrentPageSet =& $this->ps_obj;
			$this->sys->State->Set( STR_THIS_PAGESET, $this->ps_obj->name );
		}

		if ( !$b_skip_template )
		{
			//-- Output the template
			require( $path_template );
		}

		if ( $b_change_char_encoding || $b_print_to_html )
		{
			$txt = ob_get_contents();
			ob_end_clean(); 
			if ( $b_change_char_encoding )
				echo mb_convert_encoding( $txt, $oenc, $ienc );
			else
				echo $txt;

			if ( $b_print_to_html )
			{
				if (( $this->Ps() != '' ) || ( $this->Cmd() != '' ))
					CToHtml::Save( $this->Ps(), $this->Cmd(), $txt );
			}
		}
	}

	function OutputCriticalErrorPage( $msg )
	{
		$this->sys->Error->ShowError( "Critical Error : " . $msg );
	}

	function RaiseError( $status )
	{
		$this->status = $status;
	}

	function CriticalError( $err_msg )
	{
		$this->critical_err_msg = $err_msg;
		$this->RaiseError( SC_ERR_CRITICAL_ERROR );
	}
	
	function DoubleSubmitError()
	{
		$this->sys->Error->ShowError( RSTR_ERR_DOUBLE_SUBMIT );
	}

	function GetNextSc()
	{
		$s = ( isset( $this->next_ps_name ) ? $this->next_ps_name : "?" );
		$s .= STR_SC_INTERNAL_SEPA;
		$s .= ( isset( $this->next_command ) ? $this->next_command : "?" );
		return $s;
	}

	function ProcessPage()
	{
		switch( $this->status )
		{

		//-- Exit
		case SC_EXIT:
			break;

		//-- Normal Page Output
		case SC_OK:
			$this->OutputPage( $this->path_template );
			break;

		//-- General Critical Error
		case SC_ERR_CRITICAL_ERROR:
			$this->OutputCriticalErrorPage( $this->critical_err_msg );
			break;

		//-- Could occur by Forceful Browsing
		case SC_ERR_PAGE_NOT_FOUND:
			$msg = "Page Not Found ( " . $this->GetNextSc() . " )";
			$this->OutputCriticalErrorPage( $msg );
			break;

		//-- Could occur by Forceful Browsing
		case SC_ERR_WRONG_PAGE_SEQ:
			$msg = "Wrong Page Sequence";
			$this->OutputCriticalErrorPage( $msg );
			break;

		//-- Could occur by Forceful Browsing
		case SC_ERR_UNAUTHORIZED:
			$msg = "Unauthorized Access";
			$this->OutputCriticalErrorPage( $msg );
			break;

		//-- Programming Error
		case SC_ERR_CIRCULATION_SEQ:
			$s = "Page Circulation Sequence : ";
			$s .= "PageSet = " . $this->ps_name . ", ";
			$s .= "Command = " . $this->cmd . "";
			$this->sys->SystemError( get_class($this) . '/ProcessPage', $s );
			break;

		//-- Programming Error
		default:
			$s = "Unknown Error : " . $this->status;
			$this->sys->SystemError( get_class($this) . '/ProcessPage', $s );
			break;
		}

		//-- Logging Pages
		if ( DEBUG_WRITE_TO_CONSOLE )
		{
			CConsole::Write( get_class($this) . "/log", $this->PrintHistory() );
		}
	}

	function PrintHistory()
	{
		$s = '';
		$s .= "<table border='1' cellspacing='0' cellpadding='4'>";
		$s .= "<tr>";
		$s .= "<td style='color:white;background-color:#000080;'>&nbsp;</td>";
		$s .= "<td style='color:white;background-color:#000080;'>pageset</td>";
		$s .= "<td style='color:white;background-color:#000080;'>command</td>";
		$s .= "</tr>";
		$idx = 1;
		foreach( $this->history as $v )
		{
			$s .= "<tr>";
			$s .= "<td style='color:white;background-color:#000080;'>[" . $idx . "]</td>";
			$s .= "<td>" . $v['ps_name'] . "</td>";
			$s .= "<td>" . $v['cmd'] . "</td>";
			$s .= "</tr>";
			$idx++;
		}
		$s .= "</table>";

		return $s;
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>