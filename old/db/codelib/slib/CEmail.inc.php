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
// CSmtp
//----------------------------------------------------------------
define( 'CSMTP_TIMEOUT', 40 );

class CSMTP
{
	function Read()
	{
		$s = fgets($this->handle, 1024);
		$b_err = $this->IsSmtpError( $s );
		if ( $b_err ) $this->err_msg = $s;
		$this->WriteLog( "" . $s );
		return ( !$b_err );
	}

	function Write( $s )
	{
		$this->WriteLog( "" . $s );
		fputs( $this->handle, $s );
	}

	function WriteLog( $s )
	{
		$this->log .= $s;
	}

	function GetErrMsg()
	{
		return $this->err_msg;
	}

	function GetLog()
	{
		return $this->log;
	}

	function IsSmtpError( $s )
	{
		$ch = substr( $s, 0, 1 );
		return !(( $ch == '2' ) || ( $ch == '3' ));
	}

	function Run( $params )
	{
		$this->log = '';
		$this->err_msg = '';

		if ( isset( $params['Hostname'] ) )
			$this->hostname = $params['Hostname'];
		else
			$this->hostname = ini_get("SMTP");

		if ( isset( $params['Port'] ) )
			$this->port = $params['Port'];
		else
			$this->port = ini_get("smtp_port");

		if ( isset( $params['Transport'] ) )
			$this->transport = $params['Transport'] . '://';
		else
			$this->transport = '';

		//--- open connection
		$this->handle = @fsockopen( $this->transport . $this->hostname,
			$this->port, $errno, $errstr, CSMTP_TIMEOUT );

		if ( $this->handle === false )
		{
			if ( $errstr == null ) $errstr = '';
			if (( $errno == 0 ) || ( $errstr == '' ))
			{
				$this->err_msg = "Could not connect to mail server [" . $this->hostname . "]";
			}
			else
			{
				$this->err_msg = $errstr . "({$errno})";
			}
			return false;
		}

		//--- process
		$success = $this->Process( $params );

		//--- close  connection
		fclose($this->handle);

		return $success;
	}

	function Process( $params )
	{
		if ( !$this->Read() ) return false;

		$this->Write( "helo " . $this->hostname . "\r\n" );
		if ( !$this->Read() ) return false;

		$from = $params['From'];
		$from0 = $from[0];
		$from_addr = $from0[0];

		$this->Write( "mail from: " . $from_addr . "\r\n" );
		if ( !$this->Read() ) return false;

		//--- [BEGIN] rcpt to
		// You need to do this for every to, cc, and bcc.

		$ls = array( "To", "Cc" , "Bcc" );
		foreach ( $ls as $key )
		{
			if ( isset( $params[$key] ) )
			{
				$ax = $params[$key];
				foreach ( $ax as $to_email )
				{
					$addr = $to_email[0];
					$this->Write( "rcpt to: " . $addr . "\r\n" );
					if ( !$this->Read() ) return false;
				}
			}
		}
		//--- [END] rcpt to

		$this->Write( "data" . "\r\n" );
		if ( !$this->Read() ) return false;

		$this->Write( $params["data"] . "\r\n" . "." .  "\r\n");
		if ( !$this->Read() ) return false;

		//--- quit
		$this->Write( "quit" . "\r\n" );
		if ( !$this->Read() ) return false;

		return true;
	}
}

//----------------------------------------------------------------
// CEmail
//----------------------------------------------------------------
include_once("CMBStr.inc.php");
define( 'HERE_DOC_KEY', "=<<<" );

class CEmail
{
	var $params = array();

	//----------------------------------------------------------------
	// Error Handling
	//----------------------------------------------------------------
	function GetErrMsg()
	{
		return $this->err_msg;
	}

	function DisplaySmtpLog()
	{
		echo "<pre>" . str_replace( "\n", "<br>",
			str_replace( "\r", "", $this->smtp->GetLog() )) . "</pre>";
	}

	//----------------------------------------------------------------
	// ReadEmailAddress
	//----------------------------------------------------------------
	function ReadEmailAddress( $s )
	{
		$s = trim($s);
		$pat = '^([^<]+)<([^>]+)>$';
		mb_regex_encoding( "UTF-8" );
		$ax = array();
		if ( mb_ereg( $pat, $s, $matches ) )
		{
			$ax[] = trim( $matches[2] );
			$ax[] = CMBStr::replace( ",", "\\," , trim( $matches[1] ) );
		}
		else
		{
			$ax[] = $s;
		}
		return $ax;
	}

	//----------------------------------------------------------------
	// Conf File
	//----------------------------------------------------------------
	function OpenConfig( $path, $b_direct_config = false )
	{
		if ( $b_direct_config )
			$txt = $path;
		else
		{
			if ( !file_exists( $path ) )
			{
				echo "Can not find config file : {$path}";
				exit();
			}
			$txt = file_get_contents( $path );
		}

		$txt = CMBStr::replace( "\r\n", "\n", $txt );
		$tx = CMBStr::split( "\n", $txt );
		$params = array();
		$params[ "Encoding" ] = "UTF-8";

		$multi_line_key = "";
		$multi_line_end = "";
		$multi_line = "";
		foreach ( $tx as $ln )
		{
			if ( $multi_line_end != "" )
			{
				if ( trim($ln) == $multi_line_end )
				{
					$params[$multi_line_key] = $multi_line;
					$multi_line_end = "";
				}
				else
					$multi_line .= $ln . "\r\n";
			}
			else
			{
				if ( CMBStr::substr( $ln, 0, 2 ) != "//" )
				{
					$pos = CMBStr::strpos( $ln, HERE_DOC_KEY );
					if ( $pos !== false )
					{
						$multi_line_key = trim( CMBStr::substr( $ln, 0, $pos ));
						$multi_line_end = trim( CMBStr::substr( $ln, $pos+strlen(HERE_DOC_KEY), CMBStr::strlen($ln) ) );
						$multi_line = "";
					}
					else if ( trim( $ln ) != "" )
					{
						$this->split_by_e( $ln, $key, $val );
						if ( $key == "Inherits" )
						{
							$path_x = $this->get_folder_path($path) . $val;
							if ( file_exists( $path_x ) )
								$this->OpenConfig( $path_x );
							else
							{
								echo "Can not find include config file : {$path_x}";
								exit();
							}
						}
						else if ( CMBStr::substr( $key, 0, 1 ) == "!" )
						{
							$this->headers[ substr($key,1) ] = $val;
						}
						else
							$params[$key] = $val;
					}
				}
			}
		}

		$ls = array( "From", "To", "Reply-To", "Cc" , "Bcc", "Attachment" );
		foreach ( $ls as $key )
		{
			if ( isset( $params[$key] ) )
			{
				$ax = CMBStr::split( "\|", $params[$key] );
				$ax2= array();
				foreach ( $ax as $s )
				{
					$s = trim( $s );
					if ( CMBStr::strlen( $s ) > 0 )
					{
						$ax2[] = $this->ReadEmailAddress( $s );
					}
				}
				$params[$key] = $ax2;
			}
		}
		
		foreach ( $params as $key => $val )
		{
			$this->params[$key] = $val;
		}
	}
	
	function DisplayParams( $b_show = true )
	{
		$s = "";
		$s .= "<table border='1'>\r\n";
		foreach ( $this->params as $key => $val )
		{
			$s .= "<tr>\r\n";
			$s .= "<td valign='top'>{$key}</td>\r\n";
			$s .= "<td>\r\n";
			if ( is_array( $val ) )
			{
				$s .= "<table border='1'>\r\n";
				foreach ( $val as $val2 )
				{
					$s .= "<tr><td>\r\n";
					if ( is_array( $val2 ) )
					{
						$s .= "<table border='1'>";
						foreach ( $val2 as $val3 )
						{
							$s .= "<tr><td>{$val3}</td></tr>\r\n";
						}
						$s .= "</table>\r\n";
					}
					else
						$s .= $val2;
					$s .= "</td></tr>\r\n";
				}
				$s .= "</table>\r\n";
			}
			else
			{
				$s .= str_replace("\r\n","<br>",$val);
			}
			$s .= "</td>\r\n";
			$s .= "</tr>\r\n";
		}
		$s .= "</table>\r\n";
		
		if ( $b_show )
			echo $s;
		else
			return $s;
	}

	function GetParam( $key )
	{
		if ( isset( $this->params[$key] ) )
			return $this->params[$key];
		else
			return null;
	}

	function SetParam( $key, $val )
	{
		if ( is_null( $val ) )
			unset( $this->params[$key] );
		else
			$this->params[$key] = $val;
	}

	function AddParam( $key, $x )
	{
		if ( !isset( $this->params[$key] ) )
			$ax = array();
		else
			$ax = $this->params[$key];
		$ax[] = $x;
		$this->params[$key] = $ax;
	}

	function AddHeader( $key, $val )
	{
		$this->headers[$key] = $val;
	}

	//----------------------------------------------------------------
	// Utilities
	//----------------------------------------------------------------
	function split_by_e( $ln, &$key, &$val )
	{
		$pos = CMBStr::strpos( $ln, '=' );
		if ($pos === false)
		{
			$key = trim( $ln );
			$val = '';
		}
		else
		{
			$key = trim( CMBStr::substr( $ln, 0, $pos ) );
			$val = CMBStr::substr( $ln, $pos+1, CMBStr::strlen($ln) );
		}
	}

	function get_folder_path( $path )
	{
		$path_parts = pathinfo( $path );
		$dirname = $path_parts[ 'dirname' ];
		return $dirname . '/';
	}

	function base64_encode_and_split( $s )
	{
		return chunk_split( base64_encode( $s ), 72 );
	}

	function LineEncode( $s )
	{
		$encode = $this->GetEmailEncoding();
		$encode_prefix = "=?" . $encode . "?B?";

		$ret = "";
		$cnt = 0;
		$ch = "";
		$buff = "";
		$line_no = 1;
		$ss = mb_convert_encoding( $s, $encode, $this->GetEncoding() );
		$ss_len = mb_strlen( $ss, $encode );

		for( $i = 0; $i < $ss_len; $i++ )
		{
			$ch = mb_substr( $ss, $i, 1, $encode );
			$cnt += strlen( $ch );
			$buff .= $ch;

			if ( $cnt > 60 )
			{
				if ( $line_no > 1 ) $ret .= "\r\n ";
				$ret .= $encode_prefix . base64_encode($buff) . "?=";
				$line_no++;
				$buff = "";
				$cnt = 0;
			}
		}

		if( $buff != "" )
		{
			if ( $line_no > 1 ) $ret .= "\r\n ";
			$ret .= $encode_prefix . base64_encode( $buff ) . "?=";
		}

		return $ret;
	}

	function GetEncoding()
	{
		return $this->params['Encoding'];
	}
	
	function GetEmailEncoding()
	{
		return "utf-8";
	}

	function GetEmailCharSet()
	{
		//return "iso-8859-1";
		return $this->GetEmailEncoding();
	}

	function NoMultiPart()
	{
		return isset( $this->params['NoMultiPart'] ) && ( $this->params['NoMultiPart'] == "True" );
	}

	function IsAscii( $s )
	{
		mb_regex_encoding ( $this->GetEncoding() );
		return mb_ereg_match( "^[\x20-\x7E]*$", $s );
	}
	
	function CreateAddressLine( $ax )
	{
		$s = "";
		foreach ( $ax as $val )
		{
			if ( $s != '' ) $s .= ", ";

			$addr = $val[0];
			if ( isset( $val[1] ) )
			{
				$name = $val[1];
				if ( $this->IsAscii($name) )
					$s .= $name . " <" . $addr . ">";
				else
					$s .= $this->LineEncode( $name ) . " <" . $addr . ">";
			}
			else
				$s .= $addr;
		}
		
		return $s;
	}

	function DataSig()
	{
		return '//**//';
	}

	function CreateEmailData()
	{
		$data = "";

		$hx = array();

		//--- "From", "To", "Reply-To", "Cc", "Bcc"
		$ls = array( "From", "To", "Reply-To", "Cc", "Bcc" );
		foreach ( $ls as $key )
			if ( isset( $this->params[$key] ) )
				$hx[] = $key . ": " . $this->CreateAddressLine( $this->params[$key] );

		//--- "Subject"
		$hx[] = "Subject: " . $this->LineEncode( $this->params['Subject'] );

		//--- Declear MIME Version
		$hx[] = "MIME-Version: 1.0";

		if ( $this->NoMultiPart() )
		{
			$hx[] = "Content-Type: text/plain;\r\n charset=\"" . $this->GetEmailCharSet() . "\"";
			$hx[] = "Content-Transfer-Encoding: base64";
		}
		else
		{
			//--- Add Mine Boundary Sig. to Header
			$semi_rand = md5( time() ); 
			$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

			//--- Subtype ( alternative or mixed )
			if ( isset( $this->params['Html'] ) )
				$subtype = "alternative";
			else
				$subtype = "mixed";
			$hx[] = "Content-Type: multipart/{$subtype};\r\n boundary=\"{$mime_boundary}\"";
		}

		//--- Additional Headers
		if ( isset( $this->headers ) )
		{
			foreach ( $this->headers as $key => $val )
			{
				if ( $val != '' ) $hx[] = $key . ': ' . $val;
			}
		}

		//--- Combine Header Lines
		$data .= implode( "\r\n", $hx );

		//--- Add a Blank Line
		$data .= "\r\n\r\n";

		//--- Create body

		if ( !$this->NoMultiPart() )
		{
			$data .= "This is a multi-part message in MIME format.\r\n\r\n";
		}

		//--- GetEmailEncoding/Base64

		if ( $this->NoMultiPart() )
		{
			$s = $this->params['Body'];
			$s = mb_convert_encoding( $s, $this->GetEmailEncoding(), $this->GetEncoding() );
			$s = $this->base64_encode_and_split( $s );
			$s .= "\r\n\r\n";

			$data .= $s;
		}
		else
		{
			//--- Text Body
			if ( isset( $this->params['Body'] ) )
			{
				$data .= "--{$mime_boundary}\r\n";
				$data .= "Content-Type: text/plain;\r\n charset=\"" . $this->GetEmailCharSet() . "\"\r\n";
				$data .= "Content-Transfer-Encoding: base64\r\n\r\n";

				$s = $this->params['Body'];
				$s = mb_convert_encoding( $s, $this->GetEmailEncoding(), $this->GetEncoding() );
				$s = $this->base64_encode_and_split( $s );
				$data .= $s;
			}

			//--- Html Body
			if ( isset( $this->params['Html'] ) )
			{
				$data .= "--{$mime_boundary}\r\n";
				$data .= "Content-Type: text/html;\r\n charset=\"" . $this->GetEmailCharSet() . "\"\r\n";
				$data .= "Content-Transfer-Encoding: base64\r\n\r\n";

				$s = $this->params['Html'];
				$s = mb_convert_encoding( $s, $this->GetEmailEncoding(), $this->GetEncoding() );
				$s = $this->base64_encode_and_split( $s );
				$data .= $s;
			}

			//--- Attachment
			if ( isset( $this->params['Attachment'] ) )
			{
				foreach ( $this->params['Attachment'] as $attachment )
				{
					$att_file_path = $attachment[0];
					$att_file_type = $attachment[1];
					$att_file_name = $attachment[2];
					$att_file_name = $this->LineEncode( $att_file_name );

					//--- Open attachment file
					if ( substr( $att_file_path, 0, strlen($this->DataSig())) == $this->DataSig() )
					{
						$filedata = substr( $att_file_path, strlen($this->DataSig()) );
					}
					else
					{
						$file = fopen( $att_file_path, 'rb' ); 
						$filedata = fread( $file, filesize( $att_file_path ) ); 
						fclose( $file );
					}

					//--- Use chunk split
					$filedata = chunk_split( base64_encode( $filedata ) );

					//--- Add file to body
					$data .= "--{$mime_boundary}\r\n";
					$data .= "Content-Type: {$att_file_type};\r\n";
					$data .= " name=\"{$att_file_name}\"\r\n";
					$data .= "Content-Disposition: attachment;\r\n";
					$data .= " filename=\"{$att_file_name}\"\r\n";
					$data .= "Content-Transfer-Encoding: base64\r\n\r\n";
					$data .= $filedata . "\r\n\r\n";
				}
			}

			//-- Final Boundary ( there should be "--" at the end )
			$data .= "--{$mime_boundary}--\r\n";
		}
		
		return $data;
	}

	//----------------------------------------------------------------
	// Send
	//----------------------------------------------------------------
	function Send()
	{
		$this->AddHeader( 'Date', date( "D, d M Y H:i:s O" ) );

		$this->err_msg = '';

		switch ( 2 )
		{
		case 1://--- [ PHP Mail() ]
			//$from_addr = $params['from_addr'];
			//$to_addr = $params['to_addr'];
			//$this->err_msg = sendmail_by_mail( $to, $from, $subject, $body, $hx );
			break;
			
		case 2://--- [ TCP/IP Socket ]
			$this->params['data'] = $this->CreateEmailData( $this->params );
			$this->smtp = new CSmtp();
			$this->smtp->Run( $this->params );
			$this->err_msg = $this->smtp->GetErrMsg();
			break;
		}

		return ( $this->err_msg == '' ); 
	}
}

//----------------------------------------------------------------
// Sample Usage
//----------------------------------------------------------------
/*

	$mail = new CEmail();

	$mail->OpenConfig( "config/test_config.txt" );
	$mail->OpenConfig( "config/email_config.txt" );
	$mail->OpenConfig( "config/email_config2.txt" );

	$mail->AddParam( 'To', array( "sender@venus", "Mr, Foo" ) );
	$mail->AddParam( 'Cc', array( "sender@venus", "Mr, Foo" ) );
	$mail->AddParam( 'Bcc', array( "sender@venus", "Mr, Foo" ) );
	$mail->AddParam( 'Reply_To', array( "ppp@venus", "Mr, PPP" ) );
	$mail->SetParam( 'Html', "<html><h1>Hello</h1><font color='#ff0000'>Red</font></html>" );

	$mail->AddParam( 'Attachment',
		array(
			'C:\test1.doc',
			"application/octet-stream",
			"doc1.doc"
		)
	);

	$mail->DisplayParams();

	if ( !$email->Send() ) 
	{
		echo "EMAIL ERROR : <br>" . $email->GetErrMsg() . "<hr>";
		die;
	}

	$mail->DisplaySmtpLog();

*/

//----------------------------------------------------------------
// END OF FILE
//----------------------------------------------------------------
?>