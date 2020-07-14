CREATE TABLE `veiculos` (
  `id` int(11) NOT NULL,
  `veiculo` varchar(255) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `ano` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `vendido` enum('true','false') NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `veiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;