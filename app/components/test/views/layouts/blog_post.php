<?php

/**
 * Блог. Список записей
 */
// запрет прямого доступа
defined( '_JOOS_CORE' ) or die();
?>

<article class="post">
    <h1>Шпаргалки для тех, кто делает первые шаги</h1>

    <ul class="post-metadata unstyled">

        <li class="author">
            <i class="icon-user"></i>
            <a rel="author" title="Автор поста: coocheenin" href="#">coocheenin</a>
        </li>

        <li class="date"><i class="icon-time"></i>12 марта, 2012</li>

        <li class="tags">
            <i class="icon-tags"></i>
            <a href="http://uxdesign.smashingmagazine.com/tag/design/">Design</a>,
            <a href="http://uxdesign.smashingmagazine.com/tag/usability/">Usability</a>
        </li>

        <li class="comments">
            <i class="icon-comment"></i>
            <a href="#">35 комментариев</a>
        </li>
    </ul>

    <div class="post-text">
        <img src="http://habrastorage.org/storage2/9d1/9b9/a9c/9d19b9a9c08121358e6e3dd99043bf73.png"><br>
        <br>
        На картинке фрагмент отличной шпаргалки, где собраны основные электронные компоненты &mdash; их внешний вид и обозначения на принципиальных
        схемах.<br>
        <br>
        <a href="http://www.akafugu.jp/images/electronics_reference_sheet.pdf">Шпаргалка по электронным компонентам</a> (PDF, 168Kb)<br>
        <a href="http://www.akafugu.jp/images/microcontroller_reference_sheet.pdf">Шпаргалка по контроллерам AVR (ч.1)</a> (PDF, 61Kb)<br>
        <a href="http://www.akafugu.jp/images/microcontroller_reference_sheet_p2.pdf">Шпаргалка по контроллерам AVR (ч.2)</a> (PDF, 61Kb)<br>
        <br>
        PS: Там же, на сайте, имеется любопытный блог с описанием эффектных электронных поделок. Культура исполнения на высоте, приведены ссылки на
        open source прошивки.
    </div>

    <div class="row">
        <div class="span9">
            <div class="post-tags">
                <strong>Тэги:</strong>
                <a href="#"><span class="label label-info">Design</span></a>
                <a href="#"><span class="label label-info">Usability</span></a>
            </div>
        </div>

        <div class="span3">
            <div class="post-share">
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle">Поделиться<span class="caret"></span></button>
                    <ul class="dropdown-menu">
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Google +</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</article>

<div id="comments">
    <ol class="commentlist unstyled">
        <!--comment-->
        <li class="comment depth-1">
            <span class="commentnumber">1</span>

            <div id="comment-1" class="comment">
                <div class="comment-author">
                    <img class="avatar" src="http://1.gravatar.com/avatar/7ce9cd063e3c0283019c3af83313b5ba?s=38&amp;d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D38&amp;r=G" alt="">

                    <div class="authormeta">
                        <h3 class="comment-author">NickName 1</h3>
                        <span class="datetime">
                            <a href="/#comment-1" title="Commentlink #1">14 марта 2012, 18:12</a>
                        </span>
                    </div>
                </div>

                <div class="comment-text">
                    <p>Слишком крупно :D В свое время на квадратном сантиметре скомканой бумажки помещался ответ на билет) Правда наверно из-за этого зрение испортилось >_< </p>
                </div>

                <div class="commentmeta">
                    <div class="reply">
                        <a  href="#respond" class="comment-reply-link">Ответить</a>
                    </div>

                    <div class="commentrating clearfix">
                        <div class="rateresult positive">+5</div>
                        <div class="btn-group">
                            <a href="#" class="btn btn-mini"><i class="icon-arrow-up"></i></a>
                            <a href="#" class="btn btn-mini"><i class="icon-arrow-down"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </li>

        <li class="comment depth-1">
            <span class="commentnumber">2</span>
            <div id="comment-2" class="comment">
                <div class="comment-author">
                    <img class="avatar" src="http://1.gravatar.com/avatar/7ce9cd063e3c0283019c3af83313b5ba?s=38&amp;d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D38&amp;r=G" alt="">

                    <div class="authormeta">
                        <h3 class="comment-author">NickName 1</h3>
                        <span class="datetime">
                            <a href="/#comment-2" title="Commentlink #1">14 марта 2012, 18:12</a>
                        </span>
                    </div>
                </div>

                <div class="comment-text">
                    <p>Слишком крупно :D В свое время на квадратном сантиметре скомканой бумажки помещался ответ на билет) Правда наверно из-за этого зрение испортилось >_< </p>
                </div>

                <div class="commentmeta">
                    <div class="reply">
                        <a  href="#respond" class="comment-reply-link">Ответить</a>
                    </div>

                    <div class="commentrating clearfix">
                        <div class="rateresult negative">-35</div>
                        <div class="btn-group">
                            <a href="#" class="btn btn-mini"><i class="icon-arrow-up"></i></a>
                            <a href="#" class="btn btn-mini"><i class="icon-arrow-down"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="children unstyled">
                <!--comment-->
                <li class="comment even depth-2 clearfix">
                    <span class="commentnumber">3</span>

                    <div id="comment-3" class="comment">
                        <div class="comment-author">
                            <img class="avatar" src="http://1.gravatar.com/avatar/7ce9cd063e3c0283019c3af83313b5ba?s=38&amp;d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D38&amp;r=G" alt="">

                            <div class="authormeta">
                                <h3 class="comment-author">NickName 1</h3>
                                <span class="datetime">
                                    <a href="/#comment-3" title="Commentlink #1">14 марта 2012, 18:12</a>
                                </span>
                            </div>
                        </div>

                        <div class="comment-text">
                            <p>Слишком крупно :D В свое время на квадратном сантиметре скомканой бумажки помещался ответ на билет) Правда наверно из-за этого зрение испортилось >_< </p>
                        </div>

                        <div class="commentmeta">
                            <div class="reply">
                                <a  href="#respond" class="comment-reply-link">Ответить</a>
                            </div>

                            <div class="commentrating clearfix">
                                <div class="rateresult positive">+5</div>
                                <div class="btn-group">
                                    <a href="#" class="btn btn-mini"><i class="icon-arrow-up"></i></a>
                                    <a href="#" class="btn btn-mini"><i class="icon-arrow-down"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </li>

        <li class="comment depth-1">
            <span class="commentnumber">4</span>
            <div id="comment-4" class="comment">
                <div class="comment-author">
                    <img class="avatar" src="http://1.gravatar.com/avatar/7ce9cd063e3c0283019c3af83313b5ba?s=38&amp;d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D38&amp;r=G" alt="">

                    <div class="authormeta">
                        <h3 class="comment-author">NickName 1</h3>
                        <span class="datetime">
                            <a href="/#comment-4" title="Commentlink #1">14 марта 2012, 18:12</a>
                        </span>
                    </div>
                </div>

                <div class="comment-text">
                    <p>Слишком крупно :D В свое время на квадратном сантиметре скомканой бумажки помещался ответ на билет) Правда наверно из-за этого зрение испортилось >_< </p>
                </div>

                <div class="commentmeta">
                    <div class="reply">
                        <a  href="#respond" class="comment-reply-link">Ответить</a>
                    </div>

                    <div class="commentrating clearfix">
                        <div class="rateresult positive">+5</div>
                        <div class="btn-group">
                            <a href="#" class="btn btn-mini"><i class="icon-arrow-up"></i></a>
                            <a href="#" class="btn btn-mini"><i class="icon-arrow-down"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="children unstyled">
                <!--comment-->
                <li class="comment depth-2 clearfix">
                    <span class="commentnumber">5</span>

                    <div id="comment-5" class="comment">
                        <div class="comment-author">
                            <img class="avatar" src="http://1.gravatar.com/avatar/7ce9cd063e3c0283019c3af83313b5ba?s=38&amp;d=http%3A%2F%2F1.gravatar.com%2Favatar%2Fad516503a11cd5ca435acc9bb6523536%3Fs%3D38&amp;r=G" alt="">

                            <div class="authormeta">
                                <h3 class="comment-author">NickName 1</h3>
                                           <span class="datetime">
                                               <a href="/#comment-5" title="Commentlink #1">14 марта 2012, 18:12</a>
                                           </span>
                            </div>
                        </div>

                        <div class="comment-text">
                            <p>Слишком крупно :D В свое время на квадратном сантиметре скомканой бумажки помещался ответ на билет) Правда наверно
                                из-за этого зрение испортилось >_<
                            </p>
                        </div>

                        <div class="commentmeta">
                            <div class="reply">
                                <a href="#respond" class="comment-reply-link">Ответить</a>
                            </div>

                            <div class="commentrating clearfix">
                                <div class="rateresult positive">+5</div>
                                <div class="btn-group">
                                    <a href="#" class="btn btn-mini"><i class="icon-arrow-up"></i></a>
                                    <a href="#" class="btn btn-mini"><i class="icon-arrow-down"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </li>
    </ol>
</div>

<div id="comments-form">
    <form id="commentform" method="post" action="#">

        <div class="control-group">
            <label for="comment">Текст комментария <span class="required">*</span></label>
            <textarea class="span12" tabindex="4" rows="6" id="comment" name="comment"></textarea>
        </div>
        <button class="btn btn-success" type="submit">Отправить</button>


    </form>
</div>


