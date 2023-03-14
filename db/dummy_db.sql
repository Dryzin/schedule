-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2022 at 07:16 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

#criação de uma tabela
CREATE TABLE usuario (
ra_user INT PRIMARY KEY,
nome VARCHAR(255),
email VARCHAR(50) UNIQUE NOT NULL,
senha VARCHAR(32) NOT NULL,
tipo ENUM('docente', 'administrador')
);

CREATE TABLE docentes (
ra INT PRIMARY KEY unique,
FOREIGN KEY (ra) REFERENCES usuario(ra_user)
);

CREATE TABLE turma (
id INT PRIMARY KEY,
nome VARCHAR(255),
tipo ENUM('Trilhas', 'Aprendizagem', 'PSG,', 'Pago', 'MBA'),
carga_horaria Time
);

CREATE TABLE uc (
id INT AUTO_INCREMENT PRIMARY KEY,
nome_uc varchar(255),
num_turma int,
carga_horaria Time,
FOREIGN KEY (num_turma) REFERENCES turma (id)
);

#tabela criada através de uma relaçção N:M
CREATE TABLE calendario_de_aula (
id INT AUTO_INCREMENT PRIMARY KEY,
ra_docente INT,
id_uc INT,
horario_inicio DATETIME NOT NULL,
horario_fim DATETIME DEFAULT NULL,
UNIQUE (ra_docente, horario_inicio, horario_fim),
UNIQUE (id_uc, horario_inicio, horario_fim),
FOREIGN KEY (ra_docente) REFERENCES docentes (ra),
FOREIGN KEY (id_uc) REFERENCES uc (id)
);

CREATE TABLE feriado (
id INT AUTO_INCREMENT PRIMARY KEY,
titulo VARCHAR(32) NOT NULL,
descricao VARCHAR(32) NOT NULL,
horario_inicio DATETIME NOT NULL,
horario_fim DATETIME DEFAULT NULL
);

DELIMITER $$

CREATE TRIGGER check_conflito_horario
BEFORE INSERT ON calendario_de_aula
FOR EACH ROW
BEGIN
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
END$$

CREATE TRIGGER INSERT_carga_horaria
AFTER INSERT ON calendario_de_aula
FOR EACH ROW
BEGIN
    DECLARE carga_horaria_aula INT;
    SET carga_horaria_aula = TIMESTAMPDIFF(HOUR, NEW.horario_inicio, NEW.horario_fim);

    UPDATE uc
    SET carga_horaria = carga_horaria - INTERVAL carga_horaria_aula HOUR
    WHERE id = NEW.id_uc;

    UPDATE turma
    SET carga_horaria = carga_horaria - INTERVAL carga_horaria_aula HOUR
    WHERE id = (SELECT num_turma FROM uc WHERE id = NEW.id_uc);
END$$

CREATE TRIGGER update_carga_horaria_update
AFTER UPDATE ON calendario_de_aula
FOR EACH ROW
BEGIN
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
END$$

CREATE TRIGGER delete_carga_horaria
AFTER DELETE ON calendario_de_aula
FOR EACH ROW
BEGIN
    DECLARE carga_horaria_aula INT;
    SET carga_horaria_aula = TIMESTAMPDIFF(HOUR, OLD.horario_inicio, OLD.horario_fim);

    UPDATE uc
    SET carga_horaria = carga_horaria + INTERVAL carga_horaria_aula HOUR
    WHERE id = OLD.id_uc;

    UPDATE turma
    SET carga_horaria = carga_horaria + INTERVAL carga_horaria_aula HOUR
    WHERE id = (SELECT num_turma FROM uc WHERE id = OLD.id_uc);
END$$

CREATE TRIGGER check_carga_horaria_uc
BEFORE INSERT ON uc
FOR EACH ROW
BEGIN
IF NEW.carga_horaria > (SELECT carga_horaria FROM turma WHERE id = NEW.num_turma) THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Carga horária da UC não pode ser maior do que a da Turma.';
END IF;
END$$

 
CREATE TRIGGER ck_carga_horaria_uc_before_insert 
BEFORE INSERT ON uc
FOR EACH ROW
BEGIN
  IF (SELECT SUM(carga_horaria) FROM uc WHERE num_turma = NEW.num_turma) + NEW.carga_horaria > 
    (SELECT carga_horaria FROM turma WHERE id = NEW.num_turma)
  THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'A soma da carga horária das UCs não pode ser maior que a carga horária da turma';
  END IF;
END$$


CREATE TRIGGER ck_carga_horaria_uc_before_update
BEFORE UPDATE ON uc
FOR EACH ROW
BEGIN
  IF (SELECT SUM(carga_horaria) FROM uc WHERE num_turma = NEW.num_turma AND id <> NEW.id) + NEW.carga_horaria > 
      (SELECT carga_horaria FROM turma WHERE id = NEW.num_turma) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Carga horária da UC não pode ser maior do que a da Turma.';
  END IF;
END$$


CREATE TRIGGER check_tipo_usuario_before_insert
BEFORE INSERT ON docentes
FOR EACH ROW
BEGIN
IF (SELECT tipo FROM usuario WHERE ra_user = NEW.ra) != 'docente' THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Somente usuários do tipo "docente" podem ser inseridos na tabela "docentes".';
END IF;
END$$

CREATE TRIGGER tr_calendario_de_aula_insert
BEFORE INSERT ON calendario_de_aula
FOR EACH ROW
BEGIN
    IF NEW.horario_fim < NEW.horario_inicio THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'O horário de fim não pode ser anterior ao horário de início.';
    END IF;
END$$

CREATE TRIGGER tr_calendario_de_aula_update
BEFORE update ON calendario_de_aula
FOR EACH ROW
BEGIN
    IF NEW.horario_fim < NEW.horario_inicio THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'O horário de fim não pode ser anterior ao horário de início.';
    END IF;
END$$

DELIMITER ;

#INSERTS
INSERT INTO usuario (ra_user, nome, email, senha, tipo)
VALUES ('1001','João da Silva','joao@', '123', 'administrador'), ('1002','Matheus','matheus@', '123', 'docente'), ('1003','Cadu','cadu@', '123', 'docente');

INSERT INTO docentes (ra)
VALUES (1002), (1003);

INSERT INTO turma (id, nome, tipo, carga_horaria)
VALUES
  ('0222','sistema','trilhas','820:00'),('0333','redes','trilhas','800:00'),('0444','administração','aprendizagem','780:00');
  
INSERT INTO uc (nome_uc, num_turma, carga_horaria)
VALUES ('web', 222, '50:00:00'),('desktop', 333, '40:00:00'), ('mobile', 222, '10:00:00'),('hardware', 333, '32:00:00'), ('Arquivos digitais', 444, '12:00:00') ;

INSERT INTO `calendario_de_aula` (`ra_docente`, `id_uc`, `horario_inicio`, `horario_fim`) VALUES
(1002, 1, '2023-03-19 07:30:00', '2023-03-19 11:30:00'),
(1003, 2, '2023-03-20 13:30:00', '2023-03-20 17:30:00');
