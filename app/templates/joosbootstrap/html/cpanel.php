<?php
/**
 * @JoostFREE
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();

$hrefs = require_once JPATH_APP_CONFIG.'/admin_panel.php';

?>
<h1>Панель управления</h1>
<p>Здесь какие-то сводные данные</p>

<div class="cpanel">
    <?php foreach($hrefs as $block_title => $block_hrefs): ?>
        <?php if( $block_hrefs===true ): ?>
            <div class="b-clear" style="height:50px"></div>
        <?php else: ?>
            <div class="b b-50 b-left">
                <h3><?php echo $block_title ?></h3>
                <?php foreach ($block_hrefs as $href_title => $href): ?>
                <span><a class="btn btn-large" href="<?php echo $href['href'] ?>"><img src="<?php echo joosHtml::ico( $href['ico'] , '32x32') ?>"/><?php echo $href_title ?></a></span>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    <?php endforeach ?>
</div>