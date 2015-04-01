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


//-- User Type
define( 'RSTR_UT_CAP_G', 'Guest' );
define( 'RSTR_UT_CAP_M', 'Member' );
define( 'RSTR_UT_CAP_S', 'Staff' );

//-- Staff Type
define( 'RSTR_ADMINISTRATOR', 'Administrator' );
define( 'RSTR_GENERAL_STAFF', 'Staff' ); 

//-- Login Page
define( 'RSTR_LOG_IN', 'Log In' );
define( 'RSTR_USERNAME', 'Username' );
define( 'RSTR_PASSWORD', 'Password' );
define( 'RSTR_ENTER', 'ENTER' );

//-- Log Off Page
define( 'RSTR_LOG_OFF', 'Log Off' );

//-- Log Info
define( 'RSTR_LOG_INFO', 'Log Info' );
define( 'RSTR_LAST_MODIFIED_AT', 'Last modified at' );
define( 'RSTR_LAST_MODIFIED_BY', 'Last modified by' );

//-- Search Section
define( 'RSTR_SEARCH', 'Search' );
define( 'RSTR_SEARCH_CRITERIA', 'Search Criteria' );
define( 'RSTR_SEARCH_RESULT', 'Search Results' );

//-- Record List
define( 'RSTR_RL_NO_RECORDS_FOUND', 'No Records Found' );
define( 'RSTR_RL_SHOWING_RECORDS', 'Showing records <b>##sel_range##</b> of <b>##total_record##</b>' );

//-- Log Section
define( 'RSTR_RLOG', 'Log Info' );
define( 'RSTR_CREATE_DATE_TIME', 'Created at' );
define( 'RSTR_CREATE_USER_TYPE', 'Create User Type' );
define( 'RSTR_CREATE_USER_NAME', 'Created by' );
define( 'RSTR_EDIT_DATE_TIME', 'Last modified at' );
define( 'RSTR_EDIT_USER_TYPE', 'Update User Type' );
define( 'RSTR_EDIT_USER_NAME', 'Last modified by' );
define( 'RSTR_LAST_LOGIN_DATE_TIME', 'Last logged in at' );

//-- Status Bar
define( 'RSTR_BREADCRUMS_MARK', ' > ' );
define( 'RSTR_USER', 'User' );

//-- Buttons
define( 'RSTR_OK', 'OK' );
define( 'RSTR_CANCEL', 'Cancel' );
define( 'RSTR_EDIT', 'Edit' );
define( 'RSTR_DETAIL', 'View' );
define( 'RSTR_ADDNEW', 'Add New' );
define( 'RSTR_SAVE', 'Save' );
define( 'RSTR_DELETE', 'Delete' );
define( 'RSTR_BACK', 'Back' );

//-- SelRec
define( 'RSTR_SELREC_HEADER', 'Select All / Deselect All' );
define( 'RSTR_SELREC_CHECKBOX', 'Select / Deselect this record' );

//-- Page Tab
define( 'RSTR_PAGETAB_FIRST', 'First' );
define( 'RSTR_PAGETAB_PREV', 'Previous' );
define( 'RSTR_PAGETAB_PAGE', 'Page #PageNo#' );
define( 'RSTR_PAGETAB_NEXT', 'Next' );
define( 'RSTR_PAGETAB_LAST', 'Last' );

//-- Order By
define( 'RSTR_ORDER_BY_ASC', '1 &gt;&gt; 9, A &gt;&gt Z' );
define( 'RSTR_ORDER_BY_DESC', 'Z &gt;&gt; A, 9 &gt;&gt; 1' );

//-- Notification
define( 'RSTR_RECORD_UPDATED', '[%s] The record has been updated successfully.' );
define( 'RSTR_RECORD_SAVED', '[%s] The record has been saved successfully.' );
define( 'RSTR_RECORD_ADDED', '[%s] A record has been added successfully.' );
define( 'RSTR_RECORD_DELETED', '[%s] The selected record has been deleted successfully.' );
define( 'RSTR_RECORDS_DELETED', '[%s] The selected records have been deleted successfully.' );

//-- Error Message
define( 'RSTR_ERROR', 'Error' );
define( 'RSTR_ERR_EMPTY', '[##c##] is empty.' );
define( 'RSTR_ERR_INVALID_FORMAT', '[##c##] Invalid format ##v##' );
define( 'RSTR_ERR_TOO_SHORT', '[##c##] is too short. ##v##' );
define( 'RSTR_ERR_TOO_LONG', '[##c##] is too long. ##v##' );
define( 'RSTR_ERR_TOO_SMALL', '[##c##] is too small. ##v##' );
define( 'RSTR_ERR_TOO_LARGE', '[##c##] is too large. ##v##' );
define( 'RSTR_ERR_INCOMPLETE_INPUT', '[##c##] is imcomplete.' );
define( 'RSTR_ERR_CAN_NOT_CONFIRM', '[##c##] can not be confirmed.' );
define( 'RSTR_ERR_NOT_ASCII', '[##c##] contains non-alphanumerc characters.' );
define( 'RSTR_ERR_NOT_DIGIT', '[##c##] contains non-numeric characters.' );
define( 'RSTR_ERR_DATE_NOT_EXIST', '[##c##] : Invalid date ##v## [mm/dd/yyyy]' );
define( 'RSTR_ERR_DOUBLE_SUBMIT', "This form has already been submitted." );
define( 'RSTR_ERR_WRONG_UN_PASS', 'Wrong Username or Password' );
define( 'RSTR_ERR_USED_USERNAME', 'The username has already been registered.' );
define( 'RSTR_ERR_CAN_NOT_DELETE_ROOT', 'Can not delete the root account.' );

//-- Select
define( 'STR_SELECT_CAPTION', ' ' );
define( 'STR_SELECT_ON_TOP_FOR_SEARCH', '' );

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------

?>
