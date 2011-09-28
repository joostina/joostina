<?php


function remove_xss( $string ) {
	// Remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
	// This prevents some character re-spacing such as <java\0script>
	// Note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
	$string = preg_replace( '/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/' , '' , $string );

	// Straight replacements, the user should never need these since they're normal characters
	// This prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
	$search       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()~`";:?+/={}[]-_|\'\\';
	$search_count = count( $search );
	for ( $i      = 0; $i < $search_count; $i++ ) {
		// ;? matches the ;, which is optional
		// 0{0,7} matches any padded zeros, which are optional and go up to 8 chars
		// &#x0040 @ search for the hex values
		$string = preg_replace( '/(&#[xX]0{0,8}' . dechex( ord( $search[$i] ) ) . ';?)/i' , $search[$i] , $string ); // with a ;
		// &#00064 @ 0{0,7} matches '0' zero to seven times
		$string = preg_replace( '/(&#0{0,8}' . ord( $search[$i] ) . ';?)/' , $search[$i] , $string ); // with a ;
	}

	// Now the only remaining whitespace attacks are \t, \n, and \r
	$ra       = array ( 'javascript' , 'vbscript' , 'expression' , 'applet' , 'meta' , 'xml' , 'blink' , 'style' , 'script' , 'embed' , 'object' , 'iframe' , 'frame' , 'frameset' , 'ilayer' , 'layer' , 'bgsound' , 'title' , 'link' , 'base' , 'onabort' , 'onactivate' , 'onafterprint' , 'onafterupdate' , 'onbeforeactivate' , 'onbeforecopy' , 'onbeforecut' , 'onbeforedeactivate' , 'onbeforeeditfocus' , 'onbeforepaste' , 'onbeforeprint' , 'onbeforeunload' , 'onbeforeupdate' , 'onblur' , 'onbounce' , 'oncellchange' , 'onchange' , 'onclick' , 'oncontextmenu' , 'oncontrolselect' , 'oncopy' , 'oncut' , 'ondataavailable' , 'ondatasetchanged' , 'ondatasetcomplete' , 'ondblclick' , 'ondeactivate' , 'ondrag' , 'ondragend' , 'ondragenter' , 'ondragleave' , 'ondragover' , 'ondragstart' , 'ondrop' , 'onerror' , 'onerrorupdate' , 'onfilterchange' , 'onfinish' , 'onfocus' , 'onfocusin' , 'onfocusout' , 'onhelp' , 'onkeydown' , 'onkeypress' , 'onkeyup' , 'onlayoutcomplete' , 'onload' , 'onlosecapture' , 'onmousedown' , 'onmouseenter' , 'onmouseleave' , 'onmousemove' , 'onmouseout' , 'onmouseover' , 'onmouseup' , 'onmousewheel' , 'onmove' , 'onmoveend' , 'onmovestart' , 'onpaste' , 'onpropertychange' , 'onreadystatechange' , 'onreset' , 'onresize' , 'onresizeend' , 'onresizestart' , 'onrowenter' , 'onrowexit' , 'onrowsdelete' , 'onrowsinserted' , 'onscroll' , 'onselect' , 'onselectionchange' , 'onselectstart' , 'onstart' , 'onstop' , 'onsubmit' , 'onunload' );
	$ra_count = count( $ra );

	$found    = true; // Keep replacing as long as the previous round replaced something
	while ( $found == true ) {
		$string_before = $string;
		for ( $i       = 0; $i < $ra_count; $i++ ) {
			$pattern = '/';
			for ( $j = 0; $j < strlen( $ra[$i] ); $j++ ) {
				if ( $j > 0 ) {
					$pattern .= '((&#[xX]0{0,8}([9ab]);)||(&#0{0,8}([9|10|13]);))*';
				}
				$pattern .= $ra[$i][$j];
			}
			$pattern .= '/i';
			$replacement = ''; //substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
			$string = preg_replace( $pattern , $replacement , $string ); // filter out the hex tags
			if ( $string_before == $string ) {
				// no replacements were made, so exit the loop
				$found = false;
			}
		}
	}
	return $string;
} // remove_xss

/**
 * Prevent some basic XSS attacks, filters arrays
 */
function cleanArrayXSS( $ar ) {
	$ret = array ();

	foreach ( $ar as $k => $v ) {
		if ( is_array( $k ) ) {
			$k = cleanArrayXSS( $k );
		} else {
			$k = remove_xss( $k );
		}

		if ( is_array( $v ) ) {
			$v = cleanArrayXSS( $v );
		} else {
			$v = remove_xss( $v );
		}

		$ret[$k] = $v;
	}

	return $ret;
}

/**
 * Prevent some basic XSS attacks
 */
function cleanXSS() {
	$in = array ( &$_GET , &$_COOKIE , &$_SERVER ); //, &$_POST);

	while ( list( $k , $v ) = each( $in ) ) {
		foreach ( $v as $key => $val ) {
			$oldkey = $key;

			if ( !is_array( $val ) ) {
				$val = remove_xss( $val );
			} else {
				$val = cleanArrayXSS( $val );
			}

			if ( !is_array( $key ) ) {
				$key = remove_xss( $key );
			} else {
				$key = cleanArrayXSS( $key );
			}

			unset( $in[$k][$oldkey] );
			$in[$k][$key] = $val;
			continue;
			$in[] =& $in[$k][$key];
		}
	}
	unset( $in );
	return;
}
