<?php
//---------------------------------------------------------------
// Path
//---------------------------------------------------------------
define( 'PATH_INCLUDE', dirname(__FILE__) . '/' );

//---------------------------------------------------------------
// HTML Macro
//---------------------------------------------------------------
define( 'INC_HTML_TAG', PATH_INCLUDE . 'tpl.html.tag.inc.php');
define( 'INC_HTML_HEADER', PATH_INCLUDE . 'tpl.html.header.inc.php');
define( 'INC_HTML_END', PATH_INCLUDE . 'tpl.html.end.inc.php');

//---------------------------------------------------------------
// Page Macro
//---------------------------------------------------------------
define( 'INC_BODY_HEADER', PATH_INCLUDE . 'tpl.body.header.inc.php');
define( 'INC_BODY_FOOTER', PATH_INCLUDE . 'tpl.body.footer.inc.php');
define( 'INC_BODY_INFO', PATH_INCLUDE . 'tpl.body.info.inc.php');

//---------------------------------------------------------------
// Form Macro
//---------------------------------------------------------------
define( 'INC_FORM_BEGIN', PATH_INCLUDE . 'tpl.form.begin.inc.php');
define( 'INC_FORM_END', PATH_INCLUDE . 'tpl.form.end.inc.php');

//---------------------------------------------------------------
// Box Macro
//---------------------------------------------------------------
define( 'INC_BOX_DEF_BEGIN', PATH_INCLUDE . 'tpl.box.def_begin.inc.php');
define( 'INC_BOX_DEF_END', PATH_INCLUDE . 'tpl.box.def_end.inc.php');
define( 'INC_BOX_END_MARKER', PATH_INCLUDE . 'tpl.box.end_marker.inc.php');

//---------------------------------------------------------------
// Search Result Macro
//---------------------------------------------------------------
define( 'INC_SR_TOP_BAR', PATH_INCLUDE . 'tpl.sr.top_bar.inc.php');
define( 'INC_SR_BOTTOM_BAR', PATH_INCLUDE . 'tpl.sr.bottom_bar.inc.php');
define( 'INC_SR_ID_PARAM', PATH_INCLUDE . 'tpl.sr.id_param.inc.php');
define( 'INC_SR_SELREC', PATH_INCLUDE . 'tpl.sr.selrec.inc.php');
define( 'INC_SR_SELREC_HEADER', PATH_INCLUDE . 'tpl.sr.selrec_header.inc.php');
define( 'INC_SR_EDIT_BTN', PATH_INCLUDE . 'tpl.sr.edit_btn.inc.php');
define( 'INC_SR_EDIT_BTN_HEADER', PATH_INCLUDE . 'tpl.sr.edit_btn_header.inc.php');

//---------------------------------------------------------------
// Detail Page Macro
//---------------------------------------------------------------
define( 'INC_DETAIL_VERB', PATH_INCLUDE . 'tpl.detail.verb.inc.php');
define( 'INC_DETAIL_LOG_INFO', PATH_INCLUDE . 'tpl.detail.log_info.inc.php');
define( 'INC_DETAIL_BUTTONS', PATH_INCLUDE . 'tpl.detail.buttons.inc.php');

?>