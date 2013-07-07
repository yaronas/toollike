SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `toollike`
--
CREATE DATABASE IF NOT EXISTS `toollike` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `toollike`;

-- --------------------------------------------------------

--
-- Структура таблицы `user_balance`
--

CREATE TABLE IF NOT EXISTS `user_balance` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userUid` int(11) NOT NULL,
  `userType` text NOT NULL,
  `userText` text NOT NULL,
  `userCount` text NOT NULL,
  `userDate` int(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userUid` (`userUid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1482 ;

-- --------------------------------------------------------

--
-- Структура таблицы `user_base`
--

CREATE TABLE IF NOT EXISTS `user_base` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userLogin` text NOT NULL,
  `userPass` text NOT NULL,
  `userUid` int(12) NOT NULL,
  `userName` text NOT NULL,
  `userPhoto` text NOT NULL,
  `userMoney` int(11) NOT NULL,
  `userNumberJobs` int(11) NOT NULL,
  `userTaskJobs` text NOT NULL,
  `userTaskIgnore` text NOT NULL,
  `userAdmin` varchar(5) NOT NULL DEFAULT 'false',
  `userBrowser` text NOT NULL,
  `userRegTime` text NOT NULL,
  `userAuthTime` text NOT NULL,
  `userBlocked` varchar(6) NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`),
  KEY `userUid` (`userUid`),
  KEY `userNumberJobs` (`userNumberJobs`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=304 ;

-- --------------------------------------------------------

--
-- Структура таблицы `user_spam`
--

CREATE TABLE IF NOT EXISTS `user_spam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userUid` int(15) NOT NULL,
  `userSpamId` int(11) NOT NULL,
  `userDate` text NOT NULL,
  `userСlosed` varchar(5) NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=382 ;

-- --------------------------------------------------------

--
-- Структура таблицы `user_task`
--

CREATE TABLE IF NOT EXISTS `user_task` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `userUid` int(12) NOT NULL,
  `userTaskType` text NOT NULL,
  `userTaskLink` text NOT NULL,
  `userTaskText` text NOT NULL,
  `userTaskComment` varchar(30) NOT NULL DEFAULT 'false',
  `userTaskFor` int(10) NOT NULL DEFAULT '0',
  `userTaskTo` int(10) NOT NULL,
  `userTaskMoney` int(10) NOT NULL,
  `userTaskDate` text NOT NULL,
  `userTaskDel` varchar(5) NOT NULL DEFAULT 'false',
  `userTaskBlocked` varchar(5) NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`),
  KEY `userTaskTo` (`userTaskTo`),
  KEY `userTaskFor` (`userTaskFor`),
  KEY `userUid` (`userUid`),
  KEY `userTaskMoney` (`userTaskMoney`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=147 ;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
