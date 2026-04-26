-- MySQL dump 10.13  Distrib 5.7.39, for osx11.0 (x86_64)
--
-- Host: 127.0.0.1    Database: welcome
-- ------------------------------------------------------
-- Server version	5.7.39

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
-- Table structure for table `agents`
--

DROP TABLE IF EXISTS `agents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(200) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `role_label` varchar(200) DEFAULT NULL,
  `pole` varchar(100) DEFAULT 'Autre',
  `extension` varchar(20) DEFAULT NULL,
  `poste2` varchar(20) DEFAULT NULL,
  `numero_long` varchar(30) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `initiales` varchar(5) DEFAULT NULL,
  `couleur` varchar(20) DEFAULT '#1B3A7A',
  `actif` tinyint(1) DEFAULT '1',
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agents`
--

LOCK TABLES `agents` WRITE;
/*!40000 ALTER TABLE `agents` DISABLE KEYS */;
INSERT INTO `agents` VALUES (1,'ALNET','Thierry','','Etudes et ingénierie des solutions numériques','55894','','','ThAlnet@chu-angers.fr','TA','#1B3A7A',1,1),(2,'ALOUI','Saber','DSN','Direction','55849','45494','','Saber.Aloui@chu-angers.fr','SA','#1B3A7A',1,2),(3,'ANDRIEU','Sophie','','Direction','54654','','','InfoEtTelecoms@chu-angers.fr','SA','#1B3A7A',1,3),(4,'ANIL','Marie-Hélène','','DPI Sillage','','','','MarieHelene.Anil@chu-angers.fr','MA','#1B3A7A',1,4),(5,'BARAIZE','Dominique','','Téléphonie','55630','45799','06.65.80.66.05','DoBaraize@chu-angers.fr','DB','#1B3A7A',1,5),(6,'BARANGE','Aurélien','','Infrastructure','54386','44386','07.64.87.06.69','Aurelien.Barange@chu-angers.fr','AB','#1B3A7A',1,6),(7,'BARIL','Tom','','DPI Sillage','','','','Tom.Baril@chu-angers.fr','TB','#1B3A7A',1,7),(8,'BARRIERE','Denis','DSN Adjoint','Direction','54372','44372','07.63.45.84.13','Denis.Barriere@chu-angers.fr','DB','#1B3A7A',1,8),(9,'BEAUPERE','Aurélia','','DPI Sillage','','40339','06.59.47.62.59','aurelia.beaupere@chu-angers.fr','AB','#1B3A7A',1,9),(10,'BELLAL','Géraldine','','Etudes et ingénierie des solutions numériques','55640','46640','06.58.29.20.28','Geraldine.Bellal@chu-angers.fr','GB','#1B3A7A',1,10),(11,'BENARD','Amélie','','Infrastructure','57773','','','Amelie.Benard@chu-angers.fr','AB','#1B3A7A',1,11),(12,'BESSONNET','Romain','','Etudes et ingénierie des solutions numériques','','45691','06.65.80.70.29','Romain.Bessonnet@chu-angers.fr','RB','#1B3A7A',1,12),(13,'BONNEFOUX','Amanda','','Direction','56137','','','InfoEtTelecoms@chu-angers.fr','AB','#1B3A7A',1,13),(14,'BOTTOIS','Nicolas','','DPI Sillage','','41939','06.64.70.15.26','nicolas.bottois@chu-angers.fr','NB','#1B3A7A',1,14),(15,'BUREAU','Etienne','','Proxi','56142','41885','06.65.80.74.01','EtBureau@chu-angers.fr','EB','#1B3A7A',1,15),(16,'BURGEVIN','Laura','','DPI Sillage','','40335','06.60.24.81.56','laure.burgevin@chu-angers.fr','LB','#1B3A7A',1,16),(17,'CHANSON','Franck','','Etudes et ingénierie des solutions numériques','','40421','06.58.08.94.26','Franck.Chanson@chu-angers.fr','FC','#1B3A7A',1,17),(18,'CHAUSSERET','Alain','','Etudes et ingénierie des solutions numériques','57934','42797','06.65.80.75.91','AlChausseret@chu-angers.fr','AC','#1B3A7A',1,18),(19,'CHENARD','Laurent','','Poste de travail','56132','41313','06.65.80.70.75','LaChenard@chu-angers.fr','LC','#1B3A7A',1,19),(20,'CHEVILLON','Pierrick','','Infrastructure','55794','45794','06.65.80.69.61','PiChevillon@chu-angers.fr','PC','#1B3A7A',1,20),(21,'CHUPIN-PERRAULT','Veronique','','Etudes et ingénierie des solutions numériques','54389','44389','06.58.08.94.56','VeChupin@chu-angers.fr','VC','#1B3A7A',1,21),(22,'COUSSEAU','Ophelie','','DPI Sillage','54623','41037','','Ophelie.Cousseau@chu-angers.fr','OC','#1B3A7A',1,22),(23,'CROISIARD','Karine','','Etudes et ingénierie des solutions numériques','','43252','06.62.80.23.91','Karine.Croisiard@chu-angers.fr','KC','#1B3A7A',1,23),(24,'DROUIN','Ida','','Etudes et ingénierie des solutions numériques','54543','44543','06.58.08.93.12','ida.DaganoRousseau@chu-angers.fr','ID','#1B3A7A',1,24),(25,'DANDREL','Bertrand','','Etudes et ingénierie des solutions numériques','55315','45315','06.58.08.58.11','BeDandrel@chu-angers.fr','BD','#1B3A7A',1,25),(26,'DUPONT','Laurent','','Infrastructure','54928','44928','06.65.80.75.68','LaDupont@chu-angers.fr','LD','#1B3A7A',1,26),(27,'ESCUDIE','Anthony','','Etudes et ingénierie des solutions numériques','','40414','06.65.81.61.65','antony.escudie@chu-angers.fr','AE','#1B3A7A',1,27),(28,'EVEILLARD','Arnaud','','Infrastructure','57774','40464','06.59.66.71.62','Arnaud.Eveillard@chu-angers.fr','AE','#1B3A7A',1,28),(29,'EYSSARTIER','Émilie','','DPI Sillage','54623','43298','06.64.10.37.34','Emilie.Eyssartier@chu-angers.fr','EE','#1B3A7A',1,29),(30,'FREDJ','Yannis','','Poste de travail','54947','41578','06.13.86.57.87','yannis.fredj@chu-angers.fr','YF','#1B3A7A',1,30),(31,'FRESNAIS','Sébastien','','Téléphonie','55631','','06.99.52.60.56','sebastien.fresnais@chu-angers.fr','SF','#1B3A7A',1,31),(32,'GARCIA','Lunda','','DPI Sillage','55743','41115','06.68.41.39.86','LuGarcia@chu-angers.fr','LG','#1B3A7A',1,32),(33,'GAUTRET','Christophe','','Infrastructure','55898','45898','07.62.70.31.26','ChGautret@chu-angers.fr','CG','#1B3A7A',1,33),(34,'GEFFROY','Romain','','Téléphonie','56199','46199','06.67.63.82.79','RoGeffroy@chu-angers.fr','RG','#1B3A7A',1,34),(35,'GNONLA','Michaël','','Etudes et ingénierie des solutions numériques','','40561','06.62.80.24.39','mickael.gnonla@chu-angers.fr','MG','#1B3A7A',1,35),(36,'GROSSEMY','Jean-Yves','','Infrastructure','54194','','','JYGrossemy@chu-angers.fr','JG','#1B3A7A',1,36),(37,'HELLIO','Thierry','','Proxi','','42618','06.65.80.68.58','thierry.hellio@chu-angers.fr','TH','#1B3A7A',1,37),(38,'HERBAIN','Aline','','Direction','54311','','','Aline.Herbain@chu-angers.fr','AH','#1B3A7A',1,38),(39,'HERNIER','Hervé','','Helpdesk','54540','','','Herve.Hernier@chu-angers.fr','HH','#1B3A7A',1,39),(40,'HUAULME','Joel','','Etudes et ingénierie des solutions numériques','54923','44923','06.65.80.73.55','johuaulme@chu-angers.fr','JH','#1B3A7A',1,40),(41,'IDRIS PACHA','Nour','','Etudes et ingénierie des solutions numériques','','','','Nour.IdrisPacha@chu-angers.fr','NI','#1B3A7A',1,41),(42,'JADEAU','Nadine','','Helpdesk','56135','','','NaJadeau@chu-angers.fr','NJ','#1B3A7A',1,42),(43,'JEZEQUEL-HALBERT','Séverine','','DPI Sillage','','','','severine.jezequel@chu-angers.fr','SJ','#1B3A7A',1,43),(45,'JOUAN','Laurent','','DPI Sillage','','','','laurent.jouan@chu-angers.fr','LJ','#1B3A7A',1,45),(46,'KANOUTE','Alassane','','Helpdesk','','41945','06.13.86.57.13','alassane.kanoute@chu-angers.fr','AK','#1B3A7A',1,46),(47,'LAROUELLE','Yohann','','Infrastructure','54927','44927','','Yohann.Larouelle@chu-angers.fr','YL','#1B3A7A',1,47),(48,'LAURENT','Patrick','','Infrastructure','53271','43271','06.65.80.66.83','PaLaurent@chu-angers.fr','PL','#1B3A7A',1,48),(49,'LE NIVET','François','','Infrastructure','56211','','','FrLeNivet@chu-angers.fr','FL','#1B3A7A',1,49),(50,'LEMER','Alain','','Proxi','56056','','','AlLemer@chu-angers.fr','AL','#1B3A7A',1,50),(51,'LORAN','Lev','','Etudes et ingénierie des solutions numériques','','40323','06.58.08.89.24','Lev.Loran@chu-angers.fr','LL','#1B3A7A',1,51),(52,'MALNUIT','Pierre','','DPI Sillage','54623','40560','06.62.80.26.32','pierre.malnuit@chu-angers.fr','PM','#1B3A7A',1,52),(54,'MAUGARS','Adeline','','Etudes et ingénierie des solutions numériques','55743','42683','06.65.80.74.76','AdMaugars@chu-angers.fr','AM','#1B3A7A',1,54),(55,'MÉNARD-BOISSINOT','Anne-Françoise','','Etudes et ingénierie des solutions numériques','54992','43914','06.65.80.71.34','AFMenard@chu-angers.fr','AM','#1B3A7A',1,55),(56,'MOREAU','Olivier','','Etudes et ingénierie des solutions numériques','55801','45801','06.65.81.12.74','OlMoreau@chu-angers.fr','OM','#1B3A7A',1,56),(58,'OGER','Gregory','','Poste de travail','56134','','','Gregory.Oger@chu-angers.fr','GO','#1B3A7A',1,58),(59,'OSSANT','Mickaël','','Helpdesk','56304','','','mickael.ossant@chu-angers.fr','MO','#1B3A7A',1,59),(60,'OUDET','Mickaël','','Sécurité','53071','','','mickael.oudet@chu-angers.fr','MO','#1B3A7A',1,60),(61,'PAEZ','Amandine','','DPI Sillage','','40429','06.99.77.16.34','amandine.paez@chu-angers.fr','AP','#1B3A7A',1,61),(62,'PAVION','Léa','','DPI Sillage','','','','Lea.Pavion@chu-angers.fr','LP','#1B3A7A',1,62),(63,'PIONNIER','Frédéric','','Infrastructure','54491','','','Frederic.Pionnier@chu-angers.fr','FP','#1B3A7A',1,63),(64,'POCQUET','Pierre-Alain','','Sécurité','54929','44929','06.65.80.70.26','PierreAlain.Pocquet@chu-angers.fr','PP','#1B3A7A',1,64),(65,'PORTIER','Mickaël','','Proxi','56133','41754','06.65.80.74.72','MiPortier@chu-angers.fr','MP','#1B3A7A',1,65),(66,'RAIMBAULT-CHUPIN','Mélina','Accueil Pharmacie','DPI Sillage','53544','41053','','Melina.Raimbault@chu-angers.fr','MR','#1B3A7A',1,66),(67,'RIVIERE','John','','Etudes et ingénierie des solutions numériques','57654','','06.65.80.72.24','John.Riviere@chu-angers.fr','JR','#1B3A7A',1,67),(68,'ROUSSET BOBET','Damien','','Etudes et ingénierie des solutions numériques','','40495','06.99.41.24.64','Damien.RoussetBobet@chu-angers.fr','DR','#1B3A7A',1,68),(69,'SALIOU','Guillaume','','Infrastructure','55960','41030','06.65.80.74.20','Guillaume.Saliou@chu-angers.fr','GS','#1B3A7A',1,69),(70,'SIMON','Bob','','Helpdesk','56610','','','bob.simon@chu-angers.fr','BS','#1B3A7A',1,70),(71,'THESEE','Samuel','','Infrastructure','56495','40014','','samuel.thesee@chu-angers.fr','ST','#1B3A7A',1,71),(72,'THERREAU','Chloé','','Etudes et ingénierie des solutions numériques','','','06.65.80.74.09','Chloe.Therreau@chu-angers.fr','CT','#1B3A7A',1,72),(73,'TUCOULET','Martin','','Helpdesk','54542','','','martin.tucoulet@chu-angers.fr','MT','#1B3A7A',1,73),(74,'VAILLANT','Mickael','','Infrastructure','57775','47775','06.65.81.13.72','MiVaillant@chu-angers.fr','MV','#1B3A7A',1,74),(75,'VILLEBEAU','François','','Proxi','57868','41788','06.65.80.74.73','FrVillebeau@chu-angers.fr','FV','#1B3A7A',1,75),(76,'VILLOING','Olivier','','Proxi','56624','42911','06.68.44.72.83','Olivier.Villoing@chu-angers.fr','OV','#1B3A7A',1,76),(77,'VIRION','David','','Etudes et ingénierie des solutions numériques','53395','43395','06.65.81.13.67','david.virion@chu-angers.fr','DV','#1B3A7A',1,77),(78,'VIVION MASSON','Marie','','Etudes et ingénierie des solutions numériques','56685','','','MaMasson@chu-angers.fr','MM','#1B3A7A',1,78),(79,'DIOUF','Dame','','Etudes et ingénierie des solutions numériques','','','','dame.diouf@chu-angers.fr','DD','#1B3A7A',1,0),(80,'Delgado','Maxence','Responsable infrastructure','Infrastructure','','','','maxence.delgado@chu-angers.fr','','#1b3a7a',1,0),(81,'Rousseau','Guillaume','NGENIERIE ET OPERATIONS DES INFRASTRUCTURES','Infrastructure','','','','','','#1b3a7a',1,0),(82,'Derrien ','Heidi','Responsable études','Etudes et ingénierie des solutions numériques','','','','','HD','#1b3a7a',1,0),(83,'LEVEQUE','Eugène','Développeur','Etudes et ingénierie des solutions numériques','','','','','EL','#1b3a7a',1,0);
/*!40000 ALTER TABLE `agents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(200) NOT NULL,
  `role_label` varchar(200) DEFAULT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `couleur` varchar(20) DEFAULT '#1B3A7A',
  `actif` tinyint(1) DEFAULT '1',
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'Helpdesk DSN','1er niveau – support quotidien','5000','helpdesk@chu-angers.fr','#00A8D6',1,0),(2,'Astreinte informatique','Urgences hors heures ouvrées','5001','astreinte@chu-angers.fr','#D63030',1,1),(3,'Responsable Infra','Réseau, serveurs, datacenter','5010','infra@chu-angers.fr','#1B3A7A',1,2),(4,'Responsable Applicatif','Logiciels métier & DPI','5020','apps@chu-angers.fr','#8A6FE8',1,3),(5,'RSSI','Sécurité & conformité RGPD','5030','rssi@chu-angers.fr','#F5A020',1,4),(6,'Direction DSN','Direction & administration','5040','dsn@chu-angers.fr','#5CB85C',1,5);
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domaines`
--

DROP TABLE IF EXISTS `domaines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domaines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `couleur` varchar(20) NOT NULL DEFAULT '#1B3A7A',
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domaines`
--

LOCK TABLES `domaines` WRITE;
/*!40000 ALTER TABLE `domaines` DISABLE KEYS */;
INSERT INTO `domaines` VALUES (1,'Direction','#E91E63',1),(2,'Infrastructure','#00A8D6',2),(4,'Helpdesk','#F5A020',4),(5,'Sécurité','#D63030',5),(8,'Téléphonie','#1b3a7a',0),(9,'Etudes et ingénierie des solutions numériques','#795548',0),(10,'DPI Sillage','#5CB85C',9),(11,'Poste de travail','#1B3A7A',10),(12,'Proxi','#8A6FE8',11);
/*!40000 ALTER TABLE `domaines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `reponse` text,
  `actif` tinyint(1) DEFAULT '1',
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
INSERT INTO `faq` VALUES (1,'Comment accéder à ma messagerie professionnelle ?','Votre compte Outlook est créé avant votre arrivée. Accès via webmail.chu-angers.fr ou l\'application Outlook. Identifiants remis lors de l\'accueil. Problème → helpdesk (ext. 5000).',1,0),(2,'Que faire si j\'oublie mon mot de passe ?','Portail de réinitialisation sur l\'intranet, ou appelez le helpdesk (ext. 5000). Ne partagez jamais votre mot de passe.',1,1),(3,'Comment signaler une panne urgente hors heures ouvrées ?','Appelez l\'astreinte informatique (ext. 5001, 24h/24). Précisez la nature et l\'impact sur les soins.',1,2),(4,'Puis-je utiliser mon matériel personnel ?','Non – le matériel personnel n\'est pas autorisé sur le réseau interne. Pour un besoin spécifique, ouvrez une demande via GLPI.',1,3),(5,'Comment demander une formation sur un outil ?','Consultez le plan de formation DSN sur l\'intranet et parlez-en à votre responsable.',1,4),(6,'Qui contacter pour les questions RH ?','Contactez la DRH via l\'espace RH en ligne ou par téléphone (ext. 4000).',1,5);
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horaires`
--

DROP TABLE IF EXISTS `horaires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jour` varchar(200) NOT NULL,
  `horaire` varchar(200) DEFAULT NULL,
  `type_horaire` varchar(200) DEFAULT NULL,
  `couleur` varchar(20) DEFAULT '#5CB85C',
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horaires`
--

LOCK TABLES `horaires` WRITE;
/*!40000 ALTER TABLE `horaires` DISABLE KEYS */;
INSERT INTO `horaires` VALUES (1,'Lundi – Vendredi','7h30 – 18h30','Heures ouvrées','#5CB85C',0),(2,'Samedi','8h00 – 12h00','Service réduit','#F5A020',1),(3,'Dimanche & jours fériés','Astreinte uniquement','Urgences seulement','#6B7BA8',2);
/*!40000 ALTER TABLE `horaires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `infos_pratiques`
--

DROP TABLE IF EXISTS `infos_pratiques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `infos_pratiques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `contenu` text,
  `icone` varchar(100) DEFAULT 'info',
  `couleur` varchar(20) DEFAULT '#00A8D6',
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `infos_pratiques`
--

LOCK TABLES `infos_pratiques` WRITE;
/*!40000 ALTER TABLE `infos_pratiques` DISABLE KEYS */;
INSERT INTO `infos_pratiques` VALUES (1,'Badge d\'accès','Remis par la DRH à la prise de poste. Perte → sécurité ext. 9000.','badge','#00A8D6',0),(2,'Restauration','Self personnel accessible avec badge. Horaires : 11h45–13h45.','utensils','#8A6FE8',1),(3,'Stationnement','Parking agents derrière le bâtiment administratif (badge requis).','car','#F5A020',2),(4,'Poste de travail','PC et téléphone IP configurés avant votre arrivée. Problème → GLPI.','monitor','#5CB85C',3),(5,'Télétravail','1–2 jours/semaine selon accord N+1. VPN fourni. Charte sur intranet.','wifi','#1B3A7A',4),(6,'Médecine du travail','Visite obligatoire dans les 3 mois. Service santé : ext. 7500.','heart-pulse','#D63030',5);
/*!40000 ALTER TABLE `infos_pratiques` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `org_nodes`
--

DROP TABLE IF EXISTS `org_nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `org_nodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `nom` varchar(200) NOT NULL,
  `role_label` varchar(200) DEFAULT NULL,
  `initiales` varchar(5) DEFAULT NULL,
  `couleur` varchar(20) DEFAULT '#1B3A7A',
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `org_nodes`
--

LOCK TABLES `org_nodes` WRITE;
/*!40000 ALTER TABLE `org_nodes` DISABLE KEYS */;
INSERT INTO `org_nodes` VALUES (1,NULL,2,'Saber ALOUI','DSN','SA','#1B3A7A',0),(2,1,8,'Denis BARRIERE','DSN Adjoint','DB','#1B3A7A',1),(3,2,81,'Guillaume Rousseau','NGENIERIE ET OPERATIONS DES INFRASTRUCTURES','','#1b3a7a',2),(4,3,NULL,'Corentin Boucher','Chef de projet tehnique','CB','#00A8D6',0),(7,3,80,'Maxence Delgado','Responsable infrastructure','','#1b3a7a',1),(8,7,48,'Patrick LAURENT','','PL','#1B3A7A',0),(9,7,74,'Mickael VAILLANT','','MV','#1B3A7A',1),(10,7,33,'Christophe GAUTRET','','CG','#1B3A7A',2),(11,3,80,'Maxence Delgado','Responsable infrastructure','','#1b3a7a',2),(12,11,6,'Aurélien BARANGE','','AB','#1B3A7A',0),(13,11,NULL,'Ludovic Boué','Ingénieur réseau','LB','#00A8D6',1),(14,2,82,'Heidi Derrien','Responsable études','HD','#1b3a7a',3),(15,14,82,'Heidi Derrien','Responsable études','HD','#1b3a7a',0),(16,15,40,'Joel HUAULME','','JH','#1B3A7A',0),(17,15,18,'Alain CHAUSSERET','','AC','#1B3A7A',1),(18,14,21,'Veronique CHUPIN-PERRAULT','','VC','#1B3A7A',1),(19,18,25,'Bertrand DANDREL','','BD','#1B3A7A',0),(20,18,77,'David VIRION','','DV','#1B3A7A',1),(21,14,12,'Romain BESSONNET','','RB','#1B3A7A',2),(22,21,27,'Anthony ESCUDIE','','AE','#1B3A7A',0),(23,21,NULL,'Nour IDRIS PACHA','','NDP','#8A6FE8',1),(24,3,42,'Nadine JADEAU','','NJ','#1B3A7A',4),(25,24,39,'Hervé HERNIER','','HH','#1B3A7A',0),(32,2,64,'Pierre-Alain POCQUET','','PP','#1B3A7A',5),(33,32,NULL,'Analyste Sécurité','Pentest & audit','BT','#D63030',0),(34,32,NULL,'Chargé conformité','RGPD & politiques','FP','#D63030',1),(35,32,NULL,'Tech. SOC','Surveillance & alertes','MR','#D63030',2),(36,7,NULL,'Alexy Maugers','infra','AM','#00A8D6',0),(37,15,24,'Ida DROUIN','','ID','#1B3A7A',2),(38,15,68,'Damien ROUSSET BOBET','','DR','#1B3A7A',3),(39,15,35,'Michaël GNONLA','','MG','#1B3A7A',4),(40,15,79,'Dame DIOUF','','DD','#1B3A7A',5),(41,7,47,'Yohann LAROUELLE','','YL','#1B3A7A',0),(42,7,NULL,'Amélie Besnard','infra','AB','#00A8D6',0),(43,21,51,'Lev LORAN','','LL','#1B3A7A',0),(44,21,83,'Eugène LEVEQUE','Développeur','EL','#1b3a7a',0),(45,21,56,'Olivier MOREAU','','OM','#1B3A7A',0),(46,21,72,'Chloé THERREAU','','CT','#1B3A7A',0),(47,21,78,'Marie VIVION MASSON','','MM','#1B3A7A',0),(48,7,28,'Arnaud EVEILLARD','','AE','#1B3A7A',0),(49,14,7,'Tom BARIL','Chef de projet','TB','#1B3A7A',0),(50,49,43,'Séverine JEZEQUEL-HALBERT','','SJ','#1B3A7A',0);
/*!40000 ALTER TABLE `org_nodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `outils_categories`
--

DROP TABLE IF EXISTS `outils_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `outils_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categorie` varchar(200) NOT NULL,
  `icone` varchar(100) DEFAULT 'tool',
  `couleur` varchar(20) DEFAULT '#00A8D6',
  `actif` tinyint(1) DEFAULT '1',
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outils_categories`
--

LOCK TABLES `outils_categories` WRITE;
/*!40000 ALTER TABLE `outils_categories` DISABLE KEYS */;
INSERT INTO `outils_categories` VALUES (1,'Helpdesk','ticket','#00A8D6',1,0),(2,'Messagerie','mail','#8A6FE8',1,1),(3,'Supervision','activity','#F5A020',1,2),(4,'Projet','kanban','#5CB85C',1,3),(5,'Apps cliniques','stethoscope','#1B3A7A',1,4),(6,'Sécurité','lock','#D63030',1,5);
/*!40000 ALTER TABLE `outils_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `outils_items`
--

DROP TABLE IF EXISTS `outils_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `outils_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categorie_id` int(11) NOT NULL,
  `nom` varchar(300) NOT NULL,
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `categorie_id` (`categorie_id`),
  CONSTRAINT `outils_items_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `outils_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `outils_items`
--

LOCK TABLES `outils_items` WRITE;
/*!40000 ALTER TABLE `outils_items` DISABLE KEYS */;
INSERT INTO `outils_items` VALUES (1,1,'GLPI – Incidents',0),(2,1,'ServiceNow – Demandes',1),(3,2,'Outlook / Exchange',0),(4,2,'Teams',1),(5,2,'SharePoint',2),(6,3,'Centreon',0),(7,3,'Zabbix',1),(8,3,'ELK Stack',2),(9,4,'Jira',0),(10,4,'Confluence',1),(11,5,'DxCare – DPI',0),(12,5,'AGFA – Imagerie',1),(13,5,'Pharma',2),(14,6,'CyberArk',0),(15,6,'Tenable',1),(16,6,'FortiGate',2);
/*!40000 ALTER TABLE `outils_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `procedure_etapes`
--

DROP TABLE IF EXISTS `procedure_etapes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `procedure_etapes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `procedure_id` int(11) NOT NULL,
  `etape` text NOT NULL,
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `procedure_id` (`procedure_id`),
  CONSTRAINT `procedure_etapes_ibfk_1` FOREIGN KEY (`procedure_id`) REFERENCES `procedures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procedure_etapes`
--

LOCK TABLES `procedure_etapes` WRITE;
/*!40000 ALTER TABLE `procedure_etapes` DISABLE KEYS */;
INSERT INTO `procedure_etapes` VALUES (1,1,'Connectez-vous à GLPI',0),(2,1,'Créer un ticket : Assistance > Créer',1),(3,1,'Renseignez titre, catégorie, description',2),(4,1,'Joignez une capture si possible',3),(5,1,'Validez – réponse sous 4h',4),(6,2,'Faire signer la demande par votre N+1',0),(7,2,'Déposer dans ServiceNow > Accès',1),(8,2,'Joindre le formulaire signé',2),(9,2,'Délai : 48–72h ouvrées',3),(10,3,'Ouvrir une RFC dans GLPI',0),(11,3,'Validation du responsable applicatif',1),(12,3,'Planifier en CAB (Change Advisory Board)',2),(13,3,'MEP hors heures de pointe',3),(14,3,'Renseigner le compte-rendu post-déploiement',4),(15,4,'Isoler immédiatement le poste du réseau',0),(16,4,'Appeler le RSSI (liste intranet)',1),(17,4,'Ne rien supprimer, ne pas intervenir seul',2),(18,4,'Ticket GLPI : mention URGENCE SÉCURITÉ',3),(19,5,'Compléter le bon de commande (intranet)',0),(20,5,'Signature N+1',1),(21,5,'Transmettre à l\'équipe achats DSN',2),(22,5,'Délai moyen : 2–4 semaines',3);
/*!40000 ALTER TABLE `procedure_etapes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `procedures`
--

DROP TABLE IF EXISTS `procedures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `procedures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(500) NOT NULL,
  `tag` varchar(100) DEFAULT NULL,
  `couleur` varchar(20) DEFAULT '#00A8D6',
  `actif` tinyint(1) DEFAULT '1',
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procedures`
--

LOCK TABLES `procedures` WRITE;
/*!40000 ALTER TABLE `procedures` DISABLE KEYS */;
INSERT INTO `procedures` VALUES (1,'Déclarer un incident informatique','Helpdesk','#00A8D6',1,0),(2,'Demander un accès applicatif','Sécurité','#D63030',1,1),(3,'Mettre en production une évolution','Applicatif','#8A6FE8',1,2),(4,'Signaler un problème de sécurité','Sécurité','#D63030',1,3),(5,'Commander du matériel informatique','Logistique','#F5A020',1,4);
/*!40000 ALTER TABLE `procedures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ressources_categories`
--

DROP TABLE IF EXISTS `ressources_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ressources_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categorie` varchar(200) NOT NULL,
  `icone` varchar(100) DEFAULT 'link',
  `couleur` varchar(20) DEFAULT '#00A8D6',
  `actif` tinyint(1) DEFAULT '1',
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ressources_categories`
--

LOCK TABLES `ressources_categories` WRITE;
/*!40000 ALTER TABLE `ressources_categories` DISABLE KEYS */;
INSERT INTO `ressources_categories` VALUES (1,'Portails','globe','#00A8D6',1,0),(2,'Documentation','book-open','#8A6FE8',1,1),(3,'RH & formation','users','#F5A020',1,2);
/*!40000 ALTER TABLE `ressources_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ressources_liens`
--

DROP TABLE IF EXISTS `ressources_liens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ressources_liens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categorie_id` int(11) NOT NULL,
  `label` varchar(300) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `url` varchar(500) DEFAULT '#',
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `categorie_id` (`categorie_id`),
  CONSTRAINT `ressources_liens_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `ressources_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ressources_liens`
--

LOCK TABLES `ressources_liens` WRITE;
/*!40000 ALTER TABLE `ressources_liens` DISABLE KEYS */;
INSERT INTO `ressources_liens` VALUES (1,1,'Intranet CHU Angers','Portail agent','#',0),(2,1,'Portail DSN','Documentation & outils','#',1),(3,1,'GLPI – Helpdesk','Créer vos tickets','#',2),(4,2,'Wiki technique DSN','Procédures internes','#',0),(5,2,'Confluence DSN','Base de connaissance','#',1),(6,2,'Fiches RSSI','Bonnes pratiques sécu','#',2),(7,3,'Espace RH en ligne','Congés, fiches de paie','#',0),(8,3,'Plan de formation','Formations disponibles','#',1),(9,3,'Annuaire téléphonique','Tous les postes du CHU','#',2);
/*!40000 ALTER TABLE `ressources_liens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_config`
--

DROP TABLE IF EXISTS `site_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_config` (
  `cle` varchar(100) NOT NULL,
  `valeur` text,
  `label` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`cle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_config`
--

LOCK TABLES `site_config` WRITE;
/*!40000 ALTER TABLE `site_config` DISABLE KEYS */;
INSERT INTO `site_config` VALUES ('bienvenue_cta','Découvrir le service →','Texte du bouton CTA'),('bienvenue_mot_accueil','<p>Nous sommes heureux de vous accueillir parmi nous et nous espérons que vous vous plairez dans votre nouvel environnement de travail.\r\n</p><p><br></p><p>Voici quelques informations importantes pour vous aider à vous orienter et à vous familiariser avec notre service :\r\n</p><p><br></p><ul><li>Notre service informatique est responsable de la gestion et de l\'entretien de tous les systèmes informatiques de l\'établissement, y compris les ordinateurs, les serveurs, les réseaux et les logiciels. Nous travaillons en étroite collaboration avec tous les services de l\'établissement pour s\'assurer que les systèmes informatiques fonctionnent de manière efficace et sécurisée.</li><li>Vous serez amené à travailler sur divers projets informatiques, tels que la mise en place de nouveaux systèmes, la maintenance et le dépannage des systèmes existants, ou encore la formation des utilisateurs aux différents outils informatiques.</li><li>Vous aurez également la responsabilité de respecter les protocoles de sécurité informatique de l\'établissement, en veillant notamment à la confidentialité des données et à la protection contre les virus et les attaques informatiques.</li><li>Nous avons établi un certain nombre de règles et de procédures pour assurer le bon fonctionnement de notre service. Nous vous demandons de les respecter afin de contribuer à la qualité de notre travail et à la satisfaction de nos utilisateurs.</li></ul><p><br></p><p>\r\nEn cas de problème ou de question, n\'hésitez pas à vous adresser à votre manager ou à un de vos collègues. </p><p>Nous sommes là pour vous aider et vous soutenir dans votre travail.\r\n\r\nEn espérant que votre intégration se passera bien et que vous apprécierez votre nouvel environnement de travail, nous vous souhaitons une excellente année professionnelle.</p>',NULL),('bienvenue_mot_btn','',NULL),('bienvenue_mot_titre','',NULL),('bienvenue_stat1_label','agents DSN','Stat 1 – Label'),('bienvenue_stat1_value','~180','Stat 1 – Valeur'),('bienvenue_stat2_label','Postes informatique','Stat 2 – Label'),('bienvenue_stat2_value','7000','Stat 2 – Valeur'),('bienvenue_stat3_label','d\'expertise','Stat 3 – Label'),('bienvenue_stat3_value','3 pôles','Stat 3 – Valeur'),('bienvenue_subtitle','DSN · Direction du Système Numérique','Sous-titre (badge)'),('bienvenue_text','Nous sommes ravis de vous accueillir. Ce livret vous donne tous les repères pour démarrer sereinement au sein de la DSN du CHU d\'Angers.','Texte d\'accueil'),('bienvenue_title','Bienvenue dans\r\nl\'équipe DSN ?','Titre principal'),('bienvenue_video','video_bienvenue_1777125874.mp4',NULL),('helpdesk_cta','Contacter l\'équipe Happyness',NULL),('helpdesk_disponibilite','lun–ven 7h30–18h30',NULL),('helpdesk_email','happyness@chu-angers.fr',NULL),('helpdesk_faq_titre','Vous n\'avez pas trouvé votre réponse ?',NULL);
/*!40000 ALTER TABLE `site_config` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-26 18:29:42
