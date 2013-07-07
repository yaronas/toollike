<?php

/**
 * Класс: ADD
 * @author Хамитов Ильгиз <proroot@proroot.net>
 * @package proroot.net
 */

/**
 * Класс ADD - предназначен для добавления заданий в БД
 * Доступен из любой точки программы.
 */

class ADD {

	static public $userBaseInfo = array();

	/**
     * Функция для запуска функции добаления задания
     */

	static public function userStart ($userBaseInfo) {
		self::$userBaseInfo = $userBaseInfo;
		self::userAddJob ();

	}

    /**
     * Функция для добавления задания в БД
     * Проверка всех входных данных
     * Проверка ссылки в базе с типом передаваемым пользователем. Если есть выводим ошибку
     * Проверка на достаток всредств на счету
     * Запись в БД
     */

	static public function userAddJob () {
		$userBaseInfo = self::$userBaseInfo;
		if (($_REQUEST['type']) && ($_REQUEST['url']) && (URL::userNumber ($_REQUEST['count'])) && (URL::userNumber ($_REQUEST['price']))) {
			if ($userJobLink = self::userJobLinkCheck (URL::userXss($_GET['url']))) {
				$userCheckLinkBase = mysql_query ('SELECT `id` FROM `user_task` WHERE `userTaskLink` = "'.$userJobLink.'" AND `userTaskType` = "'.URL::userXss($_REQUEST['type']).'"') or die ('6');
				if (! mysql_num_rows ($userCheckLinkBase)) {
					if ($userBaseInfo['userMoney'] >= (URL::userNumber ($_REQUEST['count']) * URL::userNumber ($_REQUEST['price']))) {
						$userJobLinkExplode = explode ('_', $userJobLink);
						switch ($userJobLinkExplode[0]) {
							case 'video': {
								$userLinkText = 'видеозаписи';
								break;
							}

							case 'photo': {
								$userLinkText = 'фотографии';
								break;
							}

							case 'post': {
								$userLinkText = 'записи';
								break;
							}
						}
						// Записываем в БД
						$userComment = (URL::userXss($_REQUEST['comment']))?URL::userXss($_REQUEST['comment']):false;

						mysql_query ('UPDATE `user_base` SET `userMoney` = `userMoney` - "'.URL::userNumber ($_REQUEST['count']) * URL::userNumber ($_REQUEST['price']).'" WHERE `userUid` = "'.$userBaseInfo['userUid'].'"') or die('6');
						mysql_query ('INSERT INTO `user_task` SET `userUid` = "'.$userBaseInfo['userUid'].'", `userTaskType` = "'.URL::userXss($_REQUEST['type']).'", `userTaskLink` = "'.$userJobLink.'", `userTaskText` = "'.$userLinkText.'", `userTaskComment` = "'.$userComment.'", `userTaskTo` = "'.URL::userNumber ($_REQUEST['count']).'", `userTaskMoney` = "'.URL::userNumber ($_REQUEST['price']).'", `userTaskDate` = "'.time().'"') or die ('6');
						mysql_query ('INSERT INTO `user_balance` SET `userUid` = "'.$userBaseInfo['userUid'].'", `userType` = "userDel", `userText` = "За созданное задания", `userCount` = "-'.URL::userNumber ($_REQUEST['count']) * URL::userNumber ($_REQUEST['price']).'", `userDate` = "'.time().'"') or die ('6');
						echo 1;
					} else {
						echo 2;
					}
				} else {
					echo 9;
				}
			} else {
				echo 8;
			}
		}
	}

	/**
     * Функция для проверки количества выполненых заданий
     * Если не выполнено 5 заданий то возрашаем сколько осталось
     */

	static public function userCheckJob ($userBaseInfo) {
		if ($userBaseInfo['userNumberJobs'] < 5) {
			return 5 - $userBaseInfo['userNumberJobs'];
		}
	}

	/**
     * Функция для проверки коректности ссылки
     * Если все нормально возрашаем отформатированную ссылку
     */

	static public function userJobLinkCheck ($userCheckLink) {
		if (preg_match ('/(photo|wall|video){1}[-0-9]+_{1}[0-9]+/', $userCheckLink, $userCheckLink)) {
			return str_replace (array('wall','photo','video'), array('post_','photo_','video_'), $userCheckLink[0]);
		}
	}

}