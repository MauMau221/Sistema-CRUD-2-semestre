-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/01/2024 às 22:18
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `restaurante`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `administrador`
--

CREATE TABLE `administrador` (
  `ADM_ID` int(11) NOT NULL,
  `ADM_NOME` varchar(24) NOT NULL,
  `ADM_EMAIL` varchar(500) DEFAULT NULL,
  `ADM_SENHA` varchar(24) NOT NULL,
  `ADM_ATIVO` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `administrador`
--

INSERT INTO `administrador` (`ADM_ID`, `ADM_NOME`, `ADM_EMAIL`, `ADM_SENHA`, `ADM_ATIVO`) VALUES
(1, 'mauricio', 'mauriciobgsp@gmail.com', '12345', '1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho_item`
--

CREATE TABLE `carrinho_item` (
  `USUARIO_ID` int(11) NOT NULL,
  `PRODUTO_ID` int(11) NOT NULL,
  `ITEM_QTD` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `CATEGORIA_ID` int(11) NOT NULL,
  `CATEGORIA_NOME` varchar(255) NOT NULL,
  `CATEGORIA_DESC` varchar(255) NOT NULL,
  `CATEGORIA_ATIVO` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `endereco`
--

CREATE TABLE `endereco` (
  `ENDERECO_ID` int(11) NOT NULL,
  `USUARIO_ID` int(11) NOT NULL,
  `ENDERECO_NOME` varchar(500) NOT NULL,
  `ENDERECO_LAGRADOURO` varchar(500) NOT NULL,
  `ENDERECO_NUMERO` int(11) NOT NULL,
  `ENDERECO_COMPLEMENTO` varchar(100) NOT NULL,
  `ENDERECO_CEP` varchar(9) NOT NULL,
  `ENDERECO_CIDADE` varchar(100) NOT NULL,
  `ENDERECO_ESTADO` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido`
--

CREATE TABLE `pedido` (
  `PEDIDO_ID` int(11) NOT NULL,
  `USUARIO_ID` int(11) NOT NULL,
  `ENDERECO_ID` int(11) NOT NULL,
  `STATUS_ID` int(11) NOT NULL,
  `PEDIDO_DATA` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_item`
--

CREATE TABLE `pedido_item` (
  `PRODUTO_ID` int(11) NOT NULL,
  `PEDIDO_ID` int(11) NOT NULL,
  `ITEM_QTD` decimal(10,0) NOT NULL,
  `ITEM_PRECO` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido_status`
--

CREATE TABLE `pedido_status` (
  `STATUS_ID` int(11) NOT NULL,
  `STATUS_DESC` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `PRODUTO_ID` int(11) NOT NULL,
  `PRODUTO_NOME` varchar(255) NOT NULL,
  `PRODUTO_DESC` varchar(500) NOT NULL,
  `PRODUTO_PRECO` decimal(10,2) NOT NULL,
  `PRODUTO_DESCONTO` decimal(10,2) NOT NULL,
  `PRODUTO_ATIVO` bit(1) NOT NULL,
  `CATEGORIA_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto_estoque`
--

CREATE TABLE `produto_estoque` (
  `PRODUTO_ID` int(11) NOT NULL,
  `PRODUTO_QTD` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto_imagem`
--

CREATE TABLE `produto_imagem` (
  `IMAGEM_ID` int(1) NOT NULL,
  `IMAGEM_URL` varchar(8000) NOT NULL,
  `IMAGEM_ORDEM` int(11) NOT NULL,
  `PRODUTO_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `USUARIO_ID` int(11) NOT NULL,
  `USUARIO_NOME` varchar(100) NOT NULL,
  `USUARIO_EMAIL` varchar(200) NOT NULL,
  `USUARIO_SENHA` varchar(100) NOT NULL,
  `USUARIO_CPF` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`ADM_ID`);

--
-- Índices de tabela `carrinho_item`
--
ALTER TABLE `carrinho_item`
  ADD KEY `carrinho_item_ibfk_2` (`USUARIO_ID`),
  ADD KEY `PRODUTO_ID` (`PRODUTO_ID`);

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`CATEGORIA_ID`);

--
-- Índices de tabela `endereco`
--
ALTER TABLE `endereco`
  ADD PRIMARY KEY (`ENDERECO_ID`),
  ADD KEY `USUARIO_ID` (`USUARIO_ID`);

--
-- Índices de tabela `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`PEDIDO_ID`),
  ADD KEY `USUARIO_ID` (`USUARIO_ID`),
  ADD KEY `ENDERECO_ID` (`ENDERECO_ID`),
  ADD KEY `STATUS_ID` (`STATUS_ID`);

--
-- Índices de tabela `pedido_item`
--
ALTER TABLE `pedido_item`
  ADD KEY `PEDIDO_ID` (`PEDIDO_ID`),
  ADD KEY `PRODUTO_ID` (`PRODUTO_ID`);

--
-- Índices de tabela `pedido_status`
--
ALTER TABLE `pedido_status`
  ADD PRIMARY KEY (`STATUS_ID`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`PRODUTO_ID`),
  ADD KEY `CATEGORIA_ID` (`CATEGORIA_ID`);

--
-- Índices de tabela `produto_estoque`
--
ALTER TABLE `produto_estoque`
  ADD KEY `PRODUTO_ID` (`PRODUTO_ID`);

--
-- Índices de tabela `produto_imagem`
--
ALTER TABLE `produto_imagem`
  ADD PRIMARY KEY (`IMAGEM_ID`),
  ADD KEY `PRODUTO_ID` (`PRODUTO_ID`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`USUARIO_ID`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administrador`
--
ALTER TABLE `administrador`
  MODIFY `ADM_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `CATEGORIA_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `endereco`
--
ALTER TABLE `endereco`
  MODIFY `ENDERECO_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `PEDIDO_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedido_status`
--
ALTER TABLE `pedido_status`
  MODIFY `STATUS_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `PRODUTO_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `produto_imagem`
--
ALTER TABLE `produto_imagem`
  MODIFY `IMAGEM_ID` int(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `USUARIO_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carrinho_item`
--
ALTER TABLE `carrinho_item`
  ADD CONSTRAINT `carrinho_item_ibfk_2` FOREIGN KEY (`USUARIO_ID`) REFERENCES `usuario` (`USUARIO_ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `carrinho_item_ibfk_3` FOREIGN KEY (`PRODUTO_ID`) REFERENCES `produto` (`PRODUTO_ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Restrições para tabelas `endereco`
--
ALTER TABLE `endereco`
  ADD CONSTRAINT `endereco_ibfk_1` FOREIGN KEY (`USUARIO_ID`) REFERENCES `usuario` (`USUARIO_ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Restrições para tabelas `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`USUARIO_ID`) REFERENCES `usuario` (`USUARIO_ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`ENDERECO_ID`) REFERENCES `endereco` (`ENDERECO_ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `pedido_ibfk_3` FOREIGN KEY (`STATUS_ID`) REFERENCES `pedido_status` (`STATUS_ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Restrições para tabelas `pedido_item`
--
ALTER TABLE `pedido_item`
  ADD CONSTRAINT `pedido_item_ibfk_1` FOREIGN KEY (`PEDIDO_ID`) REFERENCES `pedido` (`PEDIDO_ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `pedido_item_ibfk_2` FOREIGN KEY (`PRODUTO_ID`) REFERENCES `produto` (`PRODUTO_ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Restrições para tabelas `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `produto_ibfk_1` FOREIGN KEY (`CATEGORIA_ID`) REFERENCES `categoria` (`CATEGORIA_ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Restrições para tabelas `produto_estoque`
--
ALTER TABLE `produto_estoque`
  ADD CONSTRAINT `produto_estoque_ibfk_1` FOREIGN KEY (`PRODUTO_ID`) REFERENCES `produto` (`PRODUTO_ID`) ON UPDATE NO ACTION;

--
-- Restrições para tabelas `produto_imagem`
--
ALTER TABLE `produto_imagem`
  ADD CONSTRAINT `produto_imagem_ibfk_1` FOREIGN KEY (`PRODUTO_ID`) REFERENCES `produto` (`PRODUTO_ID`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
