<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();


class thisHTML {

    function index( $articles, $article_list, $pagenav) {
        $fields_list = array( 'id','title','type_id','game_id','created_at','state');
        JoiAdmin::listing( $articles, $article_list, $pagenav, $fields_list );
    }

    function edit( $articles_obj, $articles_data ) {
        JoiAdmin::edit($articles_obj, $articles_data);
    }

}
