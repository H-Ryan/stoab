<?php

define( 'AUTHSESSION_KEY', "AuthSessionKey:(" . __FILE__ . ")" );

//----------------------------------------------------------------
// cls_auth_base
//----------------------------------------------------------------
class cls_auth_base extends cls_auth_aso
{
	function IsAuthorized( $ps, $cmd )
	{
		if ( $this->sys->IsAdmin() )
		{
			return true;
		}
		else
		{
			return ( $ps != "staff" );
		}
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>