<?php
/**
* @package Joostina
* @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
* @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
* Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
* Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
*/

// запрет прямого доступа
defined('_VALID_MOS') or die();


DEFINE('_NEW_USER_MESSAGE_SUBJECT','Новые данные пользователя');
DEFINE('_NEW_USER_MESSAGE','Здравствуйте, %s


Вы были зарегистрированы Администратором на сайте %s.

Это сообщение содержит Ваши имя пользователя и пароль, для входа на сайт %s:

Имя пользователя - %s
Пароль - %s


На это сообщение не нужно отвечать. Оно сгенерировано роботом рассылок и отправлено только для информирования.');
DEFINE('_USER_LOGIN_TXT','Имя пользователя (логин )');
DEFINE('_LOGGED_IN','На сайте');
DEFINE('_LAST_LOGIN','Последнее посещение');
DEFINE('_ALLOW','Разрешить');
DEFINE('_DISALLOW','Запретить');
DEFINE('_ENTER_LOGIN_PLEASE','Вы должны ввести имя пользователя для входа на сайт');
DEFINE('_BAD_USER_LOGIN','Ваше имя для входа содержит неправильные символы или слишком короткое.');
DEFINE('_ENTER_NAME_PLEASE','Вы должны ввести имя');
DEFINE('_ENTER_EMAIL_PLEASE','Вы должны ввести адрес e-mail');
DEFINE('_ENTER_GROUP_PLEASE','Вы должны назначить пользователю группу доступа');
DEFINE('_BAD_PASSWORDWORD','Пароль неправильный');
DEFINE('_BAD_GROUP_1','Пожалуйста, выберите другую группу. Группы типа `Public Front-end` выбирать нельзя');
DEFINE('_BAD_GROUP_2','Пожалуйста, выберите другую группу. Группы типа `Public Back-end` выбирать нельзя');
DEFINE('_USER_INFO','Информация о пользователе');
DEFINE('_NEW_PASSWORDWORD','Новый пароль');
DEFINE('_REPEAT_PASSWORDWORD','Проверка пароля');
DEFINE('_BLOCK_USER','Блокировать пользователя');
DEFINE('_RECEIVE_EMAILS','Получать системные сообщения на e-mail');
DEFINE('_REGISTRATION_DATE','Дата регистрации');
DEFINE('_CONTACT_INFO','Контактная информация');
DEFINE('_NO_USER_CONTACTS','У этого пользователя нет контактной информации:<br />Для подробностей смотрите \'Компоненты -> Контакты -> Управление контактами\'');
DEFINE('_FULL_NAME','Полное имя');
DEFINE('_CHANGE_CONTACT_INFO','Изменить контактную информацию');
DEFINE('_CONTACT_INFO_PATH_URL','Компоненты -> Контакты -> Управление контактами');
DEFINE('_RESTRICT_FUNCTION','Функциональность ограничена');
DEFINE('_NO_RIGHT_TO_CHANGE_GROUP','Вы не можете изменить эту группу пользователей. Это может сделать только Главный администратор сайта');
DEFINE('_NO_RIGHT_TO_USER_CREATION','Вы не можете создать пользователя с этим уровнем доступа. Это может сделать только Главный администратор сайта');
DEFINE('_PROFILE_SAVE_SUCCESS','Успешно сохранены изменения профиля пользователя');
DEFINE('_CANNOT_DEL_ONE_SUPER_ADMIN','Вы не можете удалить этого Главного администратора, т.к. он единственный Главный администратор сайта');
DEFINE('_CHOOSE_USER_TO','Выберите пользователя для');
DEFINE('_PLEASE_CHOOSE_USER','Пожалуйста, выберите пользователя');
DEFINE('_CANNOT_DISABLE_SUPER_ADMIN','Вы не можете отключить Главного администратора');
DEFINE('_THIS_CAN_DO_HIGHLEVEL_USERS','Это могут делать только пользователи с более высоким уровнем доступа');
DEFINE('_DISABLE','Отключить');
DEFINE('_com_users_SELECT_GROOP','- Выберите группу -');
DEFINE('_com_users_SELECT_STATUS','- Выберите статус -');
DEFINE('_com_users_USER_LOGED','Авторизован(а) на сайте');
DEFINE('_SITE_SETTINGS','Настройки сайта');
DEFINE('_SELECT_EDITOR','- Выберите редактор -');
DEFINE('_VALID_AZ09',"Пожалуйста, проверьте, правильно ли написано %s.  Имя не должно содержать пробелов, только символы 0-9,a-z,A-Z и иметь длину не менее %d символов.");
DEFINE('_VALID_AZ09_USER',"Пожалуйста, правильно введите %s. Должно содержать только символы 0-9,a-z,A-Z и иметь длину не менее %d символов.");
DEFINE('_YOUR_NAME','Полное имя');
DEFINE('_CONTACT_INFO_COM_CONTACT','Связь с компонентом контактов');
DEFINE('_C_USERS_USER_EDIT','Редактирование профиля пользователя');
DEFINE('_C_USERS_USER_NEW','Новый пользователь');
DEFINE('_ADDITIONAL_INFO','Дополнительная информация');
DEFINE('_C_USERS_CONTACT_INFO','Контактные данные');
DEFINE('_C_USERS_REG_SETTINGS','Пользователи: настройки регистрации');
DEFINE('_C_USERS_REG_FORM_BEFORE','Текст перед формой регистрации');
DEFINE('_C_USERS_REG_FORM_AFTER','Текст после формы регистрации');
DEFINE('_C_USERS_REG_AFTER_LINK','Ссылка для перехода после регистрации');
DEFINE('_C_USERS_REG_ONE_GROOP_TEMPLATE','Использовать единый шаблон формы регистрации для всех групп пользователей');
DEFINE('_C_USERS_REG_DEFAULT_GROOPS','Группа пользователя по умолчанию');
DEFINE('_C_USERS_REG_PROFILE_ACTIVATE','Активация аккаунта администратором (работает совместно с глобальной настройкой "Использовать активацию нового аккаунта:"');
DEFINE('_C_USERS_PROFILE_SETTINGS','Пользователи: настройки профиля');
DEFINE('_C_USERS_PROFILE_ONE_TEMPLATE','Использовать единый шаблон профиля для всех групп пользователей');
DEFINE('_C_USERS_PROFILE_ONE_TEMPLATE_EDIT','Использовать единый шаблон редактирования данных для всех групп пользователей');
DEFINE('_C_USERS_LOSTPASS_SETTINGS','Настройки восстановления пароля');