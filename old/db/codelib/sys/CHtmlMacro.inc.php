<?php
//==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>==>>>
//
// Address Book Script v1.18 [G110]
// Copyright (c) phpkobo.com ( http://www.phpkobo.com/ )
// Email : admin@phpkobo.com
// ID : AB201-118 [G110]
// URL : http://www.phpkobo.com/address_book.php
//
// This software is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; version 2 of the
// License.
//
//==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<==<<<


//----------------------------------------------------------------
// 
//----------------------------------------------------------------
define('TYPE_NORMAL_BUTTON', 'rollover' );
//define('TYPE_NORMAL_BUTTON', 'html' );

//
// '<>' => '</>'
// '<>' => '<rollover/>'
// '<>' => '<html/>'
//

//----------------------------------------------------------------
// CHtmlMacro
//----------------------------------------------------------------
class CHtmlMacro extends CObject
{
	//----------------------------------------------------------------
	// Zb
	//----------------------------------------------------------------
	function Zb( $key, $filter_type = null, $filter_param = null )
	{
		return $this->sys->ZBuffer->Get( $key, $filter_type, $filter_param );
	}

	//----------------------------------------------------------------
	// Form
	//----------------------------------------------------------------
	function Form( $px = array() )
	{
		if ( !isset( $px['name'] ) ) $px['name'] = 'main';
		if ( !isset( $px['action'] ) ) $px['action'] = $this->Url();
		if ( !isset( $px['method'] ) ) $px['method'] = 'post';
		foreach( $px as $key => $val )
		{
			$qx[] = $key . "='" . $val . "'"; 
		}
		$s = implode( " ", $qx );
		$s = "<form " . $s . ">";
		echo $s;
		echo $this->Zb('@page:state');
	}

	//----------------------------------------------------------------
	// GetDefID
	//----------------------------------------------------------------
	function GetDefID()
	{
		return $this->def_id;
	}

	//----------------------------------------------------------------
	// SetDefID
	//----------------------------------------------------------------
	function SetDefID( $def_id )
	{
		$this->def_id = $def_id;
	}

	//----------------------------------------------------------------
	// Title
	//----------------------------------------------------------------
	function Title()
	{
		$n = func_num_args();
		$this->title_arr = func_get_args();
	}

	//----------------------------------------------------------------
	// GetTitle
	//----------------------------------------------------------------
	function GetTitle( $start_idx, $delimiter )
	{
		$title_arr = $this->title_arr;
		for( $i = 0; $i < $start_idx; $i++ )
		{
			unset( $title_arr[ $i ] );
		}
		return implode( $delimiter, $title_arr );
	}

	//----------------------------------------------------------------
	// RV
	//----------------------------------------------------------------
	function RV( $key )
	{
		if ( isset( $_GET[$key] ) )
			return $_GET[$key];
		else
			return '';
	}

	//----------------------------------------------------------------
	// MakeShort
	//----------------------------------------------------------------
	function MakeShort( $s, $len )
	{
		$s = str_replace( "<br>", "", $s );
		if ( CMBStr::strlen( $s ) > $len )
			return CMBStr::substr( $s, 0, $len ) . "...";
		else
			return $s;
	}

	//----------------------------------------------------------------
	// Url
	//----------------------------------------------------------------
	function Url( $params = '' )
	{
		$self_url = $_SERVER['PHP_SELF'];
		if ( $params != '' ) $self_url .= '?' . $params;
		return $self_url;
	}

	//----------------------------------------------------------------
	// Alt
	//----------------------------------------------------------------
	function Alt( $msg )
	{
		if ( CUtil::IsIE() )
			$s = 'alt="' . $msg . '"';
		else
			$s = 'title="' . $msg . '"';

		return $s;
	}

	//----------------------------------------------------------------
	// Section
	//----------------------------------------------------------------
	function SectBegin( $label )
	{
?>	<span class="sect_title"><?php echo $label; ?></span>
	<?php include(INC_BOX_DEF_BEGIN); ?><?php
	}

	function SectEnd()
	{
?>	<?php include(INC_BOX_DEF_END); ?><?php
	}

	function SectEndMarker()
	{
?>	<?php include(INC_BOX_END_MARKER); ?><?php
	}

	//----------------------------------------------------------------
	// GetImagePath
	//----------------------------------------------------------------
	function GetImagePath()
	{
		return _LANG_FILE_( "images/buttons/" );
	}

	//----------------------------------------------------------------
	// Button
	//----------------------------------------------------------------
	function Button( $args )
	{
		$tag_name = $args['<>'];
		$tag_begin = '';
		$tag_end = '';
		
		if ( '<' == substr( $tag_name, 0, 1 ) )
		{
			$tag_name = substr( $tag_name, 1 );
			$tag_begin = '<';
		}

		if ( '/>' == substr( $tag_name, strlen($tag_name)-2 ) )
		{
			$tag_name = substr( $tag_name, 0, strlen($tag_name)-2 );
			$tag_end = '/>';
		}
		else if ( '>' == substr( $tag_name, strlen($tag_name)-1 ) )
		{
			$tag_name = substr( $tag_name, 0, strlen($tag_name)-1 );
			$tag_end = '>';
		}

		unset( $args['<>'] );

		if ( $tag_name == '' ) $tag_name = TYPE_NORMAL_BUTTON;
		return call_user_func_array( array( $this, 'Button_' . $tag_name ), array( $args, $tag_name, $tag_begin, $tag_end ) );
	}

	//----------------------------------------------------------------
	// Button_test
	//----------------------------------------------------------------
	function Button_test( $args, $tag_name, $tag_begin, $tag_end )
	{
		$ht = array();
		
		if ( $tag_begin != '' ) $ht[] = $tag_begin . $tag_name;

		$this->ExpandAttributes( $ht, $args, array() );

		if ( $tag_end != '' ) $ht[] = $tag_end;

		$s = implode( " ", $ht );

		return $s;
	}

	//----------------------------------------------------------------
	// Button_default
	//----------------------------------------------------------------
	function Button_default( $args, $tag_name, $tag_begin, $tag_end )
	{
		if ( CUtil::IsWebFetch() ) return '';

		$ht = array();
		if ( $tag_begin != '' ) $ht[] = "<input";
		$ht[] = "type=\"submit\"";
		$this->ExpandAttributes( $ht, $args, array() );
		$ht[] = "style='position:absolute; top:-100000mm;'";
		if ( $tag_end != '' ) $ht[] = $tag_end;
		$s = implode( " ", $ht );
		return $s;
	}

	//----------------------------------------------------------------
	// Button_html
	//----------------------------------------------------------------
	function Button_html( $args, $tag_name, $tag_begin, $tag_end )
	{
		$ht = array();
		if ( $tag_begin != '' ) $ht[] = "<input";
		$ht[] = "type=\"submit\"";
		$this->ExpandAttributes( $ht, $args, array() );
		if ( $tag_end != '' ) $ht[] = $tag_end;
		$s = implode( " ", $ht );
		return $s;
	}

	//----------------------------------------------------------------
	// Button_mini
	//----------------------------------------------------------------
	function Button_mini( $args, $tag_name, $tag_begin, $tag_end )
	{
		$ht = array();
		if ( $tag_begin != '' ) $ht[] = "<input";
		$ht[] = "type=\"submit\"";
		$this->ExpandAttributes( $ht, $args, array() );
		if ( $tag_end != '' ) $ht[] = $tag_end;
		$s = implode( " ", $ht );
		return $s;
	}

	//----------------------------------------------------------------
	// Button_record
	//----------------------------------------------------------------
	function Button_record( $args, $tag_name, $tag_begin, $tag_end )
	{
		$ht = array();
		if ( $tag_begin != '' ) $ht[] = "<input";
		$ht[] = "type=\"submit\"";
		if ( !isset( $args['class'] ) ) $args['class'] = 'record';
		$this->ExpandAttributes( $ht, $args, array() );
		if ( $tag_end != '' ) $ht[] = $tag_end;
		$s = implode( " ", $ht );
		return $s;
	}

	//----------------------------------------------------------------
	// Button_rollover
	//----------------------------------------------------------------
	function Button_rollover( $args, $tag_name, $tag_begin, $tag_end )
	{
		$args['_rollover'] = true;
		return $this->Button_image(  $args, $tag_name, $tag_begin, $tag_end );
	}

	//----------------------------------------------------------------
	// Button_image
	//----------------------------------------------------------------
	function Button_image( $args, $tag_name, $tag_begin, $tag_end )
	{
		$ax = split( "[.]", $args["src"] );
		$src_name = $ax[0];
		$src_ext = "png";
		if ( count( $ax ) == 2 )
			$src_ext = $ax[1];

		$ht = array();
		$a_tag = false;
		if ( $tag_begin != '' )
		{
			if ( isset($args['popup_url']) )
			{
				$ht[] = "<a href=\"" . $args['popup_url'] . "\" target='_blank'><img border='0'";
				$a_tag = true;
			}
			else
			{
				$ht[] = "<input";
				$ht[] = "type=\"image\"";
				$a_tag = false;
			}
		}
		$this->ExpandAttributes( $ht, $args, array('src') );
		$ht[] = "src=\"" . $this->GetImagePath() . "btn_{$src_name}.{$src_ext}\"";
		if ( isset( $args['_rollover'] ) && $args['_rollover'] )
		{
			$ht[] = "onmouseover=\"javascript:this.src='" . $this->GetImagePath() . "btn_{$src_name}_ro.{$src_ext}'\"";
			$ht[] = "onmouseout=\"javascript:this.src='" . $this->GetImagePath() . "btn_{$src_name}.{$src_ext}'\"";
		}

		if ( isset( $args['click'] ) )
		{
			$click = $args['click'];
			if ( $click == 'reset' )
				$ht[] = "onClick='document.forms[\"main\"].reset(); return false;'";
			else
				$ht[] = $click;
		}

		if ( $tag_end != '' )
		{
		 	if ( $a_tag )
			{
				$ht[] = $tag_end . "</a>";
			}
			else
			{
				$ht[] = $tag_end;
			}
		}
		
		$s = implode( " ", $ht );
		return $s;
	}

	//----------------------------------------------------------------
	// ExpandAttributes
	//----------------------------------------------------------------
	function ExpandAttributes( &$ht, &$args, $exclude )
	{
		foreach( $args as $key => $val )
		{
			if ( substr( $key, 0, 1 ) != '_' ) 
			{
				if ( !in_array( $key, $exclude ) )
					if ( $key =='alt' )
						$ht[] = $this->Alt( $args['alt'] );
					else if ( strtolower($key) =='onclick' )
						$ht[] = $key . "='" . $val . "'";
					else
						$ht[] = $key . '="' . $val . '"';
			}
		}
		
		if ( !isset( $args['alt'] ) && isset( $args['value'] ) )
			$ht[] = $this->Alt( $args['value'] );
	}
}

//-----------------------------------------------------------------------
// END OF FILE
//-----------------------------------------------------------------------
?>