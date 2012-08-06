<?php

defined('_JOOS_CORE') or die();

/**
 * Компонент Вывода результатов поиска
 * Контроллер сайта
 *
 * @version    1.0
 * @package    Components\Torrents
 * @subpackage Controllers\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class actionsSearch extends joosController
{
    /**
     * Главная страница компонента, вывод списка объектов
     *
     * @static
     * @return array
     */
    public function index()
    {
        $search_result = array();
        $total = 0;

        $search_word = isset(self::$param['search_word']) ? self::$param['search_word'] : '';

        if (isset($_POST['search'])) {

            $search_word = joosRequest::post('search');

            $search_word = joosText::simple_clean($search_word);
            $search_word = joosFilter::htmlspecialchars($search_word);

            joosRoute::redirect(joosRoute::href('search_word', array('search_word' => $search_word)));
        }

        $search_word = joosText::simple_clean($search_word);
        joosFilter::make_safe($search_word);

        if (strlen($search_word) > 100) {
            $search_word = joosString::substr($search_word, 0, 99);
        }

        if ($search_word && joosString::strlen($search_word) < 3) {
            $search_word = '';
        }

        if ($search_word != '') {

	        $results = joosDatabase::instance()->set_query("SELECT t.id, t.title,t.`fulltext` as text, t.type_id, t.type_cat_id, t.created_at, t.anons_image_id, t.file_id,'topic' AS itemtype,
                g.title AS gamename, t.game_id, g.slug AS game_slug
                FROM #__texts as t
                LEFT JOIN #__games AS g ON g.id=t.game_id
                WHERE LOWER(t.title) LIKE LOWER('%{$search_word}%') OR  LOWER(t.`fulltext`) LIKE LOWER('%{$search_word}%') ")->load_object_list();

            $rows = array();
            $_n = count($results);
            for ($i = 0, $n = $_n; $i < $n; $i++) {
                $rows = array_merge((array) $rows, (array) $results[$i]);
            }

            $total = count($rows);

            for ($i = 0; $i < $total; $i++) {
                $text = &$rows[$i]->text;

                $search_words = explode(' ', $search_word);
                $needle = $search_words[0];

                $text = modelSearch::prepare_search_content($text, 500, $needle);

                foreach ($search_words as $k => $hlword) {
                    $search_words[$k] = htmlspecialchars(stripslashes($hlword), ENT_QUOTES, 'UTF-8');
                }

                $searchRegex = implode('|', $search_words);
                $text = preg_replace('/' . $searchRegex . '/iu', '<span class="highlight">\0</span>', $text);

            }

            $search_result = $rows;
        }

        $page = self::$param['page'];

        $pager = new joosPager(joosRoute::href('search_word', array('search_word' => $search_word)), $total, 10);
        $pager->paginate($page);

        // для первой (0) страницы и если есть результаты поиска - запишем словопоиск в базу, для дальнейших ленивых автокомплитов
        ($total > 0 && $page == 0) ? modelSearch::add($search_word) : null;

        return array('search_word' => $search_word, 'search_result' => $search_result, 'pager' => $pager);
    }

}
