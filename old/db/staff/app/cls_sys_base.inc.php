<?php
$spec_sys_base = array(
	XA_CLASS=>'cls_sys_base',
	XA_AUTH=>true,
	XA_DEFAULT_PAGESET=>'frame',
	XA_FRAME_FIELDSET=>'staff',
	XA_FRAME_FIELDSET_ID=>'staff_id',
	XA_START_PAGE=>'addresses/_def'
);

//----------------------------------------------------------------
// cls_sys_base
//----------------------------------------------------------------
class cls_sys_base extends cls_sys_aso
{
	function OnCompoSpec( &$compo )
	{
		parent::OnCompoSpec( $compo );
		$compo['Authorization'] = 'cls_auth_base';
		$compo['HtmlMacro'] = 'cls_hm_base';
	}

	function OnLoadPageListSpec()
	{
		include('df.pageset.inc.php');
		$this->SetPageSetSpec( $spec );
	}

	function IsAdmin()
	{
		return ( $this->AuthSession->GetV('group_id') == GROUP_ADMIN );
	}

	function ShowRLog()
	{
		return true;
	}

	function EncryptPassword( $password )
	{
		//return CUtil::EncryptPassword( $password );
		return $password;
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>