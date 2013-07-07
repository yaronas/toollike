<?php

/**
 * Класс: BALANC
 * @author Хамитов Ильгиз <proroot@proroot.net>
 * @package proroot.net
 */

/**
 * Класс BALANC - предназначен для работы с балансом пользователя
 * Доступен из любой точки программы.
 */

class BALANC {

    static public $userBaseInfo = array();

    /**
     * Функция предназначена для распределении
     * GET - запросов на определенные функции
     * Запускаем функцию вывода истории
     */

    static public function userStart ($userBaseInfo) {
        self::$userBaseInfo = $userBaseInfo;
        $userNumberFor = (URL::userNumber ($_GET['userNumberFor']) == null)?0:URL::userNumber ($_GET['userNumberFor']);
        self::userBalancePrint ($userNumberFor);
    }

    /**
     * Функция для вывода истории баланса пользователя из БД
     * Есть передача параметра от какого количеств передавать
     */

    static public function userBalancePrint ($userNumberFor) {
        $userBaseInfo = self::$userBaseInfo;

        $userBalanceCheck = mysql_query ('SELECT `userType`, `userText`, `userCount`, `userDate` FROM `user_balance` WHERE `userUid` = "'.$userBaseInfo['userUid'].'" ORDER BY `userDate` DESC LIMIT  '.$userNumberFor.', 10') or die('<div style="color: gray; font-size: 12px; padding: 20px; text-align: center;">Сервер временно не доступен <b>:/</b></div>');
        if (mysql_num_rows ($userBalanceCheck)) {
            // Выводим задания в цикле клиенту
            while ($userBaseBalance  = mysql_fetch_array ($userBalanceCheck)) {
                echo 
                    '<tr>
                        <td> <div class="'.$userBaseBalance['userType'].'"> </div> </td>
                        <td> <div class="desc_text">'.$userBaseBalance['userText'].'</div> </td>
                        <td> <div class="points">'.$userBaseBalance['userCount'].' like</div> </td>
                        <td> <div class="date">'.USER::userDateNew ($userBaseBalance['userDate']).'</div> </td>
                    </tr>';
            }
            // Если в базе меньше 5 записей не выводим кнопку "Показать еще задания"
            if (mysql_num_rows ($userBalanceCheck) > 4) {
                echo '<script> var thisHistori = true; </script>';
            }
        }
    }
}