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

mosMainFrame::addLib('text');
mosMainFrame::getInstance()->addJS( JPATH_SITE.'/media/js/jquery.plugins/jquery.form.js', 'js' );

?>
<div class="page_user page">
    <?php require_once JPATH_BASE . '/components/com_users/views/navigation/profile.php'; ?>
    <div class="profile_header">
        <div class="user_karma">
            <div class="title">карма</div>
            <div class="karma_rater">
                <a class="karma_plus_1<?php echo ( isset(User::current()->extra()->votes_cache['users'][$user->id]) && User::current()->extra()->votes_cache['users'][$user->id] > 0) ? ' active' : '' ?>" obj_id="<?php echo $user->id ?>" title="Повысить карму" href="#">&nbsp;</a>
                <span class="karma_rate obj_<?php echo $user->id ?>"><big><?php echo $user->votes_count ? $user->votes_count : 0 ?></big> <small><?php echo $user->voters_count ?></small></span>
                <a class="karma_minus_1<?php echo ( isset(User::current()->extra()->votes_cache['users'][$user->id]) && User::current()->extra()->votes_cache['users'][$user->id] < 0) ? ' active' : '' ?>" obj_id="<?php echo $user->id ?>" title="Понизить карму" href="#">&nbsp;</a>
            </div>
        </div>
        <h1><?php echo $user_extra->level>0 ? 'Геймер <strong>' . $user->username. '</strong> ' .$user_extra->level.'-го уровня' : 'Пользователь '.$user->username ?></h1>
        
        <div id="user_rating">
            <big id="user_cur_rating">Рейтинг: <?php echo $user->fullrate ? $user->fullrate : 'пока неизвестен' ?></big>
        </div>
    </div>

    
    <div class="user_maininfo block_wrap">

        <div class="user_main_foto">
            <img src="<?php echo $user->avatar('_200x200') ?>" alt="Это я" />
            <!--<strong class="camera">Фотоальбом:</strong><a class="small" href="#">12 фото</a>-->
        </div>
        <table class="user_info_table">
            <tr>
                <th>Настоящее имя:</th>
                <td><?php echo $user_extra->realname ? $user_extra->realname : 'Не указано' ?></td>
            </tr>
            <tr>
                <th>Откуда:</th>
                <td><?php echo $user_extra->location ? $user_extra->location : 'Не указано' ?></td>
            </tr>
            <tr>
                <th>Возраст:</th>
                <td class="td_bottom_padding">
                    <?php echo $user->age ?>
                    <strong style="padding: 0 0 0 10px;">Пол: </strong>
                    <?php echo $user_extra->gender_text ? $user_extra->gender_text : 'Не указан' ?>
                </td>
            </tr>
            <tr>
                <th>С нами уже:</th>
                <td><?php echo $user->period ?> </td>
            </tr>
            <tr>
                <th>Онлайн был:</th>
                <td class="td_bottom_padding"><?php echo $user->lastvisitDate ?></td>
            </tr>

            <?php if ($user_extra->site) {
            ?>
                        <tr>
                            <th>Сайт:</th>
                            <td><a target="_blank" rel="nofollow" href="<?php echo $user_extra->site ?>">перейти</a></td>
                        </tr>
            <?php } ?>


            <?php
                    $contacts = array('icq' => 'ICQ', 'jabber' => 'Jabber', 'twitter' => 'Twitter', 'skype' => 'Skype');
                    foreach ($contacts as $key => $val) {
                        if ($user_extra->$key) {
            ?>
                            <tr>
                                <th><?php echo $val; ?></th>
                                <td><?php echo $user_extra->$key; ?></td>
                            </tr>
            <?php }
                    } ?>

                </table>

                <br />

        <?php if ($user_extra->about) {
        ?>
                        <p class="about">
                            <strong>О себе:</strong>
            <?php echo $user_extra->about; ?>
                    </p>
        <?php } ?>
                    
                    			<?php if(User::current()->id) : ?>
                                    <div class="buttons_soc">
                                        <a type="2" class="button_send_email button_soc ui-corner-all <?php echo (User::current()->level==0 && User::current()->gid  != 8 ) ? ' checklevel' : '' ?>" href="#">отправить сообщение</a>
                                        
                                        <?php if(User::current()->level>0 || User::current()->gid  == 8 ) : ?>
                                        	<?php require_once JPATH_BASE . '/components/com_users/views/contactform/default.php'; ?>
                                        <?php endif;?>
                                        <!--<a class="button_add_friend button_soc ui-corner-all"  href="#">добавить в друзья</a>-->
                                    </div>
                       			<?php endif;?>
   
</div>

				<?php //Награды
					require_once mosMainFrame::getInstance()->getPath('class', 'com_awards');
					$user->awards = Awards::get_user_awards($user->id);
					if (count($user->awards)) {require_once JPATH_BASE . '/components/com_awards/views/userawards/default.php';} 
				?>

                <?php if(User::current()->id && User::current()->gid == 8) : ?>
                <div class="buttons_admin">
	                <span class="ui-corner-all"><a id="button_edit" class="button_admin" href="<?php echo sefRelToAbs('index.php?option=com_users&task=edit', true) ?>">настройки профиль</a></span>
	            	<span class="ui-corner-all"><a id="button_block" class="button_admin" href="#">заблокировать аккаунт</a></span>
	            	<span class="ui-corner-all"><a id="button_del" class="button_admin" href="#">удалить аккаунт</a></span>
	            	<span class="ui-corner-all"><a id="button_history" class="button_admin" href="#">история репутации</a></span>
	            	<span class="ui-corner-all"><a id="button_award" class="button_admin" href="#">вручить награду</a></span>
	            	<span class="ui-corner-all"><a id="button_punish" class="button_admin" href="#">выписать штраф</a></span>
            	</div>
            	
            	<div id="awarding">
					<?php require_once JPATH_BASE . '/components/com_awards/views/rewardingform/default.php'; ?>            	
            	</div>
            <?php endif;?>

				<?php //Вывод результатов деятельности: BEGIN
					if(count($user->topics_counters) > 0){
			    		$my_results_params = array(
							1 => array(' статью', ' статьи', ' статей' ),
							2 => array(' новость', ' новости', ' новостей' ),
							3 => array(' чит', ' чита', ' читов'),
							4 => array(' файл', ' файла', ' файлов'),
							5 => array(' изображение', ' изображения', ' изображений'),
							6 => array(' видео', ' видео', ' видео'),
							7 => array(' док', ' дока', ' доков')
						);
						$my_results = array();
						foreach($my_results_params as $key=>$text){
							if(isset($user->topics_counters[$key])){
								$my_results[] = $user->topics_counters[$key]['count'].Text::declension($user->topics_counters[$key]['count'], $text); 
							}	
						}						
					}
				?>				
                <div class="user_results">
                    <h2>Результаты деятельности:</h2>

                    <p>
						бесценный наш <strong><?php echo $user->username ?></strong> 
						<?php echo count($user->topics_counters) > 0 ? 'добавил:' : 'ничего на сайт еще не добавил' ?>
						<br />            			
						<?php if($my_results){ echo implode(', ', $my_results); }?>

                	</p>
                	
                	<?php if($user->managed_games):?>
                    <p><strong>смотритель игр:</strong> <br />
                    	<?php foreach($user->managed_games as $game):?>
                        <a class="arrow" href="<?php echo sefRelToAbs('index.php?option=games&task=game&id=' . sprintf('%s:%s', $game->game_id, $game->title)) ?>" title="<?php echo $game->title ?>"><?php echo $game->title ?></a> <br />
                        <?php endforeach;?>
                    </p>                	
                	<?php endif;?>
                	

            	</div>
            	
            	
            	

            <div class="user_favorite_games">
                <h2>Любимые игры: <?php echo count($games_love) ?><?php if (count($games_love) > 15): ?> <small>[<a href="#">показать все</a>]</small><?php endif; ?></h2>
                    <ul>
            <?php $n = 0; ?>
            <?php foreach ($games_love as $game): ?>
                            <li class="arrow"><a href="<?php echo sefRelToAbs('index.php?option=games&task=game&id=' . sprintf('%s:%s', $game->id, $game->title)) ?>" title="<?php echo $game->title ?>"><?php echo $game->title ?></a></li>
            <?php
                            if ($n == 4) {
                                echo '</ul><ul>';
                                $n = 0;
                            } else {
                                ++$n;
                            }
            ?>
            <?php endforeach; ?>
                        </ul>
                    </div>
                    <!--
                        <div class="user_friends">
                            <h2>Друзья: 71 <small>[<a href="#">раскрыть список полностью</a>]</small></h2>

                            <ul>
                                <li><a class="online" href="#">Лукаш</a></li>
                                <li><a class="offline" href="#">AgeN</a></li>
                                <li><a class="online" href="#">alltair</a></li>
                                <li><a class="offline" href="#">BestPro777</a></li>
                                <li><a class="online" href="#">dead-kiril</a></li>
                            </ul>
                            <ul>
                                <li><a class="online" href="#">demamon</a></li>
                                <li><a class="online" href="#">DIMASSS123</a></li>
                                <li><a class="offline" href="#">DJ Dan 3000</a></li>
                                <li><a class="offline" href="#">doper71</a></li>
                                <li><a class="online" href="#">god is a mc</a></li>
                            </ul>
                            <ul>
                                <li><a class="offline" href="#">Лукаш</a></li>
                                <li><a class="online" href="#">AgeN</a></li>
                                <li><a class="online" href="#">alltair</a></li>
                                <li><a class="online" href="#">BestPro777</a></li>
                                <li><a class="offline" href="#">dead-kiril</a></li>
                            </ul>
                        </div>
                    -->
     
     <br/>
    <!-- Новости -->
    <?php if(isset($user->topics_counters[2]) ) :?>
    <h2>Новости:</h2>
    <div class="game_news tabs">
    
        <!--Меню раздела-->
        <ul class="menu_inside_game" id="game_news">
        <?php foreach ($user->news_types as   $topic_key => $topic): ?>
            <?php if (isset($user->topics[$topic_key])): ?>
            <li>
                <a href="#newscat_<?php echo $topic[1] ?>" title="<?php echo $topic[0] ?>">
                    <span><?php echo $topic[0] ?></span>
                </a>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
        </ul>
        <!--//Меню раздела-->
        <?php
            foreach ($user->news_types as $topic_key => $topic){
                if(isset($user->topics[$topic_key])){
                 ?>
                <!--<?php echo $topic[0] ?>-->
                <div id="newscat_<?php echo $topic[1] ?>">
                    <ul class="block_reviews_ul">
                    <?php foreach ($user->topics[$topic_key] as $topic): ?>
                        <?php Topic::prepare($topic); ?>
                        <li>
                            <?php
                            $src = (isset($topic->anons_image) && $topic->anons_image!='') ? $topic->anons_image :  JPATH_SITE.'/media/images/noimage.jpg';
                            $href = sefRelToAbs('index.php?option=topic&task=view&id=' . sprintf('%s:%s', $topic->id, $topic->title));
                            $user_href = sefRelToAbs('index.php?option=com_users&id=' . sprintf('%s:%s', $topic->user_id, $topic->user_login));
                            ?>
                            <a class="thumb" href="<?php echo $href ?>" title="<?php echo $topic->title ?>">
                                <img src="<?php echo $src ?>" alt="<?php echo $topic->title ?>" />
                            </a>

                            <h4><a href="<?php echo $href ?>" title="<?php echo $topic->title ?>"><?php echo $topic->title ?></a></h4>
                            <span class="date">
                                <?php echo $topic->date_info['day'] . ' ' . $topic->date_info['month_name'] . ' ' . $topic->date_info['year'] ?>
                            </span>
                            / <a href="<?php echo $user_href ?>" class="username" title="<?php echo $topic->user_login ?>"><?php echo $topic->user_login ?></a>
                            / <a class="comments_total_link"><?php echo sprintf('%s %s', $topic->comment_count, $topic->comment_count_text) ?></a>

                            <p><?php echo Text::word_limiter( Text::simple_clean($topic->anons), 30) ?></p>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
                 <?php
                }
            }
        ?>

    </div>
    <?php endif; ?>
    <!-- //Новости -->



    <?php if( isset($user->topics_counters[1]) ):?>
    <!--Информация-->
    <h2>Информация:</h2>
    <div class="game_topic tabs">
        <!--Меню раздела-->
        <ul class="menu_inside_game" id="game_topic">
        <?php foreach ($user->topic_types as   $topic_key => $topic): ?>
            <?php if (isset($user->topics[$topic_key])): ?>
            <li>
                <a href="#topiccat_<?php echo $topic[1] ?>" title="<?php echo $topic[0] ?>">
                   <span><?php echo $topic[0] ?></span>
                </a>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
        </ul>
        <!--//Меню раздела-->

        <?php
            foreach ($user->topic_types as $topic_key => $topic){
                if(isset($user->topics[$topic_key])){
                 ?>
                <!--<?php echo $topic[0] ?>-->
                <div id="topiccat_<?php echo $topic[1] ?>">
                    <ul class="block_reviews_ul">
                    <?php foreach ($user->topics[$topic_key] as $topic): ?>
                        <?php Topic::prepare($topic); ?>
                        <li>
                            <?php
                            $src = (isset($topic->anons_image) && $topic->anons_image!='') ? $topic->anons_image :  JPATH_SITE.'/media/images/noimage.jpg';
                            $href = sefRelToAbs('index.php?option=topic&task=view&id=' . sprintf('%s:%s', $topic->id, $topic->title));
                            $user_href = sefRelToAbs('index.php?option=com_users&id=' . sprintf('%s:%s', $topic->user_id, $topic->user_login));
                            ?>
                            <a class="thumb" href="<?php echo $href ?>" title="<?php echo $topic->title ?>">
                                <img src="<?php echo $src ?>" alt="<?php echo $topic->title ?>" />
                            </a>

                            <h4><a href="<?php echo $href ?>" title="<?php echo $topic->title ?>"><?php echo $topic->title ?></a></h4>
                            <span class="date">
                                <?php echo $topic->date_info['day'] . ' ' . $topic->date_info['month_name'] . ' ' . $topic->date_info['year'] ?>
                            </span>
                            / <a href="<?php echo $user_href ?>" class="username" title="<?php echo $topic->user_login ?>"><?php echo $topic->user_login ?></a>
                            / <a class="comments_total_link"><?php echo sprintf('%s %s', $topic->comment_count, $topic->comment_count_text) ?></a>

                            <p><?php echo Text::word_limiter(Text::simple_clean($topic->anons), 30) ?></p>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </div>
                 <?php
                }
            }
        ?>

   </div>
   <!--//Информация-->
   <?php endif; ?>



    <!--Читы-->
    <?php if( isset($user->topics_counters[3]) ):?>
    <h2>Читы:</h2>
    <div class="game_cheats tabs">
        <!--Меню раздела-->
        <ul class="menu_inside_game" id="game_cheats">
        <?php foreach ($user->cheat_types as   $topic_key => $topic): ?>
            <?php if (isset($user->topics[$topic_key])): ?>
            <li>
                <a href="#cheatcat_<?php echo $topic[1] ?>" title="<?php echo $topic[0] ?>">
                   <span><?php echo $topic[0] ?></span>
                </a>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
        </ul>
        <!--//Меню раздела-->

        <?php
            foreach ($user->cheat_types as $topic_key => $topic){
                if(isset($user->topics[$topic_key])){
                 ?>
                <!--<?php echo $topic[0] ?>-->
                <div id="cheatcat_<?php echo $topic[1] ?>">
                    <table class="block_table hidden_th" width="100%">
                        <tr><th width="100px">&nbsp;</th><th>&nbsp;</th><th width="150px">&nbsp;</th></tr>
                        <?php foreach ($user->topics[$topic_key] as $topic): ?>
                            <?php Topic::prepare($topic); ?>
                            <?php
                            $src = (isset($topic->anons_image) && $topic->anons_image!='') ? $topic->anons_image :  JPATH_SITE.'/media/images/noimage.jpg';
                            $href = sefRelToAbs('index.php?option=topic&task=view&id=' . sprintf('%s:%s', $topic->id, $topic->title));
                            $user_href = sefRelToAbs('index.php?option=com_users&id=' . sprintf('%s:%s', $topic->user_id, $topic->user_login));
                        ?>
                        <tr class="row1">
                            <td><?php echo $topic->date_info['day'] . ' ' . $topic->date_info['month_name'] . ' ' . $topic->date_info['year'] ?></td>
                            <td><a href="<?php echo $href ?>" title="<?php echo $topic->title ?>"><?php echo $topic->title ?></a></td>
                            <td>
                            <a href="<?php echo $user_href ?>" class="username" title="<?php echo $topic->user_login ?>"><?php echo $topic->user_login ?></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                 <?php
                }
            }
        ?>

   </div>
   <?php endif; ?>
   <!--//Читы-->

    <?php if( isset($user->topics_counters[4]) ):?>
    <!--Файлы-->
    <h2>Файлы:</h2>
    <div class="game_files tabs">
        <!--Меню раздела-->
        <ul class="menu_inside_game" id="game_files">
        <?php foreach ($user->file_types as   $topic_key => $topic): ?>
            <?php if (isset($user->topics[$topic_key])): ?>
            <li>
                <a href="#filecat_<?php echo $topic[1] ?>" title="<?php echo $topic[0] ?>">
                   <span><?php echo $topic[0] ?></span>
                </a>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
        </ul>
        <!--//Меню раздела-->

        <?php
            foreach ($user->file_types as $topic_key => $topic){
                if(isset($user->topics[$topic_key])){
                 ?>
                <!--<?php echo $topic[0] ?>-->
                <div id="filecat_<?php echo $topic[1] ?>">
                    <table class="block_table hidden_th" width="100%">
                        <tr><th width="100px">&nbsp;</th><th>&nbsp;</th><th width="50px">&nbsp;</th></tr>
                        <?php foreach ($user->topics[$topic_key] as $topic): ?>
                            <?php Topic::prepare($topic); ?>
                            <?php
                            $src = (isset($topic->anons_image) && $topic->anons_image!='') ? $topic->anons_image :  JPATH_SITE.'/media/images/noimage.jpg';
                            $href = sefRelToAbs('index.php?option=topic&task=view&id=' . sprintf('%s:%s', $topic->id, $topic->title));
                            $user_href = sefRelToAbs('index.php?option=com_users&id=' . sprintf('%s:%s', $topic->user_id, $topic->user_login));
                            ?>
                            <tr class="row1">
                                <td><?php echo $topic->date_info['day'] . ' ' . $topic->date_info['month_name'] . ' ' . $topic->date_info['year'] ?></td>
                                <td><a href="<?php echo $href ?>" title="<?php echo $topic->title ?>"><?php echo $topic->title ?></a></td>
                                <td><?php echo File::formatBytes($topic->file_size, 'Mb') ?> mb</td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                 <?php
                }
            }
        ?>
   </div>
   <!--//Файлы-->
   <?php endif; ?>


    <?php if( isset($user->topics_counters[5]) ):?>
    <!--Изображения-->
    <h2>Изображения:</h2>
    <div class="game_images tabs">
        <!--Меню раздела-->
        <ul class="menu_inside_game" id="game_images">
        <?php foreach ($user->image_types as   $topic_key => $topic): ?>
            <?php if (isset($user->topics[$topic_key])): ?>
            <li>
                <a href="#imagescat_<?php echo $topic[1] ?>" title="<?php echo $topic[0] ?>">
                    <span><?php echo $topic[0] ?></span>
                </a>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
        </ul>
        <!--//Меню раздела-->

        <?php
            foreach ($user->image_types as $topic_key => $topic){
                if(isset($user->topics[$topic_key])){
                 ?>
                <!--<?php echo $topic[0] ?>-->
                <div id="imagescat_<?php echo $topic[1] ?>">
                    <div class="slider">
                        <a id="next">Вперед</a>
                        <a id="back">Назад</a>

                        <div class="image_slider">
                            <ul>
                            <?php foreach ($user->topics[31] as $topic):
                                $href = sefRelToAbs('index.php?option=topic&task=view&id=' . sprintf('%s:%s', $topic->id, $topic->title));
                                $src =  JPATH_SITE_IMAGES . '/attachments/topics/' . File::makefilename($topic->file_id) . '/image_200x200.png';
                            ?>
                                <li>
                                    <a href="<?php echo $href ?>" title="<?php echo $topic->title ?>" >
                                    <img src="<?php echo $src ?>" alt="<?php echo $topic->title ?>" /></a>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                 <?php
                }
            }
        ?>
   </div>
   <!--//Изображения-->
   <?php endif; ?>


    <?php if ( isset($user->topics_counters[6]) ) :?>
    <!--Видео-->
    <h2>Видео:</h2>
    <div class="game_video tabs">
        <!--Меню раздела-->
        <ul class="menu_inside_game" id="game_video">
        <?php foreach ($user->video_types as   $topic_key => $topic): ?>
            <?php if (isset($user->topics[$topic_key])): ?>
            <li>
                <a href="#videocat_<?php echo $topic[1] ?>" title="<?php echo $topic[0] ?>">
                    <span><?php echo $topic[0] ?></span>
                </a>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
        </ul>
        <!--//Меню раздела-->

        <?php
            foreach ($user->video_types as $topic_key => $topic){
                if(isset($user->topics[$topic_key])){
                 ?>
                <!--<?php echo $topic[0] ?>-->
                <div id="videocat_<?php echo $topic[1] ?>">
                    <table class="block_table hidden_th" width="100%">
                        <tr><th width="100px">&nbsp;</th><th>&nbsp;</th><th width="50px">&nbsp;</th></tr>
                        <?php foreach ($user->topics[$topic_key] as $topic): ?>
                            <?php Topic::prepare($topic); ?>
                            <?php
                            $src = (isset($topic->anons_image) && $topic->anons_image!='') ? $topic->anons_image :  JPATH_SITE.'/media/images/noimage.jpg';
                            $href = sefRelToAbs('index.php?option=topic&task=view&id=' . sprintf('%s:%s', $topic->id, $topic->title));
                            $user_href = sefRelToAbs('index.php?option=com_users&id=' . sprintf('%s:%s', $topic->user_id, $topic->user_login));
                            ?>
                            <tr class="row1">
                                <td><?php echo $topic->date_info['day'] . ' ' . $topic->date_info['month_name'] . ' ' . $topic->date_info['year'] ?></td>
                                <td><a href="<?php echo $href ?>" title="<?php echo $topic->title ?>"><?php echo $topic->title ?></a></td>
                                <td><a href="<?php echo $user_href ?>" class="username" title="<?php echo $topic->user_login ?>"><?php echo $topic->user_login ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                 <?php
                }
            }
        ?>
   </div>
   <!--//Видео-->
   <?php endif; ?>




    <?php if( isset($user->topics_counters[7]) ):?>
    <!--Доки-->
    <h2>Доки:</h2>
    <div class="game_docs tabs">
        <!--Меню раздела-->
        <ul class="menu_inside_game" id="game_docs">
        <?php foreach ($user->docs_types as   $topic_key => $topic): ?>
            <?php //if (isset($user->topics[$topic_key])): ?>
            <li>
                <a href="#docscat_<?php echo $topic[1] ?>" title="<?php echo $topic[0] ?>">
                    <span><?php echo $topic[0] ?></span>
                </a>
            </li>
            <?php //endif; ?>
        <?php endforeach; ?>
        </ul>
        <!--//Меню раздела-->

        <?php
            foreach ($user->docs_types as $topic_key => $topic){
                if(isset($user->topics[$topic_key])){
                 ?>
                <!--<?php echo $topic[0] ?>-->
                <div id="docscat_<?php echo $topic[1] ?>">
                    <table class="block_table hidden_th" width="100%">
                        <tr><th width="100px">&nbsp;</th><th>&nbsp;</th><th width="50px">&nbsp;</th></tr>
                        <?php foreach ($user->topics[$topic_key] as $topic): ?>
                            <?php Topic::prepare($topic); ?>
                            <?php
                            $src = (isset($topic->anons_image) && $topic->anons_image!='') ? $topic->anons_image :  JPATH_SITE.'/media/images/noimage.jpg';
                            $href = sefRelToAbs('index.php?option=topic&task=view&id=' . sprintf('%s:%s', $topic->id, $topic->title));
                            $user_href = sefRelToAbs('index.php?option=com_users&id=' . sprintf('%s:%s', $topic->user_id, $topic->user_login));
                            ?>
                            <tr class="row1">
                                <td><?php echo $topic->date_info['day'] . ' ' . $topic->date_info['month_name'] . ' ' . $topic->date_info['year'] ?></td>
                                <td><a href="<?php echo $href ?>" title="<?php echo $topic->title ?>"><?php echo $topic->title ?></a></td>
                                <td><a href="<?php echo $user_href ?>" class="username" title="<?php echo $topic->user_login ?>"><?php echo $topic->user_login ?></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                 <?php
                }
            }
        ?>
   </div>
   <!--//Доки-->
   <?php endif; ?>

                                        
</div>