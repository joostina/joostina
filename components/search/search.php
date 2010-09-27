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

require_once ($mainframe->getPath('front_html'));
require_once ($mainframe->getPath('class'));

class actionsSearch {

    public static function index($option, $id, $page = 0, $task = false) {

        // страницы поиска имеют только первый уровень, и номер страницы рассчитывается из иднтификатора
        $page = $id;

        // поисковый текст
        $searchword = strval(mosGetParam($_GET, ':cleanid', ''));
        $searchword = strip_tags($searchword);
        $searchword = $task ? $task : $searchword;
        $searchword = urldecode($searchword);
        $searchword = str_replace(array('"', "'", '\\', '/'), ' ', $searchword);
        $searchword = Jstring::trim(stripslashes($searchword));

        if (Jstring::strlen($searchword) > 100) {
            $searchword = Jstring::substr($searchword, 0, 99);
        }

        if ($searchword && Jstring::strlen($searchword) < 3) {
            $searchword = '';
        }

        (trim($searchword) != '' && $searchword!='index' ) ? self::search($searchword, $page) : searchHTML::index();
    }

    public static function search($searchword, $page = 0) {

        $searchword_clean = htmlspecialchars(stripslashes($searchword), ENT_QUOTES, 'UTF-8');

        mosMainFrame::addLib('doocache');
        $cache = Doo::cache('memcache');

        $key = 'search::' . md5($searchword_clean).  filemtime( __FILE__ );
        $rows = $cache->get($key);

        if ($rows === false) {

            $results_topics = database::getInstance()->setQuery("SELECT id, title,`fulltext` as text, type_id, created_at, anons_image_id, 'topic' AS itemtype,
                'index.php?option=com_topic&task=view&id=' AS href
                FROM #__topics WHERE LOWER(title) LIKE LOWER('%{$searchword_clean}%') OR  LOWER(`fulltext`) LIKE LOWER('%{$searchword_clean}%') ")->loadObjectList();

            $results_games = database::getInstance()->setQuery("SELECT id, title,`desc` as text, date as created_at, image_id,
                'index.php?option=com_games&task=game&id=' AS href, 'game' AS itemtype
                FROM #__games WHERE LOWER(title) LIKE LOWER('%{$searchword_clean}%') OR LOWER(title_rus) LIKE LOWER('%{$searchword_clean}%') OR  LOWER(`desc`) LIKE LOWER('%{$searchword_clean}%') OR  LOWER(`developer`) LIKE LOWER('%{$searchword_clean}%') ")->loadObjectList();


            $results = array(
                0 => $results_topics,
                1 => $results_games
            );

            $rows = array();
            $_n = count($results);
            for ($i = 0, $n = $_n; $i < $n; $i++) {
                $rows = array_merge((array) $rows, (array) $results[$i]);
            }

            $total = count($rows);

            mosMainFrame::addLib('utils');

            for ($i = 0; $i < $total; $i++) {
                $text = &$rows[$i]->text;

                $searchwords = explode(' ', $searchword);
                $needle = $searchwords[0];

                $text = mosPrepareSearchContent($text, 500, $needle);

                foreach ($searchwords as $k => $hlword) {
                    $searchwords[$k] = htmlspecialchars(stripslashes($hlword), ENT_QUOTES, 'UTF-8');
                }

                $searchRegex = implode('|', $searchwords);
                $text = preg_replace('/' . $searchRegex . '/iu', '<span class="highlight">\0</span>', $text);

                $rows[$i]->href = sefRelToAbs($rows[$i]->href . $rows[$i]->id . ':' . $rows[$i]->title);
            }

            @$cache->set($key, (array)$rows, 3600, true);
        }

        $total = count($rows);

        // необходимо для поиска
        $limit = 10;

        mosMainFrame::addLib('paginator3000');
        $pager = new paginator3000(sefRelToAbs('index.php?option=com_search&id=' . $searchword_clean, true), $total, $limit, 5, '&larr;', '&rarr;');
        $pager->paginate($page);

        searchHTML::results($rows, $pager, $limit, $searchword);

        // для первой (0) страницы и если есть результаты поиска - запишем словопоиск в базу, для дальнейших ленивых автокомплитов
        ($total > 0 && $page == 0 ) ? SearchLog::add($searchword) : null;
    }

}