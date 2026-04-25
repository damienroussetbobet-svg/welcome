mysqldump: [Warning] Using a password on the command line interface can be insecure.
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
  `role_label` varchar(200) DEFAULT NULL,
  `pole` varchar(100) DEFAULT 'Autre',
  `extension` varchar(20) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `initiales` varchar(5) DEFAULT NULL,
  `couleur` varchar(20) DEFAULT '#1B3A7A',
  `actif` tinyint(1) DEFAULT '1',
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agents`
--

LOCK TABLES `agents` WRITE;
/*!40000 ALTER TABLE `agents` DISABLE KEYS */;
INSERT INTO `agents` VALUES (1,'Martin Dupont','Directeur DSN','Direction','5040','m.dupont@chu-angers.fr','MD','#1B3A7A',1,0),(2,'Sophie Laurent','Responsable Adjointe','Direction','5041','s.laurent@chu-angers.fr','SL','#1B3A7A',1,1),(3,'Karim Benali','Responsable Infra','Infrastructure','5010','k.benali@chu-angers.fr','KB','#00A8D6',1,2),(4,'Ingrid Morel','Ingénieure Système','Infrastructure','5011','i.morel@chu-angers.fr','IM','#00A8D6',1,3),(5,'Thomas Roux','Technicien Réseau','Infrastructure','5012','t.roux@chu-angers.fr','TR','#00A8D6',1,4),(6,'Amara Diallo','Administratrice Système','Infrastructure','5013','a.diallo@chu-angers.fr','AD','#00A8D6',1,5),(7,'Julie Petit','Responsable Applications','Applications','5020','j.petit@chu-angers.fr','JP','#8A6FE8',1,6),(8,'Romain Garnier','Chef de projet SI','Applications','5021','r.garnier@chu-angers.fr','RG','#8A6FE8',1,7),(9,'Léa Fontaine','Développeuse','Applications','5022','l.fontaine@chu-angers.fr','LF','#8A6FE8',1,8),(10,'Hugo Bernard','Intégrateur','Applications','5023','h.bernard@chu-angers.fr','HB','#8A6FE8',1,9),(11,'Nadia Chevalier','Responsable Support','Support','5030','n.chevalier@chu-angers.fr','NC','#F5A020',1,10),(12,'Pierre Marchand','Technicien N1','Support','5031','p.marchand@chu-angers.fr','PM','#F5A020',1,11),(13,'Clara Vidal','Technicienne N1','Support','5032','c.vidal@chu-angers.fr','CV','#F5A020',1,12),(14,'Antoine Lefèvre','Technicien N2','Support','5033','a.lefevre@chu-angers.fr','AL','#F5A020',1,13),(15,'Yasmine Touati','RSSI','Sécurité','5050','y.touati@chu-angers.fr','YT','#D63030',1,14),(16,'Benoît Tissier','Analyste Sécurité','Sécurité','5051','b.tissier@chu-angers.fr','BT','#D63030',1,15);
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
  `nom` varchar(200) NOT NULL,
  `role_label` varchar(200) DEFAULT NULL,
  `initiales` varchar(5) DEFAULT NULL,
  `couleur` varchar(20) DEFAULT '#1B3A7A',
  `ordre` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `org_nodes`
--

LOCK TABLES `org_nodes` WRITE;
/*!40000 ALTER TABLE `org_nodes` DISABLE KEYS */;
INSERT INTO `org_nodes` VALUES (1,NULL,'Saber Aloui','DSN','SA','#1B3A7A',0),(2,1,'Denis Barrière','DSN Adjoint','DB','#1B3A7A',1),(3,2,'Guillaume Rousseau','Responsable infrastructure','KB','#00A8D6',2),(4,3,'Corentin Boucher','Chef de projet tehnique','CB','#00A8D6',0),(7,3,'Maxence Delgado','Responsable infra','MD','#00A8D6',1),(8,7,'Patrick Laurent','infra','PL','#00A8D6',0),(9,7,'Mickael Vaillant','infra','MV','#00A8D6',1),(10,7,'Christophe Gautret','infra','CG','#00A8D6',2),(11,3,'Maxence Delgado','Responsable Réseau','MD','#00A8D6',2),(12,11,'Aurélien Barange','Ingénieur réseau','AB','#00A8D6',0),(13,11,'Ludovic Boué','Ingénieur réseau','LB','#00A8D6',1),(14,2,'Heidi Derrien','Responsable études','HD','#8A6FE8',3),(15,14,'Heidi Derrien','Interim ( solution transverses )','HD','#8A6FE8',0),(16,15,'Joël Huaulme','Chef de projet','JH','#8A6FE8',0),(17,15,'Alain Chaussret','Chef de projet','AC','#8A6FE8',1),(18,14,'Véronique Chupin','Responsable solutions santé','VC','#8A6FE8',1),(19,18,'Bertrand DANDREL','RA','BD','#8A6FE8',0),(20,18,'David VIRION','RA','DV','#8A6FE8',1),(21,14,'Romain Bessonnet','Responsable I2D2','RB','#8A6FE8',2),(22,21,'Antony ESCUDIE','','AE','#8A6FE8',0),(23,21,'Nour IDRIS PACHA','','NDP','#8A6FE8',1),(24,3,'Nadine Jadeau','Responsable Helpdesk','NJ','#F5A020',4),(25,24,'Hervé Hernier','Technicien Helpdesk','HH','#F5A020',0),(32,2,'RSSI','Sécurité & Conformité','YT','#D63030',5),(33,32,'Analyste Sécurité','Pentest & audit','BT','#D63030',0),(34,32,'Chargé conformité','RGPD & politiques','FP','#D63030',1),(35,32,'Tech. SOC','Surveillance & alertes','MR','#D63030',2),(36,7,'Alexy Maugers','infra','AM','#00A8D6',0),(37,15,'Ida Drouin','Chef de projet','ID','#8A6FE8',2),(38,15,'Damien Rousset Bobet','RA','DRB','#8A6FE8',3),(39,15,'Mickael Gnonla','RA','MG','#8A6FE8',4),(40,15,'Dame Diouf','RA','DD','#8A6FE8',5),(41,7,'Yohann Larouelle','DBA','YL','#00A8D6',0),(42,7,'Amélie Besnard','infra','AB','#00A8D6',0),(43,21,'Lev LORAN','','LL','#8A6FE8',0),(44,21,'Eugène LEVEQUE','','EL','#8A6FE8',0),(45,21,'Olivier MOREAU','Développeur','OM','#8A6FE8',0),(46,21,'Chloé THERREAU','','CT','#8A6FE8',0),(47,21,'Marie VIVION','','MV','#8A6FE8',0),(48,7,'Arnaud EVEILLARD','infra','AE','#00A8D6',0);
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
INSERT INTO `site_config` VALUES ('bienvenue_cta','Découvrir le service →','Texte du bouton CTA'),('bienvenue_stat1_label','agents DSN','Stat 1 – Label'),('bienvenue_stat1_value','~180','Stat 1 – Valeur'),('bienvenue_stat2_label','astreintes','Stat 2 – Label'),('bienvenue_stat2_value','24h/24','Stat 2 – Valeur'),('bienvenue_stat3_label','d\'expertise','Stat 3 – Label'),('bienvenue_stat3_value','3 pôles','Stat 3 – Valeur'),('bienvenue_subtitle','DSN · Direction du Système Numérique','Sous-titre (badge)'),('bienvenue_text','Nous sommes ravis de vous accueillir. Ce livret vous donne tous les repères pour démarrer sereinement au sein de la DSN du CHU d\'Angers.','Texte d\'accueil'),('bienvenue_title','Bienvenue dans\r\nl\'équipe DSN ?','Titre principal'),('bienvenue_video','video_bienvenue_1777125874.mp4',NULL),('helpdesk_cta','Contacter l\'équipe Happyness',NULL),('helpdesk_disponibilite','lun–ven 7h30–18h30',NULL),('helpdesk_email','happyness@chu-angers.fr',NULL),('helpdesk_faq_titre','Vous n\'avez pas trouvé votre réponse ?',NULL);
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

-- Dump completed on 2026-04-25 16:20:08
