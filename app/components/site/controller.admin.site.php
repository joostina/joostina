<?php defined('_JOOS_CORE') or die();

/**
 * Компонент для управления и конфигурирования системы
 * Контроллер панели управления
 * 
 * @version    1.0
 * @package    Components\Site
 * @subpackage Controllers\Admin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminSite extends joosAdminController{

    public static function  action_before(){}
    
    public static function index() {

        require_once JPATH_BASE . '/app/templates/' . JTEMPLATE_ADMIN . '/html/cpanel.php';
        
        return array();
    }
    
}