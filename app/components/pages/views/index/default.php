<?php
/**
 * @package   Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();
?>

<?php foreach($pages as $page):?>

    <div class="row">
        <div class="span12">
            <h2>
                <a href="<?php echo joosRoute::href('pages_view', array('page_name' => $page->slug ))  ?>">
                    <?php echo $page->title ?>
                </a>
            </h2>
            <p><?php echo $page->text; ?></p>
        </div>
    </div>

<?php endforeach;?>


