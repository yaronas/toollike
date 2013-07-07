<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>

    <head>
        <title> <?= TITLE ?> </title>

        <meta name="description" content="ToolLike - это бесплатные сердечки вконтакте, накрутка сердечек вконтакте, бесплатно коментарии вконтакте, накрутка расскзать друзьям, накрутка коментариев к записи"/>
        <meta name="keywords" content="ToolLike, сердечки вконтакте, накрутка сердечек вконтакте, бесплатные сердечки вконтакте, тооллайк, сервис для обмена лайками, купить лайк, продажа лайков, купить сердечки вконтакте, подписчики вконтакте, накрутка подписчиков вконтакте, программа вконтакте подписчики, скачать вконтакте подписчики, бесплатно подписчики вконтакте, вк накрутка, vk накрутка, тоол лайк"/>
        <meta http-equiv="expires" content=""/>
        <meta http-equiv="content-language" content="ru"/>
        <meta name="robots" content="index,follow"/>

        <link type="text/css" href="/coreServer/templates/css/profile.css?11" rel="stylesheet" /> 
        <link rel="SHORTCUT ICON" type="image/x-icon" href="/coreServer/templates/images/favicon.ico">

        <script type="text/javascript" src="/coreServer/templates/js/userJquery.js"> </script>
        <script type="text/javascript" src="/coreServer/templates/js/userOther.js?100500"> </script>
    </head>

    <body>
        <div id="page">
            <div id="panel_wrap">
                <div id="inner">
                    <div class="overflow">
                        <div class="left">
                            <div id="logo_wrap">
                                <a id="l" href="/home">
                                    <div id="logo"> </div>
                                </a>
                            </div>
                        <div class="right">
                            <div id="menu">
                                <a href="/home">Список заданий</a>
                                <a href="/jobMy">Мои задания</a>
                                <a href="/balance">Баланс</a>
                                <a href="/support">Помощь</a>
                                <a href="/logout">Выход</a>
                            </div>
                        </div>
                        </div>
                    </div> 
                </div>
            </div>
            <a id="move_up" href="#"> <div id="stl_text"> <b>Наверх</b> </div></a>
            <div id="error_head" style="display: none;"></div>
            <div id="content_wrap">
                <div id="content">
                  <div id="menu">
                    <div class="links">
                        <a class="active" href="http://vk.com/id<?= $this->userBaseInfo['userUid']; ?>" target="_blank">Моя Cтраница</a>
                        <a class="active" href="/home">Список заданий</a>
                        <a class="active" href="/job">Новое задание</a>
                        <a class="active" href="/jobMy">Мои задания</a>
                        <a class="active" href="/settings">Мои Настройки</a>
                    </div>
                    <br />
                    <div id="news_hr"> </div>
                    <div id="news_left">
                      <div id="text">
                        <a href="http://copy-vk.ru/"><b>COPY-VK.ru</b></a> <br /> Новое средство граббинга контента с групп VK
                      </div>
                    </div>
                    <br />
                    <div id="news_hr"> </div>
                    <div id="news_left">
                      <div id="text">
                        <a href="http://toollike.ru/"><b>ToolLike.ru</b></a> <br /> продается, по всем вопросам писать <a href="//vk.com/proroot___net" target="_blank"><b>сюда</b></a>
                      </div>
                    </div>
                  </div>
                  <div id="right_wrap">
                      <div id="right">