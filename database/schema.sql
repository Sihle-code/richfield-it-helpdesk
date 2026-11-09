-- MySQL dump 10.13  Distrib 8.0.46, for Win64 (x86_64)
--
-- Host: localhost    Database: richfield_helpdesk
-- ------------------------------------------------------
-- Server version	8.0.46

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `attachments`
--

DROP TABLE IF EXISTS `attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attachments` (
  `Attachment_ID` int NOT NULL AUTO_INCREMENT,
  `Ticket_ID` int NOT NULL,
  `File_Name` varchar(255) NOT NULL,
  `File_Path` varchar(255) NOT NULL,
  `Uploaded_At` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Attachment_ID`),
  KEY `Ticket_ID` (`Ticket_ID`),
  CONSTRAINT `attachments_ibfk_1` FOREIGN KEY (`Ticket_ID`) REFERENCES `tickets` (`Ticket_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attachments`
--

LOCK TABLES `attachments` WRITE;
/*!40000 ALTER TABLE `attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_log`
--

DROP TABLE IF EXISTS `audit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_log` (
  `Log_ID` int NOT NULL AUTO_INCREMENT,
  `User_ID` int NOT NULL,
  `Action` varchar(100) NOT NULL,
  `Ticket_ID` int DEFAULT NULL,
  `Logged_At` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Log_ID`),
  KEY `User_ID` (`User_ID`),
  KEY `Ticket_ID` (`Ticket_ID`),
  CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`),
  CONSTRAINT `audit_log_ibfk_2` FOREIGN KEY (`Ticket_ID`) REFERENCES `tickets` (`Ticket_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_log`
--

LOCK TABLES `audit_log` WRITE;
/*!40000 ALTER TABLE `audit_log` DISABLE KEYS */;
INSERT INTO `audit_log` VALUES (1,1,'login',NULL,'2026-11-02 22:33:08'),(2,1,'login',NULL,'2026-11-02 23:51:25'),(3,1,'logout',NULL,'2026-11-02 23:52:13'),(4,2,'login',NULL,'2026-11-02 23:52:24'),(5,2,'logout',NULL,'2026-11-02 23:52:37'),(6,3,'login',NULL,'2026-11-02 23:52:49'),(7,3,'logout',NULL,'2026-11-02 23:52:57'),(8,4,'login',NULL,'2026-11-02 23:53:08'),(9,4,'logout',NULL,'2026-11-02 23:56:47'),(10,1,'login',NULL,'2026-11-02 23:56:55'),(11,4,'login',NULL,'2026-11-03 00:21:59'),(12,4,'login',NULL,'2026-11-03 00:22:46'),(13,4,'create',1,'2026-11-03 00:24:10'),(14,4,'logout',NULL,'2026-11-03 00:24:33'),(15,2,'login',NULL,'2026-11-03 00:24:45'),(16,2,'logout',NULL,'2026-11-03 00:25:04'),(17,1,'login',NULL,'2026-11-03 00:25:10'),(18,1,'assign',1,'2026-11-03 00:25:42'),(19,1,'logout',NULL,'2026-11-03 00:26:23'),(20,3,'login',NULL,'2026-11-03 00:26:34'),(21,3,'status:Resolved',1,'2026-11-03 00:27:54'),(22,3,'logout',NULL,'2026-11-03 18:55:12'),(23,4,'login',NULL,'2026-11-03 18:55:22'),(24,4,'create',2,'2026-11-03 19:03:40'),(25,4,'auto-assign',2,'2026-11-03 19:03:45'),(26,4,'create',3,'2026-11-03 19:08:44'),(27,4,'auto-assign',3,'2026-11-03 19:08:48'),(28,4,'logout',NULL,'2026-11-03 19:18:11'),(29,1,'login',NULL,'2026-11-03 19:18:28'),(30,1,'assign',3,'2026-11-03 19:19:01'),(31,1,'assign',2,'2026-11-03 19:19:28'),(32,1,'logout',NULL,'2026-11-03 19:19:43'),(33,4,'login',NULL,'2026-11-03 19:19:53');
/*!40000 ALTER TABLE `audit_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `Category_ID` int NOT NULL AUTO_INCREMENT,
  `Category_Name` varchar(50) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Category_ID`),
  UNIQUE KEY `Category_Name` (`Category_Name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Hardware','Computers, printers, projectors'),(2,'Software','Applications and licences'),(3,'Network','Internet, Wi-Fi, LAN'),(4,'Account','Passwords and access');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feedback` (
  `Feedback_ID` int NOT NULL AUTO_INCREMENT,
  `Ticket_ID` int NOT NULL,
  `Rating` int NOT NULL,
  `Comments` text,
  `Submitted_At` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Feedback_ID`),
  UNIQUE KEY `Ticket_ID` (`Ticket_ID`),
  CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`Ticket_ID`) REFERENCES `tickets` (`Ticket_ID`),
  CONSTRAINT `feedback_chk_1` CHECK ((`Rating` between 1 and 5))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `knowledge_base`
--

DROP TABLE IF EXISTS `knowledge_base`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `knowledge_base` (
  `Article_ID` int NOT NULL AUTO_INCREMENT,
  `Category_ID` int NOT NULL,
  `Title` varchar(150) NOT NULL,
  `Content` text NOT NULL,
  `Created_By` int NOT NULL,
  `Created_At` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Article_ID`),
  KEY `Category_ID` (`Category_ID`),
  KEY `Created_By` (`Created_By`),
  CONSTRAINT `knowledge_base_ibfk_1` FOREIGN KEY (`Category_ID`) REFERENCES `categories` (`Category_ID`),
  CONSTRAINT `knowledge_base_ibfk_2` FOREIGN KEY (`Created_By`) REFERENCES `users` (`User_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `knowledge_base`
--

LOCK TABLES `knowledge_base` WRITE;
/*!40000 ALTER TABLE `knowledge_base` DISABLE KEYS */;
INSERT INTO `knowledge_base` VALUES (1,4,'Reset your campus password','Open the login page, choose Forgot password and follow the time-limited link sent to your email.',1,'2026-11-02 17:15:50'),(2,3,'Connect to campus Wi-Fi','Select the Richfield network, sign in with your student or staff number and accept the certificate.',1,'2026-11-02 17:15:50');
/*!40000 ALTER TABLE `knowledge_base` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `Notification_ID` int NOT NULL AUTO_INCREMENT,
  `User_ID` int NOT NULL,
  `Ticket_ID` int DEFAULT NULL,
  `Message` varchar(255) NOT NULL,
  `Is_Read` tinyint(1) NOT NULL DEFAULT '0',
  `Sent_At` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Notification_ID`),
  KEY `User_ID` (`User_ID`),
  KEY `Ticket_ID` (`Ticket_ID`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`),
  CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`Ticket_ID`) REFERENCES `tickets` (`Ticket_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,4,1,'Ticket submitted: RIT-2026-0001',1,'2026-11-03 00:24:05'),(2,1,1,'New ticket received: RIT-2026-0001',1,'2026-11-03 00:24:08'),(3,3,1,'New ticket assigned: RIT-2026-0001',1,'2026-11-03 00:25:38'),(4,4,1,'Your ticket RIT-2026-0001 has been assigned to a technician.',1,'2026-11-03 00:25:40'),(5,4,1,'New comment on RIT-2026-0001',1,'2026-11-03 00:27:31'),(6,4,2,'Ticket submitted: RIT-2026-0002',1,'2026-11-03 19:03:36'),(7,1,2,'New ticket received: RIT-2026-0002',0,'2026-11-03 19:03:38'),(8,3,2,'New ticket assigned: RIT-2026-0002',0,'2026-11-03 19:03:40'),(9,4,2,'Your ticket RIT-2026-0002 has been assigned to a technician.',1,'2026-11-03 19:03:43'),(10,4,3,'Ticket submitted: RIT-2026-0003',1,'2026-11-03 19:08:40'),(11,1,3,'New ticket received: RIT-2026-0003',0,'2026-11-03 19:08:42'),(12,2,3,'New ticket assigned: RIT-2026-0003',0,'2026-11-03 19:08:44'),(13,4,3,'Your ticket RIT-2026-0003 has been assigned to a technician.',1,'2026-11-03 19:08:46'),(14,2,3,'New ticket assigned: RIT-2026-0003',0,'2026-11-03 19:18:57'),(15,4,3,'Your ticket RIT-2026-0003 has been assigned to a technician.',0,'2026-11-03 19:18:59'),(16,3,2,'New ticket assigned: RIT-2026-0002',0,'2026-11-03 19:19:23'),(17,4,2,'Your ticket RIT-2026-0002 has been assigned to a technician.',0,'2026-11-03 19:19:26');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priorities`
--

DROP TABLE IF EXISTS `priorities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `priorities` (
  `Priority_ID` int NOT NULL AUTO_INCREMENT,
  `Priority_Name` varchar(20) NOT NULL,
  `Target_Response_Hours` int NOT NULL,
  PRIMARY KEY (`Priority_ID`),
  UNIQUE KEY `Priority_Name` (`Priority_Name`),
  CONSTRAINT `priorities_chk_1` CHECK ((`Priority_Name` in (_utf8mb4'Low',_utf8mb4'Medium',_utf8mb4'High',_utf8mb4'Critical')))
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priorities`
--

LOCK TABLES `priorities` WRITE;
/*!40000 ALTER TABLE `priorities` DISABLE KEYS */;
INSERT INTO `priorities` VALUES (1,'Low',24),(2,'Medium',8),(3,'High',4),(4,'Critical',1);
/*!40000 ALTER TABLE `priorities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technicians`
--

DROP TABLE IF EXISTS `technicians`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `technicians` (
  `Technician_ID` int NOT NULL AUTO_INCREMENT,
  `User_ID` int NOT NULL,
  `Specialisation` varchar(50) DEFAULT NULL,
  `Is_Available` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Technician_ID`),
  UNIQUE KEY `User_ID` (`User_ID`),
  CONSTRAINT `technicians_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technicians`
--

LOCK TABLES `technicians` WRITE;
/*!40000 ALTER TABLE `technicians` DISABLE KEYS */;
INSERT INTO `technicians` VALUES (1,2,'Network',1),(2,3,'Software',1);
/*!40000 ALTER TABLE `technicians` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets` (
  `Ticket_ID` int NOT NULL AUTO_INCREMENT,
  `Ticket_Number` varchar(20) NOT NULL,
  `User_ID` int NOT NULL,
  `Technician_ID` int DEFAULT NULL,
  `Category_ID` int NOT NULL,
  `Priority_ID` int NOT NULL,
  `Title` varchar(150) NOT NULL,
  `Description` text NOT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'Open',
  `Created_At` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Assigned_At` datetime DEFAULT NULL,
  `Resolved_At` datetime DEFAULT NULL,
  `Closed_At` datetime DEFAULT NULL,
  `Article_ID` int DEFAULT NULL,
  PRIMARY KEY (`Ticket_ID`),
  UNIQUE KEY `Ticket_Number` (`Ticket_Number`),
  KEY `Article_ID` (`Article_ID`),
  KEY `Status` (`Status`),
  KEY `User_ID` (`User_ID`),
  KEY `Technician_ID` (`Technician_ID`),
  KEY `Category_ID` (`Category_ID`),
  KEY `Priority_ID` (`Priority_ID`),
  KEY `Created_At` (`Created_At`),
  CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`),
  CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`Technician_ID`) REFERENCES `technicians` (`Technician_ID`),
  CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`Category_ID`) REFERENCES `categories` (`Category_ID`),
  CONSTRAINT `tickets_ibfk_4` FOREIGN KEY (`Priority_ID`) REFERENCES `priorities` (`Priority_ID`),
  CONSTRAINT `tickets_ibfk_5` FOREIGN KEY (`Article_ID`) REFERENCES `knowledge_base` (`Article_ID`),
  CONSTRAINT `tickets_chk_1` CHECK ((`Status` in (_utf8mb4'Open',_utf8mb4'Assigned',_utf8mb4'In Progress',_utf8mb4'Resolved',_utf8mb4'Closed')))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES (1,'RIT-2026-0001',4,2,4,3,'Password issue','Invalid user name / password','Resolved','2026-11-03 00:24:05','2026-11-03 00:25:38','2026-11-03 00:27:54',NULL,NULL),(2,'RIT-2026-0002',4,2,2,3,'SMWOL not connecting','Smwol doesn\'t open, and I cannot write my exam!','Assigned','2026-11-03 19:03:36','2026-11-03 19:19:23',NULL,NULL,NULL),(3,'RIT-2026-0003',4,1,3,1,'Moodle','My Moodle is not showing any icons; everything is grayed out.','Assigned','2026-11-03 19:08:40','2026-11-03 19:18:57',NULL,NULL,NULL);
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `updates`
--

DROP TABLE IF EXISTS `updates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `updates` (
  `Update_ID` int NOT NULL AUTO_INCREMENT,
  `Ticket_ID` int NOT NULL,
  `User_ID` int NOT NULL,
  `Update_Text` text NOT NULL,
  `Is_Internal` tinyint(1) NOT NULL DEFAULT '0',
  `Created_At` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Update_ID`),
  KEY `Ticket_ID` (`Ticket_ID`),
  KEY `User_ID` (`User_ID`),
  CONSTRAINT `updates_ibfk_1` FOREIGN KEY (`Ticket_ID`) REFERENCES `tickets` (`Ticket_ID`),
  CONSTRAINT `updates_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `updates`
--

LOCK TABLES `updates` WRITE;
/*!40000 ALTER TABLE `updates` DISABLE KEYS */;
INSERT INTO `updates` VALUES (1,1,3,'Student to click reset password shown on the Richfield login page',0,'2026-11-03 00:27:31'),(2,1,3,'Student was able to login',1,'2026-11-03 00:27:54');
/*!40000 ALTER TABLE `updates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `User_ID` int NOT NULL AUTO_INCREMENT,
  `First_Name` varchar(50) NOT NULL,
  `Last_Name` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password_Hash` varchar(255) NOT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Role` varchar(20) NOT NULL,
  `Is_Active` tinyint(1) NOT NULL DEFAULT '1',
  `Failed_Attempts` int NOT NULL DEFAULT '0',
  `Locked_Until` datetime DEFAULT NULL,
  `Created_At` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`User_ID`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `Username` (`Username`),
  CONSTRAINT `users_chk_1` CHECK ((`Role` in (_utf8mb4'End User',_utf8mb4'Technician',_utf8mb4'Administrator')))
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Sihle','','admin@richfield.test','admin','$2y$10$ZmNQEeSMAHAiSW/61Ce0S.zpXoGiQ4XSKLK8mdutmrhlbXtDF999.',NULL,'Administrator',1,0,NULL,'2026-11-02 17:15:50'),(2,'Mbuso','','tech1@richfield.test','tech1','$2y$10$lkCAxpZVDBrpk5f.lN885e8PnfJC4pTQJInHBwIXm6afDnyn4TPae',NULL,'Technician',1,0,NULL,'2026-11-02 17:15:50'),(3,'Ndivhu','','tech2@richfield.test','tech2','$2y$10$Ltr/PH2KYvPDwe4v4jCEo./vHGy8fYa78MilSvHCaIG5nYiXcF/ge',NULL,'Technician',1,0,NULL,'2026-11-02 17:15:50'),(4,'Bongani','','student1@richfield.test','student1','$2y$10$/rjHADLmrYiz6a8rVDKJ7e340HMWnpka6IteducSFVhMCIAXn9Z9e',NULL,'End User',1,0,NULL,'2026-11-02 17:15:50');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-03 23:12:30
