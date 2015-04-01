<?php
$spec = array(

'frame' => array(
XA_CLASS=>'cls_ps_frame',
XA_AUTH=>false,
XA_DEFAULT_COMMAND=>'login'
),

'staff' => array(
XA_CLASS=>'cls_ps_staff',
XA_DEFAULT_COMMAND=>'search_init'
),

'about' => array(
XA_CLASS=>'cls_ps_about',
XA_DEFAULT_COMMAND=>'detail'
),

'addresses' => array(
XA_CLASS=>'cls_ps_addresses',
XA_DEFAULT_COMMAND=>'search_init'
),

);

?>