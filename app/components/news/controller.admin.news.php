<?php defined('_JOOS_CORE') or exit();

/**
 * Компонент новостей
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Components\News
 * @subpackage Controllers\Admin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminNews extends joosAdminController{
    
    protected static $submenu = array(
		'default' => array(
			'name' => 'Все новости',
			'model' => 'modelAdminNews',
			'fields' => array('id', 'title', 'created_at', 'state'),
			'active' => false
		),
        'news_types' => array(
            'name' => 'Типы новостей',
            'model' => 'modelAdminNewsTypes',
            'fields' => array('title'),
            'active' => false
        ),
	);

    public static function action_before(){
        
        parent::action_before();
        
        joosDocument::instance()
            ->add_js_file( JPATH_SITE . '/app/components/news/media/js/admin.news.js' );
        
    }
    
    
}