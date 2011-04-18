<?php
/**
 * Search - Компонент поиска
 * Аякс-контроллер админ-панели
 *
 * @version 1.0
 * @package Joostina.Components
 * @subpackage Search
 * @author Joostina Team <info@joostina.ru>
 * @copyright (C) 2008-2011 Joostina Team
 * @license MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 **/

// запрет прямого доступа
defined('_JOOS_CORE') or die();

joosLoader::lib('joiadmin', 'system');
joosAutoAdmin::dispatch_ajax();
joosAutoAdmin::autoajax();

class actionsAjaxSearch
{

    public static function on_start()
    {
        require joosLoader::admin_model('search');
    }

    public static function index()
    {

    }

}