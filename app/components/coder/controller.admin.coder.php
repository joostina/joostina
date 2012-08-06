<?php defined('_JOOS_CORE') or exit;

/**
 * Компонент управляемой генерации расширений системы
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Components\Coder
 * @subpackage Controllers\Admin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminCoder  extends joosAdminController
{
    public $submenu = array(

        'default' => array(
            'name' => 'Генератор моделей',
            'model' => 'modelAdminCoder',
            'active' => false
        ),

        'code_generator' => array(
            'name' => 'Генератор контроллеров',
            'href' => 'index2.php?option=coder&task=code_generator',
            'active' => false
        ),

        'autoload_generator' => array(
            'name' => 'Генератор файла автозагрузки классов',
            'href' => 'index2.php?option=coder&task=autoload_generator',
            'active' => false
        ),

        'db_faker' => array(
            'name' => 'Генератор тестовых данных',
            'href' => 'index2.php?option=coder&task=faker',
            'model' => 'modelAdminCoder_Faker',
            'active' => false
        ),

    );

    public function action_before()
    {
        joosDocument::instance()
            ->add_css( JPATH_SITE . '/media/js/jquery.plugins/syntax/jquery.snippet.css' )
            ->add_js_file( JPATH_SITE . '/media/js/jquery.plugins/syntax/jquery.snippet.js' )
            ->add_js_file(JPATH_SITE . '/app/components/coder/media/js/coder.js');

        joosAdminView::set_param( 'component_title' ,  'Кодер');

    }

    public function action_after()
    {
        joosAdminView::set_param('submenu', self::get_submenu() );

    }

    public function index()
    {
        self::$submenu['default']['active'] = true;

        $tables = joosDatabase::instance()
            ->get_utils()
            ->get_table_list();

        return array(
            'tables' => $tables
        );
    }

    public function faker()
    {
        self::$submenu['db_faker']['active'] = true;
        $tables = joosDatabase::instance()->get_utils()->get_table_list();

        return array(
            'tables' => $tables
        );
    }

    public function code_generator()
    {
        self::$submenu['code_generator']['active'] = true;

        return array();
    }

    public function autoload_generator()
    {
        $classes = joosRobotLoader::get_classes( JPATH_BASE );
        $body = var_export($classes,true);

        return array(
          'body'=> $body
        );

    }

}
