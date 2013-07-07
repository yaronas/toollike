<?php

/**
 * Класс: APP
 * @author Хамитов Ильгиз <proroot@proroot.net>
 * @package proroot.net
 */

/**
 * Класс APP - маршрутизатор.
 * Доступен из любой точки программы.
 */

class APP {

    private $userRoute = null;
    private $userBaseInfo = array();

	/**
	 * Запуск движка системы.
	 * Подключает все нужные функции, для работы системы.
	 * Проверяем пользователя в бан списке.
	 */

	public function userRun () {
		if ($this->userCheckCookie()) {
			if ($this->userBaseInfo = USER::userCheckBase()) {
                switch ($_REQUEST['route']) {
                    case 'logout': {
                        URL::userTypeCookie();
                        exit;
                    }

                    case 'task': {
                        TASK::userStart($this->userBaseInfo);
                        exit;
                    }

                    case 'add': {
                        ADD::userStart($this->userBaseInfo);
                        exit;
                    }

                    case 'balanc': {
                        BALANC::userStart($this->userBaseInfo);
                        exit;
                    }

                    case 'admin': {
                        if ($this->userBaseInfo['userAdmin'] == 'true') {
                            echo 'admin';
                            //ADMIN::userStart();
                            exit;
                        }
                    }

                    default : {
                        // Проверяем пользователя в бан списке
                        $this->userRoute = ($this->userBaseInfo['userBlocked'] == 'true')?'blocked':$this->userRoute;
                    }
                }
			}
		}

        // Запускаем контролеры страниц.
		$this->userRunControllers ();
	}

	/**
	 * Магнитическая функция для запуска, функций движка. 
     * Подключаем функцию userRoute()
     * Запускаем движок
	 */

    public function __construct () {

        switch ($_REQUEST['route']) {
            case 'auth': {
                AUTH::userStart();
                break;
            }
            
            default: {
                $this->userRoute = URL::userRoute();
                $this->userRun();
            }
        }
    }

    /**
     * Функция проверки присутсвии cookie в браузере
     * Если отсутсвуют cookie записываем в userRoute контролер "auth"
     */

	public function userCheckCookie () {
		if (($_COOKIE['userLogin']) && ($_COOKIE['userPass'])) {
			return true;
		} else {
            $this->userRoute = 'auth';
        }
	}

    /**
     * Функция для подключаение нужных контролеров, получаем с $this->userRoute
     * Запускаем шапку
     * Запускаем основной контролер
     * Запускаем подвал страницы 
     */

    public function userRunControllers () {
        if ($this->userRoute != 'auth') {
            // Запускаем шапку всех страниц
            require_once (DIR.'coreServer/templates/header.php');
        }
        // Запускаем нужный модуль
        require_once (DIR.'coreServer/controllers/'.$this->userRoute.'.php');
        
        // Запускаем подвал страницы
        require_once (DIR.'coreServer/templates/footer.php');
    }
}