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

require_once ($mainframe->getPath('front_html'));
require_once ($mainframe->getPath('class'));

class actionsBookmarks {

    //Показываем закладки определенного пользователя
    public static function index($option, $id, $page, $task) {

        require_once mosMainFrame::getInstance()->getPath('class', 'com_topic');

/* TODO - Аааа, никак не могу это сделать ((
        // ищем топики по алисам категорий и типов
        if ($task != 'index' && trim($task) != '') {
            $types = Topic::get_types_cat_alias(true);

            if (isset($types[$task])) {
                self::cat($option, $types[$task], $page, $task);
                return;
            }

            mosRedirect(JPATH_SITE, 'Нету такой страницы еще...');
        }
*/

        $user = new User;
        if (!$user->load($id)) {
            mosRedirect(JPATH_SITE, 'Пользователь не найден');
        }


        $bookmarks = new Bookmarks;
        $bookmarks_count = $bookmarks->count(sprintf("WHERE user_id = %s AND obj_option='topic' AND obj_task='all' ", $id));

        mosMainFrame::addLib('paginator3000');
        $pager = new paginator3000(sefRelToAbs('index.php?option=bookmarks&id=' . $_GET[':fullid'], true), $bookmarks_count, 3, 10);
        $pager->paginate($page);

        $topics = new Topic;

        $param = array(
            'select' => 'topic.*,
						cc.counter AS comment_count, 
						game.title AS gamename, game.ganres_hrefs, 
						bookmcounter.counter AS bookmarks_count, 
						votesresults.votes_count AS votesresults',
            'join' => " AS topic LEFT JOIN #__comments_counter AS cc ON (cc.obj_id=topic.id AND cc.obj_option='Topic' ) "
            . " INNER JOIN #__bookmarks AS bm ON ( bm.obj_id = topic.id  AND bm.obj_option='topic' AND bm.obj_task='all' ) "
            . " LEFT JOIN #__games AS game ON game.id=topic.game_id "
            . " LEFT JOIN #__bookmarks_counter AS bookmcounter ON ( bookmcounter.obj_id=topic.id AND bookmcounter.obj_option = 'topic' AND bookmcounter.obj_task ='all') "
            . " LEFT JOIN #__votes_topic_results AS votesresults ON ( votesresults.obj_id=topic.id ) ",
            'offset' => $pager->offset,
            'limit' => $pager->limit,
            'order' => 'id DESC',
            'where' => " bm.user_id = " . $user->id
        );

        $bookmarks_list = $topics->get_list($param);

        bookmarksHTML::index($user, $bookmarks_list, $pager);
    }

    public static function gametrace($option, $id, $page) {

        require_once ( mosMainFrame::getInstance()->getPath('class', 'com_games'));
        $game = new Games;
        $game->load($id) ? null : mosRedirect(JPATH_SITE, 'Такой игры не существует');

        $bookmarks = new Bookmarks;
        $bookmarks_count = $bookmarks->count(sprintf("WHERE obj_id = %s AND obj_option='games_trace'", $id));

        mosMainFrame::addLib('paginator3000');
        $pager = new paginator3000(sefRelToAbs('index.php?option=bookmarks&task=gametrace&id=' . $_GET[':fullid'], true), $bookmarks_count, 3, 10);
        $pager->paginate($page);

        $param = array(
            'select' => 'bookmarks.user_id, bookmarks.created_at, users.id, users.lastvisitDate, users.username, urate.full_rate',
            'offset' => $pager->offset,
            'limit' => $pager->limit,
            'order' => 'id DESC',
            'join' => " AS bookmarks 
						INNER JOIN #__users AS users ON bookmarks.user_id = users.id 
						LEFT JOIN #__users_ratings as urate on urate.user_id = bookmarks.user_id",
            'where' => "bookmarks.obj_option='games_trace' AND obj_id=" . $id." GROUP BY bookmarks.user_id"
        );

        $users = $bookmarks->get_list($param);

        bookmarksHTML::gametrace($users, $pager, $game);
    }

    public static function gamelove($option, $id, $page) {

        require_once ( mosMainFrame::getInstance()->getPath('class', 'com_games'));
        $game = new Games;
        $game->load($id) ? null : mosRedirect(JPATH_SITE, 'Такой игры не существует');

        $bookmarks = new Bookmarks;
        $bookmarks_count = $bookmarks->count(sprintf("WHERE obj_id = %s AND obj_option='games_love'", $id));

        mosMainFrame::addLib('paginator3000');
        $pager = new paginator3000(sefRelToAbs('index.php?option=bookmarks&task=gamelove&id=' . $_GET[':fullid'], true), $bookmarks_count, 3, 10);
        $pager->paginate($page);

        $param = array(
            'select' => 'bookmarks.user_id, bookmarks.created_at, users.id, users.lastvisitDate, users.username, urate.full_rate',
            'offset' => $pager->offset,
            'limit' => $pager->limit,
            'order' => 'id DESC',
            'join' => " AS bookmarks 
						INNER JOIN #__users AS users ON bookmarks.user_id = users.id 
						LEFT JOIN #__users_ratings as urate on urate.user_id = bookmarks.user_id",
            'where' => "bookmarks.obj_option='games_love' AND obj_id=" . $id." GROUP BY bookmarks.user_id"
        );

        $users = $bookmarks->get_list($param);

        bookmarksHTML::gamelove($users, $pager, $game);
    }

    public static function cat($option, $id, $page, $task) {

        _xdump($_GET);

        echo $task;

        echo 555555555;
        die();

        // уличная магия
        $page = (int) mosGetParam($_GET, 'id', 0);

        $obj = new Topic;
        $obj_count = $obj->count('WHERE state=1 AND type_id=' . $id);

        $type_name = Topic::get_types();
        $type_name = $type_name[$id][0];

        mosMainFrame::addLib('paginator3000');
        $pager = new paginator3000(sefRelToAbs('index.php?option=topic&task=' . $task, true), $obj_count, 15, 10);
        $pager->paginate($page);

        $param = array(
            'select' => 'topic.*,cc.counter AS comment_count, game.title AS gamename, game.ganres_hrefs, bookmcounter.counter AS bookmarks_count, votesresults.votes_count AS votesresults',
            'join' => " AS topic LEFT JOIN #__comments_counter AS cc ON (cc.obj_id=topic.id AND cc.obj_option='Topic') "
            . " INNER JOIN #__games AS game ON game.id=topic.game_id "
            . " LEFT JOIN #__bookmarks_counter AS bookmcounter ON ( bookmcounter.obj_id=topic.id AND bookmcounter.obj_option = 'topic' AND bookmcounter.obj_task ='all') "
            . " LEFT JOIN #__votes_topic_results AS votesresults ON ( votesresults.obj_id=topic.id ) ",
            'where' => 'topic.state=1 AND topic.type_id=' . $id,
            'offset' => $pager->offset,
            'limit' => $pager->limit,
            'order' => 'id DESC'
        );

        $obj_list = $obj->get_list($param);

        // определяем задачю - вьюшку для записей
        $first = pos($obj_list);
        $view = Topic::get_types_cat();
        $task = isset($view[$first->type_cat_id][1]) ? $view[$first->type_cat_id][1] : mosRedirect(JPATH_SITE, 'Той страницы небыло');

        bookmarksHTML::index($user, $bookmarks_list, $pager);
    }

}