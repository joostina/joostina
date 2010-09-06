<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_VALID_MOS') or die();

function mosPrepareSearchContent($text, $length = 200, $searchword='') {

    $text = preg_replace("'<script[^>]*>.*?</script>'si", "", $text);
    $text = preg_replace('/{.+?}/', '', $text);
    $text = preg_replace("'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text);
    mosMainFrame::addLib('text');
    return mosSmartSubstr(Text::strip_tags_smart($text), $length, $searchword);
}

function mosSmartSubstr($text, $length = 200, $searchword='') {

    $wordpos = Jstring::strpos(Jstring::strtolower($text), Jstring::strtolower($searchword));
    $halfside = intval($wordpos - $length / 2 - Jstring::strlen($searchword));
    if ($wordpos && $halfside > 0) {
        return '...' . Jstring::substr($text, $halfside, $length) . '...';
    } else {
        return Jstring::substr($text, 0, $length);
    }
}

function SortArrayObjects(&$a, $k, $sort_direction = 1) {
    global $csort_cmp;
    $csort_cmp = array('key' => $k, 'direction' => $sort_direction);
    usort($a, 'SortArrayObjects_cmp');
    unset($csort_cmp);
}

function SortArrayObjects_cmp(&$a, &$b) {
    global $csort_cmp;
    if ($a->$csort_cmp['key'] > $b->$csort_cmp['key']) {
        return $csort_cmp['direction'];
    }
    if ($a->$csort_cmp['key'] < $b->$csort_cmp['key']) {
        return - 1 * $csort_cmp['direction'];
    }
    return 0;
}
