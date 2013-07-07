<?php

/**
 * index.php - точка входа в CMS
 * @author Хамитов Ильгиз <proroot@proroot.net>
 * @package proroot.net
 */

/**
 * Основные настройки:
 * - настраивается вывод ошибок;
 * - устанавлюиваются константы.
 * Основные константы:
 * 1. SITE - Определяем домен сайта;
 * 2. DIR - Корневая папка сайта;
 * 3. TITLE - Заголовок страниц;
 * 4. ERROR - Выводим о не допустимости сайта true/false.
 */


# Настраиваем сервис:
define ('SITE', $_SERVER['HTTP_HOST']);
define ('DIR', $_SERVER['DOCUMENT_ROOT'].'/');
define ('TITLE', 'ToolLike - это бесплатные сердечки вконтакте, накрутка сердечек вконтакте, бесплатно коментарии вконтакте, накрутка расскзать друзьям');
define ('ERROR', false);

# Функция - для автоматической подгрузки классов
function __autoload ($className) {
    include_once ('coreServer/lib/'. $className .'.php');
}

# Старт CMS
require_once ('coreServer/coreStart.php');