<?php defined('_JOOS_CORE') or exit;

/**
 * Компонент - главная страница
 * Позволяет использовать 2 типа страниц:
 * 1. Компонент - выбирается любой доступный компонент системы и настройки для него
 * 2. Страница модулей - позволяет расположить конструкцию составленную из произвольных модулей по специально подготовленному макету
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Components\Mainpage
 * @subpackage Controllers\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsMainpage extends joosController
{
    public function index()
    {
        //Хлебные крошки
        //joosBreadcrumbs::instance()
        //->add('Главная', $active_task == 'mainpage' ? false : JPATH_SITE);

        return array ( 'task' => 'modules' );
    }

}
