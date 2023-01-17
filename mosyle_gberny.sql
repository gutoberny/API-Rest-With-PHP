-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19-Nov-2022 às 17:51
-- Versão do servidor: 10.4.25-MariaDB
-- versão do PHP: 8.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `mosyle_gberny`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `drink_movement`
--

CREATE TABLE `drink_movement` (
  `idmov_drink` int(50) NOT NULL,
  `iduser` varchar(50) NOT NULL,
  `date_drink` date NOT NULL,
  `amount_coffee` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `drink_movement`
--

INSERT INTO `drink_movement` (`idmov_drink`, `iduser`, `date_drink`, `amount_coffee`) VALUES
(1, '1', '2022-11-09', 2),
(2, '1', '2022-11-10', 4),
(3, '1', '2022-11-19', 1),
(4, '1', '2022-11-19', 1),
(5, '1', '2022-11-19', 1),
(6, '1', '2022-11-19', 1),
(7, '1', '2022-11-19', 1),
(8, '1', '2022-11-19', 1),
(9, '1', '2022-11-19', 1),
(10, '1', '2022-11-19', 1),
(11, '2', '2022-11-19', 1),
(12, '2', '2022-11-19', 5),
(13, '3', '2022-11-19', 55),
(14, '3', '2022-11-19', -55),
(15, '3', '2022-11-19', 1),
(16, '3', '2022-11-19', 14),
(17, '4', '2022-11-19', 23),
(18, '5', '2022-11-19', 23),
(19, ':6', '2022-11-19', 4),
(20, ':7', '2022-11-19', 4),
(21, ':7', '2022-11-19', 4),
(22, '7', '2022-11-19', 4),
(23, '7', '2022-11-19', 4),
(24, '8', '2022-11-19', 4),
(25, '8', '2022-11-19', 6),
(26, 'Array', '2022-11-19', 6),
(27, '8', '2022-11-19', 6),
(28, '8', '2022-11-19', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `iduser` int(50) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `type_user` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`iduser`, `name`, `email`, `password`, `type_user`) VALUES
(1, 'Gustavo Berny', 'emaiuldaaasasdteste@123.com', 'naotemsenha', 1),
(2, 'Leticia de Borba', 'leteste@gmail.com', '123', 0),
(3, 'Nome novo', 'testenomenovo@gmail', '123', 0),
(5, 'teste5', 'teste5', 'teste5', 0),
(6, 'name2', 'emaiultesasdasdte@123.com', 'pass2', 0),
(7, 'name2', 'emaiultesasdasdte@123.com', 'pass2', 0),
(8, 'name2', 'emaiultesasdasdte@123.com', 'pass2', 0),
(9, 'new name test', 'emaiudasdasdltesasdasdte@123.com', 'pass2', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `drink_movement`
--
ALTER TABLE `drink_movement`
  ADD PRIMARY KEY (`idmov_drink`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`iduser`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `drink_movement`
--
ALTER TABLE `drink_movement`
  MODIFY `idmov_drink` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `iduser` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
