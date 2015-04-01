<?php include(INC_HTML_TAG); ?>
<?php $hm->Title( __FILE__, RSTR_APP_TITLE, RSTR_ADDRESSES, $hm->Zb( 'page:caption_verb' ) ); ?>
<?php include(INC_DETAIL_VERB); ?>

<head><?php include(INC_HTML_HEADER); ?></head>

<body>

<!-- [BEGIN] Container -->
<div id="container">

<?php include(INC_BODY_HEADER); ?>

<!-- [BEGIN] Main Form -->
<div id="main_div">

<?php include(INC_FORM_BEGIN); ?>

<?php include(INC_BODY_INFO); ?>

	<?php if ( $hm->Zb("def:display?") ) { ?>

	<!-- [BEGIN] basic_info -->
	<?php echo $hm->SectBegin( $hm->Zb( 'page:caption_verb' ) ); ?>

	<div style='overflow:auto;'>
	<table border='0' cellpadding='3' cellspacing='1'>

	<?php if ( $b_edit || $b_del ) { ?>
	<tr>
		<td class='column_caption'><span class="required"></span>
			<?php echo RSTR_ADDRESSES_ID; ?> : </td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:addresses_id'); ?></td>
	</tr>
	<?php } ?>

	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?>
			<span class="required">*</span>
			<?php } ?>
			<?php echo RSTR_ACTIVE; ?> :
		</td>
		<td class='column_value'>
			<?php if ( $b_reg || $b_edit ) { ?>
				<?php echo $hm->Zb('rs:def:active_Y'); ?>Yes&nbsp;&nbsp;&nbsp;
				<?php echo $hm->Zb('rs:def:active_N'); ?>No
			<?php } ?>
			<?php if ( $b_del ) { ?>
				<?php echo $hm->Zb('rs:def:active'); ?>
			<?php } ?>
		</td>
	</tr>

	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?><span class="required"></span><?php } ?>
			<?php echo RSTR_FIRST_NAME; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:first_name'); ?>
		</td>
	</tr>
	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?><span class="required"></span><?php } ?>
			<?php echo RSTR_LAST_NAME; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:last_name'); ?>
		</td>
	</tr>
	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?><span class="required"></span><?php } ?>
			<?php echo RSTR_COMPANY; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:company'); ?>
		</td>
	</tr>
	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?><span class="required"></span><?php } ?>
			<?php echo RSTR_TEL; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:tel'); ?>
		</td>
	</tr>
	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?><span class="required"></span><?php } ?>
			<?php echo RSTR_FAX; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:fax'); ?>
		</td>
	</tr>
	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?><span class="required"></span><?php } ?>
			<?php echo RSTR_CELL; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:cell'); ?>
		</td>
	</tr>
	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?><span class="required"></span><?php } ?>
			<?php echo RSTR_EMAIL; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:email'); ?>
		</td>
	</tr>
	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?><span class="required"></span><?php } ?>
			<?php echo RSTR_STREET1; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:street1'); ?>
		</td>
	</tr>
	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?><span class="required"></span><?php } ?>
			<?php echo RSTR_STREET2; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:street2'); ?>
		</td>
	</tr>
	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?><span class="required"></span><?php } ?>
			<?php echo RSTR_CITY; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:city'); ?>
		</td>
	</tr>
	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?><span class="required"></span><?php } ?>
			<?php echo RSTR_STATE; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:state'); ?>
		</td>
	</tr>
	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?><span class="required"></span><?php } ?>
			<?php echo RSTR_ZIP; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:zip'); ?>
		</td>
	</tr>

	</table>
	</div>

	<?php echo $hm->SectEnd(); ?>
	<!-- [END] basic_info -->

	<?php include(INC_DETAIL_LOG_INFO); ?>

	<?php echo $hm->SectEndMarker(); ?>

	<?php include(INC_DETAIL_BUTTONS); ?>

	<?php } ?>

<?php include(INC_FORM_END); ?>

</div>
<!-- [END] Main Form -->

<?php include(INC_BODY_FOOTER); ?>

</div>
<!-- [END] Container -->

</body>
</html>

<?php include(INC_HTML_END); ?>