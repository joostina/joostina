<?php
/**
 * Профиль пользователя - просмотр
 */

// запрет прямого доступа
defined( '_JOOS_CORE' ) or exit();

/**
 * @param modelUsers
 */
$user;

?>
<div class="c-users_profile">

    <div class="page-header page-header_with_img">
        <div class="row">
            <div class="span9">
                <img alt="" src="http://placehold.it/50x50">
                <h1><?php echo $user->user_name  ?>
                    <small>(<?php echo $user->real_name  ?>)</small>
                </h1>
            </div>

            <div class="span3">
                <div class="page-header_controls">
                    <a href="#" class="btn btn-small" title="Отправить сообщение пользователю"><i class="icon-envelope"></i> Сообщение</a>
                    <a href="#" class="btn btn-small" title="Редактировать профиль"><i class="icon-pencil"></i></a>
                </div>

            </div>
        </div>

    </div>


    <div class="row">
        <div class="span8">
            <dl>
                <dt>О себе:</dt>
                <dd>
                    Проектирую интерфейсы и разрабатываю дизайн веб-приложений / приложений для мобильных платформ. Занимаюсь анализом юзабилити.
                </dd>
            </dl>

            <dl>
                <dt>Откуда:</dt>
                <dd>
                    Россия, Кемеровская обл., Ленинск-Кузнецкий
                </dd>
            </dl>

            <dl>
                <dt>Дата рождения:</dt>
                <dd>
                    9 июля 1984
                </dd>
            </dl>

            <dl>
                <dt>Зарегистрирован:</dt>
                <dd>
                    <?php echo joosDateTime::russian_date( 'd F Y года', strtotime($user->register_date) ) ?>
                </dd>
            </dl>

            <dl>
                <dt>Последнее посещение:</dt>
                <dd>
                    <?php echo joosDateTime::russian_date( 'd F Y в h:i', strtotime($user->lastvisit_date ) ) ?>
                </dd>
            </dl>
        </div>

        <div class="span4">
            <h4>Контакты</h4>

            <dl>
                <dt>Email:</dt>
                <dd>123456@654321.123</dd>
            </dl>
            
            <dl>
                <dt>Сайт:</dt>
                <dd><a target="_blank"  href="#">http://www.site.com</a></dd>
            </dl>

            <dl>
                <dt>ICQ:</dt>
                <dd>123456</dd>
            </dl>

            <dl>
                <dt>Skype:</dt>
                <dd><a href="skype:none">none</a></dd>
            </dl>

        </div>
    </div>

</div>