<?php

/**
 * Класс: USER
 * @author Хамитов Ильгиз <proroot@proroot.net>
 * @package proroot.net
 */

/**
 * Класс USER - предназначен для работы со всеми пользовательскими функциями
 * Доступен из любой точки программы.
 */

class USER {

	/**
	 * Проверяем в базе пользователя
	 * Если пользователя нет в базе, вызываем функцию очистки cookie
	 * Если есть возрашаем нужные данные из базы
	 */

	static public function userCheckBase () {
		$userCheckBase = BD::userQuery ('SELECT `userUid`, `userBlocked`, `userName`, `userNumberJobs`, `userMoney`, `userAdmin`, `userTaskJobs`, `userTaskIgnore` FROM `user_base` WHERE `userLogin` = "'.URL::userXss($_COOKIE['userLogin']).'" AND `userPass` = "'.URL::userXss($_COOKIE['userPass']).'"');
		if (mysql_num_rows ($userCheckBase)) {
    		return mysql_fetch_array ($userCheckBase);
    	} else {
    		URL::userTypeCookie();
    	}
	}

	/**
     * Функция для возрашение имени пользователя.
     */

    static public function userNameTrim ($userNameTrim) {
    	$userNameTrim = explode (' ', $userNameTrim);
    	return $userNameTrim[0];
    }

    /**
     * Функция для приобразовании времени в нормальный вид
     */

    static public function userDateNew ($userTimeBD) {
        $userDateMonth = array (1 => 'янв', 2 => 'фев', 3 => 'мар', 4 => 'апр', 5 => 'мая', 6 => 'июн', 7 => 'июл', 8 => 'авг', 9 => 'сен', 10 => 'окт', 11 => 'ноя', 12 => 'дек');
    
        if (date ('d-Y', $userTimeBD) == date ('d-Y', time ())) {
            return 'cегодня в '.date ('H:i', $userTimeBD);
        } elseif (date ('d-Y', $userTimeBD) == date ('d-Y', strtotime ('- 1 day'))) {
            return 'вчера в '.date ('H:i', $userTimeBD);
        } else {
            return date ('j '.$userDateMonth[date ('n', $userTimeBD)].' Y в H:i', $userTimeBD);
        }
    }
}