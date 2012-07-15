<?php
/**
 * Interface for the Compression Objects
 *
 * @package Compression
 * @filesource iCompression.php
 *
 * @author Cyril Nicodème
 * @version 0.1
 *
 * @since 15/07/2008
 *
 * @license GNU/GPL
 */
interface iCompression
{
    /**
     * Constructor
     * Used to check if the extensions exists
     *
     * @throws Exception
     */
    public function __construct ();

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
    public function compress ($sValue, $iLevel = null);

    /**
     * Decompress the given value with the specific compression
     *
     * @param String $sValue
     *
     * @return String
     *
     * @throws Exception
     */
    public function decompress ($sValue);
}
