<?php

/**
 * News - модуль вывода новостей
 * Вспомогательный класс
 *
 * @version 1.0
 * @package Joostina CMS
 * @subpackage Modules
 * @author JoostinaTeam
 * @copyright (C) 2008-2010 Joostina Team
 * @license see license.txt
 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

joosLoader::admin_model('categories');

class categoriesHelper
{

    public static function get_categories(array $params)
    {

        $limit = isset($params['limit']) ? $params['limit'] : 3;

        $group = $params['group'] ? $params['group'] : 'content';

        $level = $params['level'] ? $params['level'] : 1;

        $cats = new Categories;

        return $cats->get_list(
            array(
                 'select' => "c.id, c.name, c.slug, cd.desc_short, cd.image",
                 'where' => 'c.state=1 AND c.group = "' . $group . '" AND c.level = ' . $level,
                 'limit' => $limit,
                 'order' => 'c.lft',
                 'join' => 'AS c LEFT JOIN #__categories_details AS cd ON (cd.cat_id = c.id)'
            )
        );
    }

}