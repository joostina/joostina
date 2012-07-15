<?php

/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or exit();

/**
 * Для вывода визуального редактора Redactor
 * http://imperavi.com/redactor/
 *
 * @version    1.0
 * @package    Plugins
 * @subpackage Editor
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class pluginEditorRedactor
{
    public static function init()
    {
        joosDocument::instance()
                ->add_css(JPATH_APP_PLUGINS_SITE . '/editors/redactor/css/redactor.css')
                ->add_js_file(JPATH_APP_PLUGINS_SITE . '/editors/redactor/redactor.js');
    }

    public static function display($name, $content, $hiddenField, $width, $height, $col, $row, $params)
    {
        $option = $option = joosRequest::param('option');

        $code_on_ready = <<< EOD
        $(document).ready(function() {
            $('#$name').redactor({
                imageUpload: '/admin/ajax.index.php?option=$option&task=upload_images_embedded',
                fileUpload: '/admin/ajax.index.php?option=$option&task=upload_files_embedded'
            });
        });
EOD;
        joosDocument::instance()
            ->add_js_code($code_on_ready);

        return '<textarea name="' . $hiddenField . '" id="' . $hiddenField . '" cols="' . $col . '" rows="' . $row . '" style="width:' . $width . ';height:' . $height . ';">' . $content . '</textarea>';
    }

    public static function get_content($name, $params = array())
    {
        return true;
    }

}
