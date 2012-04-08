<?php
// запрет прямого доступа
defined('_JOOS_CORE') or die();

/**
 * Компонент управляемой генерации расширений системы
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Controllers
 * @subpackage Coder
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminCoder  extends joosAdminController{

	public static $submenu = array(
        'default' => array(
            'name' => 'Генератор моделей',
            'model' => 'modelAdminCoder',
            'active' => false
        ),

		'component_generator' => array(
            'name' => 'Генератор компонента',
			'href' => 'index2.php?option=coder&task=componenter',
			'active' => false
		),

		'db_faker' => array(
            'name' => 'Генератор тестовых данных',
			'href' => 'index2.php?option=coder&task=faker',
            'model' => 'modelAdminCoder_Faker',
			'active' => false
		),
	);

	public static function action_before() {
		joosDocument::instance()
            ->add_css( JPATH_SITE . '/media/js/jquery.plugins/syntax/jquery.snippet.css' )
            ->add_js_file( JPATH_SITE . '/media/js/jquery.plugins/syntax/jquery.snippet.js' )
		    ->add_js_file(JPATH_SITE . '/app/components/coder/media/js/coder.js');
        joosAdminView::set_param( 'component_title' ,  'Кодер');
	}

    public static function action_after() {
        joosAdminView::set_param('submenu', self::get_submenu() );
    }


	public static function index() {
		self::$submenu['default']['active'] = true;
        $tables = joosDatabase::instance()->get_utils()->get_table_list();
        return array('tables' => $tables);
	}

	public static function faker($option) {
		self::$submenu['db_faker']['active'] = true;
        $tables = joosDatabase::instance()->get_utils()->get_table_list();
        return array('tables' => $tables);
	}

	public static function componenter($option) {

		//Установка подменю
		self::$submenu['component_generator']['active'] = true;

		//Установка заголовка
		echo joosAutoadmin::header('Кодер', self::$submenu['db_faker']['name']);
		?>
		<table class="adminlist">
			<tr>
				<th>Описание</th>
				<th>Код компонента</th>
			</tr>
			<tr>
				<td width="200">
					<?php echo forms::open('#', array('id' => 'componenter_form')); ?>
					<label for="">Имя компонента:</label><br/>
					<input type="text" name="component_name" class="text" value="news"/>
					<br/><br/>

					<label for="">Заголовок компонента:</label><br/>
					<input type="text" name="component_title" class="text" value="Новости"/>
					<br/><br/>

					<label for="">Описание компонента:</label><br/>
					<input type="text" name="component_desc" class="text" value="Компонент новостей"/>
					<br/><br/>

					<label for="">Автор:</label><br/>
					<input type="text" name="component_author" class="text" value="Joostina Team"/>
					<br/><br/>

					<label for="">Email:</label><br/>
					<input type="text" name="component_authoremail" class="text" value="info@joostina.ru"/>
					<br/><br/>
					<?php echo forms::close(); ?>
					<button id="create_component">Сгенерировать</button>
				</td>
				<td>
					<div id="componenter_results"></div>
				</td>
			</tr>
		</table>
		<?php
		echo joosAutoadmin::footer();
	}

}