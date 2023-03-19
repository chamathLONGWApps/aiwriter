-- MySQL dump 10.13  Distrib 8.0.28, for Win64 (x86_64)
--
-- Host: localhost    Database: ai-writer
-- ------------------------------------------------------
-- Server version	5.7.36

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
-- Table structure for table `file_prompts`
--

DROP TABLE IF EXISTS `file_prompts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_prompts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `topic` varchar(200) NOT NULL,
  `prompt` varchar(1000) NOT NULL,
  `result` longtext,
  `status` enum('pending','inprogress','completed') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_prompts`
--

LOCK TABLES `file_prompts` WRITE;
/*!40000 ALTER TABLE `file_prompts` DISABLE KEYS */;
INSERT INTO `file_prompts` VALUES (4,10,'Tree Trimming','Write a 200 to 300 word article about tree trimming.',NULL,'completed','2023-03-18 11:42:15','2023-03-18 06:25:40'),(5,10,'Electric Bicycles','Write a 200 to 300 word article about electric bicycles.','kjbjhgjhgfjhfhjfvhgfjhjhfvjhfvhgf','completed','2023-03-18 11:42:15','2023-03-18 08:45:14'),(6,10,'Dog Grooming','Write a 200 to 300 word article about dog grooming.','kjbjhgjhgfjhfhjfvhgfjhjhfvjhfvhgf','completed','2023-03-18 11:42:15','2023-03-18 08:46:19'),(7,10,'Window Washing','Write a 200 to 300 word article about window washing.','kjbjhgjhgfjhfhjfvhgfjhjhfvjhfvhgf','completed','2023-03-18 11:42:15','2023-03-18 08:46:20'),(8,10,'Car Tire Repair','Write a 200 to 300 word article about car tire repair.','kjbjhgjhgfjhfhjfvhgfjhjhfvjhfvhgf','completed','2023-03-18 11:42:15','2023-03-18 08:46:21'),(9,10,'Landscaping Services','Write a 200 to 300 word article about landscaping services.','kjbjhgjhgfjhfhjfvhgfjhjhfvjhfvhgf','completed','2023-03-18 11:42:15','2023-03-18 08:46:50'),(10,12,'Tree Trimming','Write a 200 to 300 word article about tree trimming.','kjbjhgjhgfjhfhjfvhgfjhjhfvjhfvhgf','completed','2023-03-18 14:19:08','2023-03-18 08:49:21'),(11,12,'Electric Bicycles','Write a 200 to 300 word article about electric bicycles.',NULL,'inprogress','2023-03-18 14:19:08','2023-03-18 09:01:46'),(12,12,'Dog Grooming','Write a 200 to 300 word article about dog grooming.',NULL,'inprogress','2023-03-18 14:19:08','2023-03-18 09:01:55'),(13,12,'Window Washing','Write a 200 to 300 word article about window washing.',NULL,'inprogress','2023-03-18 14:19:08','2023-03-18 09:04:24'),(14,12,'Car Tire Repair','Write a 200 to 300 word article about car tire repair.','\n\nCar tires are an essential part of any vehicle and should be routinely maintained to ensure they provide a safe and comfortable ride. Everyone who owns a vehicle should understand car tire repair, as it can help keep your car running efficiently and prolong the life of your tires.\n\nEven in normal driving conditions, tires will eventually wear down and require repair. The most common type of tire repair is patching, which can be done with a patch kit. A patch kit contains a small piece of rubber, in various sizes and shapes, that can be cut and placed over the opening in the tire, typically caused by a puncture or tear. After the patch is applied, air is added to the tire to ensure that the patch is secure and properly sealed.\n\nIf the puncture or tear is severe, a professional tire mechanic may be needed to repair the tire or, in some cases, to replace it entirely. Tire replacement should be considered when the tire has significant damage, such as deep cracks, multiple punctures, or a large tear.\n\nIn addition to puncture or tear damage, other reasons that may require tire repair include improper tire inflation, improper wheel alignment, and worn or mismatched tread. In the case of improper tire inflation or alignment, the issue can likely be fixed at a nearby garage or service shop.\n\nWhen it comes to wearing or mismatched tread, one option is to change out the tires for a new set. If a full tire replacement isn’t necessary, a more economical solution may be to have the tires rotated, addressing any tread imbalance between the tires.\n\nIn summary, car tire repair is an important part of routine maintenance for any vehicle. With proper care, many common issues can be prevented. However, there are times when the damage is too great to repair and a replacement tire is the only solution. In any case, it is important to have your tires inspected regularly for signs of wear and tear and to address any issues as soon as possible to protect you and your passengers from any potential danger.','completed','2023-03-18 14:19:08','2023-03-18 09:06:00'),(15,12,'Landscaping Services','Write a 200 to 300 word article about landscaping services.',NULL,'pending','2023-03-18 14:19:08','2023-03-18 09:01:08');
/*!40000 ALTER TABLE `file_prompts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-03-18 18:15:15
