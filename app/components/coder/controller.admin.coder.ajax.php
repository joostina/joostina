<?php defined('_JOOS_CORE') or die();

/**
 * Компонент управляемой генерации расширений системы
 * Аякс - контроллер панели управления
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
class actionsAjaxAdminCoder extends joosAdminControllerAjax{

	private static $implode_model = true;

	public static function index() {
		$tables = joosRequest::array_param('codertable',array(),$_POST);

		$ret = array();
		foreach ($tables as $table) {
			$ret[] = modelAdminCoder::get_model($table, self::$implode_model);
		}

		$body = self::$implode_model ? implode('', $ret) : implode("\n\n\n", $ret);

		return array(
			'success'=>true,
			'body'=>'<pre>' . $body . '</pre>'
		);
	}

	public static function table_select() {

		$table = joosRequest::post('table');

		$types = modelAdminCoder_Faker::$data_types;
		$type_names = array();

		array_walk($types, function( $v, $k ) use ( &$type_names ) {
	        $type_names[$k] = $v['name'];
		});

		$table_fields = joosDatabase::instance()->get_utils()->get_table_fields($table);

        ob_start();
        ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Поле</th>
                        <th>Тип</th>
                        <th>Чем заполнить</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $i = 1; foreach ($table_fields as $key => $value) :?>
                        <?php

                        $type = preg_replace('#[^A-Z]#i', '', $value);
                        $type = str_replace('unsigned', '', $type);
                        $active_option = null;

                        array_walk($types, function($v, $k) use ($type, &$active_option) {
                            $active_option = (in_array($type, $v['types']) && $active_option === null) ? $k : $active_option;
                        });

                        $faker_selector = forms::dropdown('type', $type_names, $active_option);
                        ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo $key ?></td>
                        <td><?php echo $type ?></td>
                        <td><?php echo $faker_selector ?></td>
                    </tr>

                    <?php ++$i; endforeach;?>
                </tbody>
            </table>
        <?php


        $return = ob_get_contents();
        ob_get_clean();

		return $return;
	}

	public static function generate_code() {
		self::generate_component();
	}

	public static function generate_files() {
		self::generate_component(true);
	}

	public static function generate_component($create_files = false) {

		$tpls_vars = array('name' => '',
			'desc' => '',
			'author' => '',
			'authoremail' => '',
			'title' => '');

		foreach ($tpls_vars as $var => $val) {
			$tpls_vars[$var] = joosRequest::post('component_' . $var, '');
		}

		$tpls_vars['name_lower'] = strtolower($tpls_vars['name']);
		$tpls_vars['name_upper'] = ucfirst($tpls_vars['name']);

		$tpl_path = JPATH_BASE . DS . 'app' . DS . 'components' . DS . 'coder' . DS . 'tpls' . DS . 'componenter';

		$ret = array();

		$c_path = JPATH_BASE . DS . 'app' . DS . 'components' . DS . $tpls_vars['name_lower'];
		if ($create_files == true) {

			// @todo переделать на joosFile
			$_blank = '<html><body></body></html>';
			// @todo переделать
			$file = new joosFile;
			$file->create($c_path);
			$file->create($c_path . DS . 'index.html', $_blank);
			$file->create($c_path . DS . 'views');
			$file->create($c_path . DS . 'views' . DS . 'index.html', $_blank);
			$file->create($c_path . DS . 'admin_views');
			$file->create($c_path . DS . 'admin_views' . DS . 'index.html', $_blank);
			$file->create($c_path . DS . 'media');
			$file->create($c_path . DS . 'media' . DS . 'index.html', $_blank);
		}

		$files = array('c' => $tpls_vars['name_lower'],
			'c_ajax' => $tpls_vars['name_lower'] . '.ajax',
			'c_class' => $tpls_vars['name_lower'] . '.class',
			'c_admin' => 'admin.' . $tpls_vars['name_lower'],
			'c_admin_ajax' => 'admin.' . $tpls_vars['name_lower'] . '.ajax',
			'c_admin_class' => 'admin.' . $tpls_vars['name_lower'] . '.class',
			'c_params' => $tpls_vars['name_lower'] . '.params');

		foreach ($files as $type => $name) {
			$content = file_get_contents($tpl_path . DS . $type . '.txt');
			$content = sprintf($content, $tpls_vars['name_upper'], $tpls_vars['name_lower'], $tpls_vars['desc'], $tpls_vars['author'], $tpls_vars['authoremail'], $tpls_vars['title']);
			$ret[] = '<strong>' . $name . '.php</strong><br/>' . forms::textarea(array('name' => 'c_admin',
						'value' => $content,
						'rows' => '5',
						'class' => 'c_content')) . '<br/><br/>';
			if ($create_files == true) {
				$file->create($c_path . DS . $name . '.php', $content);
			}
		}

		$output = implode('', $ret);
		$output .= is_dir($c_path) ? 'Такой компонент уже существует, введите другое имя, чтобы создать файлы' : '<button id="create_component_files">Создать файлы</button>';

		echo $output;
	}

}