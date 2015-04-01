<?php include(INC_HTML_TAG); ?>
<?php $hm->Title( __FILE__, RSTR_APP_TITLE, RSTR_STAFF, $hm->Zb( 'page:caption_verb' ) ); ?>
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
	<table width='99%' border='0' cellpadding='3' cellspacing='1'>

	<?php if ( $b_edit || $b_del ) { ?>
	<tr>
		<td class='column_caption'><span class="required"></span> <?php echo RSTR_STAFF_ID; ?> : </td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:staff_id'); ?></td>
	</tr>
	<?php } ?>

	<?php if ( ROOT_USER_ID == $hm->Zb("rs:def:staff_id") ) { ?>
		<input type='hidden' name='rs:def:active' value='Y' />
	<?php } else { ?>
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
	<?php } ?>

	<?php if ( ROOT_USER_ID == $hm->Zb("rs:def:staff_id") ) { ?>
		<input type='hidden' name='rs:def:group_id' value='<?php echo GROUP_ADMIN; ?>' />
	<?php } else { ?>
	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?>
			<span class="required">*</span>
			<?php } ?>
			<?php echo RSTR_STAFF_TYPE; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:group_id'); ?></td>
	</tr>
	<?php } ?>

	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?>
			<span class="required">*</span>
			<?php } ?>
			<?php echo RSTR_USERNAME; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:username'); ?>
		<?php if ( $b_reg || $b_edit ) { ?>
			<span style='font-size:80%'>
			</span>
		<?php } ?>
		</td>
	</tr>

	<?php if ( $b_reg || $b_edit ) { ?>
	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg ) { ?>
			<span class="required">*</span>
			<?php } ?>
			<?php echo RSTR_PASSWORD; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:password_new'); ?>
		<?php if ( $b_reg || $b_edit ) { ?>
			<span style='font-size:80%'>
			<?php if ( $b_edit ) { ?>
			<?php echo RSTR_LEAVE_PASSWORD_BLANK; ?>
			<?php } ?>
			</span>
		<?php } ?>
		</td>
	</tr>
	<?php } ?>

	<?php if ( $b_reg || $b_edit ) { ?>
	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg ) { ?>
			<span class="required">*</span>
			<?php } ?>
			<?php echo RSTR_PASSWORD_CONF; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:password_conf'); ?>
		<?php if ( $b_reg || $b_edit ) { ?>
			<span style='font-size:80%'>
			<?php if ( $hm->Zb( 'page:verb=' ) == 'edit' ) { ?>
			<?php echo RSTR_LEAVE_PASSWORD_BLANK; ?>
			<?php } ?>
			</span>
		<?php } ?>
		</td>
	</tr>
	<?php } ?>

	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?>
			<span class="required">*</span>
			<?php } ?>
			<?php echo RSTR_EMAIL; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:email'); ?>
		<?php if ( $b_reg || $b_edit ) { ?>
			<span style='font-size:80%'>
			</span>
		<?php } ?>
		</td>
	</tr>

	<tr>
		<td class='column_caption'>
			<?php if ( $b_reg || $b_edit ) { ?>
			<span class="required"></span>
			<?php } ?>
			<?php echo RSTR_NAME; ?> :
		</td>
		<td class='column_value'><?php echo $hm->Zb('rs:def:name'); ?>
		<?php if ( $b_reg || $b_edit ) { ?>
			<span style='font-size:80%'>
			</span>
		<?php } ?>
		</td>
	</tr>

	</table>
	</div>

	<?php echo $hm->SectEnd(); ?>
	<!-- [END] basic_info -->

	<?php $_last_login_ = 'yes'; ?>
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
