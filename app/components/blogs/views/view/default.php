<?php defined('_JOOS_CORE') or exit();

/**
 * Компонент ведения блогов - шаблон просмотра объекта
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

<article class="post">
    <h1><?php echo joosFilter::htmlspecialchars($blog_item->title) ?></h1>

    <ul class="post-metadata unstyled">

        <li class="author">
            <i class="icon-user"></i>
            <a rel="author" title="<?php echo joosFilter::htmlspecialchars($author->user_name) ?>" href="<?php echo joosRoute::href('user_view',array('id'=>$author->id,'user_name'=>$author->user_name)) ?>"><?php echo $author->user_name ?></a>
        </li>

        <li class="date"><i class="icon-time"></i> <?php echo joosDateTime::russian_date('d F, Y') ?></li>

        <li class="tags">
            <i class="icon-tags"></i>
            <a title="<?php echo joosFilter::htmlspecialchars($blog_category->title) ?>" href="<?php echo joosRoute::href('blog_cat', array('category_slug'=>$blog_category->slug) ) ?>"><?php echo $blog_category->title ?></a>
        </li>
    </ul>

    <div class="post-text">
        <p><?php echo $blog_item->text_full ?></p>
    </div>

    <div class="row">
        <div class="span12">
            <div class="post-share">
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle">Поделиться<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Google +</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</article>