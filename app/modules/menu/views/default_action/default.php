<?php defined('_JOOS_CORE') or exit();

//@todo Что-то не нравится мне здесь это( Может, стоит повертеть массив предварительно в контроллере...
//@todo Реализовать подсветку активного пункта (учесть, что активным может быть дочерний пункт, но подсвечивать нужно и родительский)
navigation_ul_li_recurse($menu_items, 1);

function navigation_ul_li_recurse(array $items, $level = 1) { ?>

    <ul class="<?php echo $level > 1 ? 'dropdown-menu' : 'nav' ?>">

        <?php foreach ($items as $item => $data) :
            $_has_children = (isset($data['children']) && is_array($data['children'])) ? 1 : 0;
            $title = isset($data['title']) && trim($data['title']) != '' ? 'title="'.$data['title'].'"' : '';
        ?>

            <li<?php echo $_has_children ? ' class="dropdown"' : '' ?>>
                <?php if($_has_children):?>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"<?php echo $title ?>>
                        <?php echo $item;?>
                        <b class="caret"></b>
                    </a>
                    <?php navigation_ul_li_recurse($data['children'], $level + 1) ?>

                <?php else:?>
                <a href="<?php echo $data['href'] ?>"<?php echo $title ?>><?php echo $item;?></a>
                <?php endif;?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php
}
