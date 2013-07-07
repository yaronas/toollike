<?php

/**
 * Класс: AUTH
 * @author Хамитов Ильгиз <proroot@proroot.net>
 * @package proroot.net
 */

/**
 * Класс AUTH - предназначен для работы с аторизации пользователя, а так же с регистрации и восстановление пароля
 * Доступен из любой точки программы.
 */

class AUTH {


    /**
	 * Главная часть класса вызывается с APP
	 * Получает GET, POST - запроос. Получаемт method
	 * Если найден нужный параметр, запскем его прерываем проверку
	 */

	static public function userStart () {
		switch ($_REQUEST['method']) {
            case 'login': {
                AUTH::login();
                break;
            }

            case 'reg': {
                AUTH::reg();
                break;
            }

            case 'restore': {
                AUTH::restore();
                break;
            }

            default: {
            	exit;
            }
        }
	}

	/**
	 * Функция служит для декодирование регистрации пользователя
	 * Проверяем на true все полученные параметры
	 * Экранируем XSS и SQL атаки
	 */

	static public function reg () {
		if ((URL::userXss($_POST['uid'])) && (URL::userXss($_POST['login'])) && ($_POST['password'])) {
			// Проверяем uid пользователя в базе на не существование
			if (AUTH::userCheckUidBase (URL::userNumber($_POST['uid']))) {
				// Проверяем логин пользователя в базе на не существование
				if (AUTH::userCheckLoginBase (URL::userXss($_POST['login']))) {
					// Проверяем статус пользователя (подверждаем законость страницы)
					if ($userInfo = AUTH::userCheckVk (URL::userXss($_POST['uid']))) {
						// Записываем в базу и cookie в браузер
						BD::userQuery ('INSERT INTO `user_balance` SET `userUid` = "'.$userInfo['response'][0]['uid'].'", `userType` = "userSystem", `userCount` = "+200", `userText` = "Бонус за регистрацию", `userDate` = "'.time().'"');
						BD::userQuery ('INSERT INTO `user_base` SET `userLogin` = "'.URL::userXss ($_POST['login']).'", `userPass` = "'.md5 (md5 (URL::userXss ($_POST['password']))).'", `userUid` = "'.$userInfo['response'][0]['uid'].'", `userName` = "'.$userInfo['response'][0]['first_name'].' '.$userInfo['response'][0]['last_name'].'", `userPhoto` = "'.$userInfo['response'][0]['photo'].'", `userMoney` = "200", `userNumberJobs` = "0", `userTaskJobs` = "0", `userTaskIgnore` = "0", `userBrowser` = "'.$_SERVER['HTTP_USER_AGENT'].'", `userRegTime`= "'.time().'", `userAuthTime` = "'.time().'"');
						URL::userTypeCookie (URL::userXss ($_POST['login']), URL::userXss ($_POST['password']), $userInfo['response'][0]['uid']);
					}
				}
			}
		}
	}

	/**
	 * Функция служит для авторизации пользователя
	 * Проверяем на true все полученные параметры
	 * Экранируем XSS и SQL атаки
	 * Если все нормально записываем новое время входа и cookie в браузер
	 */

	static public function login () {
		if ((URL::userXss ($_POST['login'])) && ($_POST['password'])) {
			// Если есть в базе, то возрашет на true
			if (AUTH::userCheckBase (URL::userXss ($_POST['login']), URL::userXss ($_POST['password']))) {
				// Перезаписываем новое время авторизации пользователя
				BD::userQuery ('UPDATE `user_base` SET `userAuthTime` = "'.time().'" WHERE `userLogin` = "'.URL::userXss ($_POST['login']).'"');
				URL::userTypeCookie (URL::userXss ($_POST['login']), $_POST['password']);
			}
		}
	}

    /**
	 * Функция служит для восстановление пароля
	 * Проверяем на true все полученные параметры
	 * Экранируем XSS и SQL атаки
	 * Проверяем логин в базе на существование
	 * Просеряем статус пользователя в VK
	 * Если все ок перезаписываем пароль пользователя
	 */

	static public function restore () {
		if ((URL::userXss ($_POST['login'])) && ($_POST['new_password'])) {
			// Проверяем логин на существование в базе
			$userCheckLogin = BD::userQuery('SELECT `userLogin`, `userUid` FROM `user_base` WHERE `userLogin` = "'.URL::userXss ($_POST['login']).'"');
            if (mysql_num_rows($userCheckLogin)) {
            	$userCheckUid = mysql_fetch_array ($userCheckLogin);
            	// Проверяем статус пользователя VK
            	$userCheckUidVk = URL::userJsonDecode ('https://api.vk.com/method/status.get?uid='.$userCheckUid['userUid'].'&access_token=d24fd61b9e1ebd4d6f969883990e2b014fcc02d61f8bee274ced5a19f3628e6aa3a6485c68d82876ff85e');
		        if ($userCheckUidVk['response']['text'] == 'Я люблю тебя') {
		        	// Перезаписываем пароль пользователя в базу
		        	BD::userQuery('UPDATE `user_base` SET `userPass` = "'.md5 (md5 ($_POST['new_password'])).'" WHERE `userUid` = "'.$userCheckUid['userUid'].'"');
                    AUTH::userErrorMessage(1);
		        } else {
		        	AUTH::userErrorMessage(2);
		        }
            } else {
            	AUTH::userErrorMessage(3);
            }
		}
	}

	/**
	 * Функция служит для вывода ошибок файлу, который обрабатывает id ошибок
	 * Посылает запрос обработчику и завершает весь процесс работы сервиса
	 */

	static public function userErrorMessage ($userNumberError) {
		echo $userNumberError;
		exit;
	}

	/**
	 * Проверяем пользователя статус VK для дальнейший процесса регистрации
	 * Если статус установлен, получаем информацию по пользователю
	 * Если ним чего нет, возрашаем ошибку
	 */

    static public function userCheckVk ($userUid) {
		$userCheckVk = URL::userJsonDecode ('https://api.vk.com/method/users.get?fields=photo&uids='.self::userUidVkCheck($userUid));
		$userCheckStatusVk = URL::userJsonDecode ('https://api.vk.com/method/status.get?uid='.$userCheckVk['response'][0]['uid'].'&access_token=d24fd61b9e1ebd4d6f969883990e2b014fcc02d61f8bee274ced5a19f3628e6aa3a6485c68d82876ff85e');
		if ($userCheckStatusVk['response']['text'] == 'Я люблю тебя') {
			return $userCheckVk;
		} else {
			AUTH::userErrorMessage(3);
		}
	}

	/**
	 * Проверяем id пользователя на коректность
	 */

	static public function userUidVkCheck ($userUid) {
		if (preg_match('/vk.com\/[A-z0-9]+/', $userUid, $userUid)) {
			return str_replace ('vk.com/', '', $userUid[0]);
		}
	} 

	/**
	 * Проверяет правильность веденных данных в поле авторизации
	 * Если пользователя нет в базе, возращаем ошибку
	 */

    static public function userCheckBase ($userLogin, $userPass) {
    	if ($userCheckBase = BD::userSqlNumRows ('SELECT `id` FROM `user_base` WHERE `userLogin` = "'.$userLogin.'" AND `userPass` = "'.md5 (md5 ($userPass)).'"')) {
    		return true;
    	} else {
    		AUTH::userErrorMessage(1);
    	}
    }

	/**
	 * Проверяем UID пользователя в базе
	 * Если, есть то выводим ошибку, если нет то возрашаем true
	 * Служит для формы регистрации
	 */

	static public function userCheckUidBase ($userUid) {
		if (BD::userSqlNumRows('SELECT `userUid` FROM `user_base` WHERE `userUid` = "'.$userUid.'"')) {
			AUTH::userErrorMessage(4);
		} else {
			return true;
		}
	}


	/**
	 * Проверяем логин пользователя в базе
	 * Если, есть то выводим ошибку, если нет то возрашаем true
	 * Служит для формы регистрации
	 */

	static public function userCheckLoginBase ($userLogin) {
		if (BD::userSqlNumRows('SELECT `userLogin` FROM `user_base` WHERE `userLogin` = "'.$userLogin.'"')) {
			AUTH::userErrorMessage(5);
		} else {
			return true;
		}
	}
}