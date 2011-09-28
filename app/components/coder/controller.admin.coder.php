<?php
// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();

/**
 * Coder - Компонент управляемой генерации расширений системы
 * Контроллер панели управления
 *
 * @version    1.0
 * @package    Joostina.Components.Controllers
 * @subpackage Coder
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2011 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAdminCoder {

	public static $submenu = array ( 'model_generator'     => array ( 'name'   => 'Генератор моделей' ,
	                                                                  'href'   => 'index2.php?option=coder' ,
	                                                                  'active' => false ) ,
	                                 'component_generator' => array ( 'name'   => 'Генератор компонента' ,
	                                                                  'href'   => 'index2.php?option=coder&task=componenter' ,
	                                                                  'active' => false ) ,
	                                 'db_faker'            => array ( 'name'   => 'Генератор тестовых данных' ,
	                                                                  'href'   => 'index2.php?option=coder&task=faker' ,
	                                                                  'active' => false ) , );

	public static function action_before() {
		joosDocument::instance()->add_js_file( JPATH_SITE . '/app/components/coder/media/js/coder.js' );
	}

	public static function index() {

		//Установка подменю
		self::$submenu['model_generator']['active'] = true;

		echo joosAutoadmin::header( 'Кодер' , self::$submenu['model_generator']['name'] );


		$rets   = array ();
		$rets[] = '<table class="adminlist"><tbody><tr><th>Таблицы</th><th>Код моделей</th></tr></tbody><tr>';
		$rets[] = '<td width="200" valign="top">';

		$rets[] = forms::open( '#' , array ( 'id' => 'coder_form' ) );
		$tables = joosDatabase::instance()->get_utils()->get_table_list();
		foreach ( $tables as $value ) {
			$el_id  = 'table_' . $value;
			$rets[] = forms::checkbox( 'codertable[]' , $value , false , 'id="' . $el_id . '" ' );
			$rets[] = forms::label( $el_id , $value );
			$rets[] = '<br />';
		}
		$rets[] = forms::close();
		$rets[] = '</td><td valign="top">';
		$rets[] = '<div id="coder_results" /></div>';
		$rets[] = '</td>';
		$rets[] = '</tr></table>';

		echo implode( "\n" , $rets );

		echo joosAutoadmin::footer();
	}

	public static function faker( $option ) {
		//Установка подменю
		self::$submenu['db_faker']['active'] = true;

		echo joosAutoadmin::header( 'Кодер' , self::$submenu['db_faker']['name'] );


		$rets   = array ();
		$rets[] = '<table class="adminlist"><tbody><tr><th>Таблицы</th><th>Правила заполнения</th></tr></tbody><tr>';
		$rets[] = '<td width="200" valign="top">';

		$rets[] = forms::open( '#' , array ( 'id' => 'faker_form' ) );
		$tables = joosDatabase::instance()->get_utils()->get_table_list();
		foreach ( $tables as $value ) {
			$el_id  = 'table_' . $value;
			$rets[] = forms::radio( 'fakertable[]' , $value , false , 'id="' . $el_id . '" ' );
			$rets[] = forms::label( $el_id , $value );
			$rets[] = '<br />';
		}
		$rets[] = forms::close();
		$rets[] = '</td><td valign="top">';
		$rets[] = '<div id="faker_results" /></div>';
		$rets[] = '</td>';
		$rets[] = '</tr></table>';

		echo implode( "\n" , $rets );

		echo joosAutoadmin::footer();
	}

	public static function componenter( $option ) {

		//Установка подменю
		self::$submenu['component_generator']['active'] = true;

		//Установка заголовка
		echo joosAutoadmin::header( 'Кодер' , self::$submenu['db_faker']['name'] );
		?>
	<table class="adminlist">
		<tr>
			<th>Описание</th>
			<th>Код компонента</th>
		</tr>
		<tr>
			<td width="200">
				<?php echo forms::open( '#' , array ( 'id' => 'componenter_form' ) ); ?>
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