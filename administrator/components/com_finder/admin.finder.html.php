<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_VALID_MOS') or die();

class elFinder {
    /**
     * Вывод файлового менеджера elFinder
     * @param string $elfinder_config - конфигурация клиента elFinder
     */
    public static function index( $elfinder_config) {
        ?>
<table class="adminheading">
    <tbody>
        <tr>
            <th class="config" colspan="3"><?php echo _COM_FILES ?></th>
        </tr>
    </tbody>
</table>
<div id="finder">finder</div>
<script type="text/javascript" charset="utf-8">
    $().ready(function() {
        var finder_options = <?php echo $elfinder_config; ?>;
        $('#finder').elfinder( finder_options );
    });
</script>
        <?php
    }
}
