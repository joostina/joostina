<?php defined('_JOOS_CORE') or exit();

/**
 * Test  - Компонент для тестирования нового функционала
 * Контроллер сайта ajax - функций
 *
 * @version    1.0
 * @package    Components\Test
 * @subpackage Controllers\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsAjaxTest  extends joosControllerAjax
{
    public static function upload()
    {

        $upload_result = joosUpload::easy_upload('qqfile',  JPATH_BASE.'/cache/tmp/' );

        return $upload_result + array(
            'success' => $upload_result['success']
        );
    }

}
