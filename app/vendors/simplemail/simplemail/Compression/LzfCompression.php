<?php
require_once ('iCompression.php');
/**
 * Use the Lzf Comperssion
 *
 * @package Compression
 * @filesource LzfCompression.php
 *
 * @author Cyril Nicodème
 * @version 0.1
 *
 * @since 15/07/2008
 *
 * @license GNU/GPL
 */
class LzfCompression implements iCompression {
	/**
	 * Constructor
	 * Used to check if the extensions exists
	 *
	 * @throws Exception
	 */
	public function __construct () {
		if (!function_exists ('lzf_compress'))
			throw new Exception ('Lzf extensions is missing');
	}

	/**
	 * Compress the given value to the specific compression
	 *
	 * @param String $sValue
	 * @param String $iLevel (Optionnal) : Between 0 and 9
	 *
	 * @return String
	 * 
	 * @throws Exception
	 */
	public function compress ($sValue, $iLevel = null) {
		if (!is_string ($sValue))
			throw new Exception ('Invalid first argument, must be a string');

		return lzf_compress ($sValue);
	}

	/**
	 * Decompress the given value with the specific compression
	 *
	 * @param String $sValue
	 *
	 * @return String
	 *
	 * @throws Exception
	 */
	public function decompress ($sValue) {
		if (!is_string ($sValue))
			throw new Exception ('Invalid first argument, must be a string');

		return lzf_decompress ($sValue);
	}
}
?>