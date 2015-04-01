<?php
class cls_ps_staff extends cls_ps_base
{
	var $ps_caption = RSTR_STAFF;
	var $fs_name = 'staff';
	var $fs_edit_inp = "(fd),(edit_inp)";
	var $fs_edit_save = "(fd),(edit_save)";
	var $fs_reg_inp = "(fd),(reg_inp)";
	var $fs_reg_save = "(fd),(reg_save)";
	var $fs_del_inp = "(fd),(del_inp)";

	//------------------------------------------------------------
	// CommandProc
	//------------------------------------------------------------
	function CommandProc( &$sc )
	{
		//-- [BEGIN] Assign PageSig
		$pagesig_key = 'pagesig:' . get_class( $this );
		//-- [END] Assign PageSig

		//-- [BEGIN] Get default field set
		$def =& $this->GetFieldList( $this->fs_name );
		//-- [END] Get default field set

		//-- [BEGIN] Read command
		$cmd = $sc->Cmd();
		//-- [END] Read command

		switch( $cmd )
		{

		//------------------------------------------------------
		// Search
		//------------------------------------------------------
		case "search_init":
			//-- [BEGIN] Set PageID
			$sc->SetPageID( "search_init" );
			//-- [END] Set PageID

			//-- [BEGIN] Save criteria to state and set init values
			$def->SetNS( "sp:def:" );
			$def->SetList( "(sp)" );
			$def->FromInput();
			$def->FromInitValue( 'search' );
			$def->ToState();
			$def->SetEmpty();
			//-- [END] Save criteria to state and set init values

			//-- [BEGIN] Go
			$sc->SetNextSc( 'search' );
			//-- [END] Go

			break;

		case "search":
			//-- [BEGIN] Validate the previous PageID and set the current PageID
			if ( !$sc->CheckPrevPageID(
				array(
					'search_init',
					'search_pb',
					'search_ps',
					'search_ret'
				)
			) ) break;
			$sc->SetPageID( "search" );
			//-- [END] Validate the previous PageID and set the current PageID

			//-- [BEGIN] Set state to fieldlist and clear state
			$def->SetNS( "sp:def:" );
			$def->SetList( "(sp)" );
			$def->FromState();
			$def->ClearState();
			//-- [END] Set state to fieldlist and clear state

			//-- [BEGIN] Mark PageSig
			$this->sys->PageSig->Mark( $pagesig_key );
			//-- [END] Mark PageSig

			//-- [BEGIN] Validate criteria
			$b = $def->Validate( XPT_SEARCH );
			//-- [END] Validate criteria

			//-- [BEGIN] Output criteria input
			$def->ToZBuffer( XC_OF_SEARCH );
			//-- [END] Output criteria input

			//-- [BEGIN] Criteria validated successfully 
			if ( $b )
			{
				//-- [BEGIN] OK to show results
				$this->SetDisplay( "def:", true );
				//-- [END] OK to show results

				//-- [BEGIN] Create query
				$qc = $def->GetQueryCond();
				//-- [END] Create query

				//-- [BEGIN] Output query results in table memberat
				$def->SetNS( "rs:def:" );
				$def->SetList( "(sr)" );
				$b_clear = isset( $this->b_search_clear_page_idx );
				$def->ToZBufferTable( "_this/search_ps", $qc, $b_clear, 'AND' );
				//-- [END] Output query results in table format
			}
			//-- [END] Criteria validated successfully 

			//-- [BEGIN] Set default id
			$sc->SetDefID( $def->Get(XA_ID_NAME) );
			//-- [END] Set default id

			//-- [BEGIN] Set template page
			$this->SetPage( $sc, "search" );
			//-- [END] Set template page

			break;

		case "search_pb": //-- [Search Button]
			//-- [BEGIN] Validate the previous PageID and set the current PageID
			if ( !$sc->CheckPrevPageID(
				array(
					"search"
				)
			) ) break;
			$sc->SetPageID( "search_pb" );
			//-- [END] Validate the previous PageID and set the current PageID

			//-- [BEGIN] Save criteria to state
			$def->SetNS( "sp:def:" );
			$def->SetList( "(sp)" );
			$def->FromInput();
			$def->ToState();
			$def->SetEmpty();
			//-- [END] Save criteria to state

			$this->b_search_clear_page_idx = true;

			//-- [BEGIN] Go
			$sc->SetNextSc( 'search' );
			//-- [END] Go

			break;

		case "search_ps": //-- [Page Index]
			//-- [BEGIN] Validate the previous PageID and set the current PageID
			if ( !$sc->CheckPrevPageID(
				array(
					"search"
				)
			) ) break;
			$sc->SetPageID( "search_ps" );
			//-- [END] Validate the previous PageID and set the current PageID

			//-- [BEGIN] Save criteria to state
			$def->SetNS( "sp:def:" );
			$def->SetList( "(sp)" );
			$def->FromInput();
			$def->ToState();
			$def->SetEmpty();
			//-- [END] Save criteria to state

			//-- [BEGIN] Set PageID
			$sc->SetPageID( "search_ps" );
			//-- [END] Set PageID

			//-- [BEGIN] Go
			$sc->SetNextSc( 'search' );
			//-- [END] Go

			break;

		case "search_pxy": //-- [To non-search page]
			//-- [BEGIN] Validate the previous PageID and set the current PageID
			if ( !$sc->CheckPrevPageID(
				array(
					"search"
				)
			) ) break;
			$sc->SetPageID( "search_pxy" );
			//-- [END] Validate the previous PageID and set the current PageID

			//-- [BEGIN] Save criteria to state
			$def->SetNS( "sp:def:" );
			$def->SetList( "(sp)" );
			$def->FromInput();
			$def->ToState();
			$def->SetEmpty();
			//-- [END] Save criteria to state

			//-- [BEGIN] Go
			$sc->SetNextSc( $this->sys->GetIV('_ssc') );
			//-- [END] Go

			break;

		case "search_ret": //-- [From non-search page]
			//-- [BEGIN] Validate the previous PageID and set the current PageID
			if ( !$sc->CheckPrevPageID(
				array(
					"edit_inp",
					"edit_done",
					"reg_inp",
					"reg_done",
					"del_multi"
				)
			) ) break;
			$sc->SetPageID( "search_ret" );
			//-- [END] Validate the previous PageID and set the current PageID

			//-- [BEGIN] Clear the primary key
			$def->SetNS( "key:def:" );
			$def->SetList( "(key)" );
			$def->ClearState();
			//-- [END] Clear the primary key

			//-- [BEGIN] Go
			$sc->SetNextSc( 'search' );
			//-- [END] Go

			break;

		//------------------------------------------------------
		// Edit
		//------------------------------------------------------
		case 'edit_init':

			//-- [BEGIN] Validate prev PageID & Set new PageID
			if ( !$sc->CheckPrevPageID(
				array(
					"search_pxy"
				)
			) ) break;
			$sc->SetPageID( "edit_init" );
			//-- [END] Validate prev PageID & Set new PageID

			//-- [BEGIN] Load primary key to rs:def:
			$def->SetNS( "key:def:" );
			$def->SetList( "(key)" );
			$def->FromInput();
			$def->ToState();
			$def->SetNS( "rs:def:" );
			$def->ToState();
			//-- [END] Load primary key to rs:def:

			//-- [BEGIN] Validate key(primary key)
			if ( !$def->Validate( XPT_INPUT ) )
			{
				$sc->CriticalError( "Invalid Key" );
				break;
			}
			//-- [END] Validate key(primary key)

			//-- [BEGIN] Load data from database to state

			//-- Create query condition
			$qc = $def->GetQueryCond();

			//-- Read data from database
			$def->SetNS( "rs:def:" );
			$def->SetList( $this->fs_edit_inp . ",(rlog)" );
			if ( !$def->FromRecordSet( $qc ) )
			{
				$sc->CriticalError( "Invalid Key" );
				break;
			}
			$def->ToState();
			//-- [END] Load data from database to state

			//-- [BEGIN] Go
			$sc->SetNextSc( 'edit_inp' );
			//-- [END] Go

			break;

		case 'edit_inp':

			//-- [BEGIN] Validate prev PageID & Set new PageID
			if ( !$sc->CheckPrevPageID(
				array( "edit_init", "edit_done" )
			) ) break;
			$sc->SetPageID( "edit_inp" );
			//-- [END] Validate prev PageID & Set new PageID

			//-- [BEGIN] Set verb and caption
			$this->sys->ZBuffer->Set( 'page:caption_verb', RSTR_EDIT );
			$this->sys->ZBuffer->Set( 'page:verb', 'edit' );
			//-- [END] Set verb and caption

			//-- [BEGIN] Load data from state
			$def->SetNS( "rs:def:" );
			$def->SetList( "(key),(rlog)," . $this->fs_edit_inp );
			$def->FromState();
			//-- [END] Load data from state

			//-- [BEGIN] Output fields
			//-- Output key in default output format
			$def->SetNS( "rs:def:" );
			$def->SetList( "(key)" );
			$def->ToZBuffer( XC_OF_DEFAULT );

			//-- Output fields in input box
			$def->SetNS( "rs:def:" );
			$def->SetList( $this->fs_edit_inp );
			$def->ToZBuffer( XC_OF_INPUT );

			//-- Output log in default output format
			$def->SetNS( "rs:def:" );
			$def->SetList( "(rlog)" );
			$def->ToZBuffer( XC_OF_DEFAULT );
			//-- [END] Output fields

			//-- [BEGIN] Set Display
			$this->SetDisplay( "def:", true );
			//-- [END] Set Display

			//-- [BEGIN] Set template page
			$this->SetPage( $sc, "detail" );
			//-- [END] Set template page

			break;

		case 'edit_done':

			//-- [BEGIN] Validate prev PageID & Set new PageID
			if ( !$sc->CheckPrevPageID(
				array(
					"edit_inp"
				)
			) ) break;
			$sc->SetPageID( "edit_done" );
			//-- [END] Validate prev PageID & Set new PageID

			//-- [BEGIN] Set return page
			$sc->SetNextSc( "edit_inp" );
			//-- [END] Set return page

			//-- [BEGIN] Load the primary key
			$def->SetNS( "key:def:" );
			$def->SetList( "(key)" );
			$def->FromState();
			if ( !$def->Validate( XPT_INPUT ) )
			{
				$sc->CriticalError( "Invalid Key" );
				break;
			}
			$qc = $def->GetQueryCond();
			//-- [END] Load the primary key

			//-- [BEGIN] Load data from input to state
			$def->SetNS( 'rs:def:' );
			$def->SetList( $this->fs_edit_inp );
			$def->FromInput();
			$def->ToState();
			//-- [END] Load data from input to state

			//-- [BEGIN]*** Validate Staff Input
			if ( !$this->ValidateStaffInput( $def, true, $fs_edit_save ) ) break;
			//-- [END]*** Validate Staff Input

			//-- [BEGIN] Save data into database
			$def->SetList( $fs_edit_save );
			$id = $def->UpdateRecordSet( $qc );
			//-- [END] Save data into database

			//-- [BEGIN] Output Report Info
			$this->ReportInfo( CMBStr::replace( "%s",
				$this->ps_caption, RSTR_RECORD_UPDATED ) );
			//-- [END] Output Report Info

			//-- [BEGIN] Go
			$sc->SetNextSc( 'search_ret' );
			//-- [END] Go

			break;

		//------------------------------------------------------
		// Register
		//------------------------------------------------------
		case 'reg_init':

			//-- [BEGIN] Validate prev PageID & Set new PageID
			if ( !$sc->CheckPrevPageID(
				array(
					"search_pxy"
				)
			) ) break;
			$sc->SetPageID( "reg_init" );
			//-- [END] Validate prev PageID & Set new PageID

			//-- [BEGIN] Set PageID
			$sc->SetPageID( "reg_init" );
			//-- [END] Set PageID

			//-- [BEGIN] Mark PageSig
			$this->sys->PageSig->Mark( $pagesig_key );
			//-- [END] Mark PageSig

			//-- [BEGIN] Set init values
			$def->SetNS( "rs:def:" );
			$def->SetList( $this->fs_reg_inp );
			$def->SetEmpty();
			$def->FromInitValue( 'reg' );

			$iv = $def->GetInitValues();
			if ( $iv != null )
			{
				foreach( $iv as $key=>$val )
				{
					$obj =& $def->GetChild( $key );
					$obj->SetVal( $val );
				}
			}
			$def->ToState();
			//-- [END] Set init values

			//-- [BEGIN] Go
			$sc->SetNextSc( "reg_inp" );
			//-- [END] Go

			break;

		case 'reg_inp':

			//-- [BEGIN] Validate prev PageID & Set new PageID
			if ( !$sc->CheckPrevPageID(
				array(
					"reg_init",
					"reg_done"
				)
			) ) break;
			$sc->SetPageID( "reg_inp" );
			//-- [END] Validate prev PageID & Set new PageID

			//-- [BEGIN] Set verb and caption
			$this->sys->ZBuffer->Set( 'page:caption_verb', RSTR_ADDNEW );
			$this->sys->ZBuffer->Set( 'page:verb', 'reg' );
			//-- [END] Set verb and caption

			//-- [BEGIN] Load data from state
			$def->SetNS( "rs:def:" );
			$def->SetList( $this->fs_reg_inp );
			$def->FromState();
			//-- [END] Load data from state

			//-- [BEGIN] Output
			$def->ToZBuffer( XC_OF_INPUT );
			//-- [END] Output

			//-- [BEGIN] Set display on
			$this->SetDisplay( "def:", true );
			//-- [END] Set display on

			//-- [BEGIN] Set template page
			$this->SetPage( $sc, "detail" );
			//-- [END] Set template page

			break;

		case "reg_done":

			//-- [BEGIN] Validate prev PageID & Set new PageID
			if ( !$sc->CheckPrevPageID(
				array(
					"reg_inp"
				)
			) ) break;
			$sc->SetPageID( "reg_done" );
			//-- [END] Validate prev PageID & Set new PageID

			//-- [BEGIN] Set return page
			$sc->SetNextSc( "reg_inp" );
			//-- [END] Set return page

			//-- [BEGIN] Check PageSig
			if ( !$this->sys->PageSig->Check( $pagesig_key ) )
			{
				$sc->DoubleSubmitError();
				break;
			}
			//-- [END] Check PageSig

			//-- [BEGIN] Load data from input to state
			$def->SetNS( 'rs:def:' );
			$def->SetList( $this->fs_reg_inp );
			$def->FromInput();
			$def->ToState();
			//-- [END] Load data from input to state

			//-- [BEGIN] Input & Validate
			if ( !$def->Validate( XPT_INPUT ) ) break;
			//-- [END] Input & Validate

			//-- [BEGIN]*** Prevent Username Confilct
			if ( $this->CheckUsernameConflict( $def, -1 ) ) break;
			//-- [END]*** Prevent Username Confilct

			//-- [BEGIN]*** Copy password_new to password
			$obj =& $def->GetChild('password_new');
			$pwd = $obj->GetVal();
			$obj =& $def->GetChild('password');
			$obj->SetVal( $pwd );
			//-- [END]*** Copy password_new to password

			//-- [BEGIN] Save data into database
			$def->SetList( $this->fs_reg_save );
			$id = $def->InsertRecordSet();
			//-- [END] Save data into database

			//-- [BEGIN] Clear Value
			$def->SetEmpty();
			//-- [END] Clear Value

			//-- [BEGIN] Clear PageSig
			$this->sys->PageSig->Clear( $pagesig_key );
			//-- [END] Clear PageSig

			//-- [BEGIN] Output Report Info
			$this->ReportInfo( CMBStr::replace( "%s",
				$this->ps_caption, RSTR_RECORD_ADDED ) );
			//-- [END] Output Report Info

			//-- [BEGIN] Go
			$sc->SetNextSc( 'search_ret' );
			//-- [END] Go

			break;

		//------------------------------------------------------
		// Delete Multi Records
		//------------------------------------------------------
		case 'del_multi':

			//-- [BEGIN] Validate prev PageID & Set new PageID
			if ( !$sc->CheckPrevPageID(
				array(
					"search_pxy"
				)
			) ) break;
			$sc->SetPageID( "del_multi" );
			//-- [END] Validate prev PageID & Set new PageID

			//-- [BEGIN] Set return page
			$sc->SetNextSc( 'search_ret' );
			//-- [END] Set return page

			//-- [BEGIN] Check PageSig
			if ( !$this->sys->PageSig->Check( $pagesig_key ) )
			{
				$sc->DoubleSubmitError();
				break;
			}
			//-- [END] Check PageSig

			//-- [BEGIN] Get SelRec Array
			$selrec = $this->GetSelRecArray();
			if ( count( $selrec ) == 0 )
			{
				$sc->CriticalError( "No Checkboxes are selected!" );
				break;
			}
			//-- [END] Get SelRec Array

			//-- [BEGIN]*** Prevent removing root account
			$key = array_search( ROOT_USER_ID, $selrec );
			if ( $key !== false )
			{
				$this->ReportError( RSTR_ERR_CAN_NOT_DELETE_ROOT );
				break;
			}
			//-- [END]*** Prevent removing root account

			//-- [BEGIN] Make Cond for Selected Recs
			$qc = array();
			$db =& $this->sys->DB;
			$bx = array();
			foreach( $selrec as $v )
			{
				$bx[] = "'" . $db->Sanitize($v) . "'";
			}
			$cnt = count( $bx );
			$cond = implode( ", ", $bx );
			$obj =& $def->GetPrimaryKey();
			$qc[] = "(" . $obj->GetName() . " in ( " . $cond . ") )";
			//-- [END] Make Cond for Selected Recs

			//-- [BEGIN] Delete from database
			$def->DeleteRecordSet( $qc );
			//-- [END] Delete from database

			//-- [BEGIN] Clear PageSig
			$this->sys->PageSig->Clear( $pagesig_key );
			//-- [END] Clear PageSig

			//-- [BEGIN] Output Report Info
			if ( $cnt <= 1 )
				$msg = RSTR_RECORD_DELETED;
			else
				$msg = RSTR_RECORDS_DELETED;
			$this->ReportInfo( CMBStr::replace( "%s",
				$this->ps_caption, $msg ) );
			//-- [END] Output Report Info

			break;

		//------------------------------------------------------
		// Page Not Found
		//------------------------------------------------------
		default:
			//-- [BEGIN] Unknown command
			$sc->RaiseError( SC_ERR_PAGE_NOT_FOUND );
			//-- [END] Unknown command

			break;
		}
	}

	//------------------------------------------------------------
	// ValidateStaffInput
	//------------------------------------------------------------
	function ValidateStaffInput( &$def, $b_edit, &$fs_edit_save )
	{
		//-- [BEGIN] Password Change
		//-- If both password_new and password_conf are empty
		//-- Make them Not required
		$obj1 =& $def->GetChild('password_new');
		$obj2 =& $def->GetChild('password_conf');
		if (( $obj1->GetVal() == '' ) && ( $obj2->GetVal() == '' ))
		{
			$obj1->Set( XA_REQUIRED, false );
			$obj2->Set( XA_REQUIRED, false );
		}
		//-- [END] Password Change

		//-- [BEGIN] Validate Input
		if ( !$def->Validate( XPT_INPUT ) ) return false;
		//-- [END] Validate Input

		//-- [BEGIN] Prevent Username Confilct
		$id = -1;
		if ( $b_edit )
		{
			$obj =& $def->GetChild( $def->Get(XA_ID_NAME) );
			$id = $obj->GetVal();
		}
		if ( $this->CheckUsernameConflict( $def, $id ) )
			return false;
		//-- [END] Prevent Username Confilct

		//-- [BEGIN] Password Change II
		//-- If password_new is not empty then
		//-- copy password_new to password and
		//-- Add password to fieldlist to save
		$fs_edit_save = $this->fs_edit_save;
		$obj =& $def->GetChild('password_new');
		$pwd = $obj->GetVal();
		if ( $pwd != '' )
		{
			$obj =& $def->GetChild('password');
			$obj->SetVal( $pwd );
			$fs_edit_save .= ",+password";
		}
		//-- [END] Password Change II

		return true;
	}

	//------------------------------------------------------------
	// CheckUsernameConflict
	//------------------------------------------------------------
	function CheckUsernameConflict( &$def, $id )
	{
		$p = $def->GetChild( 'username' );
		$username = $p->GetVal();

		$db =& $this->sys->DB;
		$table_name = $def->Get(XA_TABLE_NAME);
		$flist = array( "username" );
		$qc = array( "username = '". $db->Sanitize($username) . "'" );
		if ( $id != -1 ) $qc[] = $def->Get(XA_ID_NAME) . " <> {$id}";
		$sql = $db->GetSQLSelect( $table_name, $flist, $qc );
		$result = $db->Query( $sql );
		$b = ( $rs = $db->GetRowA( $result ) );
		$db->FreeResult( $result );

		if ( $b )
		{
			$p->SetErrMsg( "-" );
			$this->ReportError( RSTR_ERR_USED_USERNAME . " [" . CStr::html($username) . "]" );
		}

		return $b;
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>