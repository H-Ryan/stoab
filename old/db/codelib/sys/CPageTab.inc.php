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


/*
-----------------------------------------
CPageTab
-----------------------------------------

        |<----w---->|
        |     |     |
< 1 ... 23|24|25|26|27 ... 46 >
  |     |     |     |      |
  |     |     |     |      $idx_e
  |     |     |     $idx_re
  |     |     $idx
  |     $idx_rf
  $idx_f

-----------------------------------------
*/  

/*
define('STR_BTN_FIRST','≪');
define('STR_BTN_LAST','≫');
define('STR_BTN_PREV','＜');
define('STR_BTN_NEXT','＞');
*/

/*
define('STR_BTN_FIRST','《&nbsp;');
define('STR_BTN_LAST','&nbsp;》');
define('STR_BTN_PREV','〈&nbsp;');
define('STR_BTN_NEXT','&nbsp;〉');
*/

define('STR_BTN_FIRST','&lt;&lt;');
define('STR_BTN_LAST','&gt;&gt;');
define('STR_BTN_PREV','&lt;');
define('STR_BTN_NEXT','&gt;');


class CPageTab extends CObject
{
	var $idx;
	var $tmpl_sel;
	var $tmpl_link;
	
  /**
   * Create page tabs
   *
   * @param integer $total
   * @param integer $idx
   * @param integer $w
   * @param array $tmpl
   * @return string
   */
	function GetPageTabs( $total, $idx, $w, $tmpl )
	{
		if ( $total == 0 ) return '';
		
		$w2 = floor( $w / 2 );

		$this->idx = $idx;
		$this->tmpl = $tmpl;
//		$this->tmpl_frame = $tmpl['frame'];
//		$this->tmpl_sel = $tmpl['sel'];
//		$this->tmpl_link = $tmpl['link'];
//		$this->tmpl_etc = $tmpl['etc'];

		$this->idx_f = 1;
		$this->idx_e = $total;
		
		$idx_rf = $idx - $w2;
		if ( $this->idx_f < $idx_rf )
		{
			$idx_re = $idx + $w2;
			if (!( $idx_re < $this->idx_e ))
			{
				$idx_re = $this->idx_e;
				$idx_rf = $idx_re - $w;
				if (!( $this->idx_f < $idx_rf ))
					$idx_rf = $this->idx_f;
			}
		}
		else
		{
			$idx_rf = $this->idx_f;
			$idx_re = $idx_rf + $w;
			if (!( $idx_re < $this->idx_e ))
				$idx_re = $this->idx_e;
		}

		//--- Init Buffer
		$s = '';
		
		//--- First & Prev Button
		if ( $this->idx_f < $this->idx )
		{
			$s .= $this->GetFirst();
			$s .= $this->GetPrev();
		}

		if ( $this->idx_f < $idx_rf )
		{
			$s .= $this->GetTab( $this->idx_f );

			if ( $this->idx_f+1 == $idx_rf )
				$s .= $this->GetSepa();
			else if ( $this->idx_f+2 == $idx_rf )
			{
				$s .= $this->GetSepa();
				$s .= $this->GetTab( $this->idx_f+1 );
				$s .= $this->GetSepa();
			}
			else
				$s .= $this->GetDots();
		}

		for ( $i = $idx_rf; $i <= $idx_re; $i++ )
		{
			$s .= $this->GetTab( $i );
			if ( $i < $idx_re ) $s .= $this->GetSepa();
		}	

		if ( $idx_re < $this->idx_e )
		{
			if ( $idx_re+1 == $this->idx_e )
				$s .= $this->GetSepa();
			else if ( $idx_re+2 == $this->idx_e )
			{
				$s .= $this->GetSepa();
				$s .= $this->GetTab( $idx_re+1 );
				$s .= $this->GetSepa();
			}
			else
				$s .= $this->GetDots();

			$s .= $this->GetTab( $this->idx_e );
		}
		
		//--- Next & Last Button
		if ( $this->idx < $this->idx_e )
		{
			$s .= $this->GetNext();
			$s .= $this->GetLast();
		}

		$s = str_replace( "##PageCells##", $s, $this->tmpl['frame'] );

		return $s;
	}

  /**
   * Get "previous" link
   *
   * @return string
   */
	function GetPrev()
	{
		$s = CMBStr::replace( '#Title#',  $this->tmpl['title_prev'], $this->tmpl['link'] );
		$s = CMBStr::replace( '#PageNo#', ( $this->idx - 1 ), $s );
		$s = CMBStr::replace( '#Caption#',  STR_BTN_PREV, $s );
		return ' ' . $s . ' ';
	}

  /**
   * Get "next" link
   *
   * @return string
   */
	function GetNext()
	{
		$s = CMBStr::replace( '#Title#',  $this->tmpl['title_next'], $this->tmpl['link'] );
		$s = CMBStr::replace( '#PageNo#', ( $this->idx + 1 ), $s );
		$s = CMBStr::replace( '#Caption#',  STR_BTN_NEXT, $s );
		return ' ' . $s . ' ';
	}

  /**
   * Get "first" link
   *
   * @return string
   */
	function GetFirst()
	{
		$s = CMBStr::replace( '#Title#',  $this->tmpl['title_first'], $this->tmpl['link'] );
		$s = CMBStr::replace( '#PageNo#', ( $this->idx_f ), $s );
		$s = CMBStr::replace( '#Caption#',  STR_BTN_FIRST, $s );
		return ' ' . $s . ' ';
	}

  /**
   * Get "last" link
   *
   * @return string
   */
	function GetLast()
	{
		$s = CMBStr::replace( '#Title#',  $this->tmpl['title_last'], $this->tmpl['link'] );
		$s = CMBStr::replace( '#PageNo#', ( $this->idx_e ), $s );
		$s = CMBStr::replace( '#Caption#',  STR_BTN_LAST, $s );
		return ' ' . $s . ' ';
	}

  /**
   * Get "tab" link
   *
   * @return string
   */
	function GetTab( $i )
	{
		if ( $this->idx == $i )
			$s = $this->tmpl['sel'];
		else
			$s = $this->tmpl['link'];

		$s = CMBStr::replace( '#Title#',  $this->tmpl['title_page'], $s );
		$s = CMBStr::replace( '#PageNo#', $i, $s );
		$s = CMBStr::replace( '#Caption#', $i, $s );

		return $s;
	}

  /**
   * Get a separtor
   *
   * @return string
   */
	function GetSepa()
	{
		$s = ' ';
		return $s;
	}

  /**
   * Get dots
   *
   * @return string
   */
	function GetDots()
	{
		$s = $this->tmpl['etc'];
		return $s;
	}

	function ShowEx( $w, $sidx, $eidx )
	{
		$tmpl['sel'] = '<b>#PageNo#</b>';
		$tmpl['link'] = "<a href='index.php?pn=#PageNo#'>#Caption#</a>";

		echo "<hr>";
		for ( $total = $sidx; $total <= $eidx; $total++ )
		{
			for ( $idx = 1; $idx <= $total; $idx++ )
			{
				echo "w=$w; total=$total; idx=$idx;<br>";
				echo $this->GetPageTabs( $total, $idx, $w, $tmpl['sel'], $tmpl['link'] );
				echo "<hr>";
			}
		}
	}
}

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>