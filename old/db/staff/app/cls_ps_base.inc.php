<?php
class cls_ps_base extends cls_ps_aso
{
	//------------------------------------------------------------
	// OnLoadFieldListSpec
	//------------------------------------------------------------
	function OnLoadFieldListSpec()
	{
		include('df.fieldlist.inc.php');
		$this->SetFieldListSpec( $spec );
	}

	//------------------------------------------------------------
	// GetSelRecArray
	//------------------------------------------------------------
	function GetSelRecArray()
	{
		$ax = $this->sys->GetIV( '_selrec_' );
		$selrec = array();
		foreach( $ax as $v )
		{
			$v = trim( $v );
			if ( CValidator::IsInteger( $v ) )
			{
				$selrec[] = intval( $v );
			}
		}
		return $selrec;
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>