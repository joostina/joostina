<?php

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: s-maxage=0, max-age=0, must-revalidate');
header('Expires: Mon, 23 Jan 1978 10:00:00 GMT');

function __($s) {
	return $s;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="robots" content="noindex">
		<title><?php echo __('Установка Joostina CMS') ?></title>
		<style type="text/css">
			html {
				font: 13px/1.5 Verdana, sans-serif;
				border-top: 5.3em solid #F4EBDB;
			}

			body {
				border-top: 1px solid #E4DED5;
				margin: 0;
				background: white;
				color: #333;
			}

			#wrapper {
				max-width: 780px;
				margin: -5.3em auto 0;
			}

			h1 {
				font: 2.3em/1.5 sans-serif;
				margin: .5em 0 1.5em;
				/*background: url(assets/logo.png) right center no-repeat;*/
				color: #7A7772;
				text-shadow: 1px 1px 0 white;
			}

			h2 {
				font-size: 2em;
				font-weight: normal;
				color: #3484D2;
				margin: .7em 0;
			}

			p {
				margin: 1.2em 0;
			}

			.result {
				margin: 1.5em 0;
				padding: 0 1em;
				border: 2px solid white;
			}

			.passed h2 {
				color: #1A7E1E;
			}

			.failed h2 {
				color: white;
			}

			table {
				padding: 0;
				margin: 0;
				border-collapse: collapse;
				width: 100%;
			}

			table td, table th {
				text-align: left;
				padding: 10px;
				vertical-align: top;
				border-style: solid;
				border-width: 1px 0 0;
				border-color: inherit;
				background: white none no-repeat 12px 8px;
				background-color: inherit;
			}

			table th {
				font-size: 105%;
				font-weight: bold;
				padding-left: 50px;
			}

			.passed, .info {
				background-color: #E4F9E3;
				border-color: #C6E5C4;
			}

			.passed th {
				background-image: url('assets/passed.gif');
			}

			.info th {
				background-image: url('assets/info.gif');
			}

			.warning {
				background-color: #FEFAD4;
				border-color: #EEEE99;
			}

			.warning th {
				background-image: url('assets/warning.gif');
			}

			.failed {
				background-color: #F4D2D2;
				border-color: #D2B994;
			}

			div.failed {
				background-color: #CD1818;
				border-color: #CD1818;
			}

			.failed th {
				background-image: url('assets/failed.gif');
			}

			.description td {
				border-top: none !important;
				padding: 0 10px 10px 50px;
				color: #555;
			}

			.passed.description {
				display: none;
			}
		</style>

	</head>

	<body>
		<div id="wrapper">
			<h1><?php echo __('Установка Joostina CMS') ?></h1>

			<p><?php echo __('Скрипт проверяет доступность БД и создаёт все необходимые базовые таблицы и вносит системные данные') ?></p>

			<h2><?php echo __('База данных') ?></h2>

			<table>
				<tr class="passed">
					<th>Название базы данных</th>
					<td><input name="db_name" value="" /></td>
				</tr>
				<tr class="passed">
					<th>Название базы данных</th>
					<td><input name="db_host" value="localhost" /></td>
				</tr>
				<tr class="passed">
					<th>Имя пользователя базы данных</th>
					<td><input name="db_user" value="" /></td>
				</tr>
				<tr class="passed">
					<th>Пароль пользователя базы данных</th>
					<td><input name="db_password" value="" /></td>
				</tr>
				<tr class="warning">
					<th>Всю ответственность понимаю</th>
					<td><button >Установить базу данных</button></td>
				</tr>
				<tr class="warning description">
					<td colspan="2">Если все поля выше заполнены правильно - можно нажать на кнопку выше</td>
				</tr>
			</table>


			<h2><?php echo __('Создание администратора') ?></h2>
			<table>
				<tr class="passed">
					<th>Логин администратора</th>
					<td><input name="admin_username" value="" /></td>
				</tr>
				<tr class="passed">
					<th>Пароль администратора</th>
					<td><input name="admin_password" value="" /></td>
				</tr>
				<tr class="passed">
					<th>Email адрес администратора</th>
					<td><input name="admin_email" value="" /></td>
				</tr>
				<tr class="warning">
					<th>Всё верно</th>
					<td><button >Создать администратора</button></td>
				</tr>
			</table>

			<h2><?php echo __('Восстановить данные') ?></h2>
			<table>
				<tr class="passed">
					<th>Сайт про машинки # 19:05:2011</th>
					<td><button >Восстановить</button></td>
				</tr>
				<tr class="passed">
					<th>Сайт про машинки # 25:05:2011</th>
					<td><button >Восстановить</button></td>
				</tr>
				<tr class="passed">
					<th>Магазин галстуков # 20:01:2011</th>
					<td><button >Восстановить</button></td>
				</tr>

			</table>
		</div>
	</body>
</html>