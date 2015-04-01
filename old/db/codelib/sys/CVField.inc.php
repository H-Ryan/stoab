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
//=================================================================
// Address Book
// Copyright (c) phpkobo.com ( http://www.phpkobo.com/ )
// Email : admin@phpkobo.com
// ID : AB201-117
//
// This software is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; version 2 of the
// License.
//
// [Installation Guide]
// http://www.phpkobo.com/doc.php?d=install&p=AB201-117
//
//=================================================================

class CVField extends CObject
{
	var $err_msg;

  /**
   * Initialize Object
   *
   * @param object $prt
   */
	function Init( &$prt, $name, $attri = null )
	{
		parent::Init( $prt, $name, $attri );
		if ( $attri != null )
		{
			if ( $this->Get(XA_NAME_RS) == '' ) $this->Set( XA_NAME_RS, $name );
			if ( $this->Get(XA_NAME_RP) == '' ) $this->Set( XA_NAME_RP, $name );
		}
		$this->val = '';
		$this->err_msg = '';
	}
	

	function SetVal( $val )
	{
		$this->val = $val;
	}

	function GetVal()
	{
		if ( $link = $this->Get(XA_LINKED_TO) )
		{
			$p =& $this->prt->GetChild($link);
			return $p->GetVal();
		}
		else
			return $this->val;
	}

  /**
   * Event processing procdure
   *
   * @param object $msg
   * @return string
   */
	function XProc( &$msg )
	{
		switch ( $msg->Get(XM_CMD) )
		{

		//---[BEGIN] Output

		case XC_OF_DEFAULT:
			$v =& $this->OutputDefault( $msg );
			return $v;

		case XC_OF_SEARCH:
		case XC_OF_INPUT:
			$v =& $this->BuildHtmlTag( $msg );
			if ( $this->IsError() ) $this->sys->Error->GetErrStyle( $v );
			return $v;

		case XC_OF_RAW:
			$v = CStr::n2e( $this->GetVal() );
			return $v;

		case XC_OF_STATE:
			$this->sys->State->Set( $msg->Get(XM_NS) . $this->GetName(), $this->GetVal(), true );
			return nothing;

		//---[END] Output

		//---[BEGIN] Input
		
		case XC_IS_RECORD:
			if ( $this->Get(XA_NAME_RS) == nothing ) return nothing;
			$rs = $msg->Get(XM_RS);
			$this->SetVal( $rs[ $this->Get(XA_NAME_RS) ] );
			return nothing;

		case XC_IS_INPUT:
			$this->SetVal( $this->sys->GetIV( $this->GetRpName( $msg ) ) );
			return nothing;

		case XC_IS_STATE:
			$this->SetVal( $this->sys->State->Get( $msg->Get(XM_NS) . $this->GetName() ) );
			return nothing;

		case XC_IS_INIT_VALUE:
			$ax = $this->Get(XA_INIT_VALUE);
			if ( is_array( $ax ) && isset( $ax[ $msg->Get(XM_KEY) ] ) )
				$this->SetVal( $ax[ $msg->Get(XM_KEY) ] );
			return nothing;

		case XC_SET_EMPTY:
			$this->SetEmpty();
			return nothing;

		//---[END] Input

		case XC_SQL_NAME_RS:
			$name_rs = $this->Get(XA_NAME_RS);
			if ( $name_rs == nothing ) return nothing;
			if ( $name_rs == '' )
				return $this->GetName();
			else
				return $name_rs;

		case XC_CLEAR_STATE:
			$this->sys->State->Clear( $msg->Get(XM_NS) . $this->GetName() );
			return nothing;

		case XC_VALIDATE:
			if ( $this->Get(XA_SKIP_VALIDATION) != '' )
				return true;
			else
				return $this->Validate( $msg );
				
		case XC_SQL_COND:
			return $this->GetSQLCond( $msg );

		case XC_SQL_FV:
			return $this->GetSQLFieldValue( $msg );
		
		}
	}

	//----------------------------------
	// Output Function
	//----------------------------------
  /**
   * Output value in the default format
   *
   * @param object $msg
   * @return string
   */
	function &OutputDefault( &$msg )
	{
		$os =& new COutString();
		$os->AddItem( CStr::n2e( $this->GetVal() ) );
		return $os;
	}

  /**
   * Get a value
   *
   * @param object $msg
   * @return string
   */
	function GetValue( &$msg )
	{
		return $this->GetVal();
	}

  /**
   * Get request parameter name
   *
   * @param object $msg
   * @return string
   */
	function GetRpName( &$msg )
	{
		return $msg->Get(XM_NS) . $this->Get(XA_NAME_RP);
	}

	function GetCaption()
	{
		$c = $this->Get(XA_CAPTION);
		if ( is_array( $c ) )
		{
			if ( isset( $c[ $this->sys->GetLangCode() ] ) )
				$lang = $this->sys->GetLangCode();
			else
				$lang = "eng";
			$c = $c[ $lang ];
		}
		return $c;
	}

	//----------------------------------
	// HTML Format
	//----------------------------------

	function GetHtmlTagValue( &$msg )
	{
		return CStr::html( CStr::n2e( $this->GetVal() ) );
	}

  /**
   * BuildHtmlTag
   *
   * @param string $v
   * @param object $msg
   * @return string
   */
	function &BuildHtmlTag( &$msg )
	{
		$ht =& new COutHtml();
		$ht->SetTagName('input');
		$ht->SetKV('type','text');
		$ht->SetKV('name',$this->GetRpName( $msg ));
		$ht->SetKV('value', $this->GetHtmlTagValue( $msg ));

		if ( $msg->Get(XM_PAGE_TYPE) == XPT_SEARCH )
		{
			if ( $this->Get(XA_SB_SIZE) != '' )
				$ht->SetKV('size', $this->Get(XA_SB_SIZE));
		}
		else
		{
			if ( $this->Get(XA_SIZE) != '' )
				$ht->SetKV('size', $this->Get(XA_SIZE));
		}

		if ( $this->Get(XA_MAX_CHAR) != '' )
			$ht->SetKV('maxlength', $this->Get(XA_MAX_CHAR));

		if ( is_array($msg->Get(XA_IB_PARAMS)) )
		{
			$bx = $msg->Get(XA_IB_PARAMS);
			foreach( $bx as $key => $val )
				$ht->SetKV($key, $val);
		}

		return $ht;
	}

  /**
   * Format
   *
   * @param string $v
   * @param object $msg
   * @return string
   */
	function Format( $v, &$msg )
	{
		return $v;
	}

	//----------------------------------
	// Error Handling
	//----------------------------------
  /**
   * Set error message
   *
   * @param string $err_msg
   * @return string
   */
	function SetErrMsg( $err_msg, $val = null )
	{
		//--- Should we not display input value?
		$b_hide_value = ( $this->Get(XA_HIDE_VALUE) == '' ?
			false : $this->Get(XA_HIDE_VALUE) );

		//-- Get caption
		$caption = $this->GetCaption();

		//-- Get error message
		$this->err_msg = $this->sys->Error->FormatErrMsg(
			$err_msg,
			$caption,
			$val,
			$b_hide_value
		);
	}
	
  /**
   * Check if it's error
   *
   * @return true = yes, false = no
   */
	function IsError()
	{
		if ( $linked = $this->Get(XA_LINKED_TO) )
		{
			$obj = $this->prt->GetChild( $linked );
			return $obj->IsError();
		}
		else
			return ( $this->err_msg != null );
	}

  /**
   * Get error message
   *
   * @return string
   */
	function GetErrMsg()
	{
		if ( $this->IsError() )
			return $this->err_msg;
		else
			return '';
	}

  /**
   * Clear error
   *
   */
	function ClearError()
	{
		$this->err_msg = null;
	}

	//----------------------------------
	// Validate
	//----------------------------------
  /**
   * Validate value
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function Validate( &$msg )
	{
		if ( $this->IsEmpty( $msg ) ) 
			return $this->Validate_Empty( $msg );
		else
			return $this->Validate_Value( $msg );
	}

  /**
   * Check if it's empty
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function Validate_Empty( &$msg )
	{
		switch ( $msg->Get(XM_PAGE_TYPE) )
		{
		case XPT_INPUT:
			if ( $this->IsRequired( $msg ) )
			{
				$this->SetErrMsg( RSTR_ERR_EMPTY );
				return false;
			}
			return true;

		case XPT_SEARCH:
			return true;
		}
	}

  /**
   * Check if it's empty
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function IsEmpty( &$msg )
	{
		return ( $this->GetVal() == '' );
	}

	function SetEmpty()
	{
		$this->SetVal( '' );
	}

	function IsRequired( &$msg )
	{
		$v = $this->Get(XA_REQUIRED);
		if ( $v === REQ_ASK_PARENT )
			return $this->prt->IsRequired( $msg );
		else
			return $v;
	}

  /**
   * Validate the value
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function Validate_Value( &$msg )
	{
		$v = $this->GetVal();
		switch ( $msg->Get(XM_PAGE_TYPE) )
		{
		case XPT_INPUT:
			if ( !$this->Validate_MinChar( $v ) ) return false;
			if ( !$this->Validate_MaxChar( $v ) ) return false;
			return true;

		case XPT_SEARCH:
			return true;
		}
	}

  /**
   * Check the minimum length of the value
   *
   * @param string $v
   * @return true = yes, false = no
   */
	function Validate_MinChar( $v )
	{
		if ( ( $this->Get(XA_MIN_CHAR) != '' ) && ( $this->Get(XA_MIN_CHAR) > CMBStr::strlen($v) ) )
		{
			$this->SetErrMsg( RSTR_ERR_TOO_SHORT, $v );
			return false;
		}

		return true;
	}		

  /**
   * Check the maximum length of the value
   *
   * @param string $v
   * @return true = yes, false = no
   */
	function Validate_MaxChar( $v )
	{
		if ( ( $this->Get(XA_MAX_CHAR) != '' ) && ( $this->Get(XA_MAX_CHAR) < CMBStr::strlen($v) ) )
		{
			$this->SetErrMsg( RSTR_ERR_TOO_LONG, $v );
			return false;
		}

		return true;
	}		

	//----------------------------------
	// SQL Constrction
	//----------------------------------
  /**
   * Get SQL conditional clause
   *
   * @param object $msg
   * @return string
   */
	function GetSQLCond( &$msg )
	{
		//--- XA_SEARCH_OP : [ null, 's%', 's>=', 's<=', 's=' ]

		$w = $this->Get(XA_NAME_RS);
		if ( $w ==  nothing ) return nothing;

		$v = $this->GetValue( $msg );

		if ( $v == '' ) return nothing;

		if ( $this->Get(XA_SEARCH_OP) != '' )
		{
			$op = $this->Get(XA_SEARCH_OP);
			if ( $op == nothing ) return nothing;
		}
		else
			$op = '=';
		
		if ( substr($op,0,1) == 's' )
		{
			$q = "'";
			$op = substr($op,1);
			$db =& $this->sys->DB;
			$v = $db->Sanitize( $v );
		}
		else
			$q = '';

		$w = $msg->Get(XM_TABLE_NS) . '`' . $w . '`';

		if ( $op == '%' )
			 $w .= " Like " . $q . "%" . $v . "%" . $q;
		else if ( $op == '(%' )
			 $w .= " Like " . $q . "%" . $v . $q;
		else if ( $op == '%)' )
			 $w .= " Like " . $q . $v . "%" . $q;
		else if (
			( $op == '=' ) || 
			( $op == '<' ) ||
			( $op == '>' ) ||
			( $op == '<=' ) ||
			( $op == '>=' ))
			 $w .= " " . $op . " " . $q . $v . $q;
		
		return '(' . $w . ')';
	}
	
  /**
   * Get SQL update caluse
   *
   * @param object $msg
   * @param string $v
   * @return string
   */
	function GetSQLUpdate( &$msg, $v )
	{
		$w = null;
		if ( $v == null )
			$v = 'null';
		else
		{
			$db =& $this->sys->DB;
			$v = $db->Sanitize( $v );
			$v = "'" . $v . "'";
		}
		
		$s = $this->Get(XA_NAME_RS) . ' = ' . $v;
		return $s;
	}

  /**
   * Get SQL field name
   *
   * @param object $msg
   * @return string
   */
	function GetSQLField( &$msg )
	{
		return $this->Get(XA_NAME_RS);
	}

  /**
   * Get SQL value
   *
   * @param object $msg
   * @return string
   */
	function GetSQLValue( &$msg )
	{
		$v = $this->GetValue( $msg );
		
		if ( $v == null )
			$v = 'null';
		else
		{
			$db =& $this->sys->DB;
			$v = $db->Sanitize( $v );
			$v = "'" . $v . "'";
		}

		return $v;
	}

  /**
   * Get SQL field value pair
   *
   * @param object $msg
   * @return string
   */
	function GetSQLFieldValue( &$msg )
	{
		$name = $this->GetSQLField( $msg );
		if ( $name == nothing )
			return nothing;
		else
			return array( $name, $this->GetSQLValue( $msg ) );
	}
}

//----------------------------------------------------------------
// CVNumber
//----------------------------------------------------------------
class CVNumber extends CVField
{
  /**
   * Output value in the default format
   *
   * @param object $msg
   * @return string
   */
	function &OutputDefault( &$msg )
	{
		$v = $this->GetVal();
		if ( is_null( $v ) )
			$v = '';
		else
		{
			if ( $this->Get(XA_FORMAT) != '' )
				$v = sprintf( $this->Get(XA_FORMAT), $v );
			else
				$v = sprintf( '%d', $v );
		}
		
		$os =& new COutString();
		$os->AddItem( $v );
		return $os;
	}

  /**
   * Validate the mimimum boundary
   *
   * @param string $v
   * @return true = yes, false = no
   */
	function Validate_MinNum( $v, $min = null )
	{
		if ( ( !is_null( $min ) ) && ( $min > $v ) )
		{
			$this->SetErrMsg( RSTR_ERR_TOO_SMALL, $v );
			return false;
		}

		return true;
	}
	
  /**
   * Validate the maximum boundary
   *
   * @param string $v
   * @return true = yes, false = no
   */
	function Validate_MaxNum( $v, $max = null )
	{
		if ( ( !is_null( $max ) ) && ( $max < $v ) )
		{
			$this->SetErrMsg( RSTR_ERR_TOO_LARGE, $v );
			return false;
		}

		return true;
	}
}

//----------------------------------------------------------------
// CVInteger
//----------------------------------------------------------------
class CVInteger extends CVNumber
{
  /**
   * Validate the value
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function Validate_Value( &$msg )
	{
		$v = $this->GetVal();
		if ( !parent::Validate_Value( $msg ) ) return false;
		if ( !$this->Validate_Integer( $v ) ) return false;

		$min = $this->Get(XA_MIN_NUM);
		$min = ( $min == '' ? null : $min );

		$max = $this->Get(XA_MAX_NUM);
		$max = ( $max == '' ? null : $max );

		if ( !$this->Validate_MinNum( $v, $min ) ) return false;
		if ( !$this->Validate_MaxNum( $v, $max ) ) return false;
		return true;
	}

  /**
   * Validate integer
   *
   * @param string $v
   * @return true = yes, false = no
   */
	function Validate_Integer( $v )
	{
		if ( !CValidator::IsInteger( $v ) )
		{
			$this->SetErrMsg( RSTR_ERR_INVALID_FORMAT, $v );
			return false;
		}

		return true;
	}		
}

//----------------------------------------------------------------
// CVDouble
//----------------------------------------------------------------
class CVDouble extends CVNumber
{
  /**
   * Validate the value
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function Validate_Value( &$msg )
	{
		$v = $this->GetVal();
		if ( !$this->Validate_Float( $v ) ) return false;

		$min = $this->Get(XA_MIN_NUM);
		$min = ( $min == '' ? null : $min );

		$max = $this->Get(XA_MAX_NUM);
		$max = ( $max == '' ? null : $max );

		if ( !$this->Validate_MinNum( $v, $min ) ) return false;
		if ( !$this->Validate_MaxNum( $v, $max ) ) return false;
		return true;
	}
	
	function Validate_Float( $v )
	{
		if ( !CValidator::IsFloat( $v ) )
		{
			$this->SetErrMsg( RSTR_ERR_INVALID_FORMAT, $v );
			return false;
		}

		return true;
	}		
}

//----------------------------------------------------------------
// CVText
//----------------------------------------------------------------
class CVText extends CVField
{
}

//----------------------------------------------------------------
// CVAsciiText
//----------------------------------------------------------------
class CVAsciiText extends CVText
{
  /**
   * Validate the value
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function Validate_Value( &$msg )
	{
		$v = $this->GetVal();
		if ( !parent::Validate_Value( $msg ) ) return false;
		if ( !$this->Validate_Ascii( $v ) ) return false;
		return true;
	}

  /**
   * Validate ascii string
   *
   * @param string $v
   * @return true = yes, false = no
   */
	function Validate_Ascii( $v )
	{
		mb_regex_encoding( SYS_INTERNAL_ENCODING );
		if ( !mb_ereg("^([\x09\x0a\x0d\x20-\x7e]*)$", $v) )
		{
			$this->SetErrMsg( RSTR_ERR_NOT_ASCII, $v );
			return false;
		}

		return true;
	}		
}
	
//----------------------------------------------------------------
// CVDigit
//----------------------------------------------------------------
class CVDigit extends CVText
{
  /**
   * Validate the value
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function Validate_Value( &$msg )
	{
		$v = $this->GetVal();
		if ( !parent::Validate_Value( $msg ) ) return false;
		if ( !$this->Validate_Digit( $v ) ) return false;
		return true;
	}

  /**
   * Validate ascii string
   *
   * @param string $v
   * @return true = yes, false = no
   */
	function Validate_Digit( $v )
	{
		mb_regex_encoding( SYS_INTERNAL_ENCODING );
		if ( !mb_ereg("^([0-9]*)$", $v) )
		{
			$this->SetErrMsg( RSTR_ERR_NOT_DIGIT, $v );
			return false;
		}

		return true;
	}		
}

//----------------------------------------------------------------
// CVPassword
//----------------------------------------------------------------
class CVPassword extends CVField
{
  /**
   * Create HTML tag
   *
   * @param array $ax
   * @param object $msg
   * @return string
   */

	function &BuildHtmlTag( &$msg )
	{
		$obj =& parent::BuildHtmlTag( $msg );
		$obj->SetKV( 'type', 'password' );
		return $obj;
	}
}

//----------------------------------------------------------------
// CVTextArea
//----------------------------------------------------------------
class CVTextArea extends CVField
{

	function Render( $format_encoding )
	{
		$s = htmlspecialchars( $this->txt );
		$s = CMBStr::replace( "\r", "", $s );
		$s = CMBStr::replace( "\n", "<br>", $s );
		return $s;
	}

  /**
   * Output value in the default format
   *
   * @param string $msg
   * @return string
   */
	function &OutputDefault( &$msg )
	{
		$this->txt = CStr::n2e( $this->GetVal() );
		$os =& new COutString();
		$os->AddItem( $this );
		return $os;

		$ht =& new COutHtml();
		$ht->SetTagName('textarea');
		$ht->SetKV('name',$this->GetRpName( $msg ));
		$ht->SetKV('rows', $this->Get(XA_ROWS));
		$ht->SetKV('cols', $this->Get(XA_COLS));
		$ht->SetV('readonly');
		$ht->SetStyle( 'border', "1px solid #e0e0e0" );
//		$ht->SetInside( "\r\n". CStr::html( CStr::n2e( $this->GetVal() ) ) );
//		$ht->SetInside( CStr::html( CStr::n2e( $this->GetVal() ) ) );
		$inside =& new COutString();
		$inside->AddItem( $this->GetVal() );
		$ht->SetInside( $inside );
		return $ht;
	}

  /**
   * Create input box
   *
   * @param string $v
   * @param object $msg
   * @return string
   */
	function &BuildHtmlTag( &$msg )
	{
		if ( $msg->Get(XM_CMD) == XC_OF_SEARCH )
			return parent::BuildHtmlTag( $msg );

		$ht =& new COutHtml();
		$ht->SetTagName('textarea');
		$ht->SetKV('name',$this->GetRpName( $msg ));
		$ht->SetKV('rows', $this->Get(XA_ROWS));
		$ht->SetKV('cols', $this->Get(XA_COLS));
		$ht->SetInside( "\r\n". CStr::html( CStr::n2e( $this->GetVal() ) ) );

		return $ht;
	}
}

//----------------------------------------------------------------
// CVSelection
//----------------------------------------------------------------
class CVSelection extends CVField
{

  /**
   * Get text
   *
   * @param object $msg
   * @return string
   */
	function GetText( &$msg )
	{
/* -- [BEGIN] Example --
		$s =<<<EOM
1=LA
2=NY
3=FL
EOM;
   -- [END] Example -- */

		$sel_text = $this->Get(XA_SEL_TEXT);
		if ( ( $sel_text == '' ) && ( isset( $this->sel_text ) ) )
			$sel_text = $this->sel_text;

		return $sel_text;
	}

  /**
   * Output value in the default format
   *
   * @param object $msg
   * @return string
   */
	function &OutputDefault( &$msg )
	{
		$v = $this->GetVal();

		$val = '';
		$ret = '';

		if ( is_array( $v ) ) $val_array = array();

		if ( is_array( $v ) || ( $v != '' ))
		{
			$txt = $this->GetText( $msg );
			$txt = CMBStr::replace( "\r", '', $txt );
			$ax = explode( "\n", $txt );

			for ( $i = 0; $i < count( $ax ); $i++ )
			{
				$L = $ax[ $i ];

				if ( $L != "" )
				{
					CMBStr::splite( $L, $key, $val );

					if ( is_array( $v ) )
					{
						if ( in_array( $key, $v ) )
						{
							$val_array[] = $val;
						}
					}
					else
					{
						if ( $key == $v )
						{
							$ret = $val;
							break;
						}
					}
				}
			}
		}

		if ( is_array( $v ) )
		{
			$ret = implode( ", ", $val_array );
		}

		$os =& new COutString();
		$os->AddItem( CStr::n2e( $ret ) );
		return $os;
	}

  /**
   * Format text
   *
   * @param string $v
   * @param object $msg
   * @return string
   */
	function Format( $v, &$msg )
	{
	}
	
  /**
   * SelectOnTop
   *
   * @param object $msg
   * @return string
   */
	function SelectOnTop( &$msg )
	{
		if ( $msg->Get(XM_CMD) == XC_OF_SEARCH )
			return '';

		if ( $this->Get(XA_SELECT_ON_TOP) == null )
			return null;

		if ( $this->Get(XA_SELECT_ON_TOP) != '' )
			if ( $this->Get(XA_SELECT_ON_TOP) == STR_DEF_SELECT_CAPTION )
				return STR_SELECT_CAPTION;
			else
				return $this->Get(XA_SELECT_ON_TOP);

		return null;
	}

	function IsValidKeyVal( $key, $val )
	{
		return true;
	}

  /**
   * Create input box
   *
   * @param string $v
   * @param object $msg
   * @return string
   */
	function &BuildHtmlTag( &$msg )
	{
		$v = $this->GetVal();
		if ( !is_array( $v ) ) $v = CStr::n2e( $v );

		$txt = $this->GetText( $msg );
		$txt = CMBStr::replace( "\r", '', $txt );
		$ax = CMBStr::split( "\n", $txt );

		//--- [BEGIN] Options Tags
		$s = '';
		$select_caption = $this->SelectOnTop( $msg );
		if ( isset( $select_caption ) ) $s .= "<option value=''>" . $select_caption ."</option>\r\n";

		for ( $i = 0; $i < count( $ax ); $i++ )
		{
			$L = $ax[ $i ];

			if ( CMBStr::substr($L,0,2) == "//" )
			{
				// do nothing
			}
			else if ( $L != "" )
			{
				CMBStr::splite( $L, $key, $val );
				if ( $this->IsValidKeyVal( $key, $val ) )
				{
					$val = CStr::html($val) . '&nbsp;';

					if ( is_array( $v ) )
						$b = in_array( $key, $v );
					else
						$b = ( $key == $v );

					if ( $b )
						$s .= "<option value=\"" . $key . "\" selected>" . $val . "</option>\r\n";
					else
						$s .= "<option value=\"" . $key . "\">" . $val . "</option>\r\n";
				}
			}
		}
		//--- [END] Options Tags
		$ht =& new COutHtml();
		$ht->SetTagName('select');
		$ht->SetKV('name',$this->GetRpName( $msg ));
		if ( !is_array($v) ) $ht->SetKV('value', CStr::html( $v ));
		$ht->SetInside( $s );

		return $ht;
	}

	function Populate( &$msg, $table_name, $flist, $qc, $order_by, $param = '' )
	{
		$s = '';
		$db =& $this->sys->DB;
		$sql = $db->GetSQLSelect( $table_name, $flist, $qc ) . " ORDER BY " . $order_by;
		$result = $db->Query( $sql );
		while ( $rs = $db->GetRowA( $result ) )
		{
			if ( $param == '' )
				$s .= $rs[ $flist[0] ] . '=' . $rs[ $flist[1] ] . "\r\n";
			else
				$s .= $this->FormatPopulate( $rs, $param ) . "\r\n"; 
		}
		$db->FreeResult( $result );
		return $s;
	}

	function FormatPopulate( &$rs, $param )
	{
		return '';
	}
}

//----------------------------------------------------------------
// CVRadio
//----------------------------------------------------------------
class CVRadio extends CVField
{
  /**
   * BuildHtmlTag
   *
   * @param string $v
   * @param object $msg
   * @return string
   */

	function &BuildHtmlTag( &$msg )
	{
		$linked = $this->Get(XA_LINKED_TO);
		if ( $linked == '' )
		{
			$this->sys->SystemError( 'CVRadio::BuildHtmlTag', 'No Linked Field' );
		}
		$chi = $this->prt->GetChild( $linked );
		$v = CStr::n2e( $chi->GetVal() );

		$ht =& new COutHtml();
		$ht->SetTagName( 'input' );
		$ht->SetKV( 'type', 'radio' );
		$ht->SetKV( 'name', $chi->GetRPName( $msg ) );
		$id = $this->Get( XA_HTML_ID );
		if ( $id == '' ) $id = $this->GetName();
		$ht->SetKV( 'id', $id );
		$ht->SetKV( 'value', $this->Get( XA_RADIO_VALUE ) );
		if ( $this->Get( XA_RADIO_VALUE ) == $v )
			$ht->SetV( 'checked ' );
		return $ht;
	}
}

//----------------------------------------------------------------
// CVCheckBox
//----------------------------------------------------------------
class CVCheckBox extends CVSelection
{

	function Setup()
	{
		$this->checked_state = "Y";
		return nothing;
	}

	function IsChecked()
	{
		return ( $this->GetVal() == $this->checked_state );
	}

  /**
   * Output value in the default format
   *
   * @param string $msg
   * @return string
   */
	function &OutputDefault( &$msg )
	{
		$v = ( ( CStr::n2e( $this->GetVal() ) == $this->checked_state ) ? "Yes" : "No" );
		$os =& new COutString();
		$os->AddItem( $v );
		return $os;
	}

  /**
   * Get text
   *
   * @param object $msg
   * @return string
   */
	function GetText( &$msg )
	{
		$s =<<<EOM
Y=Yes
N=No
EOM;
		return $s;
	}

  /**
   * Create input box
   *
   * @param string $v
   * @param object $msg
   * @return string
   */
	function &BuildHtmlTag( &$msg )
	{
		if ( $msg->Get(XM_CMD) == XC_OF_SEARCH )
			return parent::BuildHtmlTag( $msg );

		$ht =& new COutHtml();
		$ht->SetTagName( 'input' );
		$ht->SetKV( 'type', 'checkbox' );
		$ht->SetKV( 'name', $this->GetRpName( $msg ) );
		$ht->SetKV( 'value', $this->checked_state );
		if ( $this->IsChecked() ) $ht->SetV('checked');
		return $ht;
	}

	function GetSQLValue( &$msg )
	{
		$v = ( $this->IsChecked() ) ? "Y" : "N";
		$v = "'" . $v . "'";
		return $v;
	}
}

//----------------------------------------------------------------
// CVCheckBoxNeg
//----------------------------------------------------------------
class CVCheckBoxNeg extends CVCheckBox
{
	function Setup()
	{
		$this->checked_state = "N";
		return nothing;
	}
}

//----------------------------------------------------------------
// CVLabel
//----------------------------------------------------------------
class CVLabel extends CVField
{
  /**
   * BuildHtmlTag
   *
   * @param string $v
   * @param object $msg
   * @return string
   */
	function &BuildHtmlTag( &$msg )
	{
		$linked = $this->Get(XA_LINKED_TO);
		$obj = $this->prt->GetChild( $linked );
		$id = $obj->Get( XA_HTML_ID );
		if ( $id == '' ) $id = $obj->GetName();

		$ht =& new COutHtml();
		$ht->SetTagName( 'label' );
		$ht->SetKV( 'for', $id );
		$ht->SetInside( $this->GetCaption() );

		return $ht;
	}
}

//----------------------------------------------------------------
// CVSerialSel
//----------------------------------------------------------------
class CVSerialSel extends CVSelection
{
	var $sstr;
	var $snum;
	var $enum;

  /**
   * Set up object
   *
   * @param object $msg
   * @return string
   */
	function Setup()
	{
		//$this->sstr = '-';
		$this->snum = 1;
		$this->enum = 1;
		return nothing;
	}

  /**
   * Set the first value
   *
   * @return string
   */
	function SetFirstValue()
	{
		$this->SetVal( $this->snum );
	}

  /**
   * Get text
   *
   * @param object $msg
   * @return string
   */
	function GetText( &$msg )
	{
		$s = '';
		if ( !is_null( $this->snum ) )
		{
			if ( $this->snum <= $this->enum )
				for ( $i = $this->snum; $i <= $this->enum; $i++ ) $this->AppendNum( $i, $s );
			else
				for ( $i = $this->snum; $i >= $this->enum; $i-- ) $this->AppendNum( $i, $s );
		}
		return $s;
	}

  /**
   * Append numbers
   *
   * @param integer $i
   * @param string $s
   * @return string
   */
	function AppendNum( $i, &$s )
	{
		if ( $s != '' ) $s .= "\r\n";
		if ( $i < 10 )
			$s .= $i .'=0' . $i;
		else
			$s .= ( $i . '=' . $i );
	}

  /**
   * Validate value
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function Validate_Value( &$msg )
	{
		$v = $this->GetVal();
		if ( !parent::Validate_Value( $msg ) ) return false;

		if ( !CValidator::IsInteger( $v ) )
		{
			$this->SetErrMsg( RSTR_ERR_INVALID_FORMAT, $v );
			return false;
		}

		$v = intval( $v );
		
		if ( $this->snum < $this->enum )
		{
			$min = $this->snum;
			$max = $this->enum;
		}
		else
		{
			$min = $this->enum;
			$max = $this->snum;
		}
		
		if ( !( $min <= $v ) )
		{
			$this->SetErrMsg( RSTR_ERR_TOO_SMALL, $v );
			return false;
		}

		if ( !( $max >= $v ) )
		{
			$this->SetErrMsg( RSTR_ERR_TOO_LARGE, $v );
			return false;
		}

		return true;
	}
}

//----------------------------------------------------------------
// CVDateTime
//----------------------------------------------------------------
class CVDateTime extends CVField
{
	function GetHtmlTagValue( &$msg )
	{
		$v = $this->GetVal();
		if ( is_null( $v ) || ( $v == '' ))
		{
			$vv = '';
		}
		else
		{
			$vv = strtotime( $v );
			if (( $vv === false ) || ( $vv == -1 ))
			{
				$vv = $v;
			}
			else
			{
				$vv = date( $this->Get(XA_FORMAT), $vv );
			}
		}
		return CStr::html( CStr::n2e( $vv ) );
	}

  /**
   * Output value in the default format
   *
   * @param string $msg
   * @return string
   */
	function &OutputDefault( &$msg )
	{
		$v = $this->GetVal();
		if (( $v == null ) || ( $v == '' ) || ( $v == '0000-00-00 00:00:00' ))
			$v = '';
		else
		{
			if ( $this->Get(XA_FORMAT) != '' )
				$v = date( $this->Get(XA_FORMAT), strtotime( $v ) );
		}

		$os =& new COutString();
		$os->AddItem( CStr::n2e( $v ) );
		return $os;
	}

  /**
   * Get
   *
   * @param object $msg
   * @return string
   */
	function GetValue( &$msg )
	{
		switch ( $msg->Get(XM_CMD) )
		{
		case XC_SQL_COND:
			if ( $this->GetVal() == '' )
				$v = '';
			else
				$v = date('Y-m-d H:i:s', strtotime($this->GetVal()) );
			return $v;
		}
		
		return parent::GetValue( $msg );
	}

  /**
   * Validate value
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function Validate_Value( &$msg )
	{
		$v = $this->GetVal();
		if ( !parent::Validate_Value( $msg ) ) return false;
		if ( !$this->Validate_DateTime( $v ) ) return false;
		return true;
	}

  /**
   * Validate date time
   *
   * @param string $v
   * @return true = yes, false = no
   */
	function Validate_DateTime( $v )
	{
		if ( !CValidator::IsDateTime($v) )
		{
			$this->SetErrMsg( RSTR_ERR_INVALID_FORMAT, $v );
			return false;
		}

		return true;
	}
}

//----------------------------------------------------------------
// CVRowIndex
//----------------------------------------------------------------
class CVRowIndex extends CVField
{
  /**
   * Output value in the default format
   *
   * @param string $msg
   * @return string
   */
	function &OutputDefault( &$msg )
	{
		if ( $this->GetVal() == '' ) $this->SetVal( 0 );
		$this->SetVal( $this->GetVal() + 1 );
		$v0 = $this->GetVal();
		return $v0;
	}
}

//----------------------------------------------------------------
// CVEmail
//----------------------------------------------------------------
class CVEmail extends CVText
{
  /**
   * Validate value
   *
   * @param object $msg
   * @return true = yes, false = no
   */
	function Validate_Value( &$msg )
	{
		$v = $this->GetVal();
		if ( !parent::Validate_Value( $msg ) ) return false;
		if ( $msg->Get(XM_PAGE_TYPE) == XPT_SEARCH ) return true;
		if ( !$this->Validate_Email( $v ) ) return false;
		return true;
	}

  /**
   * Validate email
   *
   * @param string $s
   * @return true = yes, false = no
   */
	function Validate_Email( $v )
	{
		if ( !CValidator::IsEmailAddress( $v ) )
		{
			$this->SetErrMsg( RSTR_ERR_INVALID_FORMAT, $v );
			return false;
		}

		return true;
	}		
}

//----------------------------------------------------------------
// CVPrimaryKey
//----------------------------------------------------------------
class CVPrimaryKey extends CVInteger
{
}

//-----------------------------------------------------------------------
// END OF FILE
//-----------------------------------------------------------------------
?>
