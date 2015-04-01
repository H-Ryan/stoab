<?php

$spec = array(

'staff' => array(
XA_CLASS=>'cls_fl_staff',
XA_SPEC_FILE=>'df.fl.staff.inc.php',
XA_TABLE_NAME=>TBL_STAFF,
XA_ID_NAME=>'staff_id',
XA_INIT_ORDER_BY=>'staff_id ASC',
XA_INIT_PAGE_SIZE=>20
),

'addresses' => array(
XA_CLASS=>'cls_fl_addresses',
XA_SPEC_FILE=>'df.fl.addresses.inc.php',
XA_TABLE_NAME=>TBL_ADDRESSES,
XA_ID_NAME=>'addresses_id',
XA_INIT_ORDER_BY=>'addresses_id DESC',
XA_INIT_PAGE_SIZE=>20
),

);

?>