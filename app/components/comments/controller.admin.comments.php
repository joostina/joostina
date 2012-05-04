<?php defined('_JOOS_CORE') or die();

/**
 * Компонент Комментарии Добавляет возможность комментирования объектов
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Components\Comments
 * @subpackage Controllers\Admin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminComments extends joosAdminController{

    protected static $submenu = array(
        'default' => array(
            'name' => 'Все новости',
            'model' => ':model_name',
            'fields' => array('id', 'title', 'created_at', 'state'),
            'active' => false
        ),

    );

}