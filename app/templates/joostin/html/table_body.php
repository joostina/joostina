<?php
// запрет прямого доступа
defined('_JOOS_CORE') or die();

?>

<table class="table table-bordered table-admin">
    <thead>
    <tr>
        <th>
            <input type="checkbox" name="toggle" value="" class="js-select_all">
        </th>

        <th>
            <span class="js-order-toggle g-pseudolink" title="Сортировать">ID</span>
            <i class="icon-arrow-down"></i>
        </th>

        <th width="20%">

            <div class="search-by-field_state1">
                <div class="column-header_control">
                    <div class="btn-group">
                        <button data-search="login" class="btn btn-mini js-search-by-field">
                            <i class="icon-search"></i>
                        </button>
                    </div>
                </div>

                        <span class="column-header_txt js-order-toggle g-pseudolink">
                            Логин
                        </span>
            </div>

            <div class="search-by-field_state2 hide">
                <input type="text" name="search_login" placeholder="Поиск" class="search-by-field">
            </div>

        </th>

        <th>
            <div class="column-header_control">
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-mini dropdown-toggle">
                        <i class="icon-cog"></i> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu  bottom-up pull-right">
                        <li><a href="#">Все</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Супер администраторы</a></li>
                        <li><a href="#">Администраторы</a></li>
                        <li><a href="#">Модераторы</a></li>
                        <li><a href="#">Пользователи</a></li>
                    </ul>
                </div>
            </div>

            <span class="column-header_txt">Группа</span>
        </th>

        <th>
            <span class="js-order-toggle g-pseudolink column-header_txt">Зарегистрирован</span>
        </th>
        <th>
            <span class="js-order-toggle g-pseudolink column-header_txt">Последнее посещение</span>
        </th>

        <th width="130">
            <div class="column-header_control">
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-mini dropdown-toggle">
                        <i class="icon-cog"></i> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu  bottom-up pull-right">
                        <li><a href="#">Все</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Разрешены</a></li>
                        <li><a href="#">Не разрешены</a></li>
                    </ul>
                </div>
            </div>
            <span class="column-header_txt">Разрешен</span>
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><input type="checkbox" name="toggle" value="" class="js-select"></td>
        <td>1</td>
        <td><a href="#">admin</a></td>
        <td>Супер администраторы [6]</td>
        <td>5 июня, 2004</td>
        <td>22 мая, 2012, 12:26</td>
        <td><a href="#" title="Запретить"><i class="icon-ok"></i></a></td>
    </tr>

    <tr>
        <td><input type="checkbox" name="toggle" value="" class="js-select"></td>
        <td>2</td>
        <td><a href="#">NickName</a></td>
        <td>Пользователь [7]</td>
        <td>5 июня, 2004</td>
        <td>22 мая, 2012, 12:26</td>
        <td><a href="#" title="Запретить"><i class="icon-ok"></i></a></td>
    </tr>
    </tbody>
</table>