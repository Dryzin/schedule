-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08-Mar-2023 às 03:48
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `dummy_db`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `calendario_de_aula`
--

CREATE TABLE `calendario_de_aula` (
  `id` int(11) NOT NULL,
  `ra_docente` int(11) DEFAULT NULL,
  `id_uc` int(11) DEFAULT NULL,
  `horario_inicio` datetime NOT NULL,
  `horario_fim` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `calendario_de_aula`
--

INSERT INTO `calendario_de_aula` (`id`, `ra_docente`, `id_uc`, `horario_inicio`, `horario_fim`) VALUES
(1, 1002, 1, '2023-03-19 07:30:00', '2023-03-19 11:30:00'),
(2, 1003, 2, '2023-03-20 13:30:00', '2023-03-20 17:30:00');

--
-- Acionadores `calendario_de_aula`
--
DELIMITER $$
CREATE TRIGGER `INSERT_carga_horaria` AFTER INSERT ON `calendario_de_aula` FOR EACH ROW BEGIN
    DECLARE carga_horaria_aula INT;
    SET carga_horaria_aula = TIMESTAMPDIFF(HOUR, NEW.horario_inicio, NEW.horario_fim);

    UPDATE uc
    SET carga_horaria = carga_horaria - INTERVAL carga_horaria_aula HOUR
    WHERE id = NEW.id_uc;

    UPDATE turma
    SET carga_horaria = carga_horaria - INTERVAL carga_horaria_aula HOUR
    WHERE id = (SELECT num_turma FROM uc WHERE id = NEW.id_uc);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_conflito_horario` BEFORE INSERT ON `calendario_de_aula` FOR EACH ROW BEGIN
IF EXISTS (
SELECT *
FROM calendario_de_aula
WHERE ra_docente = NEW.ra_docente
AND ((NEW.horario_inicio BETWEEN horario_inicio AND horario_fim)
OR (NEW.horario_fim BETWEEN horario_inicio AND horario_fim)
OR (NEW.horario_inicio < horario_inicio AND NEW.horario_fim > horario_fim))
) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Professor já está ocupado em outro horário no mesmo período.';
END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delete_carga_horaria` AFTER DELETE ON `calendario_de_aula` FOR EACH ROW BEGIN
    DECLARE carga_horaria_aula INT;
    SET carga_horaria_aula = TIMESTAMPDIFF(HOUR, OLD.horario_inicio, OLD.horario_fim);

    UPDATE uc
    SET carga_horaria = carga_horaria + INTERVAL carga_horaria_aula HOUR
    WHERE id = OLD.id_uc;

    UPDATE turma
    SET carga_horaria = carga_horaria + INTERVAL carga_horaria_aula HOUR
    WHERE id = (SELECT num_turma FROM uc WHERE id = OLD.id_uc);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_calendario_de_aula_insert` BEFORE INSERT ON `calendario_de_aula` FOR EACH ROW BEGIN
    IF NEW.horario_fim < NEW.horario_inicio THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'O horário de fim não pode ser anterior ao horário de início.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_calendario_de_aula_update` BEFORE UPDATE ON `calendario_de_aula` FOR EACH ROW BEGIN
    IF NEW.horario_fim < NEW.horario_inicio THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'O horário de fim não pode ser anterior ao horário de início.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_carga_horaria_update` AFTER UPDATE ON `calendario_de_aula` FOR EACH ROW BEGIN
    DECLARE carga_horaria_aula INT;
    SET carga_horaria_aula = TIMESTAMPDIFF(HOUR, OLD.horario_inicio, OLD.horario_fim);

    UPDATE uc
    SET carga_horaria = carga_horaria + INTERVAL carga_horaria_aula HOUR
    WHERE id = OLD.id_uc;

    UPDATE turma
    SET carga_horaria = carga_horaria + INTERVAL carga_horaria_aula HOUR
    WHERE id = (SELECT num_turma FROM uc WHERE id = OLD.id_uc);

    SET carga_horaria_aula = TIMESTAMPDIFF(HOUR, NEW.horario_inicio, NEW.horario_fim);

    UPDATE uc
    SET carga_horaria = carga_horaria - INTERVAL carga_horaria_aula HOUR
    WHERE id = NEW.id_uc;

    UPDATE turma
    SET carga_horaria = carga_horaria - INTERVAL carga_horaria_aula HOUR
    WHERE id = (SELECT num_turma FROM uc WHERE id = NEW.id_uc);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `docentes`
--

CREATE TABLE `docentes` (
  `ra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `docentes`
--

INSERT INTO `docentes` (`ra`) VALUES
(1002),
(1003);

--
-- Acionadores `docentes`
--
DELIMITER $$
CREATE TRIGGER `check_tipo_usuario_before_insert` BEFORE INSERT ON `docentes` FOR EACH ROW BEGIN
IF (SELECT tipo FROM usuario WHERE ra_user = NEW.ra) != 'docente' THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Somente usuários do tipo "docente" podem ser inseridos na tabela "docentes".';
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `feriado`
--

CREATE TABLE `feriado` (
  `id` int(11) NOT NULL,
  `titulo` varchar(32) NOT NULL,
  `descricao` varchar(32) NOT NULL,
  `horario_inicio` datetime NOT NULL,
  `horario_fim` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `feriado`
--

INSERT INTO `feriado` (`id`, `titulo`, `descricao`, `horario_inicio`, `horario_fim`) VALUES
(30, 'asdasdasdasdasda', 'asdadsd', '2023-03-15 00:00:00', '2023-03-15 00:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `turma`
--

CREATE TABLE `turma` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `tipo` enum('Trilhas','Aprendizagem','PSG,','Pago','MBA') DEFAULT NULL,
  `carga_horaria` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `turma`
--

INSERT INTO `turma` (`id`, `nome`, `tipo`, `carga_horaria`) VALUES
(222, 'sistema', 'Trilhas', '816:00:00'),
(333, 'redes', 'Trilhas', '796:00:00'),
(444, 'administração', 'Aprendizagem', '780:00:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `uc`
--

CREATE TABLE `uc` (
  `id` int(11) NOT NULL,
  `nome_uc` varchar(255) DEFAULT NULL,
  `num_turma` int(11) DEFAULT NULL,
  `carga_horaria` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `uc`
--

INSERT INTO `uc` (`id`, `nome_uc`, `num_turma`, `carga_horaria`) VALUES
(1, 'web', 222, '46:00:00'),
(2, 'desktop', 333, '36:00:00'),
(3, 'mobile', 222, '10:00:00'),
(4, 'hardware', 333, '32:00:00'),
(5, 'Arquivos digitais', 444, '12:00:00');

--
-- Acionadores `uc`
--
DELIMITER $$
CREATE TRIGGER `check_carga_horaria_uc` BEFORE INSERT ON `uc` FOR EACH ROW BEGIN
IF NEW.carga_horaria > (SELECT carga_horaria FROM turma WHERE id = NEW.num_turma) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Carga horária da UC não pode ser maior do que a da Turma.';
END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `ck_carga_horaria_uc_before_insert` BEFORE INSERT ON `uc` FOR EACH ROW BEGIN
  IF (SELECT SUM(carga_horaria) FROM uc WHERE num_turma = NEW.num_turma) + NEW.carga_horaria > 
    (SELECT carga_horaria FROM turma WHERE id = NEW.num_turma)
  THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'A soma da carga horária das UCs não pode ser maior que a carga horária da turma';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `ck_carga_horaria_uc_before_update` BEFORE UPDATE ON `uc` FOR EACH ROW BEGIN
  IF (SELECT SUM(carga_horaria) FROM uc WHERE num_turma = NEW.num_turma AND id <> NEW.id) + NEW.carga_horaria > 
      (SELECT carga_horaria FROM turma WHERE id = NEW.num_turma) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Carga horária da UC não pode ser maior do que a da Turma.';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `ra_user` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `tipo` enum('docente','administrador') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`ra_user`, `nome`, `email`, `senha`, `tipo`) VALUES
(1001, 'João da Silva', 'joao@', '123', 'administrador'),
(1002, 'Matheus', 'matheus@', '123', 'docente'),
(1003, 'Cadu', 'cadu@', '123', 'docente');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `calendario_de_aula`
--
ALTER TABLE `calendario_de_aula`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ra_docente` (`ra_docente`,`horario_inicio`,`horario_fim`),
  ADD UNIQUE KEY `id_uc` (`id_uc`,`horario_inicio`,`horario_fim`);

--
-- Índices para tabela `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`ra`);

--
-- Índices para tabela `feriado`
--
ALTER TABLE `feriado`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `turma`
--
ALTER TABLE `turma`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `uc`
--
ALTER TABLE `uc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `num_turma` (`num_turma`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ra_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `calendario_de_aula`
--
ALTER TABLE `calendario_de_aula`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `feriado`
--
ALTER TABLE `feriado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `uc`
--
ALTER TABLE `uc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `calendario_de_aula`
--
ALTER TABLE `calendario_de_aula`
  ADD CONSTRAINT `calendario_de_aula_ibfk_1` FOREIGN KEY (`ra_docente`) REFERENCES `docentes` (`ra`),
  ADD CONSTRAINT `calendario_de_aula_ibfk_2` FOREIGN KEY (`id_uc`) REFERENCES `uc` (`id`);

--
-- Limitadores para a tabela `docentes`
--
ALTER TABLE `docentes`
  ADD CONSTRAINT `docentes_ibfk_1` FOREIGN KEY (`ra`) REFERENCES `usuario` (`ra_user`);

--
-- Limitadores para a tabela `uc`
--
ALTER TABLE `uc`
  ADD CONSTRAINT `uc_ibfk_1` FOREIGN KEY (`num_turma`) REFERENCES `turma` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
