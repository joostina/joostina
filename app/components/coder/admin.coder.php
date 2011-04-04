<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

class actionsCoder {

	public static $submenu = array(
		'model_generator' => array(
			'name' => 'Моделегенератор',
			'href' => 'index2.php?option=coder',
			'active' => false
		),
		'db_faker' => array(
			'name' => 'Базадурьюзаполнялка',
			'href' => 'index2.php?option=coder&task=faker',
			'active' => false
		),
		'component_generator' => array(
			'name' => 'Компонентогенератор',
			'href' => 'index2.php?option=coder&task=componenter',
			'active' => false
		)
	);

	public static function on_start() {
		joosLoader::lib('forms');
		joosDocument::instance()->add_js_file(JPATH_SITE . '/app/components/coder/media/js/coder.js');
		joosLoader::admin_model('coder');
	}

	public static function index() {
		
		//Установка подменю
		self::$submenu['model_generator']['active'] = true;
		
		echo JoiAdmin::header( 'Кодер', 'Моделегенератор');

		

		$rets = array();
		$rets[] = '<table class="adminlist"><tbody><tr><th>Таблицы</th><th>Код моделей</th></tr></tbody><tr>';
		$rets[] = '<td width="200" valign="top">';

		$rets[] = forms::open('#', array('id' => 'coder_form'));
		$tables = database::instance()->get_utils()->get_table_list();
		foreach ($tables as $key => $value) {
			$el_id = 'table_' . $value;
			$rets[] = forms::checkbox('codertable[]', $value, false, 'id="' . $el_id . '" ');
			$rets[] = forms::label($el_id, $value);
			$rets[] = '<br />';
		}
		$rets[] = forms::close();
		$rets[] = '</td><td valign="top">';
		$rets[] = '<div id="coder_results" /></div>';
		$rets[] = '</td>';
		$rets[] = '</tr></table>';

		echo implode("\n", $rets);
		
		echo JoiAdmin::footer();
	}

	public static function faker($option) {
		//Установка подменю
		self::$submenu['db_faker']['active'] = true;
		
		echo JoiAdmin::header( 'Кодер', 'БазаДурьюЗаполнялка');
		

		$rets = array();
		$rets[] = '<table class="adminlist"><tbody><tr><th>Таблицы</th><th>Правила заполнения</th></tr></tbody><tr>';
		$rets[] = '<td width="200" valign="top">';

		$rets[] = forms::open('#', array('id' => 'faker_form'));
		$tables = database::instance()->get_utils()->get_table_list();
		foreach ($tables as $key => $value) {
			$el_id = 'table_' . $value;
			$rets[] = forms::radio('fakertable[]', $value, false, 'id="' . $el_id . '" ');
			$rets[] = forms::label($el_id, $value);
			$rets[] = '<br />';
		}
		$rets[] = forms::close();
		$rets[] = '</td><td valign="top">';
		$rets[] = '<div id="faker_results" /></div>';
		$rets[] = '</td>';
		$rets[] = '</tr></table>';

		echo implode("\n", $rets);

		echo JoiAdmin::footer();
	}

	
	public static function componenter($option) {
	
		//Установка подменю
		self::$submenu['component_generator']['active'] = true;
		
		//Установка заголовка
		echo JoiAdmin::header( 'Кодер', 'Компонентогенератор');		
		 	
		
		?>
			<table class="adminlist">
				<tr><th>Описание</th><th>Код компонента</th></tr>
				<tr>					
					<td width="200">
						<?php echo forms::open('#', array('id' => 'componenter_form')); ?>
							<label for="">Имя компонента:</label><br/>
							<input type="text" name="component_name" class="text" value="news" />
							<br/><br/>
							
							<label for="">Заголовок компонента:</label><br/>
							<input type="text" name="component_title" class="text" value="Новости" />
							<br/><br/>							
							
							<label for="">Описание компонента:</label><br/>
							<input type="text" name="component_desc" class="text" value="Компонент новостей" />
							<br/><br/>
							
							<label for="">Автор:</label><br/>
							<input type="text" name="component_author" class="text" value="Joostina Team" />
							<br/><br/>
							
							<label for="">Email:</label><br/>
							<input type="text" name="component_authoremail" class="text" value="info@joostina.ru" />
							<br/><br/>
						<?php echo forms::close();?>						
						<button id="create_component">Сгенерировать</button>
					</td>
					<td><div id="componenter_results"></div></td>
				</tr>
			</table>
		<?php
		

		echo JoiAdmin::footer();
	}
}