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


$spec = array(

'staff_id'=>array(
XA_CLASS=>'CVPrimaryKey',
XA_CAPTION=>RSTR_STAFF_ID,
XA_SIZE=>9,
XA_REQUIRED=>false,
XA_MAX_CHAR=>9,
XA_SB_SIZE=>9,
XA_LIST=>'(ses)(sp)(sr)(key)'
),

'active'=>array(
XA_CLASS=>'cls_active',
XA_CAPTION=>RSTR_ACTIVE,
XA_INIT_VALUE=>array( 'reg'=>'Y', 'search'=>'Y' ),
XA_REQUIRED=>true,
XA_SELECT_ON_TOP=>STR_SELECT_CAPTION,
XA_SEARCH_OP=>'s=',
XA_LIST=>'(auth)(sp)(sr)(fd)'
),

'active_Y' => array(
XA_CLASS=>'CVRadio',
XA_NAME_RS=>nothing,
XA_REQUIRED=>false,
XA_LINKED_TO=>'active',
XA_RADIO_VALUE=>'Y',
XA_LIST=>'(fd)'
),

'active_N' => array(
XA_CLASS=>'CVRadio',
XA_NAME_RS=>nothing,
XA_REQUIRED=>false,
XA_LINKED_TO=>'active',
XA_RADIO_VALUE=>'N',
XA_LIST=>'(fd)'
),

'group_id'=>array(
XA_CLASS=>'cls_group_id',
XA_CAPTION=>RSTR_STAFF_TYPE,
XA_REQUIRED=>true,
XA_SELECT_ON_TOP=>STR_SELECT_CAPTION,
XA_SEARCH_OP=>'s=',
XA_LIST=>'(ses)(sp)(sr)(fd)'
),

'username'=>array(
XA_CLASS=>'CVAsciiText',
XA_CAPTION=>RSTR_USERNAME,
XA_SIZE=>15,
XA_REQUIRED=>true,
XA_MIN_CHAR=>0,
XA_MAX_CHAR=>15,
XA_SEARCH_OP=>'s%',
XA_LIST=>'(ses)(sp)(sr)(fd)'
),

'password'=>array(
XA_CLASS=>'cls_password_encrypt',
XA_CAPTION=>RSTR_PASSWORD,
XA_SIZE=>15,
XA_REQUIRED=>true,
XA_MIN_CHAR=>0,
XA_MAX_CHAR=>15,
XA_SEARCH_OP=>'s%',
XA_LIST=>'(ses)(reg_save)'
),

'rlog_create_date_time'=>array(
XA_CLASS=>'cls_rlog_date_time',
XA_CAPTION=>RSTR_CREATE_DATE_TIME,
XA_FORMAT=>'Y-m-d H:i:s',
XA_LIST=>'(rlog)(reg_save)'
),

'rlog_create_user_type'=>array(
XA_CLASS=>'cls_rlog_user_type',
XA_CAPTION=>RSTR_CREATE_USER_TYPE,
XA_LIST=>'(rlog)(reg_save)'
),

'rlog_create_user_id'=>array(
XA_CLASS=>'cls_rlog_user_id',
XA_CAPTION=>RSTR_CREATE_USER_NAME,
XA_LIST=>'(rlog)(reg_save)'
),

'rlog_create_user_name'=>array(
XA_CLASS=>'cls_rlog_user_name',
XA_CAPTION=>RSTR_CREATE_USER_NAME,
XA_LIST=>'(rlog)(reg_save)'
),

'rlog_edit_date_time'=>array(
XA_CLASS=>'cls_rlog_date_time',
XA_CAPTION=>RSTR_EDIT_DATE_TIME,
XA_FORMAT=>'Y-m-d H:i:s',
XA_LIST=>'(rlog)(edit_save)'
),

'rlog_edit_user_type'=>array(
XA_CLASS=>'cls_rlog_user_type',
XA_CAPTION=>RSTR_EDIT_USER_TYPE,
XA_LIST=>'(rlog)(edit_save)'
),

'rlog_edit_user_id'=>array(
XA_CLASS=>'cls_rlog_user_id',
XA_CAPTION=>RSTR_EDIT_USER_NAME,
XA_LIST=>'(rlog)(edit_save)'
),

'rlog_edit_user_name'=>array(
XA_CLASS=>'cls_rlog_user_name',
XA_CAPTION=>RSTR_EDIT_USER_NAME,
XA_LIST=>'(rlog)(edit_save)'
),

'rlog_last_login_date_time'=>array(
XA_CLASS=>'cls_rlog_date_time',
XA_CAPTION=>RSTR_LAST_LOGIN_DATE_TIME,
XA_FORMAT=>'Y-m-d H:i:s',
XA_LIST=>'(rlog)(last_login)'
),

'username_login'=>array(
XA_CLASS=>'cls_username_login',
XA_NAME_RS=>'username',
XA_CAPTION=>RSTR_USERNAME,
XA_SIZE=>20,
XA_REQUIRED=>true,
XA_MIN_CHAR=>0,
XA_MAX_CHAR=>15,
XA_HIDE_VALUE=>true,
XA_SEARCH_OP=>'s=',
XA_LIST=>'(login)(auth)'
),

'password_login'=>array(
XA_CLASS=>'cls_password_login',
XA_NAME_RS=>nothing,
XA_CAPTION=>RSTR_PASSWORD,
XA_SIZE=>20,
XA_REQUIRED=>true,
XA_MIN_CHAR=>0,
XA_MAX_CHAR=>15,
XA_HIDE_VALUE=>true,
XA_SEARCH_OP=>'s=',
XA_LIST=>'(login)'
),

'active_login' =>array(
XA_CLASS=>'cls_active_login',
XA_NAME_RS=>'active',
XA_SEARCH_OP=>'s=',
XA_LIST=>''
),

'password_new'=>array(
XA_CLASS=>'cls_password_new',
XA_NAME_RS=>nothing,
XA_CAPTION=>RSTR_PASSWORD,
XA_SIZE=>15,
XA_REQUIRED=>true,
XA_MIN_CHAR=>0,
XA_MAX_CHAR=>15,
XA_HIDE_VALUE=>true,
XA_LIST=>'(edit_inp)(reg_inp)'
),

'password_conf'=>array(
XA_CLASS=>'cls_password_conf',
XA_NAME_RS=>nothing,
XA_CAPTION=>RSTR_PASSWORD_CONF,
XA_SIZE=>15,
XA_REQUIRED=>true,
XA_MIN_CHAR=>0,
XA_MAX_CHAR=>15,
XA_HIDE_VALUE=>true,
XA_LIST=>'(edit_inp)(reg_inp)'
),

'email'=>array(
XA_CLASS=>'CVEmail',
XA_CAPTION=>RSTR_EMAIL,
XA_SIZE=>48,
XA_REQUIRED=>true,
XA_MIN_CHAR=>0,
XA_MAX_CHAR=>100,
XA_SEARCH_OP=>'s%',
XA_LIST=>'(ses)(sp)(sr)(fd)'
),

'name'=>array(
XA_CLASS=>'CVText',
XA_CAPTION=>RSTR_NAME,
XA_SIZE=>48,
XA_REQUIRED=>false,
XA_MIN_CHAR=>0,
XA_MAX_CHAR=>64,
XA_SEARCH_OP=>'s%',
XA_LIST=>'(ses)(sp)(sr)(fd)'
),

);

?>