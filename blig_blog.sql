-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Ago 26, 2022 alle 11:19
-- Versione del server: 10.4.20-MariaDB
-- Versione PHP: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blig_blog`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `abbonamenti`
--

CREATE TABLE `abbonamenti` (
  `id` int(11) UNSIGNED NOT NULL,
  `nome_abbonamento` varchar(255) NOT NULL,
  `descrizione_abbonamento` text NOT NULL,
  `prezzo_abbonamento` float NOT NULL,
  `abbonamento_default` tinyint(1) NOT NULL DEFAULT 0,
  `blog_max` int(2) NOT NULL DEFAULT 3,
  `articoli_max` int(3) NOT NULL DEFAULT 20,
  `template_disponibili` set('base','delicato','caldo','') NOT NULL DEFAULT 'base'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `abbonamenti`
--

INSERT INTO `abbonamenti` (`id`, `nome_abbonamento`, `descrizione_abbonamento`, `prezzo_abbonamento`, `abbonamento_default`, `blog_max`, `articoli_max`, `template_disponibili`) VALUES
(10, 'Blig Gratis', '<li>Tre blog a disposizione</li>\n<li>Massimo venti post pubblicati per blog</li>\n<li>Template base</li>', 0, 1, 3, 20, 'base'),
(20, 'Blig Premium', '<li>Dieci blog a disposizione</li>                                     <li>Articoli infiniti su tutti i blog</li>                                    <li>Due template:</li>\r\n<li>-Base</li> \r\n<li>-Delicato</li>                                                                         ', 15, 0, 10, -1, 'base,delicato'),
(30, 'Blig Deluxe', '<li>Blog infiniti</li>\n                                        <li>Articoli infiniti</li>\n                                        <li>Tre template:</li>\n<li>-Base</li>                                     \n<li>-Delicato</li>                                     \n<li>-Caldo</li>                                     ', 50, 0, -1, -1, 'base,delicato,caldo');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `abbonamenti_limiti`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `abbonamenti_limiti` (
`id_utente_abbonamento` int(11) unsigned
,`blog_max` int(2)
,`articoli_max` int(3)
,`template_disponibili` set('base','delicato','caldo','')
);

-- --------------------------------------------------------

--
-- Struttura della tabella `abbonamenti_utenti`
--

CREATE TABLE `abbonamenti_utenti` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_utente_abbonamento` int(11) UNSIGNED NOT NULL,
  `id_abbonamento` int(11) UNSIGNED NOT NULL,
  `data_inizio_abbonamento` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_fine_abbonamento` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `abbonamenti_utenti`
--

INSERT INTO `abbonamenti_utenti` (`id`, `id_utente_abbonamento`, `id_abbonamento`, `data_inizio_abbonamento`, `data_fine_abbonamento`) VALUES
(1, 1, 30, '2022-08-26 08:51:49', '2023-08-26 08:51:49'),
(2, 2, 10, '2022-08-26 09:06:26', '2023-08-26 09:06:26');

-- --------------------------------------------------------

--
-- Struttura della tabella `articoli`
--

CREATE TABLE `articoli` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_blog` int(11) UNSIGNED NOT NULL,
  `titolo_articolo` varchar(255) NOT NULL,
  `testo_articolo` text NOT NULL,
  `id_utente_articolo` int(11) UNSIGNED DEFAULT NULL,
  `tags` varchar(255) NOT NULL,
  `immagine_cop` varchar(255) DEFAULT NULL,
  `immagine_art` varchar(255) DEFAULT NULL,
  `data_creazione_articolo` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_modifica_articolo` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `data_pubblicazione_articolo` timestamp NOT NULL DEFAULT current_timestamp(),
  `bozza` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `articoli`
--

INSERT INTO `articoli` (`id`, `id_blog`, `titolo_articolo`, `testo_articolo`, `id_utente_articolo`, `tags`, `immagine_cop`, `immagine_art`, `data_creazione_articolo`, `data_modifica_articolo`, `data_pubblicazione_articolo`, `bozza`) VALUES
(1, 1, 'Questo è una prova', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sollicitudin vehicula purus non auctor. In vitae risus a sem semper ullamcorper. Integer eget mauris molestie, blandit dolor ut, scelerisque lectus. Phasellus non mollis augue, vitae aliquam massa. Nam varius laoreet metus, vitae tincidunt felis placerat nec. Aenean maximus nisi vel ipsum facilisis vulputate. Maecenas urna lacus, pellentesque et metus nec, sodales commodo libero. Vivamus ipsum quam, bibendum a hendrerit aliquet, porta et augue. Vestibulum lobortis pulvinar egestas. Ut aliquet dapibus felis sit amet fringilla. Donec eu augue ac nunc feugiat feugiat. Vivamus metus neque, posuere cursus tempor in, volutpat sodales purus. In hac habitasse platea dictumst. Mauris aliquam non purus eu lacinia. Ut pulvinar ante et nibh egestas pulvinar. Donec ultricies sapien ac urna ornare finibus.</p>', 1, 'prova,test', 'cop_1661503857_852.jpeg', 'art_1661503857_852.jpeg', '2022-08-26 08:50:57', '2022-08-26 08:57:23', '2022-08-26 08:57:23', 0),
(2, 1, 'Ciao a tutti', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sollicitudin vehicula purus non auctor. In vitae risus a sem semper ullamcorper. Integer eget mauris molestie, blandit dolor ut, scelerisque lectus. Phasellus non mollis augue, vitae aliquam massa. Nam varius laoreet metus, vitae tincidunt felis placerat nec. Aenean maximus nisi vel ipsum facilisis vulputate. Maecenas urna lacus, pellentesque et metus nec, sodales commodo libero. Vivamus ipsum quam, bibendum a hendrerit aliquet, porta et augue. Vestibulum lobortis pulvinar egestas. Ut aliquet dapibus felis sit amet fringilla. Donec eu augue ac nunc feugiat feugiat. Vivamus metus neque, posuere cursus tempor in, volutpat sodales purus. In hac habitasse platea dictumst. Mauris aliquam non purus eu lacinia. Ut pulvinar ante et nibh egestas pulvinar. Donec ultricies sapien ac urna ornare finibus.</p>', 1, '', 'cop_1661503879_953.jpeg', NULL, '2022-08-26 08:51:19', '2022-08-26 09:00:44', '2022-08-26 09:00:44', 0),
(3, 2, 'È difficile scrivere con questa fantasia', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sollicitudin vehicula purus non auctor. In vitae risus a sem semper ullamcorper. Integer eget mauris molestie, blandit dolor ut, scelerisque lectus. Phasellus non mollis augue, vitae aliquam massa. Nam varius laoreet metus, vitae tincidunt felis placerat nec. Aenean maximus nisi vel ipsum facilisis vulputate. Maecenas urna lacus, pellentesque et metus nec, sodales commodo libero. Vivamus ipsum quam, bibendum a hendrerit aliquet, porta et augue. Vestibulum lobortis pulvinar egestas. Ut aliquet dapibus felis sit amet fringilla. Donec eu augue ac nunc feugiat feugiat. Vivamus metus neque, posuere cursus tempor in, volutpat sodales purus. In hac habitasse platea dictumst. Mauris aliquam non purus eu lacinia. Ut pulvinar ante et nibh egestas pulvinar. Donec ultricies sapien ac urna ornare finibus.</p>', 1, '', NULL, 'art_1661503997_765.jpeg', '2022-08-26 08:53:17', NULL, '2022-08-26 08:52:17', 0),
(4, 2, 'Ancora due articoli', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sollicitudin vehicula purus non auctor. In vitae risus a sem semper ullamcorper. Integer eget mauris molestie, blandit dolor ut, scelerisque lectus. Phasellus non mollis augue, vitae aliquam massa. Nam varius laoreet metus, vitae tincidunt felis placerat nec. Aenean maximus nisi vel ipsum facilisis vulputate. Maecenas urna lacus, pellentesque et metus nec, sodales commodo libero. Vivamus ipsum quam, bibendum a hendrerit aliquet, porta et augue. Vestibulum lobortis pulvinar egestas. Ut aliquet dapibus felis sit amet fringilla. Donec eu augue ac nunc feugiat feugiat. Vivamus metus neque, posuere cursus tempor in, volutpat sodales purus. In hac habitasse platea dictumst. Mauris aliquam non purus eu lacinia. Ut pulvinar ante et nibh egestas pulvinar. Donec ultricies sapien ac urna ornare finibus.</p>', 1, '', NULL, NULL, '2022-08-26 08:53:30', NULL, '2022-08-26 08:53:30', 0),
(5, 2, 'Questo è un titolo', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sollicitudin vehicula purus non auctor. In vitae risus a sem semper ullamcorper. Integer eget mauris molestie, blandit dolor ut, scelerisque lectus. Phasellus non mollis augue, vitae aliquam massa. Nam varius laoreet metus, vitae tincidunt felis placerat nec. Aenean maximus nisi vel ipsum facilisis vulputate. Maecenas urna lacus, pellentesque et metus nec, sodales commodo libero. Vivamus ipsum quam, bibendum a hendrerit aliquet, porta et augue. Vestibulum lobortis pulvinar egestas. Ut aliquet dapibus felis sit amet fringilla. Donec eu augue ac nunc feugiat feugiat. Vivamus metus neque, posuere cursus tempor in, volutpat sodales purus. In hac habitasse platea dictumst. Mauris aliquam non purus eu lacinia. Ut pulvinar ante et nibh egestas pulvinar. Donec ultricies sapien ac urna ornare finibus.</p>', 1, '', NULL, NULL, '2022-08-26 08:53:42', NULL, '2022-08-26 08:53:42', 0),
(6, 3, 'prova provosa', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sollicitudin vehicula purus non auctor. In vitae risus a sem semper ullamcorper. Integer eget mauris molestie, blandit dolor ut, scelerisque lectus. Phasellus non mollis augue, vitae aliquam massa. Nam varius laoreet metus, vitae tincidunt felis placerat nec. Aenean maximus nisi vel ipsum facilisis vulputate. Maecenas urna lacus, pellentesque et metus nec, sodales commodo libero. Vivamus ipsum quam, bibendum a hendrerit aliquet, porta et augue. Vestibulum lobortis pulvinar egestas. Ut aliquet dapibus felis sit amet fringilla. Donec eu augue ac nunc feugiat feugiat. Vivamus metus neque, posuere cursus tempor in, volutpat sodales purus. In hac habitasse platea dictumst. Mauris aliquam non purus eu lacinia. Ut pulvinar ante et nibh egestas pulvinar. Donec ultricies sapien ac urna ornare finibus.</p>', 1, '', NULL, NULL, '2022-08-26 09:03:25', NULL, '2022-08-26 09:03:25', 0),
(7, 3, 'la prova delle prove', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sollicitudin vehicula purus non auctor. In vitae risus a sem semper ullamcorper. Integer eget mauris molestie, blandit dolor ut, scelerisque lectus. Phasellus non mollis augue, vitae aliquam massa. Nam varius laoreet metus, vitae tincidunt felis placerat nec. Aenean maximus nisi vel ipsum facilisis vulputate. Maecenas urna lacus, pellentesque et metus nec, sodales commodo libero. Vivamus ipsum quam, bibendum a hendrerit aliquet, porta et augue. Vestibulum lobortis pulvinar egestas. Ut aliquet dapibus felis sit amet fringilla. Donec eu augue ac nunc feugiat feugiat. Vivamus metus neque, posuere cursus tempor in, volutpat sodales purus. In hac habitasse platea dictumst. Mauris aliquam non purus eu lacinia. Ut pulvinar ante et nibh egestas pulvinar. Donec ultricies sapien ac urna ornare finibus.</p>', 1, '', NULL, NULL, '2022-08-26 09:03:34', NULL, '2022-08-26 09:03:34', 0),
(8, 4, 'Questo è un articolo creato da me, il coautore', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sollicitudin vehicula purus non auctor. In vitae risus a sem semper ullamcorper. Integer eget mauris molestie, blandit dolor ut, scelerisque lectus. Phasellus non mollis augue, vitae aliquam massa. Nam varius laoreet metus, vitae tincidunt felis placerat nec. Aenean maximus nisi vel ipsum facilisis vulputate. Maecenas urna lacus, pellentesque et metus nec, sodales commodo libero. Vivamus ipsum quam, bibendum a hendrerit aliquet, porta et augue. Vestibulum lobortis pulvinar egestas. Ut aliquet dapibus felis sit amet fringilla. Donec eu augue ac nunc feugiat feugiat. Vivamus metus neque, posuere cursus tempor in, volutpat sodales purus. In hac habitasse platea dictumst. Mauris aliquam non purus eu lacinia. Ut pulvinar ante et nibh egestas pulvinar. Donec ultricies sapien ac urna ornare finibus.</p>', 1, '', NULL, NULL, '2022-08-26 09:17:59', NULL, '2022-08-26 09:17:59', 0);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `articoli_conteggio`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `articoli_conteggio` (
`id_articolo` int(11) unsigned
,`commenti_totali` bigint(21)
,`voto_totale` decimal(25,0)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `articoli_full`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `articoli_full` (
`id` int(11) unsigned
,`id_blog` int(11) unsigned
,`titolo_articolo` varchar(255)
,`testo_articolo` text
,`id_utente_articolo` int(11) unsigned
,`tags` varchar(255)
,`immagine_cop` varchar(255)
,`immagine_art` varchar(255)
,`data_creazione_articolo` timestamp
,`data_modifica_articolo` timestamp
,`data_pubblicazione_articolo` timestamp
,`bozza` tinyint(1)
,`username_utente` varchar(100)
,`titolo_blog` varchar(255)
,`indirizzo_blog` varchar(30)
,`commenti_totali` bigint(21)
,`voto_totale` decimal(25,0)
,`pubblicato` int(1)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `autori`
--

CREATE TABLE `autori` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_blog` int(11) UNSIGNED NOT NULL,
  `id_utente` int(11) UNSIGNED NOT NULL,
  `stato_autore` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `autori`
--

INSERT INTO `autori` (`id`, `id_blog`, `id_utente`, `stato_autore`) VALUES
(1, 4, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `blog`
--

CREATE TABLE `blog` (
  `id` int(11) UNSIGNED NOT NULL,
  `indirizzo_blog` varchar(30) NOT NULL,
  `id_utente` int(11) UNSIGNED NOT NULL,
  `id_categoria` int(11) UNSIGNED DEFAULT NULL,
  `titolo_blog` varchar(255) NOT NULL,
  `descrizione_blog` varchar(255) DEFAULT NULL,
  `data_creazione_blog` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Valore creato automaticamente',
  `id_template` int(11) UNSIGNED NOT NULL,
  `sottocategorie` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `blog`
--

INSERT INTO `blog` (`id`, `indirizzo_blog`, `id_utente`, `id_categoria`, `titolo_blog`, `descrizione_blog`, `data_creazione_blog`, `id_template`, `sottocategorie`) VALUES
(1, 'prova', 1, 1, 'Blog della prova', 'Un blog semplice per provare', '2022-08-26 08:47:42', 0, '1'),
(2, 'provadue', 1, 1, 'Blog della seconda prova', 'La seconda prova', '2022-08-26 08:49:17', 1, '2'),
(3, 'terzaprova', 1, 9, 'Blog della terza prova', 'Il mio terzo blog, che emozione!', '2022-08-26 08:52:25', 2, '27'),
(4, 'bianchiblog', 2, 5, 'Blog di marco bianchi', 'IL mio super blog', '2022-08-26 09:07:01', 0, '14');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `blog_full`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `blog_full` (
`id` int(11) unsigned
,`indirizzo_blog` varchar(30)
,`id_utente` int(11) unsigned
,`id_categoria` int(11) unsigned
,`titolo_blog` varchar(255)
,`descrizione_blog` varchar(255)
,`data_creazione_blog` timestamp
,`id_template` int(11) unsigned
,`sottocategorie` varchar(255)
,`nome_categoria` varchar(255)
,`nome_template` varchar(100)
,`nome_utente` varchar(255)
,`username_utente` varchar(100)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) UNSIGNED NOT NULL,
  `nome_categoria` varchar(255) NOT NULL,
  `descrizione_categoria` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `categoria`
--

INSERT INTO `categoria` (`id`, `nome_categoria`, `descrizione_categoria`) VALUES
(1, 'Informatica', 'Tutto ciò che è inerente al mondo dell\'informatica'),
(2, 'Cucina', 'Condivisione di ricette, trucchi e consigli sulla cucina'),
(3, 'Hobby', 'Fai scoprire le tue passioni e i tuoi passatempi!'),
(4, 'Natura', 'Per gli appassionati del mondo naturale e i suoi abitanti'),
(5, 'Sport', 'Per parlare di tutti gli sport e attività sportive'),
(6, 'Vestiti', 'Condividi idee, design o outlook nel tuo blog!'),
(7, 'Motori', 'Tutto ciò che è inerente alle automobili, moto o altri mezzi motorizzati!'),
(8, 'Vacanze', 'Dai consigli o condividi esperienze su villeggiature o luoghi per vacanze'),
(9, 'Personale', 'Parla della tua vita, dei tuoi punti di vista o della tue esperienze nel tuo blog!');

-- --------------------------------------------------------

--
-- Struttura della tabella `commenti`
--

CREATE TABLE `commenti` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_utente_commento` int(11) UNSIGNED DEFAULT NULL,
  `id_articolo_commento` int(11) UNSIGNED NOT NULL,
  `testo_commento` varchar(150) NOT NULL,
  `data_commento` timestamp NOT NULL DEFAULT current_timestamp(),
  `conteggio_voto` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `commenti`
--

INSERT INTO `commenti` (`id`, `id_utente_commento`, `id_articolo_commento`, `testo_commento`, `data_commento`, `conteggio_voto`) VALUES
(1, 1, 2, 'Il mio commento sul mio post', '2022-08-26 09:15:37', 0),
(2, 2, 2, 'il mio commento sul suo post', '2022-08-26 09:16:25', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `sottocategorie`
--

CREATE TABLE `sottocategorie` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_categoria` int(11) UNSIGNED NOT NULL,
  `nome_sottocategoria` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `sottocategorie`
--

INSERT INTO `sottocategorie` (`id`, `id_categoria`, `nome_sottocategoria`) VALUES
(1, 1, 'javascript'),
(2, 1, 'php'),
(3, 1, 'html'),
(4, 2, 'Dolci'),
(5, 2, 'Vegano'),
(6, 2, 'Vegetariano'),
(7, 3, 'Modellistica'),
(8, 3, 'Musica'),
(9, 3, 'Pittura'),
(10, 4, 'Giardinaggio'),
(11, 4, 'Campeggio'),
(12, 4, 'Birdwatching'),
(13, 5, 'Calcio'),
(14, 5, 'Basket'),
(15, 5, 'Nuoto'),
(16, 6, 'Alta moda'),
(17, 6, 'Fashion'),
(18, 6, 'Casual'),
(19, 7, 'Moto'),
(20, 7, 'Macchine'),
(21, 7, 'Veicoli d\'epoca'),
(22, 8, 'Tropicali'),
(23, 8, 'Cultura'),
(24, 8, 'Enogastronomia'),
(25, 9, 'Real life'),
(26, 9, 'Diario'),
(27, 9, 'Consigli e trucchi');

-- --------------------------------------------------------

--
-- Struttura della tabella `template`
--

CREATE TABLE `template` (
  `id` int(11) UNSIGNED NOT NULL,
  `nome_template` varchar(100) NOT NULL,
  `descrizione_template` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `template`
--

INSERT INTO `template` (`id`, `nome_template`, `descrizione_template`) VALUES
(0, 'base', 'Un template minimalista'),
(1, 'delicato', 'Un template confusionario'),
(2, 'caldo', 'Un template non troppo forte');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) UNSIGNED NOT NULL,
  `nome_utente` varchar(255) DEFAULT NULL,
  `cognome_utente` varchar(255) DEFAULT NULL,
  `email_utente` varchar(255) DEFAULT NULL,
  `username_utente` varchar(100) DEFAULT NULL,
  `password_utente` varchar(255) DEFAULT NULL COMMENT 'Chiave hash, non password utente reale',
  `tipo_documento` varchar(255) DEFAULT NULL,
  `estremi_documento_utente` varchar(16) DEFAULT NULL,
  `telefono_utente` varchar(15) DEFAULT NULL,
  `descrizione_utente` text DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Valore che indica se un utente è un amministratore'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `nome_utente`, `cognome_utente`, `email_utente`, `username_utente`, `password_utente`, `tipo_documento`, `estremi_documento_utente`, `telefono_utente`, `descrizione_utente`, `admin`) VALUES
(1, 'Lorenzo', 'Cecio', 'lorenzo-cecio@hotmail.it', 'Prova', '$2y$10$O2kndb7ChwqtRwkL6lC5UOukqnA5W2frFiaKWLxQTKnt6mk1pr5va', 'codice_fiscale', 'CCELNZ98C30E625O', '3931616639', 'La mia descrizione', 0),
(2, 'Marco', 'Bianchi', 'marco_bianchi@gmail.com', 'prova2', '$2y$10$Y2fVDJxDcid/nHlryUAgsOHbrMWzGQMghmnAqvJuialPWtn3slPEK', 'codice_fiscale', 'RPCDZO35A31D009R', '123456789', NULL, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `voti`
--

CREATE TABLE `voti` (
  `id` int(11) UNSIGNED NOT NULL,
  `voto` tinyint(1) NOT NULL,
  `id_utente_voto` int(11) UNSIGNED NOT NULL,
  `id_articolo_voto` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `voti`
--

INSERT INTO `voti` (`id`, `voto`, `id_utente_voto`, `id_articolo_voto`) VALUES
(1, -1, 1, 2),
(2, -1, 2, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `voucher`
--

CREATE TABLE `voucher` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_abbonamento` int(11) UNSIGNED NOT NULL,
  `codice_voucher` varchar(15) NOT NULL,
  `contatore_voucher` int(3) NOT NULL,
  `utilizzi_massimi_voucher` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `voucher`
--

INSERT INTO `voucher` (`id`, `id_abbonamento`, `codice_voucher`, `contatore_voucher`, `utilizzi_massimi_voucher`) VALUES
(1, 20, 'P100', 0, 1),
(2, 30, 'D100', 1, 1);

-- --------------------------------------------------------

--
-- Struttura per vista `abbonamenti_limiti`
--
DROP TABLE IF EXISTS `abbonamenti_limiti`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `abbonamenti_limiti`  AS SELECT `ab`.`id_utente_abbonamento` AS `id_utente_abbonamento`, `a`.`blog_max` AS `blog_max`, `a`.`articoli_max` AS `articoli_max`, `a`.`template_disponibili` AS `template_disponibili` FROM (`abbonamenti` `a` join `abbonamenti_utenti` `ab` on(`ab`.`id_abbonamento` = `a`.`id`)) ;

-- --------------------------------------------------------

--
-- Struttura per vista `articoli_conteggio`
--
DROP TABLE IF EXISTS `articoli_conteggio`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `articoli_conteggio`  AS SELECT `articoli`.`id` AS `id_articolo`, coalesce(`tabella_commenti`.`commenti_totali`,0) AS `commenti_totali`, coalesce(`tabella_voti`.`voto_totale`,0) AS `voto_totale` FROM ((`articoli` left join (select `commenti`.`id_articolo_commento` AS `id_articolo_commento`,count(0) AS `commenti_totali` from `commenti` group by `commenti`.`id_articolo_commento`) `tabella_commenti` on(`articoli`.`id` = `tabella_commenti`.`id_articolo_commento`)) left join (select `voti`.`id_articolo_voto` AS `id_articolo_voto`,sum(`voti`.`voto`) AS `voto_totale` from `voti` group by `voti`.`id_articolo_voto`) `tabella_voti` on(`articoli`.`id` = `tabella_voti`.`id_articolo_voto`)) ;

-- --------------------------------------------------------

--
-- Struttura per vista `articoli_full`
--
DROP TABLE IF EXISTS `articoli_full`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `articoli_full`  AS SELECT `articoli`.`id` AS `id`, `articoli`.`id_blog` AS `id_blog`, `articoli`.`titolo_articolo` AS `titolo_articolo`, `articoli`.`testo_articolo` AS `testo_articolo`, `articoli`.`id_utente_articolo` AS `id_utente_articolo`, `articoli`.`tags` AS `tags`, `articoli`.`immagine_cop` AS `immagine_cop`, `articoli`.`immagine_art` AS `immagine_art`, `articoli`.`data_creazione_articolo` AS `data_creazione_articolo`, `articoli`.`data_modifica_articolo` AS `data_modifica_articolo`, `articoli`.`data_pubblicazione_articolo` AS `data_pubblicazione_articolo`, `articoli`.`bozza` AS `bozza`, `utenti`.`username_utente` AS `username_utente`, `blog`.`titolo_blog` AS `titolo_blog`, `blog`.`indirizzo_blog` AS `indirizzo_blog`, coalesce(`tabella_commenti`.`commenti_totali`,0) AS `commenti_totali`, coalesce(`tabella_voti`.`voto_totale`,0) AS `voto_totale`, if(`articoli`.`bozza` = 0 and `articoli`.`data_pubblicazione_articolo` <= current_timestamp(),1,0) AS `pubblicato` FROM ((((`articoli` left join `utenti` on(`utenti`.`id` = `articoli`.`id_utente_articolo`)) left join `blog` on(`blog`.`id` = `articoli`.`id_blog`)) left join (select `commenti`.`id_articolo_commento` AS `id_articolo_commento`,count(0) AS `commenti_totali` from `commenti` group by `commenti`.`id_articolo_commento`) `tabella_commenti` on(`articoli`.`id` = `tabella_commenti`.`id_articolo_commento`)) left join (select `voti`.`id_articolo_voto` AS `id_articolo_voto`,sum(`voti`.`voto`) AS `voto_totale` from `voti` group by `voti`.`id_articolo_voto`) `tabella_voti` on(`articoli`.`id` = `tabella_voti`.`id_articolo_voto`)) ;

-- --------------------------------------------------------

--
-- Struttura per vista `blog_full`
--
DROP TABLE IF EXISTS `blog_full`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `blog_full`  AS SELECT `blog`.`id` AS `id`, `blog`.`indirizzo_blog` AS `indirizzo_blog`, `blog`.`id_utente` AS `id_utente`, `blog`.`id_categoria` AS `id_categoria`, `blog`.`titolo_blog` AS `titolo_blog`, `blog`.`descrizione_blog` AS `descrizione_blog`, `blog`.`data_creazione_blog` AS `data_creazione_blog`, `blog`.`id_template` AS `id_template`, `blog`.`sottocategorie` AS `sottocategorie`, `categoria`.`nome_categoria` AS `nome_categoria`, `template`.`nome_template` AS `nome_template`, `utenti`.`nome_utente` AS `nome_utente`, `utenti`.`username_utente` AS `username_utente` FROM (((`blog` left join `utenti` on(`utenti`.`id` = `blog`.`id_utente`)) left join `categoria` on(`categoria`.`id` = `blog`.`id_categoria`)) left join `template` on(`template`.`id` = `blog`.`id_template`)) ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `abbonamenti`
--
ALTER TABLE `abbonamenti`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `abbonamenti_utenti`
--
ALTER TABLE `abbonamenti_utenti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `abbonamenti_tipo_del` (`id_abbonamento`),
  ADD KEY `abbonamenti_u_del` (`id_utente_abbonamento`);

--
-- Indici per le tabelle `articoli`
--
ALTER TABLE `articoli`
  ADD PRIMARY KEY (`id`),
  ADD KEY `articoli_blog_del` (`id_blog`),
  ADD KEY `articoli_utente_del` (`id_utente_articolo`);

--
-- Indici per le tabelle `autori`
--
ALTER TABLE `autori`
  ADD PRIMARY KEY (`id`),
  ADD KEY `autori_blog_del` (`id_blog`),
  ADD KEY `autori_utente_del` (`id_utente`);

--
-- Indici per le tabelle `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `indirizzo_blog` (`indirizzo_blog`),
  ADD KEY `blog_categoria_del` (`id_categoria`),
  ADD KEY `blog_template_del` (`id_template`),
  ADD KEY `blog_utente_del` (`id_utente`);

--
-- Indici per le tabelle `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `commenti`
--
ALTER TABLE `commenti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commenti_utenti_del` (`id_utente_commento`) USING BTREE COMMENT 'Al cancellare dell''utente, cancello i suoi commenti',
  ADD KEY `commento_articolo_del` (`id_articolo_commento`);

--
-- Indici per le tabelle `sottocategorie`
--
ALTER TABLE `sottocategorie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_sottocat_del` (`id_categoria`);

--
-- Indici per le tabelle `template`
--
ALTER TABLE `template`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_utente` (`username_utente`),
  ADD UNIQUE KEY `estremi_documento_utente` (`estremi_documento_utente`,`telefono_utente`);

--
-- Indici per le tabelle `voti`
--
ALTER TABLE `voti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voto_utente_del` (`id_utente_voto`),
  ADD KEY `voto_articolo_del` (`id_articolo_voto`);

--
-- Indici per le tabelle `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_abbonamento_del` (`id_abbonamento`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `abbonamenti`
--
ALTER TABLE `abbonamenti`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT per la tabella `abbonamenti_utenti`
--
ALTER TABLE `abbonamenti_utenti`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `articoli`
--
ALTER TABLE `articoli`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `autori`
--
ALTER TABLE `autori`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `commenti`
--
ALTER TABLE `commenti`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `sottocategorie`
--
ALTER TABLE `sottocategorie`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT per la tabella `template`
--
ALTER TABLE `template`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `voti`
--
ALTER TABLE `voti`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `voucher`
--
ALTER TABLE `voucher`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `abbonamenti_utenti`
--
ALTER TABLE `abbonamenti_utenti`
  ADD CONSTRAINT `abbonamenti_tipo_del` FOREIGN KEY (`id_abbonamento`) REFERENCES `abbonamenti` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `abbonamenti_u_del` FOREIGN KEY (`id_utente_abbonamento`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `articoli`
--
ALTER TABLE `articoli`
  ADD CONSTRAINT `articoli_blog_del` FOREIGN KEY (`id_blog`) REFERENCES `blog` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `articoli_utente_del` FOREIGN KEY (`id_utente_articolo`) REFERENCES `utenti` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Limiti per la tabella `autori`
--
ALTER TABLE `autori`
  ADD CONSTRAINT `autori_blog_del` FOREIGN KEY (`id_blog`) REFERENCES `blog` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `autori_utente_del` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_categoria_del` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `blog_template_del` FOREIGN KEY (`id_template`) REFERENCES `template` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `blog_utente_del` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `commenti`
--
ALTER TABLE `commenti`
  ADD CONSTRAINT `commento_articolo_del` FOREIGN KEY (`id_articolo_commento`) REFERENCES `articoli` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `commento_utente_del` FOREIGN KEY (`id_utente_commento`) REFERENCES `utenti` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Limiti per la tabella `sottocategorie`
--
ALTER TABLE `sottocategorie`
  ADD CONSTRAINT `categoria_sottocat_del` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `voti`
--
ALTER TABLE `voti`
  ADD CONSTRAINT `voto_articolo_del` FOREIGN KEY (`id_articolo_voto`) REFERENCES `articoli` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `voto_utente_del` FOREIGN KEY (`id_utente_voto`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `voucher`
--
ALTER TABLE `voucher`
  ADD CONSTRAINT `voucher_abbonamento_del` FOREIGN KEY (`id_abbonamento`) REFERENCES `abbonamenti` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
