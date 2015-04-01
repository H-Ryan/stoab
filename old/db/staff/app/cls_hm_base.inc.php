<?php
class cls_hm_base extends cls_hm_aso
{
	//----------------------------------------------------------------
	// GetImagePath
	//----------------------------------------------------------------
	function GetImagePath()
	{
		return _LANG_FILE_( "images/buttons/##LANG_CODE##/" );
	}
/*
	//----------------------------------------------------------------
	// Section
	//----------------------------------------------------------------
	function SectBegin( $label = null )
	{
	?-->
	<fieldset>
	<--?php if ( $label != null ) { ?-->
		<legend class="legendTitle"><--?php echo $label; ?--></legend><center>
	<--?php } ?-->
	<--?php
	}

	function SectEnd()
	{
	?-->
	</center></fieldset>
	<--?php
	}

	function SectEndMarker()
	{
	}
*/
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>