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
// COutObject
//----------------------------------------------------------------
class COutObject
{
	function Render()
	{
		return '';
	}
}

//----------------------------------------------------------------
// COutString
//----------------------------------------------------------------
//
//	$ht =& new COutString();
//	$ht->Set( '---template---', array( item, item item... );
//
//	$ht =& new COutString();
//	$ht->AddItem( item );
//	$ht->AddItem( item );
//	$ht->AddItem( item );
//	.....................
//
class COutString extends COutObject
{
	var $items;
	var $template;
	
	function COutString()
	{
		$this->template = null;
		$this->items = array();
	}

	function Set( $template, $items = null )
	{
		$this->template = $template;
		if ( !is_null( $items ) ) $this->items = $items;
	}

	function AddItem( $item )
	{
		$this->items[] = $item;
	}

	function Render( $format_encoding )
	{
		$ax = array();
		for( $i = 0; $i < count($this->items); $i++ )
		{
			$item = $this->items[$i];
			if ( is_object( $item ) )
				$ax[] = $this->items[$i]->Render( $format_encoding );
			else
			{
				switch( $format_encoding )
				{
				case 'html':
					$s = CStr::html( $item );
					//$s = CMBStr::replace( "\r\n", '<br/>', $s );
					break;
				default:
					$s = $item;
					break;
				}

				$ax[] = $s;
			}
		}

		if ( count( $ax ) == 0  )
		{
			$s = '';
		}
		else
		{
			if ( is_null( $this->template ) )
				$s = implode( '', $ax );
			else
				$s = vsprintf( $this->template, $ax );
		}

		return $s;
	}
}

//----------------------------------------------------------------
// COutHtml
//----------------------------------------------------------------
class COutHtml extends COutObject
{
	var $tag_name;
	var $kv;
	var $idx;
	var $inside;

	function COutHtml()
	{
		$this->tag_name = "";
		$this->attri = array();
		$this->inside = null;
		$this->idx = 0;
	}

	function SetTagName( $tag_name )
	{
		$this->tag_name = $tag_name;
	}

	function SetInside( $inside )
	{
		$this->inside = $inside;
	}

	function SetKV( $key, $val )
	{
		if ( $val == null )
			unset( $this->attri[$key] );
		else if ( $key == 'style' )
		{
			foreach( $val as $k => $v )
			{
				$this->SetStyle( $k, $v );
			}
		}
		else
			$this->attri[$key] = $val;
	}

	function SetV( $val )
	{
		$this->attri['---' . $this->idx] = $val;
		$this->idx++;
	}

	function AppendV( $val )
	{
		$this->attri['===' . $this->idx] = $val;
		$this->idx++;
	}

	function SetStyle( $key, $val )
	{
		if ( isset( $this->attri['style'] ) )
			$this->attri['style'][$key] = $val;
		else
			$this->attri['style'] = array( $key=>$val );
	}

	function Render( $format_encoding )
	{
		if ( is_null( $this->inside ) )
			$inside = '';
		else if ( is_object( $this->inside ) )
			$inside = $this->inside->Render( $format_encoding );
		else
			$inside = $this->inside;

		if ( $format_encoding == 'html' )
		{
			$ax = array();
			$ax[] = '<' . $this->tag_name;
			foreach( $this->attri as $key => $val )
			{
				if ( substr($key,0,3) == '===' )
				{
					//--- do nothing
				}
				else if ( substr($key,0,3) == '---' )
					$ax[] = $val;
				else if ( $key == 'style' )
				{
					$style= '';
					foreach( $val as $k => $v )
						$style .= $k .':' . $v .';';
					$ax[] = $key . "=\"" . $style . "\"";
				}
				else
					$ax[] = $key . "=\"" . $val . "\"";
			}

			$s = implode( ' ', $ax );

			if ( is_null( $this->inside ) )
				$s .= '/>';
			else
				$s .= '>' . $inside . '</' . $this->tag_name . '>';
		}
		else
			$s = $inside;

		//-- [BEGIN] Append Strings
		foreach( $this->attri as $key => $val )
		{
			if ( substr($key,0,3) == '===' )
			{
				$s .= $val;
			}
		}
		//-- [END] Append Strings

		return $s;
	}

}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>