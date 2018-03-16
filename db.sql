-- MySQL dump 10.13  Distrib 5.7.20, for Win64 (x86_64)
--
-- Host: localhost    Database: game
-- ------------------------------------------------------
-- Server version	5.7.20

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
  `id_weapon` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charachters`
--

LOCK TABLES `charachters` WRITE;
/*!40000 ALTER TABLE `charachters` DISABLE KEYS */;
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
  PRIMARY KEY (`id_item`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,1);
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
  `attack_range` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_type`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items_type`
--

LOCK TABLES `items_type` WRITE;
/*!40000 ALTER TABLE `items_type` DISABLE KEYS */;
INSERT INTO `items_type` VALUES (1,'weapon','Ржавый мечь',10,2);
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
INSERT INTO `locations` VALUES (1,'Академия','Много окон, свет из которых распространяется по всему большому залу.\nЭта академия предназначена для обучения новичков, желающих отправиться на рисковые приключения вглубь королевства.\nУбивайте мобов, получайте опыт и золото, чтобы выйти в мир полготовленным',100,100);
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objects`
--

LOCK TABLES `objects` WRITE;
/*!40000 ALTER TABLE `objects` DISABLE KEYS */;
INSERT INTO `objects` VALUES (1,1,'Мешок с сеном',30,0,0,0,1,10,15),(2,1,'Мешок с сеном',30,0,0,0,1,20,30),(3,1,'Мешок с сеном',30,0,0,0,1,20,50),(5,1,'Мешок с сеном',30,0,0,0,1,90,80),(6,1,'Мешок с сеном',30,0,0,0,1,60,50),(7,1,'Мешок с сеном',30,0,0,0,1,60,40),(8,1,'Мешок с сеном',30,0,0,0,1,25,20);
/*!40000 ALTER TABLE `objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objects_type`
--

DROP TABLE IF EXISTS `objects_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `objects_type` (
  `id_type` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `health` int(11) DEFAULT NULL,
  `armor` int(11) DEFAULT NULL,
  `attack` int(11) DEFAULT NULL,
  `inviolability` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objects_type`
--

LOCK TABLES `objects_type` WRITE;
/*!40000 ALTER TABLE `objects_type` DISABLE KEYS */;
INSERT INTO `objects_type` VALUES (1,'Мешок с сеном',30,0,0,0);
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

-- Dump completed on 2018-03-16 16:44:49
