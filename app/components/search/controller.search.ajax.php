<?php

defined('_JOOS_CORE') or die();

/**
 * Компонент Вывода результатов поиска
 * Контроллер ajax - функций
 *
 * @version    1.0
 * @package    Components\Search
 * @subpackage Controllers\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAjaxSearch extends joosControllerAjax
{
    public static function autocomplete()
    {
        $word = joosRequest::get('term');
        joosFilter::make_safe($word);

        // если пользователь ввёл меньше 2х символов - не будем выдавать ему подсказку
        if (strlen($word) > 2) {
            $result = modelSearch::get_log($word);
        } else {
            $result = false;
        }

        echo json_encode($result);
    }

}
