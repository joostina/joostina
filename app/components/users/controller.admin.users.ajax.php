<?php

/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Компонент управления пользователями
 * Контроллер панели управления ajax
 *
 * @version    1.0
 * @package    Controllers
 * @subpackage Users
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAjaxAdminUsers extends joosAdminControllerAjax {


    public static function action_before() {
        joosLoader::model('acls');
        joosLoader::admin_model('acls');
    }

    public static function status_change() {
        return joosAutoadmin::autoajax();
    }

    /**
     * Смена статуса доступа группы к действию
     *
     * @return array
     */
    public static function change() {

        $group_id = joosRequest::int('group_id');
        $task_id = joosRequest::int('task_id');

        $access = new modelAclAccess;

        $access->group_id = $group_id;
        $access->task_id = $task_id;

        $access->find();
        if ($access->id) {
            $access->delete($access->id);
        } else {
            $access->store();
        }


        return array(
            'success' => true,
            'body' => 555
        );
    }

}