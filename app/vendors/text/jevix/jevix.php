<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2007-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
defined('_JOOS_CORE') or die();

// на основе LS
class JJevix
{

    private $oJevix;

    public function __construct()
    {
        require_once 'jevix.class.php';
        $this->oJevix = new Jevix();
        $this->config();
    }

    private function config()
    {
        $this->oJevix->cfgAllowTags(array('cut', 'a', 'img', 'i', 'b', 'u', 's', 'video', 'em', 'strong', 'nobr', 'li', 'ol', 'ul', 'sup', 'abbr', 'sub', 'acronym', 'h4', 'h5', 'h6', 'br', 'hr', 'pre', 'code', 'object', 'param', 'embed', 'blockquote'));
        // Коротие теги типа
        $this->oJevix->cfgSetTagShort(array('br', 'img', 'hr', 'cut'));
        // Преформатированные теги
        $this->oJevix->cfgSetTagPreformatted(array('pre', 'code', 'video'));
        // Разрешённые параметры тегов
        $this->oJevix->cfgAllowTagParams('img', array('src', 'alt' => '#text', 'title', 'align' => array('right', 'left', 'center'), 'width' => '#int', 'height' => '#int', 'hspace' => '#int', 'vspace' => '#int'));
        $this->oJevix->cfgAllowTagParams('a', array('title', 'href', 'rel'));
        $this->oJevix->cfgAllowTagParams('cut', array('name'));
        $this->oJevix->cfgAllowTagParams('object', array('width' => '#int', 'height' => '#int', 'data' => '#link'));
        $this->oJevix->cfgAllowTagParams('param', array('name' => '#text', 'value' => '#text'));
        $this->oJevix->cfgAllowTagParams('embed', array('src' => '#image', 'type' => '#text', 'allowscriptaccess' => '#text', 'allowfullscreen' => '#text', 'width' => '#int', 'height' => '#int', 'flashvars' => '#text', 'wmode' => '#text'));
        // Параметры тегов являющиеся обязательными
        $this->oJevix->cfgSetTagParamsRequired('img', 'src');
        $this->oJevix->cfgSetTagParamsRequired('a', 'href');
        // Теги которые необходимо вырезать из текста вместе с контентом
        $this->oJevix->cfgSetTagCutWithContent(array('script', 'iframe', 'style'));
        // Вложенные теги
        $this->oJevix->cfgSetTagChilds('ul', array('li'), false, true);
        $this->oJevix->cfgSetTagChilds('ol', array('li'), false, true);
        $this->oJevix->cfgSetTagChilds('object', 'param', false, true);
        $this->oJevix->cfgSetTagChilds('object', 'embed', false, false);
        // Если нужно оставлять пустые не короткие теги
        $this->oJevix->cfgSetTagIsEmpty(array('param', 'embed'));
        // Не нужна авто-расстановка <br>
        $this->oJevix->cfgSetTagNoAutoBr(array('ul', 'ol', 'object'));
        // Теги с обязательными параметрами
        $this->oJevix->cfgSetTagParamDefault('embed', 'wmode', 'opaque', true);
        if (true) {
            $this->oJevix->cfgSetTagParamDefault('a', 'rel', 'nofollow', true);
        }
        // Отключение авто-добавления <br>
        $this->oJevix->cfgSetAutoBrMode(true);
        // Автозамена
        $this->oJevix->cfgSetAutoReplace(array('+/-', '(c)', '(с)', '(r)', '(C)', '(С)', '(R)'), array('±', '©', '©', '®', '©', '©', '®'));
        //$this->oJevix->cfgSetXHTMLMode(false);
        $this->oJevix->cfgSetTagNoTypography('code');
        $this->oJevix->cfgSetTagNoTypography('video');
        $this->oJevix->cfgSetTagNoTypography('object');
        // Теги, после которых необходимо пропускать одну пробельную строку
        $this->oJevix->cfgSetTagBlockType(array('h4', 'h5', 'h6', 'ol', 'ul', 'blockquote', 'pre'));
    }

    /**
     * Парсит текст
     *
     * @param string $sText
     */
    public function Parser($sText)
    {
        $sResult = $this->FlashParamParser($sText);
        $sResult = $this->JevixParser($sResult);
        $sResult = $this->VideoParser($sResult);
        $sResult = $this->CodeSourceParser($sResult);
        if (false) {
            // требует доработки, т.к. обрабатывает ВСЕ ссылки, включая в <code></code>
            $sResult = $this->MakeUrlNoIndex($sResult);
        }
        return $sResult;
    }

    /**
     * Парсинг текста с помощью Jevix
     *
     * @param string $sText
     * @param array $aError
     * @return string
     */
    public function JevixParser($sText, &$aError = null)
    {
        $sResult = $this->oJevix->parse($sText, $aError);
        return $sResult;
    }

    /**
     * Парсинг текста на предмет видео
     *
     * @param string $sText
     * @return string
     */
    public function VideoParser($sText)
    {
        /**
         * youtube.com
         */
        $sText = preg_replace('/<video>http:\/\/(?:www\.|)youtube\.com\/watch\?v=([a-zA-Z0-9_\-]+)<\/video>/Ui', '<object width="500" height="344"><param name="movie" value="http://www.youtube.com/v/$1&hl=en"></param><param name="wmode" value="opaque"></param><embed src="http://www.youtube.com/v/$1&hl=en" type="application/x-shockwave-flash" wmode="opaque" width="425" height="344"></embed></object>', $sText);
        /**
         * rutube.ru
         */
        $sText = preg_replace('/<video>http:\/\/(?:www\.|)rutube.ru\/tracks\/\d+.html\?v=([a-zA-Z0-9_\-]+)<\/video>/Ui', '<OBJECT width="470" height="353"><PARAM name="movie" value="http://video.rutube.ru/$1"></PARAM><PARAM name="wmode" value="opaque"></PARAM><PARAM name="allowFullScreen" value="true"></PARAM><PARAM name="flashVars" value="uid=662118"></PARAM><EMBED src="http://video.rutube.ru/$1" type="application/x-shockwave-flash" wmode="opaque" width="470" height="353" allowFullScreen="true" flashVars="uid=662118"></EMBED></OBJECT>', $sText);
        return $sText;
    }

    /**
     * Производить резрезание текста по тегу <cut>.
     * Возвращаем массив вида:
     * array(
     *         $sTextShort - текст до тега <cut>
     *         $sTextNew   - весь текст за исключением удаленного тега
     *         $sTextCut   - именованное значение <cut>
     * )
     *
     * @param  string $sText
     * @return array
     */
    public function Cut($sText)
    {
        $sTextShort = $sText;
        $sTextNew = $sText;
        $sTextCut = null;

        $sTextTemp = str_replace("\r\n", '[<rn>]', $sText);
        $sTextTemp = str_replace("\n", '[<n>]', $sTextTemp);

        if (preg_match("/^(.*)<cut(.*)>(.*)$/Ui", $sTextTemp, $aMatch)) {
            $aMatch[1] = str_replace('[<rn>]', "\r\n", $aMatch[1]);
            $aMatch[1] = str_replace('[<n>]', "\r\n", $aMatch[1]);
            $aMatch[3] = str_replace('[<rn>]', "\r\n", $aMatch[3]);
            $aMatch[3] = str_replace('[<n>]', "\r\n", $aMatch[3]);
            $sTextShort = $this->Parser($aMatch[1]);
            $sTextNew = $this->Parser($aMatch[1] . ' ' . $aMatch[3]);
            if (preg_match('/^\s*name\s*=\s*"(.+)"\s*\/?$/Ui', $aMatch[2], $aMatchCut)) {
                $sTextCut = trim($aMatchCut[1]);
            }
        }

        return array($sTextShort, $sTextNew, $sTextCut);
    }

    /**
     * Делает ссылки не видимыми для поисковиков
     *
     * @param unknown_type $sText
     * @return unknown
     */
    public function MakeUrlNoIndex($sText)
    {
        return preg_replace("/(<a .*>.*<\/a>)/Ui", "<noindex>$1</noindex>", $sText);
    }

    /**
     * Заменяет все вхождения короткого тега <param/> на длиную версию <param></param>
     * Заменяет все вхождения короткого тега <embed/> на длиную версию <embed></embed>
     *
     */
    protected function FlashParamParser($sText)
    {
        if (preg_match_all("@(<\s*param\s*name\s*=\s*\".*\"\s*value\s*=\s*\".*\")\s*/?\s*>(?!</param>)@Ui", $sText, $aMatch)) {
            foreach ($aMatch[1] as $key => $str) {
                $str_new = $str . '></param>';
                $sText = str_replace($aMatch[0][$key], $str_new, $sText);
            }
        }
        if (preg_match_all("@(<\s*embed\s*.*)\s*/?\s*>(?!</embed>)@Ui", $sText, $aMatch)) {
            foreach ($aMatch[1] as $key => $str) {
                $str_new = $str . '></embed>';
                $sText = str_replace($aMatch[0][$key], $str_new, $sText);
            }
        }
        /**
         * Удаляем все <param name="wmode" value="*"></param>
         */
        if (preg_match_all("@(<param\s.*name=\"wmode\".*>\s*</param>)@Ui", $sText, $aMatch)) {
            foreach ($aMatch[1] as $key => $str) {
                $sText = str_replace($aMatch[0][$key], '', $sText);
            }
        }
        /**
         * А теперь после <object> добавляем <param name="wmode" value="opaque"></param>
         * Решение не фантан, но главное работает :)
         */
        if (preg_match_all("@(<object\s.*>)@Ui", $sText, $aMatch)) {
            foreach ($aMatch[1] as $key => $str) {
                $sText = str_replace($aMatch[0][$key], $aMatch[0][$key] . '<param name="wmode" value="opaque"></param>', $sText);
            }
        }

        return $sText;
    }

    public function CodeSourceParser($sText)
    {
        $sText = str_replace("<code>", '<pre class="prettyprint"><code>', $sText);
        $sText = str_replace("</code>", '</code></pre>', $sText);
        return $sText;
    }

}