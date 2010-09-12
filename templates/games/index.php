<?php

defined('_VALID_MOS') or die();

global $task, $my, $mosConfig_mailfrom;

$option = mosGetParam($_REQUEST,'option');
$task = mosGetParam($_REQUEST,'task', '');
$id = mosGetParam($_REQUEST,'id', '');

echo '<?xml version="1.0" encoding="UTF-8"?' . '>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
		echo Jdocument::head();

		Jdocument::getInstance()
				->addCSS(JPATH_SITE . '/templates/games/css/base.css', array('media' => 'all'))
				->addCSS(JPATH_SITE . '/templates/games/css/layouts.css')
				->addCSS(JPATH_SITE . '/templates/games/css/pages.css')
				->addCSS(JPATH_SITE . '/templates/games/css/jquery-ui-1.8.custom.css')
				->addCSS(JPATH_SITE . '/templates/games/css/jquery.impromptu.css')
				->addCSS(JPATH_SITE . '/templates/games/css/jslider.css')
				->addCSS(JPATH_SITE . '/templates/games/css/jslider.round.plastic.css')
				->addCSS(JPATH_SITE . '/templates/games/css/colorbox.css');

		echo Jdocument::stylesheet();
?>
        <!--[if lte IE 7]><link href="<?php echo JPATH_SITE; ?>/templates/games/css/ie7.css" rel="stylesheet" type="text/css" /><![endif]-->
        <script type="text/javascript">var _live_site = '<?php echo JPATH_SITE; ?>';</script>
    </head>
    <body>
        <!--.color_bg::BEGIN-->
        <div class="content_bg"><div class="color_bg">
                <!--.main_wrap (footer bg)::BEGIN-->
                <div class="main_wrap"> 

                    <!--.wrap (контейнер с фиксированной шириной)::BEGIN-->
                    <div class="wrap">

                        <!--.header::BEGIN-->
                        <div class="header">

                            <h2 class="logo"><a href="/">Игровой сайтик</a></h2>
                            <!--Форма логина/логаута::BEGIN-->                            
                            <?php require_once JPATH_BASE . DS . 'modules/mod_login/mod_login.php'; ?>
                            <!--Форма логина/логаута::END-->

                        </div>
                        <!--.header::END-->

                        <!--.content_wrap::BEGIN-->
                        <div class="content_wrap">
                            <!--.content (основное содержимое страницы)::BEGIN-->
                            <div class="content">
                                <?php echo Jdocument::body(); ?>
                            </div>
                            <!--.content (основное содержимое страницы)::END-->

                            <!--.col (Левая колонка)::BEGIN-->
                            <div class="col_wrap"><div class="col">
                                    <!--.mod_menu (Основное меню)::BEGIN-->
                                    <?php require_once JPATH_BASE . DS . 'modules/mod_menu/mod_menu.php'; ?>
                                    <!--.mod_menu (Основное меню)::END-->

                                    <!--.mod_search(Модуль поиска)::BEGIN-->
                                    <div class="mod_search">
                                        <form action="/index.php" method="get">
                                            <div class="search_input_wrap">
                                                <input type="text" class="inputbox search_input" name="q" />
                                            </div>
                                            <button class="search_button" type="submit">&rarr;</button>
                                            <input type="hidden" name="option" value="search" />
                                            <input type="hidden" name=":antisuf" value="true" />
                                        </form>
                                    </div>
                                    <!--.mod_search(Модуль поиска)::END-->

                                    <!--.ads_left_1(Рекламная позиция в левой колонке №1)::BEGIN-->
                                    <div class="mod_ads_left_1">
                                        <a href="/."><img src="<?php echo JPATH_SITE; ?>/_temp/1.gif" alt="" /></a>
                                    </div>
                                    <!--.ads_left_1(Рекламная позиция в левой колонке №1)::END-->

                                    <!--.mod_same_news (Модуль "Похожие материалы")::BEGIN-->
                                    <?php //require_once JPATH_BASE . DS . 'modules/mod_same_news/mod_same_news.php'; ?>
                                    <!--.mod_same_news (Модуль "Похожие материалы")::END-->

                                    <!--.mod_tag_cloud (Модуль "Облако тэгов")::BEGIN-->
                                    <?php //require_once JPATH_BASE . DS . 'modules/mod_tag_cloud/mod_tag_cloud.php'; ?>
                                    <!--.mod_tag_cloud (Модуль "Облако тэгов")::END-->

                                    <!--.ads_left_2(Рекламная позиция в левой колонке №2)::BEGIN-->
                                    <div class="mod_ads_left_2">
                                        <a href="/."><img src="<?php echo JPATH_SITE; ?>/_temp/adv2.jpg" alt="" /></a>
                                    </div>
                                    <!--.ads_left_2(Рекламная позиция в левой колонке №2)::END-->

                                    <!--<div class="mod_rss"><a class="mod_rss_a" href="/.">Подписка на RSS</a></div>-->

                                </div></div>
                            <!--.col (Левая колонка)::END-->
                        </div>
                        <!--.content_wrap::END-->

                        <!--.footer ::BEGIN-->
                        <div class="footer">                            
                            <span class="copy">MegaPlay.ru &copy; 2010. Все права защищены</span>
                        </div>
                        <!--.footer ::END-->
                    
                    </div>
                    <!--.wrap (контейнер с фиксированной шириной)::END-->

                </div>
         
                
            </div></div>
        <?php

				

			Jdocument::getInstance()
				->addJS(JPATH_SITE.'/media/js/jquery.js', array('first'=>true) )
				->addJS(JPATH_SITE . '/media/js/jquery.ui/jquery-ui.js')
				->addJS(JPATH_SITE . '/templates/games/js/jquery.hoverIntent.js')
				->addJS(JPATH_SITE . '/templates/games/js/jquery.cluetip.js')
				->addJS(JPATH_SITE . '/templates/games/js/easyTooltip.js')
				->addJS(JPATH_SITE . '/templates/games/js/jquery.paginator.js')
				->addJS(JPATH_SITE . '/templates/games/js/slider.js')
				->addJS(JPATH_SITE . '/media/js/jquery.plugins/jquery.colorbox-min.js')
				->addJS(JPATH_SITE . '/templates/games/js/jquery.dependClass.js')
				->addJS(JPATH_SITE . '/templates/games/js/jquery.slider-min.js')
				->addJS(JPATH_SITE . '/media/js/jquery.plugins/jquery.cookie.js')
				->addJS(JPATH_SITE . '/media/js/jquery.plugins/jquery.notifyBar.js')
				->addJS(JPATH_SITE . '/media/js/jquery.plugins/jquery.metadata.js')
				->addJS(JPATH_SITE . '/media/js/jquery.plugins/jquery.tablesorter.min.js')
				->addJS(JPATH_SITE . '/media/js/jquery.plugins/jquery.scrollTo.js')
				->addJS(JPATH_SITE . '/media/js/jquery.plugins/jquery.localscroll.js')
				->addJS(JPATH_SITE . '/media/js/jquery.plugins/jquery.plupload/plupload.full.min.js')
				->addJS(JPATH_SITE . '/media/js/jquery.plugins/jquery.plupload/jquery.plupload.queue.min.js')
				->addJS(JPATH_SITE . '/media/js/jquery.plugins/jquery.validate.js')

				// подключаем js-файл компонента закладок
				->addJS(JPATH_SITE . '/components/com_bookmarks/media/js/bookmarks.js')

				//подключаем js-файл шаблона
				->addJS(JPATH_SITE . '/templates/games/js/template.js');

			echo Jdocument::javascript();
			
        ?>
    </body>
</html>