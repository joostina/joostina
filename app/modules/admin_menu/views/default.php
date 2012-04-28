<?php
/**

 */
// запрет прямого доступа
defined( '_JOOS_CORE' ) or exit();

?>
<ul class="nav">
    <?php foreach ($menu_items as $menu_item_title => $menu_item): ?>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $menu_item_title ?><b class="caret"></b></a>
        <ul class="dropdown-menu">
            <?php foreach ($menu_item['children'] as $children_item_title => $children_item): ?>
            <!--<li class="nav-header">Независимые страницы</li>-->
            <li><a href="<?php echo $children_item['href'] ?>"><?php echo $children_item_title ?></a></li>
            <!--<li class="divider"></li>-->
            <?php endforeach ?>
        </ul>
    </li>
    <?php endforeach ?>
</ul>