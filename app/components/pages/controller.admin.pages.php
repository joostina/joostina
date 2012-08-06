<?php defined('_JOOS_CORE') or exit;

/**
 * Компонент управления независимыми страницами
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Components\Pages
 * @subpackage Controllers\Admin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminPages  extends joosAdminController
{
    public $submenu = array(
        'default' => array(
            'name' => 'Все страницы',
            'model'=>'modelAdminPages',
            'href' => 'index2.php?option=pages',
            'fields'=>array('title','created_at', 'state'),
            'active' => false
        ),
    );

    public function action_before()
    {
        parent::action_before();

        joosDocument::instance()
            ->add_js_file( JPATH_SITE . '/app/components/pages/media/js/pages.admin.js' );

    }

}
