<?php
/**
 * Сomments
 * Шаблон вывода модуля
 *
 * */
//Запрет прямого доступа
defined('_JOOS_CORE') or die();

joosLoader::lib('text');

if (!$comments) {
    return;
}
?>
<div class="m-comments_latest block_sidebar">

    <h5 class="g-blocktitle_sidebar"><?php echo $module_title ?></h5>

    <ul class="listreset m-comments_list">
        <?php foreach ($comments as $comment): ?>
        <?php
                    $linkuser = $comment->user_id ? joosRoute::href('user_view', array('username' => $comment->user_name, 'id' => $comment->user_id)) : '#';
        $params = json_decode($comment->params);
        $link_comment = isset($params->href) ? JPATH_SITE . $params->href . '#comment' . $comment->id : '#';
        ?>
        <li class="m-comments_list_item">
            <div class="comment_author">
                <a href="<?php echo $linkuser ?>" class="comment_author_link">
                    <img class="g-thumb_40 g-user_avatar" src="<?php echo User::avatar($comment->user_id, '40x40') ?>"
                         alt=""/>
                </a>
            </div>
            <a class="comment_text"
               href="<?php echo $link_comment ?>"><?php echo joosText::text_wrap(joosText::character_limiter(joosText::simple_clean($comment->comment_text), 70, '', 25), 25) ?>
                [...]</a>
            <span class="comment_author_name"><?php echo $comment->user_name ?></span>
            <span class="comment_date date"><?php echo $comment->created_at ?></span>
        </li>
        <?php endforeach; ?>
    </ul>
</div>