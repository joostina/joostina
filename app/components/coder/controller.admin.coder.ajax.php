<?php defined('_JOOS_CORE') or exit();

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
class actionsAjaxAdminCoder extends joosAdminControllerAjax
{
    private static $implode_model = true;

    public static function index()
    {
        $tables = joosRequest::array_param('codertable',array(),$_POST);

        $ret = array(
            'site'=>array(),
            'admin'=>array()
        );

        foreach ($tables as $table) {

            $model_code = modelAdminCoder::get_model($table, self::$implode_model);
            $ret['site'][] = $model_code['site'];
            $ret['admin'][] = $model_code['admin'];

        }

        $body_site = self::$implode_model ? implode('', $ret['site']) : implode("\n\n\n", $ret);
        $body_admin = self::$implode_model ? implode('', $ret['admin']) : implode("\n\n\n", $ret);

        $tables_count = count( $tables );

        return array(
            'success'=>true,
            'message'=> $tables_count ? sprintf('Код для %s %s готов', $tables_count, joosText::declension($tables_count, array('модели','моделей','моделей') )  ) : 'Модели не выбраны' ,
            'body_site'=>'<pre>' . $body_site . '</pre>',
            'body_admin'=>'<pre>' . $body_admin . '</pre>'
        );
    }

    public static function table_select()
    {
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

                        $faker_selector = joosHTML::dropdown('type', $type_names, $active_option);
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

    public static function codegenerator()
    {
        $template_vars_default = array(
            'component_title' => '',
            'component_name' => '',
            'component_description' => '',
            'component_author' => 'Joostina Team',
            'component_authoremail' => 'info@joostina.ru',
            'component_copyright'=>'(C) 2007-2012 Joostina Team'
        );

        $template_vars = array();
        foreach ($template_vars_default as $var => $default_value) {
            $value = joosRequest::post($var, false);
            $template_vars[':'.$var] = $value ? $value : $default_value;
        }

        $template_vars[':component_name_camelcase'] =  joosInflector::camelize($template_vars[':component_name']);

        $template_path_root = JPATH_BASE . DS . 'app' . DS . 'components' . DS . 'coder' . DS . 'templates' . DS . 'component'.DS;

        $template_files = array(
            'controller.component_name',
            'controller.component_name.ajax',
            'controller.admin.component_name',
            'controller.admin.component_name.ajax'
        );

        $return = array();
        foreach ($template_files as $template_file) {
            $template_body = joosFile::get_content( $template_path_root . $template_file);

            $file_body = strtr($template_body,$template_vars);
            $file_name = str_replace('component_name', $template_vars[':component_name'] ,$template_file);

            $return[$template_file] = sprintf('%s.php<br /><textarea class="span10" rows="10">%s</textarea>',$file_name,$file_body);

        }

        return array('success'=>true, 'body' => implode("\n", $return) );
    }

}
