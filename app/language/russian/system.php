<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_JOOS_CORE') or die();

global $mosConfig_form_date,$mosConfig_form_date_full;

DEFINE('_DATE_FORMAT_LC','%d %B %Y г. %H:%M'); //Используйте формат PHP-функции strftime
DEFINE('_DATE_FORMAT_LC2',$mosConfig_form_date_full); // Полный формат времени
DEFINE('_DATE_FORMAT','Сегодня: d.m.Y г.'); //Используйте формат PHP-функции DATE

DEFINE('_LANGUAGE','ru');

DEFINE('_JAN','Январь');
DEFINE('_FEB','Февраль');
DEFINE('_MAR','Март');
DEFINE('_APR','Апрель');
DEFINE('_MAY','Май');
DEFINE('_JUN','Июнь');
DEFINE('_JUL','Июль');
DEFINE('_AUG','Август');
DEFINE('_SEP','Сентябрь');
DEFINE('_OCT','Октябрь');
DEFINE('_NOV','Ноябрь');
DEFINE('_DEC','Декабрь');

/**
 * даты со склонением
 */
DEFINE('_JAN_2','Января');
DEFINE('_FEB_2','Февраля');
DEFINE('_MAR_2','Марта');
DEFINE('_APR_2','Апреля');
DEFINE('_MAY_2','Мая');
DEFINE('_JUN_2','Июня');
DEFINE('_JUL_2','Июля');
DEFINE('_AUG_2','Августа');
DEFINE('_SEP_2','Сентября');
DEFINE('_OCT_2','Октября');
DEFINE('_NOV_2','Ноября');
DEFINE('_DEC_2','Декабря');


/**
 * Ошибки и предупреждения
 */
DEFINE('_404','Запрошенная страница не найдена');
DEFINE('_404_RTS','Вернуться на сайт');
DEFINE('_IFRAMES','Эта страница будет отображена некорректно. Ваш браузер не поддерживает вложенные фреймы (IFrame)');
DEFINE('_JAVASCRIPT','Внимание! Для выполнения данной операции, в вашем браузере должна быть включена поддержка Java-script.');
DEFINE('_DO_LOGIN','Вы должны авторизоваться или пройти регистрацию.');
DEFINE('_ERROR_OCCURED','Произошла ошибка');
DEFINE('_MOS_WARNING','Внимание!');
DEFINE('_NOT_AUTH','Извините, но для просмотра этой страницы у Вас недостаточно прав.');
DEFINE('_NOT_EXIST','Извините, страница не найдена.<br />Пожалуйста, вернитесь на главную страницу сайта.');
DEFINE('_NOT_EXISTS','Отсутствуют');  
DEFINE('_SYSERR1','Нет поддержки MySQL');
DEFINE('_SYSERR2','Невозможно подключиться к серверу базы данных');
DEFINE('_SYSERR3','Невозможно подключиться к базе данных');
DEFINE('_TEMPLATE_WARN','<font color=\"red\"><strong>Файл шаблона не найден:</strong></font> <br /> Зайдите в Панель управления сайтом и выберите новый шаблон ');


/** pageNavigation - front*/
DEFINE('_PN_LT','&larr;');
DEFINE('_PN_RT','&rarr;');
DEFINE('_PN_PAGE','Страница');
DEFINE('_PN_OF','из');
DEFINE('_PN_START','Первая');
DEFINE('_PN_PREVIOUS','Предыдущая');
DEFINE('_PN_NEXT','Следующая');
DEFINE('_PN_END','Последняя');
DEFINE('_PN_DISPLAY_NR','Отображать');
DEFINE('_PN_RESULTS','Результаты');
/** pageNavigation - admin*/
DEFINE('_NAV_SHOW','Показано');
DEFINE('_NAV_SHOW_FROM','из');
DEFINE('_NO_ITEMS','Записи не найдены');
DEFINE('_NAV_ORDER_UP','Переместить выше');
DEFINE('_NAV_ORDER_DOWN','Переместить ниже');
DEFINE('_PN_ALL','-Все-');
DEFINE('_PN_MOVE_TOP','Передвинуть выше');
DEFINE('_PN_MOVE_DOWN','Передвинуть ниже');

DEFINE('_NEXT','След.');
DEFINE('_NEXT_ARROW',"&nbsp;&raquo;");
DEFINE('_PREV','Пред.');
DEFINE('_PREV_ARROW',"&laquo;&nbsp;");


/**
 * Текст общего назначения
 */
DEFINE('_ACCESS','Доступ'); 
DEFINE('_ACCESS_RIGHTS','Права доступа');
DEFINE('_ADD','Добавить');
DEFINE('_ADVANCED','Дополнительно');
DEFINE('_ALL','Все');
DEFINE('_ALL_CONTENT','Всё содержимое'); 
DEFINE('_ALLOWED','Разрешен'); 
DEFINE('_ALWAYS','Всегда');
DEFINE('_APPLY','Применить');
DEFINE('_ARCHIVE','Архив');  
DEFINE('_ASSIGN','Назначить');
DEFINE('_ASSIGNED_TO','Назначен');
DEFINE('_AUTHOR',' Автор');
DEFINE('_AUTHOR_BY',' Автор');
DEFINE('_AUTHORS','Авторы');
DEFINE('_BACK','Вернуться'); 
DEFINE('_BOTTOM','Внизу');
DEFINE('_BUTTON','Кнопка');
DEFINE('_BUTTON_LOGIN','Войти');
DEFINE('_BUTTON_LOGOUT','Выйти');
DEFINE('_CANCEL','Отмена');
DEFINE('_CAPTION','Заголовок');
DEFINE('_CATEGORIES','Категории');
DEFINE('_CATEGORY','Категория');
DEFINE('_CENTER','По центру');
DEFINE('_CHANGE','Изменить');
DEFINE('_CHECKED_OUT','Заблокировано');
DEFINE('_CHOOSE_IMAGE','Выберите изображение');
DEFINE('_CLOSE','Закрыть'); 
DEFINE('_COMPONENT','Компонент');  
DEFINE('_COMPONENTS','Компоненты'); 
DEFINE('_CONTACT_NAME','Имя: ');
DEFINE('_CONTINUE','Продолжить');
DEFINE('_COPY','Копировать');
DEFINE('_CREATE_ACCOUNT','Регистрация');
DEFINE('_CREATE','Создать'); 
DEFINE('_CREATED','Дата создания:');
DEFINE('_CREATION','Создание');
DEFINE('_DATABASE','База данных');
DEFINE('_DATE','Дата');
DEFINE('_DEFAULT','По умолчанию');
DEFINE('_DELETE','Удалить');
DEFINE('_DELETING','Удаление');
DEFINE('_DESCRIPTION','Описание');
DEFINE('_DETAILS','Детали'); 
DEFINE('_EDIT','Редактировать');
DEFINE('_EDIT_CATEGORY','Редактирование категории');
DEFINE('_EDITING','Редактирование');
DEFINE('_EMAIL','E-mail ');
DEFINE('_END','Окончание');
DEFINE('_EXTENSIONS','Расширения'); 
DEFINE('_FILE','Файл');   
DEFINE('_FILTER','Фильтр');   
DEFINE('_FIRST','Первый');
DEFINE('_FOLDER','Каталог');
DEFINE('_GENERAL','Общее');
DEFINE('_GROUP','Группа');
DEFINE('_HELP','Помощь');
DEFINE('_HI','Здравствуйте, ');
DEFINE('_HIDE','Скрыть');
DEFINE('_HITS','Просмотров');
DEFINE('_HITS_NOT_FOUND','просмотров не было');
DEFINE('_ICON','Значок');
DEFINE('_ICON_SEP','|');
DEFINE('_IMAGE','Изображение'); 
DEFINE('_IMAGES','Изображения');  
DEFINE('_INFO','Информация');
DEFINE('_INPROGRESS','Выполняется...');
DEFINE('_INSTALL','Установить');
DEFINE('_INSTALLATION','Установка');
DEFINE('_LAST','Последний');
DEFINE('_LEFT','Слева');
DEFINE('_MAIN_PAGE','Главная');
DEFINE('_METADATA','Метаданные'); 
DEFINE('_MODULE','Модуль');
DEFINE('_MODULES','Модули');
DEFINE('_MORE','Далее...');
DEFINE('_MOVE','Перенести');
DEFINE('_NAME','Название');  
DEFINE('_NEVER','Никогда');
DEFINE('_NEW','Создать');
DEFINE('_NO','Нет');
DEFINE('_NO_ACCOUNT','Ещё не зарегистрированы?');
DEFINE('_NONE','Нет');
DEFINE('_NOT_CHOOSED','Не выбрано');
DEFINE('_UNPUBLISHED','Не опубликовано');
DEFINE('_OBJECT_MUST_HAVE_TITLE','Этот объект должен иметь заголовок');
DEFINE('_ON_SITE','На сайте'); 
DEFINE('_OPTIONAL','Не обязательно');
DEFINE('_ORDER_BY_DATE_CR','Дате создания');
DEFINE('_ORDER_BY_DATE_MOD','Дате модификации');
DEFINE('_ORDER_BY_HEADERS','Заголовкам');
DEFINE('_ORDER_BY_HITS','Просмотрам');
DEFINE('_ORDER_BY_ID','Идентификаторам ID');
DEFINE('_ORDER_BY_NAME','Внутреннему порядку');
DEFINE('_ORDER_DROPDOWN','Порядок');  
DEFINE('_ORDERING','Сортировка');
DEFINE('_PARAMETERS','Параметры');  
DEFINE('_PARENT','Родитель');
DEFINE('_PASSWORD','Пароль');
DEFINE('_PLEASE_CHOOSE_CATEGORY','Вы должны выбрать категорию');
DEFINE('_PLEASE_CHOOSE_SECTION','Вы должны выбрать раздел');
DEFINE('_PLEASE_WAIT','Ждите...'); 
DEFINE('_PLUGINS','Плагины');
DEFINE('_POSITION','Расположение');  
DEFINE('_PREVIEW','Предпросмотр');
DEFINE('_PRINT','Печать');
DEFINE('_PRINT_PAGE_LINK','Адрес страницы на сайте');
DEFINE('_PUBLISH_ON_FRONTPAGE','Опубликовать на сайте');
DEFINE('_PUBLISHED','Опубликовано');
DEFINE('_PUBLISHING','Публикация');  
DEFINE('_READ_MORE','Подробнее...');
DEFINE('_READ_MORE_REGISTER','Только для зарегистрированных пользователей...');
DEFINE('_REMOVE','Удалить');
DEFINE('_RESET','Обнулить');
DEFINE('_REQUIRED','Обязательно');
DEFINE('_RIGHT','Справа');
DEFINE('_SAVE','Сохранить');
DEFINE('_SAVE_AND_ADD','Сохранить и добавить');  
DEFINE('_SEARCH','Поиск');
DEFINE('_SECTION','Раздел');
DEFINE('_SECTIONS','Разделы');
DEFINE('_SELECT','Выбрать');
DEFINE('_SEND_BUTTON','Отправить'); 
DEFINE('_SHOW','Показать');
DEFINE('_SITE','Сайт'); 
DEFINE('_SITE_2','сайта');
DEFINE('_SORT_ASC','По возрастанию');
DEFINE('_SORT_DESC','По убыванию');
DEFINE('_SORT_NONE','Без сортировки');
DEFINE('_SORT_ORDER','Порядок сортировки'); 
DEFINE('_START','Начало');
DEFINE('_SUBFOLDER','Подкаталог');
DEFINE('_SUBJECT','Тема'); 
DEFINE('_SUBMIT_BUTTON','Отправить');
DEFINE('_TAGS','Тэги:'); 
DEFINE('_TAGS_NOT_DEFINED','Тэги не указаны'); 
DEFINE('_TASK_UPLOAD','Загрузить');
DEFINE('_TEMPLATES','Шаблоны');  
DEFINE('_TEMPLATE','Шаблон'); 
DEFINE('_TIMES','раз');
DEFINE('_TO_BOTTOM','Вниз');
DEFINE('_TO_TOP','Вверх');
DEFINE('_TOP','Вверху');
DEFINE('_TYPE','Тип');
DEFINE('_VERSION','Версия:');
DEFINE('_VIEW_COUNT','Кол-во просмотров');
DEFINE('_USERNAME','Имя пользователя');
DEFINE('_UNKNOWN','Неизвестно');
DEFINE('_USED_ON','Используется');
DEFINE('_USER','Пользователь');
DEFINE('_USERS','Пользователи');  
DEFINE('_WARNCAT','Пожалуйста, выберите категорию');
DEFINE('_WEBLINK','Ссылка');
DEFINE('_LINKS','Ссылки');
DEFINE('_WITHOUT_END','Без окончания');
DEFINE('_WRITEABLE','Доступен для записи');  
DEFINE('_WRITTEN_BY',' Автор');
DEFINE('_YES','Да');
DEFINE('_MALE', 'Мужской');
DEFINE('_FEMALE', 'Женский');
DEFINE('_GENDER_NONE', 'Не указан');
DEFINE('_CACHE','Кэш');
DEFINE('_MESSAGE_ERROR_404','Запрошенная страница не существует');
DEFINE('_MESSAGE_ERROR_403','Доступ к данной странице запрещён');
DEFINE('_REG_CAPTCHA','Введите текст с изображения:*');
DEFINE('_REG_CAPTCHA_VAL','Необходимо ввести код с изображения.');
DEFINE('_REG_CAPTCHA_REF','Нажмите чтобы обновить изображение.');
DEFINE('_YOU_NEED_TO_AUTH','Необходимо авторизоваться');
DEFINE('_ADMIN_SESSION_ENDED','Сессия администратора закончилась');
DEFINE('_YOU_NEED_TO_AUTH_AND_FIX_PHP_INI','Вам необходимо авторизоваться. Если включен параметр PHP session.auto_start или выключен параметр session.use_cookies setting, то сначала вы должны их исправить перед тем, как сможете войти');
DEFINE('_WRONG_USER_SESSION','Неправильная сессия');
DEFINE('_ADM_MENUS_TARGET_CUR_WINDOW','текущем окне с панелью навигации');
DEFINE('_ADM_MENUS_TARGET_NEW_WINDOW_WITH_PANEL','новом окне с панелью навигации');
DEFINE('_ADM_MENUS_TARGET_NEW_WINDOW_WITHOUT_PANEL','новом окне без панели навигации');
DEFINE('_WITH_UNASSIGNED','Со свободными');
DEFINE('_NO_USER','Нет пользователя');
DEFINE('_CREATE_CATEGORIES_FIRST','Сначала необходимо создать категории');
DEFINE('_PUBLISHED_VUT_NOT_ACTIVE','Опубликовано, но <u>Не активно</u>');
DEFINE('_PUBLISHED_AND_ACTIVE','Опубликовано и <u>Активно</u>');
DEFINE('_PUBLISHED_BUT_DATE_EXPIRED','Опубликовано, но <u>Истек срок публикации</u>');
DEFINE('_LINK_NAME','Название ссылки');
DEFINE('_MENU_EXPIRED','Устарело');
DEFINE('_MENU_ITEM_NAME','Название пункта');
DEFINE('_SEL_SECTION','- Выберите раздел -'); 
DEFINE('_SEL_CATEGORY','- Выберите категорию -');  
DEFINE('_SEL_AUTHOR','- Выберите автора -');
DEFINE('_SEL_POSITION','- Выберите позицию -');
DEFINE('_SEL_TYPE','- Выберите тип -'); 
DEFINE('_DEFAULT_IMAGE','- Изображение по умолчанию -');
DEFINE('_DONT_USE_IMAGE','- Не использовать изображение -');
DEFINE('_ET_MENU','- Выберите меню -');
DEFINE('_HANDLER','Обработчик для данного типа отсутствует');
DEFINE('_NO_PARAMS','Объект не содержит настраиваемых параметров');
DEFINE('_INCLUDED_FILES', 'Загружено файлов');
DEFINE('_ERROR_DELETING_CACHE','Ошибка удаления кэша');
DEFINE('_ERROR_READING_CACHE_DIR','Ошибка чтения директории кэша');
DEFINE('_ERROR_READING_CACHE_FILE','Ошибка чтения файла кэша');
DEFINE('_ERROR_WRITING_CACHE_FILE','Ошибка записи файла кэша');
DEFINE('_SCRIPT_MEMORY_USING','Использовано памяти');


/**
 * administrator area
 */
DEFINE('_ADMIN_LOGIN_COUNTER','Количество неудачных попыток авторизации');
DEFINE('_ADMIN_LOGIN_COUNTER2','Количество неудачных попыток, после которых будет показана captcha. -1 - не показывать. 0 - всегда показывать.');
DEFINE('_ADMIN_REDIRECT_PAGE','Указанная');
DEFINE('_ADMIN_SECURE_CODE','Код безопасности для доступа в панель управления');
DEFINE('_ADMIN_SECURE_CODE_HELP','При включении кода безопасности вход в панель управления осуществляется по адресу http://yoursite.ru/administrator/?код_безопасности');
DEFINE('_ADMIN_SECURE_CODE_OPTION','Делать редирект на главную страницу сайта или на страницу определенную администратором');
DEFINE('_ADMIN_SECURE_CODE_REDIRECT_OPTIONS','Режим редиректа');
DEFINE('_ADMIN_SECURE_CODE_REDIRECT_PATH','Пользовательская страница для редиректа, адрес пишется от корня сайта');
DEFINE('_CACHE_DIR','Каталог кэша');  
DEFINE('_CACHE_SYSTEM','Тип кэширующей системы');
DEFINE('_CONFIG_SAVED','Конфигурация успешно сохранена');
DEFINE('_CHANGE_USER_DATA','Изменить данные пользователя'); 
DEFINE('_CHOOSE_MENU_PLEASE','Пожалуйста, выберите меню');
DEFINE('_CHOOSE_MENUTYPE_PLEASE','Пожалуйста, выберите тип меню');
DEFINE('_CMN_ARCHIVE','Добавить в архив');
DEFINE('_CMN_UNARCHIVE','Извлечь из архива');
DEFINE('_CONTROL_PANEL','Панель управления');
DEFINE('_CONTROL_PANEL_2','панели управления');
DEFINE('_CREATE_MENU_ITEM','Создать пункт меню');
DEFINE('_ENABLE_ADMIN_SECURE_CODE','Включить код безопасности для доступа к панели управления');
DEFINE('_ENTER_MENUITEM_NAME','Пожалуйста, введите название для этого пункта меню');
DEFINE('_ERROR_REPORTING','Отображение ошибок');
DEFINE('_EXISTED_MENU_ITEMS','Существующие ссылки меню');
DEFINE('_IMAGES_DIRS','Каталоги изображений (MOSImage)');
DEFINE('_IMAGE_POSTITION','Расположение изображения');
DEFINE('_IN_TRASH','В корзине'); 
DEFINE('_INSTALL_3PD_WARN','Предупреждение: Установка сторонних расширений может нарушить безопасность вашего сайта. При обновлении Joostina! сторонние расширения не обновляются.<br />Для получения дополнительной информации о мерах защиты своего сайта и сервера, пожалуйста, посетите <a href="http://forum.joomla.org/index.php/board,267.0.html" target="_blank" style="color: blue; text-decoration: underline;">Форум безопасности Joomla!</a>.');
DEFINE('_INSTALL_WARN','По соображениям безопасности, пожалуйста, удалите каталог <strong>installation</strong> с вашего сервера и обновите страницу.');
DEFINE('_LOGIN_BLOCKED','Извините, ваша учетная запись заблокирована. За более подробной информацией обратитесь к администратору сайта.');
DEFINE('_LOGIN_INCOMPLETE','Пожалуйста, заполните поля Имя пользователя и Пароль.');
DEFINE('_LOGIN_INCORRECT','Неправильное имя пользователя (логин) или пароль. Попробуйте ещё раз.');
DEFINE('_LOGIN_NOADMINS','Извините, вы не можете войти на сайт. Администраторы на сайте не зарегистрированы.');
DEFINE('_MAINMENU_BOX','Главное меню');
DEFINE('_MAKE_UNWRITEABLE_AFTER_SAVING','Сделать недоступным для записи после сохранения');
DEFINE('_MENU','Меню');
DEFINE('_MENU_ITEMS','Пункты меню');
DEFINE('_MENU_LINK','Связь с меню');   
DEFINE('_MENU_NAME','Название пункта меню');
DEFINE('_MENUITEM','Пункт меню');  
DEFINE('_NEW_ITEM_FIRST','По умолчанию новые объекты будут добавлены в начало списка. Порядок расположения может быть изменен только после сохранения объекта.');
DEFINE('_NEW_ITEM_LAST','По умолчанию новые объекты будут добавлены в конец списка. Порядок расположения может быть изменен только после сохранения объекта.');
DEFINE('_NEWSFLASH_BOX','Краткие новости!');
DEFINE('_E_STATE','Состояние');
DEFINE('_PARENT_MENU_ITEM','Родительский пункт');
DEFINE('_STATIC_CONTENT','Статичное содержимое');
DEFINE('_TRASH','Корзина');
DEFINE('_UMENU_TITLE','Меню пользователя');
DEFINE('_UNWRITEABLE','Недоступен для записи');  
DEFINE('_USER_GROUP_ALL','Общий');
DEFINE('_USER_GROUP_REGISTERED','Участники');
DEFINE('_USER_GROUP_SPECIAL','Специальный');



/**
 * menubar.html.old.php + menubar.html.php
 */
DEFINE('_CMN_EDIT_CSS','Редактировать CSS');
DEFINE('_CMN_EDIT_HTML','Редактировать HTML');
DEFINE('_EDIT_CSS','Ред.&nbsp;CSS');
DEFINE('_EDIT_HTML','Ред.&nbsp;HTML');
DEFINE('_FROM_ARCHIVE','Из&nbsp;архива');
DEFINE('_PLEASE_CHOOSE_ELEMENT','Пожалуйста, выберите элемент.');
DEFINE('_PLEASE_CHOOSE_ELEMENT_FOR_PUBLICATION','Пожалуйста, выберите из списка объекты для их публикации на сайте');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_ARCHIVE','Пожалуйста, выберите из списка объекты для их архивации');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_ASSIGN','Пожалуйста, для назначения объекта выберите его');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_DELETE','Выберите объект из списка для его удаления');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_EDIT','Выберите объект из списка для его редактирования');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_MAKE_DEFAULT','Пожалуйста, выберите объект, чтобы назначить его по умолчанию');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_TRASH','Выберите объект из списка для перемещения его в корзину');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_UNARCHIVE','Выберите объект для восстановления его из архива');
DEFINE('_PLEASE_CHOOSE_ELEMENT_TO_UNPUBLISH','Для отмены публикации объекта, сначала выберите его');
DEFINE('_REALLY_WANT_TO_DELETE_OBJECTS','Вы действительно хотите удалить выбранные объекты?');
DEFINE('_REMOVE_TO_TRASH','В&nbsp;корзину');
DEFINE('_TO_ARCHIVE','В&nbsp;архив');

/**
 * administrator modules includes admin.php
 */
DEFINE('_CACHE_DIR_IS_NOT_WRITEABLE','Пожалуйста, сделайте каталог кэша доступным для записи');
DEFINE('_CACHE_DIR_IS_NOT_WRITEABLE2','Каталог кэша не доступен для записи');
DEFINE('_CLEANING_ADMIN_MENU_CACHE','Ошибка очистки кэша меню панели управления');
DEFINE('_MENU_CACHE_CLEANED','Кэш меню панели управления очищен');
DEFINE('_NO_MENU_ADMIN_CACHE','Кэш меню панели управления не обнаружен. Проверьте права доступа на каталог кэша.');
DEFINE('_PHP_REGISTER_GLOBALS_ON_OFF','PHP register_globals установлено в `ON` вместо `OFF`');
DEFINE('_PHP_SETTINGS_WARNING','Следующие настройки PHP не являются оптимальными для <strong>БЕЗОПАСНОСТИ</strong> и их рекомендуется изменить');


/**
 * administrator modules popups uploadimage.php
 */
DEFINE('_BAD_UPLOAD_FILE_NAME','Имена файлов должны состоять из символов алфавита и не должны содержать пробелов');
DEFINE('_CHOOSE_IMAGE_FOR_UPLOAD','Пожалуйста, выберите изображение для загрузки');
DEFINE('_FILE_MUST_HAVE_THIS_EXTENSION','Файл должен иметь расширение');
DEFINE('_FILE_UPLOAD_SUCCESS','Загрузка файла успешно завершена');
DEFINE('_FILE_UPLOAD_UNSUCCESS','Загрузка файла неудачна');
DEFINE('_IMAGE_ALREADY_EXISST','Изображение уже существует');


/**
 * administrator index.php index2.php index3.php
 */
DEFINE('_ACCESS_DENIED','В доступе отказано');
DEFINE('_BAD_USERNAME_OR_PASSWORDWORD','Неверные имя пользователя, пароль, или уровень доступа.  Пожалуйста, повторите снова');
DEFINE('_BAD_USERNAME_OR_PASSWORDWORD2','Имя или пароль не верны. Повторите ввод.');//not equal to _BAD_USERNAME_OR_PASSWORDWORD!!!
DEFINE('_CHECKIN_OJECT','Разблокировать');
DEFINE('_PLEASE_ENTER_PASSWORDWORD','Пожалуйста, введите пароль');
DEFINE('_TEMPLATE_NOT_FOUND','Шаблон не обнаружен');

/* users */
DEFINE('_USERS_USERLIST','Список пользователей');
DEFINE('_REGISTERED_USERS_COUNT','Зарегистрированно');

/* section,category */
DEFINE('_MENU_LINK_AVAILABLE_AFTER_SAVE','Связь с меню будет доступна после сохранения');
DEFINE('_CATEGORIES_BLOG', 'Блог категории');
DEFINE('_CATEGORIES_ARHIVE', 'Архив категории');
DEFINE('_CATEGORIES_TABLE', 'Таблица содержимого категории');
DEFINE('_TEMPLATE_ITEM_SHOW', 'Страница просмотра записи');
DEFINE('_TEMPLATE_ITEM_EDIT', 'Страница добавления/редактирования записи');

DEFINE('_CONTACT', 'Контакт');

DEFINE('_ALIAS','Псевдоним');

/* content,users,avatars */
DEFINE('_C_USERS_AVATARS_SHOISE','Сменить');
DEFINE('_UPLOADING','Загрузка');

/* captha */
DEFINE('_BAD_CAPTCHA_STRING','Введен неверный код проверки');
DEFINE('_PRESS_HERE_TO_RELOAD_CAPTCHA','Нажмите чтобы обновить изображение');
DEFINE('_SHOW_CAPTCHA','Обновить изображение');
DEFINE('_PLEASE_ENTER_CAPTCHA','Введите код проверки с картинки выше');
DEFINE('_IN_NEW_WINDOW','Открыть в новом окне');
DEFINE('_NO_CAPTCHA_CODE','Не введён код проверки');
DEFINE('_USER_BLOKED','Пользователь заблокирован');

DEFINE('_PREVIEW_SITE','на сайт');

DEFINE('_JWMM_FILESIZE','Размер');
DEFINE('_JWMM_BYTES','байт');
DEFINE('_JWMM_KBYTES','кб');
DEFINE('_JWMM_MBYTES','мб');
DEFINE('_JWMM_FILE_DELETED','Файл успешно удалён');

DEFINE('_CONTENT_PREVIEW','Просмотр содержимого');
DEFINE('_MAX_SIZE','Максимальный размер');
DEFINE('_FILE_UPLOAD','Загрузка файла');

DEFINE('_USER_BLOCK','Блокировка');

DEFINE('_NEW_ORDER_SAVED','Новый порядок сохранен');
DEFINE('_SAVE_ORDER','Сохранить порядок');
DEFINE('_CHANGE_CONTENT','Изменить содержимое');
DEFINE('_CHANGE_CATEGORY','Изменить категорию');

DEFINE('_C_CONTENT_USER_CONTENT','Содержимое пользователя');
DEFINE('_USERS_MALE_S','мужской');
DEFINE('_USERS_FEMALE_S','женский');
DEFINE('_YEAR','год');
DEFINE('_YEAR_','года');
DEFINE('_YEARS','лет');

DEFINE('_REGISTER_REQUIRED','Все поля, отмеченные символом (*), обязательны для заполнения!');
DEFINE('_PAGE_TITLE','Заголовок страницы');
DEFINE('_USER_PROFILE','Профиль пользователя %s');

DEFINE('_TEMPLATE_DIR','Шаблон из');
DEFINE('_TEMPLATE_DIR_DEF','Каталога шаблона');
DEFINE('_TEMPLATE_DIR_SYSTEM','Системный каталог');

DEFINE('_C_USERS_GENDER','Пол');
DEFINE('_C_USERS_B_DAY','Дата рождения');
DEFINE('_C_USERS_DESCRIPTION','О себе');
DEFINE('_USER_CONTACTS','Контакты');
DEFINE('_C_USERS_CONTACT_SITE','Сайт');
DEFINE('_C_USERS_CONTACT_PHONE','Телефон');
DEFINE('_C_USERS_CONTACT_PHONE_MOBILE','Мобильный телефон');
DEFINE('_C_USERS_CONTACT_FAX','Факс');
DEFINE('_C_USERS_AVATARS','Аватар');
DEFINE('_USERS_LOCATION','Местоположение');
DEFINE('_COM_USERS_CONTACT_INFO','Контактная информация');
DEFINE('_USER_NONE_LAST_VISIT','не определено');

DEFINE('_PAGE_ACCESS_DENIED','Извините, к этой странице доступ закрыт');
DEFINE('_CONTENT_TYPED','Статичное содержимое');

DEFINE('_E_ITEM_SAVED','Успешно сохранено!');
DEFINE('_REGWARN_EMAIL_INUSE','Этот e-mail уже используется. Если Вы забыли свой пароль, Нажмите на ссылку "Забыли пароль?" и на указанный e-mail будет выслан новый пароль.');
DEFINE('_REGWARN_INUSE','Это имя пользователя уже используется. Пожалуйста, выберите другое имя.');
DEFINE('_REGWARN_MAIL','Твой email нам необхождим, пожалуйста, введи его и сделай это правильно.');
DEFINE('_PAGES','Страницы');
DEFINE('_LOST_PASSWORDWORD','Забыли пароль?');
DEFINE('_MAIL_SUB','Новый материал от пользователя');
DEFINE('_MAIL_MSG','Здравствуйте $adminName,\n\nПользователь $author предлагает новый материал в раздел $type с названием $title'.
		' для сайта JPATH_SITE.\n\n\n'.
		'Пожалуйста, зайдите в панель администратора по адресу JPATH_SITE/administrator для просмотра и добавления его в $type.\n\n'.
		'На это письмо не надо отвечать, так как оно создано автоматически и предназначено только для уведомления\n');
DEFINE('_THANK_SUB','Спасибо за Ваш материал. Он будет просмотрен администратором перед размещением на сайте.');
DEFINE('_MASS_RESULTS','Результаты массового добавления');
DEFINE('_CANNOT_MOVE_TO_MEDIA','Не могу переместить скачанный файл в каталог <code>/media</code>');
DEFINE('_CLOAKING','Этот e-mail защищен от спам-ботов. Для его просмотра в вашем браузере должна быть включена поддержка Java-script');
DEFINE('_SITE_OFFLINE','Сайт выключен');
DEFINE('_MOVED_TO_TRASH','Отправлено в корзину');
DEFINE('_CATS_TO_COPY','Копируемые категории');
DEFINE('_PROMPT_CLOSE','Закрыть окно');
DEFINE('_REMEMBER_ME','Запомнить');
DEFINE('_ENQUIRY','Контакт');
DEFINE('_WARNING','Предупреждение');
DEFINE('_BROWSER','Браузер (User Agent)');
DEFINE('_MENU_NEXT','Далее');
DEFINE('_MENU_BACK','Назад');
DEFINE('_ITEM_PREVIOUS','&laquo; ');
DEFINE('_ITEM_NEXT',' &raquo;');
DEFINE('_CONTENT_ITEMS_TO_COPY','Копируемое содержимое');
DEFINE('_USER_POSITION','Положение (должность)');
DEFINE('_OBJECTS_DELETED','Объект(ы) успешно удален(ы)');
DEFINE('_PASSWORDWORD','Пароль');
DEFINE('_ALL_SECTIONS','Все разделы');
DEFINE('_MENU_ITEMS_TO_COPY','Будут скопированы следующие пункты');
DEFINE('_PLEASE_ENTER_SUBJECT','Пожалуйста, впишите тему');
DEFINE('_PLEASE_ENTER_MODULE_NAME','Пожалуйста, введите название для нового модуля');
DEFINE('_CONTENT_IMAGES','Изображения содержимого');
DEFINE('_ACTIVE_IMAGE','Активное изображение');
DEFINE('_ALIGN','Выравнивание');
DEFINE('_CANNOT_EDIT_ARCHIVED_ITEM','Вы не можете отредактировать объект архива');
DEFINE('_CAPTION_ALIGN','Выравнивание подписи');
DEFINE('_CAPTION_POSITION','Положение подписи');
DEFINE('_CAPTION_WIDTH','Ширина подписи');
DEFINE('_CHECKED_IN_ITEMS','Проверка');
DEFINE('_SECTION_LIST', 'Список раздела');
DEFINE('_E_EDIT', 'Редактировать');
DEFINE('_SEARCH_BOX','Поиск...');
DEFINE('_GLOBAL_CONFIG','Глобальная конфигурация');
DEFINE('_LANGUAGE_PACKS','Языковые пакеты');
DEFINE('_PAGES_HITS','Статистика посещения страниц');
DEFINE('_BUTTON_LINK_IN_NEW_WINDOW','В новом окне');
DEFINE('_SQL_CONSOLE','SQL консоль');
DEFINE('_XMAP_MSG_NO_SITEMAP', 'Данная карта недоступна');
DEFINE('_CACHE_MANAGEMENT', 'Управление кешем');
DEFINE('_COM_FILES', 'Файловый менеджер');

DEFINE('_GUEST_USER','Гость');