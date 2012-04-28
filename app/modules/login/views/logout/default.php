<?php
/**
 * Login - модуль авторизации
 * Основной исполняемый файл
 *
 * @version    1.0
 * @package    Joostina CMS
 * @subpackage modelModules
 * @author     JoostinaTeam
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    see license.txt
 *
 * */
//Запрет прямого доступа
defined( '_JOOS_CORE' ) or exit();
?>

<ul class="nav pull-right">
	<li class="dropdown">
		<a data-toggle="dropdown" class="dropdown-toggle" href="#">
            <?php echo $user->user_name; ?>
            <b class="caret"></b>
        </a>

		<ul class="dropdown-menu">
			<li>
                <a href="<?php echo joosRoute::href( 'user_view' , array ( 'id' => $user->id , 'user_name' => $user->user_name ) ) ?>">Перейти в профиль</a>
			</li>
            <li><a href="<?php echo joosRoute::href('user_edit') ?>">Настройки профиля</a></li>
			<li class="divider"></li>
			<li class="logout">
                <form action="<?php echo joosRoute::href('logout') ?>" method="post" id="m-auto_logout">
                    <button type="submit" class="btn">Выйти</button>
                    <input type="hidden" name="<?php echo joosCSRF::get_code(1); ?>" value="1" />
                </form>
			</li>
		</ul>
	</li>
</ul>
