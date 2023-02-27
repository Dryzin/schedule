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
id INT AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(255),
cpf VARCHAR(255),
tipo ENUM('docente', 'administrador')
);

CREATE TABLE docentes (
ra INT PRIMARY KEY,
usuario_id INT unique,
FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

CREATE TABLE turma (
id INT PRIMARY KEY,
nome VARCHAR(255),
tipo ENUM('trilhas', 'aprendizagem'),
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
horario_inicio DATETIME,
horario_fim DATETIME,
UNIQUE (ra_docente, horario_inicio, horario_fim),
UNIQUE (id_uc, horario_inicio, horario_fim),
FOREIGN KEY (ra_docente) REFERENCES docentes (ra),
FOREIGN KEY (id_uc) REFERENCES uc (id)
);

#INSERTS
INSERT INTO usuario (nome, cpf, tipo)
VALUES ('João da Silva', '111.111.111-11', 'administrador'), ('Matheus', '222.222.222-22', 'docente'), ('Cadu', '333.333.333-33', 'docente');

INSERT INTO docentes (ra, usuario_id)
VALUES (1002, 2), (1003, 3);

INSERT INTO turma (id, nome, tipo, carga_horaria)
VALUES
  ('0222','sistema','trilhas','820:00'),('0333','redes','trilhas','800:00'),('0444','administração','aprendizagem','780:00');
  
INSERT INTO uc (nome_uc, num_turma, carga_horaria)
VALUES ('web', 222, '50:00:00'),('desktop', 333, '40:00:00'), ('mobile', 222, '10:00:00'),('hardware', 333, '32:00:00'), ('Arquivos digitais', 444, '12:00:00') ;


INSERT INTO `calendario_de_aula` (`ra_docente`, `id_uc`, `horario_inicio`, `horario_fim`) VALUES
(1002, 1, '2023-02-19 07:30:00', '2023-02-19 11:30:00'),
(1003, 2, '2023-02-20 13:30:00', '2023-02-20 17:30:00');
