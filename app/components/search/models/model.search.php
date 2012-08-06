<?php

defined('_JOOS_CORE') or exit;

/**
 * modelPages - Модель поиска
 * Модель для работы сайта
 *
 * @version    1.0
 * @package    Components\Pages
 * @subpackage Models\Site
 * @author     Joostina Team <info@joostina.ru>
 * @copyright  (C) 2007-2012 Joostina Team
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * Информация об авторах и лицензиях стороннего кода в составе Joostina CMS: docs/copyrights
 *
 * */
class modelSearch extends joosModel
{
    /**
     * @field int(11) unsigned
     * @type int
     */
    public $id;

    /**
     * @field varchar(255)
     * @type string
     */
    public $word;

    /**
     * @field int(11) unsigned
     * @type int
     */
    public $hit;

    /*
     * Constructor
     */

    public function __construct()
    {
        parent::__construct('#__searched', 'id');
    }

    public function add($word)
    {
        $sql = "INSERT INTO `#__searched` (`word`, `hit`) VALUES ('" . $word . "',1) ON DUPLICATE KEY UPDATE hit=hit+1;";

        return joosDatabase::instance()->set_query($sql)->query();
    }

    public function get_log($word)
    {
        $sql = "SELECT hit AS id, word AS label FROM #__searched WHERE LOWER(word) LIKE LOWER('%{$word}%') ORDER BY hit DESC";

        return joosDatabase::instance()->set_query($sql, 0, 10)->load_assoc_list();
    }

    public function prepare_search_content($text, $length = 200, $searchword = '')
    {
        $text = preg_replace("'<script[^>]*>.*?</script>'si", "", $text);
        $text = preg_replace('/{.+?}/', '', $text);
        $text = preg_replace("'<(br[^/>]*?/|hr[^/>]*?/|/(div|h[1-6]|li|p|td))>'si", ' ', $text);

        return modelSearch::smart_substr(strip_tags($text), $length, $searchword);
    }

    public function smart_substr($text, $length = 200, $searchword = '')
    {
        $wordpos = joosString::strpos(strtolower($text), joosString::strtolower($searchword));
        $halfside = intval($wordpos - $length / 2 - joosString::strlen($searchword));
        if ($wordpos && $halfside > 0) {
            return '...' . joosString::substr($text, $halfside, $length) . '...';
        } else {
            return joosString::substr($text, 0, $length);
        }
    }

}
