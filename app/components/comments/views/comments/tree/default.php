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

global $my;

//Библиотека работы с деревьями
joosLoader::lib('tree', 'joomlatune');
$tree = new JoomlaTuneTree($comments_list);
$items = $tree->get();

$i = 0;
$count = count($comments_list);
$currentLevel = 0;
?>
<!--Comments main level-->
<div class="comments-list" id="comments-list-0">
	<?php
    foreach ($items as $id => $comment) :
    $comment->user_name = $comment->user_id ? $comment->user_name : _GUEST_USER;
    if ($currentLevel < $comment->level) {
        ?>
		</div><!--Comments sub level-->
    <!--Comments sub level-->
		<div class="comments-list" id="comments-list-<?php echo $comment->parent; ?>">
		<?php } else { ?>
        <?php
                    $j = 0;
        if ($currentLevel >= $comment->level) {
            $j = $currentLevel - $comment->level;
        } else if ($comment->level > 0 && $i == $count - 1) {
            $j = $comment->level;
        }
        while ($j > 0) {
            ?>
			</div><!--Comments sub level-->
        <?php $j--;
        } ?>
    <?php } ?>

<!--Comment item-->
	<div class="comment_item <?php echo ($i % 2 ? 'odd' : 'even'); ?>" id="comment-item-<?php echo $id; ?>">
		<?php CommentsHTML::comment($comment); ?>

    <?php
            if ($comment->children == 0) {
    echo '</div>';
}
    ?>
    <?php
            if ($comment->level > 0 && $i == $count - 1) {
    $j = $comment->level;
}
    ?>
    <?php while ($j > 0) { ?>
		</div><!--//Comment item-->
<?php $j--;
} ?>

<?php $i++;
    $currentLevel = $comment->level; ?>

<?php endforeach; ?>
</div><!--//Comments main level-->