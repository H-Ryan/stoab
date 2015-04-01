<?php if ( $hm->Zb("page:info_msg?") ) { ?>
<!-- [BEGIN] Info Message -->
<div class='info_box'><?php echo $hm->Zb("page:info_msg"); ?></div>
<!-- [END] Info Message -->
<?php } ?>

<?php if ( $hm->Zb("page:err_msg?") ) { ?>
<!-- [BEGIN] Error Message -->
<div class='err_box'>
<table width='99%' style='border-collapse: collapse;'>
<tr>
	<td align='center' valign='middle' width='20'><img src='images/icons/icon_warn.gif'></td>
	<td width='10'>&nbsp;</td>
	<td align='left' valign='middle'><?php echo $hm->Zb("page:err_msg"); ?></td>
</tr>
</table>
</div>
<!-- [END] Error Message -->
<?php } ?>