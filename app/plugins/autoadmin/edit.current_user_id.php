<?php

// запрет прямого доступа
defined( '_JOOS_CORE' ) or exit();

/**
 * Для вывода скрытого элемента формы содержащего ID текущего вторизованного пользователя
 *
 * @version    1.0
 * @package    Plugins
 * @subpackage Autoadmin
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class pluginAutoadminEditCurrentUserId implements joosAutoadminPluginsEdit
{
    public static function render( $element_param , $key , $value , $obj_data , $params )
    {
        return joosHtml::hidden( $key , joosCore::user()->id );
    }

}
