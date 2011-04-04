<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class IP {
    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */

// полученпие ПОЛНОГО идентификатора IP, если прокси - то и прокси
    public static function get_full_ip() {
        /*         * ****************************************************************************
         *                                                                             *
         * This library will try to get the most probable IP address of an user. It is *
         *   based on a the free of use 'identifier' script written by Marc Meurrens   *
         *                          (http://www.cgsa.net/php)                          *
         *                                                                             *
         * **************************************************************************** */


        // Get some headers that may contain the IP address
        $SimpleIP = (isset($REMOTE_ADDR) ? $REMOTE_ADDR : getenv("REMOTE_ADDR"));

        $TrueIP = (isset($HTTP_X_FORWARDED_FOR) ? $HTTP_X_FORWARDED_FOR : getenv("HTTP_X_FORWARDED_FOR"));
        if ($TrueIP == "")
            $TrueIP = (isset($HTTP_X_FORWARDED) ? $HTTP_X_FORWARDED : getenv("HTTP_X_FORWARDED"));
        if ($TrueIP == "")
            $TrueIP = (isset($HTTP_FORWARDED_FOR) ? $HTTP_FORWARDED_FOR : getenv("HTTP_FORWARDED_FOR"));
        if ($TrueIP == "")
            $TrueIP = (isset($HTTP_FORWARDED) ? $HTTP_FORWARDED : getenv("HTTP_FORWARDED"));
        $GetProxy = ($TrueIP == "" ? "0" : "1");

        if ($GetProxy == "0") {
            $TrueIP = (isset($HTTP_VIA) ? $HTTP_VIA : getenv("HTTP_VIA"));
            if ($TrueIP == "")
                $TrueIP = (isset($HTTP_X_COMING_FROM) ? $HTTP_X_COMING_FROM : getenv("HTTP_X_COMING_FROM"));
            if ($TrueIP == "")
                $TrueIP = (isset($HTTP_COMING_FROM) ? $HTTP_COMING_FROM : getenv("HTTP_COMING_FROM"));
            if ($TrueIP != "")
                $GetProxy = "2";
        };

        if ($TrueIP == $SimpleIP)
            $GetProxy = "0";

// Return the true IP if found, else the proxy IP with a 'p' at the begining
        switch ($GetProxy) {
            case '0':
                // True IP without proxy
                $IP = $SimpleIP;
                $ExternalIP = $SimpleIP;
                break;
            case '1':
                $b = ereg("^([0-9]{1,3}\.){3,3}[0-9]{1,3}", $TrueIP, $IP_array);
                if ($b && (count($IP_array) > 0)) {
                    // True IP behind a proxy
                    $ExternalIP = $SimpleIP;
                    $InternalIP = $IP_array[0];
                    $IP = $SimpleIP . "::" . $IP_array[0];
                } else {
                    // Proxy IP
                    $ExternalIP = $SimpleIP;
                    $IP = "p" . $SimpleIP;
                };
                break;

                break;
            case '2':
                // Proxy IP
                $ExternalIP = $SimpleIP;
                $IP = "p" . $SimpleIP;
                break;
        };

        return $IP;
    }

}