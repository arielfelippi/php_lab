-- php_lab.usuarios definition

CREATE TABLE `usuarios` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `nome_usuario` varchar(100) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `id_perfil_usuario` tinyint(4) DEFAULT NULL,
  `excluido` tinyint(1) DEFAULT NULL,
  `id_usuario_criacao` tinyint(4) NOT NULL,
  `id_usuario_alteracao` tinyint(4) NOT NULL,
  `id_usuario_exclusao` tinyint(4) DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `data_alteracao` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `data_exclusao` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_UN` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;