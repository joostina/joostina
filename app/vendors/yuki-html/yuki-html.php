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
foreach (array('yHtmlAttribute', 'yHtmlTag', 'yHtmlHelper', 'yHtmlTagList', 'yTextNode', 'yMetaTag', 'yHeadTag', 'yStyleTag') as $tag){
    require_once dirname(__FILE__).'/yuki-html/'.$tag.'.php';
}
