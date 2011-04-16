<?php
/**
 * Последние сообщения с форума
 * Вспомагательный класс
 *
 * */

//Запрет прямого доступа
defined('_JOOS_CORE') or die();

class forum_latestHelper
{

    //Получение последних записей
    public static function get_latest($params)
    {
        $limit = isset($params['limit']) ? $params['limit'] : 4;

        joosLoader::lib('simplepie');

        $feed = new SimplePie($params['url']);
        $feed->enable_cache(true);
        $feed->set_cache_duration(1800);
        $feed->set_cache_location(JPATH_BASE . DS . 'cache');

        $feed->init();
        $feed->handle_content_type();

        return $feed->get_items(0, $limit);
    }

}