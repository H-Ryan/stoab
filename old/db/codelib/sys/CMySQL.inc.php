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


class CMySQL extends CObject
{
	var $oConn = null;
	var $b_commit = true;
	var $b_attached = false;

	//----------------------------------------------------------------
	// Setup
	//----------------------------------------------------------------
	function Setup()
	{
		$hostname = DB_HOSTNAME;
		$username = DB_USERNAME;
		$password = DB_PASSWORD;
		$database = DB_DATABASE;
		$this->Open( $hostname, $username, $password, $database );
		$set_names_cmd = 'SET NAMES utf8;';
		$this->Query( $set_names_cmd );
	}

	//----------------------------------------------------------------
	// Open
	//----------------------------------------------------------------
  /**
   * Open database
   *
   * @param string $DB_SERVER
   * @param string $DB_USERNAME
   * @param string $DB_PASSWORD
   * @param string $DB_DATABASE
   */
	function Open( $DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE )
	{
		if ( !( $this->oConn = mysql_connect( $DB_SERVER, $DB_USERNAME, $DB_PASSWORD ) ) )
		{
			$this->oConn = null;
			$this->sys->SystemError( get_class($this) . '/Open', 'Could not connect' );
		}

		mysql_select_db( $DB_DATABASE, $this->oConn );
	}

	//----------------------------------------------------------------
	// Close
	//----------------------------------------------------------------
  /**
   * Close database
   *
   */
	function Close()
	{
		if ( !$this->b_attached ) mysql_close($this->oConn);
		$this->oConn = null;
	}

	//----------------------------------------------------------------
	// AttachConn
	//----------------------------------------------------------------
  /**
   * Attach a connection
   *
   * @param handle $oConn
   */
	function AttachConn( $oConn )
	{
		$this->oConn = &$oConn;
		$this->b_attached = true;
	}
	
	//----------------------------------------------------------------
	// IsOpen
	//----------------------------------------------------------------
  /**
   * Checks if the connection is open
   *
   * @return handle $oConn
   * @return true = success, false = failure
   */
	function IsOpen()
	{
		return !is_null( $this->oConn );
	}

	//----------------------------------------------------------------
	// Sanitize
	//----------------------------------------------------------------
  /**
   * Sanitize user's input
   *
   * @return string $s
   * @return string
   */
	function Sanitize( $s )
	{
		//--------------------------------------------------------
		//--- Characters before space keys(\x20)
		//--- except TAB(\x09), LF(\x0a), CR(\x0d)
		//--- [\x00-\x08\x0b\x0c\x0e-\x1f]

		mb_regex_encoding( "UTF-8" );
		$s = mb_ereg_replace( "[\x00-\x08\x0b\x0c\x0e-\x1f]", '' , $s );

		//---
		//---
		//--------------------------------------------------------
		
		//--------------------------------------------------------
		//---
		//---

		$s = mysql_real_escape_string($s);

		//---
		//---
		//--------------------------------------------------------
	
		return $s;
	}
	
	//----------------------------------------------------------------
	// Query
	//----------------------------------------------------------------
  /**
   * Make a query
   *
   * @return string $sql
   * @return true = success, false = failure
   */
	function Query( $SQL )
	{
		if ( DEBUG_WRITE_TO_CONSOLE )
		{
			CConsole::Write( get_class($this) . "/sql", $SQL );
		}

		return mysql_query( $SQL, $this->oConn );
	}

	//----------------------------------------------------------------
	// BeginTrans
	//----------------------------------------------------------------
  /**
   * Begin transaction
   *
   * @return true = success, false = failure
   */
	function BeginTrans()
	{
		return $this->Query( "BEGIN" );
	}

	//----------------------------------------------------------------
	// EndTrans
	//----------------------------------------------------------------
  /**
   * End transaction
   *
   * @return true = success, false = failure
   */
	function EndTrans()
	{
		if ( $this->b_commit )
			return $this->CommitTrans();
		else
			return $this->RollBackTrans();
	}

	//----------------------------------------------------------------
	// SetRollBack
	//----------------------------------------------------------------
  /**
   * Set rollback
   *
   */
	function SetRollBack()
	{
		$this->b_commit = false;
	}

	//----------------------------------------------------------------
	// CommitTrans
	//----------------------------------------------------------------
  /**
   * Commite transaction
   *
   */
	function CommitTrans()
	{
		return $this->Query( "COMMIT" );
	}

	//----------------------------------------------------------------
	// RollBackTrans
	//----------------------------------------------------------------
  /**
   * Roll back transaction
   *
   * @return true = success, false = failure
   */
	function RollBackTrans()
	{
		return $this->Query( "ROLLBACK" );
	}
	
	//----------------------------------------------------------------
	// GetRow
	//----------------------------------------------------------------
  /**
   * Get a row
   *
   * @param handle $result
   * @return handle recordset
   */
	function GetRow( $result )
	{
		return mysql_fetch_row( $result );
	}
	
	//----------------------------------------------------------------
	// GetRowA
	//----------------------------------------------------------------
  /**
   * Get a associative row
   *
   * @param handle $result
   * @return handle recordset
   */
	function GetRowA( $result )
	{
		return mysql_fetch_array( $result, MYSQL_ASSOC );
	}

	//----------------------------------------------------------------
	// GetRowCount
	//----------------------------------------------------------------
  /**
   * Get the row count
   *
   * @param handle $result
   * @return integer
   */
	function GetRowCount( $result )
	{
		return mysql_num_rows( $result );
	}
	
	//----------------------------------------------------------------
	// FreeResult
	//----------------------------------------------------------------
  /**
   * Free up the result set
   *
   * @param handle $result
   */
	function FreeResult( $result )
	{
		mysql_free_result( $result );
	}
	
	//----------------------------------------------------------------
	// GetLastError
	//----------------------------------------------------------------
  /**
   * Get the last error message
   *
   * @return string
   */
	function GetLastError()
	{
		return mysql_error();
	}
	
	//----------------------------------------------------------------
	// GetInsertID
	//----------------------------------------------------------------
  /**
   * Get the inserted ID
   *
   * @param string $TableName
   * @param string $FieldName
   * @return integer
   */
	function GetInsertID( $TableName = null, $FieldName = null )
	{
		return mysql_insert_id( $this->oConn );
	}

	//----------------------------------------------------------------
	// GetRecordCount
	//----------------------------------------------------------------
  /**
   * Get the record count
   *
   * @param string $SQL
   * @return integer
   */
	function GetRecordCount( $SQL )
	{
		$ax = split( ' ORDER ', $SQL );
		$bx = split( ' FROM ',  $ax[0] );
		$sql_y = 'SELECT COUNT(*) AS c FROM ' . $bx[1];
		$result = $this->Query( $sql_y ) or $this->sys->SystemError( get_class($this) . '/GetRecordCount', 'Query() failed: ' . $this->GetLastError() );
		$rs = $this->GetRowA( $result ) or $this->sys->SystemError( get_class($this) . '/GetRecordCount', 'GetRow() failed: ' . $this->GetLastError() );
		$Total = $rs['c'];
		$this->FreeResult( $result );

		//-- Subquery method
		//	$SQLX = "SELECT COUNT(*) As count_of_records FROM ( " . $SQL . " ) AS TABLE_FOR_COUNT";
		//	$result = $this->Query( $SQLX ) or $this->sys->SystemError( get_class($this) . '/GetRecordCount', 'Query() failed: ' . $this->GetLastError() );
		//	$rs = $this->GetRow( $result ) or $this->sys->SystemError( get_class($this) . '/GetRecordCount', 'GetRow() failed: ' . $this->GetLastError() );
		//	$Total = $rs[0];
		//	$this->FreeResult( $result );
		//

		return $Total;
	}

	//----------------------------------------------------------------
	// GetPageResult
	//----------------------------------------------------------------
  /**
   * Get page result set
   *
   * @param handle $result
   * @param string $SQL
   * @param integer $PageSize
   * @param integer $TotalRecord
   * @param integer $TotalPage
   * @param integer $PageIdx
   */
	function GetPageResult(
		&$result, 
		&$SQL, 
		$PageSize, 
		&$TotalRecord, 
		&$TotalPage, 
		&$PageIdx )
	{

		$TotalRecord = $this->GetRecordCount( $SQL );

		//--- Set all variables
		if ( $PageSize == 0 )
		{
			$PageSize = $TotalRecord;
			if ( $TotalRecord == 0 )
			{
				$TotalPage = 0;
				$PageIdx = 0;
			}
			else
			{
				$TotalPage = 1;
				$PageIdx = 1;
			}
		}
		else
		{
			$TotalPage = intval( $TotalRecord / $PageSize );
			if (( $TotalRecord % $PageSize ) != 0 ) { $TotalPage = $TotalPage + 1; }
			if ( $TotalPage == 0 ) { $PageIdx = 0; }
			if ( $PageIdx > $TotalPage ) { $PageIdx = $TotalPage; }
		}

		if ( $TotalPage > 0 )
		{
			$Offset = ($PageIdx-1) * $PageSize;
			$SQLX = $SQL . " LIMIT $Offset, $PageSize;";
			$result = $this->Query( $SQLX ) or $this->sys->SystemError( get_class($this) . '/GetPageResult', 'Query() failed: ' . $this->GetLastError() );
		}
	}

	//------------------------------------------------------------
	// CombineCond
	//------------------------------------------------------------
	function CombineCond( $qx, $op = "AND" )
	{
		$qx1 = array();
		foreach( $qx as $s ) $qx1[] = "( " . $s . " )";
		return implode( " " . $op . " ", $qx1 );
	}
	
	//------------------------------------------------------------
	// GetSQLSelect
	//------------------------------------------------------------
	function GetSQLSelect( $table_name, $ls, $qx, $op = "AND" )
	{
		$s = '';
		foreach( $ls as $v )
		{
			if ( $s != '' ) $s .= ', ';
			$s .= '`' . $v . '`';
		}

		$sql = 'SELECT ' . $s . ' FROM ' . '`' . $table_name . '`';
		if ( count( $qx ) > 0 )
		 	$sql .= ' WHERE ' . $this->CombineCond( $qx, $op );

		return $sql;
	}

	//------------------------------------------------------------
	// InsertRecord_Bool
	//------------------------------------------------------------
	function InsertRecord_Bool( $table_name, $fv )
	{
		$s = '';
		foreach( $fv as $v )
		{
			if ( $s != '' ) $s .= ', ';
			$s .= '`' . $v[0] . '`';
		}

		$sql = 'INSERT INTO ' . '`' . $table_name . '`' .  
			' (' . $s . ') VALUES' .
			' (' . CStr::implode( ', ', $fv, 1 ) . ')';

		return ( $this->Query( $sql ) );;
	}

	//------------------------------------------------------------
	// InsertRecord
	//------------------------------------------------------------
	function InsertRecord( $table_name, $fv )
	{
		if ( !$this->InsertRecord_Bool( $table_name, $fv )  )
		{
			$this->sys->SystemError( get_class($this) . '/' . __METHOD__, "Insert Record (" . $table_name . ") : " . $this->GetLastError() );
		}

		return $this->GetInsertID( $table_name );
	}
	
	//------------------------------------------------------------
	// UpdateRecord
	//------------------------------------------------------------
	function UpdateRecord( $table_name, $fv, $qx )
	{
		if ( count( $qx ) > 0 )
		{
			$ax = array();
			foreach( $fv as $v ) $ax[] = '`' . $v[0] . '`=' . $v[1];

			$sql = 'UPDATE ' . '`' . $table_name . '`' . 
			 ' SET ' . implode( ', ', $ax ) .
			 ' WHERE ' . $this->CombineCond( $qx );

			if ( !$this->Query( $sql ) )
			{
				$this->sys->SystemError( get_class($this) . '/' . __METHOD__, "Update Record (" . $table_name . ") : " . $this->GetLastError() );
			}
		}
		else
		{
			$this->sys->SystemError( get_class($this) . '/' . __METHOD__, "No WHERE Clauses in UPDATE (" . $table_name . ")" );
		}
	}

	//------------------------------------------------------------
	// DeleteRecord
	//------------------------------------------------------------
	function DeleteRecord( $table_name, $qx )
	{
		if ( count( $qx ) > 0 )
		{
			$sql = 'DELETE FROM `' . $table_name . '` WHERE ' . $this->CombineCond( $qx );

			if ( !$this->Query( $sql ) )
			{
				$this->sys->SystemError( get_class($this) . '/' . __METHOD__, "Delete Record (" . $table_name . ") : " . $this->GetLastError() );
			}
		}
		else
		{
			$this->sys->SystemError( get_class($this) . '/' . __METHOD__, "No WHERE Clauses in DELETE (" . $table_name . ")" );
		}
	}

	//------------------------------------------------------------
	// SetNextAutoIncrement
	//------------------------------------------------------------
	function SetNextAutoIncrement( $table_name, $next_id )
	{
		$sql = "ALTER TABLE `{$table_name}` AUTO_INCREMENT = " . $next_id;
		if ( !$this->Query( $sql ) )
		{
			$this->sys->SystemError( get_class($this) . '/' . __METHOD__, "SetNextAutoIncrement (" . $table_name . ") : " . $this->GetLastError() );
		}
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>
