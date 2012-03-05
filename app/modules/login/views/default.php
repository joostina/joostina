<?php
/**
 * DEMO
 **/

//Запрет прямого доступа
defined( '_JOOS_CORE' ) or die();
?>

<div class="modal hide fade" id="modal-login_form">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>Вход</h3>
    </div>
    <div class="modal-body">
        <form class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="inp-login">Логин:</label>
                <div class="controls">
                    <input type="text" class="input-medium" id="inp-login">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="inp-password">Пароль:</label>
                <div class="controls">
                    <input type="password" class="input-medium" id="inp-password">
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Войти</button>
                <button class="btn" data-dismiss="modal">Передумал</button>
            </div>
        </form>
    </div>
</div>