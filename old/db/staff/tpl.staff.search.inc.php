<?php include(INC_HTML_TAG); ?>
<?php $hm->Title( __FILE__, RSTR_APP_TITLE, RSTR_STAFF, RSTR_SEARCH ); ?>

<head><?php include(INC_HTML_HEADER); ?></head>

<body>

<!-- [BEGIN] Container -->
<div id="container">

<?php include(INC_BODY_HEADER); ?>

<!-- [BEGIN] Main Form -->
<div id="main_div">

<?php include(INC_FORM_BEGIN); ?>

<?php include(INC_BODY_INFO); ?>

	<!-- [BEGIN] Search Criteria -->
	<?php echo $hm->SectBegin( RSTR_SEARCH_CRITERIA ); ?>

	<div style='overflow:auto;'>
	<table width='99%'>

	<tr>
		<td align="right"><?php echo RSTR_ACTIVE; ?> : </td>
		<td align="left"><?php echo $hm->Zb( 'sp:def:active' ); ?></td>
		<td align="right"><?php echo RSTR_STAFF_TYPE; ?> : </td>
		<td align="left"><?php echo $hm->Zb( 'sp:def:group_id' ); ?></td>
		<td align="right"><?php echo RSTR_USERNAME; ?> : </td>
		<td align="left"><?php echo $hm->Zb( 'sp:def:username' ); ?></td>
	</tr>

	<tr>
		<td align="right"><?php echo RSTR_NAME; ?> : </td>
		<td align="left"><?php echo $hm->Zb( 'sp:def:name' ); ?></td>
		<td align="right"><?php echo RSTR_EMAIL; ?> : </td>
		<td align="left"><?php echo $hm->Zb( 'sp:def:email' ); ?></td>
		<td align="right">&nbsp;</td>
		<td align="right"><?php echo $hm->Button( array( '<>'=>'</>', 'name'=>"_sc=_this/search_pb&", 'src'=>'search', 'value'=>RSTR_SEARCH ) ); ?></td>
	</tr>

	</table>
	</div>

	<?php echo $hm->SectEnd(); ?>
	<!-- [END] Search Criteria-->

	<!-- [BEGIN] Search Result -->
	<?php if ( $hm->Zb("def:display?") ) { ?>

	<?php echo $hm->SectBegin( RSTR_SEARCH_RESULT ); ?>

	<?php include(INC_SR_TOP_BAR); ?>

	<div style='overflow:auto;'>
	<table class='data_table'>

		<tr class='data_table_caption'>
			<th><?php echo RSTR_STAFF_ID; ?></th>
			<th><?php include(INC_SR_SELREC_HEADER); ?></th>
			<th><?php include(INC_SR_EDIT_BTN_HEADER); ?></th>
			<th><?php echo RSTR_ACTIVE; ?></th>
			<th><?php echo RSTR_STAFF_TYPE; ?></th>
			<th><?php echo RSTR_USERNAME; ?></th>
			<th><?php echo RSTR_EMAIL; ?></th>
			<th><?php echo RSTR_NAME; ?></th>
		</tr>

		<?php while( $hm->zb('@rs:def:begin_table') ) { ?>
		<tr>
			<td style='text-align:left;'><?php echo $hm->Zb('rs:def:staff_id'); ?></td>
			<?php include(INC_SR_ID_PARAM); ?>
			<?php include(INC_SR_SELREC); ?>
			<?php include(INC_SR_EDIT_BTN); ?>
			<td style='text-align:center;'><?php echo $hm->Zb('rs:def:active'); ?></td>
			<td style='text-align:center;'><?php echo $hm->Zb('rs:def:group_id'); ?></td>
			<td style='text-align:left;'><?php echo $hm->Zb('rs:def:username'); ?></td>
			<td style='text-align:left;'><?php echo $hm->Zb('rs:def:email'); ?></td>
			<td style='text-align:left;'><?php echo $hm->Zb('rs:def:name'); ?></td>
		</tr>
		<?php } ?>

	</table>
	</div>

	<?php include(INC_SR_BOTTOM_BAR); ?>

	<?php echo $hm->SectEnd(); ?>

	<?php } ?>
	<!-- [END] Search Result -->

	<?php echo $hm->SectEndMarker(); ?>

<?php include(INC_FORM_END); ?>

</div>
<!-- [END] Main Form -->

<?php include(INC_BODY_FOOTER); ?>

</div>
<!-- [END] Container -->

</body>
</html>

<?php include(INC_HTML_END); ?>