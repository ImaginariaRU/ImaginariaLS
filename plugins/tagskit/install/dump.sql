--
-- Структура таблицы `prefix_tk_tag`
--

CREATE TABLE IF NOT EXISTS `prefix_tk_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `main_text` varchar(50) CHARACTER SET utf8 NOT NULL,
  `text` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `main_text` (`main_text`),
  KEY `text` (`text`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;