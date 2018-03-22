-- MySQL dump 10.13  Distrib 5.7.21, for Linux (x86_64)
--
-- Host: localhost    Database: game
-- ------------------------------------------------------
-- Server version	5.7.21-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `charachters`
--

DROP TABLE IF EXISTS `charachters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `charachters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `cur_loc` int(11) NOT NULL,
  `account` varchar(50) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `posX` int(11) DEFAULT NULL,
  `posY` int(11) DEFAULT NULL,
  `basic_attack` int(11) DEFAULT NULL,
  `cur_health` int(11) DEFAULT NULL,
  `cur_exp` int(11) NOT NULL DEFAULT '0',
  `gold` int(11) NOT NULL DEFAULT '0',
  `max_health` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charachters`
--

LOCK TABLES `charachters` WRITE;
/*!40000 ALTER TABLE `charachters` DISABLE KEYS */;
INSERT INTO `charachters` VALUES (1,'test',1,'t',1,43,58,1,100,0,0,0),(2,'test',1,'test1',1,43,58,1,NULL,0,0,0),(3,'test2',1,'test1',1,50,50,1,NULL,0,0,0),(4,'solver',1,'silencer',1,50,50,1,NULL,0,0,0),(5,'omniknight',1,'silencer',1,50,50,1,100,0,0,0),(6,'qwqw',1,'qwqw',1,50,50,1,100,0,0,0),(7,'ghost',1,'maks',1,20,30,1,100,10,1,100);
/*!40000 ALTER TABLE `charachters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id_item` int(11) NOT NULL AUTO_INCREMENT,
  `id_type` int(11) NOT NULL,
  `owner` int(11) DEFAULT NULL,
  `storage` varchar(1) DEFAULT NULL,
  `id_loc` int(11) DEFAULT NULL,
  `posX` int(11) DEFAULT NULL,
  `posY` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_item`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,1,1,'c',NULL,NULL,NULL),(2,2,1,'c',NULL,NULL,NULL),(3,1,5,'c',NULL,NULL,NULL),(4,2,5,'c',NULL,NULL,NULL),(5,1,6,'c',NULL,NULL,NULL),(6,2,6,'c',NULL,NULL,NULL),(7,1,7,'c',NULL,NULL,NULL),(8,2,7,'c',NULL,NULL,NULL);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items_type`
--

DROP TABLE IF EXISTS `items_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items_type` (
  `id_type` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `attack` int(11) DEFAULT NULL,
  `armor` int(11) DEFAULT NULL,
  `attack_range` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_type`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items_type`
--

LOCK TABLES `items_type` WRITE;
/*!40000 ALTER TABLE `items_type` DISABLE KEYS */;
INSERT INTO `items_type` VALUES (1,'weapon','Ржавый мечь',10,0,2),(2,'body','Рубаха',0,5,0);
/*!40000 ALTER TABLE `items_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locations` (
  `id_loc` int(11) NOT NULL AUTO_INCREMENT,
  `name_loc` varchar(20) DEFAULT NULL,
  `description_loc` text,
  `sizeX` int(11) DEFAULT NULL,
  `sizeY` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_loc`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,'Академия','Много окон, свет из которых распространяется по всему большому залу.Эта академия предназначена для обучения новичков, желающих отправиться на рисковые приключения вглубь королевства.Убивайте мобов, получайте опыт и золото, чтобы выйти в мир полготовленным',100,100);
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `npc_loc`
--

DROP TABLE IF EXISTS `npc_loc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `npc_loc` (
  `id_obj_type` int(11) NOT NULL,
  `id_loc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `npc_loc`
--

LOCK TABLES `npc_loc` WRITE;
/*!40000 ALTER TABLE `npc_loc` DISABLE KEYS */;
INSERT INTO `npc_loc` VALUES (1,1),(2,1);
/*!40000 ALTER TABLE `npc_loc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objects`
--

DROP TABLE IF EXISTS `objects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `objects` (
  `id_obj` int(11) NOT NULL AUTO_INCREMENT,
  `id_type` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `cur_health` int(11) NOT NULL,
  `cur_armor` int(11) NOT NULL,
  `cur_attack` int(11) NOT NULL,
  `inviolability` int(11) NOT NULL,
  `cur_loc` int(11) DEFAULT NULL,
  `posX` int(11) DEFAULT NULL,
  `posY` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_obj`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objects`
--

LOCK TABLES `objects` WRITE;
/*!40000 ALTER TABLE `objects` DISABLE KEYS */;
INSERT INTO `objects` VALUES (3,1,'Мешок с сеном',30,0,0,0,1,20,50),(5,1,'Мешок с сеном',30,0,0,0,1,90,80),(8,1,'Мешок с сеном',30,0,0,0,1,25,20),(9,1,'Мешок с сеном',30,0,0,0,1,40,40),(10,1,'Мешок с сеном',30,0,0,0,1,34,16),(11,1,'Мешок с сеном',30,0,0,0,1,27,96),(13,2,'Тренировочный голем',50,1,5,0,1,13,100),(17,2,'Тренировочный голем',50,1,5,0,1,95,62),(19,2,'Тренировочный голем',50,1,5,0,1,80,100),(20,2,'Тренировочный голем',50,1,5,0,1,39,30),(22,1,'Мешок с сеном',30,0,0,0,1,28,47),(23,2,'Тренировочный голем',50,1,5,0,1,48,93),(24,2,'Тренировочный голем',50,1,5,0,1,81,59),(25,1,'Мешок с сеном',30,0,0,0,1,26,75);
/*!40000 ALTER TABLE `objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objects_type`
--

DROP TABLE IF EXISTS `objects_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `objects_type` (
  `id_type` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `health` int(11) DEFAULT NULL,
  `armor` int(11) DEFAULT NULL,
  `attack` int(11) DEFAULT NULL,
  `inviolability` int(11) DEFAULT NULL,
  `exp` int(11) NOT NULL DEFAULT '0',
  `gold` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_type`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objects_type`
--

LOCK TABLES `objects_type` WRITE;
/*!40000 ALTER TABLE `objects_type` DISABLE KEYS */;
INSERT INTO `objects_type` VALUES (1,'Мешок с сеном',30,0,0,0,10,1),(2,'Тренировочный голем',50,1,5,0,25,3);
/*!40000 ALTER TABLE `objects_type` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-03-22 16:54:29
