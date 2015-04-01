	<?php if ( $sys->ShowRLog() && ( $b_edit || $b_del ) ) { ?>
	<!-- [BEGIN] rlog -->
	<?php echo $hm->SectBegin( RSTR_RLOG ); ?>

	<div style='overflow:auto;'>
	<table border='0' cellpadding='3' cellspacing='1'>

	<?php if ( isset( $_last_login_ ) ) { ?>
	<tr>
		<td class='column_caption'><?php echo RSTR_LAST_LOGIN_DATE_TIME; ?> : </td>
		<td colspan='3'><?php echo $hm->Zb('rs:def:rlog_last_login_date_time'); ?></td>
	</tr>
	<?php } ?>

	<tr>
		<td class='column_caption'><?php echo RSTR_CREATE_DATE_TIME; ?> : </td>
		<td align='left'><?php echo $hm->Zb('rs:def:rlog_create_date_time'); ?></td>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td align='right'></td>
		<td align='left'></td>
	</tr>

	<tr>
		<td  class='column_caption'><?php echo RSTR_EDIT_DATE_TIME; ?> : </td>
		<td align='left'><?php echo $hm->Zb('rs:def:rlog_edit_date_time'); ?></td>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td align='right'> </td>
		<td align='left'></td>
	</tr>

	</table>
	</div>

	<?php echo $hm->SectEnd(); ?>
	<!-- [END] rlog -->
	<?php } ?>