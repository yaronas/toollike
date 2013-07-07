<?php

/**
 * Класс: TASK
 * @author Хамитов Ильгиз <proroot@proroot.net>
 * @package proroot.net
 */

/**
 * Класс TASK - предназначен для вывода заданий и работы с ними
 * Доступен из любой точки программы.
 */

class TASK {

    static public $userBaseInfo = array();


    /**
     * Функция предназначена для распределении
     * GET - запросов на определенные функции
     * Если не одной функции нас не устроила, то запскаем вывод заданий
     */

    static public function userStart ($userBaseInfo, $userTaskPrintType = null) {
        // Записываем в статитическую перменую массив данных пользователя
        self::$userBaseInfo = $userBaseInfo;

        switch ($_REQUEST['method']) {
            case 'go': {
                TASK::_userTaskGo();
                break;
            }

            case 'check': {
                TASK::_userTaskCheck();
                break;
            }

            case 'ignored': {
                TASK::_userTaskIgnore();
                break;
            }

            case 'spam': {
                TASK::_userTaskSpam();
                break;
            }

            case 'del': {
                TASK::_userTaskDel();
                break;
            }

            default: {
                $userNumberFor = (URL::userNumber ($_GET['userNumberFor']) == null)?0:URL::userNumber ($_GET['userNumberFor']);
                if ($userTaskPrintType) {
                    self::userTaskPrintJobsMy ($userNumberFor);
                } else {
                    self::userTaskPrintJobs ($userNumberFor);
                }
            }
        }
    }

    /**
     * Функция для получения информации о задании
     * Передаваемый параметр ID - задания
     * Возрашаем результат $userTaskBase
     */

    static public function userTaskJobsBase ($userTaskJobId) {
        $userTaskBase = mysql_query ('SELECT `id`, `userTaskLink`, `userTaskType`, `userTaskComment`, `userTaskFor`, `userTaskTo`, `userTaskMoney` FROM `user_task` WHERE `id` = "'.$userTaskJobId.'"') or die ('6');
        $userTaskBase = mysql_fetch_array ($userTaskBase) or die ('6');
        return $userTaskBase;
    }

    /**
     * Функция преднозначена для резки на массив строку
     * Передаваемые параметры Сепаратор и текст
     * Возрашаем результат массивом
     */

    static public function userExplode ($userSeparator, $userText) {
        return explode ($userSeparator, $userText);
    }

    /**
     * Функция для получения массива UID пользователей, которым понравилась данная запись
     * Возрашаем результат
     */

    static public function userArrayUidLink ($userTypeLink, $userArrayLink) {
        switch ($userTypeLink) {
            case 'like': {
                $userTypeFilterLink = 'likes';
                break;
            }

            case 'repost': {
                $userTypeFilterLink = 'copies';
                break;
            }

            case 'comment': {
                $userTypeLink = 'comment';
                break;
            }
        }
        if ($userTypeLink == 'comment') {
            return URL::userJsonDecode ('https://api.vk.com/method/wall.getComments?owner_id='.$userArrayLink[1].'&sort=desc&post_id='.$userArrayLink[2].'&count=100&access_token=dd04abf1a5d415a4740bb1ea4303f2a11a8412ca9a5972a8c9ba184e2047663194d1ec9a0abbe3d8b7926');
        } else {
            return URL::userJsonDecode ('https://api.vk.com/method/likes.getList?owner_id='.$userArrayLink[1].'&filter='.$userTypeFilterLink.'&item_id='.$userArrayLink[2].'&type='.$userArrayLink[0].'&count=1000');
        }
    }

    /**
     * Функция для проверки выполнения задания
     * Передаваемый параметр ID - задания
     * Если все выполнено верно записываем в БД пользователя все нужнные данные
     * При возникновении ошибок выводим коды ошибок на стороне клиента JS обработает и выводит пользователю
     */

    static public function _userTaskCheck () {
        $userBaseInfo = self::$userBaseInfo;
        if ($userTaskJobId = URL::userNumber ($_REQUEST['id'])) {
            // Проверяем на достигновение определенного количества участников
            $userTaskBase  = self::userTaskJobsBase ($userTaskJobId);
            if ($userTaskBase['userTaskFor'] != $userTaskBase['userTaskTo']) {
                // Проверяем на выполнение этого задания ранее
                $userArrayUidLike = self::userExplode (',', $userBaseInfo['userTaskJobs']);
                if (! in_array ($userTaskBase['id'], $userArrayUidLike)) {
                    if (($userTaskBase['userTaskType'] == 'like') || ($userTaskBase['userTaskType'] == 'repost')) {
                        // Проверяем UID пользователя в массиве "кто поставил Мне нравится или рассказал друзьям".
                        $userArrayUidLike = self::userArrayUidLink ($userTaskBase['userTaskType'], self::userExplode ('_',$userTaskBase['userTaskLink']));
                        $userArrayUid = $userArrayUidLike['response']['users'];
                    } elseif ($userTaskBase['userTaskType'] == 'comment') {
                        // Проверяем UID пользователя в массиве
                        $userArrayUid = self::userArrayUidLink ($userTaskBase['userTaskType'], self::userExplode ('_',$userTaskBase['userTaskLink']));
                        for ($i = 1; $i < 10; $i++) {
                            if (($userArrayUid['response'][$i]['uid'] == $userBaseInfo['userUid']) && ($userArrayUid['response'][$i]['text'] == $userTaskBase['userTaskComment'])) {
                                $userArrayUid = array($userBaseInfo['userUid']);
                                break;
                            }
                        }
                    }
                    if (in_array ($userBaseInfo['userUid'], $userArrayUid)) {
                        // Записываем в БД нужные данные пользователю
                        mysql_query ('INSERT INTO `user_balance` SET `userUid` = "'.$userBaseInfo['userUid'].'", `userType` = "userJob", `userText` = "За выполненное задание", `userCount` = "+'.$userTaskBase['userTaskMoney'].'", `userDate` = "'.time().'"') or die ('6');
                        mysql_query ('UPDATE `user_task` SET userTaskFor = userTaskFor + 1 WHERE `id` = "'.$userTaskJobId.'"') or die ('6');
                        mysql_query ('UPDATE `user_base` SET userMoney = userMoney + "'.$userTaskBase['userTaskMoney'].'", `userNumberJobs` = userNumberJobs + 1, `userTaskJobs` = "'.$userBaseInfo['userTaskJobs'].','.$userTaskBase['id'].'" WHERE `userUid` = "'.$userBaseInfo['userUid'].'"') or die ('6');
                        echo 1;
                    } else {
                        echo 2;
                    }
                } else {
                    echo 3;
                }
            } else {
                echo 9;
            }
        }
    }

    /**
     * Функция для игнорирование задания
     * Передаваемый параметр ID - задания
     * Если все выполнено верно записываем в БД пользователя все нужнные данные
     * При возникновении ошибок выводим коды ошибок на стороне клиента JS обработает и выводит пользователю
     */

    static public function _userTaskIgnore () {
        $userBaseInfo = self::$userBaseInfo;
        if ($userTaskJobId = URL::userNumber ($_REQUEST['id'])) {
            // Проверяем ID задание на уже проигнорирование в БД
            $userTaskBase = self::userTaskJobsBase ($userTaskJobId);
            if (! in_array ($userTaskJobId, self::userExplode (',', $userBaseInfo['userTaskIgnore']))) {
                // Записываем в БД нужные данные пользователю
                mysql_query ('UPDATE `user_base` SET `userTaskIgnore` = "'.$userBaseInfo['userTaskIgnore'].','.$userTaskJobId.'" WHERE `userUid` = "'.$userBaseInfo['userUid'].'"') or die ('6');
                echo 1;
            } else {
                echo 3;
            }
        }
    }

    /**
     * Функция для отправки жалобы на задания
     * Передаваемый параметр ID - задания
     * Если все нормально, выводим сообщение об успешной отправки далобы
     * При возникновении ошибок выводим коды ошибок на стороне клиента JS обработает и выводит пользователю
     */

    static public function _userTaskSpam () {
        $userBaseInfo = self::$userBaseInfo;
        if ($userTaskJobId = URL::userNumber ($_REQUEST['id'])) {
            $userCheckSpamId = mysql_query ('SELECT `id`, `userUid` FROM `user_spam` WHERE `id` = "'.$userTaskJobId.'" AND `userUid` = "'.$userBaseInfo['userUid'].'"') or die ('6');
            if (! mysql_num_rows ($userCheckSpamId)) {
                mysql_query ('INSERT INTO `user_spam` SET `userUid` = "'.$userBaseInfo['userUid'].'", `userSpamId` = "'.$userTaskJobId.'", `userDate` = "'.time().'"') or die('6');
                echo 1;
            } else {
                echo 3;
            }
        }
    }

    /**
     * Функция для удаляения задания
     * Передаваемый параметр ID - задания
     * Если все нормально, выводим сообщение об успешной отправки далобы
     * При возникновении ошибок выводим коды ошибок на стороне клиента JS обработает и выводит пользователю
     */

    static public function _userTaskDel () {
        $userBaseInfo = self::$userBaseInfo;
        if ($userTaskJobId = URL::userNumber ($_REQUEST['id'])) {
            $userCheckDelId = mysql_query ('SELECT `userTaskFor`, `userTaskTo`, `userTaskMoney` FROM `user_task` WHERE `id` = "'.$userTaskJobId.'" AND `userUid` = "'.$userBaseInfo['userUid'].'" AND `userTaskDel` = "false" AND `userTaskBlocked` = "false"') or die ('6');
            if (mysql_num_rows ($userCheckDelId)) {
                $userTaskInfo = mysql_fetch_array ($userCheckDelId);
                mysql_query ('UPDATE `user_task` SET `userTaskDel` = "true" WHERE `id` = "'.$userTaskJobId.'"') or die('6');
                if ($userTaskInfo['userTaskFor'] != $userTaskInfo['userTaskTo']) {
                    $userLeftMoney = ($userTaskInfo['userTaskTo'] - $userTaskInfo['userTaskFor']) * $userTaskInfo['userTaskMoney'];
                    mysql_query ('UPDATE `user_base` SET `userMoney` = userMoney + "'.$userLeftMoney.'" WHERE `userUid` = "'.$userBaseInfo['userUid'].'"') or die ('6');
                    mysql_query ('INSERT INTO `user_balance` SET `userUid` = "'.$userBaseInfo['userUid'].'", `userType` = "userJob", `userText` = "Возврат средств за задание", `userCount` = "+'.$userLeftMoney.'", `userDate` = "'.time().'"') or die ('6');
                }
                echo 1;
            } else {
                echo 3;
            }
        }
    }

    /**
     * Функция для перенаправлении пользователя на выполнение задания
     * Передаваемый параметр ID - задания
     * Если все нормально, перенаправляем пользователя на задание в VK.com/ссылка
     */


    static public function _userTaskGo () {
        if ($userTaskJobId = URL::userNumber ($_REQUEST['id'])) {
            $userTaskBase = mysql_query ('SELECT `userTaskLink` FROM `user_task` WHERE `userTaskFor` <> `userTaskTo` AND `userTaskDel` = "false" AND `userTaskBlocked` = "false" AND id = "'.$userTaskJobId.'"');
            if (mysql_num_rows ($userTaskBase)) {
                $userTaskBase = mysql_fetch_array ($userTaskBase);
                // Перезаписываем post на wall, если есть в ссылке
                $userTaskBase = self::userExplode ('_', $userTaskBase['userTaskLink']);
                $userTaskBase[0] = ($userTaskBase[0] == 'post')?'wall':$userTaskBase[0];

                // Переадрисация с полученными данными
                header ('Location: http://vk.com/'.$userTaskBase[0].$userTaskBase[1].'_'.$userTaskBase[2]);
            } else {
               echo '<div style="color: gray; font-size: 16px; padding: 200px; text-align: center;">Извините произошла ошибка. Попробуйте позже..</div>';
            }
        }
    }

    /**
     * Функция для вывода заданий из БД
     * Есть передача параметра от какого количеств передавать
     */

    static public function userTaskPrintJobs ($userNumberFor) {
        $userBaseInfo = self::$userBaseInfo;
        $userTaskCheckBase = mysql_query ('SELECT `id`, `userTaskType`, `userTaskLink`, `userTaskText`, `userTaskComment`, `userTaskFor`, `userTaskTo`, `userTaskMoney` FROM `user_task` WHERE `id` NOT IN ('.$userBaseInfo['userTaskJobs'].') AND `id` NOT IN ('.$userBaseInfo['userTaskIgnore'].') AND userTaskFor <> userTaskTo AND `userTaskDel` = "false" AND userTaskBlocked = "false" ORDER BY `userTaskMoney` DESC  LIMIT  '.$userNumberFor.', 30') or die('<div style="color: gray; font-size: 12px; padding: 20px; text-align: center;">Сервер временно не доступен <b>:/</b></div>');
        if (mysql_num_rows ($userTaskCheckBase)) {
            // Выводим задания в цикле клиенту
            while ($userTaskBase = mysql_fetch_array ($userTaskCheckBase)) {
                switch ($userTaskBase['userTaskType']) {
                    case 'like': {
                        $userTaskTypeText = 'Нажать мне нравится на <b>'.$userTaskBase['userTaskText'].'</b>';
                        $usertaskLink = '_like('.$userTaskBase['id'].', '.$userTaskBase['userTaskMoney'].')';
                        break;
                    }

                    case 'repost': {
                        $userTaskTypeText = 'Рассказать друзьям о <b>'.$userTaskBase['userTaskText'].'</b>';
                        $usertaskLink = '_like('.$userTaskBase['id'].', '.$userTaskBase['userTaskMoney'].')';
                        break;
                    }

                    case 'comment': {
                        $userTaskTypeText = 'Оставить комментарий к <b>'.$userTaskBase['userTaskText'].'</b>';
                        $userTaskCommentText = '<div id="comment_hide'.$userTaskBase['id'].'" style="display: none">'.$userTaskBase['userTaskComment'].'</div>';
                        $usertaskLink = "_new_comment(".$userTaskBase['id'].", $('#comment_hide".$userTaskBase['id']."').text())";
                        break;
                    }
                }
                echo
                    '<div id="task'.$userTaskBase['id'].'" class="task">
                        '.$userTaskCommentText.'
                        <div id="error_task'.$userTaskBase['id'].'"> </div>
                        <div class="_icon">
                            <div class="icon '.$userTaskBase['userTaskType'].'"> </div>
                        </div>
                        <div class="_title">
                            '.$userTaskTypeText.'
                            <br />
                            <div class="_support">
                                Нажмите <a id="spam'.$userTaskBase['id'].'" href="javascript://" onclick="tasks._spam('.$userTaskBase['id'].');">сюда</a>, если хотите пожаловаться.
                            </div>
                        </div>
                        <div class="_count">
                            '.$userTaskBase['userTaskFor'].' из '.$userTaskBase['userTaskTo'].'
                        </div>
                        <div class="_price">
                            +'.$userTaskBase['userTaskMoney'].' ♥
                        </div>
                        <div class="_run">
                            <a id="check'.$userTaskBase['id'].'" href="javascript://" onclick="task_check.'.$usertaskLink.';"><b>Выполнить задание</b></a>
                            <a id="ignored'.$userTaskBase['id'].'" href="javascript://" onclick="tasks._ignored('.$userTaskBase['id'].');"><b>Не показывать</b></a>           
                        </div>
                    </div> 
                    ';
            }
            // Если в базе меньше 30 записей не выводим кнопку "Показать еще задания"
            if (mysql_num_rows ($userTaskCheckBase) > 30) {
                echo '<script> var thisWork = true; </script>';
            }
        } else {
            echo '<div style="color: gray; font-size: 12px; padding: 20px; text-align: center;">Не найдено заданий для выполнения..</div>';
        }
    }

    /**
     * Функция для вывода списка заданий пользователя из БД
     * Есть передача параметра от какого количеств передавать
     */

    static public function userTaskPrintJobsMy ($userNumberFor) {
        $userBaseInfo = self::$userBaseInfo;
        $userTaskCheckBase = mysql_query ('SELECT `id`, `userTaskType`, `userTaskLink`, `userTaskText`, `userTaskFor`, `userTaskTo`, `userTaskMoney`, `userTaskBlocked` FROM `user_task` WHERE `userUid` = "'.$userBaseInfo['userUid'].'" AND `userTaskDel` = "false"  ORDER BY `userTaskDate` DESC  LIMIT  '.$userNumberFor.', 3') or die('<div style="color: gray; font-size: 12px; padding: 20px; text-align: center;">Сервер временно не доступен <b>:/</b></div>');
        if (mysql_num_rows ($userTaskCheckBase)) {
            // Выводим задания в цикле клиенту
            while ($userTaskBase = mysql_fetch_array ($userTaskCheckBase)) {
                switch ($userTaskBase['userTaskType']) {
                    case 'like': {
                        $userTaskTypeText = 'Нажать мне нравится на <b>'.$userTaskBase['userTaskText'].'</b>';
                        break;
                    }

                    case 'repost': {
                        $userTaskTypeText = 'Рассказать друзьям о <b>'.$userTaskBase['userTaskText'].'</b>';
                        break;
                    }

                    case 'comment': {
                        $userTaskTypeText = 'Оставить комментарий к <b>'.$userTaskBase['userTaskText'].'</b>';
                        break;
                    }
                }

                if ($userTaskBase['userTaskFor'] == $userTaskBase['userTaskTo']) {
                    $userTaskStatus = 'выполнен';
                } elseif ($userTaskBase['userTaskBlocked'] == 'true') {
                    $userTaskStatus = 'заблокирован';
                } else {
                    $userTaskStatus = 'выполняется';
                }
                echo 
                    '<div id="task'.$userTaskBase['id'].'" class="task">
                        <div id="error_task'.$userTaskBase['id'].'"> </div>
                        <div class="_icon">
                            <div class="icon '.$userTaskBase['userTaskType'].'"> </div>
                        </div>
                        <div class="_title">
                            '.$userTaskTypeText.'
                            <br />
                            <div class="_support">
                                Статус задания: <b><u>'.$userTaskStatus.'</u></b>
                            </div>
                        </div>
                        <div class="_count">
                            '.$userTaskBase['userTaskFor'].' из '.$userTaskBase['userTaskTo'].'
                        </div>
                        <div class="_price">
                            +'.$userTaskBase['userTaskMoney'].' ♥
                        </div>
                        <div class="_run">
                            <a id="checkDel'.$userTaskBase['id'].'" href="javascript://" onclick="tasks._del('.$userTaskBase['id'].');"><b>Удалить задание</b></a>           
                        </div>
                    </div> 
                    ';
                }

            } else {
                echo '<div style="color: gray; font-size: 12px; padding: 20px; text-align: center;">В Вашем списке не найдено ни одного задания..</div>';
        }
    }
}