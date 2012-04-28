<?php defined('_JOOS_CORE') or exit();

/**
 * Компонент ведения блогов - шаблон просмотра стартовой страницы компонента
 *
 * @version    1.0
 * @package    Components\Blogs
 * @subpackage Views
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
?>
<?php echo joosBreadcrumbs::instance()->get() ?>
<br />

<?php foreach( $blogs_items as $blog_item ): ?>
<article class="post">
    <h2><a title="<?php echo joosFilter::htmlspecialchars($blog_item->title) ?>" href="<?php echo joosRoute::href('blog_view', array('category_slug'=>$blog_item->category_slug, 'id'=>$blog_item->id) ) ?>"><?php echo $blog_item->title ?></a></h2>

    <ul class="post-metadata unstyled">

        <li class="author">
            <i class="icon-user"></i>
            <a rel="author" title="<?php echo joosFilter::htmlspecialchars($blog_item->user_name) ?>" href="<?php echo joosRoute::href('user_view',array('id'=>$blog_item->user_id,'user_name'=>$blog_item->user_name)) ?>"><?php echo $blog_item->user_name ?></a>
        </li>

        <li class="date"><i class="icon-time"></i> <?php echo joosDateTime::russian_date('d F, Y') ?></li>

        <li class="tags">
            <i class="icon-tags"></i>
            <a title="<?php echo joosFilter::htmlspecialchars($blog_item->title) ?>" href="<?php echo joosRoute::href('blog_cat', array('category_slug'=>$blog_item->category_slug) ) ?>"><?php echo $blog_item->category_title ?></a>
        </li>
    </ul>

    <div class="post-text">
        <p><?php echo $blog_item->text_intro ?></p>
    </div>

    <a class="btn btn-large btn-primary" href="<?php echo joosRoute::href('blog_view', array('category_slug'=>$blog_item->category_slug, 'id'=>$blog_item->id) ) ?>">Читать дальше</a>
</article>
<?php endforeach ?>