<?php
/**
 * Профиль пользователя - редактирование
 */

// запрет прямого доступа
defined( '_JOOS_CORE' ) or exit;

/**
 * @param modelUsers
 */
$user;

?>
<div class="c-users_profile">

    <div class="page-header page-header_with_img">
        <div class="row">
            <div class="span12">
                <img alt="" src="http://placehold.it/50x50">
                <h1><?php echo $user->user_name  ?>
                    <small>(<?php echo $user->user_name  ?>)</small>
                </h1>
            </div>
        </div>
    </div>

    <h2>Редактирование профиля</h2>

    <form class="form-horizontal" action="<?php echo joosRoute::href('user_edit') ?>" method="post">
        <fieldset>
            <legend>Основные данные</legend>

            <div class="control-group">
                <label for="real_name" class="control-label">Реальное имя</label>

                <div class="controls">
                    <input type="text" value="<?php echo $user->real_name ?>" class="input-xlarge" id="real_name" name="real_name">
                </div>
            </div>

            <div class="control-group">
                <label for="focusedInput" class="control-label">Откуда</label>

                <div class="controls">
                    <input type="text" value="" class="input-xlarge">
                </div>
            </div>

            <div class="control-group">
                <label  class="control-label">Дата рождения</label>

                <div class="controls">
                    <div class="input-append date" id="js-datepicker" data-date="12-02-2012" data-date-format="dd-mm-yyyy">
                        <input class="span2" size="16" type="text" value="12-02-2012">
                        <span class="add-on"><i class="icon-th"></i></span>
                    </div>

                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Пол</label>

                <div class="controls">
                    <label class="radio">
                        <input type="radio" checked="" value="option1" id="optionsRadios1" name="optionsRadios">
                        мужской
                    </label>
                    <label class="radio">
                        <input type="radio" value="option2" id="optionsRadios2" name="optionsRadios">
                        женский
                    </label>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">О себе</label>

                <div class="controls">
                    <textarea rows="3" id="textarea" class="input-xlarge"></textarea>
                </div>
            </div>

            <div class="control-group">
                <label  class="control-label" for="email">Email</label>

                <div class="controls">
                    <input type="text" value="<?php echo $user->email ?>" class="input-xlarge" id="email" name="email">
                </div>
            </div>

            <div class="control-group">
                <label  class="control-label">Пароль</label>

                <div class="controls">
                    <input type="text" value="" class="span2" placeholder="Старый пароль">
                    <input type="text" value="" class="span2" placeholder="Новый пароль">
                </div>
            </div>

        </fieldset>

        <fieldset class="user-contacts">
            <legend>Контакты</legend>

            <div class="control-group">
                <label class="control-label">
                    <div class="controls-buttons">
                        <a href="#" class="btn btn-mini"><i class="icon-pencil"></i></a>
                        <a href="#" class="btn btn-mini"><i class="icon-trash"></i></a>
                    </div>
                    ICQ
                </label>

                <div class="controls">
                    <p>213334</p>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">
                    <div class="controls-buttons">
                        <a href="#" class="btn btn-mini"><i class="icon-pencil"></i></a>
                        <a href="#" class="btn btn-mini"><i class="icon-trash"></i></a>
                    </div>
                    Facebook
                </label>

                <div class="controls">
                    <p>http://facebook.com/xxx</p>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">&nbsp;</label>

                <div class="controls">
                    <p><a href="#" class="btn btn-small"><i class="icon-plus"></i> Добавить</a></p>
                </div>
            </div>

        </fieldset>

        <div class="form-actions">
            <button class="btn btn-large btn-primary" type="submit">Сохранить</button>
            <button class="btn btn-large">Отмена</button>
        </div>

        <input type="hidden" name="<?php echo joosCSRF::get_code(1) ?>" value="1"/>
    </form>

</div>
