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


define( 'STR_PAGESIG_KEY', '_page_key' );
define( 'STR_NS_LISTVIEW', '_lv:');
define( 'STR_PAGENO_KEY', 'pn');
define( 'STR_PAGESIZE_KEY', 'ps');
define( 'STR_ORDERBY_KEY', 'ob');

//----------------------------------------------------------------
// CRecordList
//----------------------------------------------------------------
class CRecordList extends CObject
{
	function SetSc( &$sc )
	{
		$this->sc = $sc;
	}

  /**
   * Set name space
   *
   * @param string $ns
   */
	function SetNameSpace( $ns )
	{
		$this->ns = $ns;
	}
	
	function GetNameSpace()
	{
		return $this->ns;
	}

	function SetFieldList( $ls )
	{
		$this->ls = $ls;
	}

  /**
   * Set page #
   *
   * @param integer $init_page_no
   * @param bool $b_force
   */
	function SetPageNo( $init_page_no = 1, $b_force = true )
	{
		if ( $b_force )
			$page_no = $init_page_no;
		else
		{
			$ns_lv = $this->ns . STR_NS_LISTVIEW . STR_PAGENO_KEY;

			$page_no = $this->sys->Request->Get( $ns_lv );
			if ( $page_no == null ) $page_no = $this->sys->State->Get( $ns_lv );

			if (( $page_no == '' ) || ( !CValidator::IsInteger( $page_no ) ))
				$page_no = $init_page_no;
			else
			{
				$page_no = intval( $page_no );
				if ( $page_no < 1 ) $page_no = $init_page_no;
			}
		}
		
		$this->page_no = $page_no;
	}

  /**
   * Set page size
   *
   * @param integer $init_page_size
   */
	function SetPageSize( $init_page_size )
	{
		$ns_lv = $this->ns . STR_NS_LISTVIEW . STR_PAGESIZE_KEY;

		$page_size = $this->sys->Request->Get( $ns_lv );
		if ( $page_size == null ) $page_size = $this->sys->State->Get( $ns_lv );

		if (( $page_size == '' ) || ( !CValidator::IsInteger( $page_size ) ))
			$page_size = $init_page_size;
		else
		{
			$page_size = intval( $page_size );
			if ( $page_size < 0 ) $page_size = $init_page_size;
		}
		
		$this->page_size = $page_size;
	}

  /**
   * Set order by
   *
   * @param string $init_order_by
   */
	function SetOrderBy( $init_order_by )
	{
		$ns_lv = $this->ns . STR_NS_LISTVIEW . STR_ORDERBY_KEY;

		$order_by = $this->sys->Request->Get( $ns_lv );
		if ( $order_by == null ) $order_by = $this->sys->State->Get( $ns_lv );

		if ( $order_by == '' ) $order_by = $init_order_by;

		//-- [BEGIN] Validate Order By
		$obx = split( ",", $order_by );
		foreach( $obx as $ob )
		{
			$ax = split( ' ', trim($ob) );
			if ( count( $ax ) != 2 )
			{
				$msg = "Invalid Order By : " . $order_by;
				$this->sys->Error->ShowError( $msg );
			}

			if ( !in_array( trim($ax[0]), $this->ls ) )
			{
				$msg = "Invalid Field in Order By : " . $order_by;
				$this->sys->Error->ShowError( $msg );
			}
		}
		//-- [END] Validate Order By

		$this->order_by = $order_by;
	}

  /**
   * Set up
   *
   * @param string $sql
   */
	function Setup( $sql, $msgx = '' )
	{
		$this->msg_no_record = RSTR_RL_NO_RECORDS_FOUND;
		$this->msg_showing_record = RSTR_RL_SHOWING_RECORDS;
		if ( $msgx != '' )
		{
			$this->msg_no_record = $msgx['msg_no_record'];
			$this->msg_showing_record = $msgx['msg_showing_record'];
		}

		$this->msg_no_record = "<span class='msg_record_stat'>" .
			$this->msg_no_record . "</span>";
		$this->msg_showing_record = "<span class='msg_record_stat'>" .
			$this->msg_showing_record . "</span>"; 

		$db =& $this->sys->DB;

		//--- [BEGIN] Init
		$ns_lv = $this->ns . STR_NS_LISTVIEW;
		if ( $this->order_by != '' ) $sql .= ' ORDER BY ' . $db->Sanitize($this->order_by);
		//--- [END] Init

		//--- [BEGIN] Get Page Result
		$db->GetPageResult(
			$this->result,
			$sql,
			$this->page_size,
			$this->total_record,
			$this->total_page,
			$this->page_no );
		//--- [END] Get Page Result

		//--- [BEGIN] Calc Page Ranges
		$this->fidx = ( $this->page_no - 1 ) * $this->page_size + 1;
		$this->eidx = $this->page_no * $this->page_size;
		if ( $this->eidx > $this->total_record ) $this->eidx = $this->total_record;
		//--- [END] Calc Page Ranges

		//--- [BEGIN] Page Tabs
		if ( $this->total_page <= 1 )
			$page_tabs = "";
		else
		{
			$pt =& new CPageTab();
			$w = 5;
			$tmpl = array();
			$tmpl['frame'] = "<table class='page_tabs'><tr>##PageCells##</tr></table>";
			$tmpl['sel'] = "<td class='page_cell_sel'>#PageNo#</td>\r\n";
			$tmpl['link'] = "<td class='page_cell'><a title='#Title#' href='' onClick='return CallSubmit(\"" . CPath::ThisFileUrl() . '?'. $ns_lv . STR_PAGENO_KEY . "=#PageNo#&_sc=" . $this->sc . "&\");'>#Caption#</a></td>\r\n";
			$tmpl['etc'] = "<td class='page_cell_etc'> ... </td>\r\n";
			$tmpl['title_first'] = RSTR_PAGETAB_FIRST;
			$tmpl['title_prev'] = RSTR_PAGETAB_PREV;
			$tmpl['title_page'] = RSTR_PAGETAB_PAGE;
			$tmpl['title_next'] = RSTR_PAGETAB_NEXT;
			$tmpl['title_last'] = RSTR_PAGETAB_LAST;
			$page_tabs = $pt->GetPageTabs( $this->total_page, $this->page_no, $w, $tmpl );
		}
		//--- [END] Page Tabs

		//--- [BEGIN] Navigational Messages
		if ( $this->page_no == 0 )
			$msg = $this->msg_no_record;
		else
		{
			$sel_range = ( $this->fidx == $this->eidx ? $this->fidx : $this->fidx . ' - ' . $this->eidx );
			$msg = $this->msg_showing_record;
			$msg = str_replace( "##sel_range##", $sel_range, $msg );
			$msg = str_replace( "##total_record##", $this->total_record, $msg );
		}
		//--- [END] Navigational Messages

		//--- [BEGIN] State Maintenance
		$this->sys->State->Set( $ns_lv . STR_PAGESIZE_KEY,  $this->page_size );
		$this->sys->State->Set( $ns_lv . STR_PAGENO_KEY,  $this->page_no );
		$this->sys->State->Set( $ns_lv . STR_ORDERBY_KEY, $this->order_by );
		//--- [END] State Maintenance

		//--- [BEGIN] ZBuffer
		$zb =& $this->sys->ZBuffer;
		$ns = $this->ns;
		$zb->Set("navi:{$ns}ps", $this->page_size);
		$zb->Set("navi:{$ns}fidx", $this->fidx);
		$zb->Set("navi:{$ns}eidx", $this->eidx);
		$zb->Set("navi:{$ns}page_tabs", $page_tabs);
		$zb->Set("stat:{$ns}msg", $msg);
		$zb->Set("stat:{$ns}total_record", $this->total_record);
		$zb->Set("stat:{$ns}total_page", $this->total_page);
		foreach( $this->ls as $vfield )
			$zb->Set( "ob:{$ns}{$vfield}", $this->GetOrderByIcons( $vfield ) );
		//--- [END] ZBuffer

		return $this->total_record;
	}

  /**
   * Fetch next record
   *
   * @return handle
   */
	function FetchNextRecord()
	{
		if ( !$this->result ) return false;
		$db =& $this->sys->DB;
		if ( !( $rs = $db->GetRowA( $this->result ) ) ) return false;
		return $rs;
	}

  /**
   * Set next record
   *
   * @param object $obj
   * @param object $rs
   * @param string $ns
   * @param array $ls
   */
	function SetNextRecord( &$obj, &$rs, $list_display = null, $param = null )
	{
		$ns = $this->ns;

		//---------------------------
		$ls = $this->ls;
		foreach( $ls as $key => $val ) $ls[$key] = $key;
		if ( $list_display == null ) $list_display = $ls;
		//---------------------------

		$obj->SendMsg( $ls, array( XM_CMD=>XC_IS_RECORD, XM_RS=>$rs ) );
		$px = array( XM_CMD=>XC_OF_DEFAULT, XM_NS=>$ns );
		if ( is_array( $param ) ) foreach ( $param as $key => $val ) $px[$key] = $val;
		$obj->SendMsg( $list_display, $px );
	}

	function GetOrderByIcons( $vfield )
	{
		return
			$this->CreateOrderByLink( $vfield, "ASC" ) . 
			$this->CreateOrderByLink( $vfield, "DESC" );
	}

	function CreateOrderByLink( $vfield, $asc_desc, $class_name = "" )
	{
		$ns_lv = $this->ns . STR_NS_LISTVIEW;
		$up_down = ( $asc_desc == "ASC" ? "up" : "down" );
		$title = ( $asc_desc == "ASC" ? RSTR_ORDER_BY_ASC : RSTR_ORDER_BY_DESC );
		$on_off = ( $this->order_by == $vfield . " " . $asc_desc ? "on" : "off" );
		if ( $class_name == '' ) $class_name = 'class_orderby_button';

		return
			"<a class='" . $class_name . "' " .
				"href='' " .
				"title='" . $title . "' " .
				"alt='" . $title . "' " .
				"onClick='return CallSubmit(" . 
				"\"". 
					CPath::ThisFileUrl() . '?' . 
					$ns_lv . STR_PAGENO_KEY . "=1&" . 
					"_sc=" . $this->sc . "&" .
					$ns_lv . STR_ORDERBY_KEY . "=" . $vfield . " " . $asc_desc.
				"\"" .
			");'>" .
			$this->GetOrderByIcon( $up_down, $on_off ) . 
			"</a>";
	}
	
	function GetOrderByIcon( $up_down, $on_off )
	{
		switch( 1 )
		{
		case 1:
			$image_path = "images/order_by";
			return "<img src='" . $image_path . "/icon_" . $up_down . "_" . $on_off . ".gif' border='0'>";

		case 2:
			$ch = ( $up_down == "up" ? '&and;' : '&or;' );
			$color = ( $on_off == "on" ? 'white' : '#a0a0a0' );
			return "<span style='color:{$color};'>{$ch}</span>";
		}
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>