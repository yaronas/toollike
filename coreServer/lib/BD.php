<?php

/**
 * Класс: BD
 * @author Хамитов Ильгиз <proroot@proroot.net>
 * @package proroot.net
 */

/**
 * Класс BD - предназначен для работы с базой.
 * Доступен из любой точки программы.
 */

class BD {

	/**
	 * Задаем константы для работы с БД
	 * Записываем в константы:
	 * 1. Host - сервера
	 * 2. Name - логин пользователя
	 * 3. Pass - пароль пользователя
	 * 4. Base - выбираем базу
	 */

	const userHost = '';
	const userName = '';
	const userPass = '';
	const userBase = '';

	/**
	 * Магнитическая функция
	 * Подключаемся к БД, при запуске класса
	 * Устанавливаем кодировку UTF-8
	 */

	static public function userStart () {
		// Бодключаемся к БД
		mysql_connect (self::userHost, self::userName, self::userPass);
		// Выбираем БД
		mysql_select_db (self::userBase);
		// Устанавливаем кодировку с БД
		mysql_query ("SET CHARACTER SET 'utf8'");
        mysql_query ("set character_set_client='utf8'");
        mysql_query ("set character_set_results='utf8'");
        mysql_query ("set collation_connection='utf81_general_ci'");
        mysql_query ("SET NAMES utf8");
	}

    /**
	 * Отправляем SQL - запрос, получаем ответ
	 * Возрашаем ответ
	 */

	static public function userQuery ($userSql) {
		$userSql = mysql_query ($userSql) or AUTH::userErrorMessage(6);
		return $userSql;
	}

	/**
	 * Проверем на присутствие данных переданных SQL - запросом
	 * Если присутствует данное поле возрашаем $userCheckNumBase
	 */

	static public function userSqlNumRows ($userSql) {
		if ($userCheckNumBase = mysql_num_rows (BD::userQuery ($userSql))) {
			return $userCheckNumBase;
		}
	}
}