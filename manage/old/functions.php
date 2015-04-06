<?php
/**
 *This method will convert the passed String plus the 
 * two salts, one placed at the beggining and one at
 * the end, into 40 char hexadecimal String
 * @param (String)  example: password
 * @return (String) The hexadecimal representation of the passed String + 2 salts
 */
function encrypt_password($str){
	$salt1 = "y%r!";
	$salt2 = "wq*&";
	return sha1($salt1.$str.$salt2);
}

?>