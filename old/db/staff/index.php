<?php

// 22527 = E_ALL & ~E_DEPRECATED & ~E_STRICT
	error_reporting( 22527 );

	require('common.inc.php');
	$sys =& CVSystem::SetupSystem( $spec_sys_base );
	$sys->SetUserType( UT_STAFF );
	$sys->Run();
?>
