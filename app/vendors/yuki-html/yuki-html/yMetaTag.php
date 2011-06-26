<?php

/*
 * This file is part of the yuki package.
 * Copyright (c) 2011 olamedia <olamedia@gmail.com>
 *
 * This source code is release under the MIT License.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * yMetaTag
 *
 * @package yuki
 * @subpackage html
 * @author olamedia
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 */
class yMetaTag extends yHtmlTag{
    public function __construct($attr = array()){
        parent::__construct('meta', $attr, true);
    }
    public function setContent($content){
        $this->set('content', $content);
    }
    public function pushContent($content){
        $this->popContent($content);
        $a = explode(',', $this->getAttribute('content'));
        if ($a[0] == '')
            $a = array(); // fix initial array("")


            
//var_dump($a);
        $a[] = $content;
        $this->setAttribute('content', implode(',', $a));
    }
    public function popContent($content){
        $a = explode(',', $this->getAttribute('content'));
        unset($a[$content]);
        if (($k = array_search($content, $a)) !== false){
            unset($a[$k]);
        }
        $this->setAttribute('content', implode(',', $a));
    }
}

