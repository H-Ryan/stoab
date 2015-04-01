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


//define( 'TBL_GENLOG', 'tbl_genlog' );
//define( 'SYS_KEEP_LOG_PERIOD', 30 );

//----------------------------------------------------------------
// CGenLog
//----------------------------------------------------------------
class CGenLog
{
	function dq( &$db, $s )
	{
		return "'" . $db->Sanitize( $s ) . "'";
	}

	function Send( &$sys, $Sender, $Subject, $Body, $Format = 'T' )
	{
		self::DeleteExpired( $sys );

		$LogTime = date("Y-m-d H:i:s");
		
		$db =& $sys->DB;
		$fv = array(
			array( 'time_create', self::dq( $db, $LogTime ) ),
			array( 'ip_address', self::dq( $db, $_SERVER['REMOTE_ADDR'] ) ),
			array( 'sender', self::dq( $db, $Sender ) ),
			array( 'subject', self::dq( $db, $Subject ) ),
			array( 'body', self::dq( $db, $Body ) ),
			array( 'format', self::dq( $db, $Format ) )
		);
		$db->InsertRecord( TBL_GENLOG, $fv );
	}

	function DeleteExpired( &$sys )
	{
		$period = SYS_KEEP_LOG_PERIOD;
		$t = mktime( 0, 0, 0, date("m"), date("d")-$period, date("Y") );
		$d = date( "Y-m-d", $t );
		$qx = array( "time_create < '" . $d . "'" );
		$db =& $sys->DB;
		$db->DeleteRecord( TBL_GENLOG, $qx );
	}

	function DumpRecord( &$sys, $sql )
	{
		$db =& $sys->DB;

		$data = array();
		$data[] = "[SQL]:" . $sql;

		$result = $db->Query( $sql );
		if ( !$result )
		{
			$data[] = '[ERROR]:Invalid Query:' . mysql_error();
		}
		else if ( is_resource( $result ) )
		{
			$num_fields = mysql_num_fields( $result );
			$fields_name = array();
			$index = 0;
			while ( $index < $num_fields )
			{
				$obj =& mysql_fetch_field( $result, $index );
				$fields_name[ $index ] = $obj->name;
				$index++;
			}

			if ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) )
			{
				$data[] = "[BEGIN]:Record Dump";
				foreach( $fields_name as $key => $value )
				{
					$v = $row[ $value ];

					//-- [BEGIN] Null, Empty String, or Zero?
					if ( is_null( $v ) ) //-- NULL Test
					{
						$v = '(NULL)';
					}
					else if ( $v == '' ) //-- Empty String Test
					{
						$v = '=';
					}
					else
					{
						$v = '=' . $v;
					}
					//-- [END] Null, Empty String, or Zero?

					$data[] = "[FIELD]:" . $value . $v;
				}
				$data[] = "[END]:Record Dump";
			}
			else
				$data[] = "[ERROR]:No Records Found";
			
			$db->FreeResult( $result );
		}
		else if ( $result )
		{
			$data[] = "[AFFECTED_ROWS]:" . mysql_affected_rows( $db->oConn );
			$db->FreeResult( $result );
		}

		return implode( "\r\n", $data );
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>