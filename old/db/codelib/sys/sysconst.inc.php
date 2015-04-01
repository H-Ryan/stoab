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
// SysVersion
//----------------------------------------------------------------
define( 'SYS_VERSION', "3.13" );

//----------------------------------------------------------------
// Nothing Type
//----------------------------------------------------------------
define( 'nothing', "\x00" );

//----------------------------------------------------------------
// Page Type
//----------------------------------------------------------------
define( 'XPT_INPUT', 1 );
define( 'XPT_SEARCH', 2 );

//----------------------------------------------------------------
// Command
//----------------------------------------------------------------
//-- Input Source
define( 'XC_IS_RECORD', 1101 );
define( 'XC_IS_STATE', 1102 );
define( 'XC_IS_INPUT', 1103 );
define( 'XC_IS_INIT_VALUE', 1104 );

//-- Output Format
define( 'XC_OF_RAW', 1201 );
define( 'XC_OF_DEFAULT', 1202 );
define( 'XC_OF_INPUT', 1203 );
define( 'XC_OF_SEARCH', 1204 );
define( 'XC_OF_STATE', 1205 );

//-- Signal
define( 'XC_AFTER_FROM_INPUT', 1301 );
define( 'XC_AFTER_FROM_STATE', 1302 );
define( 'XC_BEFORE_TO_STATE', 1303 );
define( 'XC_AFTER_TO_STATE', 1304 );
define( 'XC_AFTER_FROM_RECORDSET', 1305 );
define( 'XC_BEFORE_INSERT_RECORDSET', 1306 );
define( 'XC_AFTER_INSERT_RECORDSET', 1307 );
define( 'XC_BEFORE_UPDATE_RECORDSET', 1308 );
define( 'XC_AFTER_UPDATE_RECORDSET', 1309 );

//-- SQL Generation
define( 'XC_SQL_NAME_RS', 1401 );
define( 'XC_SQL_COND', 1402 );
define( 'XC_SQL_FV', 1403 );

//-- Validate FieldSet
define( 'XC_VALIDATE', 1501 );

//-- Clear State
define( 'XC_CLEAR_STATE', 1502 );

//-- Clear FieldSet
define( 'XC_SET_EMPTY', 1503 );

//----------------------------------------------------------------
// Attributes
//----------------------------------------------------------------

//-- Name used in Request Parameter
define( 'XA_NAME_RP', 2101 );

//-- Name used in Recordset
define( 'XA_NAME_RS', 2102 );

//-- Specifies fieldlist
define( 'XA_LIST', 2110 );

//-- Field class name
define( 'XA_CLASS', 2201 );

//-- Field caption
define( 'XA_CAPTION', 2202 );

//-- Field size
define( 'XA_SIZE', 2203 );

//-- Minimum input characters
define( 'XA_MIN_CHAR', 2204 );

//-- Maximum input characters
define( 'XA_MAX_CHAR', 2205 );

//-- Field format
define( 'XA_FORMAT', 2206 );

//-- Create a '--select--' option in a pulldown box
define( 'XA_SELECT_ON_TOP', 2207 );

//-- Number of columns of TextArea
define( 'XA_COLS', 2208 );

//-- Number of rows of TextArea
define( 'XA_ROWS', 2209 );

//-- Search Operator ( =, <=, >=, != )
define( 'XA_SEARCH_OP', 2210 );

//-- Size of search box
define( 'XA_SB_SIZE', 2211 );

//-- Input box parameters ( 'id', 'class', 'name', ... )
define( 'XA_IB_PARAMS', 2212 );

//-- Minimum number for integer validation
define( 'XA_MIN_NUM', 2213 );

//-- Maximum number for integer validation
define( 'XA_MAX_NUM', 2214 );

//-- Specifies if field is required
define( 'XA_REQUIRED', 2301 );
define( 'REQ_ASK_PARENT', -1000 ); //-- A value for XA_REQUIRED

//-- Skip validation
define( 'XA_SKIP_VALIDATION', 2302 );

//-- Spec. filename ( e.g. "df.fl.staff.inc.php" )
define( 'XA_SPEC_FILE', 2401);

//-- Table name
define( 'XA_TABLE_NAME', 2402);

//-- Table name for search
define( 'XA_TABLE_NAME_SEARCH', 2403);

//-- Default order-by
define( 'XA_INIT_ORDER_BY', 2404);

//-- Default # of records per page
define( 'XA_INIT_PAGE_SIZE', 2405);

//-- Name of ID in record set
define( 'XA_ID_NAME', 2406);

//-- Specifies if the pageset requires authentication
define( 'XA_AUTH', 2501);

//-- Namespace
define( 'XA_NS', 2502);

//-- Specifies if the field needs to hide the value such as "password"
define( 'XA_HIDE_VALUE', 2503);

//-- Pageset can inherit attributes from another pageset specified by XA_BASE
define( 'XA_BASE', 2504);

//-- Items in pulldown box
define( 'XA_SEL_TEXT', 2505);

//-- Specifies init values on XC_IS_INIT_VALUE
define( 'XA_INIT_VALUE', 2506);

//-- Connect radio button to selection box
define( 'XA_LINKED_TO', 2601);

//-- The value of radio button connected to selection box
define( 'XA_RADIO_VALUE', 2602);

//-- Specifies the id tag in html ( <tagname id='...' > )
define( 'XA_HTML_ID', 2603 );

//-- Specifies the default pageset to System object.
define( 'XA_DEFAULT_PAGESET', 2701 );

//-- Specifies the default command to Pageset object.
define( 'XA_DEFAULT_COMMAND', 2702 );

//-- Specifies the start page after successful login
define( 'XA_START_PAGE', 2801 );

//-- Specifies the fieldset used in frame object
define( 'XA_FRAME_FIELDSET', 2802 );

//-- Specifies the fieldset id used in frame object
define( 'XA_FRAME_FIELDSET_ID', 2803 );

//----------------------------------------------------------------
// Message Type
//----------------------------------------------------------------

//-- Command
define( 'XM_CMD', 3101 );

//-- Namespace
define( 'XM_NS', 3102 );

//-- Recordset
define( 'XM_RS', 3103 );

//-- Page type
define( 'XM_PAGE_TYPE', 3104 );

//-- Table Namespace
define( 'XM_TABLE_NS', 3105 );

//-- A new id created after inserting a record
define( 'XM_NEW_ID', 3106 );

//-- Key for XC_IS_INIT_VALUE
define( 'XM_KEY', 3107 );

//-----------------------------------------------------------------------
// END OF FILE
//-----------------------------------------------------------------------
?>