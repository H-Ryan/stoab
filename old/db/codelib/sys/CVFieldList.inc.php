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
// CVFieldList
//----------------------------------------------------------------
class CVFieldList extends CVFieldSet
{
	//------------------------------------------------------------
	// CreateChildren()
	//------------------------------------------------------------
	function CreateChildren()
	{
		$this->SetSpec( $spec );
		$this->ConstructObjects( $spec );
	}

	//------------------------------------------------------------
	// GetPrimaryKey
	//------------------------------------------------------------
	function &GetPrimaryKey()
	{
		foreach( $this->clist as $name => $dummy )
		{
			if ( $this->clist[$name]->Get(XA_CLASS) == 'CVPrimaryKey' )
				return $this->clist[$name];
		}
		$p = null;
		return $p;
	}

	//------------------------------------------------------------
	// SetSpec
	//------------------------------------------------------------
	function SetSpec( &$spec )
	{
		$spec = array();
	}

	//------------------------------------------------------------
	// SetNS
	//------------------------------------------------------------
	function SetNS( $ns )
	{
		$keep = $this->Get( XA_NS );
		$this->Set( XA_NS, $ns );
		return $keep;
	}

	//------------------------------------------------------------
	// SetList
	//------------------------------------------------------------
	function SetList( $ls_list )
	{
		if ( is_array( $ls_list ) )
			$xa = $ls_list;
		else
			$xa = split( ",", $ls_list );

		$ls = array();
		foreach ( $xa as $item )
		{
			$op = "+";
			$type = "";
			$s = trim( $item );
			$ch = substr( $s, 0, 1 );
			if ( $ch == "+" )
			{
				$op = "+";
				$s = substr( $s, 1, strlen($s)-1 );
				$ch = substr( $s, 0, 1 );
			}
			else if ( $ch == "-" )
			{
				$op = "-";
				$s = substr( $s, 1, strlen($s)-1 );
				$ch = substr( $s, 0, 1 );
			}

			if ( $ch == "(" )
			{
				foreach( $this->clist as $name => $dummy )
				{
					$xa_list = $this->clist[$name]->Get(XA_LIST);
					if ( strpos( $xa_list, $s ) !== false )
					{
						if ( $op == "+" )
						{
							$ls[] = $name;
						}
						else
						{
							$key = array_search( $name, $ls );
							if ( $key !== false ) unset( $ls[$key] );
						}
					}
				}
			}
			else
			{
				if ( $op == "+" )
				{
					$ls[] = $s;
				}
				else
				{
					$key = array_search( $s, $ls );
					if ( $key !== false ) unset( $ls[$key] );
				}
			}
		}

		$this->field_list = $ls;
	}

	//------------------------------------------------------------
	// RemoveList
	//------------------------------------------------------------
	function RemoveList( $ls_list )
	{
		$xa = split( ",", $ls_list );
		foreach ( $xa as $name => $item ) $xa[$name] = trim($item);
		
		$ls = array();
		foreach( $this->clist as $name => $dummy )
		{
			$s = $this->clist[$name]->Get(XA_LIST);
			foreach ( $xa as $item )
			{
				if ( strpos( $s, "(" . $item . ")" ) !== false )
				{
					$ls[] = $name;
					break;
				}
			}
		}
		
		$ls2 = array();
		foreach( $this->field_list as $val )
		{
			if ( !in_array( $val, $ls ) )
				$ls2[] = $val;
		}
		$this->field_list = $ls2;
	}

	//------------------------------------------------------------
	// SetAttri
	//------------------------------------------------------------
	function SetAttri( $key, $val )
	{
		foreach( $this->field_list as $name )
			 $this->clist[$name]->Set( $key, $val );
	}

	//------------------------------------------------------------
	// SetZBuff
	//------------------------------------------------------------
	function SetZBuff( $ns, $ax )
	{
		foreach ( $ax as $key => $val )
		{
			$this->sys->ZBuffer->Set( $ns . $key, $val );
		}
	}

	//------------------------------------------------------------
	// SetEmpty
	//------------------------------------------------------------
	function SetEmpty()
	{
		$this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_SET_EMPTY
			)
		);
	}

	//------------------------------------------------------------
	// FromInitValue
	//------------------------------------------------------------
	function FromInitValue( $key )
	{
		$this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_IS_INIT_VALUE,
				XM_KEY=>$key
			)
		);
	}

	//------------------------------------------------------------
	// FromInput
	//------------------------------------------------------------
	function FromInput()
	{
		$this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_IS_INPUT,
				XM_NS=>$this->Get(XA_NS)
			)
		);

		$this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_AFTER_FROM_INPUT,
				XM_NS=>$this->Get(XA_NS)
			)
		);
	}

	//------------------------------------------------------------
	// FromState
	//------------------------------------------------------------
	function FromState()
	{
		$this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_IS_STATE,
				XM_NS=>$this->Get(XA_NS)
			)
		);

		$this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_AFTER_FROM_STATE,
				XM_NS=>$this->Get(XA_NS)
			)
		);
	}

	//------------------------------------------------------------
	// Validate
	//------------------------------------------------------------
	function Validate( $vtype )
	{
		$err_fields = $this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_VALIDATE,
				XM_NS=>$this->Get(XA_NS),
				XM_PAGE_TYPE=>$vtype
			)
		);

		$b = ( count($err_fields) == 0 );
		if ( !$b )
		{
			$this->sys->SysInfo->SetErrMsg( $this->GetErrList( $err_fields ) );
		}

		return $b;
	}

	//------------------------------------------------------------
	// GetErrorList
	//------------------------------------------------------------
	function GetErrorList()
	{
		if ( is_array( $this->err_fields ) )
			return $this->GetErrList( $this->err_fields );
		else
			return $this->err_fields;
	}

	//------------------------------------------------------------
	// GetQueryCond
	//------------------------------------------------------------
	function GetQueryCond()
	{
		return $this->SendMsg( $this->field_list,
			array( XM_CMD=>XC_SQL_COND, XM_TABLE_NS=>'' )
		);
	}
	
	//------------------------------------------------------------
	// ToAuthSes
	//------------------------------------------------------------
	function ToAuthSes()
	{
		$this->sys->AuthSession->SetAV( 
			$this->SendMsg( $this->field_list,
				array(
					XM_CMD=>XC_OF_RAW,
					XM_NS=>$this->Get(XA_NS)
				)
			)
		);
	}
	
	//------------------------------------------------------------
	// ToZBuffer
	//------------------------------------------------------------
	function ToZBuffer( $of )
	{
		//$of = XC_OF_DEFAULT / XC_OF_INPUT / XC_OF_SEARCH

		switch ( $of )
		{
		case XC_OF_INPUT: $pt = XPT_INPUT; break;
		case XC_OF_SEARCH: $pt = XPT_SEARCH; break;
		default: $pt = 0;
		}

		$this->SetZBuff( $this->Get(XA_NS), 
			$this->SendMsg( $this->field_list,
				array(
					XM_CMD=>$of,
					XM_NS=>$this->Get(XA_NS),
					XM_PAGE_TYPE=>$pt
				)
			)
		);
	}

	//------------------------------------------------------------
	// ClearState
	//------------------------------------------------------------
	function ClearState()
	{
		$this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_CLEAR_STATE,
				XM_NS=>$this->Get(XA_NS)
			)
		);
	}

	//------------------------------------------------------------
	// ToState
	//------------------------------------------------------------
	function ToState()
	{
		$this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_BEFORE_TO_STATE,
				XM_NS=>$this->Get(XA_NS)
			)
		);

		$this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_OF_STATE,
				XM_NS=>$this->Get(XA_NS)
			)
		);

		$this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_AFTER_TO_STATE,
				XM_NS=>$this->Get(XA_NS)
			)
		);
	}

	//------------------------------------------------------------
	// CheckFieldCount
	//------------------------------------------------------------
	function CheckFieldCount()
	{
		if ( count( $this->field_list ) == 0 )
		{
			$this->sys->SystemError( get_class($this) . '/CheckFieldCount',
				"There are no fields in Field List ( " . $this->name . " )" );
		}
	}
	
	//------------------------------------------------------------
	// FromRecordSet
	//------------------------------------------------------------
	function FromRecordSet( $qc, $b_show_error = true )
	{
		$this->CheckFieldCount();
		
		$db =& $this->sys->DB;
		$table_name = $this->Get(XA_TABLE_NAME);
		$sel_list = $this->SendMsg( $this->field_list, array( XM_CMD=>XC_SQL_NAME_RS ) );
		$sql = $db->GetSQLSelect( $table_name, $sel_list, $qc );
		$result = $db->Query( $sql );

		if ( $b = ( $rs = $db->GetRowA( $result ) ) )
			$this->SendMsg( $this->field_list, array( XM_CMD=>XC_IS_RECORD, XM_RS=>$rs ) );
		$db->FreeResult( $result );
		if ( !$b )
		{
			if ( $b_show_error )
				$this->sys->SysInfo->SetErrMsg( "No Records Found" );
		}
		else
		{
			$this->SendMsg( $this->field_list, array( XM_CMD=>XC_AFTER_FROM_RECORDSET ) );
		}
		
		return $b;
	}

	//------------------------------------------------------------
	// UpdateRecordSet
	//------------------------------------------------------------
	function UpdateRecordSet( $qc )
	{
		$this->CheckFieldCount();

		$this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_BEFORE_UPDATE_RECORDSET,
				XM_NS=>$this->Get(XA_NS)
			)
		);

		$db =& $this->sys->DB;
		$fv = $this->SendMsg( $this->field_list, array( XM_CMD=>XC_SQL_FV ) );
		$db->UpdateRecord( $this->Get(XA_TABLE_NAME), $fv, $qc );

		$this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_AFTER_UPDATE_RECORDSET,
				XM_NS=>$this->Get(XA_NS)
			)
		);
	}

	//------------------------------------------------------------
	// InsertRecordSet
	//------------------------------------------------------------
	function InsertRecordSet()
	{
		$this->CheckFieldCount();

		$this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_BEFORE_INSERT_RECORDSET,
				XM_NS=>$this->Get(XA_NS)
			)
		);

		$db =& $this->sys->DB;
		$fv = $this->SendMsg( $this->field_list, array( XM_CMD=>XC_SQL_FV ) );
		$new_id = $db->InsertRecord( $this->Get(XA_TABLE_NAME), $fv );
		$this->clist[$this->Get(XA_ID_NAME)]->val = $new_id;

		$this->SendMsg( $this->field_list,
			array(
				XM_CMD=>XC_AFTER_INSERT_RECORDSET,
				XM_NS=>$this->Get(XA_NS),
				XM_NEW_ID=>$new_id
			)
		);

		return $new_id;
	}

	//------------------------------------------------------------
	// DeleteRecordSet
	//------------------------------------------------------------
	function DeleteRecordSet( $qc )
	{
		$db =& $this->sys->DB;
		$db->DeleteRecord( $this->Get(XA_TABLE_NAME), $qc );
	}

	//------------------------------------------------------------
	// ReportInfo
	//------------------------------------------------------------
	function ReportInfo( $s )
	{
		$this->sys->SysInfo->SetInfoMsg( $s );
	}

	//------------------------------------------------------------
	// ToZBufferTable
	//------------------------------------------------------------
	function ToZBufferTable( $sc, $qc, $b_clear, $op = 'AND' )
	{
		$this->CheckFieldCount();

		$ns = $this->Get(XA_NS);
		$ls = $this->field_list;

		$db =& $this->sys->DB;
		$table_name = $this->Get(XA_TABLE_NAME_SEARCH);
		if ( $table_name == '' ) $table_name = $this->Get(XA_TABLE_NAME);
		$flist = $this->SendMsg( $this->field_list, array( XM_CMD=>XC_SQL_NAME_RS ) );
		$sql = $db->GetSQLSelect( $table_name, $flist, $qc, $op );
		$this->sys->ZBuffer->SetCallBack( $ns . 'begin_table', $this );

		//--- [BEGIN] List View
		$this->rl =& new CRecordList();
		$rl =& $this->rl;
		$rl->Init( $this->sys );
		$rl->SetNameSpace( $ns );
		$rl->SetFieldList( $ls );
		$rl->SetSc( $sc );
		$rl->SetPageNo( 1, $b_clear );
		$rl->SetPageSize( $this->Get(XA_INIT_PAGE_SIZE) );
		$rl->SetOrderBy( $this->Get(XA_INIT_ORDER_BY) );
		$rl->Setup( $sql, $this->Get("record_list_msg") );
		//--- [END] List View
	}

	function begin_table()
	{
		if ( !($rs = $this->rl->FetchNextRecord()) ) return false;
		$this->SendMsg( $this->rl->ls, array( XM_CMD=>XC_IS_RECORD, XM_RS=>$rs ) );
		$this->SendMsg( $this->rl->ls, array( XM_CMD=>XC_AFTER_FROM_RECORDSET ) );
		$this->SetZBuff( $this->Get(XA_NS), 
			$this->SendMsg( $this->rl->ls,
				array(
					XM_CMD=>XC_OF_DEFAULT,
					XM_NS=>$this->Get(XA_NS)
				)
			)
		);
		return true;
	}

	//------------------------------------------------------------
	// GetInitValues
	//------------------------------------------------------------
	function GetInitValues()
	{
		$ret = null;
		$key = INIT_FORM_ARRAY_PREFIX . $this->name;
		if ( isset( $GLOBALS[ $key ] ) )
			$ret = $GLOBALS[ $key ];

		return $ret;
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>