
CREATE TABLE IF NOT EXISTS `category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_parent` bigint(20) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `position` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_parent` (`id_parent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `id_parent`, `name`, `icon`, `description`, `position`) VALUES
(1, NULL, 'Test cat', '', '', 0),
(2, NULL, 'Test de cat 2', '', '', 0),
(3, NULL, 'Supercurve23', '', '', 0),
(4, 1, 'a', '', '', 0),
(5, 1, 'b', '', '', 1),
(6, 2, 'c', '', '', 0),
(7, 2, 'd', '', '', 1),
(8, 2, 'e', '', '', 2),
(9, 3, 'f', '', '', 0),
(10, 7, 'i', '', '', 0),
(11, 7, 'ii', '', '', 1),
(12, 8, 'i', '', '', 0),
(13, NULL, 'M3C', '', '', 3),
(14, 13, 'Year', '', '', 0),
(15, 13, 'Month', '', '', 2),
(16, 13, 'Quart', '', '', 1),
(17, 13, 'Other', '', '', 3);

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`id_parent`) REFERENCES `category` (`id`) ON DELETE CASCADE;
