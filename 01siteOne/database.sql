--
-- Banco de dados: `coremvc`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `image` varchar(128) NOT NULL,
  `content` mediumtext NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0->false, 1->true',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `authors_id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_articles_authors_idx` (`authors_id`),
  KEY `fk_articles_categories1_idx` (`categories_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estrutura da tabela `authors`
--

CREATE TABLE IF NOT EXISTS `authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(32) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(60) NOT NULL,
  `status` enum('0','1') DEFAULT '0' COMMENT '0->false, 1->true',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nickname` (`nickname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `authors`
-- Senha inicial: 12345

INSERT INTO `authors` (`id`, `nickname`, `name`, `email`, `password`, `status`) VALUES
(1, 'admin', 'Administrador', 'admin@admin.com', '$2y$10$GgJCickfqNzbnuZS4sfPO.BUry85IRRm1BI/vWMD6o9wJK0glOvqm', '1');

--
-- Estrutura da tabela `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `slug` varchar(128) NOT NULL,
  `description` varchar(512) NOT NULL,
  `status` enum('0','1') DEFAULT '0' COMMENT '0->false, 1->true',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estrutura da tabela `newsletters`
--

CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `email` varchar(64) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `registered_in` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Estrutura da tabela `visitors`
--

CREATE TABLE IF NOT EXISTS `visitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articles_id` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `acessed_in` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_visitors_articles_id` (`articles_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Limitadores para a tabela `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `fk_articles_authors` FOREIGN KEY (`authors_id`) REFERENCES `authors` (`id`),
  ADD CONSTRAINT `fk_articles_categories1` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`id`);

--
-- Limitadores para a tabela `visitors`
--
ALTER TABLE `visitors`
  ADD CONSTRAINT `fk_visitors_articles` FOREIGN KEY (`articles_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
