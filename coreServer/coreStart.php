<?php

/**
 * cms-start.php - точка входа в CMS
 * @author Хамитов Ильгиз <proroot@proroot.net>
 * @package proroot.net
 */


/**
 * cms-start.php - запускает движок и выводит на экран возвращенные им днные.
 * Инициализирует компоненты CMS, доступные из любой точки программы.
 * Проверяем работу сайта (реконструкция)
 * Покдлючает классы CMS:
 * 1. DB - класс для работы с БД;
 */

/**
 * Если сайт находится на реконструкции
 * то выводим текст реконструкции
 */

if ((ERROR) && ($_COOKIE['userLogin'] != 'proroot@proroot.net')) {
	require_once ('controllers/error.php');
	exit();
}

# Инициализация CMS
BD::userStart();
$userAPP = new APP;