<?php include(INC_HTML_TAG); ?>
<?php $hm->Title( __FILE__, RSTR_APP_TITLE, RSTR_AREA_TITLE, RSTR_LOG_IN ); ?>

<head><?php include(INC_HTML_HEADER); ?>

<style type="text/css">

div.login_title {
	margin-top:10px;
	text-align:center;
	font-size:28px;
	font-weight:bold;
	color:#3e83c9;
}

span.login_input_caption {
	color:#808080;
	font-weight:bold;
	font-style:italic;
}
span.login_input_box input {
	width:180px;
	padding:0 6px 0 6px;
	font-weight:bold;
	font-size:150%;
	color:#404040;
	background:#f0f0f0 url(images/login/bg_input.png);
}

a.login_maker_link {
	text-align:center;
	color:#d0d0d0;
	font-style:italic;
	font-weight:bold;
	font-size:11px;
	text-decoration:none;
}
</style>

</head>

<body>

<!-- [BEGIN] Container -->
<div id="container">

<div style='width:440px; margin:0 auto;'>

<!-- [BEGIN] Main Form -->
<?php include(INC_FORM_BEGIN); ?>

<div style="margin-top:80px;"></div>

<?php include(INC_BODY_INFO); ?>

	<!-- [BEGIN] Log In -->
	<?php echo $hm->SectBegin( RSTR_LOG_IN ); ?>

		<div style="margin-top:20px;"></div>

	<table width='100%' border='0' cellpadding='3' cellspacing='1'>

	<tr>
	  <td class='column_caption' style='width:150px;'><span class="required">*</span>
	  <span class='login_input_caption'><?php echo RSTR_USERNAME; ?></span> : </td>
	  <td class='column_value'><span class='login_input_box'><?php echo $hm->Zb('rs:def:username_login'); ?></span></td>
	</tr>

	<tr>
	  <td class='column_caption' style='width:150px;'><span class="required">*</span>
	  <span class='login_input_caption'><?php echo RSTR_PASSWORD; ?></span> : </td>
	  <td class='column_value'><span class='login_input_box'><?php echo $hm->Zb('rs:def:password_login'); ?></span></td>
	</tr>

	</table>

	<div style="text-align:center;margin-top:20px;margin-bottom:10px;">
		<?php echo $hm->Button( array( '<>'=>'</>', 'name'=>'_sc=_this/auth&', 'src'=>'enter', 'value'=>RSTR_ENTER ) ); ?>
	</div>

	<?php echo $hm->SectEnd(); ?>
	<!-- [END] Log In -->

	<?php echo $hm->SectEndMarker(); ?>

	<div style='text-align:center;margin:10px;'>
	</div>

<?php include(INC_FORM_END); ?>
<!-- [END] Main Form -->

</div>

<?php include(INC_BODY_FOOTER); ?>

</div>
<!-- [END] Container -->

</body>
</html>

<?php include(INC_HTML_END); ?>
