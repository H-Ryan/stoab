<?php 
	$user_id = $this->sys->AuthSession->GetV( $this->sys->Get( XA_FRAME_FIELDSET_ID ) );
	$username = $this->sys->AuthSession->GetV( "username" );
?>

<!-- [BEGIN] Page Header -->
<?php
	include('df.top_menu.inc.php');

	$p = $this->sys->GetPageSet();
	$sc_sel = $p->name;

	$attri = array(
		XA_CLASS=>'CMenu',
		'menu'=>$spec,
		'sc_sel'=>$sc_sel
	);

	$menu_obj =& $sys->RunObject( $sys, 'Menu', $attri );
?>
<div class='dmenu'>
<div class='dmenu_topline'></div>
<table class='dmenu_table'>

<?php while ( $menu = $menu_obj->GetMenu() ) { ?>
<tr>
	<td class='dmenu_td_empty'>&nbsp;</td>
	<?php
		foreach( $menu as $key => $val )
		{
			if ( $menu_obj->GetMenuItemInfo( $key, $val, $sc, $sel, $caption ) )
			{
	?>
	<td class='dmenu_td'>
		<a class="dmenu_item" href="<?php echo $hm->Url( '_sc=' . $sc . '&' ); ?>"
		><?php echo $caption; ?></a>
	</td>
	<td class='dmenu_td_empty'>&nbsp;</td>
	<?php 
			}
		}
	?>
</tr>
<?php } ?>

</table>
</div>

<div class='dmenu_shadow'></div>

<div id='page_header'>
<table border="0" width="100%">
<tr>
	<td>
		<span class='page_title'>
		<?php echo $hm->GetTitle( 2, RSTR_BREADCRUMS_MARK ); ?>
		</span>
	</td>
	<td align='right' valign='top'>
		<span class='login_user_caption'><?php echo RSTR_USER; ?> : </span>
		<span class='login_user_name'><?php echo $username; ?></span>
	</td>
</tr>
</table>
</div>


<!-- [END] Page Header -->
