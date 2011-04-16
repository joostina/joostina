<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

/** проверка включения этого файла файлом-источником*/
defined('_JOOS_CORE') or die();

require_once (JPATH_BASE . '/includes/joostina.php');
include_once (JPATH_BASE . DS . 'language' . DS . $mosConfig_lang . DS . 'system.php');

global $option, $database;

// получение шаблона страницы
$cur_template = @JTEMPLATE;
if (!$cur_template) {
    $cur_template = 'newline2';
}

// Вывод HTML

// требуется для разделения номера ISO из константы языкового файла _ISO
$iso = split('=', _ISO);
// xml prolog
echo '<?xml version="1.0" encoding="' . $iso[1] . '"?' . '>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <style>
        table.moswarning {
            font-size: 200%;
            background-color: #c00;
            color: #fff;
            border-bottom: 2px solid #600
        }

        table.moswarning h2 {
            padding: 0;
            margin: 0;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif;
        }

    </style>
    <meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>"/>
    <title><?php echo $mosConfig_sitename; ?> - <?php echo _SITE_OFFLINE?></title>
    <link rel="stylesheet" href="<?php echo JPATH_SITE; ?>/templates/<?php echo $cur_template; ?>/css/template_css.css"
          type="text/css"/>
</head>
<body style="margin: 0px; padding: 0px;">

<table width="100%" align="center" class="moswarning">
<?php
            if ($mosConfig_offline == 1) {
    ?>
    <tr>
        <td>
            <h2>
                <?php
                                            echo $mosConfig_sitename;
                echo ' - ';
                echo $mosConfig_offline_message;
                ?>
            </h2>
        </td>
    </tr>
                <?php

} elseif (@$mosSystemError) {

    ?>
    <tr>
        <td>
            <h2>
                <?php echo $mosConfig_error_message; ?>
            </h2>
            <?php echo $mosSystemError; ?>
        </td>
    </tr>
    <?php

} else {
    ?>
    <tr>
        <td>
            <h2>
                <?php echo INSTALL_WARN; ?>
            </h2>
        </td>
    </tr>
    <?php

}
    ?>
</table>

</body>
</html>