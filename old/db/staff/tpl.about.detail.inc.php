<?php include(INC_HTML_TAG); ?>
<?php $hm->Title( __FILE__, RSTR_APP_TITLE, RSTR_ABOUT ); ?>

<head><?php include(INC_HTML_HEADER); ?></head>

<body>

<!-- [BEGIN] Container -->
<div id="container">

<?php include(INC_BODY_HEADER); ?>

<!-- [BEGIN] Main Form -->
<div id="main_div">

<?php //include(INC_FORM_BEGIN); ?>

<?php include(INC_BODY_INFO); ?>

	<!-- [BEGIN] about -->
	<?php echo $hm->SectBegin( RSTR_ABOUT ); ?>

	<div style='overflow:auto;'>
	<table width='99%' border='0' cellpadding='3' cellspacing='1'>

	<tr>
		<td align='left'>

			

		</td>
	</tr>

	<tr>
		<td align='left'>
		</td>
	</tr>

	</table>
	</div>

	<?php echo $hm->SectEnd(); ?>
	<!-- [END] about -->

	<?php echo $hm->SectEndMarker(); ?>

<?php //include(INC_FORM_END); ?>

</div>
<!-- [END] Main Form -->

<?php include(INC_BODY_FOOTER); ?>

</div>
<!-- [END] Container -->

</body>
</html>

<?php include(INC_HTML_END); ?>
