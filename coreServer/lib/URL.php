<?php

/**
 * Класс: URL
 * @author Хамитов Ильгиз <proroot@proroot.net>
 * @package proroot.net
 */

/**
 * Класс URL - предназначен для работы со ссылками, в также с адресной строкой.
 * Доступен из любой точки программы.
 */

class URL {

	/**
	 * Инициализирует данный класс URL,
	 * в дальнейшем доступный из любой точки программы
	 */

	static public function userRoute () {
		// Удаляем не нужный код (HTML или PHP)
		$userRoute = URL::userXss ($_REQUEST['route']);
		// Проверяем директорию на существование
		if (file_exists (DIR.'coreServer/controllers/'.$userRoute.'.php')) {
			return $userRoute;
		} else {
			return 'home';
		}
	}

	/**
	 * Защита от XSS и SQL атак
	 * Возрашаем отформатированную строку
	 */

	static public function userXss ($userRouteXss) {
		return trim (strip_tags (mysql_real_escape_string ($userRouteXss)));
	}

    /**
	 * Получаем только цифры
	 */

	static public function userNumber ($userRouteXss) {
		return preg_replace ('/\D/', '', $userRouteXss);
	}

	/**
	 * Функция служит для декодирование JSON
	 */

	static public function userJsonDecode ($userRequest) {
		return json_decode (file_get_contents ($userRequest), true);
	}

    /**
	 * Передаем значение LOGIN и PASS записываем cookie
	 * Если данных не было передано, очишаем cookie в браузере
	 */

    static public function userTypeCookie ($userLogin = null, $userPass = null) {
        if (($userLogin) && ($userPass)) {
            setcookie ('userLogin', $userLogin, time () + 3600 * 24 * 31);
            setcookie ('userPass', md5 (md5 ($userPass)), time () + 3600 * 24 * 31);
            AUTH::userErrorMessage(2);
        } else {
            setcookie ('userLogin', '', time () - 3600 * 24 * 31);
            setcookie ('userPass',  '', time () - 3600 * 24 * 31);
            header ('Location: .');
        }
    }
}