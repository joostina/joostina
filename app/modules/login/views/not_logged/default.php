<?php defined('_JOOS_CORE') or exit(); ?>

<ul class="nav pull-right">
	<li class="dropdown">
		<a data-toggle="dropdown" class="dropdown-toggle" href="#">Вход/регистрация <b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a class="js-login-modal-replace">Войти</a></li>
			<li><a href="#">Забыли пароль?</a></li>
			<li class="divider"></li>
			<li><a href="#">Регистрация</a></li>
		</ul>
	</li>
</ul>

<div class="modal hide fade" id="modal-login_form">
     <div class="modal-header">
         <a class="close" data-dismiss="modal">×</a>
         <h3>Вход</h3>
     </div>
     <div class="modal-body">
         <form class="form-horizontal" action="<?php echo joosRoute::href( 'login' ) ?>" method="post">
             <div class="control-group">
                 <label class="control-label" for="inp-login">Логин:</label>
                 <div class="controls">
                     <input type="text" class="input-medium" id="inp-login" name="user_name">
                 </div>
             </div>

             <div class="control-group">
                 <label class="control-label" for="inp-password">Пароль:</label>
                 <div class="controls">
                     <input type="password" class="input-medium" id="inp-password" name="password">
                 </div>
             </div>

             <div class="form-actions">
                 <button class="btn btn-primary" type="submit">Войти</button>
                 <button class="btn" data-dismiss="modal">Передумал</button>
             </div>
             <input type="hidden" name="<?php echo joosCSRF::get_code( 1 ); ?>" value="1"/>
         </form>
     </div>
 </div>

