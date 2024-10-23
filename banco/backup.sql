-- --------------------------------------------------------
-- Servidor:                     localhost
-- Versão do servidor:           8.3.0 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para db_jornal
CREATE DATABASE IF NOT EXISTS `db_jornal` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_jornal`;

-- Copiando estrutura para tabela db_jornal.comentarios
CREATE TABLE IF NOT EXISTS `comentarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_noticia` int NOT NULL,
  `id_usuario` int NOT NULL,
  `comentario` text COLLATE utf8mb4_general_ci NOT NULL,
  `data_comentario` timestamp NOT NULL DEFAULT (now()),
  PRIMARY KEY (`id`),
  KEY `fk_comentarios_tb_jornal` (`id_noticia`),
  KEY `fk_comentarios_usuarios` (`id_usuario`),
  CONSTRAINT `fk_comentarios_tb_jornal` FOREIGN KEY (`id_noticia`) REFERENCES `tb_jornal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_comentarios_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela db_jornal.comentarios: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela db_jornal.tb_eventos
CREATE TABLE IF NOT EXISTS `tb_eventos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `evento` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `data_evento` date NOT NULL DEFAULT '0000-00-00',
  `horario` time DEFAULT '00:00:00',
  `descricao` text COLLATE utf8mb4_general_ci,
  `data_criacao` timestamp NULL DEFAULT (now()),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela db_jornal.tb_eventos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela db_jornal.tb_jornal
CREATE TABLE IF NOT EXISTS `tb_jornal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `desc` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `img` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `data_cad` date NOT NULL,
  `conteudo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `midia` text COLLATE utf8mb4_general_ci,
  `video_duration` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela db_jornal.tb_jornal: ~25 rows (aproximadamente)
INSERT INTO `tb_jornal` (`id`, `titulo`, `desc`, `img`, `data_cad`, `conteudo`, `midia`, `video_duration`) VALUES
	(31, 'Nova vacina contra a gripe é aprovada', 'A vacina foi aprovada após testes rigorosos.', '', '2024-09-23', 'Uma nova vacina contra a gripe foi aprovada pelas autoridades de saúde após uma série de testes rigorosos que demonstraram sua eficácia e segurança. Espera-se que ela reduza significativamente os casos de gripe nesta temporada.', NULL, ''),
	(32, 'Mudanças climáticas afetam a agricultura', 'Estudo revela impactos severos nas colheitas.', '', '2024-09-23', 'Um novo estudo revela que as mudanças climáticas estão afetando gravemente a agricultura, resultando em colheitas menores e maior insegurança alimentar em diversas regiões do mundo. Os agricultores estão sendo incentivados a adotar práticas mais sustentáveis.', NULL, ''),
	(33, 'Tecnologia 5G chega às grandes cidades', 'A implementação da tecnologia avança rapidamente.', '', '2024-09-23', 'A tecnologia 5G finalmente chegou às grandes cidades, prometendo velocidades de internet muito mais rápidas e melhor conectividade. Isso pode revolucionar diversos setores, desde a saúde até a educação.', NULL, ''),
	(34, 'Lançamento do novo smartphone da marca X', 'O modelo promete recursos inovadores.', '', '2024-09-23', 'A marca X lançou seu novo smartphone, que promete recursos inovadores como câmera de alta resolução e bateria de longa duração. Os entusiastas da tecnologia aguardam ansiosamente as análises.', NULL, ''),
	(35, 'Reforma da previdência é aprovada no Congresso', 'A nova legislação visa melhorar a sustentabilidade do sistema.', '', '2024-09-23', 'Após intensos debates, a reforma da previdência foi aprovada no Congresso. O objetivo é garantir a sustentabilidade do sistema a longo prazo, embora a medida tenha gerado controvérsias entre a população.', NULL, ''),
	(36, 'Festival de música atrai milhares de fãs', 'O evento contou com grandes artistas nacionais.', '', '2024-09-23', 'O festival de música realizado no último fim de semana atraiu milhares de fãs, com performances de grandes artistas nacionais. O evento foi um sucesso e ajudou a movimentar a economia local.', NULL, ''),
	(37, 'Estudo revela benefícios da meditação', 'A prática pode reduzir níveis de estresse.', '', '2024-09-23', 'Um estudo recente mostra que a meditação pode ajudar a reduzir os níveis de estresse e ansiedade, melhorando a saúde mental e o bem-estar geral. Especialistas recomendam a prática regular.', NULL, ''),
	(38, 'Nova política de sustentabilidade é lançada', 'Iniciativa busca reduzir a pegada de carbono.', '', '2024-09-23', 'Uma nova política de sustentabilidade foi lançada por uma grande empresa, com o objetivo de reduzir sua pegada de carbono e promover práticas ecológicas. Essa iniciativa é um passo importante para um futuro mais sustentável.', NULL, ''),
	(39, 'Crescimento do e-commerce em tempos de pandemia', 'Setor se destaca e inovações surgem.', '', '2024-09-23', 'O e-commerce experimentou um crescimento significativo durante a pandemia, com muitas empresas se adaptando às novas demandas do mercado. Inovações tecnológicas também têm surgido para melhorar a experiência do consumidor.', NULL, ''),
	(40, 'Mudanças nas regras de trânsito entram em vigor', 'Nova legislação visa aumentar a segurança.', '', '2024-09-23', 'As novas regras de trânsito, que visam aumentar a segurança nas ruas, entram em vigor esta semana. As mudanças incluem penalidades mais severas para infrações graves e novas sinalizações nas estradas.', NULL, ''),
	(87, 'Semana da Tecnologia é um sucesso', 'A semana da tecnologia foi incrível!!!', './uploads/img/66ff37f8df5b3.jpg', '2024-10-03', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc in libero dolor. Aenean luctus sapien non vestibulum egestas. Aenean efficitur ut lectus a feugiat. Integer vitae erat diam. Donec auctor, quam eu venenatis ornare, tellus erat faucibus leo, et laoreet mi felis at nisl. Integer vel fringilla libero. Curabitur id iaculis risus. Maecenas dignissim quam purus, vitae faucibus eros volutpat id. Nunc pharetra erat varius est hendrerit rhoncus id vel quam. Praesent ut fringilla dolor. Nullam posuere neque eu nisi fermentum, sed mollis purus faucibus. Ut finibus mi mauris. Aliquam sagittis placerat ante tempus rutrum. Nam sagittis vulputate lacus at elementum. In hac habitasse platea dictumst. Aenean iaculis congue nisi, at auctor justo elementum sit amet.\r\n\r\nMauris fringilla, nunc nec elementum mattis, felis ipsum hendrerit lectus, in varius dui felis vel neque. Praesent vulputate augue id consectetur vulputate. Interdum et malesuada fames ac ante ipsum primis in faucibus. Pellentesque egestas varius molestie. Duis mauris justo, dignissim ac vulputate condimentum, ullamcorper a nisi. Curabitur lorem velit, malesuada vel fermentum vel, dapibus in velit. Cras sapien elit, finibus nec accumsan et, sodales et neque. Cras accumsan porta velit quis finibus. Curabitur congue dapibus convallis. In egestas tempor lorem convallis consequat. Cras erat orci, sagittis ut vulputate sed, pulvinar ac tellus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Pellentesque at congue elit.', NULL, NULL),
	(90, 'Entrevista com X', 'Entrevista com candidato X foi um sucesso', './uploads/img/66ff3b1bab8bd.jpg', '2024-10-03', 'A moda para o inverno de 2024 promete ser diversificada, com diversas cores, estampas e modelos. Algumas das tendências incluem: \r\nCores\r\nO vermelho cereja, o verde matchá, os tons terrosos como o marrom, terracota e caramelo, os tons escuros como o preto e azul, e os tons rosas como o rosé, pink e cereja. \r\nEstampas\r\nO xadrez grade. \r\nCouro\r\nO couro estará presente em diversas modelagens, como vestidos, calças, saias e jaquetas. \r\nCasacos de pele fake\r\nUma tendência que veio para ficar, os casacos de pele fake marcaram presença nas passarelas em tons de marrom, bege e preto.\r\nSuéteres\r\nUm clássico que nunca sai de moda, os suéteres dão uma vibe eclectic grandpa ao look. \r\nMacacão jeans\r\nUma opção de tendência de inverno para 2024 perfeita não só para os adolescentes, mas para os pequenos também.', 'https://www.youtube.com/embed/ZUBe_X6KU_w?si=EIPmA7X0DwWGx2r2', '15 min'),
	(91, 'Entrevista com X', 'Entrevista com candidato X foi um sucesso', './uploads/img/66ff3b1bab8bd.jpg', '2024-10-03', 'A moda para o inverno de 2024 promete ser diversificada, com diversas cores, estampas e modelos. Algumas das tendências incluem: \r\nCores\r\nO vermelho cereja, o verde matchá, os tons terrosos como o marrom, terracota e caramelo, os tons escuros como o preto e azul, e os tons rosas como o rosé, pink e cereja. \r\nEstampas\r\nO xadrez grade. \r\nCouro\r\nO couro estará presente em diversas modelagens, como vestidos, calças, saias e jaquetas. \r\nCasacos de pele fake\r\nUma tendência que veio para ficar, os casacos de pele fake marcaram presença nas passarelas em tons de marrom, bege e preto.\r\nSuéteres\r\nUm clássico que nunca sai de moda, os suéteres dão uma vibe eclectic grandpa ao look. \r\nMacacão jeans\r\nUma opção de tendência de inverno para 2024 perfeita não só para os adolescentes, mas para os pequenos também.', 'https://www.youtube.com/embed/ZUBe_X6KU_w?si=EIPmA7X0DwWGx2r2', '15 min'),
	(100, 'Entrevista com X', 'Entrevista com candidato X foi um sucesso', './uploads/img/66ff3b1bab8bd.jpg', '2024-10-03', 'A moda para o inverno de 2024 promete ser diversificada, com diversas cores, estampas e modelos. Algumas das tendências incluem: \r\nCores\r\nO vermelho cereja, o verde matchá, os tons terrosos como o marrom, terracota e caramelo, os tons escuros como o preto e azul, e os tons rosas como o rosé, pink e cereja. \r\nEstampas\r\nO xadrez grade. \r\nCouro\r\nO couro estará presente em diversas modelagens, como vestidos, calças, saias e jaquetas. \r\nCasacos de pele fake\r\nUma tendência que veio para ficar, os casacos de pele fake marcaram presença nas passarelas em tons de marrom, bege e preto.\r\nSuéteres\r\nUm clássico que nunca sai de moda, os suéteres dão uma vibe eclectic grandpa ao look. \r\nMacacão jeans\r\nUma opção de tendência de inverno para 2024 perfeita não só para os adolescentes, mas para os pequenos também.', 'https://www.youtube.com/embed/ZUBe_X6KU_w?si=EIPmA7X0DwWGx2r2', '15 min'),
	(101, 'Entrevista com X', 'Entrevista com candidato X foi um sucesso', './uploads/img/66ff3b1bab8bd.jpg', '2024-10-03', 'A moda para o inverno de 2024 promete ser diversificada, com diversas cores, estampas e modelos. Algumas das tendências incluem: \r\nCores\r\nO vermelho cereja, o verde matchá, os tons terrosos como o marrom, terracota e caramelo, os tons escuros como o preto e azul, e os tons rosas como o rosé, pink e cereja. \r\nEstampas\r\nO xadrez grade. \r\nCouro\r\nO couro estará presente em diversas modelagens, como vestidos, calças, saias e jaquetas. \r\nCasacos de pele fake\r\nUma tendência que veio para ficar, os casacos de pele fake marcaram presença nas passarelas em tons de marrom, bege e preto.\r\nSuéteres\r\nUm clássico que nunca sai de moda, os suéteres dão uma vibe eclectic grandpa ao look. \r\nMacacão jeans\r\nUma opção de tendência de inverno para 2024 perfeita não só para os adolescentes, mas para os pequenos também.', 'https://www.youtube.com/embed/ZUBe_X6KU_w?si=EIPmA7X0DwWGx2r2', '15 min'),
	(102, 'Entrevista com X', 'Entrevista com candidato X foi um sucesso', './uploads/img/66ff3b1bab8bd.jpg', '2024-10-03', 'A moda para o inverno de 2024 promete ser diversificada, com diversas cores, estampas e modelos. Algumas das tendências incluem: \r\nCores\r\nO vermelho cereja, o verde matchá, os tons terrosos como o marrom, terracota e caramelo, os tons escuros como o preto e azul, e os tons rosas como o rosé, pink e cereja. \r\nEstampas\r\nO xadrez grade. \r\nCouro\r\nO couro estará presente em diversas modelagens, como vestidos, calças, saias e jaquetas. \r\nCasacos de pele fake\r\nUma tendência que veio para ficar, os casacos de pele fake marcaram presença nas passarelas em tons de marrom, bege e preto.\r\nSuéteres\r\nUm clássico que nunca sai de moda, os suéteres dão uma vibe eclectic grandpa ao look. \r\nMacacão jeans\r\nUma opção de tendência de inverno para 2024 perfeita não só para os adolescentes, mas para os pequenos também.', 'https://www.youtube.com/embed/ZUBe_X6KU_w?si=EIPmA7X0DwWGx2r2', '15 min'),
	(103, 'Entrevista com X', 'Entrevista com candidato X foi um sucesso', './uploads/img/66ff3b1bab8bd.jpg', '2024-10-03', 'A moda para o inverno de 2024 promete ser diversificada, com diversas cores, estampas e modelos. Algumas das tendências incluem: \r\nCores\r\nO vermelho cereja, o verde matchá, os tons terrosos como o marrom, terracota e caramelo, os tons escuros como o preto e azul, e os tons rosas como o rosé, pink e cereja. \r\nEstampas\r\nO xadrez grade. \r\nCouro\r\nO couro estará presente em diversas modelagens, como vestidos, calças, saias e jaquetas. \r\nCasacos de pele fake\r\nUma tendência que veio para ficar, os casacos de pele fake marcaram presença nas passarelas em tons de marrom, bege e preto.\r\nSuéteres\r\nUm clássico que nunca sai de moda, os suéteres dão uma vibe eclectic grandpa ao look. \r\nMacacão jeans\r\nUma opção de tendência de inverno para 2024 perfeita não só para os adolescentes, mas para os pequenos também.', 'https://www.youtube.com/embed/ZUBe_X6KU_w?si=EIPmA7X0DwWGx2r2', '15 min'),
	(106, 'Entrevista com X', 'Entrevista com candidato X foi um sucesso', './uploads/img/66ff3b1bab8bd.jpg', '2024-10-03', 'A moda para o inverno de 2024 promete ser diversificada, com diversas cores, estampas e modelos. Algumas das tendências incluem: \r\nCores\r\nO vermelho cereja, o verde matchá, os tons terrosos como o marrom, terracota e caramelo, os tons escuros como o preto e azul, e os tons rosas como o rosé, pink e cereja. \r\nEstampas\r\nO xadrez grade. \r\nCouro\r\nO couro estará presente em diversas modelagens, como vestidos, calças, saias e jaquetas. \r\nCasacos de pele fake\r\nUma tendência que veio para ficar, os casacos de pele fake marcaram presença nas passarelas em tons de marrom, bege e preto.\r\nSuéteres\r\nUm clássico que nunca sai de moda, os suéteres dão uma vibe eclectic grandpa ao look. \r\nMacacão jeans\r\nUma opção de tendência de inverno para 2024 perfeita não só para os adolescentes, mas para os pequenos também.', 'https://www.youtube.com/embed/ZUBe_X6KU_w?si=EIPmA7X0DwWGx2r2', '15 min'),
	(107, 'Entrevista com X', 'Entrevista com candidato X foi um sucesso', './uploads/img/66ff3b1bab8bd.jpg', '2024-10-03', 'A moda para o inverno de 2024 promete ser diversificada, com diversas cores, estampas e modelos. Algumas das tendências incluem: \r\nCores\r\nO vermelho cereja, o verde matchá, os tons terrosos como o marrom, terracota e caramelo, os tons escuros como o preto e azul, e os tons rosas como o rosé, pink e cereja. \r\nEstampas\r\nO xadrez grade. \r\nCouro\r\nO couro estará presente em diversas modelagens, como vestidos, calças, saias e jaquetas. \r\nCasacos de pele fake\r\nUma tendência que veio para ficar, os casacos de pele fake marcaram presença nas passarelas em tons de marrom, bege e preto.\r\nSuéteres\r\nUm clássico que nunca sai de moda, os suéteres dão uma vibe eclectic grandpa ao look. \r\nMacacão jeans\r\nUma opção de tendência de inverno para 2024 perfeita não só para os adolescentes, mas para os pequenos também.', 'https://www.youtube.com/embed/ZUBe_X6KU_w?si=EIPmA7X0DwWGx2r2', '15 min'),
	(108, 'Entrevista com X', 'Entrevista com candidato X foi um sucesso', './uploads/img/66ff3b1bab8bd.jpg', '2024-10-03', 'A moda para o inverno de 2024 promete ser diversificada, com diversas cores, estampas e modelos. Algumas das tendências incluem: \r\nCores\r\nO vermelho cereja, o verde matchá, os tons terrosos como o marrom, terracota e caramelo, os tons escuros como o preto e azul, e os tons rosas como o rosé, pink e cereja. \r\nEstampas\r\nO xadrez grade. \r\nCouro\r\nO couro estará presente em diversas modelagens, como vestidos, calças, saias e jaquetas. \r\nCasacos de pele fake\r\nUma tendência que veio para ficar, os casacos de pele fake marcaram presença nas passarelas em tons de marrom, bege e preto.\r\nSuéteres\r\nUm clássico que nunca sai de moda, os suéteres dão uma vibe eclectic grandpa ao look. \r\nMacacão jeans\r\nUma opção de tendência de inverno para 2024 perfeita não só para os adolescentes, mas para os pequenos também.', 'https://www.youtube.com/embed/ZUBe_X6KU_w?si=EIPmA7X0DwWGx2r2', '15 min'),
	(109, 'Entrevista com X', 'Entrevista com candidato X foi um sucesso', './uploads/img/66ff3b1bab8bd.jpg', '2024-10-03', 'A moda para o inverno de 2024 promete ser diversificada, com diversas cores, estampas e modelos. Algumas das tendências incluem: \r\nCores\r\nO vermelho cereja, o verde matchá, os tons terrosos como o marrom, terracota e caramelo, os tons escuros como o preto e azul, e os tons rosas como o rosé, pink e cereja. \r\nEstampas\r\nO xadrez grade. \r\nCouro\r\nO couro estará presente em diversas modelagens, como vestidos, calças, saias e jaquetas. \r\nCasacos de pele fake\r\nUma tendência que veio para ficar, os casacos de pele fake marcaram presença nas passarelas em tons de marrom, bege e preto.\r\nSuéteres\r\nUm clássico que nunca sai de moda, os suéteres dão uma vibe eclectic grandpa ao look. \r\nMacacão jeans\r\nUma opção de tendência de inverno para 2024 perfeita não só para os adolescentes, mas para os pequenos também.', 'https://www.youtube.com/embed/ZUBe_X6KU_w?si=EIPmA7X0DwWGx2r2', '15 min'),
	(110, 'Entrevista com X', 'Entrevista com candidato X foi um sucesso', './uploads/img/66ff3b1bab8bd.jpg', '2024-10-03', 'A moda para o inverno de 2024 promete ser diversificada, com diversas cores, estampas e modelos. Algumas das tendências incluem: \r\nCores\r\nO vermelho cereja, o verde matchá, os tons terrosos como o marrom, terracota e caramelo, os tons escuros como o preto e azul, e os tons rosas como o rosé, pink e cereja. \r\nEstampas\r\nO xadrez grade. \r\nCouro\r\nO couro estará presente em diversas modelagens, como vestidos, calças, saias e jaquetas. \r\nCasacos de pele fake\r\nUma tendência que veio para ficar, os casacos de pele fake marcaram presença nas passarelas em tons de marrom, bege e preto.\r\nSuéteres\r\nUm clássico que nunca sai de moda, os suéteres dão uma vibe eclectic grandpa ao look. \r\nMacacão jeans\r\nUma opção de tendência de inverno para 2024 perfeita não só para os adolescentes, mas para os pequenos também.', 'https://www.youtube.com/embed/ZUBe_X6KU_w?si=EIPmA7X0DwWGx2r2', '15 min'),
	(111, 'Entrevista com X', 'Entrevista com candidato X foi um sucesso', './uploads/img/66ff3b1bab8bd.jpg', '2024-10-03', 'A moda para o inverno de 2024 promete ser diversificada, com diversas cores, estampas e modelos. Algumas das tendências incluem: \r\nCores\r\nO vermelho cereja, o verde matchá, os tons terrosos como o marrom, terracota e caramelo, os tons escuros como o preto e azul, e os tons rosas como o rosé, pink e cereja. \r\nEstampas\r\nO xadrez grade. \r\nCouro\r\nO couro estará presente em diversas modelagens, como vestidos, calças, saias e jaquetas. \r\nCasacos de pele fake\r\nUma tendência que veio para ficar, os casacos de pele fake marcaram presença nas passarelas em tons de marrom, bege e preto.\r\nSuéteres\r\nUm clássico que nunca sai de moda, os suéteres dão uma vibe eclectic grandpa ao look. \r\nMacacão jeans\r\nUma opção de tendência de inverno para 2024 perfeita não só para os adolescentes, mas para os pequenos também.', 'https://www.youtube.com/embed/ZUBe_X6KU_w?si=EIPmA7X0DwWGx2r2', '15 min'),
	(112, 'Entrevista com X', 'Entrevista com candidato X foi um sucesso', './uploads/img/66ff3b1bab8bd.jpg', '2024-10-03', 'A moda para o inverno de 2024 promete ser diversificada, com diversas cores, estampas e modelos. Algumas das tendências incluem: \r\nCores\r\nO vermelho cereja, o verde matchá, os tons terrosos como o marrom, terracota e caramelo, os tons escuros como o preto e azul, e os tons rosas como o rosé, pink e cereja. \r\nEstampas\r\nO xadrez grade. \r\nCouro\r\nO couro estará presente em diversas modelagens, como vestidos, calças, saias e jaquetas. \r\nCasacos de pele fake\r\nUma tendência que veio para ficar, os casacos de pele fake marcaram presença nas passarelas em tons de marrom, bege e preto.\r\nSuéteres\r\nUm clássico que nunca sai de moda, os suéteres dão uma vibe eclectic grandpa ao look. \r\nMacacão jeans\r\nUma opção de tendência de inverno para 2024 perfeita não só para os adolescentes, mas para os pequenos também.', 'https://www.youtube.com/embed/ZUBe_X6KU_w?si=EIPmA7X0DwWGx2r2', '15 min'),
	(120, 'Semana da Tecnologia é um sucesso', 'A semana da tecnologia foi incrível!!!', './uploads/img/66ff37f8df5b3.jpg', '2024-10-03', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc in libero dolor. Aenean luctus sapien non vestibulum egestas. Aenean efficitur ut lectus a feugiat. Integer vitae erat diam. Donec auctor, quam eu venenatis ornare, tellus erat faucibus leo, et laoreet mi felis at nisl. Integer vel fringilla libero. Curabitur id iaculis risus. Maecenas dignissim quam purus, vitae faucibus eros volutpat id. Nunc pharetra erat varius est hendrerit rhoncus id vel quam. Praesent ut fringilla dolor. Nullam posuere neque eu nisi fermentum, sed mollis purus faucibus. Ut finibus mi mauris. Aliquam sagittis placerat ante tempus rutrum. Nam sagittis vulputate lacus at elementum. In hac habitasse platea dictumst. Aenean iaculis congue nisi, at auctor justo elementum sit amet.\r\n\r\nMauris fringilla, nunc nec elementum mattis, felis ipsum hendrerit lectus, in varius dui felis vel neque. Praesent vulputate augue id consectetur vulputate. Interdum et malesuada fames ac ante ipsum primis in faucibus. Pellentesque egestas varius molestie. Duis mauris justo, dignissim ac vulputate condimentum, ullamcorper a nisi. Curabitur lorem velit, malesuada vel fermentum vel, dapibus in velit. Cras sapien elit, finibus nec accumsan et, sodales et neque. Cras accumsan porta velit quis finibus. Curabitur congue dapibus convallis. In egestas tempor lorem convallis consequat. Cras erat orci, sagittis ut vulputate sed, pulvinar ac tellus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Pellentesque at congue elit.', NULL, NULL);

-- Copiando estrutura para tabela db_jornal.tb_jornal_pdf
CREATE TABLE IF NOT EXISTS `tb_jornal_pdf` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nome_arquivo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `data_publicacao` date NOT NULL DEFAULT (curdate()),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela db_jornal.tb_jornal_pdf: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela db_jornal.tb_sugestoes
CREATE TABLE IF NOT EXISTS `tb_sugestoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_general_ci DEFAULT '',
  `sugestao` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `data_envio` datetime NOT NULL DEFAULT (now()),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela db_jornal.tb_sugestoes: ~1 rows (aproximadamente)
INSERT INTO `tb_sugestoes` (`id`, `nome`, `sugestao`, `data_envio`) VALUES
	(6, 'Joana D\'arc', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam vitae elit purus. Nam laoreet, lorem at dignissim aliquam, felis orci mollis sapien, vitae pulvinar ligula diam a risus. Praesent suscipit ante et elit tempus condimentum. Aenean ac nunc id tellus interdum viverra non eget augue. Sed efficitur, felis nec aliquam euismod, urna libero mollis ipsum, ac lobortis velit nibh a mi. Nullam eu rhoncus nibh. Maecenas a tincidunt justo. Morbi massa orci, varius nec quam eget, consequat vehicula dui. Phasellus nibh quam, finibus nec luctus eu, posuere sit amet massa. Mauris sed pulvinar velit. Integer ultricies eu urna nec posuere. Sed gravida justo et turpis placerat auctor. Maecenas ultrices elit eget nibh facilisis, ut luctus sem ornare. Etiam ipsum dui, dictum eu tellus non, egestas gravida lectus.\r\n\r\nEtiam augue nisl, dictum a iaculis sit amet, rutrum accumsan diam. Sed mauris lacus, varius vel volutpat a, tempor vel turpis. Ut et libero pellentesque turpis tempus bibendum non in eros. Phasellus rhoncus ut eros vel sollicitudin. Maecenas sit amet tempor sapien, eget scelerisque magna. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Quisque dapibus rhoncus tellus id aliquet. Duis id dolor ultricies dolor vestibulum facilisis ac nec justo.', '2024-09-30 20:07:19');

-- Copiando estrutura para tabela db_jornal.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `nome` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT 'Armazena a senha criptografada',
  `poderes` tinyint(1) NOT NULL COMMENT '''1 para administrador, 0 para usuário comum''',
  `data_cadastro` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- Copiando dados para a tabela db_jornal.usuarios: ~6 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `usuario`, `nome`, `senha`, `poderes`, `data_cadastro`) VALUES
	(2, 'admin', 'Gabriel, O Devorador de Deuses', '$2y$10$g61reboGdpT/ZzDB4mxUJ.0B.32LlVg.dpf2eBKoq42.p.bZtEWsS', 1, '2024-10-22'),
	(4, 'test', 'aaa', '$2y$10$pzjO/sErXH0lIAVdshxBReC5Nmf65k7bLLyIXOUeA33SgY1lMH2bK', 0, '2024-10-22'),
	(8, 'blackine4', 'blackine4', '$2y$10$Li5YvP3NDB02sC55GQILKOUuV9Vgap2GmujUmlQlgi9Wdo3gZ8vna', 0, '2024-10-22'),
	(11, 'blackine', 'blackine', '$2y$10$iFeNPFMapSXaMkihBBWjfuaDTM1hsLUs7ffullVNnEFIbXdwnQiim', 0, '2024-10-22'),
	(16, 'Haugxx', 'Henrique Firmino', '$2y$10$aHcQSLcoAtGXzAnAWv7uUOXkWbahxnsn5XQQnUnzYUZbBlMjfGEU.', 0, '2024-10-22'),
	(17, 'ab', 'ab', '$2y$10$Quu9Lk7t8M1M2xyV2IkajOx6UhARHdgjxPxjLS6Kq4dWTH4s.VGEi', 0, '2024-10-22');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
